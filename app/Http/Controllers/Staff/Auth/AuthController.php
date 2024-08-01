<?php

namespace App\Http\Controllers\Staff\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\Auth\ChangePassword;
use App\Http\Requests\Staff\Auth\LoginRequest;
use App\Http\Requests\Staff\Auth\ResetPassword;
use App\Repositories\Staff\Interfaces\Auth\AuthRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @OA\Post(
     *      path="/backend/api/staff/login",
     *      operationId="staffLogin",
     *      tags={"Staff"},
     *      summary="Staff login",
     *      description="Staff login ",
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
    public function login(LoginRequest $request){
        return $this->authRepository->login($request->all());
    }

    /**
     * @OA\Post(
     *      path="/backend/api/staff/reset-password",
     *      operationId="staffResetPassword",
     *      tags={"Staff"},
     *      summary="Staff reset password",
     *      description="Staff reset password",
     *       security={{"passport":{}}},
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
    public function resetPassword(ResetPassword $request){
        return $this->authRepository->resetPassword($request->all());
    }

    /**
     * @OA\Post(
     *      path="/backend/api/staff/change-password",
     *      operationId="staffChangePassword",
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
    public function changePassword(ChangePassword $request){
        return $this->authRepository->changePassword($request->all());
    }

    /**
     * @OA\Post(
     *      path="/backend/api/staff/logout",
     *      operationId="staffLogout",
     *      tags={"Staff"},
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
    public function logout(){
        return $this->authRepository->logout();
    }
}
