<?php

namespace App\Filters\Staff;

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
        $user_key = request()->search;
        $middle_name = request()->search;
        $primary_phone_number  = request()->search;
        $name  = request()->search;
        $department_name  = request()->search;
        $role_name  = request()->search;
      if(count($search)> 1){
          $first_name = $search[0];
          $last_name = $search[1];

      }


        return $next($request)->whereHas('roles', function ($query) use ($search, $role_name, $first_name,$middle_name,  $last_name,$user_key ) {
            $query->where('name', 'ilike', '%' . $role_name . '%')->orWhere('users.first_name', 'ilike', '%' . $first_name . '%')->orWhere('users.middle_name', 'ilike', '%' . $middle_name . '%')->orWhere('users.last_name', 'ilike', '%' .   $last_name . '%')->orWhere('users.user_key', 'ilike', '%' . $user_key . '%');
        })->orWhereHas('department', function ($query) use ($search,$name) {
            $query->where('name', 'ilike', '%' . $name . '%');
        })->orWhereHas('department_employee_type', function ($query) use ($search,$department_name) {
            $query->where('name', 'ilike', '%' . $department_name . '%');
        });
    }
}
