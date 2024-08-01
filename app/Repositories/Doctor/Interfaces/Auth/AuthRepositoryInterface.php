<?php

namespace App\Repositories\Doctor\Interfaces\Auth;

interface AuthRepositoryInterface
{
    public function login($request);

    public function resetPassword($request);

    public function changePassword($request);

    public function switchPractice($request);

    public function logout();
}
