<?php

namespace App\Filters\Doctor;

use Closure;

class DoctorSpecialization
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('specialization')) {
            return $next($request);
        }
        $specialization = request()->specialization;

        return $next($request)->whereHas('doctor.doctorSpecializations.specializations', function ($query) use ($specialization) {
            $query->where('name', 'ilike', '%' . $specialization . '%');
        });

      
    }
}
