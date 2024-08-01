<?php

namespace App\Repositories\Staff\Eloquent\Auth;

use App\libs\Messages\StaffGlobalMessageBook;
use App\Models\User\User;
use App\Repositories\Staff\Interfaces\Auth\AuthRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    use RespondsWithHttpStatus;

    /**
     *  Description: This function is to validate staff credentials and return staff data and access token
     * 1) This method is used by staff to login
     * 2) Get email and password from request and send to passport auth method to verify
     * 3) If credentials are wrong invalid credentials message is returned
     * 4) In case of valid credentials, activity is logged, and a success message is returned
     * 5) In case of any exception error is logged, and a response is returned
     *
     * @param mixed $request
     * @return Response
     */
    public function login($request): Response
    {
        // Check credentials is true or false
        if (Auth::guard('web')->attempt(['email' => $request['email'], 'password' => $request['password']]))
        {
            $staff = User::with('practice.initialPractice:id,practice_name')->where('id', auth()->guard('web')->user()->id)->first();
            $staff['token'] =  $staff->createToken('user')->accessToken;
            $staff['role'] = str_replace('practice-'.$staff->practice_id.'@','',$staff->getRoleNames()[0]);
            $staff['permissions'] = $staff->getPermissionsViaRoles();
            // Track Activity Logs
            $message = StaffGlobalMessageBook::SUCCESS['LOGGED_IN']; $status = 200; $success = true;
        }
        else
        {
            $message = StaffGlobalMessageBook::FAILED['INVALID_CREDENTIALS']; $status = 400; $staff = false; $success = false;
        }
        return $this->response($request, $staff, $message, $status, $success);
    }

    /**
     *  Description: This function is used to change password
     * 1) old_password and new_password is sent by request
     * 2) If credentials are wrong invalid credentials message will be returned
     * 3) In case of valid credentials, change password  and a success message is returned
     * 4) In case of any exception error is logged, and a response is returned
     *
     * @param mixed $request
     * @return Response
     */
    public function changePassword($request): Response
    {
        $staff = User::where('id', auth()->guard('api')->id())->first();

        if (!$staff)
        {
            $response = $this->response($request,null, StaffGlobalMessageBook::FAILED['STAFF_NOT_FOUND'],400,false);
        }
        else
        {
            $old_password = auth()->guard('api')->user()->password;
            if (Hash::check($request['password'], $old_password)) {
                $staff->password = Hash::make($request['new_password']);
                $staff->is_password_reset = 1;
                $staff->remember_token = null;
                $staff->save();

                $response = $this->response($request, $staff, StaffGlobalMessageBook::SUCCESS['CHANGE_PASSWORD'], 200);
            }
            else
            {
                $response = $this->response($request, null, StaffGlobalMessageBook::FAILED['INVALID_CREDENTIALS'], 400,false);
            }
        }
        return $response;
    }

    /**
     *  Description: This function is used by staff to reset password
     * 1) Email , password and password_confirmation is sent by request
     * 2) If credentials are wrong invalid credentials message will be returned
     * 3) In case of valid credentials, reset password and a success message is returned
     * 4) In case of any exception error is logged, and a response is returned
     *
     * @param mixed $request
     * @return Response
     */
    public function resetPassword($request): Response
    {
        $staff = User::where('email', auth()->guard('api')->user()->email)->first();
        if (!$staff)
        {
            $response = $this->response($request,null, StaffGlobalMessageBook::FAILED['EMAIL_NOT_FOUND'],400,false);
        }
        else
        {
            $staff->password = Hash::make($request['password']);
            $staff->is_password_reset = 1;
            $staff->remember_token = null;
            $staff->save();

            $response = $this->response($request, $staff, StaffGlobalMessageBook::SUCCESS['RESET_PASSWORD'], 200);
        }

        return $response;
    }

    /**
     *  Description: This function is used by staff to logout
     * 1) Signed in staff token is revoke/deleted
     *
     * @return Response
     */
    public function logout(): Response
    {
        $userID = auth()->guard('api')->id();
        auth()->guard('api')->user()->token()->revoke();

        return $this->response($userID, null, StaffGlobalMessageBook::SUCCESS['LOGOUT'], 200);
    }
}
