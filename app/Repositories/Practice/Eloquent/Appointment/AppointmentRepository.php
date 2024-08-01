<?php

namespace App\Repositories\Practice\Eloquent\Appointment;

use App\Filters\Appointment\AppointmentID;
use App\Filters\Appointment\Date;
use App\Filters\Appointment\DoctorFirstName;
use App\Filters\Appointment\DoctorSpecialization as AppointmentDoctorSpecialization;
use App\Filters\Appointment\PatientFirstName;
use App\Filters\Appointment\Search;
use App\Filters\Appointment\Status;
use App\Filters\Doctor\DoctorLastName;
use App\Filters\Doctor\FirstName;
use App\Filters\Doctor\MiddleName;
use App\Filters\Specialization\Name;
use App\Helper\Appointment as HelperAppointment;
use App\Helper\Helper;
use App\Http\Resources\SlotResource;
use App\Jobs\Appointment\DoctorAppointment as AppointmentDoctorAppointment;
use App\Jobs\Appointment\PatientAppointment as AppointmentPatientAppointment;
use App\Jobs\Doctor\AppointmentRescheduledNotification;
use App\Jobs\Patient\NewAppointmentNotification;
use App\libs\Messages\AppointmentGlobalMessageBook as AGMBook;
use App\Models\Appointment\Appointment;
use App\Models\Doctor\Doctor;
use App\Models\Doctor\DoctorPractice;
use App\Models\Doctor\DoctorSlot;
use App\Models\Doctor\DoctorSpecialization;
use App\Models\Doctor\Specialization;
use App\Models\Patient\MedicalProblem;
use App\Models\Patient\Patient;
use App\Models\Practice\Practice;
use App\Models\Practice\PracticePatient;
use App\Models\User\User;
use App\Notifications\Doctor\AppointmentRescheduled;
use App\Notifications\Doctor\NewAppointment;
use App\Repositories\Practice\Interfaces\Appointment\AppointmentRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\gCalendarController;
use App\Http\Controllers\oCalendarController;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    use RespondsWithHttpStatus;

    public function __construct(gCalendarController $gCalendarController,oCalendarController $oCalendarController)
    {
        $this->gCalendarController = $gCalendarController;
        $this->oCalendarController = $oCalendarController;
    }

    /**
     *  Description: This function create appointment and send email patient & doctor
     *  1) This method is used to create appointment
     *  2) If fields not validated,field is required message will return
     *  3) In case of fields validated , create appointment
     *  4) Use helper function & send email doctor & patient
     *  5) Doctor and patient are notified about new appointment
     *  6) Activity is logged, and a success message is return
     * @param  mixed $request
     * @return Response
     */
    public function createAppointment($request): Response
    {
         $practice_id = Auth::guard('practice-api')->user()->id;

        // $event = Event::get();
        $appointmentExist = Appointment::where(['practice_id' =>  $practice_id, 'doctor_id' => $request['doctor_id'], 'start_time' => $request['start_time'], 'end_time' => $request['end_time'], 'date' => $request['date']])
            ->where('status', '!=', 'Cancelled')->first();
        if ($appointmentExist) {
            $message = AGMBook::FAILED['APPOINTMENT_EXIST'];
            $status = 409;
            $appointment = false;
            $success = false;
        } else {
            // check patient_id & practice_id is exists if not exists then store the practice_id & patient_id
            $patientExist = PracticePatient::where('patient_id', $request['patient_id'])
                ->where('practice_id', $practice_id)->first();
            if (!$patientExist) {
                PracticePatient::create([
                    'practice_id' => $practice_id,
                    'patient_id' => $request['patient_id'],
                    'created_by' => auth()->user()->practice_key,
                ]);
            }
            $request['practice_id'] = $practice_id;
            $request['created_by'] = auth()->user()->practice_key;

            $appointment = HelperAppointment::createAppointment($request);
            $doctor = Doctor::where('id', $appointment['doctor_id'])->first();  // find doctor details
            $patient = Patient::where('id', $appointment['patient_id'])->first(); // find patient detail
            // send email doctor and patient

            $practice = DB::SELECT("SELECT practice_registration_requests.practice_name ,
            practice_addresses.address_line_1, practice_addresses.address_line_2
        FROM practices
        INNER JOIN practice_registration_requests ON practices.practice_registration_request_id = practice_registration_requests.id
        INNER JOIN practice_addresses ON practices.id = practice_addresses.practice_id
        where practices.id = '{$practice_id}';");

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

            $appointment->patient->notify(new \App\Notifications\Patient\NewAppointment($appointment));
            $notification = DatabaseNotification::where(['notifiable_id' => $appointment->patient->id, 'read_at' => null])->where('notifiable_type', 'App\Models\Patient\Patient')->orderByDesc('created_at')->first();
            $unread_notifications_count = $appointment->patient->unreadNotifications()->count();
            $total_notifications = $appointment->patient->notifications()->count();

            dispatch(new NewAppointmentNotification($appointment, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.NEW_APPOINTMENT_PATIENT_NOTIFICATION'));

            $appointment->doctor->notify(new NewAppointment($appointment));
            $notification = DatabaseNotification::where(['notifiable_id' => $appointment->doctor->id, 'read_at' => null])->where('notifiable_type', 'App\Models\Doctor\Doctor')->orderByDesc('created_at')->first();
            $unread_notifications_count = $appointment->doctor->unreadNotifications()->count();
            $total_notifications = $appointment->doctor->notifications()->count();

            dispatch(new \App\Jobs\Doctor\NewAppointmentNotification($appointment, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.NEW_APPOINTMENT_DOCTOR_NOTIFICATION'));

            dispatch(new AppointmentPatientAppointment($appointment, $doctor, $patient, $practice))->onQueue(config('constants.PATIENT_APPOINTMENT'));

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
     *  Description: Show appointment list to practice
     *  1) This method is used to get appointment list
     *  2) If date filter is apply then show appointment list date related data
     *  3) If date filter is not  apply then show appointment list current date
     *  4) Activity is logged, and a success message is return
     * @param  mixed $request
     * @return Response
     */
    public function appointmentList($request): Response
    {
        $auth = auth()->user()->practice_id ? auth()->user()->practice_id : auth()->id();
        $query =  Appointment::where('practice_id', $auth)
            ->where('status', '!=', 'Rescheduled')
            ->with('doctor.doctorSpecializations.specializations', 'patient')
            ->latest('id');

        $appointments = app(Pipeline::class)
            ->send($query)
            ->through([
                Date::class,
                AppointmentID::class,
                Status::class,
                DoctorFirstName::class,
                PatientFirstName::class,
                AppointmentDoctorSpecialization::class,
                Search::class,
            ])
            ->thenReturn()
            ->paginate($request->pagination);
        return $this->response($request, $appointments, AGMBook::SUCCESS['APPOINTMENT_LIST_PRACTICE'], 200);
    }

    /**
     *  Description: This function reSchedule appointment and send email patient & doctor
     *  1) This method is used to create reSchedule appointment
     *  2) If appointment id is exist
     *  3) If fields not validated ,field is required message will return
     *  4) In case of fields validated , reSchedule appointment
     *  5) Use helper function & send email doctor & patient
     *  6) Doctor and patient are notified about the appointment reschedule
     *  7) Activity is logged, and a success message is return
     * @param  mixed $request
     * @param  mixed $id
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
            $practiceId = Auth::guard('practice-api')->user()->id;
            $appointmentExist = Appointment::where([
                'practice_id' => $practiceId, 'doctor_id' => $request['doctor_id'],
                'start_time' => $request['start_time'], 'end_time' => $request['end_time'], 'date' => $request['date']
            ])
                ->where(function ($query){
                    return $query->where('status', '!=' , 'Cancelled')
                        ->where('status', '!=' , 'Rescheduled');
                })
                ->first();
            if ($appointmentExist) {
                $message = AGMBook::FAILED['APPOINTMENT_EXIST'];
                $status = 409;
                $appointment = false;
                $success = false;
            } else {
                $appointment->status = "Rescheduled";
                $appointment->updated_by = auth()->user()->practice_key;
                $appointment->update();
                $previousAppointment = $appointment;

                $request['practice_id'] = auth()->id();
                $request['created_by'] = auth()->user()->practice_key;
                $appointment = HelperAppointment::createAppointment($request);
                $doctor = Doctor::where('id', $appointment['doctor_id'])->first();  // find doctor details
                $patient = Patient::where('id', $appointment['patient_id'])->first();  // find patient detail
                $practice = DB::SELECT("SELECT practice_registration_requests.practice_name,
                practice_addresses.address_line_1, practice_addresses.address_line_2 FROM practices
                INNER JOIN practice_registration_requests
                ON practices.practice_registration_request_id = practice_registration_requests.id
                INNER JOIN practice_addresses ON practices.id = practice_addresses.practice_id
                where practices.id = '{$practiceId}';");


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

                $appointment->patient->notify(new \App\Notifications\Patient\AppointmentRescheduled($previousAppointment, $appointment));
                $notification = DatabaseNotification::where(['notifiable_id' => $appointment->patient->id, 'read_at' => null])->where('notifiable_type', 'App\Models\Patient\Patient')->orderByDesc('created_at')->first();
                $unread_notifications_count = $appointment->patient->unreadNotifications()->count();
                $total_notifications = $appointment->patient->notifications()->count();

                dispatch(new \App\Jobs\Patient\AppointmentRescheduledNotification($previousAppointment, $appointment, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.APPOINTMENT_RESCHEDULED_PATIENT_NOTIFICATION'));

                $appointment->doctor->notify(new AppointmentRescheduled($previousAppointment, $appointment));
                $notification = DatabaseNotification::where(['notifiable_id' => $appointment->doctor->id, 'read_at' => null])->where('notifiable_type', 'App\Models\Doctor\Doctor')->orderByDesc('created_at')->first();
                $unread_notifications_count = $appointment->doctor->unreadNotifications()->count();
                $total_notifications = $appointment->doctor->notifications()->count();

                dispatch(new AppointmentRescheduledNotification($previousAppointment, $appointment, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.APPOINTMENT_RESCHEDULED_DOCTOR_NOTIFICATION'));

                dispatch(new AppointmentPatientAppointment($appointment, $doctor, $patient, $practice))->onQueue(config('constants.PATIENT_APPOINTMENT'));

                dispatch(new AppointmentDoctorAppointment($appointment, $doctor, $patient, $practice))->onQueue(config('constants.DOCTOR_APPOINTMENT'));
                $this->gCalendarController->store($request);
                $this->oCalendarController->event($request);


                $message = AGMBook::SUCCESS['PRACTICE_CREATE_APPOINTMENT'];
                $status = 200;
                $success = true;
            }
        }
        return $this->response($request, $appointment, $message, $status, $success);
    }

    /**  Description: This function show practice doctor list with search
     *  1) This method is used to list
     *  2) Search filter is first_name ,middle_name ,last_name
     *  3) If search filter is apply then practice doctor list show related to filter
     *  4) If search filter is not apply then show all practice doctor list
     *  5) Activity is logged, and a success message is return
     * @param  mixed $request
     * @return Response
     */
    public function practiceDoctor($request): Response
    {
        $practice = Practice::find(auth()->id());
        $doctors = app(Pipeline::class)
            ->send(Doctor::query())
            ->through([
                FirstName::class,
                MiddleName::class,
                DoctorLastName::class,
            ])
            ->thenReturn()
            ->where(function ($query) use ($practice) {
                return $query->with(['doctorPractices' => function($query) use ($practice){
                    return $query->where(['practice_id' => $practice]);
                }]);
            })
            ->latest('id')
            ->get();
        return $this->response($request, $doctors, AGMBook::SUCCESS['PRACTICE_DOCTOR_LIST'], 200);
    }

    /**
     *  Description: This function show doctor slot list with doctor slot days , doctor off days
     *  1) This method is used to list
     *  2) data is show to doctor relate
     *  3) Activity is logged, and a success message is return
     * @param  mixed $doctor_id
     * @return Response
     */
    public function doctorSlot($doctor_id): Response
    {
        $doctorSlots  = Doctor::with(['doctorSlots' => function($query){
            return $query->where('status', 1)->where('practice_id', $this->practice_id());
        }, 'doctorOffDays' => function($query) use ($doctor_id) {
            $query->where(['doctor_id' => $doctor_id, 'practice_id' => $this->practice_id()]);
        }, 'doctorSlots.doctorSlotDays:doctor_slot_id,day'])
            ->where('id', $doctor_id)
            ->first();
        $slotData = new SlotResource($doctorSlots);
        $message = AGMBook::SUCCESS['DOCTOR_SLOT_LIST'];
        $status = 200;
        $success = true;

        if (!$doctorSlots) {
            $message = AGMBook::FAILED['DOCTOR_NOT_FOUND'];
            $status = 400;
            $slotData = false;
            $success = false;
        }
        return $this->response(true, $slotData, $message, $status, $success);
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
        $practice = Practice::find(auth()->user()->practice_id ? auth()->user()->practice_id : auth()->id());
        $specialization = $specializationModel::find($id);
        if ($specialization) {
            $specializations = $specializationModel::with('doctorSpecialization')->where('id', $id)->first();
            $doctor = Doctor::whereHas('doctorSpecializations', function ($query) use ($specializations, $practice) {
                $query->where('specialization_id', $specializations['id'])
                    ->where(function ($query) use ($practice) {
                    return $query->with(['doctorPractices' => function($query) use ($practice) {
                        return $query->where(['practice_id' => $practice['id'], 'doctor_status_in_practice' => true]);
                    }]);
                });
            })->has('doctorSlots')->get();

            $message = AGMBook::SUCCESS['DOCTOR_LIST'];
            $status = 200;
        } else {
            $practiceDoctors = $doctorPracticeModel::where(['practice_id' => $this->practice_id(), 'doctor_status_in_practice' => 'true'])->latest('id')->get('doctor_id');
            $doctor = Doctor::whereIn('id' , $practiceDoctors)
                ->where(['is_active' => 'true', 'kyc_status' => 'Accepted'])->latest('id')
                ->has('doctorSlots')
                ->get();
            $message = AGMBook::SUCCESS['DOCTOR_LIST'];
            $status = 200;
        }
        return $this->response($request, $doctor, $message, $status);
    }

    /**
     * Description: Get doctor specializations list
     *  1) This method is used to list doctor specializations
     *  2) If select doctor then show specializations list relate to doctor
     *  3) If doctor is not select then show all specializations list
     *  4) Activity is logged, and a success message is return
     * @param mixed $request
     * @param $specializationModel
     * @return Response
     */
    public function doctorSpecializationsList($request, $specializationModel): Response
    {
        if (!empty($request->doctor_id)) {

            $doctorSpecializations = DoctorSpecialization::where('doctor_id', $request->doctor_id)->get();
            $specializations = $specializationModel::whereIn('id', $doctorSpecializations->pluck('specialization_id'))->get();
        } else {
            $specializations = $specializationModel::latest('id')->get();
        }
        return $this->response($request, $specializations, AGMBook::SUCCESS['DOCTOR_SPECIALIZATION_LIST'], 200);
    }

    /**
     *  Description: Get medical problem list
     *  1) This method is used to list medical problem
     *  2) Activity is logged, and a success message is return
     * @return Response
     */
    public function medicalProblemList(): Response
    {
        $medicalProblemList = MedicalProblem::get();
        return $this->response(true, $medicalProblemList, AGMBook::SUCCESS['MEDICAL_PROBLEM_LIST'], 200);
    }

    /**
     * Description: Get appointment by id and date
     *  1) This method is used to list
     *  2) Activity is logged, and a success message is return
     * @param  mixed $request
     * @return Response
     */
    public function getAppointmentByIdAndDate($request): Response
    {
        $date = $request['date'];
        $appointments = DoctorSlot::with(['appointments' => function ($query) use ($date) {
            return $query->where(['date' => $date, 'practice_id' => $this->practice_id()])->where(function ($query) {
                return $query->where('status', '!=' , 'Cancelled')->where('status', '!=', 'Rescheduled');
            });
        }])
            ->where(['doctor_id' => $request['doctor_id'], 'practice_id' => $this->practice_id()])
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
     * Description: Get all stats of practice dashboard
     * get all count of
     * Total practice patients.
     * Total Active Patient.
     * Total InActive Patient.
     * Total practice doctors.
     * Total Active  doctors.
     * Total InActive  doctors.
     * Total practice Appointment.
     * Total Today Appointment.
     * Total Upcoming Appointment.
     * Total Cancelled Appointment.
     * Total Completed Appointment.
     *Total Practice Staff.
     * @return Response
     */
    public function practiceStats(): Response
    {
        $auth = auth()->user()->practice_id ? auth()->user()->practice_id : auth()->id();
        $authPracticeAppointments = Appointment::where('practice_id', $auth)->get();

        $practiceDoctors = DoctorPractice::where(['practice_id' => $auth])->get();
        $practicePatients = PracticePatient::where('practice_id', $auth)->get();

        $totalAppointment =  $authPracticeAppointments->count();
        $completeAppointment =  $authPracticeAppointments->where('status', 'Completed')->count();
        $cancelAppointment =  $authPracticeAppointments->where('status', 'Cancelled')->count();
        $confirmAppointment =  $authPracticeAppointments->where('status', 'Confirmed')->count();
        $totalPracticeDoctors =  $practiceDoctors->count();
        $totalPracticePatients = $practicePatients->count();
        $totalPracticeStaff = User::where('practice_id', $auth)->count();

        $statsData = [
            'total_practice_appointment' => $totalAppointment,
            'complete_appointment' => $completeAppointment,
            'cancel_appointment' => $cancelAppointment,
            'confirm_appointment' => $confirmAppointment,
            'total_practice_doctors' => $totalPracticeDoctors,
            'total_practice_patients' => $totalPracticePatients,
            'total_practice_staff' => $totalPracticeStaff,
        ];

        return $this->response(true, $statsData, AGMBook::SUCCESS['PRACTICE_DASHBOARD_LIST'], 200);
    }

    /**
     * Description: Pi chart for practice dashboard
     * get all Appointment
     * Total practice patients.
     * Total Upcoming Appointment.
     * Total Doctors.
     *
     * @return Response
     */
    public function practiceAppointmentPiChart(): Response
    {
        $date = date("Y-m-d");
        $auth = auth()->user()->practice_id ? auth()->user()->practice_id : auth()->id();
        $authPractice = Appointment::where('practice_id', $auth)->get();

        $practiceDoctors = DoctorPractice::where(['practice_id' => $auth])->get();
        $practicePatients = PracticePatient::where('practice_id', $auth)->get();

        $totalAppointment =  $authPractice->count();
        $upcomingAppointment =  $authPractice->where('date', '>', $date)->count();
        $totalPracticeDoctors =  $practiceDoctors->count();
        $totalPracticePatients = $practicePatients->count();

        $totalAppointment = ['name' => 'Total Appointments', 'y' => $totalAppointment];
        $upcomingAppointment = ['name' => 'Upcoming Appointments', 'y' => $upcomingAppointment];
        $totalPracticeDoctors = ['name' => 'Doctors', 'y' => $totalPracticeDoctors];
        $totalPracticePatients = ['name' => 'Patients', 'y' => $totalPracticePatients];
        $statsData = [];
        array_push($statsData, $totalAppointment, $upcomingAppointment, $totalPracticePatients,  $totalPracticeDoctors);

        return $this->response(true, $statsData, AGMBook::SUCCESS['PRACTICE_PI_CHART'], 200);
    }

    /**
     * Description: practice appointment current week appointment spline graph.
     * get all Appointment count of all week.
     * everyday appointment count of current week.
     * return all week appointment counts.
     * @return Response
     */
    public function appointmentSplineGraph(): Response
    {
        $now = CarbonImmutable::now();
        $weekStartDate = $now->startOfWeek(Carbon::MONDAY);
        $weekStartDateMonday =  $weekStartDate->format('Y-m-d');
        $weekDate2 = $weekStartDate->addDay(1)->format('Y-m-d');
        $weekDate3 = $weekStartDate->addDay(2)->format('Y-m-d');
        $weekDate4 = $weekStartDate->addDays(3)->format('Y-m-d');
        $weekDate5 = $weekStartDate->addDays(4)->format('Y-m-d');
        $weekDate6 = $weekStartDate->addDays(5)->format('Y-m-d');
        $weekEndDate = $now->endOfWeek(Carbon::SUNDAY)->format('Y-m-d');

        $auth = auth()->user()->practice_id ? auth()->user()->practice_id : auth()->id();
        $authPractice = Appointment::where('practice_id', $auth)->get();
        $mondayAppointment =  $authPractice->where('date', $weekStartDateMonday)->count();
        $tuesdayAppointment =  $authPractice->where('date', $weekDate2)->count();
        $wednesdayAppointment =  $authPractice->where('date', $weekDate3)->count();
        $thursdayAppointment =  $authPractice->where('date', $weekDate4)->count();
        $fridayAppointment =  $authPractice->where('date', $weekDate5)->count();
        $saturdayAppointment =  $authPractice->where('date', $weekDate6)->count();
        $sundayAppointment =  $authPractice->where('date', $weekEndDate)->count();
        $statsDataOfDate = [];
        array_push($statsDataOfDate, $weekStartDateMonday, $weekDate2, $weekDate3, $weekDate4, $weekDate5, $weekDate6,  $weekEndDate);
        $statsData = [];
        array_push($statsData, $mondayAppointment, $tuesdayAppointment, $wednesdayAppointment, $thursdayAppointment, $fridayAppointment, $saturdayAppointment,  $sundayAppointment);
        $appointmentCount = ['name' => 'Appointments', 'data' => $statsData, 'categories' => $statsDataOfDate];

        return $this->response(true, $appointmentCount, AGMBook::SUCCESS['PRACTICE_APPOINTMENT_WEEK_CHART'], 200);
    }

   /**
    *  Description: Show appointment list monthly base count
     *  1) This method is used to get appointment list
     *  2) Get date from request
     *  3) Data show to 15 days data
     *  4) Activity is logged, and a success message is return
     * @param  mixed $request
     * @return Response
     */
    public function appointmentListMonthlyCount($request): Response
    {
        $auth = auth()->user()->practice_id ? auth()->user()->practice_id : auth()->id();
        for ($i = 0; $i <= 60; $i++) {
            $date   = date('Y-m-d', strtotime($request->date . ' + ' . $i . ' days'));
            $appointments =  Appointment::where('practice_id', $auth)
                ->where('status', '!=', 'Rescheduled')
                ->where('date', $date)
                ->with('doctor.doctorSpecializations.specializations', 'patient')
                ->orderBy('date', 'asc');
            $data['date'] = $date;
            $data['count'] = $appointments->count();
            $dataArray[] = $data;
        }
        return $this->response($request, $dataArray, AGMBook::SUCCESS['APPOINTMENT_LIST_PRACTICE'], 200);
    }

    /**
     *  Description: Show upcoming appointment list
     *  1) This method is used to get upcoming appointment list
     *  2) Activity is logged, and a success message is return
     * @param  mixed $request
     * @return Response
     */
    public function upcomingAppointmentList($request)
    {
        $auth = auth()->user()->practice_id ? auth()->user()->practice_id : auth()->id();
        $appointments =  Appointment::where('practice_id', $auth)
        ->where('status', '!=', 'Rescheduled')
        ->whereDate('date', '>', date("Y-m-d"))
        ->orderBy('date', 'asc')->paginate($request['pagination']);

        return $this->response(true, $appointments, AGMBook::SUCCESS['APPOINTMENT_LIST_PRACTICE'], 200);
    }
}
