<?php

namespace App\Filters\Staff;

use Closure;

class DepartmentName
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('department_name')) {
            return $next($request);
        }
        $department_name = request()->department_name;
        return $next($request)->whereHas('department' , function($query) use ($department_name){
            $query->where('name', 'ilike', '%' . $department_name . '%');
        });

    }
}