<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ReviewerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('reviewer')->check()) {
            return redirect()->route('reviewer.login');
        }

        return $next($request);
    }
}