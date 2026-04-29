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
        return view('auth.approver.login');
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

        // Debug: Check if approver exists
        $approver = \App\Models\Approver::where('employee_id', $request->employee_id)->first();

        if (!$approver) {
            \Log::info('Approver not found: ' . $request->employee_id);
            return back()->withErrors([
                'employee_id' => 'Approver not found with this Employee ID.',
            ])->withInput($request->only('employee_id'));
        }

        if ($approver->status !== 'active') {
            \Log::info('Approver inactive: ' . $request->employee_id);
            return back()->withErrors([
                'employee_id' => 'Your account is inactive. Please contact administrator.',
            ])->withInput($request->only('employee_id'));
        }

        // Verify password manually first
        if (!\Hash::check($request->password, $approver->password)) {
            \Log::info('Password mismatch for: ' . $request->employee_id);
            return back()->withErrors([
                'employee_id' => 'Invalid password.',
            ])->withInput($request->only('employee_id'));
        }

        // If all checks pass, authenticate
        if (Auth::guard('approver')->attempt([
            'employee_id' => $request->employee_id,
            'password' => $request->password,
            'status' => 'active'
        ], $request->filled('remember'))) {
            $request->session()->regenerate();
            \Log::info('Approver logged in successfully: ' . $request->employee_id);
            return redirect()->intended(route('approver.dashboard'));
        }

        \Log::error('Auth attempt failed for: ' . $request->employee_id);
        return back()->withErrors([
            'employee_id' => 'Authentication failed. Please try again.',
        ])->withInput($request->only('employee_id'));
    }

    /**
     * Show dashboard
     */
    public function dashboard()
    {
        $approver = Auth::guard('approver')->user();

        $stats = [
            'total_applications'    => \App\Models\ApplicationForm::where('approver_id', $approver->id)->where('status', '!=', 'draft')->count(),
            'pending_applications'  => \App\Models\ApplicationForm::where('approver_id', $approver->id)->where('status', 'reviewed')->count(),
            'approved_applications' => \App\Models\ApplicationForm::where('approver_id', $approver->id)->where('status', 'approved')->count(),
            'rejected_applications' => \App\Models\ApplicationForm::where('approver_id', $approver->id)->where('status', 'rejected')->count(),
        ];

        $recentApplications = \App\Models\ApplicationForm::with('jobPosting')
            ->where('approver_id', $approver->id)
            ->where('status', '!=', 'draft')
            ->latest()
            ->take(5)
            ->get();

        return view('approver.dashboard', compact('approver', 'stats', 'recentApplications'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::guard('approver')->logout();
        $request->session()->regenerate();
        $request->session()->regenerateToken();
        return redirect()->route('approver.login');
    }
}
