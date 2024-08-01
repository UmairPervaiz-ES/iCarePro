<?php

namespace App\Filters\Specialization;

use Closure;

class Name
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('name')) {
            return $next($request);
        }
        $name = request()->name;
        return $next($request)->where('specializations.name', 'ilike', '%' . $name . '%');
    }
}
