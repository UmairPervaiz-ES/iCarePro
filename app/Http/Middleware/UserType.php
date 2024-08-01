<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserType
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse|JsonResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        //  dump(Auth::getDefaultDriver()=="practice-api");
        //  dd(Auth::guard('practice-api')->user()->token());
        $name = auth()->user()->token()['name'];
        if (Auth::getDefaultDriver() == "practice-api" and $name == 'practice') {
            return $next($request);
        } elseif (Auth::getDefaultDriver() == "doctor-api" and $name == 'doctor') {
            return $next($request);
        } elseif (Auth::getDefaultDriver() == "patient-api" and $name == 'patient') {
            return $next($request);
        } elseif (Auth::getDefaultDriver() == "api" and $name == 'user') {
            return $next($request);
        }
        elseif (Auth::getDefaultDriver() == "superAdmin-api" and $name == 'superAdmin') {
            return $next($request);
        }

        return response()->json(array(
            "success" => false,
            "message" => "Invalid token supplied.",
            "data" => null
        ), 401);
    }
}
