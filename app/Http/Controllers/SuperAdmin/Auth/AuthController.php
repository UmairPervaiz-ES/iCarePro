<?php

namespace App\Http\Controllers\SuperAdmin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\LoginRequest;
use App\Repositories\SuperAdmin\Interfaces\Auth\AuthRepositoryInterface;
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
     *      path="/backend/api/superAdmin/login",
     *      operationId="superAdminLogin",
     *      tags={"SuperAdmin"},
     *      summary="superAdmin login",
     *      description="superAdmin login ",
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
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
    public function login(LoginRequest $request)
    {
        return $this->authRepository->login($request->all());
    }
}
