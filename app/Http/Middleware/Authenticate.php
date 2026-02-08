<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // Determine which portal is being accessed
            $path = $request->path();

            if (str_starts_with($path, 'admin/')) {
                return route('admin.login');
            }

            if (str_starts_with($path, 'hr-administrator/')) {
                return route('hr-administrator.login');
            }

            if (str_starts_with($path, 'reviewer/')) {
                return route('reviewer.login');
            }

            if (str_starts_with($path, 'candidate/')) {
                return route('candidate.login');
            }

            // Default to admin login
            return route('admin.login');
        }

        return null;
    }
}