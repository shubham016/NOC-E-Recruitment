<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CandidateSessionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('candidate')->check()) {
            return redirect()->route('candidate.login')
                ->with('error', 'Please login to access this page.');
        }

        return $next($request);
    }
}