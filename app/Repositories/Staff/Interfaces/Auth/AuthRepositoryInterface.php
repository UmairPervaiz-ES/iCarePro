<?php

namespace App\Repositories\Staff\Interfaces\Auth;

interface AuthRepositoryInterface
{
    public function login($request);

    public function resetPassword($request);

    public function changePassword($request);

    public function logout();

}
