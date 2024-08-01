<?php

namespace App\Repositories\Practice\Interfaces\Auth;

interface AuthRepositoryInterface
{
    public function login($login);
    public function resetPassword($request);
    public function changePassword($request);
    public function forgetPassword($request);
    public function verifyOtp($request);
    public function setPassword($request);
  






}
