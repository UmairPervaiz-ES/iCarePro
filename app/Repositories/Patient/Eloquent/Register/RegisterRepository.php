<?php

namespace App\Repositories\Patient\Eloquent\Register;

use App\Helper\Helper;
use App\Jobs\Patient\SendSmsMessageToPatient;
use App\Jobs\Patient\SendSmsMessageToPatientWithCode;
use App\Jobs\Practice\SendRegistrationLinkToPatient;
use App\libs\Messages\PatientGlobalMessageBook as PGMBook;
use App\Mail\Practice\SendRegistrationMessageToPatient;
use App\Models\{Patient\Patient, Practice\PracticePatient};
use App\Models\OtpVerification\OtpVerification;
use App\Models\Practice\InitialPractice;
use App\Models\Practice\Practice;
use App\Repositories\Patient\Interfaces\Register\RegisterRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use App\Traits\SmsSendTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;


class RegisterRepository implements RegisterRepositoryInterface
{
    use RespondsWithHttpStatus;
    use SmsSendTrait;

    protected $phone_number;
    protected $country_code;

    function __construct(Request $request)
    {
        $this->phone_number = $request->phone_no;
        $this->country_code = $request->phone_no_code;
    }

    /**
     * Description: Register Patient user data
     * 1) This method is used to register patient by two way
     * 2) If one way patient register yourself
     * 3) If practice register patient so also pass those practice id which we use Auth
     * 4) if pass practice_id so also create new record in practice_patients table
     * 5) send request field and also validate this request for required field
     * 6) If credentials are wrong invalid credentials message will return error message
     * 7) In case of valid credentials, check then register patient
     * 8) Also send verification message to patient email and patient phone number
     * 9) In case of any exception error message in response is return
     * @param  mixed $request
     * @return Response
     */
    public function registerPatient($request): Response
    {
        // check patient exist
        if (Patient::where('phone_number', $request['phone_number'])->where('country_code', $request['country_code'])->exists()) {
            $response_message = PGMBook::SUCCESS['PATIENT_REGISTER_CHECK'];
            $status = 401;
            $data = null;
            $success = false;
        } else {
            //check auth
            $auth = $this->uniqueKey();

            $patient_variable =  $this->patientSetVariables($request);

            // save patient
            $patient = $this->createPatient($request, $patient_variable['patient_key'], $patient_variable['path'], $patient_variable['created_by'], $patient_variable['is_phone_number_verified']);

            if ($auth != 'web') {
                $practice_Patient = new PracticePatient;
                $practice_Patient->patient_id = $patient->id;
                $practice_Patient->practice_id = $request->practice_id;
                $practice_Patient->save();

                $practice = InitialPractice::where('id', $request['practice_id'])->first('practice_name');
                $practice_name = $practice->practice_name;
                dispatch(new SendRegistrationLinkToPatient($patient, $practice_name))->onQueue(config('constants.SEND_REGISTRATION_LINK_PATIENT'));
            }
            $response_message = PGMBook::SUCCESS['REGISTER'];
            $status = 201;
            $data = $patient;
            $success = true;
        }
        return $this->response($request, $data, $response_message, $status, $success);
    }

    /**
     * Description: Check patient register or not
     * 1) This method is used to check  patient registration
     * 2) If patient register so return those patient basic data and also the condition
     * 3) that number verified, 1st login, or patient set password in the form of true false
     * 4) I f not register then also return message
     * 5) In case of any exception error message in response is return
     * @param  mixed $request
     * @return Response
     */
    public function checkPatientExist($request): Response
    {
        $patient_check = Patient::where('phone_number', $request['phone_number'])->where('country_code', $request['country_code'])->first(['id', 'patient_key', 'first_name', 'last_name', 'middle_name', 'phone_number', 'email', 'gender', 'dob', 'is_phone_number_verified', 'is_password_reset', 'is_first_login']);

        if (!empty($patient_check)) {
            $condition_status = [
                'is_phone_number_verified' => $patient_check->is_phone_number_verified,
                'is_password_reset' => $patient_check->is_password_reset,
                'is_first_login' => $patient_check->is_first_login,
            ];
            // Message for activity log
            $message = PGMBook::SUCCESS['PATIENT_REGISTER_CHECK'];
            $this->activityLogs($message, $request, $patient_check);        // Parameters ($message, $request , $response)
            return response([
                'success' => true,
                'message' => PGMBook::SUCCESS['PATIENT_REGISTER_CHECK'],
                'data' => $patient_check,
                'patient_condition_status' => $condition_status,
            ], 200);
        } else {
            return $this->response($request, $patient_check, PGMBook::SUCCESS['PATIENT_NOT_REGISTER_CHECK'], 200);
        }
    }

    /**
     * Description: Check patient Login condition
     * 1) This method is used to check  patient registration
     * 2) patient exist or not if not return message
     * 3) number verified or not if not return message
     * 4) 1st login true or not also find in this condition check patient set password or not
     * 5) In case of any exception error message in response is return
     * 6) use in future
     * @param  mixed $request
     * @return void
     */
    public function checkPatientLogin($request): Response
    {
        $patient = Patient::where('phone_number', $request['phone_number'])
            ->where('country_code', $request['country_code'])->first();

        if ($patient) {
            if ($patient->is_phone_number_verified == '0') {
                $response_message = PGMBook::SUCCESS['PATIENT_NUMBER_CHECK'];
                $status = 200;
                $data = $patient;
                $success = true;
            }
            if ($patient->is_login == '0') {
                $response_message = PGMBook::SUCCESS['PATIENT_LOGIN_CHECK'];
                $status = 200;
                $data = $patient;
                $success = true;
            }
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_NOT_REGISTER_CHECK'];
            $status = 200;
            $data = $patient;
            $success = true;
        }
        return $this->response($request, $data, $response_message, $status, $success);
    }

    /**
     * Description: Send mobile verification code
     * 1) This method is used to send  mobile verification code to patient for verification so 1st we check
     * 2) Check  patient exist or not if not return message
     * 3) number is valid format  or not if not return message
     * 4) If all is then send message to those number
     * 5) In case of any exception error message in response is return
     * @param  mixed $request
     * @return Response
     */
    public function sendMobileVerificationCode($request)
    {
        $patient = Patient::where('phone_number', $request->phone_number)->first();
        if (empty($patient)) {
            $response_message = PGMBook::FAILED['PATIENT_NOT_FOUND'];
            $status = 400;
            $data = null;
            $success = false;
        } else {
            // $code = rand(111111, 999999);
            $code = 123456;
            $country_code = $request->country_code;
            $phone_number = $request->phone_number;

            dispatch(new SendSmsMessageToPatientWithCode($country_code, $phone_number, $code));

            Helper::otpVerification($patient->id, Auth::getDefaultDriver(), $code, 1, $phone_number);
            $response_message = PGMBook::SUCCESS['VERIFICATION_CODE_SENT'];
            $status = 200;
            $data = null;
            $success = true;
        }
        return $this->response($request, $data, $response_message, $status, $success);
    }

    /**
     * Description: verify mobile verification code
     * 1) This method is used to verify patient register  mobile phone number through verification code that iCare pro to sent
     * 2) Check  patient  number exist or not if not return message
     * 3) if change patient our number so enter new_phone_number with also against those number verification code
     * 4) after then change as well as verified
     * 5) verification code check  that correct or not if not return message
     * 6) If verification code and db verified code against this number are same return message number verified successfully
     * 7) In case of any exception error message in response is return
     * @param  mixed $request
     * @return Response
     */
    public function verifyMobileVerificationCode($request)
    {
        $patient = Patient::where('phone_number', $request->phone_number)->where('country_code', $request->country_code)->first();
        // $auth = Auth::getDefaultDriver();
        // if ($request->new_phone_number) {
        //     $auth =  'patient-api';
        // }
        $auth =  'patient-api';

        $otpVerification = OtpVerification::where(['user_id' => $patient->id, 'guard_name' => $auth])->where('type', 1)->first();

        if (($request->new_phone_number && $request->verified_code != "" ) && ($request->verified_code == $otpVerification->otp)) {
            $patient->phone_number = $request->new_phone_number;
            $patient->country_code = $request->country_code;
            $patient->is_phone_number_verified = 1;
            $patient->Save();
            $otpVerification->is_verified = 1;
            $otpVerification->save();
            $otpVerification->delete();

            $response_message = PGMBook::SUCCESS['PHONE_NUMBER_CHANGE_AND_VERIFIED'];
            $status = 201;
            $data = $patient;
            $success = true;
        }
        else {
            $response_message = PGMBook::FAILED['VERIFICATION_CODE_INCORRECT'];
            $status = 401;
            $data = null;
            $success = false;
        }

        return $this->response($request, $data, $response_message, $status, $success);
    }

    /**
     * Description: set patient password
     * 1) This method is used to patient set our password
     * 2) Check  patient  number exist or not if not return message
     * 3) Check patient password and confirm password are same or not if not return message
     * 4) If all is true return password set successfully
     * 5) In case of any exception error message in response is return
     * @param  mixed $request
     * @return Response
     */
    public function setPassword($request)
    {
        $patient = Patient::where('phone_number', $request->phone_number)->first();

        $response_message = PGMBook::SUCCESS['PASSWORD_SET'];
        $status = 201;
        $data = $patient;
        $success = true;
        if (empty($patient)) {
            $response_message = PGMBook::FAILED['PATIENT_NOT_FOUND'];
            $status = 400;
            $data = null;
            $success = false;
        } else if ($request->password === $request->confirm_password) {
            $patient->password = bcrypt($request->password);
            $patient->is_password_reset = 1;
            $patient->is_first_login = 1;
            $patient->Save();
        } else {
            $response_message = PGMBook::FAILED['PASSWORD_ERROR'];
            $status = 401;
            $data = null;
            $success = false;
        }
        return $this->response($request, $data, $response_message, $status, $success);
    }


    /**
     * Description: set patient password
     * 1) This method is used to patient set our password
     * 2) Check  patient  number exist or not if not return message
     * 3) Check patient password and confirm password are same or not if not return message
     * 4) If all is true return password set successfully
     * 5) In case of any exception error message in response is return
     * @param  mixed $request
     * @return Response
     */
    public function updatePassword($request)
    {
        $patient = Patient::where('id', auth()->id())->first();

        if (!Hash::check($request->old_password, $patient->password)) {
            $response_message = PGMBook::SUCCESS['OLD_PASSWORD'];
            $status = 401;
            $data = null;
            $success = false;
        } else if (Hash::check($request->new_password, $patient->password)) {
            $response_message = PGMBook::SUCCESS['Old_PASSWORD_SAME'];
            $status = 401;
            $data = null;
            $success = false;
        } else if (Hash::check($request->old_password, $patient->password) && $request->new_password === $request->confirm_password) {
            $patient->password = bcrypt($request->new_password);
            $patient->Save();
            $response_message = PGMBook::SUCCESS['UPDATE_PASSWORD'];
            $status = 201;
            $data = $patient;
            $success = true;
        } else {
            $response_message = PGMBook::FAILED['PASSWORD_CONFIRM_ERROR'];
            $status = 401;
            $data = null;
            $success = false;
        }
        return $this->response($request, $data, $response_message, $status, $success);
    }

    /**
     * Description: Update patient basic information data
     * 1) This method is used to edit basic information of patient practice and patient both edit this
     * 2) Check  patient  number exist or not if not return message
     * 3) send request field and also validate this request for required field
     * 4) If credentials are wrong invalid credentials message will return error message
     * 5) If true return message
     * 6) In case of any exception error message in response is return
     * @param  mixed $request
     * @return Response
     */
    public function editPatientBasicInformation($request)
    {
        $patient = Patient::where('phone_number', $request->phone_number)->first(['id', 'patient_key', 'first_name', 'last_name', 'middle_name', 'phone_number', 'email', 'gender', 'dob']);
        $patient_variable =  $this->patientSetVariables($request);

        $response_message = PGMBook::SUCCESS['PATIENT_INFO_UPDATE'];
        $status = 200;
        $success = true;
        if (empty($patient)) {
            // save patient
            $patient = $this->createPatient($request, $patient_variable['patient_key'], $patient_variable['path'], $patient_variable['created_by'], $patient_variable['is_phone_number_verified']);

            $response_message = PGMBook::SUCCESS['REGISTER'];
            $status = 201;
            $success = true;
        } else {
            $patient->first_name = $request->first_name;
            $patient->middle_name = $request->middle_name;
            $patient->last_name = $request->last_name;
            $patient->gender = $request->gender;
            $patient->dob = $request->dob;
            $patient->updated_by = $patient_variable['updated_by'];
            $patient->save();
        }
        return $this->response($request, $patient, $response_message, $status, $success);
    }
    /**
     * Description: For changing phone number send verification code first
     * 1) This method is used to send  mobile verification code to patient for verification so 1st we check
     * 2) Check  patient exist or not if not return message
     * 3) number is valid format  or not if not return message
     * 4) If all is then send message to those number
     * 5) In case of any exception error message in response is return
     * @param  mixed $request
     * @return Response
     */
    public function changePhoneNumber($request)
    {
        $patient = Patient::where('phone_number', $request->phone_number)->where('country_code', $request->country_code)->first(['id', 'first_name', 'last_name', 'middle_name', 'phone_number', 'email', 'gender', 'dob']);
        if (empty($patient)) {
            $response_message = PGMBook::FAILED['PATIENT_NOT_FOUND'];
            $status = 400;
            $data = null;
            $success = false;
        } elseif (Patient::where('phone_number', $request->new_phone_number)->exists()) {
            $response_message = PGMBook::FAILED['NEW_NUMBER_EXIST'];
            $status = 400;
            $data = null;
            $success = false;
        } else {
            // $code = rand(111111, 999999);
            $code = 123456;
            $country_code = $request->country_code;
            $phone_number = $request->new_phone_number;

            if ($request->country_code == '+92') {
                dispatch(new SendSmsMessageToPatientWithCode($country_code, $phone_number, $code));
            }
            else {
                try {
                    $this->twilio($country_code.$phone_number , $code);
                } catch (Exception $e) {
                    return $this->response(false, $patient, PGMBook::FAILED['OTP_SEND_ERROR'], 400);
                }
            }

            Helper::otpVerification($patient->id, Auth::getDefaultDriver(), $code, 1, $phone_number);

            $response_message = PGMBook::SUCCESS['VERIFICATION_CODE_SENT'];
            $status = 200;
            $data = null;
            $success = true;
        }
        return $this->response($request, $data, $response_message, $status, $success);
    }
    /**
     * Description: This function use inside of patient register function
     * 1) This method is used save patient profile image
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function uploadPhoto($request, $id)
    {
        if ($request->file('profile_photo_url')) {
            $filenameWithExt = $request->file('profile_photo_url')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('profile_photo_url')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // Upload Image
            return $request->file('profile_photo_url')->storeAs('public/patient/' . $id, $fileNameToStore);
        }
    }
    /**
     * Description: This function use inside of patient register function
     * 1) This method is used save patient data
     * 2) These all data  use in patient registration
     * @param  mixed $request
     * @param  mixed $patient_key
     * @param  mixed $path
     * @return Response
     */
    public function createPatient($request, $patient_key, $path, $created_by, $is_phone_number_verified)
    {
        $patient = new Patient;
        $patient->patient_key = $patient_key;
        $patient->country_code = $request->country_code;
        $patient->phone_number = $request->phone_number;
        $patient->first_name = $request->first_name;
        $patient->middle_name = $request->middle_name;
        $patient->last_name = $request->last_name;
        $patient->email = $request->email;
        $patient->gender = $request->gender;
        $patient->dob = $request->dob;
        $patient->password = bcrypt($request->password);
        $patient->thumbnail_photo_url = $request->thumbnail_photo_url;
        $patient->profile_photo_url = $path;
        $patient->created_by = $created_by;
        $patient->is_phone_number_verified =  $is_phone_number_verified;
        $patient->save();

        $code = 123456;
        $country_code = $request->country_code;
        $phone_number = $request->phone_number;

        if ($request->country_code == '+92') {

            Helper::otpVerification($patient->id, Auth::getDefaultDriver(), $code, 1, $phone_number);

            dispatch(new SendSmsMessageToPatientWithCode($country_code, $phone_number, $code));

        }
        else {
            try {
                $this->twilioOtpSend($country_code, $phone_number);
            } catch (Exception $e) {
                return $this->response(false, $patient, PGMBook::FAILED['OTP_SEND_ERROR'], 400);
            }
        }

        return $patient;
    }

    /**
     * Description: This functions sends OTP to the User using the phone number provided
     */
    public function sendOTP()
    {
        $patient_check = Patient::where('phone_number', $this->phone_number)->where('country_code', $this->country_code)->first(['id', 'patient_key', 'first_name', 'last_name', 'middle_name', 'phone_number', 'email', 'gender', 'dob', 'is_phone_number_verified', 'is_password_reset', 'is_first_login']);

        if (!empty($patient_check)) {
            $condition_status = [
                'is_phone_number_verified' => $patient_check->is_phone_number_verified,
                'is_password_reset' => $patient_check->is_password_reset,
                'is_first_login' => $patient_check->is_first_login,
            ];
            // Message for activity log
            $message = PGMBook::SUCCESS['PATIENT_REGISTER_CHECK'];
            $this->activityLogs($message, true, $patient_check);        // Parameters ($message, $request , $response)
            return response([
                'success' => true,
                'message' => PGMBook::SUCCESS['PATIENT_REGISTER_CHECK'],
                'data' => $patient_check,
                'patient_condition_status' => $condition_status,
            ], 200);
        } else {
            // $code = rand(111111, 999999);
            $code = 123456;
            if ($this->country_code == '+92') {
                dispatch(new SendSmsMessageToPatientWithCode($this->country_code, $this->phone_number, $code));
                // Helper::otpVerification($patient_check->id, Auth::getDefaultDriver(), $code, 1, $this->phone_number);
            } else {
                try {
                    $this->twilioOtpSend($this->country_code, $this->phone_number);
                } catch (Exception $e) {
                    return $this->response(false, $patient_check, PGMBook::FAILED['OTP_SEND_ERROR'], 400);
                }
            }
            // self::registerPatient();
            return $this->response(true, $patient_check, PGMBook::SUCCESS['PATIENT_NOT_REGISTER_CHECK_OTP'], 200);
        }
    }
    /**
     * Description: This functions Verifies OTP of the User against the phone number provided
     * @param Integer $otp
     * @return Boolean
     */
    public function verifyOtp($request)
    {
        $patient = Patient::select('id')->where('phone_number', $request->phone_number)->first();

        if ($request->country_code === '+92') {
            if (!empty($patient)) {
                $otpVerification = OtpVerification::select('otp')->where(['user_id' => $patient->id, 'guard_name' => Auth::getDefaultDriver(), 'type' => 1])->first();
            }

            if ($request->verified_code == @$otpVerification->otp) {
                $patient->is_phone_number_verified = 1;
                $patient->Save();
                $otpVerification->is_verified = 1;
                $otpVerification->save();
                $otpVerification->delete();

                $response_message = PGMBook::SUCCESS['VERIFICATION_CODE_CORRECT'];
                $status = 201;
                $data = $patient;
                $success = true;
            } else {
                $response_message = PGMBook::FAILED['OTP_CODE'];
                $status = 400;
                $data = $patient;
                $success = false;
            }
        }
        else if ($request->country_code !== '+92') {
            $token = env("TWILIO_TOKEN");
            $twilio_sid = env("TWILIO_SID");
            $twilio_verify_sid = env("TWILIO_VERIFY_SID");
            $twilio = new Client($twilio_sid, $token);
            $verification = $twilio->verify->v2->services($twilio_verify_sid)
                ->verificationChecks
                ->create(array('code' => $request->verified_code, 'to' => $request->country_code . $request->phone_number));
            if ($verification->valid) {
                $patient->is_phone_number_verified = 1;
                $patient->Save();
                $response_message = PGMBook::SUCCESS['VERIFICATION_CODE_CORRECT'];
                $status = 201;
                $data = $patient;
                $success = true;
            } else {
                $response_message = PGMBook::FAILED['OTP_CODE'];
                $status = 400;
                $data = $patient;
                $success = false;
            }
        }
        else{
            $response_message = PGMBook::FAILED['OTP_CODE'];
            $status = 400;
            $data = $patient;
            $success = false;
        }
        return $this->response($request, $data, $response_message, $status, $success);
    }
    /**
     * Description: This function use set patient variable which use for patient registration
     * 1) This method is used save patient variable
     * 2) These all data  use in patient registration
     * @param  mixed $request
     * @return void
     */
    public function patientSetVariables($request)
    {
        //check auth
        $auth = $this->uniqueKey();
        $id = 1;
        $pact = Patient::latest()->first();
        if(empty($pact)){
            $patient_key = 'patient-1';
        }
        else{
            $id = $pact['id'] + 1;
            $patient_key = 'patient' . -$id;
        }
        //set Created by
        $created_by = $patient_key;
        $updated_by = Patient::where('phone_number', $request->phone_number)->first('patient_key');
        if (!empty($updated_by)) {
            $updated_by = $updated_by->patient_key;
        }

        if ($auth != 'web') {
            $created_by = $auth;
            $updated_by = $auth;
            $is_phone_number_verified = 0;
        } else {
            $is_phone_number_verified = 1;
        }
        // upload photo. set path
        $path = '';
        if ($request->profile_photo_url) {
            $path = $this->uploadPhoto($request, $id);
        }
        return [
            'patient_key' => $patient_key,
            'created_by' => $created_by,
            'is_phone_number_verified' => $is_phone_number_verified,
            'path' => $path,
            'updated_by' => $updated_by,
        ];
    }
}
