<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    /**
     * Show settings page
     */
    public function index()
    {
        $candidate = Auth::guard('candidate')->user();
        return view('candidate.settings.index', compact('candidate'));
    }

    /**
     * Update account settings
     */
    public function updateAccount(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email,' . $candidate->id,
            'mobile_number' => 'required|string|max:10',
        ]);

        $candidate->update($validated);

        return back()->with('success', 'Account settings updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $candidate->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $candidate->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();

        $settings = [
            'email_job_alerts' => $request->boolean('email_job_alerts'),
            'email_application_updates' => $request->boolean('email_application_updates'),
            'email_interview_reminders' => $request->boolean('email_interview_reminders'),
            'email_marketing' => $request->boolean('email_marketing'),
            'sms_notifications' => $request->boolean('sms_notifications'),
        ];

        // Store in JSON column or separate table
        $candidate->update([
            'notification_settings' => json_encode($settings)
        ]);

        return back()->with('success', 'Notification preferences updated successfully!');
    }

    /**
     * Update privacy settings
     */
    public function updatePrivacy(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();

        $settings = [
            'profile_visibility' => $request->input('profile_visibility'),
            'show_email' => $request->boolean('show_email'),
            'show_phone' => $request->boolean('show_phone'),
            'allow_search' => $request->boolean('allow_search'),
        ];

        $candidate->update([
            'privacy_settings' => json_encode($settings)
        ]);

        return back()->with('success', 'Privacy settings updated successfully!');
    }

    /**
     * Delete account
     */
    public function deleteAccount(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();

        $validated = $request->validate([
            'password' => 'required',
            'confirmation' => 'required|in:DELETE',
        ]);

        // Verify password
        if (!Hash::check($validated['password'], $candidate->password)) {
            return back()->withErrors(['password' => 'Password is incorrect']);
        }

        // Logout
        Auth::guard('candidate')->logout();

        // Delete candidate
        $candidate->delete();

        return redirect()->route('candidate.login')->with('success', 'Your account has been deleted successfully.');
    }
}