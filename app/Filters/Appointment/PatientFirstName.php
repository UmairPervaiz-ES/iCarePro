<?php

namespace App\Filters\Appointment;

use Closure;

class PatientFirstName
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('patient_first_name')) {
            return $next($request);
        }
        $first_name = request()->patient_first_name;
        return $next($request)->whereHas('patient', function ($query) use ($first_name) {
            $query->where('first_name', 'ilike', '%' . $first_name . '%');
        });
    }
}
