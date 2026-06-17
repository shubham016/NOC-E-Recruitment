<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\CandidateRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CandidateRegistrationController extends Controller
{
    public function register(Request $request)
    {
       /// dd($request->all());
        $validated = $request->validate([
        
            
            'name_english'              => 'required|string|max:255',
            'email'                     => 'required|email|unique:candidate_registration,email',
            'phone'                     => 'required|string|max:20',
            'gender'                    => 'required|in:Male,Female,Other',
            'date_of_birth_bs'          => 'required|string|max:20',
            'citizenship_number'        => 'required|string|unique:candidate_registration,citizenship_number',
            'citizenship_issue_district' => 'required|string|max:255',
            'citizenship_issue_date_bs' => 'required|string|max:20',
            'nid'                       => 'nullable|string|max:50|unique:candidate_registration,nid',
            'noc_employee'              => 'required|string',
            'employee_id'               => 'nullable|required_if:noc_employee,Yes|string|max:100',
            'password'                  => 'required|string|min:8|confirmed',
        ]
          
        
        , [
            'name_english.required'               => 'Full Name is required.',
            'email.required'                      => 'Email is required.',
            'email.unique'                        => 'This email is already registered.',
            'phone.required'                      => 'Phone Number is required.',
            'gender.required'                     => 'Gender is required.',
            'date_of_birth_bs.required'           => 'Date of Birth is required.',
            'citizenship_number.required'         => 'Citizenship Number is required.',
            'citizenship_number.unique'           => 'This citizenship number is already registered.',
            'citizenship_issue_district.required' => 'Citizenship Issue District is required.',
            'citizenship_issue_date_bs.required'  => 'Citizenship Issue Date is required.',
            'nid.unique'                          => 'This National ID is already registered.',
            'password.required'                   => 'Password is required.',
            'password.min'                        => 'Password must be at least 8 characters.',
            'password.confirmed'                  => 'Password confirmation does not match.',
        ]
          
        );
        //dd($validated);

        try {
            CandidateRegistration::create([
               ///  dd('Creating candidate with data: ' . json_encode($validated));

              //  dd($validated);
                'name_english'               => $validated['name_english'],
                'email'                      => $validated['email'],
                'phone'                      => $validated['phone'],
                'gender'                     => $validated['gender'],
                'date_of_birth_bs'           => $validated['date_of_birth_bs'],
                'citizenship_number'         => $validated['citizenship_number'],
                'citizenship_issue_district' => $validated['citizenship_issue_district'],
                'citizenship_issue_date_bs'  => $validated['citizenship_issue_date_bs'],
                'nid'                        => $validated['nid'] ?? null,
                'noc_employee'               => $validated['noc_employee'],
                'employee_id'                => $validated['employee_id'] ?? null,
                'password'                   => Hash::make($validated['password']),
                'profile_status'             => 'draft',
            ]);

            Log::info('New candidate registered: ' . $validated['email']);

            return redirect()
                ->route('candidate.login')
                ->with('success', 'Registration successful! You can now login.');

        } catch (\Exception $e) {
            Log::error('Candidate registration failed: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Registration failed. Please try again. ' . $e->getMessage()])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }
}