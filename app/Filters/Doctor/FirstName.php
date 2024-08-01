<?php

namespace App\Filters\Doctor;

use Closure;

class FirstName
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('first_name')) {
            return $next($request);
        }
        $first_name = request()->first_name;


        return $next($request)->whereHas('doctor', function ($query) use ($first_name) {
            $query->where('first_name', 'ilike', '%' . $first_name . '%');
        });

    }
}
