<?php

namespace App\Repositories\Patient\Interfaces\Auth;

interface AuthRepositoryInterface 
{
    public function login($login);
    public function logout();


}