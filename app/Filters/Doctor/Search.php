<?php

namespace App\Filters\Doctor;

use Closure;

class Search
{
    public function handle($request, Closure $next)
    {

        if (!request()->has('search')) {

            return $next($request);
        }

        $search = request()->search;

        $search = explode(" ",request()->search);


        $first_name  = request()->search;
        $last_name = request()->search;
        $doctor_key = request()->search;
        $middle_name = request()->search;
        $primary_phone_number  = request()->search;
        $kyc_status  = request()->search;
        $is_active  = request()->search;
      if(count($search)> 1){
          $first_name = $search[0];
          $last_name = $search[1];

      }



        return $next($request)->whereHas('doctor', function ($query) use ($search,$first_name,$last_name,$middle_name,$doctor_key,$primary_phone_number,$kyc_status,$is_active) {
            $query->where('first_name', 'ilike', '%' . $first_name . '%')
                ->orWhere('middle_name', 'ilike', '%' . $middle_name . '%')
                ->orWhere('primary_phone_number', 'ilike', '%' . $primary_phone_number . '%')
                ->orWhere('last_name', 'ilike', '%' . $last_name . '%')
                ->orWhere('kyc_status', 'ilike', '%' . $kyc_status . '%')
                ->orWhere('is_active', 'ilike', '%' . $is_active . '%')
                ->orWhere('doctor_key', 'ilike', '%' . $doctor_key . '%')

                 
                 ;

                
        });


       
    }
}




