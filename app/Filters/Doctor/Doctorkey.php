<?php

namespace App\Filters\Doctor;

use Closure;

class Doctorkey
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('doctor_key')) {
            return $next($request);
        }
        $doctor_key = request()->doctor_key;

        return $next($request)->whereHas('doctor', function ($query) use ($doctor_key) {
            $query->where('doctor_key', 'ilike', '%' . $doctor_key . '%');
        });

        
    }
}
