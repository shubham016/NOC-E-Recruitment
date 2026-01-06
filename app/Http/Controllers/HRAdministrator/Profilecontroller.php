<?php

namespace App\Http\Controllers\HRAdministrator;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the HR Administrator's own profile
     */
    public function show()
    {
        // DEBUG: Check if we're even getting here
        dd('ProfileController show method called');

        $hrAdministrator = Auth::guard('hr_administrator')->user();

        // Get statistics
        $stats = [
            'total_jobs_posted' => JobPosting::where('posted_by', $hrAdministrator->id)->count(),
            'active_jobs' => JobPosting::where('posted_by', $hrAdministrator->id)->where('status', 'active')->count(),
            'closed_jobs' => JobPosting::where('posted_by', $hrAdministrator->id)->where('status', 'closed')->count(),
            'total_applications' => Application::whereHas('jobPosting', function ($q) use ($hrAdministrator) {
                $q->where('posted_by', $hrAdministrator->id);
            })->count(),
        ];

        // Get recent jobs
        $recentJobs = JobPosting::where('posted_by', $hrAdministrator->id)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.hr-administrators.show', compact('hrAdministrator', 'stats', 'recentJobs'));
    }

    /**
     * Show the form for editing own profile
     */
    public function edit()
    {
        $hrAdministrator = Auth::guard('hr_administrator')->user();
        return view('hr-administrator.profile.edit', compact('hrAdministrator'));
    }

    /**
     * Update own profile
     */
    public function update(Request $request)
    {
        $hrAdministrator = Auth::guard('hr_administrator')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:hr_administrators,email,' . $hrAdministrator->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($hrAdministrator->photo) {
                Storage::disk('public')->delete($hrAdministrator->photo);
            }
            $validated['photo'] = $request->file('photo')->store('hr-administrator-photos', 'public');
        }

        $hrAdministrator->update($validated);

        return redirect()->route('hr-administrator.profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Change own password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $hrAdministrator = Auth::guard('hr_administrator')->user();

        // Verify current password
        if (!Hash::check($validated['current_password'], $hrAdministrator->password)) {
            return back()->with('error', 'Current password is incorrect!');
        }

        $hrAdministrator->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('hr-administrator.profile.show')
            ->with('success', 'Password changed successfully!');
    }
}