<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CandidateRegistrationController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'                      => 'required|string|max:255',
            'email'                     => 'required|email|unique:candidates,email',
            'phone'                     => 'required|string|max:20',
            'gender'                    => 'required|in:Male,Female,Other',
            'date_of_birth_bs'          => 'required|string|max:20',
            'noc_employee'              => 'required|in:yes,no',
            'citizenship_number'        => 'required|string|unique:candidate_registration,citizenship_number',
            'nid'                       => 'nullable|string|max:50',
            'citizenship_issue_distric' => 'required|string|max:255',
            'citizenship_issue_date_bs' => 'required|string|max:20',
            'nid'                       => 'required|string|max:20',
            'noc_employee'              => 'required|string',
            'password'                  => 'required|string|min:8|confirmed',
        ], [
            'name.required'                      => 'Full Name is required.',
            'email.required'                     => 'Email is required.',
            'email.unique'                       => 'This email is already registered.',
            'phone.required'                     => 'Phone Number is required.',
            'gender.required'                    => 'Gender is required.',
            'date_of_birth_bs.required'          => 'Date of Birth is required.',
            'noc_employee.required'              => 'Please select if you are a NOC employee.',
            'noc_employee.in'                    => 'Invalid value for NOC Employee field.',
            'citizenship_number.required'        => 'Citizenship Number is required.',
            'citizenship_number.unique'          => 'This citizenship number is already registered.',
            'citizenship_issue_distric.required' => 'Citizenship Issue District is required.',
            'citizenship_issue_date_bs.required' => 'Citizenship Issue Date is required.',
            'password.required'                  => 'Password is required.',
            'password.min'                       => 'Password must be at least 8 characters.',
            'password.confirmed'                 => 'Password confirmation does not match.',
        ]);

        try {
            // Split full name into first / middle / last
            $nameParts  = explode(' ', trim($validated['name']), 3);
            $firstName  = $nameParts[0];
            $middleName = count($nameParts) === 3 ? $nameParts[1] : null;
            $lastName   = count($nameParts) >= 2 ? $nameParts[count($nameParts) - 1] : $firstName;

            // Auto-generate unique username from email prefix
            $baseUsername = Str::slug(explode('@', $validated['email'])[0], '_');
            $username     = $baseUsername;
            $counter      = 1;
            while (Candidate::where('username', $username)->exists()) {
                $username = $baseUsername . '_' . $counter++;
            }

            Candidate::create([
                'first_name'                 => $firstName,
                'middle_name'                => $middleName,
                'last_name'                  => $lastName,
                'username'                   => $username,
                'email'                      => $validated['email'],
                'phone'                      => $validated['phone'],
                'gender'                     => $validated['gender'],
                'date_of_birth_bs'           => $validated['date_of_birth_bs'],
                'noc_employee'               => $validated['noc_employee'],
                'citizenship_number'         => $validated['citizenship_number'],
                'nid'                        => $validated['nid'] ?? null,
                'citizenship_issue_distric'  => $validated['citizenship_issue_distric'],
                'citizenship_issue_date_bs'  => $validated['citizenship_issue_date_bs'],
                'password'                   => Hash::make($validated['password']),
                'status'                     => 'active',
                'email_verified_at'          => now(),
            ]);

            Log::info('New candidate registered: ' . $validated['email']);

            return redirect()
                ->route('candidate.login')
                ->with('success', 'Registration successful! You can now login to your account.');

        } catch (\Exception $e) {
            Log::error('Candidate registration failed: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Registration failed. Please try again. ' . $e->getMessage()])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }
}
