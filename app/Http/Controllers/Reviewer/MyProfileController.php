<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class MyProfileController extends Controller
{
    // Show profile
    public function index()
    {
        $user = Auth::guard('reviewer')->user(); // important
        return view('reviewer.myprofile', compact('user'));
    }

    // Show edit form
    public function edit()
    {
        $user = Auth::guard('reviewer')->user();
        return view('reviewer.myprofile', compact('user'));
    }

    // Update profile
    public function update(Request $request)
    {
        $user = Auth::guard('reviewer')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email'
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('reviewer.myprofile')
                         ->with('success', 'Profile updated successfully');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->guard('reviewer')->user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect');
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully');
    }
}