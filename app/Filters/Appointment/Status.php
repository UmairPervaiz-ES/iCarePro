<?php

namespace App\Filters\Appointment;

use Closure;

class Status
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('status')) {
            return $next($request);
        }
        $status = request()->status;
        return $next($request)->where('appointments.status', 'ilike', '%' . $status . '%');
    }
}
