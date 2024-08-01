<?php

namespace App\Repositories\Practice\Eloquent\Doctor;

use App\Filters\Appointment\Date;
use App\Filters\Doctor\Doctorkey;
use App\Filters\Doctor\DoctorLastName;
use App\Filters\Doctor\DoctorSpecialization;
use App\Filters\Doctor\FirstName;
use App\Filters\Doctor\KycStatus;
use App\Filters\Doctor\PhoneNumber;
use App\Filters\Doctor\Search;
use App\Filters\Doctor\Status;
use App\Helper\Doctor as DoctorHelper;
use App\Helper\Helper;
use App\Http\Resources\Doctor\DoctorSpecializationResource;
use App\Http\Resources\Practice\DoctorCollection;
use App\Http\Resources\SpecializationResource;
use App\Jobs\Doctor\KYCVerification;
use App\Jobs\Doctor\PasswordOneTime;
use App\Jobs\Doctor\RegisterRequestReject;
use App\Jobs\Doctor\RequestDoctorToGetRegister;
use App\Jobs\Doctor\SendRegistrationLinkToDoctor;
use App\Jobs\Practice\SendInvitationToDoctorForRegistration;
use App\libs\Messages\AppointmentGlobalMessageBook as AGMBook;
use App\libs\Messages\DoctorGlobalMessageBook as DGMBook;
use App\libs\Messages\PracticeGlobalMessageBook;
use App\Models\Appointment\Appointment;
use App\Models\Doctor\Doctor;
use App\Models\Doctor\DoctorPractice;
use App\Models\Doctor\DoctorPracticeRequest;
use App\Models\Doctor\Specialization;
use App\Notifications\Doctor\RegistrationInvitation;
use App\Repositories\Practice\Interfaces\Doctor\DoctorRepositoryInterface;
use App\Traits\CreateOrUpdate;
use App\Traits\RespondsWithHttpStatus;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DoctorRepository implements DoctorRepositoryInterface
{
    use RespondsWithHttpStatus;
    use CreateOrUpdate;

    // Using RespondsWithHttpStatus trait for activity logs and response through-out the repository
    // All functions are used by practice and staff

    public function __construct(Request $request)
    {
    }

    /**
     *  Description: Function to get list of doctors of a practice and returns doctorSlotCollection
     *  1) This method receives different query parameter to get doctors list
     *  2) Returns empty data array if no doctor is found against the query parameter
     *
     * @param $request
     * @return DoctorCollection
     */
    public function listOfDoctors($request): DoctorCollection
    {
        $doctors = app(Pipeline::class)
            ->send(DoctorPractice::query())
            ->through([
                FirstName::class,
                PhoneNumber::class,
                DoctorSpecialization::class,
                 DoctorLastName::class,
                 Status::class,
                 KycStatus::class,
                 Doctorkey::class,
                 Search::class

            ])
            ->thenReturn()
            ->with(
                'doctor.doctorAddress',
                'doctor.doctorSpecializations',
                'doctor.doctorSpecializations.specializations',
                'doctor.doctorLegalInformation',
                'doctor.doctorAddress.currentCountry:id,name',
                'doctor.doctorAddress.currentState:id,name',
                'doctor.doctorAddress.currentCity:id,name',
                'doctor.doctorAddress.homeTownCountry:id,name',
                'doctor.doctorAddress.homeTownState:id,name',
                'doctor.doctorAddress.homeTownCity:id,name'
            )
            ->where('practice_id', $this->practice_id())
            ->latest('id')
            ->paginate($request['pagination']);

        $this->response($request->all(), $doctors, DGMBook::SUCCESS['LIST_OF_DOCTORS'], 200);
        return new DoctorCollection($doctors);
    }

    /**
     *  Description: Function to get specific doctor details
     *  1) This method receives doctor ID as parameter
     *  2) Returns DoctorCollection returns empty data array if no doctor is found against the passed parameter
     *
     * @param $id
     * @return Response
     */
    public function doctorByID($id): Response
    {
        $doctorPractice = DoctorPractice::with(
            [
                'doctor.doctorAddress',
                'doctor.doctorSpecializations',
                'doctor.doctorSpecializations.specializations',
                'doctor.doctorLegalInformation',
                'doctor.doctorPractices' => function ($query) {
                    return $query->select(['id', 'doctor_id', 'practice_id', 'doctor_status_in_practice', 'currently_active_in_practice_status'])
                        ->where(['practice_id' => $this->practice_id()]);
                }
            ]
        )
            ->where(['doctor_id' => $id, 'practice_id' => $this->practice_id()])
            ->first();

        if ($doctorPractice->count() == 0) {
            $response = $this->response($id, null, DGMBook::FAILED['DOCTOR_NOT_FOUND'], 400, false);
        } else {
            $doctorPractice = $this->doctorByIDDetails($doctorPractice);
            $response = $this->response(true, $doctorPractice, DGMBook::SUCCESS['DOCTOR_DETAILS'], 200);
        }

        return $response;
    }

    function doctorByIDDetails($doctorPractice): array
    {
        return [
            'id' => $doctorPractice->doctor->id,
            'first_name' => $doctorPractice->doctor->first_name,
            'middle_name'       => $doctorPractice->doctor->middle_name ?? '',
            'last_name'       => $doctorPractice->doctor->last_name,
            'primary_email'       => $doctorPractice->doctor->primary_email,
            'secondary_email'       => $doctorPractice->doctor->secondary_email ?? '',
            'gender'       => $doctorPractice->doctor->gender,
            'dob'       => $doctorPractice->doctor->dob,
            'about_me'       => $doctorPractice->doctor->about_me ?? '',
            'profile_photo_url'       => $doctorPractice->doctor->profile_photo_url ?? '',
            'country_code_primary_phone_number' => $doctorPractice->doctor->country_code_primary_phone_number,
            'primary_phone_number'       =>  $doctorPractice->doctor->primary_phone_number,
            'country_code_secondary_phone_number' => $doctorPractice->doctor->country_code_secondary_phone_number,
            'secondary_phone_number'       => $doctorPractice->doctor->secondary_phone_number,
            'current_address_1' => $doctorPractice->doctor->doctorAddress->current_address_1 ?? '',
            'current_address_2' => $doctorPractice->doctor->doctorAddress->current_address_2 ?? '',
            'current_zip_code' => $doctorPractice->doctor->doctorAddress->current_zip_code ?? '',
            'current_country_id' => isset($doctorPractice->doctor->doctorAddress->currentCountry) ? $doctorPractice->doctor->doctorAddress->currentCountry->id : '',
            'current_country_name' => isset($doctorPractice->doctor->doctorAddress->currentCountry) ? $doctorPractice->doctor->doctorAddress->currentCountry->name : '',
            'current_state_id' => isset($doctorPractice->doctor->doctorAddress->currentState) ? $doctorPractice->doctor->doctorAddress->currentState->id : '',
            'current_state_name' => isset($doctorPractice->doctor->doctorAddress->currentState) ? $doctorPractice->doctor->doctorAddress->currentState->name : '',
            'current_city_id' => isset($doctorPractice->doctor->doctorAddress->currentCity) ? $doctorPractice->doctor->doctorAddress->currentCity->id : '',
            'current_city_name' => isset($doctorPractice->doctor->doctorAddress->currentCity) ? $doctorPractice->doctor->doctorAddress->currentCity->name : '',
            'home_town_address_1' => $doctorPractice->doctor->doctorAddress->home_town_address_1 ?? '',
            'home_town_address_2' => $doctorPractice->doctor->doctorAddress->home_town_address_2 ?? '',
            'home_town_zip_code' => $doctorPractice->doctor->doctorAddress->home_town_zip_code ?? '',
            'home_country_id' => isset($doctorPractice->doctor->doctorAddress->homeTownCountry) ? $doctorPractice->doctor->doctorAddress->homeTownCountry->id : '',
            'home_country_name' => isset($doctorPractice->doctor->doctorAddress->homeTownCountry) ? $doctorPractice->doctor->doctorAddress->homeTownCountry->name : '',
            'home_state_id' => isset($doctorPractice->doctor->doctorAddress->homeTownState) ? $doctorPractice->doctor->doctorAddress->homeTownState->id : '',
            'home_state_name' => isset($doctorPractice->doctor->doctorAddress->homeTownState) ? $doctorPractice->doctor->doctorAddress->homeTownState->name : '',
            'home_city_id' => isset($doctorPractice->doctor->doctorAddress->homeTownCity) ? $doctorPractice->doctor->doctorAddress->homeTownCity->id : '',
            'home_city_name' => isset($doctorPractice->doctor->doctorAddress->homeTownCity) ? $doctorPractice->doctor->doctorAddress->homeTownCity->name : '',
            'license_number' => $doctorPractice->doctor->doctorLegalInformation->license_number ?? '',
            'emirate_id' => $doctorPractice->doctor->doctorLegalInformation->emirate_id ?? '',
            'passport_number' => $doctorPractice->doctor->doctorLegalInformation->passport_number ?? '',
            'doctor_specializations'       =>  $doctorPractice->doctor->doctorSpecializations ? DoctorSpecializationResource::collection($doctorPractice->doctor->doctorSpecializations) : '',
            'doctor_practice'       =>  $doctorPractice->doctor->doctorPractices ?? '',
            'license_photo_url'       => $doctorPractice->doctor->license_photo_url ?? '',
            'passport_photo_url'       => isset($doctorPractice->doctor->passport_photo_url) ? $doctorPractice->doctor->passport_photo_url : '',
            'emirate_photo_url'       => isset($doctorPractice->doctor->emirate_photo_url) ? $doctorPractice->doctor->emirate_photo_url : '',
            'marital_status'       => isset($doctorPractice->doctor->marital_status) ? $doctorPractice->doctor->marital_status : '',
            'kyc_status'       => isset($doctorPractice->doctor->kyc_status) ? $doctorPractice->doctor->kyc_status : '',
            'is_active'       => isset($doctorPractice->doctor->is_active) ? $doctorPractice->doctor->is_active : '',
            'created_at'       => Carbon::parse($doctorPractice->doctor->created_at)->format('d-m-Y'),
        ];
    }

    /**
     *  Description: Function to show doctor with kyc_status status pending
     *  1) This method is used to list
     *  2) Activity is logged, and a success message is return
     *
     * @return Response
     */
    public function doctorPendingList(): Response
    {
        $doctors = DoctorPractice::where('practice_id', $this->practice_id())->where(function ($query) {
            return $query->with(['doctor' => function ($query) {
                return $query->where('kyc_status', 'Pending');
            }]);
        })->latest('id')->get();
        if ($doctors->count() == 0) {
            $response = $this->response('Pending list', null, DGMBook::FAILED['DOCTOR_NOT_FOUND'], 400, false);
        } else {
            $response = $this->response(true, $doctors, DGMBook::SUCCESS['DOCTOR_LIST'], 200);
        }
        return $response;
    }

    /**
     *  Description: This function doctor appointment list with date filter
     *  1) This method is used to list
     *  2) If doctor_id  & date is passed  from request data show base on date
     *  3) If date is not given then show all doctor base data
     *  4) Activity is logged, and a success message is return
     *
     * @param  mixed $request
     * @return Response
     */
    public function doctorAppointmentList($request): Response
    {

        if ($request['date']) {
            $query =  Appointment::with('doctor', 'patient')->where(['practice_id' => $this->practice_id(), 'doctor_id' => $request['doctor_id']])
                ->latest('id');
            $appointments = app(Pipeline::class)
                ->send($query)
                ->through([
                    Date::class,
                ])
                ->thenReturn()
                ->paginate($request['pagination']);
        } else {
            $appointments =  Appointment::with('doctor', 'patient')->where(['practice_id' => $this->practice_id(), 'doctor_id' => $request['doctor_id']])
                ->latest('id')
                ->paginate($request['pagination']);
        }
        return $this->response(true, $appointments, AGMBook::SUCCESS['APPOINTMENT_LIST_PRACTICE'], 200);
    }

    /**
     * Description: Function for practice to send response to pending Doctor by email
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return Response
     */
    public function doctorPendingListResponse($request, $id): Response
    {
        $doctorPractice = DoctorPractice::with('doctor')->where('doctor_id', $id)->where(function ($query) {
            return $query->with(['doctor' => function ($query) {
                return $query->where('practice_id', $this->practice_id());
            }]);
        })->first();
        if (!$doctorPractice) {
            $response = $this->response(true, null, DGMBook::FAILED['DOCTOR_NOT_FOUND'], 400, false);
        } else {
            // kyc_status is update
            $doctorPractice->doctor->kyc_status = $request['kyc_status'];
            $password = Str::random(10);
            // kyc_status is 1 then update password
            if ($request->kyc_status == "Accepted") {
                $doctorPractice->doctor->password = bcrypt($password);
            } elseif ($request->kyc_status == "Declined") {
                $doctorPractice->doctor->password = null;
            }

            $doctorPractice->doctor->save();
            //if status is accepted then send email to practice register
            if ($request->kyc_status == "Accepted") {
                dispatch(new PasswordOneTime($doctorPractice->doctor, $password))->onQueue(config('constants.DOCTOR_REQUEST_ACCEPT'));
            } elseif ($request->kyc_status == "Declined") {
                dispatch(new RegisterRequestReject($doctorPractice->doctor))->onQueue(config('constants.DOCTOR_REQUEST_REJECT'));
            }
            $response = $this->response(true, $doctorPractice->doctor, DGMBook::SUCCESS['DOCTOR_REQUEST'], 200);
        }
        return $response;
    }

    /**
     *  Description: Function to get list of specializations
     *  1) Returns DoctorCollection returns empty data array if no doctor is found against the passed parameter
     *
     * @return AnonymousResourceCollection
     */
    public function doctorSpecializations(): AnonymousResourceCollection
    {
        $specializations = Specialization::get();

        $this->response('List of specializations', $specializations, DGMBook::SUCCESS['SPECIALIZATION_LIST'], 200);
        return SpecializationResource::collection($specializations);
    }

    /**
     *  Description: Function used by PRACTICE when practice adds all doctor details if request has doctor_id value than it means
     *  the incoming doctor details were first saved as draft by practice and that draft is being now saved else if
     *  no doctor_id is present in the request than all doctor details were added without creating any draft.
     *  1) This method receives all doctor details in request
     *  2) Uses doctorHelper transaction to add doctor
     *  3) Email with KYC verification is sent ot doctor's primary email ID
     *  4) Returns doctor with KYC verification link
     *
     * @param $doctorRequest
     * @return Response
     */
    public function store($doctorRequest): Response
    {
        // Doctors basic details array returned for doctorDetails function and saved in doctorDetails array variable
        $doctorDetails = $this->doctorDetails($doctorRequest);

        // Doctors address array returned for doctorDetails function and saved in doctorAddressDetails array variable
        $doctorAddressDetails = $this->doctorAddressDetails($doctorRequest);

        // Doctors legal information array returned for doctorDetails function and saved in doctorAddressDetails array variable
        $doctorLegalInformation = $this->doctorLegalInformation($doctorRequest);

        // Using database transactions because incoming data in being stored in more than one tables
        $doctorHelper = new DoctorHelper();
        $doctor = $doctorHelper->transaction($doctorDetails, $doctorAddressDetails, $doctorRequest, $doctorLegalInformation, $this->practice_id());
        // Message for activity log
        $message = $doctor->first_name . ' ' . $doctor->middle_name . ' ' . $doctor->last_name . ' ' . DGMBook::SUCCESS['ADDED'];
        Helper::activityLog($message, json_encode(request()->all()), json_encode($doctor));

        // ShuftiPro KYC Confirmation API for face and document
//        $shuftiPro = DoctorHelper::shuftiPro($doctor);

        // Saving verification url to database
//        $doctor->update(['kyc_verification_url' => $shuftiPro['verification_url'], 'kyc_reference_no' => $shuftiPro['reference_no']]);
        // Mail doctor with KYC verification url

//        if ($shuftiPro['verification_url']) {
//            dispatch(new KYCVerification($shuftiPro['verification_url'], $doctor))->onQueue(config('constants.KYC_VERIFICATION'));
//        }

        $doctor->update(['kyc_status' => 'Accepted']);
        return response([
            'success' => true,
            'doctor' => $doctor,
//            'shufti_pro_response' => $shuftiPro['response_data'],
//            'verification_url' => $shuftiPro['verification_url'],
        ], 200);
    }

    /**
     *  Description: Local function used by store function
     *
     * @param $doctorRequest
     * @return array
     */
    function doctorDetails($doctorRequest): array
    {
        return [
            'practice_id' => $this->practice_id(),
            'suffix' => $doctorRequest->suffix,
            'first_name' => $doctorRequest->first_name,
            'middle_name' => $doctorRequest->middle_name,
            'last_name' => $doctorRequest->last_name,
            'primary_email' => $doctorRequest->primary_email,
            'secondary_email' => $doctorRequest->secondary_email,
            'gender' => $doctorRequest->gender,
            'dob' => $doctorRequest->dob,
            'country_code_primary_phone_number' => $doctorRequest->country_code_primary_phone_number,
            'primary_phone_number' => $doctorRequest->primary_phone_number,
            'country_code_secondary_phone_number' => $doctorRequest->country_code_secondary_phone_number,
            'secondary_phone_number' => $doctorRequest->secondary_phone_number,
            'marital_status' => $doctorRequest->marital_status,
            'account_registration' => 1,
            'created_by' => $this->uniqueKey(),
        ];
    }

    /**
     *  Description: Local function used by store function
     *
     * @param $doctorRequest
     * @return array
     */
    function doctorAddressDetails($doctorRequest): array
    {
        return  [
            'current_country_id' => $doctorRequest->current_country_id,
            'current_state_id' => $doctorRequest->current_state_id,
            'current_city_id' => $doctorRequest->current_city_id,
            'home_town_country_id' => $doctorRequest->home_town_country_id,
            'home_town_state_id' => $doctorRequest->home_town_state_id,
            'home_town_city_id' => $doctorRequest->home_town_city_id,
            'current_address_1' => $doctorRequest->current_address_1,
            'current_address_2' => $doctorRequest->current_address_2,
            'current_zip_code' => $doctorRequest->current_zip_code,
            'home_town_address_1' => $doctorRequest->home_town_address_1,
            'home_town_address_2' => $doctorRequest->home_town_address_2,
            'home_town_zip_code' => $doctorRequest->home_town_zip_code,
            'created_by' => $this->uniqueKey(),
        ];
    }

    /**
     *  Description: Local function used by store function
     *
     * @param $doctorRequest
     * @return array
     */
    function doctorLegalInformation($doctorRequest): array
    {
        return  [
            'license_number' => $doctorRequest->license_number,
            'emirate_id' => $doctorRequest->emirate_id,
            'passport_number' => $doctorRequest->passport_number,
            'created_by' => $this->uniqueKey(),
        ];
    }

    /**
     *  Description: Function to see list of requests sent to doctor by practice
     *
     * @param $pagination
     * @return Response
     */
    public function listOfDoctorRequestsSent($pagination): Response
    {
        $doctorPracticeRequests = DoctorPracticeRequest::with('doctor:id,primary_email,first_name,middle_name,last_name')
            ->where('practice_id', $this->practice_id())
            ->orderByDesc('created_at')
            ->paginate($pagination);

        return  $this->response(null, $doctorPracticeRequests, PracticeGlobalMessageBook::SUCCESS['LIST_OF_REQUESTS'], 200);
    }

    /**
     *  Description: Function used by PRACTICE when practice adds doctor details such as name, primary_email,
     *  primary phone number, gender, dob as draft and send doctor link to update all his details. If doctor
     *  is present in portal but not in his particular practice than a request to get register will be sent
     *  via email to get register in his practice. If send_invite parameter is present it means practice wants
     *  doctor to add to his practice
     *  1) This method receives name, primary email, primary email ID, gender, date of birth, send_invite parameters
     *  2) Completed registration link email is sent to doctor in case doctor is not present in practice and portal
     *  3) Request to get register in current practice email is sent to doctor in case doctor is not present in practice
     *     but present on portal
     *  4) Doctor role is assigned to that doctor
     *  5) tab key is sent in response to redirect practice to previous screen (Confirm screen => registration screen)
     *  6) exist key is sent in response to indicate that doctor is present in portal or in currently authenticated
     *     practice (0 => present in practice and 1 => present in portal)
     *  7) Activity log is maintained against API action
     *
     * @param $request
     * @return Response
     */
    public function sendRegistrationLinkToDoctor($request): Response
    {

        $doctor = Doctor::with(['doctorPractices' => function ($query) {
            return $query->where('practice_id', $this->practice_id());
        }, 'doctorPracticeRequests' => function ($query) {
            return $query->where(['practice_id' => $this->practice_id(), 'status' => 'Pending'])->first();
        }])->where('primary_email', $request->primary_email)->first();

        if ($request->send_invite == 1) {
            if (!$doctor) {
                return $this->response($request->all(), null, DGMBook::FAILED['DOCTOR_NOT_FOUND'], 404);
            }
            if (count($doctor->doctorPracticeRequests) > 0) {
                if ($request->has('tab') && $request->tab == 2) {
                    $doctor['tab'] = 2;
                }
                return $this->response($request->all(), $doctor, DGMBook::FAILED['REQUEST_ALREADY_SENT'], 400);
            }
            if (count($doctor->doctorPractices) > 0) {
                return $this->response($request->all(), null, DGMBook::FAILED['DOCTOR_PRESENT'], 400);
            }
            return $this->requestDoctorToGetRegister($doctor);
        }

        if ($doctor && count($doctor->doctorPractices) > 0)      // Doctor already present in practice
        {
            $doctor['exist'] = 0;
            $response = $this->response($request->all(), null, DGMBook::FAILED['DOCTOR_PRESENT'], 400);
        }

        if ($doctor && count($doctor->doctorPractices) == 0)        // Doctor present in portal
        {
            $doctor['exist'] = 1;
            $response = $this->response($request->all(), $doctor, DGMBook::SUCCESS['DOCTOR_PRESENT_ON_PORTAL'], 200);
        }

        if (!$doctor) {
            $response = $this->sendRegistrationLinkToDoctorValidateAndSend($request);
        }

        return $response;
    }

    /**
     *  Description: Function used by sendRegistrationLinkToDoctor function to request a doctor previously present on the platform to get register
     *  in his practice as a doctor.
     *  1) This method receives doctor model instance
     *  2) Email is sent to doctor in order to accept registration invitation
     *  3) Doctor is notified about the invite
     *  4) An entry in doctorRequestRequest model is created
     *  5) tab key is sent in response inorder to redirect user to previous screen (Registration screen => doctors list screen)
     *  6) Returns doctorPractice model instance
     *
     * @param $doctor
     * @return Response
     */
    function requestDoctorToGetRegister($doctor): Response
    {
        $doctorPracticeRequest = DoctorPracticeRequest::where(['doctor_id' => $doctor->id, 'practice_id' => $this->practice_id()])
            ->where('status', 'Rejected')
            ->first();

        if ($doctorPracticeRequest) {
            $doctorPractice = $doctorPracticeRequest;
            $doctorPracticeRequest->update([
                'status' => 'Pending',
                'updated_by' => $this->uniqueKey()
            ]);
            $doctorPracticeRequest->count++;
        }
        else {
            $doctorPractice = DoctorPracticeRequest::create([
                'doctor_id' => $doctor->id,
                'practice_id' => $this->practice_id(),
                'created_by' => $this->uniqueKey()
            ]);
        }

        $doctorPractice->doctor->notify(new RegistrationInvitation($doctorPractice->practice, $doctorPractice->doctor));
        $notification = DatabaseNotification::where(['notifiable_id' => $doctor->id, 'read_at' => null])->where('notifiable_type', 'App\Models\Doctor\Doctor')->orderByDesc('created_at')->first();
        $unread_notifications_count = $doctorPractice->doctor->unreadNotifications()->count();
        $total_notifications = $doctorPractice->doctor->notifications()->count();

        dispatch(new SendInvitationToDoctorForRegistration($doctorPractice, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.SEND_INVITATION_TO_DOCTOR_NOTIFICATION'));
        dispatch(new RequestDoctorToGetRegister($doctorPractice))->onQueue(config('constants.REQUEST_DOCTOR_TO_GET_REGISTER'));

        $doctor['tab'] = 1;
        return $this->response($doctor, $doctorPractice, DGMBook::SUCCESS['REQUEST_DOCTOR_TO_GET_REGISTER'], 200);
    }

    /**
     *  Description: Function used by sendRegistrationLinkToDoctor function to send a doctor for complete his registration
     *  1) This method receives request instance
     *  2) Custom validation is done in the function
     *  3) Random password is generated is set as doctor credentials using otpGenerator function present in helper file
     *  4) Transaction is added inorder to insert values in more than one table
     *  5) Email sent to doctor with credentials inorder to complete his registration
     *  6) tab key is sent to redirect practice to previous screen (registration screen => list of doctors screen)
     *  7) Returns doctor model instance
     *
     * @param $request
     * @return Response
     */
    function sendRegistrationLinkToDoctorValidateAndSend($request): Response
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'primary_email' => ['required', 'email'],
            'country_code_primary_phone_number' => ['required'],
            'primary_phone_number' => ['required'],
            'gender' => ['required'],
            'dob' => ['required'],
        ], [
            'first_name.required' => 'Please enter first name.',
            'middle_name.required' => 'Please enter middle name.',
            'last_name.required' => 'Please enter last name.',
            'primary_email.required' => 'Please enter primary email.',
            'primary_email.email' => 'Please valid email.',
            'country_code_primary_phone_number.required' => 'Please enter country code.',
            'primary_phone_number.required' => 'Please enter primary phone number.',
            'gender.required' => 'Please select gender.',
            'dob.required' => 'Please enter date of brith.',
        ]);

        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'success'   => false,
                'error'      => $validator->errors()
            ], 422));
        }

        $password = Helper::otpGenerator();
        $data = $this->storeDoctorDraftDetails($request->all(), $password);
        $doctor = DB::transaction(function () use ($data, $password) {
            $doctor = Doctor::create($data);
            $doctor->update(['doctor_key' => 'doctor-' . $doctor->id]);
            $role = Role::with('permissions')->where(['name' => 'Doctor', 'guard_name' => 'doctor-api'])->first();

            $doctorPractice = $doctor->doctorPractices()->create([
                'doctor_id' => $doctor->id,
                'practice_id' => $this->practice_id(),
                'role_id' => $role->id,
                'role_name' => $role->name,
                'doctor_status_in_practice' => 1,
                'currently_active_in_practice_status' => 1,
                'created_by' => $this->uniqueKey(),
            ]);

            foreach ($role->permissions as $permission) {
                $doctorPractice->doctorPracticePermissions()->create([
                    'permission_id' => $permission->id,
                    'permission_name' => $permission->name,
                ]);
            }

            $doctor->assignRole($role);

            dispatch(new SendRegistrationLinkToDoctor($doctor, $password, $doctor->practice))->onQueue(config('constants.SEND_REGISTRATION_LINK_TO_DOCTOR'));

            return $doctor;
        });

        $doctor['tab'] = 1;
        return $this->response($request->all(), $doctor, DGMBook::SUCCESS['REGISTRATION_LINK_SENT_TO_DOCTOR'], 200);
    }

    /**
     *  Description: Local function used by sendRegistrationLinkToDoctor function
     *
     * @param $request
     * @param $password
     * @return array
     */
    function storeDoctorDraftDetails($request, $password): array
    {
        return [
            'first_name'    => $request['first_name'],
            'middle_name'    => $request['middle_name'],
            'last_name'    => $request['last_name'],
            'primary_email'    => $request['primary_email'],
            'password'    => Hash::make($password),
            'country_code_primary_phone_number'    => $request['country_code_primary_phone_number'],
            'primary_phone_number'    => $request['primary_phone_number'],
            'gender'    => $request['gender'],
            'dob'    => $request['dob'],
            'practice_id' => $this->practice_id(),
            'created_by' => $this->uniqueKey(),
            'draft_status' => 1,
        ];
    }

    /**
     *  Description: Function used by practice to get his all registered doctor's appointments to view in a calendar for selected date
     *  1) Start date and end date are passed for between dates scenario as a request for appointments
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
                ->where('practice_id', $this->practice_id())
                ->whereBetween('date', [$request->start_date , $request->end_date])
                ->whereNotIn('status', array('Cancelled', 'Rescheduled'))
                ->when($request->has('doctor_id'), function($query) use ($request){
                    return $query->where('doctor_id', $request->doctor_id);
                })
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
                ->where('practice_id', $this->practice_id())
                ->whereNotIn('status', array('Cancelled', 'Rescheduled'))
                ->whereDate('date' , $request->start_date)
                ->when($request->has('doctor_id'), function($query) use ($request){
                    return $query->where('doctor_id', $request->doctor_id);
                })
                ->get();
        }

        return $this->response('Doctors appointments list for calendar view', $doctors, PracticeGlobalMessageBook::SUCCESS['DOCTOR_CALENDAR_APPOINTMENTS'], 200);
    }
}
