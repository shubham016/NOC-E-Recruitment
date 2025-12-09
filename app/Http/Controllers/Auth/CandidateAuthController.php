<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CandidateOtp;
use App\Mail\CandidateOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CandidateAuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::guard('candidate')->check()) {
            return redirect()->route('candidate.dashboard');
        }
        return view('auth.candidate.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $candidate = Candidate::where('email', $request->email)->first();

        if (!$candidate) {
            return back()->withErrors([
                'email' => 'No account found with this email address.',
            ])->withInput($request->only('email'));
        }

        // Check if email is verified
        if (!$candidate->hasVerifiedEmail()) {
            return back()->withErrors([
                'email' => 'Please verify your email address first. Check your inbox for the OTP code.',
            ])->withInput($request->only('email'));
        }

        // Check if account is active
        if ($candidate->status !== 'active') {
            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact support.',
            ])->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('candidate')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('candidate.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        if (Auth::guard('candidate')->check()) {
            return redirect()->route('candidate.dashboard');
        }
        return view('auth.candidate.register');
    }

    /**
     * Handle registration - Step 1: Create account and send OTP
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:candidates,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today|before:-18 years',
            'address' => 'required|string|max:500',
        ], [
            'date_of_birth.before' => 'You must be at least 18 years old to register.',
        ]);

        try {
            // Create candidate account (unverified)
            $candidate = Candidate::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'date_of_birth' => $validated['date_of_birth'],
                'address' => $validated['address'],
                'status' => 'active',
                'email_verified_at' => null, // Not verified yet
            ]);

            if (!$candidate) {
                throw new \Exception('Failed to create candidate account');
            }

            // Generate and send OTP
            $otpRecord = CandidateOtp::createOTP($validated['email'], 'registration');

            // Send OTP email
            Mail::to($validated['email'])->send(
                new CandidateOtpMail($otpRecord->otp, $validated['name'], 'registration')
            );

            // Store email in session for OTP verification
            Session::put('candidate_registration_email', $validated['email']);

            Log::info('New candidate registered (pending verification): ' . $candidate->email);

            // Redirect to OTP verification page
            return redirect()
                ->route('candidate.verify.otp')
                ->with('success', 'Registration successful! Please check your email for the OTP code.');

        } catch (\Exception $e) {
            Log::error('Candidate registration failed: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Registration failed. Please try again. Error: ' . $e->getMessage()])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /**
     * Show OTP verification form
     */
    public function showVerifyOtpForm()
    {
        $email = Session::get('candidate_registration_email');

        if (!$email) {
            return redirect()->route('candidate.register')
                ->with('error', 'Session expired. Please register again.');
        }

        return view('auth.candidate.verify-otp', compact('email'));
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $email = Session::get('candidate_registration_email');

        if (!$email) {
            return back()->withErrors(['error' => 'Session expired. Please register again.']);
        }

        $otpRecord = CandidateOtp::verifyOTP($email, $request->otp, 'registration');

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP code. Please try again.']);
        }

        // Mark OTP as used
        $otpRecord->markAsUsed();

        // Mark email as verified
        $candidate = Candidate::where('email', $email)->first();
        $candidate->markEmailAsVerified();

        // Clear session
        Session::forget('candidate_registration_email');

        Log::info('Email verified for candidate: ' . $email);

        // Redirect to login with success message
        return redirect()
            ->route('candidate.login')
            ->with('success', 'Email verified successfully! You can now login to your account.');
    }

    /**
     * Resend OTP
     */
    public function resendOtp()
    {
        $email = Session::get('candidate_registration_email');

        if (!$email) {
            return back()->withErrors(['error' => 'Session expired. Please register again.']);
        }

        $candidate = Candidate::where('email', $email)->first();

        if (!$candidate) {
            return back()->withErrors(['error' => 'Candidate not found.']);
        }

        // Generate new OTP
        $otpRecord = CandidateOtp::createOTP($email, 'registration');

        // Send OTP email
        Mail::to($email)->send(
            new CandidateOtpMail($otpRecord->otp, $candidate->name, 'registration')
        );

        return back()->with('success', 'OTP code has been resent to your email.');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.candidate.forgot-password');
    }

    /**
     * Send password reset OTP
     */
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:candidates,email',
        ], [
            'email.exists' => 'No account found with this email address.',
        ]);

        $candidate = Candidate::where('email', $request->email)->first();

        // Generate OTP for password reset
        $otpRecord = CandidateOtp::createOTP($request->email, 'password_reset');

        // Send OTP email
        Mail::to($request->email)->send(
            new CandidateOtpMail($otpRecord->otp, $candidate->name, 'password_reset')
        );

        // Store email in session
        Session::put('password_reset_email', $request->email);

        return redirect()
            ->route('candidate.password.verify-otp')
            ->with('success', 'OTP code has been sent to your email.');
    }

    /**
     * Show password reset OTP verification form
     */
    public function showResetOtpForm()
    {
        $email = Session::get('password_reset_email');

        if (!$email) {
            return redirect()->route('candidate.forgot.password')
                ->with('error', 'Session expired. Please try again.');
        }

        return view('auth.candidate.verify-reset-otp', compact('email'));
    }

    /**
     * Verify password reset OTP
     */
    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $email = Session::get('password_reset_email');

        if (!$email) {
            return back()->withErrors(['error' => 'Session expired. Please try again.']);
        }

        $otpRecord = CandidateOtp::verifyOTP($email, $request->otp, 'password_reset');

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP code. Please try again.']);
        }

        // Mark OTP as used
        $otpRecord->markAsUsed();

        // Store verification status in session
        Session::put('password_reset_verified', true);

        // Redirect to password reset form
        return redirect()->route('candidate.password.reset');
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm()
    {
        if (!Session::get('password_reset_verified')) {
            return redirect()->route('candidate.forgot.password')
                ->with('error', 'Please verify OTP first.');
        }

        $email = Session::get('password_reset_email');
        return view('auth.candidate.reset-password', compact('email'));
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        if (!Session::get('password_reset_verified')) {
            return back()->withErrors(['error' => 'Unauthorized access.']);
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = Session::get('password_reset_email');
        $candidate = Candidate::where('email', $email)->first();

        if (!$candidate) {
            return back()->withErrors(['error' => 'Candidate not found.']);
        }

        // Update password
        $candidate->update([
            'password' => Hash::make($request->password),
        ]);

        // Clear session
        Session::forget(['password_reset_email', 'password_reset_verified']);

        Log::info('Password reset successful for: ' . $email);

        // Redirect to login
        return redirect()
            ->route('candidate.login')
            ->with('success', 'Password reset successfully! You can now login with your new password.');
    }

    /**
     * Resend password reset OTP
     */
    public function resendResetOtp()
    {
        $email = Session::get('password_reset_email');

        if (!$email) {
            return back()->withErrors(['error' => 'Session expired. Please try again.']);
        }

        $candidate = Candidate::where('email', $email)->first();

        if (!$candidate) {
            return back()->withErrors(['error' => 'Candidate not found.']);
        }

        // Generate new OTP
        $otpRecord = CandidateOtp::createOTP($email, 'password_reset');

        // Send OTP email
        Mail::to($email)->send(
            new CandidateOtpMail($otpRecord->otp, $candidate->name, 'password_reset')
        );

        return back()->with('success', 'OTP code has been resent to your email.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::guard('candidate')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('candidate.login');
    }
}