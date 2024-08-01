<?php

namespace App\Http\Controllers\Doctor\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\Auth\ChangePassword;
use App\Http\Requests\Doctor\Auth\LoginRequest;
use App\Http\Requests\Doctor\Auth\ResetPassword;
use App\Repositories\Doctor\Interfaces\Auth\AuthRepositoryInterface;
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
     *      path="/backend/api/doctor/login",
     *      operationId=" doctor login",
     *      tags={"Doctor"},
     *      summary="doctor login",
     *      description="Login",
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
     *      path="/backend/api/doctor/reset-password",
     *      operationId=" doctor reset password",
     *      tags={"Doctor"},
     *      summary="Reset password",
     *      description="Reset password ",
     *       security={{"passport":{}}},
     *
     *          @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  @OA\Parameter(
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
     *      path="/backend/api/doctor/change-password",
     *      operationId="DoctorChangePassword",
     *      tags={"Staff"},
     *      summary="Change password",
     *      description="Change password",
     *       security={{"passport":{}}},
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          @OA\Parameter(
     *          name="new_password",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  @OA\Parameter(
     *          name="new_password_confirmation",
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
     *      path="/backend/api/doctor/switch-practice",
     *      operationId="SwitchPractice",
     *      tags={"Doctor"},
     *      summary="logout",
     *      description="logout",
     *       security={{"passport":{}}},
     *
     *  @OA\Parameter(
     *          name="practice_id",
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
    public function switchPractice(Request $request)
    {
        return $this->authRepository->switchPractice($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/logout",
     *      operationId="DoctorLogout",
     *      tags={"Doctor"},
     *      summary="logout",
     *      description="logout",
     *       security={{"passport":{}}},
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
    public function logout()
    {
        return $this->authRepository->logout();
    }
}
