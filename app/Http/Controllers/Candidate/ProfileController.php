<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
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
            'total' => $candidate->applications()->count(),
            'pending' => $candidate->applications()->where('status', 'pending')->count(),
            'under_review' => $candidate->applications()->where('status', 'under_review')->count(),
            'shortlisted' => $candidate->applications()->where('status', 'shortlisted')->count(),
            'rejected' => $candidate->applications()->where('status', 'rejected')->count(),
        ];

        // Get recent applications
        $recentApplications = $candidate->applications()
            ->with('jobPosting')
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
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'mobile_number' => $validated['mobile_number'],
            'email' => $validated['email'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'country' => $validated['country'],
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $candidate->update($updateData);

        return back()->with('success', 'Profile updated successfully!');
    }
}