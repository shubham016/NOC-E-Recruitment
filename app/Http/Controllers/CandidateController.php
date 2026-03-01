<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CandidateController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REGISTRATION
    |--------------------------------------------------------------------------
    */

    /**
     * Show the candidate registration form.
     */
    public function showRegisterForm()
    {
        return view('candidate.register');
    }

    /**
     * Handle candidate registration.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                       => 'required|string|max:255',
            'email'                      => 'required|email|unique:candidate_registration,email',
            'gender'                     => 'required|in:Male,Female,Other',
            'date_of_birth_bs'           => 'required|string',
            'citizenship_number'         => 'required|string|unique:candidate_registration,citizenship_number',
            'citizenship_issue_distric'  => 'required|string|max:255',
            'citizenship_issue_date_bs'  => 'required|string',
            'password'                   => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::table('candidate_registration')->insert([
            'name'                      => $request->name,
            'email'                     => $request->email,
            'gender'                    => $request->gender,
            'date_of_birth_bs'          => $request->date_of_birth_bs,
            'citizenship_number'        => $request->citizenship_number,
            'citizenship_issue_distric' => $request->citizenship_issue_distric,
            'citizenship_issue_date_bs' => $request->citizenship_issue_date_bs,
            'password'                  => Hash::make($request->password),
            'created_at'                => now(),
            'updated_at'                => now(),
        ]);

        return redirect()->route('candidate.login')
            ->with('success', 'Registration successful! Please login.');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN / LOGOUT
    |--------------------------------------------------------------------------
    */

    /**
     * Show the candidate login form.
     */
    public function showLoginForm()
    {
        return view('candidate.login');
    }

    /**
     * Handle candidate login.
     * Supports both email and citizenship number as the identifier.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Determine whether the input is an email or a citizenship number
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'citizenship_number';

        $candidate = DB::table('candidate_registration')
            ->where($fieldType, $request->email)
            ->first();

        if ($candidate && Hash::check($request->password, $candidate->password)) {
            Session::put('candidate_id',         $candidate->id);
            Session::put('candidate_name',       $candidate->name);
            Session::put('candidate_email',      $candidate->email);
            Session::put('candidate_logged_in',  true);

            return redirect()->route('candidate.dashboard')
                ->with('success', 'Welcome back, ' . $candidate->name . '!');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Invalid email/citizenship number or password.'])
            ->withInput();
    }

    /**
     * Log the candidate out.
     */
    public function logout()
    {
        Session::forget('candidate_id');
        Session::forget('candidate_name');
        Session::forget('candidate_email');
        Session::forget('candidate_logged_in');
        Session::flush();

        return redirect()->route('candidate.login')
            ->with('success', 'Logged out successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD & PROFILE
    |--------------------------------------------------------------------------
    */

    /**
     * Show the candidate dashboard.
     */
    public function dashboard()
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first.']);
        }

        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        // Total applications submitted by this candidate
        $applicationsCount = DB::table('application_form')
            ->where('citizenship_number', $candidate->citizenship_number)
            ->count();

        // Total active job postings
        $jobpostingsCount = DB::table('job_postings')->count();

        return view('candidate.dashboard', [
            'candidate'         => $candidate,
            'applicationsCount' => $applicationsCount,
            'jobpostingsCount'  => $jobpostingsCount,
            'job'               => null,
        ]);
    }

    /**
     * Show the candidate profile page.
     */
    public function profile()
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first.']);
        }

        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        if (!$candidate) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Profile not found.']);
        }

        return view('candidate.profile', compact('candidate'));
    }

    /*
    |--------------------------------------------------------------------------
    | CHANGE PASSWORD (logged-in candidates)
    |--------------------------------------------------------------------------
    */

    /**
     * Show the change-password form (requires active session).
     */
    public function showChangePasswordForm()
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first.']);
        }

        return view('candidate.change-password');
    }

    /**
     * Handle password update for a logged-in candidate.
     */
    public function updatePassword(Request $request)
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first.']);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        if (!Hash::check($request->current_password, $candidate->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput();
        }

        DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->update([
                'password'   => Hash::make($request->new_password),
                'updated_at' => now(),
            ]);

        return redirect()->back()
            ->with('success', 'Password changed successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD (unauthenticated – send reset link)
    |--------------------------------------------------------------------------
    */

    /**
     * Show the forgot-password form.
     */
    public function showForgotPasswordForm()
    {
        return view('candidate.forgot-password');
    }

    /**
     * Validate the email, generate a secure token, store it, and email the reset link.
     *
     * We always return a generic success message so that attackers cannot
     * enumerate registered email addresses.
     */
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $candidate = DB::table('candidate_registration')
            ->where('email', $request->email)
            ->first();

        if ($candidate) {
            // Remove any stale tokens for this email
            DB::table('candidate_password_resets')
                ->where('email', $request->email)
                ->delete();

            // Generate a plain-text token; store its hash
            $plainToken = Str::random(64);

            DB::table('candidate_password_resets')->insert([
                'email'      => $request->email,
                'token'      => Hash::make($plainToken),
                'created_at' => Carbon::now(),
            ]);

            // Build the reset URL (plain token goes in the URL, not the hash)
            $resetUrl = route('candidate.password.reset', ['token' => $plainToken])
                        . '?email=' . urlencode($request->email);

            // Send the reset email
            Mail::send(
                'candidate.emails.reset-password',
                ['resetUrl' => $resetUrl, 'candidate' => $candidate],
                function ($message) use ($request) {
                    $message->to($request->email)
                            ->subject('NOC E-Recruitment – Password Reset Request');
                }
            );
        }

        // Generic message regardless of whether the email was found
        return redirect()->back()->with(
            'status',
            'If your email is registered, you will receive a password reset link shortly.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RESET PASSWORD (unauthenticated – set new password via token)
    |--------------------------------------------------------------------------
    */

    /**
     * Show the reset-password form.
     */
    public function showResetPasswordForm(Request $request, string $token)
    {
        return view('candidate.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Validate the token, check expiry, and update the candidate's password.
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Look up the reset record
        $resetRecord = DB::table('candidate_password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return redirect()->back()
                ->withErrors(['email' => 'No password reset request found for this email address.'])
                ->withInput();
        }

        // Check expiry (60 minutes)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('candidate_password_resets')
                ->where('email', $request->email)
                ->delete();

            return redirect()->back()
                ->withErrors(['email' => 'This password reset link has expired. Please request a new one.'])
                ->withInput();
        }

        // Verify the plain token against the stored hash
        if (!Hash::check($request->token, $resetRecord->token)) {
            return redirect()->back()
                ->withErrors(['email' => 'Invalid reset token. Please request a new password reset link.'])
                ->withInput();
        }

        // Update the password
        DB::table('candidate_registration')
            ->where('email', $request->email)
            ->update([
                'password'   => Hash::make($request->password),
                'updated_at' => now(),
            ]);

        // Invalidate the used token
        DB::table('candidate_password_resets')
            ->where('email', $request->email)
            ->delete();

        return redirect()->route('candidate.login')
            ->with('status', 'Password reset successfully! Please login with your new password.');
    }
}