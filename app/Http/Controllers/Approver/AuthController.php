<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Approver;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function showLogin()
    {
        return view('approver.login');
    }

    public function login(Request $request)
        {
            $request->validate([
                'employee_id' => 'required',
                'password' => 'required'
            ]);

            $credentials = [
                'employee_id' => $request->employee_id,
                'password' => $request->password
            ];

            $approver = Approver::where('employee_id', $request->employee_id)->where('password', $request->password)->first();

            if ($approver) {
                session([
                    'approver_id' => $approver->id,
                    'name' => $approver->name
                ]);
            }
            // Use the approver guard
            if (Auth::guard('approver')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('approver.dashboard');
            }

            return back()->with('error', 'Invalid Employee ID or Password');
        }

    public function dashboard()
    {
        return view('approver.dashboard');
    }

    public function logout(Request $request)
{
    Auth::guard('approver')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('approver.login');
}
}