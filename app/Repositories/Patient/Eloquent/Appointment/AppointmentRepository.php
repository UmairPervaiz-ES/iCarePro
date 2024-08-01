<?php

namespace App\Repositories\Patient\Eloquent\Appointment;

use App\Filters\Appointment\Date;
use App\Filters\Doctor\DoctorLastName;
use App\Filters\Doctor\FirstName;
use App\Filters\Doctor\MiddleName;
use App\Filters\Specialization\Name;
use App\Helper\Appointment as HelperAppointment;
use App\Http\Resources\SlotResource;
use App\Jobs\Appointment\DoctorAppointment as AppointmentDoctorAppointment;
use App\Jobs\Appointment\PatientAppointment as AppointmentPatientAppointment;
use App\Jobs\Doctor\AppointmentRescheduledNotification;
use App\Jobs\Doctor\NewAppointmentNotification;
use App\libs\Messages\AppointmentGlobalMessageBook as AGMBook;
use App\Models\Appointment\Appointment;
use App\Models\Doctor\Doctor;
use App\Models\Doctor\DoctorSlot;
use App\Models\Doctor\Specialization;
use App\Models\Patient\MedicalProblem;
use App\Models\Patient\Patient;
use App\Models\Practice\InitialPractice;
use App\Models\Practice\Practice;
use App\Models\Practice\PracticePatient;
use App\Notifications\Doctor\AppointmentRescheduled;
use App\Notifications\Doctor\NewAppointment;
use App\Repositories\Patient\Interfaces\Appointment\AppointmentRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Filters\Appointment\AppointmentID;
use Illuminate\Support\Str;
use App\Http\Controllers\gCalendarController;
use App\Http\Controllers\oCalendarController;
use App\Http\Resources\Practice\PracticeListCollection;
use App\Models\CalendarSyncUser;
use App\Helper\Helper;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    use  RespondsWithHttpStatus;

    public function __construct(gCalendarController $gCalendarController , oCalendarController $oCalendarController )
    {
        $this->gCalendarController = $gCalendarController;
        $this->oCalendarController = $oCalendarController;

    }

    /**
     *  Description: This function appointment list
     *  1) This method is used to list
     *  2) If date filter is apply then show appointment list date related data
     *  3) If date filter is not apply then show appointment list current date
     *  4) Activity is logged, and a success message is return
     * @param mixed $request
     * @return Response
     */
    public function appointmentList($request): Response
    {
        $appointments = app(Pipeline::class)
            ->send(Appointment::query())
            ->through([
                Date::class,
            ])
            ->thenReturn()
            ->where('patient_id', auth()->id())
            ->with('doctor')
            ->latest('id')
            ->paginate($request->pagination);
        return $this->response($request, $appointments, AGMBook::SUCCESS['APPOINTMENT_LIST_PATIENT'], 200);
    }

    /**
     *  Description: This function reSchedule appointment and send email patient & doctor
     *  1) This method is used to create reSchedule appointment
     *  2) If appointment id is exist
     *  3) If fields not validated ,field is required message will return
     *  4) In case of fields validated , reSchedule appointment
     *  5) Use helper function & send email doctor & patient
     *  6) Doctor is notified about appointment status
     *  7) Activity is logged, and a success message is return
     * @param mixed $request
     * @return Response
     */
    public function reSchedule($request): Response
    {
        $appointment = Appointment::find($request['id']);
        if (!$appointment) {
            $message = AGMBook::FAILED['APPOINTMENT_NOT_FOUND'];
            $status = 400;
            $success = false;
        }
         elseif($appointment['status'] == "Completed"|| $appointment['status'] == "Cancelled"  ){
            $message = AGMBook::FAILED['APPOINTMENT_NOT_RESCHEDULE'];
            $status = 409;
            $appointment = false;
            $success = false;
        }
         elseif ($appointment) {
            $appointmentExist = Appointment::where([
                'patient_id' => Auth::guard('patient-api')->user()->id,
                'doctor_id' => $request['doctor_id'],
                'start_time' => $request['start_time'],
                'end_time' => $request['end_time'],
                'date' => $request['date']])
                ->where(function ($query) {
                    return $query->where('status', '!=', 'Cancelled')
                        ->where('status', '!=', 'Rescheduled');
                })
                ->first();
            if ($appointmentExist) {
                $message = AGMBook::FAILED['APPOINTMENT_EXIST'];
                $status = 409;
                $appointment = false;
                $success = false;
            } else {
                $appointment->status = "Rescheduled";
                $appointment->updated_by = auth()->user()->patient_key;
                $appointment->update();
                $previousAppointment = $appointment;

                $request['patient_id'] = auth()->id();
                $request['created_by'] = auth()->user()->patient_key;
                $appointment = HelperAppointment::createAppointment($request);
                $doctor = Doctor::where('id', $appointment['doctor_id'])->first();  // find doctor details
                $patient = Patient::where('id', $appointment['patient_id'])->first();  // find patient detail
                $practice = DB::SELECT("SELECT practice_registration_requests.practice_name , practice_addresses.address_line_1, practice_addresses.address_line_2
                FROM practices
                INNER JOIN practice_registration_requests ON practices.practice_registration_request_id = practice_registration_requests.id
                INNER JOIN practice_addresses ON practices.id = practice_addresses.practice_id
                where practices.id = '{$request['practice_id']}';");

                // $query[] = 'start_time';
                // $query[] = 'end_time';
                // $query[] = 'date';
                // $query[] = 'id';


                // $icareProEventDetails = Appointment::select($query)->find($appointment['id']);

                // $icareProEventDetails['uuid'] = (string) Str::uuid();
                // HelperAppointment::getEventsICalObject($icareProEventDetails);

                if ($doctor->zoom_status && ($request['appointment_type'] == 2)) {
                    // Set Zoom Meeting
                     Helper::bookZoomAppointment($request, $patient, $doctor, $appointment);
                }

                dispatch(new AppointmentPatientAppointment($appointment, $doctor, $patient, $practice))->onQueue(config('constants.PATIENT_APPOINTMENT'));

                dispatch(new AppointmentDoctorAppointment($appointment, $doctor, $patient, $practice))->onQueue(config('constants.DOCTOR_APPOINTMENT'));
                $this->gCalendarController->store($request);
                $this->oCalendarController->event($request);


                $appointment->doctor->notify(new AppointmentRescheduled($previousAppointment, $appointment));
                $notification = DatabaseNotification::where(['notifiable_id' => $appointment->doctor->id, 'read_at' => null])->where('notifiable_type', 'App\Models\Doctor\Doctor')->orderByDesc('created_at')->first();
                $unread_notifications_count = $appointment->doctor->unreadNotifications()->count();
                $total_notifications = $appointment->doctor->notifications()->count();

                dispatch(new AppointmentRescheduledNotification($previousAppointment, $appointment, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.APPOINTMENT_RESCHEDULED_DOCTOR_NOTIFICATION'));

                $message = AGMBook::SUCCESS['PRACTICE_CREATE_APPOINTMENT'];
                $status = 200;
                $success = true;
            }
        }

        return $this->response($request, $appointment, $message, $status, $success);
    }

    /**
     * Description: This function practice list
     *  1) This method is used to list
     *  2) Activity is logged, and a success message is return
     * @return Response
     */
    public function practiceList(): Response|PracticeListCollection
    {


          $practices = Practice::select('id','practice_registration_request_id')->with('initialPractice:id,practice_name')->latest('id')->get();


          $this->response(true, $practices, AGMBook::SUCCESS['PRACTICE_LIST'], 200);

          return new PracticeListCollection($practices);
    }

    /**
     * Description: This function show practice doctor list with search
     *  1) This method is used to list
     *  2) Search filter is first_name ,middle_name ,last_name
     *  3) If search filter is apply then practice doctor list show related to filter
     *  4) If search filter is not apply then show all practice doctor list
     *  5) Activity is logged, and a success message is return
     * @param  mixed $request
     * @return Response
     */
    public function doctorList($request): Response
    {
        $doctors = app(Pipeline::class)
            ->send(Doctor::query())
            ->through([
                FirstName::class,
                MiddleName::class,
                DoctorLastName::class,
            ])
            ->thenReturn()
            ->where(function ($query) use ($request) {
                return $query->with(['doctorPractices' => function($query) use ($request){
                    return $query->where(['practice_id' => $request['practice_id']]);
                }]);
            })
            ->latest('id')
            ->get();
        return $this->response($request, $doctors, AGMBook::SUCCESS['PRACTICE_DOCTOR_LIST'], 200);
    }

    /**
     *  Description: This function create appointment and send email patient & doctor
     *  1) This method is used to create appointment
     *  2) If fields not validated,field is required message will return
     *  3) In case of fields validated , create appointment
     *  4) Use helper function & send email doctor & patient
     *  5) Doctor is notified about new appointment
     *  6) Activity is logged, and a success message is return
     * @param  mixed $request
     * @return Response
     */
    public function appointmentCreate($request): Response
    {
        $appointmentExist = Appointment::where([
            'patient_id' => Auth::guard('patient-api')->user()->id,
            'practice_id' => $request['practice_id'],
            'doctor_id' => $request['doctor_id'],
            'start_time' => $request['start_time'],
            'end_time' => $request['end_time'],
            'date' => $request['date']
        ])
            ->where('status', '!=', 'Cancelled')->first();

        if ($appointmentExist) {
            $message = AGMBook::FAILED['APPOINTMENT_EXIST'];
            $status = 409;
            $appointment = false;
            $success = false;
        } else {
            //check patient_id & practice_id is exists if not exists then store the practice_id & patient_id
            $patientExist = PracticePatient::where('patient_id', auth()->id())->where('practice_id', $request['practice_id'])->first();
            if (!$patientExist) {
                PracticePatient::create([
                    'practice_id' => $request['practice_id'],
                    'patient_id' => auth()->id(),
                    'created_by' => auth()->user()->patient_key,
                ]);
            }
            $request['patient_id'] = auth()->id();
            $request['created_by'] = auth()->user()->patient_key;
            $appointment =  HelperAppointment::createAppointment($request);
            $doctor = Doctor::where('id', $appointment['doctor_id'])->first();  // find doctor details
            $patient = Patient::where('id', $appointment['patient_id'])->first();  // find patient detail
            $practice = DB::SELECT("SELECT practice_registration_requests.practice_name , practice_addresses.address_line_1, practice_addresses.address_line_2
                FROM practices
                INNER JOIN practice_registration_requests ON practices.practice_registration_request_id = practice_registration_requests.id
                INNER JOIN practice_addresses ON practices.id = practice_addresses.practice_id
                where practices.id = '{$request['practice_id']}';");

            // $query[] = 'start_time';
            // $query[] = 'end_time';
            // $query[] = 'date';
            // $query[] = 'id';


            // $icareProEventDetails = Appointment::select($query)->find($appointment['id']);

            // $icareProEventDetails['uuid'] = (string) Str::uuid();
            // HelperAppointment::getEventsICalObject($icareProEventDetails);

            if ($doctor->zoom_status && ($request['appointment_type'] == 2)) {
                // Set Zoom Meeting
                 Helper::bookZoomAppointment($request, $patient, $doctor, $appointment);
            }
            dispatch(new AppointmentPatientAppointment($appointment, $doctor, $patient, $practice))->onQueue(config('constants.PATIENT_APPOINTMENT'));

            $appointment->doctor->notify(new NewAppointment($appointment));
            $notification = DatabaseNotification::where(['notifiable_id' => $appointment->doctor->id, 'read_at' => null])->where('notifiable_type', 'App\Models\Doctor\Doctor')->orderByDesc('created_at')->first();
            $unread_notifications_count = $appointment->doctor->unreadNotifications()->count();
            $total_notifications = $appointment->doctor->notifications()->count() ;

            dispatch(new NewAppointmentNotification($appointment, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.NEW_APPOINTMENT_DOCTOR_NOTIFICATION'));

            dispatch(new AppointmentDoctorAppointment($appointment, $doctor, $patient, $practice))->onQueue(config('constants.DOCTOR_APPOINTMENT'));

             $this->gCalendarController->store($request);
              $this->oCalendarController->event($request);

            $message = AGMBook::SUCCESS['PRACTICE_CREATE_APPOINTMENT'];
            $status = 201;
            $success = true;
        }
        return $this->response($request, $appointment, $message, $status, $success);
    }

    /**
     *  Description: This function select doctor show  specializations list
     *  1) This method is used to list
     *  2) If get doctor id then show doctor related specializations
     *  3) If not get doctor id then show all doctor
     *  4) Activity is logged, and a success message is return
     * @param $request
     * @param $doctorModel
     * @param $specializationModel
     * @return Response
     */
    public function doctorSpecializationsList($request, $doctorModel, $specializationModel): Response
    {
        $id = $request['doctor_id'];
        $doctor = $doctorModel::find($id);
        if (!empty($doctor)) {
            $doctor = $doctorModel::with('doctorSpecializations')->where('id', $id)->first();
            $specializations = $specializationModel::whereIn('id', $doctor->doctorSpecializations->pluck('specialization_id'))->get();
        } else {
            $specializations = $specializationModel::get();
        }
        return $this->response($request, $specializations, AGMBook::SUCCESS['DOCTOR_SPECIALIZATION_LIST'], 200);
    }

    /**
     *  Description: This function show specializations list with search
     *  1) This method is used to list
     *  2) If name filter is apply then show specializations list name related
     *  3) If name filter is not apply then show all specializations list
     *  4) Activity is logged, and a success message is return
     *
     * @param  mixed $request
     * @return Response
     */
    public function specializationList($request): Response
    {
        $specializations = app(Pipeline::class)
            ->send(Specialization::query())
            ->through([
                Name::class,
            ])
            ->thenReturn()
            ->latest('id')
            ->get();
        return $this->response($request, $specializations, AGMBook::SUCCESS['SPECIALIZATION_LIST'], 200);
    }

    /**
     * Description: This function is used to show doctors list against selected specializations
     *  1) This method is used to list doctors against selected specializations
     *  2) If selected specialization is present then show doctor list relate to selected specialization
     *  3) If specialization is not selected, then show all doctors list
     *  4) Only doctors having slots will be returned in response
     *  5) Activity is logged, and a success message is return
     *
     * @param mixed $request
     * @param $doctorModel
     * @param $specializationModel
     * @return Response
     */
    public function specializationsWithDoctor($request, $doctorPracticeModel, $specializationModel): Response
    {
        $id = $request['specialization_id'];
        $specialization = $specializationModel::find($id);
        if ($specialization) {
            $specializations = $specializationModel::with('doctorSpecialization')->where('id', $id)->first();
            $doctor = Doctor::whereHas('doctorSpecializations', function ($query) use ($specializations, $request) {
                $query->where('specialization_id', $specializations['id'])
                    ->where(function ($query) use ($request) {
                    return $query->with(['doctorPractices' => function($query) use ($request) {
                        return $query->where(['practice_id' => $request['id'], 'doctor_status_in_practice' => true]);
                    }]);
                });
            })->has('doctorSlots')->get();
        } else {
            $practiceDoctors = $doctorPracticeModel::where(['practice_id' => $request['practice_id'], 'doctor_status_in_practice' => 'true'])->latest('id')->get('doctor_id');
            $doctor = Doctor::whereIn('id' , $practiceDoctors)
                ->where(['is_active' => 'true', 'kyc_status' => 'Accepted'])
                ->latest('id')
                ->has('doctorSlots')
                ->get();
        }
        return $this->response($request, $doctor, AGMBook::SUCCESS['DOCTOR_LIST'], 200);
    }

    /**
     *  Description: This function show doctor slot list with doctor slot days , doctor off days
     *  1) This method is used to list
     *  2) data is show to doctor relate
     *  3) Activity is logged, and a success message is return
     * @param mixed $doctor_id
     * @param $request
     * @return Response
     */
    public function doctorSlot($doctor_id, $request): Response
    {
        $doctorSlots  = Doctor::with(['doctorSlots' => function($query) use ($doctor_id, $request){
            return $query->where(['doctor_id' => $doctor_id, 'practice_id' => $request->practice_id])->where('status', 1);
        }, 'doctorOffDays' => function($query) use ($doctor_id, $request){
            $query->where(['doctor_id' => $doctor_id, 'practice_id' => $request->practice_id]);
        }, 'doctorSlots.doctorSlotDays:doctor_slot_id,day'])
            ->where('id', $doctor_id)
            ->first();
        $slotData = new SlotResource($doctorSlots);
        $message = AGMBook::SUCCESS['DOCTOR_SLOT_LIST'];
        $status = 200;
        $success = true;

        // return new UserResource
        if (!$doctorSlots) {
            $message = AGMBook::FAILED['DOCTOR_NOT_FOUND'];
            $status = 400;
            $slotData = false;
            $success = false;
        }
        return $this->response(true, $slotData, $message, $status, $success);
    }

    /**
     *  Description: This function medical problem list
     *  1) This method is used to list
     *  2) Activity is logged, and a success message is return
     * @return Response
     */
    public function medicalProblemList(): Response
    {
        $medicalProblemList = MedicalProblem::get();
        return $this->response(true, $medicalProblemList, AGMBook::SUCCESS['MEDICAL_PROBLEM_LIST'], 200);
    }

    /**
     * Description: This function show appointment list by id and date
     *  1) This method is used to list
     *  2) Activity is logged, and a success message is return
     * @param mixed $request
     * @param $appointmentModel
     * @return Response
     */
    public function getAppointmentByIdAndDate($request, $appointmentModel): Response
    {
        $month  =  date('m', strtotime($request['date']));  // get month  : 07
        $year   =  date('Y', strtotime($request['date']));  // get year  : 2022
        if (isset($request['appointment_key'])) {
            $appointments = app(Pipeline::class)
                ->send($appointmentModel::query())
                ->through([
                    AppointmentID::class,
                ])
                ->thenReturn()
                ->where('patient_id', auth()->id())
                ->with('doctor')
                ->get();
        } else {
            $appointments = $appointmentModel::where('patient_id', auth()->id())
                ->with('doctor')
                ->whereYear('date', '=',  $year)       // check by year
                ->whereMonth('date', '=', $month)      // check by month
                ->orderBy('date', 'ASC')
                ->orderBy('start_time', 'ASC')
                ->get();
        } // get year  : 2022


        return $this->response($request, $appointments, AGMBook::SUCCESS['APPOINTMENT_LIST_PATIENT'], 200);
    }

    public function getAppointmentList($request): Response
    {
        $date = $request['date'];
        $appointments = DoctorSlot::with(['appointments' => function ($query) use ($date) {
            return $query->where('date', $date)->where(function ($query) {
                return $query->where('status', '!=', 'Cancelled')->where('status', '!=', 'Rescheduled');
            });
        }])
            ->where(['doctor_id' => $request['doctor_id'], 'practice_id' => $request['practice_id']])
            ->where('status', 1)
            ->where(function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->whereDate('date_from', '<=', $request->date)
                        ->whereDate('date_to', '>=', $request->date);
                })
                    ->orWhere(function ($query) use ($request) {
                        $query->whereDate('date_from', '<', $request->date)
                            ->whereDate('date_to', '>=', $request->date);
                    });
            })
            ->whereHas('doctorSlotDays', function ($query) use ($request) {
                return $query->where('day', Carbon::parse($request->date)->dayName);
            })
            ->get();
        return $this->response($request, $appointments, AGMBook::SUCCESS['APPOINTMENT_LIST_PRACTICE'], 200);
    }

    /**
     * Description: This function show patient appointment list by group month
     *  1) This method is used to list
     *  2) Activity is logged, and a success message is return
     * @return Response
     */
    public function appointmentListByMonth(): Response
    {
        // Last appointment date
        $appointment = [];
        $appointment = Appointment::where('patient_id', auth()->id())
            ->where('date', '>=', date("Y-m-d"))
            ->where('status', '!=', 'Rescheduled')
            ->when('date' == date("Y-m-d"), function ($query) {
                return $query->where('start_time', '>', date("H:i:s"));
            })
            ->with('practice.initialPractice:id,practice_name', 'doctor:id,first_name,middle_name,last_name', 'zoomDetail:id,appointment_id,join_url')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(function ($val) {
                return Carbon::parse($val->date)->format("F Y");
            });
            $pateint = Patient::where('id' , auth()->id())->first();
            $outlookCalendar = CalendarSyncUser::select("status")->where('login_email', $pateint->email)
            ->whereNotNull('calendar_email')
            ->where('sync_type','outlook')->first();
            $googleCalendar = CalendarSyncUser::select("status")->where('login_email', $pateint->email)
            ->whereNotNull('calendar_email')
            ->where('sync_type','google')->first();



        return $this->response(true, ['data'=>$appointment,'outlookCalendar'=>$outlookCalendar,'googleCalendar'=>$googleCalendar], AGMBook::SUCCESS['APPOINTMENT_LIST_PATIENT'], 200);
    }

    /**
     * Description: This function show patient appointment list by group month pervious date
     *  1) This method is used to list
     *  2) Activity is logged, and a success message is return
     * @return Response
     */
    public function appointmentListByMonthToPreviousDate(): Response
    {
        // Previous date
        $appointment = [];
        // Last appointment date

        $appointment = Appointment::where('patient_id', auth()->id())
            ->where('date', '<=', date("Y-m-d"))
            ->where('status', '!=', 'Rescheduled')
            ->when('date' == date("Y-m-d"), function ($query) {
                return $query->where('end_time', '<', date("H:i:s"));
            })
            ->with('practice.initialPractice:id,practice_name', 'doctor:id,first_name,middle_name,last_name')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(function ($val) {
                return Carbon::parse($val->date)->format("F Y");
            });
        return $this->response(true, $appointment, AGMBook::SUCCESS['APPOINTMENT_LIST_PATIENT'], 200);
    }
}
