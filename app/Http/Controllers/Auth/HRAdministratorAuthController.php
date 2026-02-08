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
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $remember = $request->boolean('remember');

    //  DEBUG: Log attempt
    \Log::info('HR Login attempt', ['email' => $credentials['email']]);

    if (Auth::guard('hr_administrator')->attempt($credentials, $remember)) {
        $request->session()->regenerate();

        $hrAdmin = Auth::guard('hr_administrator')->user();

        //  DEBUG: Log success
        \Log::info('HR Login SUCCESS', [
            'user_id' => $hrAdmin->id,
            'email' => $hrAdmin->email,
            'authenticated' => Auth::guard('hr_administrator')->check(),
        ]);

        if ($hrAdmin->status !== 'active') {
            Auth::guard('hr_administrator')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Your account has been deactivated.',
            ]);
        }

        return redirect()->intended(route('hr-administrator.dashboard'));
    }

    //  DEBUG: Log failure
    \Log::info('HR Login FAILED', ['email' => $credentials['email']]);

    throw ValidationException::withMessages([
        'email' => 'The provided credentials do not match our records.',
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