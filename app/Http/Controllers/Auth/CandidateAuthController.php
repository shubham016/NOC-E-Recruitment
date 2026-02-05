<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CandidateAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration
    |--------------------------------------------------------------------------
    */
    
    // Show registration form
    public function showRegisterForm()
    {
        return view('auth.candidate.register');
    }
    
    // Handle registration
    public function register(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidate_registration,email',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth_bs' => 'required|string',
            'citizenship_number' => 'required|string|unique:candidate_registration,citizenship_number',
            'citizenship_issue_distric' => 'required|string|max:255',
            'citizenship_issue_date_bs' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Insert into database
        DB::table('candidate_registration')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'date_of_birth_bs' => $request->date_of_birth_bs,
            'citizenship_number' => $request->citizenship_number,
            'citizenship_issue_distric' => $request->citizenship_issue_distric,
            'citizenship_issue_date_bs' => $request->citizenship_issue_date_bs,
            'password' => Hash::make($request->password),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('candidate.login')
            ->with('success', 'Registration successful! Please login with your email or citizenship number.');
    }

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */
    
    // Show login form
    public function showLoginForm()
    {
        return view('auth.candidate.login');
    }

    // Handle login with either email or citizenship number
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|min:8',
        ]);

        // Check if login_id is email or citizenship number
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'citizenship_number';
        
        // Get candidate from database
        $candidate = DB::table('candidate_registration')
            ->where($fieldType, $request->email)
            ->first();

        // Check if candidate exists and password matches
        if ($candidate && Hash::check($request->password, $candidate->password)) {
            // Create session for the candidate
            session([
                'candidate_id' => $candidate->id,
                'candidate_name' => $candidate->name,
                'candidate_email' => $candidate->email,
                'candidate_logged_in' => true
            ]);
            
            return redirect()->intended(route('candidate.dashboard'))
                ->with('success', 'Welcome back, ' . $candidate->name . '!');
        }

        return back()->withErrors([
            'login_id' => 'Invalid email/citizenship number or password.',
        ])->withInput($request->only('email'));
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    
    // Handle logout
    public function logout(Request $request)
    {
        session()->forget(['candidate_id', 'candidate_name', 'candidate_email', 'candidate_logged_in']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('candidate.login')
            ->with('success', 'Logged out successfully!');
    }
}