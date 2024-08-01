<?php

namespace App\Filters\Patient;

use Closure;

class LastName
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('last_name')) {
            return $next($request);
        }
        $last_name = request()->last_name;
        return $next($request)->where('patients.last_name', 'ilike', '%' . $last_name . '%');
    }
}
