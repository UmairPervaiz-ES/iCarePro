<?php

namespace App\Filters\Staff;

use Closure;

class FirstName
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('first_name')) {
            return $next($request);
        }

        $first_name = request()->first_name;
        return $next($request)->where('users.first_name', 'ilike', '%' . $first_name . '%');
    }
}
