<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $role = Auth::user()->role ?? 'patient';

                return match ($role) {
                    'admin'  => redirect('/admin/dashboard'),
                    'doctor' => redirect('/doctor/dashboard'),
                    default  => redirect('/patient/dashboard'),
                };
            }
        }

        return $next($request);
    }
}
