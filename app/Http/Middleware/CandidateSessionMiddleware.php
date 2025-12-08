<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CandidateSessionMiddleware
{
    public function handle($request, Closure $next)
{
    if (!session()->has('candidate_logged_in')) {
        return redirect()->route('candidate.login');
    }

    // Attach candidate object to request
    $request->merge([
        'candidate' => session('candidate') // or whatever you stored
    ]);

    return $next($request);
}
}