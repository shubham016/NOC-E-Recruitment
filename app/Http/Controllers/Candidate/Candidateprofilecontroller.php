<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\CandidateRegistration;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CandidateProfileController extends Controller
{
    /**
     * Display the candidate profile page.
     */
    public function show()
    {
        $candidateId = session('candidate_id');
        $candidate   = CandidateRegistration::find($candidateId);

        if (!$candidate) {
            return redirect()->route('candidate.login')
                ->with('error', 'Candidate not found.');
        }

        return view('candidate.my-profile', compact('candidate'));
    }

    /**
     * Show the edit profile page.
     */
    public function edit()
    {
        $candidateId = session('candidate_id');
        $candidate   = CandidateRegistration::find($candidateId);

        if (!$candidate) {
            return redirect()->route('candidate.login')
                ->with('error', 'Candidate not found.');
        }

        return view('candidate.edit-profile', compact('candidate'));
    }

    /**
     * Update candidate profile.
     *
     * All fields match CandidateRegistration $fillable exactly:
     *   name, email, phone, gender, date_of_birth_bs,
     *   citizenship_number, citizenship_issue_distric (DB typo — one 't'),
     *   citizenship_issue_date_bs, nid, noc_employee, employee_id
     */
    public function update(Request $request)
    {
        $candidateId = session('candidate_id');
        $candidate   = CandidateRegistration::find($candidateId);

        if (!$candidate) {
            return redirect()->route('candidate.login')
                ->with('error', 'Candidate not found.');
        }

        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
            ],
            'email' => [
                'required', 'email', 'max:255',
                // Table name is candidate_registration (with underscore)
                Rule::unique('candidate_registration', 'email')->ignore($candidate->id),
            ],
            'phone' => [
                'required', 'string', 'max:20',
            ],
            'gender' => [
                'required', 'in:Male,Female,Other',
            ],
            'date_of_birth_bs' => [
                'nullable', 'string', 'max:20',
            ],
            'nid' => [
                'nullable', 'string', 'max:100',
            ],
            'citizenship_number' => [
                'nullable', 'string', 'max:100',
                Rule::unique('candidate_registration', 'citizenship_number')->ignore($candidate->id),
            ],
            // DB column is citizenship_issue_distric — one 't', original typo preserved
            'citizenship_issue_distric' => [
                'nullable', 'string', 'max:255',
            ],
            'citizenship_issue_date_bs' => [
                'nullable', 'string', 'max:20',
            ],
            'noc_employee' => [
                'nullable', 'string',
            ],
            'employee_id' => [
                'required_if:noc_employee,Yes', 'string', 'max:100',
            ],
        ]);

        $candidate->update($validated);

        return redirect()
            ->route('candidate.my-profile')
            ->with('success', 'Profile updated successfully.');
    }
}