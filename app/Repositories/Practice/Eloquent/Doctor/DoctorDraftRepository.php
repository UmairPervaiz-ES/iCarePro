<?php

namespace App\Repositories\Practice\Eloquent\Doctor;

use App\Helper\Doctor as DoctorHelper;
use App\Helper\Helper;
use App\Jobs\Doctor\KYCVerification;
use App\libs\Messages\DoctorGlobalMessageBook as DGMBook;
use App\Models\Doctor\Doctor;
use App\Repositories\Practice\Interfaces\Doctor\DoctorDraftRepositoryInterface;
use App\Traits\CreateOrUpdate;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class DoctorDraftRepository implements DoctorDraftRepositoryInterface
{
    use RespondsWithHttpStatus;
    use CreateOrUpdate;

    /**
     * @var int|mixed|string|null
     */
    private mixed $doctorID;

    /**
     *  Description: Doctor ID is initialized using helper function in order to use across the repository.
     *  1) Request is passed in order to determine who is requesting doctor or practice/staff
     *  2) If in-coming request has doctor_id than requesting user is practice/staff otherwise it is doctor requesting
     */
    public function __construct(Request $request)
    {
        $this->doctorID = Helper::doctor_id($request);
    }
    // DRAFT FUNCTIONS FOR PRACTICE STARTS (3 FUNCTIONS CREATE, UPDATE, STORE)

    /**
     *  Description: Functions to save doctor details saved as draft
     *  1) This method receives all doctor details in request
     *  2) Uses doctorHelper transaction to add doctor
     *  3) Email with KYC verification is sent ot doctor's primary email ID
     *  4) Returns doctor with KYC verification link
     *
     * @param $request
     * @return Response
     */
    public function store($request): Response
    {
        // Doctors basic details array returned for doctorDetails function and saved in doctorDetails array variable
        $doctorDetails = $this->doctorDetails($request, $this->practice_id(), 0, $this->uniqueKey());

        // Doctors address array returned for doctorDetails function and saved in doctorAddressDetails array variable
        $doctorAddressDetails = $this->doctorAddressDetails($request, $this->uniqueKey());

        // Doctors legal information array returned for doctorDetails function and saved in doctorAddressDetails array variable
        $doctorLegalInformation = $this->doctorLegalInformationDetails($request, $this->uniqueKey());

        $doctorHelper = new DoctorHelper();
        $doctor = $doctorHelper->transaction($doctorDetails, $doctorAddressDetails, $request, $doctorLegalInformation, $this->practice_id());

        // Message for activity log
        $message = $doctor->first_name. ' ' . $doctor->middle_name. ' ' . $doctor->last_name . ' ' . DGMBook::SUCCESS['ADDED_DOCTOR_THROUGH_DRAFT'];
        Helper::activityLog($message, json_encode(request()->all()), json_encode($doctor));

        // ShuftiPro KYC Confirmation API for face and document
        $shuftiPro = DoctorHelper::shuftiPro($doctor);

        // Saving verification url to database
        $doctor->update(['kyc_verification_url' => $shuftiPro['verification_url'], 'kyc_reference_no' => $shuftiPro['reference_no']]);
        // Mail doctor with KYC verification url

        if ($shuftiPro['verification_url'])
        {
            dispatch(new KYCVerification($shuftiPro['verification_url'], $doctor))->onQueue(config('constants.KYC_VERIFICATION'));
        }

        return response([
            'success' => true,
            'doctor' => $doctor,
            'shufti_pro_response' => $shuftiPro['response_data'],
            'verification_url' => $shuftiPro['verification_url'],
        ], 200);
    }

    /**
     *  Description: Function to save doctor details as draft.
     *  1) This method receives doctor details in request to be saved as draft
     *  2) Doctor as a draft is saved with entered details
     *  3) Returns doctor model instance with success message
     *
     * @param $request
     * @return Response
     */
    public function createDoctorAsDraft($request): Response
    {
        // Doctors basic details array returned for doctorDetails function and saved in doctorDetails array variable
        $doctorDetails = $this->doctorDetails($request, $this->practice_id(), 1, $this->uniqueKey());

        // Doctors address array returned for doctorDetails function and saved in doctorAddressDetails array variable
        $doctorAddressDetails = $this->doctorAddressDetails($request, $this->uniqueKey());

        // Doctors legal information array returned for doctorDetails function and saved in doctorAddressDetails array variable
        $doctorLegalInformation = $this->doctorLegalInformationDetails($request, $this->uniqueKey());

        $doctor = Doctor::create($doctorDetails);
        $doctor->update(['doctor_key' => 'doctor-' . $doctor->id]);      // Updating doctor_key after doctor is created because for now it saves doctor ID with static string.

        ################## Saving doctor's profile_photo_url ##################
        if ($request->profile_photo_url) {
            $doctorHelper = new DoctorHelper();
            $doctorHelper->profile_photo_url($request, $request->profile_photo_url, $this->practice_id(), $doctor);
        }

        // Saving doctor's license_photo_url
        if ($request->license_photo_url){
            $doctorHelper = new DoctorHelper();
            $doctorHelper->license_photo_url($request, $request->license_photo_url, $this->practice_id(), $doctor);
        }

        ################## Saving doctor's passport_photo_url ##################
        if ($request->passport_photo_url){
            $doctorHelper = new DoctorHelper();
            $doctorHelper->passport_photo_url($request, $request->passport_photo_url, $this->practice_id(), $doctor);
        }

        ################## Saving doctor's emirate_photo_url ##################
        if ($request->emirate_photo_url){
            $doctorHelper = new DoctorHelper();
            $doctorHelper->emirate_photo_url($request, $request->emirate_photo_url, $this->practice_id(), $doctor);
        }

        if ($doctorAddressDetails) {
            $doctor->doctorAddress()->create($doctorAddressDetails);
        }

        // Saving doctors' specializations
        if ($request->specialization_id) {
            $this->doctorSpecializations($doctor, $request->specialization_id, $this->uniqueKey());
        }

        // COMMENTED FOR NOW
        // Saving doctors' uploaded documents
        /*foreach ($request->file('file_path') as $doctorDocument)
        {
            $filenameWithExt = $doctorDocument->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $doctorDocument->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // Upload Image
            $path = $doctorDocument->storeAs('public/practice/'. auth()->guard('practice-api')->id() . '/' .'doctor/'.$doctor->id . '/documents' , $fileNameToStore);

            $doctor->doctorDocuments()->create([
                'file_path' => $path,
                'created_by' => auth()->guard('practice-api')->user()->practice_key,
            ]);
        }*/

        // Saving doctors' legal information
        if ($doctorLegalInformation) {
            $doctor->doctorLegalInformation()->create($doctorLegalInformation);
        }

        return $this->response($request->all(), $doctor, DGMBook::SUCCESS['ADDED_DOCTOR_DRAFT'], 201);
    }

    /**
     *  Description: Update function to update doctor details to saved as draft
     *  1) This method receives doctor details in request to updated saved as draft
     *  2) Doctor as a draft is updated with entered details
     *  3) Returns doctor model instance with success message
     *
     * @param $request
     * @return Response
     */
    public function updateDoctorDraft($request): Response
    {
        // Doctors basic details array returned for doctorDetails function and saved in doctorDetails array variable
        $doctorDetails = $this->doctorDetails($request, $this->practice_id(),1, $this->uniqueKey());

        // Doctors address array returned for doctorDetails function and saved in doctorAddressDetails array variable
        $doctorAddressDetails = $this->doctorAddressDetails($request, $this->uniqueKey());

        // Doctors legal information array returned for doctorDetails function and saved in doctorAddressDetails array variable
        $doctorLegalInformation = $this->doctorLegalInformationDetails($request, $this->uniqueKey());

        $doctor = Doctor::where('id', $this->doctorID)->first();
        $doctor->update($doctorDetails);

        ################## Saving doctor's profile_photo_url ##################
        if ($request->profile_photo_url) {
            $doctorHelper = new DoctorHelper();
            $doctorHelper->profile_photo_url($request, $request->profile_photo_url, $this->practice_id(), $doctor);
        }

        // Saving doctor's license_photo_url
        if ($request->license_photo_url) {
            $doctorHelper = new DoctorHelper();
            $doctorHelper->license_photo_url($request, $request->license_photo_url, $this->practice_id(), $doctor);
        }

        ################## Saving doctor's passport_photo_url ##################
        if ($request->passport_photo_url) {
            $doctorHelper = new DoctorHelper();
            $doctorHelper->passport_photo_url($request, $request->passport_photo_url, $this->practice_id(), $doctor);
        }

        ################## Saving doctor's emirate_photo_url ##################
        if ($request->emirate_photo_url) {
            $doctorHelper = new DoctorHelper();
            $doctorHelper->emirate_photo_url($request, $request->emirate_photo_url, $this->practice_id(), $doctor);
        }

        if ($doctorAddressDetails) {
            if (isset($doctor->doctorAddress))
            {
                $doctor->doctorAddress()->update($doctorAddressDetails);
            }
            else
            {
                $doctor->doctorAddress()->create($doctorAddressDetails);
            }
        }

        // Saving doctors' specializations
        if ($request->specialization_id)
        {
            $this->doctorSpecializations($doctor, $request->specialization_id, $this->uniqueKey());
        }

        // COMMENTED FOR NOW
        // Saving doctors' uploaded documents
        /*foreach ($request->file('file_path') as $doctorDocument)
        {
            $filenameWithExt = $doctorDocument->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $doctorDocument->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // Upload Image
            $path = $doctorDocument->storeAs('public/practice/'. auth()->guard('practice-api')->id() . '/' .'doctor/'.$doctor->id . '/documents' , $fileNameToStore);

            $doctor->doctorDocuments()->create([
                'file_path' => $path,
                'created_by' => auth()->guard('practice-api')->user()->practice_key,
            ]);
        }*/

        // Saving doctors' legal information
        if ($doctorLegalInformation) {
            if (isset($doctor->doctorLegalInformation)){
                $doctor->doctorLegalInformation()->update($doctorLegalInformation);
            }
            else{
                $doctor->doctorLegalInformation()->create($doctorLegalInformation);
            }
        }

        return $this->response($request->all(), $doctor, DGMBook::SUCCESS['UPDATE_DOCTOR_DRAFT'],200);
    }

    // DRAFT FUNCTIONS FOR PRACTICE ENDS

    // DRAFT FUNCTIONS FOR DOCTOR STARTS (2 FUNCTIONS UPDATE AND STORE)

    /**
     *  Description: Store function when DOCTOR adds his details via link sent to him
     *  1) This method receives all doctor details in request by doctor
     *  2) Uses doctorHelper transaction to add doctor
     *  3) Email with KYC verification is sent ot doctor's primary email ID
     *  4) Returns doctor with KYC verification link
     *
     * @param $request
     * @return Response
     */
    public function storeDoctorDraftByDoctor($request): Response
    {
        // Doctors basic details array returned for doctorDetails function and saved in doctorDetails array variable
        $doctorDetails = $this->doctorDetails($request, $this->practice_id(), 0, $this->uniqueKey());

        // Doctors address array returned for doctorDetails function and saved in doctorAddressDetails array variable
        $doctorAddressDetails = $this->doctorAddressDetails($request, $this->uniqueKey());

        // Doctors legal information array returned for doctorDetails function and saved in doctorAddressDetails array variable
        $doctorLegalInformation = $this->doctorLegalInformationDetails($request, $this->uniqueKey());

        $doctorHelper = new DoctorHelper();
        $doctor = $doctorHelper->transaction($doctorDetails, $doctorAddressDetails, $request, $doctorLegalInformation, $this->practice_id());
        $doctor->update(['account_registration' => 1]);

        // Message for activity log
        $message = $doctor->first_name. ' ' . $doctor->middle_name. ' ' . $doctor->last_name . ' ' . DGMBook::SUCCESS['ADDED_DOCTOR_THROUGH_DRAFT'];
        Helper::activityLog($message, json_encode(request()->all()), json_encode($doctor));

        // ShuftiPro KYC Confirmation API for face and document
//        $shuftiPro = DoctorHelper::shuftiPro($doctor);

        // Saving verification url to database
//        $doctor->update(['kyc_verification_url' => $shuftiPro['verification_url'], 'kyc_reference_no' => $shuftiPro['reference_no']]);
        // Mail doctor with KYC verification url
//        if ($shuftiPro['verification_url'])
//        {
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
     *  Description: Update function when DOCTOR updates his details via link sent to him.
     *  1) This method receives doctor details in request by doctor
     *  2) Updates relevant entered details by doctor
     *  4) Returns doctor model instance
     *
     * @param $request
     * @return Response
     */
    public function updateDoctorDraftByDoctor($request): Response
    {
        // Doctors basic details array returned for doctorDetails function and saved in doctorDetails array variable
        $doctorDetails = $this->doctorDetails($request, $this->practice_id(),1, $this->uniqueKey());

        // Doctors address array returned for doctorDetails function and saved in doctorAddressDetails array variable
        $doctorAddressDetails = $this->doctorAddressDetails($request, $this->uniqueKey());

        // Doctors legal information array returned for doctorDetails function and saved in doctorAddressDetails array variable
        $doctorLegalInformation = $this->doctorLegalInformationDetails($request, $this->uniqueKey());

        $doctor = Doctor::where('id', $this->doctorID)->first();
        $doctor->update($doctorDetails);

        ################## Saving doctor's profile_photo_url ##################
        if ($request->profile_photo_url) {
            $doctorHelper = new DoctorHelper();
            $doctorHelper->profile_photo_url($request, $request->profile_photo_url, $this->practice_id(), $doctor);
        }

        // Saving doctor's license_photo_url
        if ($request->license_photo_url) {
            $doctorHelper = new DoctorHelper();
            $doctorHelper->license_photo_url($request, $request->license_photo_url, $this->practice_id(), $doctor);
        }

        ################## Saving doctor's passport_photo_url ##################
        if ($request->passport_photo_url) {
            $doctorHelper = new DoctorHelper();
            $doctorHelper->passport_photo_url($request, $request->passport_photo_url, $this->practice_id(), $doctor);
        }

        ################## Saving doctor's emirate_photo_url ##################
        if ($request->emirate_photo_url) {
            $doctorHelper = new DoctorHelper();
            $doctorHelper->emirate_photo_url($request, $request->emirate_photo_url, $this->practice_id(), $doctor);
        }

        if ($doctorAddressDetails) {
            if (isset($doctor->doctorAddress)){
                $doctor->doctorAddress()->update($doctorAddressDetails);
            }
            else{
                $doctor->doctorAddress()->create($doctorAddressDetails);
            }
        }

        // Saving doctors' specializations
        if ($request->specialization_id)
        {
            $this->doctorSpecializations($doctor, $request->specialization_id, $this->uniqueKey());
        }

        // COMMENTED FOR NOW
        // Saving doctors' uploaded documents
        /*foreach ($request->file('file_path') as $doctorDocument)
        {
            $filenameWithExt = $doctorDocument->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $doctorDocument->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // Upload Image
            $path = $doctorDocument->storeAs('public/practice/'. auth()->guard('practice-api')->id() . '/' .'doctor/'.$doctor->id . '/documents' , $fileNameToStore);

            $doctor->doctorDocuments()->create([
                'file_path' => $path,
                'created_by' => auth()->guard('doctor-api')->user()->doctor_key,
            ]);
        }*/

        // Saving doctors' legal information
        if ($doctorLegalInformation)
        {
            if (isset($doctor->doctorLegalInformation))
            {
                $doctor->doctorLegalInformation()->update($doctorLegalInformation);
            }
            else{
                $doctor->doctorLegalInformation()->create($doctorLegalInformation);
            }
        }

        return $this->response($request->all(),$doctor,DGMBook::SUCCESS['UPDATE_DOCTOR_DRAFT'],200);
    }

    // DRAFT FUNCTIONS FOR DOCTOR ENDS

    /**
     *  Description: Local function
     *
     * @param $request
     * @param $practice_id
     * @param $draft_status
     * @param $uniqueKey
     * @return array
     */
    function doctorDetails($request, $practice_id, $draft_status, $uniqueKey): array
    {
        $data = [
            'practice_id' => $practice_id,
            'suffix' => $request->suffix,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'primary_email' => $request->primary_email,
            'secondary_email' => $request->secondary_email,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'country_code_primary_phone_number' => $request->country_code_primary_phone_number,
            'primary_phone_number' => $request->primary_phone_number,
            'country_code_secondary_phone_number' => $request->country_code_secondary_phone_number,
            'secondary_phone_number' => $request->secondary_phone_number,
            'marital_status' => $request->marital_status,
            'draft_status' => $draft_status,
            'created_by' => $uniqueKey,
        ];
        // Replacing created_by with updated_by depending upon the incoming request
        if ((Route::currentRouteName() == 'practice.store-doctor-as-draft' && $request->has('doctor_id')) || (Route::currentRouteName() == 'staff.store-doctor-as-draft' && $request->has('doctor_id')) || Route::currentRouteName() == 'update-doctor-draft-by-doctor')
        {
            $data['updated_by'] = $data['created_by'];
            unset($data['created_by']);
        }
        if (Route::currentRouteName() == 'storeDoctorDraftByDoctor' || Route::currentRouteName() == 'practice.store-doctor-draft' || Route::currentRouteName() == 'staff.store-doctor-draft')
        {
            $data['account_registration'] = 1;
        }

        return $data;
    }

    /**
     *  Description: Local function
     *
     * @param $request
     * @param $uniqueKey
     * @return array
     */
    function doctorAddressDetails($request, $uniqueKey): array
    {
        $data =  [
            'current_country_id' => $request->current_country_id,
            'current_state_id' => $request->current_state_id,
            'current_city_id' => $request->current_city_id,
            'home_town_country_id' => $request->home_town_country_id,
            'home_town_state_id' => $request->home_town_state_id,
            'home_town_city_id' => $request->home_town_city_id,
            'current_address_1' => $request->current_address_1,
            'current_address_2' => $request->current_address_2,
            'current_zip_code' => $request->current_zip_code,
            'home_town_address_1' => $request->home_town_address_1,
            'home_town_address_2' => $request->home_town_address_2,
            'home_town_zip_code' => $request->home_town_zip_code,
            'created_by' => $uniqueKey,
        ];
        $doctor = Doctor::with('doctorLegalInformation')->where('doctor_key', $uniqueKey)->first();
        // Replacing created_by with updated_by depending upon the incoming request
        if ((Route::currentRouteName() == 'practice.store-doctor-as-draft' && $request->has('doctor_id')) || (Route::currentRouteName() == 'staff.store-doctor-as-draft' && $request->has('doctor_id')) || Route::currentRouteName() == 'update-doctor-draft-by-doctor')
        {
            if ($doctor->doctorAddress)
            {
                $data['updated_by'] = $data['created_by'];
                unset($data['created_by']);
            }
        }
        return $data;
    }

    /**
     *  Description: Local function
     *
     * @param $request
     * @param $uniqueKey
     * @return array
     */
    function doctorLegalInformationDetails($request, $uniqueKey): array
    {
        $data =  [
            'license_number' => $request->license_number,
            'emirate_id' => $request->emirate_id,
            'passport_number' => $request->passport_number,
            'created_by' => $uniqueKey,
        ];
        $doctor = Doctor::with('doctorLegalInformation')->where('doctor_key', $uniqueKey)->first();
        // Replacing created_by with updated_by depending upon the incoming request
        if ((Route::currentRouteName() == 'practice.store-doctor-as-draft' && $request->has('doctor_id')) || (Route::currentRouteName() == 'staff.store-doctor-as-draft' && $request->has('doctor_id')) || Route::currentRouteName() == 'update-doctor-draft-by-doctor')
        {
            if ($doctor->doctorLegalInformation)
            {
                $data['updated_by'] = $data['created_by'];
                unset($data['created_by']);
            }
        }
        return $data;
    }

    /**
     *  Description: Local function
     *
     * @param $doctor
     * @param $requestSpecialization_id
     * @param $uniqueKey
     * @return void
     */
    function doctorSpecializations($doctor, $requestSpecialization_id, $uniqueKey)
    {
        if ($doctor->doctorSpecializations()->exists()){
            $doctor->doctorSpecializations()->delete();
            foreach ($requestSpecialization_id as $specializationID) {
                $doctor->doctorSpecializations()->create([
                    'specialization_id' => $specializationID,
                    'updated_by' => $uniqueKey,
                ]);
            }
        }
        else{
            foreach ($requestSpecialization_id as $specializationID) {
                $doctor->doctorSpecializations()->create([
                    'specialization_id' => $specializationID,
                    'created_by' => $uniqueKey,
                ]);
            }
        }
    }

    /**
     *  Description: Get function to show DOCTOR his details when Doctor wants to
     *  update his details via link sent to him at the time of his registration
     *  1) This method receives doctor details in request by doctor
     *  2) Updates relevant entered details by doctor
     *  4) Returns doctor model instance
     * @return Response
     */
    public function getDoctorDetails(): Response
    {
        $doctor = Doctor::with('doctorAddress', 'doctorSpecializations', 'doctorLegalInformation')->where('id', $this->doctorID)->first();

        if (!$doctor)
        {
            $response = $this->response(null,null, DGMBook::FAILED['DOCTOR_NOT_FOUND'],400,false);
        }
        else
        {
            $doctorDetails = $this->arrayOfDoctorDetails($doctor);

            $doctorDetails['specialization_id'] = $doctor->doctorSpecializations->pluck('specialization_id');
            $response = $this->response($doctor, $doctorDetails, DGMBook::SUCCESS['DOCTOR_DETAILS'], 200);
        }
        return $response;
    }

    /**
     *  Description: Local function to reorganise data array as required by font-end
     * @param $doctor
     * @return array
     */
    function arrayOfDoctorDetails($doctor): array
    {
        return [
            'practice_id' => $doctor->practice_id,
            'doctor_key' => $doctor->doctor_key,
            'practice_country_name' => $doctor->practice->practiceAddress->country->name,
            'suffix' => $doctor->suffix,
            'first_name' => $doctor->first_name,
            'middle_name' => $doctor->middle_name,
            'last_name' => $doctor->last_name,
            'primary_email' => $doctor->primary_email,
            'secondary_email' => $doctor->secondary_email,
            'update_primary_email' => $doctor->update_primary_email,
            'gender' => $doctor->gender,
            'dob' => $doctor->dob,
            'profile_photo_url' => $doctor->profile_photo_url,
            'about_me' => $doctor->about_me,
            'country_code_primary_phone_number' => $doctor->country_code_primary_phone_number,
            'primary_phone_number' => $doctor->primary_phone_number,
            'country_code_secondary_phone_number' => $doctor->country_code_secondary_phone_number,
            'secondary_phone_number' => $doctor->secondary_phone_number,
            'license_photo_url' => $doctor->license_photo_url,
            'passport_photo_url' => $doctor->passport_photo_url,
            'emirate_photo_url' => $doctor->emirate_photo_url,
            'marital_status' => $doctor->marital_status,
            'account_registration' => $doctor->account_registration,
            'is_first_login' => $doctor->is_first_login,
            'is_password_reset' => $doctor->is_password_reset,
            'current_country_id' => isset($doctor->doctorAddress) ? $doctor->doctorAddress->current_country_id : null,
            'current_state_id' => isset($doctor->doctorAddress) ? $doctor->doctorAddress->current_state_id : null,
            'current_city_id' => isset($doctor->doctorAddress) ? $doctor->doctorAddress->current_city_id : null,
            'home_town_country_id' => isset($doctor->doctorAddress) ? $doctor->doctorAddress->home_town_country_id : null,
            'home_town_state_id' => isset($doctor->doctorAddress) ? $doctor->doctorAddress->home_town_state_id : null,
            'home_town_city_id' => isset($doctor->doctorAddress) ? $doctor->doctorAddress->home_town_city_id : null,
            'current_address_1' => isset($doctor->doctorAddress) ? $doctor->doctorAddress->current_address_1 : null,
            'current_address_2' => isset($doctor->doctorAddress) ? $doctor->doctorAddress->current_address_2 : null,
            'current_zip_code' => isset($doctor->doctorAddress) ? $doctor->doctorAddress->current_zip_code : null,
            'home_town_address_1' => isset($doctor->doctorAddress) ? $doctor->doctorAddress->home_town_address_1 : null,
            'home_town_address_2' => isset($doctor->doctorAddress) ? $doctor->doctorAddress->home_town_address_2 : null,
            'home_town_zip_code' => isset($doctor->doctorAddress) ? $doctor->doctorAddress->home_town_zip_code : null,
            'license_number' => isset($doctor->doctorLegalInformation) ? $doctor->doctorLegalInformation->license_number : null,
            'emirate_id' => isset($doctor->doctorLegalInformation) ? $doctor->doctorLegalInformation->emirate_id : null,
            'passport_number' => isset($doctor->doctorLegalInformation) ? $doctor->doctorLegalInformation->passport_number : null,
        ];
    }
}
