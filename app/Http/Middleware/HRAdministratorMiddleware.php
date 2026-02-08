<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HRAdministratorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('hr_administrator')->check()) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to access the HR Administrator portal.');
        }

        return $next($request);
    }
}