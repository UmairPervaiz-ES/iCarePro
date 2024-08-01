<?php

namespace App\Filters\Patient;

use Closure;

class Patientkey
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('patient_key')) {
            return $next($request);
        }
        $patient_key = request()->patient_key;
        return $next($request)->where('patients.patient_key', 'ilike', '%' . $patient_key . '%');
    }
}
