<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\CandidateRegistration;

class CandidateController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REGISTRATION
    |--------------------------------------------------------------------------
    */

    public function showRegisterForm()
    {
        return view('candidate.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_english'               => 'required|string|max:150',
            'email'                      => 'required|email|unique:candidate_registration,email',
            'gender'                     => 'required|in:Male,Female,Other',
            'birth_date_bs'           => 'required|string',
            'citizenship_number'         => 'required|string|unique:candidate_registration,citizenship_number',
            'nid'                        => 'nullable|string|unique:candidate_registration,nid',
            'noc_employee'               => 'required|string|in:yes,no',
            'employee_id'                => 'required_if:noc_employee,yes|nullable|string|max:50',
            'citizenship_issue_district' => 'required|string|max:100',
            'citizenship_issue_date_bs'  => 'required|string',
            'password'                   => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::table('candidate_registration')->insert([
                'name_english'               => $request->name_english,
                'email'                      => $request->email,
                'phone'                      => $request->phone,
                'gender'                     => $request->gender,
                'birth_date_bs'           => $request->birth_date_bs,
                'citizenship_number'         => $request->citizenship_number,
                'citizenship_issue_district' => $request->citizenship_issue_district,
                'citizenship_issue_date_bs'  => $request->citizenship_issue_date_bs,
                'nid'                        => $request->nid,
                'noc_employee'               => $request->noc_employee,
                'employee_id'                => $request->employee_id,
                'password'                   => Hash::make($request->password),
                'created_at'                 => now(),
                'updated_at'                 => now(),
            ]);

            Log::info('Candidate registered: ' . $request->email);

            return redirect()
                ->route('candidate.login')
                ->with('success', 'Registration successful! You can now login to your account.');

        } catch (\Exception $e) {
            Log::error('Candidate registration failed: ' . $e->getMessage());

            return redirect()->back()
                ->withErrors(['error' => 'Registration failed. Please try again.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN / LOGOUT
    |--------------------------------------------------------------------------
    */

    public function showLoginForm()
    {
        return view('candidate.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'citizenship_number';

        $candidate = CandidateRegistration::where($fieldType, $request->email)->first();

        if ($candidate && Hash::check($request->password, $candidate->password)) {
            Auth::guard('candidate')->login($candidate);
            $request->session()->regenerate();

            return redirect()->route('candidate.dashboard')
                ->with('success', 'Welcome, ' . $candidate->name_english . '!');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Invalid email/citizenship number or password.'])
            ->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('candidate')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('candidate.login')
            ->with('success', 'Logged out successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD & PROFILE
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $candidate = Auth::guard('candidate')->user();

        $applicationsCount = DB::table('application_form')
            ->where('citizenship_number', $candidate->citizenship_number)
            ->count();

        $jobpostingsCount = DB::table('job_postings')->count();

        return view('candidate.dashboard', [
            'candidate'         => $candidate,
            'applicationsCount' => $applicationsCount,
            'jobpostingsCount'  => $jobpostingsCount,
            'job'               => null,
        ]);
    }

    public function profile()
    {
        $candidate = Auth::guard('candidate')->user();
        return view('candidate.profile', compact('candidate'));
    }

    public function editProfile()
    {
        $candidate = CandidateRegistration::find(Auth::guard('candidate')->id());

        return view('candidate.edit-profile', compact('candidate'));
    }
    
    public function updateProfile(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();

        $validator = Validator::make($request->all(), [
            // Personal
            'name_english'                => 'required|string|max:150',
            'name_nepali'                 => 'nullable|string|max:150',
            'birth_date_bs'               => 'required|string',
            'birth_date_ad'               => 'nullable|string',
            'age'                         => 'nullable|string',  // auto-filled by JS, may be empty on first save
            'email'                       => 'required|email|unique:candidate_registration,email,' . $candidate->id,
            'phone'                       => 'required|string|max:20',
            'alternate_phone_number'      => 'nullable|string|max:20',
            'gender'                      => 'required|in:Male,Female,Other',
            'marital_status'              => 'required|string',
            'spouse_name_english'         => 'nullable|string|max:100',
            'spouse_nationality'          => 'nullable|string|max:50',

            // Citizenship
            'citizenship_number'          => 'required|string',
            'citizenship_issue_date_bs'   => 'required|string',
            'citizenship_issue_district'  => 'required|string|max:100',

            // Family
            'father_name_english'         => 'required|string|max:100',
            'mother_name_english'         => 'required|string|max:100',
            'grandfather_name_english'    => 'required|string|max:100',

            // General
            'blood_group'                 => 'required|string',
            'nationality'                 => 'required|string|max:50',
            'noc_employee'                => 'required|in:yes,no',
            'physical_disability'         => 'required|in:yes,no',
            'religion'                    => 'required|string',
            'community'                   => 'required|string',
            'ethnic_group'                => 'required|string',
            'mother_tongue'               => 'required|string|max:50',
            'employment_status'           => 'required|string',

            // Address
            'permanent_province'          => 'required|string',
            'permanent_district'          => 'required|string',
            'permanent_municipality'      => 'required|string',
            'permanent_ward'              => 'required|string',

            // Education
            'education_level'             => 'required|string',
            'field_of_study'              => 'required|string|max:100',
            'institution_name'            => 'required|string|max:150',
            'graduation_year'             => 'required|string|max:4',
            'university'                  => 'required|string|max:150',

            // Experience
            'has_work_experience'         => 'required|in:Yes,No',

            // Files — all nullable to preserve existing uploads
            'passport_size_photo'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:700',
            'citizenship_id_document'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:700',
            'signature'                   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:700',
            'transcript'                  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:700',
            'character_certificate'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:700',
            'equivalency_certificate'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:700',
            'noc_id_card'                 => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:700',
            'disability_certificate'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:700',
            'ethnic_certificate'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:700',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // ── Build data array ──────────────────────────────────────────────
        $data = [
            // Personal
            'name_english'               => $request->name_english,
            'name_nepali'                => $request->name_nepali,
            'birth_date_bs'              => $request->birth_date_bs,
            'birth_date_ad'              => $request->birth_date_ad ?: null,
            'age'                        => $request->age ?: $candidate->age,
            'email'                      => $request->email,
            'phone'                      => $request->phone,
            'alternate_phone_number'     => $request->alternate_phone_number,
            'gender'                     => $request->gender,
            'marital_status'             => $request->marital_status,
            'spouse_name_english'        => $request->spouse_name_english,
            'spouse_nationality'         => $request->spouse_nationality,

            // Citizenship
            'citizenship_number'         => $request->citizenship_number,
            'citizenship_issue_date_bs'  => $request->citizenship_issue_date_bs,
            'citizenship_issue_district' => $request->citizenship_issue_district,

            // Family
            'father_name_english'        => $request->father_name_english,
            'mother_name_english'        => $request->mother_name_english,
            'grandfather_name_english'   => $request->grandfather_name_english,

            // General
            'blood_group'                => $request->blood_group,
            'nationality'                => $request->nationality,
            'noc_employee'               => $request->noc_employee,
            'physical_disability'        => $request->physical_disability,
            'disability_other'           => $request->disability_other,
            'religion'                   => $request->religion,
            'religion_other'             => $request->religion_other,
            'community'                  => $request->community,
            'community_other'            => $request->community_other,
            'ethnic_group'               => $request->ethnic_group,
            'ethnic_group_other'         => $request->ethnic_group_other,
            'mother_tongue'              => $request->mother_tongue,
            'employment_status'          => $request->employment_status,

            // Address
            'permanent_province'         => $request->permanent_province,
            'permanent_district'         => $request->permanent_district,
            'permanent_municipality'     => $request->permanent_municipality,
            'permanent_ward'             => $request->permanent_ward,
            'permanent_tole'             => $request->permanent_tole,
            'permanent_house_number'     => $request->permanent_house_number,
            'same_as_permanent'          => $request->has('same_as_permanent') ? 1 : 0,
            'mailing_province'           => $request->mailing_province,
            'mailing_district'           => $request->mailing_district,
            'mailing_municipality'       => $request->mailing_municipality,
            'mailing_ward'               => $request->mailing_ward,
            'mailing_tole'               => $request->mailing_tole,
            'mailing_house_number'       => $request->mailing_house_number,

            // Education
            'education_level'            => $request->education_level,
            'field_of_study'             => $request->field_of_study,
            'institution_name'           => $request->institution_name,
            'graduation_year'            => $request->graduation_year,
            'graduation_year_english'    => $request->graduation_year_english,
            'university'                 => $request->university,

            // Experience
            'has_work_experience'        => $request->has_work_experience,
        ];

        // ── Experience rows ───────────────────────────────────────────────
        foreach (range(1, 10) as $i) {
            $data["exp{$i}_organization"]  = null;
            $data["exp{$i}_position"]      = null;
            $data["exp{$i}_start_date_bs"] = null;
            $data["exp{$i}_start_date"]    = null;
            $data["exp{$i}_end_date_bs"]   = null;
            $data["exp{$i}_end_date"]      = null;
            $data["exp{$i}_years"]         = null;
            $data["exp{$i}_document"]      = $candidate->{"exp{$i}_document"}; // preserve existing doc
        }

        if ($request->has_work_experience === 'Yes') {
            foreach (range(1, 10) as $i) {
                $org = $request->input("exp{$i}_organization");
                if (empty($org)) continue;

                $data["exp{$i}_organization"]  = $org;
                $data["exp{$i}_position"]      = $request->input("exp{$i}_position");
                $data["exp{$i}_start_date_bs"] = $request->input("exp{$i}_start_date_bs");
                $data["exp{$i}_start_date"]    = $request->input("exp{$i}_start_date") ?: null;
                $data["exp{$i}_end_date_bs"]   = $request->input("exp{$i}_end_date_bs");
                $data["exp{$i}_end_date"]      = $request->input("exp{$i}_end_date") ?: null;
                $data["exp{$i}_years"]         = $request->input("exp{$i}_years");

                if ($request->hasFile("exp{$i}_document")) {
                    $oldDoc = $candidate->{"exp{$i}_document"};
                    if (!empty($oldDoc)) Storage::disk('public')->delete($oldDoc);
                    $data["exp{$i}_document"] = $request->file("exp{$i}_document")
                        ->store('candidate-documents', 'public');
                }
            }
        } else {
            foreach (range(1, 10) as $i) {
                $oldDoc = $candidate->{"exp{$i}_document"};
                if (!empty($oldDoc)) Storage::disk('public')->delete($oldDoc);
                $data["exp{$i}_document"] = null;
            }
        }

        // ── File uploads ──────────────────────────────────────────────────
        $fileFields = [
            'passport_size_photo',
            'citizenship_id_document',
            'signature',
            'transcript',
            'character_certificate',
            'equivalency_certificate',
            'noc_id_card',
            'disability_certificate',
            'ethnic_certificate',
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                if (!empty($candidate->$field)) {
                    Storage::disk('public')->delete($candidate->$field);
                }
                $data[$field] = $request->file($field)
                    ->store('candidate-documents', 'public');
            }
            // No new upload = keep existing, so we intentionally don't touch $data[$field]
        }

        // ── Save ──────────────────────────────────────────────────────────
        try {
            $candidate->update($data);

            return redirect()
                ->route('candidate.my-profile')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            Log::error('Profile update failed for candidate ' . $candidate->id . ': ' . $e->getMessage());

            return redirect()->back()
                ->withErrors(['error' => 'Profile update failed: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CHANGE PASSWORD
    |--------------------------------------------------------------------------
    */

    public function showChangePasswordForm()
    {
        return view('candidate.change-password');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $candidate = Auth::guard('candidate')->user();

        if (!Hash::check($request->current_password, $candidate->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput();
        }

        $candidate->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->back()
            ->with('success', 'Password changed successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    public function showForgotPasswordForm()
    {
        return view('candidate.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $candidate = DB::table('candidate_registration')
            ->where('email', $request->email)
            ->first();

        if ($candidate) {
            DB::table('candidate_password_resets')
                ->where('email', $request->email)
                ->delete();

            $plainToken = Str::random(64);

            DB::table('candidate_password_resets')->insert([
                'email'      => $request->email,
                'token'      => Hash::make($plainToken),
                'created_at' => Carbon::now(),
            ]);

            $resetUrl = route('candidate.password.reset', ['token' => $plainToken])
                        . '?email=' . urlencode($request->email);

            Mail::send(
                'candidate.emails.reset-password',
                ['resetUrl' => $resetUrl, 'candidate' => $candidate],
                function ($message) use ($request) {
                    $message->to($request->email)
                            ->subject('NOC E-Recruitment – Password Reset Request');
                }
            );
        }

        return redirect()->back()->with(
            'status',
            'If your email is registered, you will receive a password reset link shortly.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RESET PASSWORD
    |--------------------------------------------------------------------------
    */

    public function showResetPasswordForm(Request $request, string $token)
    {
        return view('candidate.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $resetRecord = DB::table('candidate_password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return redirect()->back()
                ->withErrors(['email' => 'No password reset request found for this email address.'])
                ->withInput();
        }

        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('candidate_password_resets')
                ->where('email', $request->email)
                ->delete();

            return redirect()->back()
                ->withErrors(['email' => 'This password reset link has expired. Please request a new one.'])
                ->withInput();
        }

        if (!Hash::check($request->token, $resetRecord->token)) {
            return redirect()->back()
                ->withErrors(['email' => 'Invalid reset token. Please request a new password reset link.'])
                ->withInput();
        }

        DB::table('candidate_registration')
            ->where('email', $request->email)
            ->update([
                'password'   => Hash::make($request->password),
                'updated_at' => now(),
            ]);

        DB::table('candidate_password_resets')
            ->where('email', $request->email)
            ->delete();

        return redirect()->route('candidate.login')
            ->with('status', 'Password reset successfully! Please login with your new password.');
    }
}
