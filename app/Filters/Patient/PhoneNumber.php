<?php

namespace App\Filters\Patient;

use Closure;

class PhoneNumber
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('phone_number')) {
            return $next($request);
        }
        $phone_number = request()->phone_number;
        return $next($request)->where('patients.phone_number', 'ilike', '%' . $phone_number . '%');
    }
}
