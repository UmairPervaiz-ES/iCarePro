<?php

namespace App\Filters\Patient;

use Closure;

class Status
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('status')) {
            return $next($request);
        }
        $status = request()->status;
        return $next($request)->where('patients.is_first_login', 'ilike', '%' . $status . '%');
    }
}
