<?php

namespace App\Repositories\Doctor\Eloquent\Auth;

use App\libs\Messages\DoctorGlobalMessageBook;
use App\Models\CalendarSyncUser;
use App\Models\Doctor\Doctor;
use App\Models\Doctor\DoctorPractice;
use App\Repositories\Doctor\Interfaces\Auth\AuthRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Response;
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
     *
     * @param mixed $request
     * @return Response
     */
    public function login($request): Response
    {
        // Check credentials is true or false
        if (Auth::guard('doctor')->attempt(['primary_email' => $request['email'], 'password' => $request['password']])) {
            $doctor = Doctor::with(['doctorPractices' => function($query){
                return $query->select(['id','doctor_id','practice_id','role_id', 'role_name','doctor_status_in_practice','currently_active_in_practice_status']);
            },'doctorPractices.practice:id,practice_registration_request_id','doctorPractices.practice.initialPractice:id,practice_name'])->where('id', Auth::guard('doctor')->user()->id)->first();
            $doctor['token'] =  $doctor->createToken('doctor')->accessToken;
            $currentDoctorPractice = $doctor->doctorPractices()->where('currently_active_in_practice_status', 1)->first();
            $doctor['practices'] = $doctor->doctorPractices;
            $doctor['role_id'] = $currentDoctorPractice->role_id;
            $doctor['role_name'] = $currentDoctorPractice->role_name;
            $doctor['permissions'] = $currentDoctorPractice->doctorPracticePermissions;
            $doctor['practice_name'] = $currentDoctorPractice->practice->initialPractice->practice_name;
            $doctor['practice_country_name'] = $doctor->practice->practiceAddress->country->name;
            // Track Activity Logs
            $doctor['outlook_calendar'] = CalendarSyncUser::where('login_email', $doctor->primary_email)
            ->whereNotNull('calendar_email')
            ->where('sync_type', 'outlook')->first();
            $doctor['google_calendar'] = CalendarSyncUser::where('login_email', $doctor->primary_email)
            ->whereNotNull('calendar_email')
            ->where('sync_type', 'google')->first();
            $message = DoctorGlobalMessageBook::SUCCESS['LOGGED_IN']; $status = 200; $success = true;
        } else {
            $message = DoctorGlobalMessageBook::FAILED['INVALID_CREDENTIALS']; $status = 400; $doctor = false; $success = false;
        }
        return $this->response(true, $doctor, $message, $status, $success);
    }

    /**
     *  Description: This function reset password
     * 1) This method is used to update
     * 2) Get email , password and password_confirmation from request
     * 3) If credentials are wrong invalid credentials message will return
     * 4) In case of valid credentials, reset password  and a success message is return
     * 5) In case of any exception error is logged, and a response is return
     *
     * @param mixed $request
     * @return Response
     */
    public function resetPassword($request): Response
    {
        $doctor = Doctor::where('primary_email', auth()->guard('doctor-api')->user()->primary_email)->first();
        if (!$doctor) {
            $message = DoctorGlobalMessageBook::FAILED['EMAIL_NOT_FOUND'];
            $status = 400;
            $doctor = false;
        } elseif (!Hash::check($request['password'], $doctor->password)) {
            $doctor->password = Hash::make($request['password']);
            $doctor->is_password_reset = 1;
            $doctor->remember_token = null;
            $doctor->save();
            $message = DoctorGlobalMessageBook::SUCCESS['RESET_PASSWORD'];
            $status = 200;
        } else {

            $message = DoctorGlobalMessageBook::FAILED['PASSWORD_MATCH'];
            $status = 400;
            $doctor = false;
        }
        return $this->response(true, $doctor, $message, $status, false);
    }

    /**
     *  Description: This function is used to change password
     * 1) old_password and new_password is sent by request
     * 2) If credentials are wrong invalid credentials message will be returned
     * 3) In case of valid credentials, change password and a success message is returned
     * 4) In case of any exception error is logged, and a response is returned
     *
     * @param $request
     * @return Response
     */
    public function changePassword($request): Response
    {
        $doctor = Doctor::where('id', auth()->guard('doctor-api')->id())->first();

        if (!$doctor)
        {
            $response = $this->response($request,null, DoctorGlobalMessageBook::FAILED['DOCTOR_NOT_FOUND'],400,false);
        }
        else
        {
            $old_password = auth()->guard('doctor-api')->user()->password;
            if (Hash::check($request['password'], $old_password)) {
                $doctor->password = Hash::make($request['new_password']);
                $doctor->is_password_reset = 1;
                $doctor->remember_token = null;
                $doctor->save();

                $response = $this->response($request, $doctor, DoctorGlobalMessageBook::SUCCESS['CHANGE_PASSWORD'], 200);
            }
            else
            {
                $response = $this->response($request, null, DoctorGlobalMessageBook::FAILED['INVALID_CREDENTIALS'], 400,false);
            }
        }
        return $response;
    }

    /**
     *  Description: This function is used to switch between doctor practices
     * 1) practice_id is sent along the request
     * 2) Status is updated for the required practice_id
     * 3) In case of any exception error is logged, and a response is returned
     *
     * @param $request
     * @return Response
     */
    public function switchPractice($request): Response
    {
        $doctorPractice = DoctorPractice::where('practice_id', $request->practice_id)->first();

        if (!$doctorPractice)
        {
            $response = $this->response($request,null, DoctorGlobalMessageBook::FAILED['PRACTICE_NOT_FOUND'],400,false);
        }
        else
        {
            DB::transaction(function () use ($request){
                DoctorPractice::where('doctor_id', auth()->guard('doctor-api')->id())->update([
                    'currently_active_in_practice_status' => 0,
                    'updated_by' => auth()->guard('doctor-api')->user()->doctor_key,
                ]);

                $doctorPractice = DoctorPractice::where(['doctor_id' => auth()->guard('doctor-api')->id(), 'practice_id' => $request->practice_id])->update([
                    'currently_active_in_practice_status' => 1,
                    'updated_by' => auth()->guard('doctor-api')->user()->doctor_key,
                ]);
                Doctor::where('id', auth()->guard('doctor-api')->id())->update(['practice_id' => $request->practice_id]);
                return $doctorPractice;
            });

            $doctorPractice = DoctorPractice::with('practice')->where(['doctor_id' => auth()->guard('doctor-api')->id(), 'doctor_status_in_practice' => 1])->first();
            $doctor = Doctor::with(['doctorPractices' => function($query){
                return $query->select(['id','doctor_id','practice_id','role_id', 'role_name','doctor_status_in_practice','currently_active_in_practice_status']);
            },'doctorPractices.practice:id,practice_registration_request_id','doctorPractices.practice.initialPractice:id,practice_name'])->where('id', $doctorPractice->doctor_id)->first();
            $currentDoctorPractice = $doctor->doctorPractices()->where('currently_active_in_practice_status', 1)->first();
            $doctor['practices'] = $doctor->doctorPractices;
            $doctor['role_id'] = $currentDoctorPractice->role_id;
            $doctor['role_name'] = $currentDoctorPractice->role_name;
            $doctor['permissions'] = $currentDoctorPractice->doctorPracticePermissions;
            $doctor['practice_name'] = $currentDoctorPractice->practice->initialPractice->practice_name;

            $response = $this->response($request->all(), $doctor, DoctorGlobalMessageBook::SUCCESS['PRACTICE_SWITCHED_SUCCESSFULLY'], 200);
        }
        return $response;
    }

    /**
     *  Description: This function is used by doctor to logout
     * 1) Signed in doctor token is revoke/deleted
     *
     * @return Response
     */
    public function logout(): Response
    {
        $userID = auth()->guard('doctor-api')->id();
        auth()->guard('doctor-api')->user()->token()->revoke();

        return $this->response($userID, null, DoctorGlobalMessageBook::SUCCESS['LOGOUT'], 200);
    }
}
