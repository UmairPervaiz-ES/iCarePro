<?php

namespace App\Repositories\Doctor\Eloquent\Appointment;

use App\Filters\Appointment\AppointmentID;
use App\Filters\Appointment\Date;
use App\Filters\Patient\FirstName;
use App\Filters\Patient\LastName;
use App\Filters\Patient\PhoneNumber;
use App\Filters\Patient\Search;
use App\Helper\Appointment as HelperAppointment;
use App\Http\Controllers\gCalendarController;
use App\Helper\Helper;
use App\Http\Resources\SlotResource;
use App\Jobs\Appointment\DoctorAppointment as AppointmentDoctorAppointment;
use App\Jobs\Appointment\PatientAppointment as AppointmentPatientAppointment;
use App\Jobs\Patient\AppointmentRescheduledNotification;
use App\Jobs\Patient\NewAppointmentNotification;
use App\libs\Messages\AppointmentGlobalMessageBook as AGMBook;
use App\Models\Appointment\Appointment;
use App\Models\Doctor\Doctor;
use App\Models\Doctor\DoctorSlot;
use App\Models\Patient\MedicalProblem;
use App\Models\Patient\Patient;
use App\Models\Practice\PracticePatient;
use App\Notifications\Patient\AppointmentRescheduled;
use App\Notifications\Patient\NewAppointment;
use App\Repositories\Doctor\Interfaces\Appointment\AppointmentRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\oCalendarController;
use App\Models\CalendarSyncUser;
use App\Models\ZoomAppointmentDetail;
use App\Models\ZoomCredentials;
use GuzzleHttp\Exception\RequestException;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    use RespondsWithHttpStatus;
    public $gController;

    private mixed $doctor_id;

    public function __construct(Request $request, gCalendarController $gCalendarController, oCalendarController $oCalendarController)
    {
        $this->doctor_id = Helper::doctor_id($request);
        $this->gCalendarController = $gCalendarController;
        $this->oCalendarController = $oCalendarController;
    }

    /**
     *  Description: Show appointment list to practice
     *  1) This method is used to get practice appointment list
     *  2) If date filter is apply then show appointment list date related data
     *  3) If date filter is not  apply then show appointment list current date
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
            ->where(['doctor_id' => $this->doctor_id, 'practice_id' => $this->practice_id()])
            ->where('status', '!=', 'Rescheduled')
            ->with('doctor.doctorSpecializations.specializations', 'patient', 'zoomDetail:id,appointment_id,start_url')
            ->latest('id')
            ->paginate($request['pagination']);
        return $this->response($request, $appointments, AGMBook::SUCCESS['APPOINTMENT_LIST_DOCTOR'], 200);
    }


    /**
     *  Description: This function performs to Doctor Slot list with doctor off days
     *  1) This method is used to list
     *  2) When logged in doctor get id show data relate to id
     *  3) Use collection to get data
     *  4) Activity is logged, and a success message is return
     * @return Response
     */
    public function doctorSlot(): Response
    {
        $doctorSlots  = Doctor::with(['doctorSlots' => function ($query) {
            return $query->where('status', 1)->where('practice_id', $this->practice_id());
        }, 'doctorOffDays' => function($query){
            $query->where(['doctor_id' => $this->doctor_id, 'practice_id' => $this->practice_id()]);
        }, 'doctorSlots.doctorSlotDays:doctor_slot_id,day'])
            ->where(['id' => $this->doctor_id, 'practice_id' => $this->practice_id()])
            ->get();
        $slotData = SlotResource::collection($doctorSlots);
        return $this->response(true, $slotData, AGMBook::SUCCESS['SLOT_LIST'], 200);
    }

    /**
     *  Description: This function create appointment and send email patient & doctor
     *  1) This method is used to create appointment
     *  2) If fields not validated,field is required message will return
     *  3) In case of fields validated , create appointment
     *  4) Use helper function & send email doctor & patient
     *  5) Patient is notified about new appointment
     *  6) Activity is logged, and a success message is return
     * @param  mixed $request
     * @return Response
     */
    public function createAppointment($request): Response
    {
        if ($request['id']) {
            $appointmentExist = Appointment::where(['id' => $request['id']])->where('status', '!=', 'Cancelled')->first();
            if (!$appointmentExist) {
                $message = AGMBook::FAILED['APPOINTMENT_NOT_FOUND'];
                $status = 409;
                $appointment = false;
                $success = false;
            } else {
                $medicalProblemsAdd = Appointment::where('id', $request['id'])->update(['medical_problem_id' => $request['medical_problem_id']]);
                $message = AGMBook::SUCCESS['MEDICAL_PROBLEMS_ADDED'];
                $status = 201;
                $appointment = $medicalProblemsAdd;
                $success = true;
            }
        } else {
            $appointmentExist = Appointment::where([
                'doctor_id' => $this->doctor_id,
                'practice_id' => $this->practice_id(),
                'start_time' => $request['start_time'],
                'end_time' => $request['end_time'],
                'date' => $request['date'],
            ])
                ->where('status', '!=', 'Cancelled')
                ->first();
            if ($appointmentExist) {
                $message = AGMBook::FAILED['APPOINTMENT_EXIST'];
                $status = 409;
                $appointment = false;
                $success = false;
            } else {
                $patientExist = PracticePatient::where('patient_id', $request['patient_id'])->where('practice_id', auth()->user()->practice_id)->first();
                if (!$patientExist) {
                    PracticePatient::create([
                        'practice_id' =>  auth()->user()->practice_id,
                        'patient_id' => $request['patient_id'],
                        'created_by' =>  auth()->user()->doctor_key,
                    ]);
                }
                $request['doctor_id'] = auth()->id();
                $request['created_by'] = auth()->user()->doctor_key;
                $request['practice_id'] = $this->practice_id();
                $appointment =  HelperAppointment::createAppointment($request);
                $doctor = Doctor::where('id', $request['doctor_id'])->first();  // find doctor details
                $patient = Patient::where('id', $request['patient_id'])->first();  // find patient detail


                // send email doctor and patient
                $practice = DB::SELECT("SELECT practice_registration_requests.practice_name , practice_addresses.address_line_1, practice_addresses.address_line_2
            FROM practices
            INNER JOIN practice_registration_requests ON practices.practice_registration_request_id = practice_registration_requests.id
            INNER JOIN practice_addresses ON practices.id = practice_addresses.practice_id
            where practices.id = '{$request['practice_id']}';");

                if ($doctor->zoom_status && ($request['appointment_type'] == 2)) {
                    // Set Zoom Meeting
                    Helper::bookZoomAppointment($request, $patient, $doctor, $appointment);
                }
                $appointment->patient->notify(new NewAppointment($appointment));
                $notification = DatabaseNotification::where(['notifiable_id' => $appointment->patient->id, 'read_at' => null])->where('notifiable_type', 'App\Models\Patient\Patient')->orderByDesc('created_at')->first();
                $unread_notifications_count = $appointment->patient->unreadNotifications()->count();
                $total_notifications = $appointment->patient->notifications()->count();

                dispatch(new NewAppointmentNotification($appointment, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.NEW_APPOINTMENT_PATIENT_NOTIFICATION'));

                dispatch(new AppointmentPatientAppointment($appointment, $doctor, $patient, $practice))->onQueue(config('constants.PATIENT_APPOINTMENT'));

                dispatch(new AppointmentDoctorAppointment($appointment, $doctor, $patient, $practice))->onQueue(config('constants.DOCTOR_APPOINTMENT'));
                $this->gCalendarController->store($request);
                $this->oCalendarController->event($request);

                $message = AGMBook::SUCCESS['DOCTOR_SEND_EMAIL'];
                $status = 201;
                $success = true;
            }
        }
        return $this->response($request, $appointment, $message, $status, $success);
    }

    /**
     *  Description: This function reSchedule appointment and send email patient & doctor
     *  1) This method is used to create reSchedule appointment
     *  2) If appointment id is exist
     *  3) If fields not validated ,field is required message will return
     *  4) In case of fields validated , reSchedule appointment
     *  5) Use helper function & send email doctor & patient
     *  6) Patient is notified about new appointment
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
                'doctor_id' => $this->doctor_id,
                'practice_id' => $this->practice_id(),
                'start_time' => $request['start_time'],
                'end_time' => $request['end_time'],
                'date' => $request['date']
            ])
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
                $appointment->updated_by = auth()->user()->doctor_key;
                $appointment->update();
                $previousAppointment = $appointment;

                $request['doctor_id'] = auth()->id();
                $request['created_by'] = auth()->user()->doctor_key;
                $request['practice_id'] = $this->practice_id();
                $appointment = HelperAppointment::createAppointment($request);
                $doctor = Doctor::where('id', $appointment['doctor_id'])->first();  // find doctor details
                $patient = Patient::where('id', $appointment['patient_id'])->first();  // find patient detail
                // send email doctor and patient
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

                $appointment->patient->notify(new AppointmentRescheduled($previousAppointment, $appointment));
                $notification = DatabaseNotification::where(['notifiable_id' => $appointment->patient->id, 'read_at' => null])->where('notifiable_type', 'App\Models\Patient\Patient')->orderByDesc('created_at')->first();
                $unread_notifications_count = $appointment->patient->unreadNotifications()->count();
                $total_notifications = $appointment->patient->notifications()->count();

                dispatch(new AppointmentRescheduledNotification($previousAppointment, $appointment, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.APPOINTMENT_RESCHEDULED_PATIENT_NOTIFICATION'));

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

    /**
     *  Description: This function show  patient list with search
     *  1) This method is used to list
     *  2) Search filter is phone_number & first_name
     *  3) Get phone number & first name from search
     *  4) If search filter is not apply then show all data
     *  5) If search filter is apply then show filter related data
     *  6) Activity is logged, and a success message is return
     * @param mixed $request
     * @return Response
     */
    public function patientList($request): Response
    {
        $patient = app(Pipeline::class)
            ->send(Patient::query())
            ->through([
                PhoneNumber::class,
                FirstName::class,
                LastName::class,
                Search::class
            ])
            ->thenReturn()
            ->latest('id')
            ->get();
        return $this->response($request, $patient, AGMBook::SUCCESS['PATIENT_LIST'], 200);
    }

    /**
     *  Description: This function  medical problem list
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
     * Description: This function performs to Doctor appointment list by id and date
     *  1) This method is used to list
     *  2) Get date form request
     *  3) Activity is logged, and a success message is return
     * @param mixed $request
     * @return Response
     */
    public function getAppointmentByIdAndDate($request): Response
    {
        $date = $request['date'];
        $appointments = DoctorSlot::with(['appointments' => function ($query) use ($date) {
            return $query->where('date', $date)->where(function ($query) {
                return $query->where('status', '!=', 'Cancelled')->where('status', '!=', 'Rescheduled');
            });
        }])
            ->where(['doctor_id' => $this->doctor_id, 'practice_id' => $this->practice_id()])
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
        for ($i = 0; $i <= 60; $i++) {
            $date   = date('Y-m-d', strtotime($request->date . ' + ' . $i . ' days'));
            $appointments =  Appointment::where(['practice_id' => $this->practice_id(), 'doctor_id' => $this->doctor_id])
                ->where('status', '!=', 'Rescheduled')
                ->where('date', $date)
                ->with('doctor.doctorSpecializations.specializations', 'patient')
                ->orderBy('date', 'asc');
            $data['date'] = $date;
            $data['count'] = $appointments->count();
            $dataArray[] = $data;
        }

        $doctor = Doctor::where('id', $this->doctor_id)->first();
        $outlookCalendar = CalendarSyncUser::select("status")->where('login_email', $doctor->primary_email)
            ->whereNotNull('calendar_email')
            ->where('sync_type', 'outlook')->first();
        $googleCalendar = CalendarSyncUser::select("status")->where('login_email', $doctor->primary_email)
            ->whereNotNull('calendar_email')
            ->where('sync_type', 'google')->first();

        return $this->response($request, ['data' => $dataArray, 'outlookCalendar' => $outlookCalendar, 'googleCalendar' => $googleCalendar], AGMBook::SUCCESS['APPOINTMENT_LIST_DOCTOR'], 200);
    }

    /**
     * Description: This function show doctor appointment list by group month
     *  1) This method is used to list
     *  2) Activity is logged, and a success message is return
     * @return Response
     */
    public function appointmentListByMonth(): Response
    {
        // Last appointment date
        $appointment = [];
        $appointment = Appointment::where(['doctor_id' => $this->doctor_id, 'practice_id' => $this->practice_id()])
            ->where('date', '>=', date("Y-m-d"))
            ->where('status', '!=', 'Rescheduled')
            ->when('date' == date("Y-m-d"), function ($query) {
                return $query->where('start_time', '>', date("H:i:s"));
            })
            ->with('practice.initialPractice:id,practice_name', 'doctor:id,first_name,middle_name,last_name', 'zoomDetail:id,appointment_id,start_url')
            ->orderBy('date', 'ASC')
            ->get()
            ->groupBy(function ($val) {
                return Carbon::parse($val->date)->format("F Y");
            });
        return $this->response(true, $appointment, AGMBook::SUCCESS['APPOINTMENT_LIST_DOCTOR'], 200);
    }

    /**
     * Description: This function show doctor appointment list by group month previous date
     *  1) This method is used to list
     *  2) Activity is logged, and a success message is return
     * @return Response
     */
    public function appointmentListByMonthToPreviousDate(): Response
    {
        // Previous date
        $appointment = [];
        // Last appointment date

        $appointment = Appointment::where(['practice_id' => $this->practice_id(), 'doctor_id' => $this->doctor_id])
            ->where('date', '<=', date("Y-m-d"))
            ->where('status', '!=', 'Rescheduled')
            ->when('date' == date("Y-m-d"), function ($query) {
                return $query->where('end_time', '<', date("H:i:s"));
            })
            ->with('practice.initialPractice:id,practice_name', 'doctor:id,first_name,middle_name,last_name')
            ->orderBy('date', 'ASC')
            ->get()
            ->groupBy(function ($val) {
                return Carbon::parse($val->date)->format("F Y");
            });
        return $this->response(true, $appointment, AGMBook::SUCCESS['APPOINTMENT_LIST_DOCTOR'], 200);
    }



    public function createZoomUser($request)
    {
        $zoom  = ZoomCredentials::where('id', 1)->first();
        $doctor = Doctor::where('id', auth()->id())->first();  // find doctor details

        $accessToken = $zoom->access_token;

        if ($zoom->token_updated_at <= Carbon::now()->subHour()) {
            // Token has expired (1 hour)
            $accessToken = Helper::refreshToken();
        }

        $zoomEmail =  $doctor->primary_email;

        if ($request['zoom_email']) {
            $zoomEmail = $request['zoom_email'];
        }

        $endPoint = 'https://api.zoom.us/v2/users';
        $requestBody = [
            'action' => 'create',
            'user_info' => [
                'email' => $zoomEmail,
                'first_name' => $doctor->first_name,
                'last_name' => $doctor->last_name,
                'password' => '123456',
                'type' => 1,
                'feature' => [
                    'zoom_phone' => false,
                ],
            ],
        ];

        $response = Helper::makeHttpPostRequest($endPoint, $accessToken, [], $requestBody);
        if ($response->getStatusCode() == 201) {
            $responseData = json_decode($response->getBody()->getContents());
            $doctor->update(['zoom_status' => 1, 'zoom_user_id' => $responseData->id, 'zoom_email' => $zoomEmail]);

            $code = 200;
            $message = AGMBook::SUCCESS['DOCTOR_ZOOM_ACCOUNT_CREATED'];
        } else {
            $code = $response->getStatusCode();

            $message = AGMBook::FAILED['DOCTOR_ZOOM_ACCOUNT_FAILED'];
        }

        return $this->response(true, true, $message, $code);
    }

      /**
     * Description: This function show list of all appointment and date wise appointment list those create by *
     *  doctor with patient details and also search by appointment id
     *  1) This method is used to list
     *  2) Activity is logged, and a success message is return
     * @return Response
     */

    public function patientAppointmentListForDoctor($request, $appointmentModel): Response
    {
        // $this->doctor_id = Helper::doctor_id($request);
        $month  =  date('m', strtotime($request['date']));  // get month  : 07
        $year   =  date('Y', strtotime($request['date']));  // get year  : 2022

        if (isset($request['appointment_key'])) {
            $appointments = app(Pipeline::class)
                ->send($appointmentModel::query())
                ->through([
                    AppointmentID::class,
                ])
                ->thenReturn()
                ->with('doctor:id,first_name,middle_name,last_name')
                ->where(['doctor_id' => $this->doctor_id, 'practice_id' => $this->practice_id()])
                ->where('patient_id', $request['patient_id'])
                ->get();
        } else {
            $appointments = $appointmentModel::with('doctor:id,first_name,middle_name,last_name')
                ->where(['doctor_id' => $this->doctor_id, 'practice_id' => $this->practice_id()])
                ->where('patient_id', $request['patient_id'])
                ->whereYear('date', '=',  $year)       // check by year
                ->whereMonth('date', '=', $month)      // check by month
                ->orderBy('date', 'ASC')
                ->orderBy('start_time', 'ASC')
                ->get();
        }
        return $this->response(true, $appointments, AGMBook::SUCCESS['APPOINTMENT_LIST_DOCTOR'], 200);
    }
}
