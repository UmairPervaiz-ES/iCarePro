<?php

namespace App\Filters\Appointment;

use Closure;

class DoctorFirstName
{
    public function handle($request, Closure $next)
    {

        if (!request()->has('doctor_first_name')) {
            return $next($request);
        }
        $first_name = request()->doctor_first_name;

        return $next($request)->whereHas('doctor', function ($query) use ($first_name) {
            $query->where('first_name', 'ilike', '%' . $first_name . '%')->orWhere('appointments.appointment_key', 'ilike', '%' . $first_name . '%');
        });
    }
}
