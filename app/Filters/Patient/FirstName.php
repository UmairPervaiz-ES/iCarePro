<?php

namespace App\Filters\Patient;
use App\Models\Patient\Patient;
use DB;
use Closure;

class FirstName
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('first_name')) {
            return $next($request);
        }
        $name = explode(" ",request()->first_name);
        $first_name = request()->first_name;
        $last_name= request()->first_name;
        if(count($name) > 1){
            $first_name = $name[0];
            $last_name = $name[1];
        } 
 return $next($request)->where('first_name', 'ilike', '%'.$first_name.'%')->orWhere('last_name', 'ilike', '%'.$last_name.'%')
 ->orWhere('last_name', 'ilike', '%'.$last_name.'%');

    }
}
