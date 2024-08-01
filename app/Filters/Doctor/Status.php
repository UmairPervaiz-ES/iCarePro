<?php

namespace App\Filters\Doctor;

use Closure;

class Status
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('is_active')) {
            return $next($request);
        }
        $status = request()->is_active;

        return $next($request)->whereHas('doctor', function ($query) use ($status) {
            $query->where('is_active', 'ilike', '%' . $status . '%');
        });
    }
}
