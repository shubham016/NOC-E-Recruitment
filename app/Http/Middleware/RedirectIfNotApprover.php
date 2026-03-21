<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Detect which guard is being used
        $guard = $this->guards()[0] ?? null;

        switch ($guard) {
            case 'approver':
                return route('approver.login'); // your approver login route
            default:
                return route('login'); // default user login route
        }
    }
}