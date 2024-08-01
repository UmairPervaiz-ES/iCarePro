<?php

namespace App\Filters\Staff;

use Closure;

class DepartmentTypeName
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('department_type_name')) {
            return $next($request);
        }
        $department_type_name = request()->department_type_name;
        return $next($request)->whereHas('department_employee_type' , function($query) use ($department_type_name){
            $query->where('name', 'ilike', '%' . $department_type_name . '%');
        });

    }
}