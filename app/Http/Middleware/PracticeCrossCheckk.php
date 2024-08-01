<?php

namespace App\Http\Middleware;

use App\Models\Doctor\Doctor;
use App\Models\Doctor\DoctorPractice;
use App\Traits\RespondsWithHttpStatus;
use Closure;
use Illuminate\Http\Request;

class PracticeCrossCheckk
{
    use RespondsWithHttpStatus;
    /**
     * Handle an incoming request.
     * 1) Checking whether incoming request is from practice or staff if yes than cross-check practice ID
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Used to indicate that requesting user is practice/staff
        if ($request->has('doctor_id') || $request->has('practice_id'))
        {
            $doctor = DoctorPractice::where(['doctor_id' => $request->doctor_id, 'practice_id' => $this->practice_id()])->first();

            if ($doctor)
            {
                return $next($request);
            }
            else
            {
                return response()->json(['error', 'Forbidden'], 403);
            }
        }

        return $next($request);
    }
}
