<?php

namespace App\Filters\Appointment;

use Closure;

class FromToDate
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('date')) {
            $date = date("Y-m-d");
            return $next($request)->where('appointments.date', $date);
        }
        $date = request()->date;
        return $next($request)->where('appointments.date', $date);
    }
}
