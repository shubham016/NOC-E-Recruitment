<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('reviewer')->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'status' => 'active'
        ], $request->remember)) {
            return redirect()->intended(route('reviewer.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or account is inactive.',
        ])->withInput($request->only('email'));
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::guard('reviewer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('reviewer.login');
    }
}