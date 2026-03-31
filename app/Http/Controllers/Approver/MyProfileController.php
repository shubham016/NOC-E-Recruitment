<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class MyProfileController extends Controller
{
    // Show profile
    public function index()
    {
        $user = Auth::guard('approver')->user(); // important
        return view('approver.myprofile', compact('user'));
    }

    // Show edit form
    public function edit()
    {
        $user = Auth::guard('approver')->user();
        return view('approver.myprofile', compact('user'));
    }

    // Update profile
    public function update(Request $request)
    {
        $user = Auth::guard('approver')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email'
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('approver.myprofile')
                         ->with('success', 'Profile updated successfully');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->guard('approver')->user();

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