<?php

namespace App\Filters\Doctor;

use Closure;

class PhoneNumber
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('phone_number')) {
            return $next($request);
        }
        $phone_number = request()->phone_number;

        return $next($request)->whereHas('doctor', function ($query) use ($phone_number) {
            $query->where('primary_phone_number', 'ilike', '%' . $phone_number . '%');
        });

    }
}
