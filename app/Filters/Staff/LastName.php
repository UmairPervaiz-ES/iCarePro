<?php

namespace App\Filters\Staff;

use Closure;

class LastName
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('last_name')) {
            return $next($request);
        }

        $last_name = request()->last_name;
        return $next($request)->where('users.last_name', 'ilike', '%' . $last_name . '%');
    }
}
