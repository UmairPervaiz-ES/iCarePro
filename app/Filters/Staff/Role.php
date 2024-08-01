<?php

namespace App\Filters\Staff;

use Closure;

class Role
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('role')) {
            return $next($request);
        }
        $role = request()->role;
        return $next($request)->whereHas('roles', function ($query) use ($role) {
            $query->where('name', 'ilike', '%' . $role . '%');
        });
    }
}
