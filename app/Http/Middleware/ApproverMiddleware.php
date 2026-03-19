<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApproverMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('approver')->check()) {
            return redirect()->route('approver.login');
        }

        return $next($request);
    }
}
