<?php

namespace App\Filters\Doctor;

use Closure;

class MiddleName
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('middle_name')) {
            return $next($request);
        }
        $middle_name = request()->middle_name;
        return $next($request)->where('doctors.middle_name', 'ilike', '%' . $middle_name . '%');
    }
}
