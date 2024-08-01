<?php

namespace App\Filters\Appointment;

use App\Models\Appointment\Appointment;
use Closure;

class Date
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('from_date') &&  !request()->has('to_date') && !request()->has('doctor_first_name') &&  !request()->has('patient_first_name') && !request()->has('specialization') && !request()->has('status') && !request()->has('appointment_key')) {
            if (!request()->has('date')) {
                $date = date("Y-m-d");
                return $next($request)->where('appointments.date', $date);
            } else {
                $date = request()->date;
                return $next($request)->where('appointments.date', $date);
            }
        } else {
            if (request()->has('from_date') &&  request()->has('to_date')) {
                $from_date = request()->from_date;
                $to_date =  request()->to_date;
                return $next($request)->whereBetween('appointments.date', [$from_date, $to_date]);
            }
            if (request()->has('from_date')) {
                $from_date = request()->from_date;
                $appointment = Appointment::orderBy('date', 'desc')->first();
                $to_date = $appointment['date'];

                return $next($request)->whereBetween('appointments.date', [$from_date, $to_date]);
            }
            return $next($request);
        }
    }
}
