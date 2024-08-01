<?php

namespace App\Http\Controllers\Practice\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Practice\Auth\ChangePassword;
use App\Http\Requests\Practice\Auth\ForgetPassword;
use App\Http\Requests\Practice\Auth\LoginRequest;
use App\Http\Requests\Practice\Auth\OtpRequest;
use App\Http\Requests\Practice\Auth\ResetPassword;
use App\Repositories\Practice\Interfaces\Auth\AuthRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    private AuthRepositoryInterface $authRepository;
    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/login",
     *      operationId="practiceLogin",
     *      tags={"Practice"},
     *      summary="Practice login",

     *      description="After complete the resigteration practice login with email & password",
     *
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */

    public function login(LoginRequest $request)
    {
        return $this->authRepository->login($request->all());
    }


    /**
     * @OA\Post(
     *      path="/backend/api/practice/reset-password",
     *      operationId="practiceResetPassword ",
     *      tags={"Practice"},
     *      summary="Practice reset password",
     *      description=" Practice reset password ",
     *   security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password_confirmation",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */


    public function resetPassword(ResetPassword $request)
    {
        return $this->authRepository->resetPassword($request->all());
    }

     /**
     * @OA\Post(
     *      path="/backend/api/practice/change-password",
     *      operationId="practiceChangePassword ",
     *      tags={"Practice"},
     *      summary="Practice change password",
     *      description="Practice change password ",
     *   security={{"passport":{}}},
     *          @OA\Parameter(
     *          name="old_password",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="new_password",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */


    public function changePassword(ChangePassword $request)
    {
        return $this->authRepository->changePassword($request->all());
    }


    /**
     * @OA\Post(
     *      path="/backend/api/forget-password",
     *      operationId="practiceForgetPassword ",
     *      tags={"Practice"},
     *      summary="Forget password",

     *      description="Practice forget password send then send OTP by email and verify OTP",
     *
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */



    public function forgetPassword(ForgetPassword $request)
    {
        return $this->authRepository->forgetPassword($request->all());
    }


    /**
     * @OA\Post(
     *      path="/backend/api/verify-otp/{token}",
     *      operationId="verifyOTP",
     *      tags={"Practice"},
     *      summary="Verify otp",

     *      description="User send forget password request send .Send opt by emal verify user opt if opt is match then forget password",
     *
     *      @OA\Parameter(
     *          name="token",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *    @OA\Parameter(
     *          name="otp",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */

    public function verifyOtp(OtpRequest $request)
    {
        return $this->authRepository->verifyOtp($request->all());
    }




    /**
     * @OA\Post(
     *      path="/backend/api/set-password",
     *      operationId= "setPassword ",
     *      tags={"Practice"},
     *      summary="Set password",
     *      description="Set password ",
     *
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password_confirmation",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */


    public function setPassword(ResetPassword $request)
    {
        return $this->authRepository->setPassword($request->all());
    }
}
