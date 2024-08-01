<?php

namespace App\Filters\Appointment;

use Closure;

class Search
{
    public function handle($request, Closure $next)
    {

        if (!request()->has('search')) {

            return $next($request);
        }

        $search = request()->search;

        return $next($request)->WhereHas('patient', function ($query) use ($search) {
            $query->where('first_name', 'ilike', '%' . $search . '%')->orWhere('middle_name', 'ilike', '%' . $search . '%')->orWhere('last_name', 'ilike', '%' . $search . '%')->orWhere('appointments.appointment_key', 'ilike', '%' . $search . '%');
        })->orWhereHas('doctor', function ($query) use ($search) {

            $query->where('first_name', 'ilike', '%' . $search . '%')->orWhere('middle_name', 'ilike', '%' . $search . '%')->orWhere('last_name', 'ilike', '%' . $search . '%');
        });
    }
}
