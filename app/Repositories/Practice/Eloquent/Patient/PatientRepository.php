<?php

namespace App\Repositories\Practice\Eloquent\Patient;

use App\Filters\Appointment\AppointmentID;
use App\Helper\Helper;
use App\libs\Messages\AppointmentGlobalMessageBook as AGMBook;
use App\libs\Messages\PatientGlobalMessageBook;
use App\Models\Appointment\Appointment;
use App\Models\Patient\MedicalProblem;
use App\Models\Patient\Patient;
use App\Repositories\Practice\Interfaces\Patient\PatientRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;

class PatientRepository implements PatientRepositoryInterface
{
    use RespondsWithHttpStatus;

    /**
     * Function performs to Patient appointment list relate to practice & search by  appointment id and filter by year & month
     *  Description: This function patient appointment list relate to practice with search
     *  1) This method is used to list
     *  2) If get appointment_id from search then filter data base to appointment_id
     *  3) If get date  from search then filter data base to year and month
     *  4) Activity is logged, and a success message is return
     * @param  mixed $request
     * @return Response
     */
    public function patientAppointmentList($request, $appointmentModel): Response
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
                ->with('doctor:id,first_name,middle_name,last_name')
                ->where('practice_id', $this->practice_id())
                ->where('patient_id', $request['patient_id'])
                ->get();
        } else {
            $appointments = $appointmentModel::with('doctor:id,first_name,middle_name,last_name')
                ->where('practice_id', $this->practice_id())
                ->where('patient_id', $request['patient_id'])
                ->whereYear('date', '=',  $year)       // check by year
                ->whereMonth('date', '=', $month)      // check by month
                ->orderBy('date', 'ASC')
                ->orderBy('start_time', 'ASC')
                ->get();
        }
        return $this->response(true, $appointments, AGMBook::SUCCESS['APPOINTMENT_LIST_PRACTICE'], 200);
    }

    /**
     *  Description: This function appointment details with doctor and patient
     *  1) This method is used to list
     *  2) If get id (appointment id) from request
     *  4) Activity is logged, and a success message is return
     * @param  mixed $request
     * @return Response
     */
    public function patientAppointmentDetails($request): Response
    {

        $appointments = Appointment::with('doctor','patient')->where('id', $request['id']);

        if (Auth::getDefaultDriver() == 'patient-api') {
            $appointments = $appointments->where('patient_id', Auth::guard('patient-api')->user()->id);
        }
        if (Auth::getDefaultDriver() == 'practice-api') {

            $appointments = $appointments->where('practice_id', Auth::guard('practice-api')->user()->id);
        }
        if (Auth::getDefaultDriver() == 'api') {

            $appointments = $appointments->where('practice_id', Auth::guard('api')->user()->practice_id);
        }
        if (Auth::getDefaultDriver() == 'doctor-api') {
            $appointments = $appointments->where('doctor_id', Auth::guard('doctor-api')->user()->id);
        }

        if (isset($request['is_edit'])) {
            $appointments = $appointments->where('status', 'Confirmed');
        }
        $appointments = $appointments->first();
        $medicalProblem = array_map('intval', explode(',',$appointments['medical_problem_id']));
        if (sizeof($medicalProblem) > 0) {
            $appointments['medicalProblem'] = MedicalProblem::select('id', 'name')
            ->whereIn('id', $medicalProblem)->get();
        }
        return $this->response(true, $appointments, AGMBook::SUCCESS['APPOINTMENT_DETAILS'], 200);
    }

    /**
     * Description: Retrieving all patient notifications
     *  1) Response with notifications array is returned
     *
     * @param $request
     * @return Response
     */
    public function allNotifications($request): Response
    {
        $patient = Patient::with('google_calendar', 'outlook_calendar')->where('id', auth()->guard('patient-api')->id() )->first();
        $notifications = $patient->notifications()
            ->paginate($request->pagination);
        $notifications->setCollection($notifications->groupBy(
            fn($query) => Carbon::parse($query->created_at)->format('d M Y')
        ));
        $notifications['unread'] = $patient->unreadNotifications()->count();
        $notifications['google_calendar'] = $patient->google_calendar;
        $notifications['outlook_calendar'] = $patient->outlook_calendar;
        return $this->response(null, $notifications, PatientGlobalMessageBook::SUCCESS['NOTIFICATIONS'],200);
    }

    /**
     * Description: Marking patient notification as read
     *  1) Notification is marked as read
     *  2) Unread notifications count is returned as response
     *  3) Notification is logged, and it's response is returned
     *
     * @param Request $request
     * @return Response
     */
    public function markNotificationAsRead($request): Response
    {
        $patient = Patient::where('id', auth()->guard('patient-api')->id())->first();
        $notification = $patient->notifications()->where('id', $request->notification_id)->first();

        if (!$notification)
        {
            $response = $this->response($request->notification_id, null, PatientGlobalMessageBook::FAILED['NOTIFICATIONS_NOT_FOUND'],404);
        }
        else
        {
            $notification->markAsRead();
            $unreadNotificationsCount = $patient->unreadNotifications()->count();
            $response = $this->response($request->notification_id, $unreadNotificationsCount, PatientGlobalMessageBook::SUCCESS['NOTIFICATION_READ'],200);
        }

        return $response;
    }

    /**
     * Description: Marking patient all notifications as read
     *  1) All notifications is marked as read
     *  2) Notification is logged, and it's response is returned
     *
     * @param Request $request
     * @return Response
     */
    public function markAllNotificationsAsRead($request): Response
    {
        $patient = Patient::where('id', auth()->guard('patient-api')->id())->first();
        $notifications = $patient->unreadNotifications() ? $patient->unreadNotifications()->update(['read_at' => now()]) : null ;

        return $this->response(auth()->guard('patient-api')->id(), $notifications, PatientGlobalMessageBook::SUCCESS['ALL_NOTIFICATIONS_MARKED_AS_READ'],200);
    }
}
