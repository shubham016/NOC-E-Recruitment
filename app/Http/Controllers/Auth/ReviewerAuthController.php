<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewerAuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.reviewer.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'password' => 'required|min:6',
        ]);

        // Check if reviewer exists
        $reviewer = \App\Models\Reviewer::where('employee_id', $request->employee_id)->first();

        if (!$reviewer) {
            app(AuditLogger::class)->auth($request, 'reviewer', 'login', 'failed', null, $request->employee_id, 'Reviewer not found');
            return back()->withErrors([
                'employee_id' => 'Reviewer not found with this Employee ID.',
            ])->withInput($request->only('employee_id'));
        }

        if ($reviewer->status !== 'active') {
            app(AuditLogger::class)->auth($request, 'reviewer', 'login', 'failed', $reviewer, $request->employee_id, 'Inactive account');
            return back()->withErrors([
                'employee_id' => 'Your account is inactive. Please contact administrator.',
            ])->withInput($request->only('employee_id'));
        }

        // Verify password manually first
        if (!\Hash::check($request->password, $reviewer->password)) {
            app(AuditLogger::class)->auth($request, 'reviewer', 'login', 'failed', $reviewer, $request->employee_id, 'Invalid password');
            return back()->withErrors([
                'employee_id' => 'Invalid password.',
            ])->withInput($request->only('employee_id'));
        }

        // If all checks pass, authenticate
        if (Auth::guard('reviewer')->attempt([
            'employee_id' => $request->employee_id,
            'password' => $request->password,
            'status' => 'active'
        ], $request->filled('remember'))) {
            $request->session()->regenerate();
            app(AuditLogger::class)->auth($request, 'reviewer', 'login', 'success', Auth::guard('reviewer')->user(), $request->employee_id);
            return redirect()->route('reviewer.dashboard');
        }

        app(AuditLogger::class)->auth($request, 'reviewer', 'login', 'failed', $reviewer, $request->employee_id, 'Authentication failed');

        return back()->withErrors([
            'employee_id' => 'Authentication failed. Please try again.',
        ])->withInput($request->only('employee_id'));
    }

    // Handle logout
    public function logout(Request $request)
    {
        $reviewer = Auth::guard('reviewer')->user();
        if ($reviewer) {
            app(AuditLogger::class)->auth($request, 'reviewer', 'logout', 'success', $reviewer, $reviewer->employee_id);
        }

        Auth::guard('reviewer')->logout();
        // Other portals use the same Laravel session cookie. Rotating the
        // session preserves their guard data while safely ending this guard.
        $request->session()->regenerate();
        $request->session()->regenerateToken();
        return redirect()->route('reviewer.login');
    }
}
