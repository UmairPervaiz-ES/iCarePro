<?php

namespace App\Filters\Doctor;

use Closure;

class KycStatus
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('kyc_status')) {
            return $next($request);
        }
        $keyStatus = request()->kyc_status;

        return $next($request)->whereHas('doctor', function ($query) use ($keyStatus) {
            $query->where('kyc_status', 'ilike', '%' . $keyStatus . '%');
        });
    }
}
