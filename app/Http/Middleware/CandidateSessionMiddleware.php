<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CandidateSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if candidate is logged in
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->with('error', 'Please login to access this page.');
        }

        return $next($request);
    }
}