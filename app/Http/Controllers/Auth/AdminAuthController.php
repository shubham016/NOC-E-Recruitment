<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['status'] = 'active'; // Only allow active admins
        $admin = Admin::where('email', $request->email)->first();

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            app(AuditLogger::class)->auth($request, 'admin', 'login', 'success', Auth::guard('admin')->user(), $request->email);
            return redirect()->route('admin.dashboard');
        }

        app(AuditLogger::class)->auth($request, 'admin', 'login', 'failed', $admin, $request->email, 'Invalid credentials or inactive account');

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            app(AuditLogger::class)->auth($request, 'admin', 'logout', 'success', $admin, $admin->email);
        }

        Auth::guard('admin')->logout();
        $request->session()->regenerate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
