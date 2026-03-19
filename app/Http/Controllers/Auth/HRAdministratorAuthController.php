<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class HRAdministratorAuthController extends Controller
{
    /**
     * Show the HR Administrator login form
     */
    public function showLoginForm()
    {
        return view('auth.hr-administrator.login');
    }

    /**
     * Handle HR Administrator login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['status'] = 'active'; // Only allow active HR administrators

        $remember = $request->boolean('remember');

        if (Auth::guard('hr_administrator')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('hr-administrator.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records or your account has been deactivated.',
        ]);
    }

    /**
     * Handle HR Administrator logout request
     */
    public function logout(Request $request)
    {
        Auth::guard('hr_administrator')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('hr-administrator.login')
            ->with('success', 'You have been logged out successfully.');
    }
}