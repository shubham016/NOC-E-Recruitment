<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\ApplicationForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    /**
     * Display the Admin's own profile
     */
    public function show()
    {
        $admin = Auth::guard('admin')->user();

        // Get statistics
        $stats = [
            'total_vacancies' => JobPosting::count(),
            'active_vacancies' => JobPosting::where('status', 'active')->count(),
            'total_applications' => ApplicationForm::count(),
            'pending_applications' => ApplicationForm::where('status', 'pending')->count(),
        ];

        // Get recent jobs
        $recentJobs = JobPosting::latest()->take(5)->get();

        return view('admin.profile.show', compact('admin', 'stats', 'recentJobs'));
    }

    /**
     * Show the form for editing own profile
     */
    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Update own profile
     */
    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($admin->photo) {
                Storage::disk('public')->delete($admin->photo);
            }
            $validated['photo'] = $request->file('photo')->store('admin-photos', 'public');
        }

        $admin->update($validated);

        return redirect()->route('admin.settings')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show unified settings page (profile + change password tabs)
     */
    public function settings()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.settings.index', compact('admin'));
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.change-password', compact('admin'));
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

        $admin = Auth::guard('admin')->user();

        // Verify current password
        if (!Hash::check($validated['current_password'], $admin->password)) {
            return back()->with('error', 'Current password is incorrect!');
        }

        $admin->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.settings')
            ->with('success', 'Password changed successfully!');
    }
}
