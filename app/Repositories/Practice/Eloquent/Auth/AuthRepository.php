<?php

namespace App\Repositories\Practice\Eloquent\Auth;

use App\Helper\Helper;
use App\Jobs\Practice\SendOtpCode;
use App\libs\Messages\PracticeGlobalMessageBook as PGMBook;
use App\Models\Country\Country;
use App\Models\OtpVerification\OtpVerification;
use App\Models\Practice\Practice;
use App\Repositories\Practice\Interfaces\Auth\AuthRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    use RespondsWithHttpStatus;

    /**
     *  Description: This function validated user credentials and returns user data and access token
     * 1) This method is used to login
     * 2) Get email and password from request and send to passport auth method to verify
     * 3) If credentials are wrong invalid credentials message will return
     * 4) In case of valid credentials, activity is logged, and a success message is return
     * 5) In case of any exception error is logged, and a response is return
     * @param  mixed $request
     * @return void
     */
    public function login($request)
    {
        // Check credentials is true or false
        if (Auth::guard('practice')->attempt(['email' => $request['email'], 'password' => $request['password']])) {
            $practice = Auth::guard('practice')->user()->load(['initialPractice','practiceAddress']);
            $country = Country::where('id', $practice->practiceAddress->country_id)->first();
            $practice['country_name'] = $country['name'];
            $practice['role'] = str_replace('practice-'.$practice->id.'@', '', $practice->getRoleNames()[0]);
            $practice['token'] =  $practice->createToken('practice')->accessToken;
            $practice['permissions'] = $practice->getPermissionsViaRoles();
            // Track Activity Logs
            $message = PGMBook::SUCCESS['LOGGED_IN'];
            $status = 200;
            $success = true;
        } else {
            $message = PGMBook::FAILED['INVALID_CREDENTIALS'];
            $status = 401;
            $practice = false;
            $success = false;
        }
        return $this->response($request, $practice, $message, $status, $success);
    }

    /**
     *  Description: This function reset password
     * 1) This method is used to update
     * 2) Get email , password and password_confirmation from request
     * 3) If credentials are wrong invalid credentials message will return
     * 4) In case of valid credentials, reset password  and a success message is return
     * 5) In case of any exception error is logged, and a response is return
     * @param  mixed $request
     * @return void
     */
    public function resetPassword($request)
    {

        $practice = Practice::where('email', Auth::guard('practice-api')->user()->email)->first();
        if (!$practice) {
            $message = PGMBook::FAILED['EMAIL_NOT_FOUND'];
            $status = 400;
            $practice = false;
            $success = false;
        } elseif (!Hash::check($request['password'], $practice->password)) {
            $practice->password = Hash::make($request['password']);
            $practice->is_password_reset = 1;
            $practice->remember_token = null;
            $practice->save();

            $message = PGMBook::SUCCESS['RESET_PASSWORD'];
            $status = 200;
            $success = true;
        } else {

            $message = PGMBook::FAILED['PASSWORD_MATCH'];
            $status = 400;
            $practice = false;
            $success = false;
        }
        return $this->response($request, $practice, $message, $status, $success);
    }

    /**
     *  Description: This function change password
     * 1) This method is used to update
     * 2) old_password and new_password from request
     * 3) If credentials are wrong invalid credentials message will return
     * 4) In case of valid credentials, change password  and a success message is return
     * 5) In case of any exception error is logged, and a response is return
     * @param  mixed $request
     * @return void
     */
    public function changePassword($request)
    {
        
        $practice = Practice::find(auth()->id());
        if (!Hash::check($request['old_password'], $practice['password'])){

            $message = PGMBook::FAILED['INVALID_CREDENTIALS'];
                $status = 400;
                $practice = false;
                $success = false;
        }        
        
        else if (!Hash::check($request['new_password'], $practice['password'])) {
            $practice->password = Hash::make($request['new_password']);
            $practice->is_password_reset = 1;
            $practice->remember_token = null;
            $practice->save();

            $message = PGMBook::SUCCESS['CHANGE_PASSWORD'];
            $status = 200;
            $success = true;
        } 
        else{
            $message = PGMBook::FAILED['PASSWORD_MATCH'];
            $status = 400;
            $practice = false;
            $success = false;
        }
        return $this->response($request, $practice, $message, $status, $success);
    }

    /**
     *  Description: This function forget password
     * 1) This method is used to update
     * 2) Get email from request
     * 3) If email is not exist send message will return
     * 4) In case of email is exist,send OTP code to email
     * 5) In case of any exception error is logged, and a response is return
     * @param  mixed $request
     * @return void
     */
    public function forgetPassword($request)
    {
        $practice = Practice::where('email', $request['email'])->first();
        if (!$practice) {
            $message = PGMBook::FAILED['EMAIL_NOT_FOUND'];
            $status = 400;
            $success = false;
        } else {
            $otp  =   rand(6, 999999);
            Helper::otpVerification($practice['id'], Auth::getDefaultDriver(), $otp, 0, $request['email']);
            Helper::credentialLog($practice['id'], Auth::getDefaultDriver(), 0, $request['email']);
            // Generate unique token for reset password
            $token = md5(time());

            // Save record of requested user in password_resets
            DB::table('password_resets')->insert([
                'email' => $request['email'],
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);
            $details = ['email' => $practice->email, 'token' => $token, 'otp' => $otp];
            // Send email on provided email
            dispatch(new SendOtpCode($details))->onQueue(config('constants.SEND_OTP'));

            $message = PGMBook::SUCCESS['SEND_OTP'];
            $status = 200;
            $success = true;
        }

        return $this->response($request, $practice, $message, $status,   $success);
    }

    /**
     *  Description: This function verify otp
     * 1) This method is used to verify
     * 2) Get opt from request
     * 3) If opt is not exist send message will return
     * 4) In case of opt is exist,send success message will return
     * 5) In case of any exception error is logged, and a response is return
     * @param  mixed $request
     * @return void
     */
    public function verifyOtp($request)
    {
        $otpVerification = OtpVerification::where('otp', $request['otp'])->where('type', 0)->first();
        if (!$otpVerification) {
            $message = PGMBook::FAILED['OTP_CODE'];
            $status = 400;
            $success = false;
        } else {
            $otpVerification->is_verified = 1;
            $otpVerification->save();
            $otpVerification->delete();
            $message = PGMBook::SUCCESS['OTP_CODE_MATCH'];
            $status = 200;
            $success = true;
        }
        return $this->response($request, $otpVerification, $message, $status, $success);
    }

    /**
     *  Description: This function reset password
     * 1) This method is used to update
     * 2) Get email , password and password_confirmation from request
     * 3) If credentials are wrong invalid credentials message will return
     * 4) In case of valid credentials, reset password  and a success message is return
     * 5) In case of any exception error is logged, and a response is return
     * @param  mixed $request
     * @return void
     */
    public function setPassword($request)
    {
        $practice = Practice::where('email', $request['email'])->first();
        if (!$practice) {
            $message = PGMBook::FAILED['EMAIL_NOT_FOUND'];
            $status = 400;
            $practice = false;
            $success = false;
        } else {
            $practice->password = Hash::make($request['password']);
            $practice->is_password_reset = 1;
            $practice->remember_token = null;
            $practice->save();

            $message = PGMBook::SUCCESS['SET_PASSWORD'];
            $status = 200;
            $success = true;
        }
        return $this->response($request, $practice, $message, $status, $success);
    }
}
