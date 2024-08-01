<?php

namespace App\Filters\Doctor;

use Closure;

class DoctorLastName
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('last_name')) {
            return $next($request);
        }
        $last_name = request()->last_name;

        return $next($request)->whereHas('doctor', function ($query) use ($last_name) {
            $query->where('last_name', 'ilike', '%' . $last_name . '%');
        });
    }
}
