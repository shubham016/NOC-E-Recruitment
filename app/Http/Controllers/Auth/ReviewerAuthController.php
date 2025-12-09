<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewerAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('reviewer')->check()) {
            return redirect()->route('reviewer.dashboard');
        }
        return view('auth.reviewer.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['status'] = 'active'; // Only allow active reviewers

        if (Auth::guard('reviewer')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('reviewer.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::guard('reviewer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('reviewer.login');
    }
}