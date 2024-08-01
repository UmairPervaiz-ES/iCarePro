<?php

namespace App\Filters\Appointment;

use Closure;

class AppointmentID
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('appointment_key')) {
            return $next($request);
        }
        $appointment_key = request()->appointment_key;
        return $next($request)->where('appointments.appointment_key', 'ilike', '%' . $appointment_key . '%');
    }
}
