<?php

namespace App\Filters\Patient;

use Closure;

class Search
{
    public function handle($request, Closure $next)
    {


        if (!request()->has('search')) {

            return $next($request);
        }
        $search = explode(" ",request()->search);


          $first_name  = request()->search;
          $last_name = request()->search;
          $patient_key = request()->search;
          $middle_name = request()->search;
          $phone_number  = request()->search;
        if(count($search)> 1){
            $first_name = $search[0];
            $last_name = $search[1];

        }


  
        return $next($request)->whereHas('practicePatient', function ($query) use ($search, $first_name,$patient_key, $middle_name,$phone_number,$last_name) {
            $query->where('first_name', 'ilike', '%' .  $first_name . '%')
            ->orWhere('middle_name', 'ilike', '%' . $middle_name . '%')
                 ->orWhere('phone_number', 'ilike', '%' .$phone_number . '%')
                 ->orWhere('last_name', 'ilike', '%' .  $last_name . '%')
                 ->orWhere('patient_key', 'ilike', '%' . $patient_key . '%');
        });
    }
}
