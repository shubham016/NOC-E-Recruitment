<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApproverAuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('approver.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('approver')->attempt([
            'employee_id' => $request->employee_id,
            'password' => $request->password,
            'status' => 'active'
        ], $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('approver.dashboard'));
        }

        return back()->withErrors([
            'employee_id' => 'Invalid credentials or account is inactive.',
        ])->withInput($request->only('employee_id'));
    }

    /**
     * Show dashboard
     */
    public function dashboard()
    {
        $approver = Auth::guard('approver')->user();

        // Get statistics for dashboard
        $stats = [
            'pending_applications' => 0,
            'approved_applications' => 0,
            'rejected_applications' => 0,
            'total_applications' => 0,
        ];

        return view('approver.dashboard', compact('approver', 'stats'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::guard('approver')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('approver.login');
    }
}
