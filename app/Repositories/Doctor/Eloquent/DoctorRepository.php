<?php

namespace App\Repositories\Doctor\Eloquent;

use App\Filters\Patient\FirstName;
use App\Filters\Patient\LastName;
use App\Filters\Patient\MiddleName;
use App\Filters\Patient\Patientkey;
use App\Filters\Patient\PhoneNumber;
use App\Filters\Patient\Search;
use App\Filters\Patient\Status;
use App\Helper\Helper;
use App\Http\Resources\Doctor\DoctorCollection;
use App\Http\Resources\Doctor\DoctorFeeCollection;
use App\Http\Resources\Doctor\DoctorOffDateResource;
use App\Http\Resources\Doctor\PracticeRequestResource;
use App\Jobs\Appointment\DoctorAppointment as AppointmentDoctorAppointment;
use App\Jobs\Appointment\PatientAppointment as AppointmentPatientAppointment;
use App\Jobs\Doctor\DoctorRequestByPractice;
use App\Jobs\Doctor\SendOtpToUpdatePrimaryEmailID;
use App\Jobs\Practice\DoctorAcceptInvitationSentByPracticeNotification;
use App\Jobs\Practice\DoctorRejectedInvitationSentByPracticeNotification;
use App\libs\Messages\DoctorGlobalMessageBook as DGMBook;
use App\Models\Appointment\Appointment;
use App\Models\Doctor\Doctor;
use App\Models\Doctor\DoctorDocument;
use App\Models\Doctor\DoctorFee;
use App\Models\Doctor\DoctorOffDate;
use App\Models\Doctor\DoctorPractice;
use App\Models\Doctor\DoctorPracticeRequest;
use App\Models\Doctor\DoctorSlot;
use App\Models\Doctor\DoctorSpecialization;
use App\Models\OtpVerification\OtpVerification;
use App\Models\Patient\Patient;
use App\Notifications\Practice\DoctorAcceptedInvitation;
use App\Notifications\Practice\DoctorRejectedInvitation;
use App\Repositories\Doctor\Interfaces\DoctorRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DoctorRepository implements DoctorRepositoryInterface
{
    use RespondsWithHttpStatus;

    protected mixed $doctor_id;

    /**
     *  Description: Doctor ID is initialized using helper function in order to use across the repository.
     *  1) Request is passed in order to determine who is requesting doctor or practice/staff
     *  2) If in-coming request has doctor_id than requesting user is practice/staff otherwise it is doctor requesting
     */
    public function __construct(Request $request)
    {
        $this->doctor_id = Helper::doctor_id($request);
    }

    /**
     *  Description: Function to get doctor details by ID and returns DoctorCollection
     *  1) This method receives doctor ID ($id) as parameter
     *  2) Returns empty data array if no slot is found against a doctor
     *
     * @param $id
     * @return Response|DoctorCollection
     */
    public function getDetailsByID($id): Response|DoctorCollection
    {
        $doctor = Doctor::with(
            'doctorAddress',
            'doctorSpecializations',
            'doctorSpecializations.specializations',
            'doctorLegalInformation',
            'doctorAddress.currentCountry:id,name',
            'doctorAddress.currentState:id,name',
            'doctorAddress.currentCity:id,name',
            'doctorAddress.homeTownCountry:id,name',
            'doctorAddress.homeTownState:id,name',
            'doctorAddress.homeTownCity:id,name'
        )
            ->where(['id' => $id, 'practice_id' => $this->practice_id()])
            ->get();            // Used get in order to use DoctorCollection API resource because it is being used in list of doctors API.
        if ($doctor->count() == 0)
        {
            $response = $this->response($id, $doctor, DGMBook::FAILED['DOCTOR_NOT_FOUND'],200,false);
        }
        else
        {
            $this->response($id, $doctor, DGMBook::SUCCESS['DOCTOR_DETAILS'],200);
            $response = new DoctorCollection($doctor);
        }
        return $response;
    }

    /**
     *  Description: Function to get list of slots with off dates of a doctor.
     *  1) This method receives doctor id ($id) as parameter
     *  2) Returns empty data array if no slot is found against a doctor
     *
     * @param $request
     * @param $id
     * @return array|Response
     */
    public function listOfSlots($request, $id): Response|array
    {
        $doctorPractice = DoctorPractice::with(['doctor.doctorOffDays' => function ($query){
            return $query->where('practice_id' , $this->practice_id());
        }, 'doctor.doctorSlots' => function($query){
            return $query->where('practice_id', $this->practice_id());
        }, 'doctor.doctorSlots.doctorSlotDays'])
            ->where(['doctor_id' =>  $id, 'practice_id' => $this->practice_id()])
            ->orderByDesc('created_at')
            ->first();

        if (count($doctorPractice->doctor->doctorSlots) == 0)
        {
            $response = $this->response($id, null, DGMBook::FAILED['SLOT_NOT_FOUND'],200,false);
        }
        else
        {
            $this->response($id, $doctorPractice->doctor->doctorSlots, DGMBook::SUCCESS['LIST_OF_SLOTS'],200);
            $listOfSlots['offDates'] = $doctorPractice->doctor->doctorOffDays;
            $data = $doctorPractice->doctor->doctorSlots()->with('days:id,doctor_slot_id,day')->select([
                'id','date_from', 'date_to', 'time_from', 'time_to', 'slot_time', 'status'
            ])->where('practice_id', $this->practice_id())->orderByDesc('created_at')->paginate($request->pagination);
            $listOfSlots['data'] = $data;

            $response = $listOfSlots;
        }
        return $response;
    }

    /**
     *  Description: OTP is generated and emailed to doctor's entered new primary email id but email id is not yet updated
     *  unless OTP is verified
     *  1) This method receives doctor_id as parameter
     *  2) Email is sent to requested email ID
     *  3) Returns response with doctor model instance
     *
     * @param $request
     * @return Response
     */
    public function requestOtpToUpdatePrimaryEmail($request): Response
    {
        $doctor = Doctor::where('id', $this->doctor_id)->first();
        $otp = Helper::otpGenerator();
        if (!$doctor) {
            $response = $this->response($request->all(), null, DGMBook::FAILED['DOCTOR_NOT_FOUND'], 400, false);
        }
        else
        {
            Helper::credentialLog($doctor->id, Auth::getDefaultDriver(),0, $doctor->primary_email);
            Helper::otpVerification($doctor->id, Auth::getDefaultDriver(), $otp, 0, $request->update_primary_email);
            $doctor->updated_by = $this->uniqueKey();
            $doctor->save();

            dispatch(new SendOtpToUpdatePrimaryEmailID($request->update_primary_email, $otp, $doctor))->onQueue(config('constants.SEND_OTP_TO_UPDATE_PRIMARY_EMAIL_ID'));
            $response = $this->response($request->all(), $doctor, DGMBook::SUCCESS['PRIMARY_EMAIL_UPDATED_OTP_SENT'], 200);
        }

        return $response;
    }

    /**
     *  Description: OTP is generated and emailed to doctor's entered new primary email id but email id is not yet updated
     *  unless OTP is verified
     *  1) This method receives doctor_id as parameter
     *  2) Returns response with doctor model instance on success
     *
     * @param $request
     * @return Response
     */
    public function updatePrimaryEmail($request): Response
    {
        $doctor = Doctor::where('id', $this->doctor_id)->first();
        if (!$doctor) {
            $response = $this->response($request->all(), null, DGMBook::FAILED['DOCTOR_NOT_FOUND'], 400, false);
        }
        else
        {
            // 0 for email in where condition
            $otpVerification = OtpVerification::where(['user_id' => $doctor->id, 'guard_name' => Auth::getDefaultDriver()])->where('type',0)->first();

            if (!$otpVerification)
            {
                $response = $this->response($request->all(), null, DGMBook::FAILED['INVALID_OTP'], 400, false);
            }
            elseif ($request->otp == $otpVerification->otp)
            {
                $doctor->update([
                    'primary_email' => $otpVerification->value,
                    'updated_by' => $this->uniqueKey(),
                ]);
                $otpVerification->is_verified = 1;
                $otpVerification->save();
                $otpVerification->delete();

                $response = $this->response($request->all(), $doctor, DGMBook::SUCCESS['PRIMARY_EMAIL_UPDATED_SUCCESSFULLY'], 200);
            }
            else {
                $response = $this->response($request->all(), null, DGMBook::FAILED['INVALID_OTP'], 422, false);
            }
        }
        return $response;
    }

    /**
     *  Description: Function to list doctor fee
     *  1) Doctor id with $id key is send with request
     *  2) Pagination is sent with request to paginate response
     *  3) Returns response with doctorFeeCollection on success
     *
     * @param $request
     * @param $id
     * @return Response|DoctorFeeCollection
     */
    public function doctorFee($request, $id): Response|DoctorFeeCollection
    {
        $doctorFee = DoctorFee::where(['doctor_id' => $id, 'practice_id' => $this->practice_id()])->orderByDesc('created_at')->paginate($request->pagination);

        if (!$doctorFee)
        {
            return $this->response($request->all(), null, DGMBook::FAILED['FEE_NOT_FOUND'], 400, false);
        }
        $this->response($request->all(), $doctorFee, DGMBook::SUCCESS['LIST_OF_FEE'], 200);

        return new DoctorFeeCollection($doctorFee);
    }

    /**
     *  Description: Stores doctor fee. On adding new fee all other doctor fees will be deactivated
     *  1) Doctor fee is passed as an amount key in request
     *  2) Returns response with doctorFee model instance on success
     *
     * @param $request
     * @return Response
     */
    public function addDoctorFee($request): Response
    {
        $doctorPractice = DoctorPractice::where(['doctor_id' => $this->doctor_id, 'practice_id' => $this->practice_id()])->first();

        if (!$doctorPractice)
        {
            $response = $this->response($request->all(), null, DGMBook::FAILED['DOCTOR_NOT_FOUND'], 400, false);
        }
        elseif (!$doctorPractice->doctor_status_in_practice)    // checking whether doctor's account is active in that practice (Check used by practice, and it's staff)
        {
            $response = $this->response($request->all(), $doctorPractice->doctor_status_in_practice, DGMBook::FAILED['ACCOUNT_SUSPENDED'], 200);
        }
        else
        {
            $doctorPractice->doctor->doctorFees()->where('practice_id', $this->practice_id())->update(['updated_by' => $this->uniqueKey(), 'status' => false]);         // Deactivating all previous fees

            $doctorFee = $doctorPractice->doctor->doctorFees()->create([                // Initializing variable inorder to save log
                'practice_id' => $this->practice_id(),
                'amount' => $request->amount,
                'created_by' => $this->uniqueKey(),
                'status' => true,
            ]);

            $response = $this->response($request->all(), $doctorFee, DGMBook::SUCCESS['ADDED_FEE'], 200);
        }

        return $response;
    }

    /**
     *  Description: Updates doctor fee status. On updating fee status all other doctor fee status will be deactivated
     *  1) Doctor fee is passed as an amount key in request
     *  2) Returns response with doctorFee model instance on success
     *
     * @param $request
     * @param $id
     * @return Response
     */
    public function updateDoctorFeeStatus($request, $id): Response
    {
        $doctorFee = DoctorFee::with('doctor')->where(['id' => $id, 'doctor_id' => $this->doctor_id])->where('practice_id', $this->practice_id())->first();

        if (!$doctorFee)
        {
            $response = $this->response($request->all(), null, DGMBook::FAILED['FEE_NOT_FOUND'], 400, false);
        }
        else
        {
            // Deactivating all doctor fee status before updating current requested fee status to active
            DoctorFee::where(['doctor_id' => $doctorFee->doctor_id, 'practice_id' => $this->practice_id()])->update(['updated_by' => $this->uniqueKey(), 'status' => false]);

            $doctorFee->update([
                'status' => true,
                'updated_by' => $this->uniqueKey()
            ]);
            $response = $this->response($request->all(), $doctorFee, DGMBook::SUCCESS['FEE_STATUS_UPDATED'], 200);
        }

        return $response;
    }

    /**
     *  Description: New slot is stored with default status inactive
     *  New slot will not be stored if it is overlapping with date, time or days with previous slots
     *  1) Times (time_from and time_to) is sent in request with format "10:00 AM, 12:00 PM"
     *  2) Dates (date_from and date_to) is sent in request with format "2022-09-15"
     *  3) Array of days (Days) are sent in request with format (Monday, tuesday)
     *  4) Returns response with doctorFee model instance on success
     *
     * @param $request
     * @return Response
     */
    public function addSlot($request): Response
    {
        // checking for any slot overlap (dates, time, days)
        $doctorSlot = $this->doctorSlotQuery($request, $this->doctor_id);

        if ($doctorSlot->count() > 0)
        {
            $response = $this->response($request->all(), null, DGMBook::FAILED['ENTRY_PRESENT'], 403, false);
        }
        else
        {
            $doctorSlot = $this->addSlotTransaction($request, $this->doctor_id, $this->uniqueKey());
            $response = $this->response($request->all(), $doctorSlot->load('days'), DGMBook::SUCCESS['SLOT_ADDED'], 200);
        }

        return $response;
    }

    /**
     *  Description: Local function used by addSlot function
     *  1) Check any overlap between time, day, date while adding a slot
     *
     * @param $request
     * @param $doctorID
     * @return Collection|array
     */
    function doctorSlotQuery($request, $doctorID): Collection|array
    {
        return DoctorSlot::where(['doctor_id' => $doctorID])
            ->where(function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('time_from', '<=', $request->time_from)
                        ->where('time_to', '>', $request->time_from);
                })
                    ->orWhere(function ($query) use ($request) {
                        $query->where('time_from', '<', $request->time_to)
                            ->where('time_to', '>=', $request->time_to);
                    });
            })
            ->where(function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->whereDate('date_from', '<=', $request->date_from)
                        ->whereDate('date_to', '>=', $request->date_from);
                })
                    ->orWhere(function ($query) use ($request) {
                        $query->whereDate('date_from', '<', $request->date_to)
                            ->whereDate('date_to', '>=', $request->date_to);
                    });
            })
            ->whereHas('doctorSlotDays', function($query) use ($request){
                $query->whereIn('day', $request->days);
            })
            ->get();
    }

    /**
     *  Description: Local function used by addSlot function
     *  1) Transaction to add slot and slot day
     *
     * @param $request
     * @param $doctorID
     * @param $uniqueKey
     * @return mixed
     */
    function addSlotTransaction($request, $doctorID, $uniqueKey): mixed
    {
        return DB::transaction(function () use ($request, $doctorID, $uniqueKey) {
            $doctorSlot = DoctorSlot::create([
                'doctor_id' => $doctorID,
                'practice_id' => $this->practice_id(),
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'time_from' => $request->time_from,
                'time_to' => Carbon::make($request->time_to)->subMinute()->format('h:i A'),     // Saving 1 less minute inorder to prevent time overlap error
                'slot_time' => $request->slot_time,
                'created_by' => $uniqueKey,
            ]);
            foreach ($request->days as $day) {
                $doctorSlot->doctorSlotDays()->create([
                    'day' => $day
                ]);
            }
            return $doctorSlot;
        });
    }

    /**
     *  Description: Function to publish slot
     *  1) Array of slot ids are sent in request
     *  2) Returns response with doctorSlots model instance on success
     *
     * @param $request
     * @return Response
     */
    public function publishSLot($request): Response
    {
        $slots = DoctorSlot::where(['doctor_id' => $this->doctor_id, 'practice_id' => $this->practice_id()])
            ->whereIn('id', $request->ids)
            ->get();

        if ($slots->count() == 0)
        {
            $response = $this->response($request->all(), null, DGMBook::FAILED['SLOT_NOT_FOUND'], 400, false);
        }
        else
        {
            // Using toQuery method that returns an Eloquent query builder instance containing a whereIn constraint on the collection model's primary keys
            $slots->toQuery()->update([ 'status' => true,'updated_by' => $this->uniqueKey(),]);
            $response = $this->response($request->all(), $slots, DGMBook::SUCCESS['SLOT_PUBLISHED'], 200);
        }

        return $response;
    }

    /**
     *  Description: Function to deactivate slot. Slots having appointments will be canceled
     *  1) Slot id is sent in request
     *  2) Any appointments present against that slot will be cancelled
     *
     * @param $request
     * @return Response
     */
    public function deactivateSlot($request): Response
    {
        $slot = DoctorSlot::with(['appointments' => function($query){
            return $query->where(['status' => 'Confirmed', 'practice_id' => $this->practice_id()]);
        }, 'appointments.patient'])
            ->where(['id' => $request->id, 'doctor_id' => $this->doctor_id])
            ->where('practice_id', $this->practice_id())
            ->first();
        if (!$slot)
        {
            $response = $this->response($request->all(), null, DGMBook::FAILED['SLOT_NOT_FOUND'], 400, false);
        }
        else {

            // Canceling all appointments related to slot(s)
            if ($slot->appointments) {
                // Getting all appointments for slots depending on where conditions
                $slotAppointments = $slot->appointments()
                    ->where('status', '=', 'Confirmed')
                    ->get();

                foreach ($slotAppointments as $appointment) {
                    $appointment->update([
                        'status' => 'Cancelled',
                        'reason' => 'Doctor not available',
                        'comments' => 'Doctor not available',
                        'updated_by' => $this->uniqueKey(),
                    ]);
                    $patient = $appointment->patient;
                    $doctor = $appointment->doctor;
                    $practice = DB::SELECT("SELECT practice_registration_requests.practice_name , practice_addresses.address_line_1, practice_addresses.address_line_2
                 FROM practices
                 INNER JOIN practice_registration_requests ON practices.practice_registration_request_id = practice_registration_requests.id
                 INNER JOIN practice_addresses ON practices.id = practice_addresses.practice_id
                 where practices.id = '{$appointment->practice_id}';");
                    // send email doctor and patient
                    dispatch(new AppointmentPatientAppointment($appointment, $doctor, $patient, $practice))->onQueue(config('constants.PATIENT_APPOINTMENT'));
                    dispatch(new AppointmentDoctorAppointment($appointment, $doctor, $patient, $practice))->onQueue(config('constants.DOCTOR_APPOINTMENT'));
                }

                $slot->update([
                    'status' => false,
                    'updated_by' => $this->uniqueKey()
                ]);
                $response = $this->response($request->all(), $slot, DGMBook::SUCCESS['SLOT_DEACTIVATE_STATUS'], 200);
            }
        }

        return $response;
    }

    /**
     *  Description: Function to list OffDates of a doctor
     *  1) Doctor id is sent in request
     *  2) Pagination is sent in request to paginate response
     *  3) DoctorOffDateResource collection is return in response, empty data array is return if no data is returned
     *
     * @param $doctor_id
     * @param $pagination
     * @return AnonymousResourceCollection
     */
    public function listOfOffDates($doctor_id, $pagination): AnonymousResourceCollection
    {
        $offDates = DoctorOffDate::where(['doctor_id' => $doctor_id, 'practice_id' => $this->practice_id()])->orderByDesc('created_at')->paginate($pagination);
        $this->response($doctor_id, $offDates, DGMBook::SUCCESS['LIST_OF_OFF_DATES'], 200);
        return DoctorOffDateResource::collection($offDates);
    }

    /**
     *  Description: Function to add OffDates for a doctor. An array of dates is sent in request to add as off dates.
     *  Any appointment against these dates will be canceled
     *  1) An array of dates is sent in request to be added as off dates
     *  2) DoctorOffDateResource collection is return in response
     *
     * @param $request
     * @return Response
     */
    public function addOffDates($request): Response
    {
        $offDates = array();    // saving newly created doctor off dates for activity log

        $appointments = Appointment::with('patient')->where(['doctor_id' => $this->doctor_id, 'practice_id' => $this->practice_id()])
            ->where('status', '=', 'Confirmed')
            ->whereIn('date', $request->dates)
            ->get();

        // Canceling appointments (if any) against the dates being added as off dates
        if ($appointments) {
            foreach ($appointments as $appointment)
            {
                $appointment->update([
                    'status' => 'Cancelled',
                    'reason' => 'Doctor not available',
                    'comments' => 'Doctor not available',
                    'updated_by' => $this->uniqueKey()
                ]);
                $patient = $appointment->patient;
                $doctor = $appointment->doctor;
                $practice = DB::SELECT("SELECT practice_registration_requests.practice_name , practice_addresses.address_line_1, practice_addresses.address_line_2
                 FROM practices
                 INNER JOIN practice_registration_requests ON practices.practice_registration_request_id = practice_registration_requests.id
                 INNER JOIN practice_addresses ON practices.id = practice_addresses.practice_id
                 where practices.id = '{$appointment->practice_id}';");
                // send email doctor and patient
                dispatch(new AppointmentPatientAppointment($appointment, $doctor, $patient, $practice))->onQueue(config('constants.PATIENT_APPOINTMENT'));
                dispatch(new AppointmentDoctorAppointment($appointment, $doctor, $patient, $practice))->onQueue(config('constants.DOCTOR_APPOINTMENT'));
            }
        }

        foreach ($request->dates as $date) {
            $previousDoctorDate = DoctorOffDate::withTrashed()->where(['doctor_id' => $this->doctor_id, 'date' => $date])->where('practice_id', $this->practice_id())->first();
            // Restoring if dates are present inorder to prevent in entry
            if ($previousDoctorDate) {
                $previousDoctorDate->restore();
            } else {
                $offDates[] = DoctorOffDate::create([
                    'doctor_id' => $this->doctor_id,
                    'practice_id' => $this->practice_id(),
                    'date' => Carbon::parse($date)->format('Y-m-d'),
                    'created_by' => $this->uniqueKey(),
                ]);
            }
        }

        return $this->response($request->all(), DoctorOffDateResource::collection($offDates), DGMBook::SUCCESS['ADDED_OFF_DATES'], 200);
    }

    /**
     *  Description: Function to delete OffDates of a doctor. An array of dates is sent in request to delete off dates
     *  1) An array of dates is sent in request to delete off dates
     *  2) Success response is returned
     *
     * @param $request
     * @return Response
     */
    public function deleteOffDates($request): Response
    {
        $offDates = DoctorOffDate::where(['doctor_id' => $this->doctor_id, 'practice_id' => $this->practice_id()])->whereIn('id', $request->ids)->get();

        foreach ($offDates as $offDate)
        {
            if ($offDate->delete())
            {
                $offDate->updated_by = $this->uniqueKey();
                $offDate->save();
            }
        }

        return $this->response($request->all(), $offDates, DGMBook::SUCCESS['DELETED_OFF_DATES'], 200);
    }

    /**
     *  Description: Function to delete additional documents of a doctor
     *  1) Document id is sent in request
     *  2) Success response is returned
     *
     * @param $request
     * @return Response
     */
    public function deleteDocument($request): Response
    {
        $document = DoctorDocument::where(['id' => $request->id, 'doctor_id' => $this->doctor_id])->where('practice_id', $this->practice_id())->first();

        if (!$document)
        {
            $response = $this->response($request->all(), null, DGMBook::FAILED['DOCUMENT_NOT_FOUND'], 400, false);
        }
        else
        {
            $document->update(['updated_by' => $this->uniqueKey()]);
            $document->delete();

            $response = $this->response($request->all(), null, DGMBook::SUCCESS['DOCUMENT_DELETED'], 200);
        }

        return $response;
    }

    /**
     *  Description: Function to upload additional documents of a doctor
     *  1) Document id is sent in request
     *  2) Success response is returned
     *
     * @param $request
     * @return Response
     */
    public function uploadDocument($request): Response
    {
        $documents = array();                   // Initialing in order to save response in activity log

        foreach ($request->file_paths as $file_path) {

            $documents[] = DoctorDocument::create([
                'doctor_id' => $this->doctor_id,
                'practice_id' => $this->practice_id(),
                'file_path' => $file_path,
                'created_by' => $this->uniqueKey(),
            ]);
        }

        return $this->response($request->all(), $documents, DGMBook::SUCCESS['DOCUMENT_UPLOADED'], 201);
    }

    /**
     *  Description: Function to update specializations
     *  1) An array of specialization ids are sent in the request
     *  2) Previous specializations are deleted
     *  3) New specializations are assigned to doctor
     *
     * @param $request
     * @return Response
     */
    public function updateSpecialization($request): Response
    {
        DoctorSpecialization::where('doctor_id', $this->doctor_id)->delete();

        $specializations = array();                 // Initialing in order to save response in activity log
        foreach ($request->specialization_ids as $specialization_id) {
            $specializations[] = DoctorSpecialization::create([
                'doctor_id' => $this->doctor_id,
                'specialization_id' => $specialization_id,
                'created_by' => $this->uniqueKey(),
                'updated_by' => $this->uniqueKey()
            ]);
        }

        return $this->response($request->all(), $specializations, DGMBook::SUCCESS['SPECIALIZATION_UPDATED'], 200);
    }

    /**
     *  Description: Function to update personal information
     *  1) An array of specialization ids are sent in the request
     *  2) Personal information like first_name, middle_name, last_name, gender, marital_status
     *  3) Success response with message doctor model instance is returned
     *
     * @param $request
     * @return Response
     */
    public function updatePersonalInformation($request): Response
    {
        $doctor = Doctor::where('id', $this->doctor_id)->first();
        if (!$doctor)
        {
            $response = $this->response($request->all(), null, DGMBook::FAILED['DOCTOR_NOT_FOUND'], 400, false);
        }
        else
        {
            $data = $this->updatePersonalInformationDetails($request, $this->uniqueKey());

            $doctor->update($data);

            $specializationIDs[] = array_diff($request->specializationIDs, $doctor->doctorSpecializations->pluck('specialization_id')->toArray());
            $doctor->doctorSpecializations()->whereNotIn('specialization_id', $request->specializationIDs)->delete();
            $doctor->doctorSpecializations()->whereIn('specialization_id', $request->specializationIDs)->update([
                'updated_by' => $this->uniqueKey(),
            ]);

            // Passing 0 in $specializationIDs array as an index because array_diff used above creates a single index containing more than one value(s).
            foreach ($specializationIDs[0] as $specializationID)
            {
                $doctor->doctorSpecializations()->create([
                    'specialization_id' => $specializationID,
                    'created_by' => $this->uniqueKey(),
                ]);
            }

            $response = $this->response($request->all(), $doctor, DGMBook::SUCCESS['UPDATED_PERSONAL_INFORMATION'], 200);
        }

        return $response;
    }

    /**
     *  Description: Local function used by updatePersonalInformation function
     *  1) Returns an array of personal information
     *
     * @param $request
     * @param $uniqueKey
     * @return array
     */
    function updatePersonalInformationDetails($request, $uniqueKey)
    {
        return [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
            'updated_by' => $uniqueKey,
        ];
    }

    /**
     *  Description: Function to update about me
     *  1) About me is sent in the request
     *  2) Success response with  message doctor model instance is returned
     *
     * @param $request
     * @return Response
     */
    public function updateAboutMe($request): Response
    {
        $doctor = Doctor::where('id', $this->doctor_id)->first();

        if (!$doctor)
        {
            $response = $this->response($request->all(), null, DGMBook::FAILED['DOCTOR_NOT_FOUND'], 400, false);
        }
        else
        {
            $doctor->update([
                'about_me' => $request->about_me,
                'updated_by' => $this->uniqueKey()
            ]);
            $response = $this->response($request->all(), $doctor, DGMBook::SUCCESS['UPDATED_PERSONAL_INFORMATION'], 200);
        }
        return $response;
    }

    /**
     *  Description: Function to update contact information
     *  1) An array of specialization ids are sent in the request
     *  2) Contact information like country_code_primary_phone_number, primary_phone_number, country_code_secondary_phone_number, secondary_phone_number,
     *     secondary_email
     *  3) Success response with message doctor model instance is returned
     *
     * @param $request
     * @return Response
     */
    public function updateContactInformation($request): Response
    {
        $doctor = Doctor::where('id', $this->doctor_id)->first();

        if (!$doctor)
        {
            $response = $this->response($request->all(), null, DGMBook::FAILED['DOCTOR_NOT_FOUND'], 400, false);
        }
        else
        {
            $doctor->update([
                'country_code_primary_phone_number' => $request->country_code_primary_phone_number,
                'primary_phone_number' => $request->primary_phone_number,
                'country_code_secondary_phone_number' => $request->country_code_secondary_phone_number,
                'secondary_phone_number' => $request->secondary_phone_number,
                'secondary_email' => $request->secondary_email,
                'updated_by' => $this->uniqueKey(),
            ]);
            $response = $this->response($request->all(), $doctor, DGMBook::SUCCESS['UPDATED_CONTACT_INFORMATION'], 200);
        }

        return $response;
    }

    /**
     *  Description: Function to update current address
     *  1) An array of specialization ids are sent in the request
     *  2) Contact information like current_address_1, current_address_2, current_state_id, current_country_id,
     *     current_city_id, current_zip_code
     *  3) Success response with message doctor model instance is returned
     *
     * @param $request
     * @return Response
     */
    public function updateCurrentAddress($request): Response
    {
        $doctor = Doctor::with('doctorAddress')->where('id', $this->doctor_id)->first();
        if (!$doctor)
        {
            $response = $this->response($request->all(), null, DGMBook::FAILED['DOCTOR_NOT_FOUND'], 400, false);
        }
        else
        {
            if(!isset($doctor->doctorAddress))
            {
                $doctor->doctorAddress()->create([
                    'current_address_1' => $request->current_address_1,
                    'current_address_2' => $request->current_address_2,
                    'current_state_id' => $request->current_state_id,
                    'current_country_id' => $request->current_country_id,
                    'current_city_id' => $request->current_city_id,
                    'current_zip_code' => $request->current_zip_code,
                    'updated_by' => $this->uniqueKey(),
                ]);
            }
            else
            {
                $doctor->doctorAddress()->update([
                    'current_address_1' => $request->current_address_1,
                    'current_address_2' => $request->current_address_2,
                    'current_state_id' => $request->current_state_id,
                    'current_country_id' => $request->current_country_id,
                    'current_city_id' => $request->current_city_id,
                    'current_zip_code' => $request->current_zip_code,
                    'updated_by' => $this->uniqueKey(),
                ]);
            }
            $response = $this->response($request->all(), $doctor->doctorAddress(), DGMBook::SUCCESS['UPDATED_CURRENT_ADDRESS'], 200);
        }
        return $response;
    }

    /**
     * * Description: Get Doctor all Patient
     * 1) Showing all those patient against a doctor
     * 2) This will return patients list against specific doctor
     * 3) there is no indirectly relation of patient and doctor
     * 4) So 1st I collect doctor patient from there appointment table
     * 5) Retrieved patient IDs to patient table and get those patients
     *
     * @param mixed $request
     * @return Response
     */
    public function doctorPatientList($request): Response
    {
        $patients = Appointment::where(['doctor_id' => $this->doctor_id, 'practice_id' => $this->practice_id()])->select(['id', 'patient_id'])->distinct('patient_id')->pluck('patient_id')->toArray();
        $query = Patient::where( function ($query) use ($patients) {
            $query->whereIn('id', $patients);
        })->latest('id');

        $patient = app(Pipeline::class)
            ->send($query)
            ->through([
                Search::class,
                FirstName::class,
                PhoneNumber::class,
                Patientkey::class,
                Status::class,
                MiddleName::class,
                LastName::class
            ])
            ->thenReturn()
            ->paginate($request->pagination);

        return $this->response(true,  $patient, DGMBook::SUCCESS['PATIENT_LIST'], 200);
    }

    /**
     *  Description: Get all stats of doctor dashboard
     * get all count of
     * Total doctor patients.
     * Total doctor Appointment.
     * Total Today Appointment.
     * Total Upcoming Appointment.
     * Total Cancelled Appointment.
     * Total Completed Appointment.
     * @return Response
     */
    public function doctorDashboardStats(): Response
    {
        $date = date("Y-m-d");
        $auth = auth()->id();
        $authDoctor = Appointment::where(['doctor_id' => $auth, 'practice_id' => $this->practice_id()])->get();

        $totalAppointment =  $authDoctor->count();
        $upcomingAppointment =  $authDoctor->where('date', '>', $date)->count();
        $todayAppointment =  $authDoctor->where('date',  $date)->count();
        $completeAppointment =  $authDoctor->where('status',  'Completed')->count();
        $cancelAppointment =  $authDoctor->where('status', 'Cancelled')->count();
        $confirmedAppointments =  $authDoctor->where('status', 'Confirmed')->count();
        $totalDoctorPatients =  Appointment::where(['doctor_id' => $auth, 'practice_id' => $this->practice_id()])->distinct('patient_id')->pluck('patient_id')->count();

        $statsData = [
            'total_doctor_appointment' => $totalAppointment,
            'today_appointment' => $todayAppointment,
            'upcoming_appointment' => $upcomingAppointment,
            'complete_appointment' => $completeAppointment,
            'cancel_appointment' => $cancelAppointment,
            'confirmed_appointments' => $confirmedAppointments,
            'total_practice_patients' => $totalDoctorPatients,
        ];

        return $this->response(true, $statsData, DGMBook::SUCCESS['DOCTOR_DASHBOARD_STATS'], 200);
    }

    /**
     * Description: Pi chart for doctor dashboard
     * In this api return all ....
     *  All Appointment count
     * All Upcoming Appointment count
     * All Completed Appointment count.
     * All Cancelled Appointment count
     * @return Response
     */
    public function doctorAppointmentPiChart(): Response
    {
        $date = date("Y-m-d");
        $auth = auth()->id();
        $authDoctor = Appointment::where(['doctor_id' => $auth, 'practice_id' => $this->practice_id()])->get();
         $completeAppointment =  $authDoctor->where('status',  'Completed')->count();
        $cancelAppointment =  $authDoctor->where('status', 'Cancelled')->count();
        $totalAppointment =  $authDoctor->count();
        $upcomingAppointment =  $authDoctor->where('date', '>', $date)->count();

        $totalAppointment = ['name' => 'Total Appointments', 'y' => $totalAppointment];
        $upcomingAppointment = ['name' => 'Upcoming Appointments', 'y' => $upcomingAppointment];
        $completeAppointment = ['name' => 'Completed Appointments', 'y' => $completeAppointment];
        $cancelAppointment = ['name' => 'Cancelled Appointments', 'y' => $cancelAppointment];
        $statsData = [];
        array_push($statsData, $totalAppointment, $upcomingAppointment, $completeAppointment, $cancelAppointment);

        return $this->response(true, $statsData, DGMBook::SUCCESS['DOCTOR_PI_CHART'], 200);
    }

    /**
     * Description: List of requests sent to doctor by practice to get registered as a doctor to his practice
     *  1) List of requests of a signed-in doctor is returned
     *
     * @param $pagination
     * @return Response|AnonymousResourceCollection
     */
    public function listOfDoctorRegistrationRequests($pagination): Response|AnonymousResourceCollection
    {
        $practiceRequest = DoctorPracticeRequest::with('practice:id,email,practice_registration_request_id','practice.initialPractice:id,practice_name,first_name,middle_name,last_name')
            ->where('doctor_id', $this->doctor_id)
            ->paginate($pagination);


        if (count($practiceRequest) == 0)
        {
            $response = $this->response(null,null, DGMBook::FAILED['NO_PRACTICE_REQUESTS'],200, false);
        }
        else
        {
            $this->response(null, $practiceRequest, DGMBook::SUCCESS['PRACTICE_REQUEST_LIST'], 200);
            $response = PracticeRequestResource::collection($practiceRequest);
        }
        return $response;
    }

    /**
     * Description: Updating request status sent by practice to get register in his practice
     *  1) Status is received in the request
     *  2) Email is sent to practice to alert about the status of the request.
     *  3) Practice is notified about the status of the request.
     *  4) Success response with message about updated status
     *
     * @param $request
     * @return Response
     */
    public function updateDoctorRegistrationRequestStatus($request): Response
    {
        $practiceRequest = DoctorPracticeRequest::where('id', $request->request_id)->first();
        if (!$practiceRequest)
        {
            $response = $this->response($request->all(),null, DGMBook::FAILED['REQUEST_NOT_FOUND'],400);
        }
        else
        {
            $practiceRequest->update([
                'status' => $request->status,
                'updated_by' => $this->uniqueKey()
            ]);
            if ($request->status == 'Accepted')
            {
                $role = Role::with('permissions')->where(['id' => 2, 'name' => 'Doctor'])->first(['id','name']);
                $doctorPractice = DoctorPractice::create([
                    'doctor_id' => $practiceRequest->doctor_id,
                    'practice_id' => $practiceRequest->practice_id,
                    'role_id' => $role->id,
                    'role_name' => $role->name,
                    'created_by' => $this->uniqueKey(),
                ]);

                $doctor = Doctor::with(['doctorPractices' => function($query){
                    return $query->select(['id','doctor_id','practice_id','role_id', 'role_name','doctor_status_in_practice','currently_active_in_practice_status'])
                        ->orderByDesc('created_at')
                        ->take(1);
                },'doctorPractices.practice:id,practice_registration_request_id','doctorPractices.practice.initialPractice:id,practice_name'])
                    ->where('id', $practiceRequest->doctor_id)
                    ->first();
                $doctorWithPractice = $doctor->doctorPractices;

                foreach ($role->permissions as $permission)
                {
                    $doctorPractice->doctorPracticePermissions()->create([
                        'permission_id' => $permission->id,
                        'permission_name' => $permission->name,
                    ]);
                }

                $doctorPractice->practice->notify(new DoctorAcceptedInvitation($doctorPractice));
                $notification = DatabaseNotification::where(['notifiable_id' => $doctorPractice->practice_id, 'read_at' => null])->where('notifiable_type', 'App\Models\Practice\Practice')->orderByDesc('created_at')->first();
                $unread_notifications_count = $doctorPractice->practice->unreadNotifications()->count();
                $total_notifications = $doctorPractice->practice->notifications()->count();

                dispatch(new DoctorAcceptInvitationSentByPracticeNotification($doctorPractice, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.DOCTOR_ACCEPTED_INVITATION_SENT_BY_PRACTICE_NOTIFICATION'));
            }
            else
            {
                $doctorWithPractice = null;

                $practiceRequest->practice->notify(new DoctorRejectedInvitation($practiceRequest));
                $notification = DatabaseNotification::where(['notifiable_id' => $practiceRequest->practice_id, 'read_at' => null])->where('notifiable_type', 'App\Models\Practice\Practice')->orderByDesc('created_at')->first();
                $unread_notifications_count = $practiceRequest->practice->unreadNotifications()->count();
                $total_notifications = $practiceRequest->practice->notifications()->count();

                dispatch(new DoctorRejectedInvitationSentByPracticeNotification($practiceRequest, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.DOCTOR_REJECTED_INVITATION_SENT_BY_PRACTICE_NOTIFICATION'));
            }

            dispatch(new DoctorRequestByPractice($practiceRequest))->onQueue(config('constants.DOCTOR_REQUEST_BY_PRACTICE'));

            $response = $this->response($request->all(), $doctorWithPractice, DGMBook::SUCCESS['REQUEST_STATUS_UPDATED'], 200);
        }
        return $response;
    }

    /**
     *  Description: Function used by doctor to get his all his appointments to view in a calendar for selected date
     *  1) Start date and end date with format (yy/mm/dd) e.g 2022-12-20 are passed for between dates scenario as a request for appointments
     *  2) Or only start date is passed to retrieve appointments for selected date
     *  3) Returns data array containing appointments details
     *
     * @param $request
     * @return Response
     */
    public function calendarAppointmentsViewDates($request): Response
    {
        if ( $request->has('start_date') && $request->has('end_date') )
        {
            $doctors = Appointment::with(['doctor' => function($query){
                $query->select(['id', 'first_name', 'middle_name', 'last_name']);
            }, 'doctorSlot' => function($query){
                $query->select(['id','doctor_id','slot_time']);
            }, 'patient' => function($query){
                $query->select(['id', 'first_name', 'middle_name', 'last_name']);
            }])
                ->select(['id', 'appointment_key' ,'doctor_id','doctor_slot_id', 'patient_id', 'date', 'start_time', 'end_time' ,'status'])
                ->where(['doctor_id' => $this->doctor_id, 'practice_id' => $this->practice_id()])
                ->whereBetween('date', [$request->start_date , $request->end_date])
                ->whereNotIn('status', array('Cancelled', 'Rescheduled'))
                ->get();
        }
        else{
            $doctors = Appointment::with(['doctor' => function($query){
                $query->select(['id', 'first_name', 'middle_name', 'last_name']);
            }, 'doctorSlot' => function($query){
                $query->select(['id','doctor_id','slot_time']);
            }, 'patient' => function($query){
                $query->select(['id', 'first_name', 'middle_name', 'last_name']);
            }])
                ->select(['id', 'appointment_key' ,'doctor_id','doctor_slot_id', 'patient_id', 'date', 'start_time', 'end_time' ,'status'])
                ->where(['doctor_id' => $this->doctor_id, 'practice_id' => $this->practice_id()])
                ->whereNotIn('status', array('Cancelled', 'Rescheduled'))
                ->whereDate('date' , $request->start_date)
                ->get();
        }

        return $this->response('Doctors appointments list for calendar view', $doctors, DGMBook::SUCCESS['DOCTOR_CALENDAR_APPOINTMENTS'], 200);
    }

    /**
     * Description: Retrieving all doctor notifications
     *  1) Response with notifications array is returned
     *  2) Adding doctor google calendar and outlook google status for updating frontend calendars icon
     *
     * @param $request
     * @return Response
     */
    public function allNotifications($request): Response
    {
        $doctor = Doctor::with('google_calendar', 'outlook_calendar')->where('id', $this->doctor_id )->first();
//        $notifications = $doctor->notifications->groupBy(
//            fn($query) => Carbon::parse($query->created_at)->format('d-m-Y')
//        );
        $notifications = $doctor->notifications()
            ->paginate($request->pagination);

//        $notifications->setCollection($notifications->groupBy(function ($date) {
//            return Carbon::parse($date->created_at)->format('Y-m-d');
//        }));
        $notifications->setCollection($notifications->groupBy(
            fn($query) => Carbon::parse($query->created_at)->format('d M Y')
        ));
//return $notifications;
//        $count = 0;
//        foreach($notifications as $key => $notification)
//        {
////            $not['date'] = Carbon::parse($key)->format('d-m-Y');
//            $not['date'][$count] = $key;
//            $not['data'][$count] = $notification;
////            $array['data'] = array($not);
//            $count++;
//        }
//        $array['data'] = array($not);
//return $array;
//return $not;

        $notifications['unread'] = $doctor->unreadNotifications()->count();
        $notifications['google_calendar'] = $doctor->google_calendar;
        $notifications['outlook_calendar'] = $doctor->outlook_calendar;
        return $this->response(null, $notifications, DGMBook::SUCCESS['NOTIFICATIONS'],200);
    }

    /**
     * Description: Marking doctor notification as read
     *  1) Notification is marked as read
     *  2) Unread notifications count is returned as response
     *  3) Notification is logged, and it's response is returned
     *
     * @param Request $request
     * @return Response
     */
    public function markNotificationAsRead($request): Response
    {
        $doctor = Doctor::where('id', $this->doctor_id)->first();
        $notification = $doctor->notifications()->where('id', $request->notification_id)->first();

        if (!$notification)
        {
            $response = $this->response($request->notification_id, null, DGMBook::FAILED['NOTIFICATIONS_NOT_FOUND'],404);
        }
        else
        {
            $notification->markAsRead();
            $unreadNotificationsCount = $doctor->unreadNotifications()->count();
            $response = $this->response($request->notification_id, $unreadNotificationsCount, DGMBook::SUCCESS['NOTIFICATION_READ'],200);
        }

        return $response;
    }

    /**
     * Description: Marking doctor all notifications as read
     *  1) All notifications is marked as read
     *  2) Notification is logged, and it's response is returned
     *
     * @param Request $request
     * @return Response
     */
    public function markAllNotificationsAsRead($request): Response
    {
        $doctor = Doctor::where('id', $this->doctor_id)->first();
        $notifications = $doctor->unreadNotifications() ? $doctor->unreadNotifications()->update(['read_at' => now()]) : null ;

        return $this->response($this->doctor_id, $notifications, DGMBook::SUCCESS['ALL_NOTIFICATIONS_MARKED_AS_READ'],200);
    }
}
