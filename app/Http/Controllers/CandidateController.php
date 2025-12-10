<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class CandidateController extends Controller
{
    // Show Registration Form
    public function showRegisterForm()
    {
        return view('candidate.register');
    }
    
    // Handle Registration
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
        
        // Insert into database with hashed password
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
            ->with('success', 'Registration successful! Please login.');
    }
    
    // Show Login Form
    public function showLoginForm()
    {
        return view('candidate.login');
    }
    
    // Handle Login - NOW SUPPORTS BOTH EMAIL AND CITIZENSHIP NUMBER
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string', // Changed from citizenship_number
            'password' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Check if input is email or citizenship number
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'citizenship_number';
        
        // Check credentials
        $candidate = DB::table('candidate_registration')
            ->where($fieldType, $request->email)
            ->first();
        
        if ($candidate && Hash::check($request->password, $candidate->password)) {
            // Store candidate info in session
            Session::put('candidate_id', $candidate->id);
            Session::put('candidate_name', $candidate->name);
            Session::put('candidate_email', $candidate->email);
            Session::put('candidate_logged_in', true);
            
            return redirect()->route('candidate.dashboard')
                ->with('success', 'Welcome back, ' . $candidate->name . '!');
        }
        
        return redirect()->back()
            ->withErrors(['email' => 'Invalid email/citizenship number or password'])
            ->withInput();
    }
    
    // Candidate Dashboard
    public function dashboard()
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }
        
        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();
        
        $applicationsCount = DB::table('application_form')
            ->where('citizenship_number', $candidate->citizenship_number)
            ->count();

        return view('candidate.dashboard', [
            'candidate' => $candidate,
            'applicationsCount' => $applicationsCount
        ]);
    }
    
    // Logout
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

    // Show Candidate Profile
    public function profile()
    {
        // Check if candidate is logged in
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }
        
        // Get candidate information
        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();
        
        if (!$candidate) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Profile not found']);
        }
        
        return view('candidate.profile', compact('candidate'));
    }

    // Show Change Password Form
    public function showChangePasswordForm()
    {
        // Check if candidate is logged in
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }
        
        return view('candidate.change-password');
    }

    // Handle Password Update
    public function updatePassword(Request $request)
    {
        // Check if candidate is logged in
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get current candidate
        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        // Verify current password
        if (!Hash::check($request->current_password, $candidate->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput();
        }

        // Update password
        DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->update([
                'password' => Hash::make($request->new_password),
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Password changed successfully!');
    }
}