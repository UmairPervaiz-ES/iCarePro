<?php

namespace App\Http\Controllers\Patient\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\Auth\LoginRequest;
use App\Repositories\Patient\Interfaces\Auth\AuthRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private AuthRepositoryInterface $authRepository;
    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/login",
     *      operationId="patientLogin",
     *      tags={"Patient"},
     *      summary="Patient Login ",
     *      description="Patient Login with phone number and password",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="phone_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *   @OA\Parameter(
     *      name="country_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function login(LoginRequest $request)
    {
        return $this->authRepository->login($request->all());
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/logout",
     *      operationId="patientLogout",
     *      tags={"Patient"},
     *      summary="Patient Logout ",
     *      description="Patient Login",
     *      security={{"passport":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function logout()
    {
        return $this->authRepository->logout();
    }


}
