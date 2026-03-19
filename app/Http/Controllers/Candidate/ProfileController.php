<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show candidate profile
     */
    public function show()
    {
        $candidate = Auth::guard('candidate')->user();

        // Get application statistics
        $applicationStats = [
            'total' => $candidate->applicationForms()->where('status', '!=', 'draft')->count(),
            'pending' => $candidate->applicationForms()->where('status', 'pending')->count(),
            'approved' => $candidate->applicationForms()->where('status', 'approved')->count(),
            'shortlisted' => $candidate->applicationForms()->where('status', 'shortlisted')->count(),
            'rejected' => $candidate->applicationForms()->where('status', 'rejected')->count(),
        ];

        // Get recent applications
        $recentApplications = $candidate->applicationForms()
            ->where('status', '!=', 'draft')
            ->with('vacancy')
            ->latest()
            ->take(5)
            ->get();

        return view('candidate.profile.show', compact('candidate', 'applicationStats', 'recentApplications'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $candidate = Auth::guard('candidate')->user();
        return view('candidate.profile.edit', compact('candidate'));
    }

    /**
     * Update candidate profile
     */
    public function update(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:10',
            'email' => 'required|email|unique:candidates,email,' . $candidate->id,
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth_bs' => 'nullable|string|max:20',
            'citizenship_number' => 'nullable|string|max:255|unique:candidates,citizenship_number,' . $candidate->id,
            'citizenship_issue_district' => 'nullable|string|max:255',
            'citizenship_issue_date_bs' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'mobile_number' => $validated['mobile_number'],
            'email' => $validated['email'],
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'country' => $validated['country'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'date_of_birth_bs' => $validated['date_of_birth_bs'] ?? null,
            'citizenship_number' => $validated['citizenship_number'] ?? null,
            'citizenship_issue_district' => $validated['citizenship_issue_district'] ?? null,
            'citizenship_issue_date_bs' => $validated['citizenship_issue_date_bs'] ?? null,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $candidate->update($updateData);

        return back()->with('success', 'Profile updated successfully!');
    }
}
