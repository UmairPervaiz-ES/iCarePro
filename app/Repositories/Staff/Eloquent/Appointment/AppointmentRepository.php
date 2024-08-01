<?php

namespace App\Repositories\Staff\Eloquent\Appointment;

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
use App\Http\Controllers\gCalendarContoller;
use App\Http\Resources\SlotResource;
use App\Jobs\Appointment\DoctorAppointment as AppointmentDoctorAppointment;
use App\Jobs\Appointment\PatientAppointment as AppointmentPatientAppointment;
use App\libs\Messages\AppointmentGlobalMessageBook as AGMBook;
use App\Models\Appointment\Appointment;
use App\Models\Doctor\Doctor;
use App\Models\Doctor\DoctorSlot;
use App\Models\Doctor\DoctorSpecialization;
use App\Models\Doctor\Specialization;
use App\Models\Patient\MedicalProblem;
use App\Models\Patient\Patient;
use App\Models\Practice\Practice;
use App\Models\Practice\PracticePatient;
use App\Models\User\User;
use App\Repositories\Staff\Interfaces\Appointment\AppointmentRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Response;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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
     *  5) Activity is logged, and a success message is return
     * @param  mixed $request
     * @return Response
     */
    public function createAppointment($request): Response
    {

        $practice_id = auth()->user()->practice_id ? auth()->user()->practice_id : auth()->id();
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
                    'created_by' => auth()->user()->user_key,
                ]);
            }
            $request['practice_id'] = $practice_id;
            $request['created_by'] = auth()->user()->user_key;

            $appointment = HelperAppointment::createAppointment($request);
            $doctor = Doctor::where('id', $appointment['doctor_id'])->first();  // find doctor details
            $patient = Patient::where('id', $appointment['patient_id'])->first(); // find patient detail
            // send email doctor and patient

            $practice = DB::SELECT("SELECT practice_registration_requests.practice_name , practice_addresses.address_line_1, practice_addresses.address_line_2
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
        $query =  Appointment::where('practice_id', $this->practice_id())
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
     *  6) Activity is logged, and a success message is return
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
        } elseif ($appointment) {
            $practiceId = auth()->user()->practice_id ? auth()->user()->practice_id : auth()->id();
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
                $appointment->updated_by = auth()->user()->user_key;
                $appointment->update();

                $request['practice_id'] =  $practiceId;
                $request['created_by'] = auth()->user()->user_key;
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
        $practice = auth()->user()->practice_id;
        $doctors = app(Pipeline::class)
            ->send(Doctor::query())
            ->through([
                FirstName::class,
                MiddleName::class,
                DoctorLastName::class,
            ])
            ->thenReturn()
            ->where('practice_id', $practice)
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
            return $query->where(['practice_id' => $this->practice_id(), 'status' => 1 ]);
        }, 'doctorOffDays' => function($query) use ($doctor_id){
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
    public function specializationsWithDoctor($request, $doctorModel, $specializationModel): Response
    {
        $id = $request['specialization_id'];
        $specialization = $specializationModel::find($id);
        if ($specialization) {
            $specializations = $specializationModel::with('doctorSpecialization')->where('id', $id)->first();
            $doctor = $doctorModel::whereHas('doctorSpecializations', function ($query) use ($specializations) {
                $query->where('specialization_id', $specializations['id'])->where('practice_id', $this->practice_id());
            })->Has('doctorSlots')->get();

        } else {
            $doctor = $doctorModel::where(['practice_id' => $this->practice_id(), 'is_active' => 'true', 'kyc_status' => 'Accepted'])->latest('id')->get();
        }
        $message = AGMBook::SUCCESS['DOCTOR_LIST'];
        $status = 200;
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
            return $query->where('date', $date)->where(function ($query) {
                return $query->where('status', '!=', 'Cancelled')->where('status', '!=', 'Rescheduled');
            });
        }])
            ->where('doctor_id', $request['doctor_id'])
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

}
