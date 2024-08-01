<?php

namespace App\Repositories\SuperAdmin\Eloquent\Auth;

use App\libs\Messages\SuperAdminGlobalMessageBook as SAGMBook;
use App\Repositories\SuperAdmin\Interfaces\Auth\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Traits\RespondsWithHttpStatus;

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
        if (Auth::guard('superAdmin')->attempt(['email' => $request['email'], 'password' => $request['password']])) {
            $superAdmin = Auth::guard('superAdmin')->user();
            $superAdmin['token'] =  $superAdmin->createToken('superAdmin')->accessToken;
            // Track Activity Logs

            $message = SAGMBook::SUCCESS['LOGGED_IN'];
            $status = 200;
            $success = true;

        } else {
            $message = SAGMBook::FAILED['INVALID_CREDENTIALS'];
            $status = 401;
            $success = false;
            $superAdmin = false;
        }
        return $this->response($request, $superAdmin, $message, $status, $success);
    }
}
