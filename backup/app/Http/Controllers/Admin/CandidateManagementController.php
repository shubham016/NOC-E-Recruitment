<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\ApplicationForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CandidateManagementController extends Controller
{
    /**
     * Display all candidates
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        // Get unique candidates from application_form table
        $candidates = ApplicationForm::query()
            ->select([
                \DB::raw('MIN(id) as id'),
                'email',
                \DB::raw('MAX(name_english) as name_english'),
                \DB::raw('MAX(citizenship_number) as citizenship_number'),
                \DB::raw('MAX(phone) as phone'),
                \DB::raw('MAX(created_at) as created_at'),
                \DB::raw('COUNT(*) as applications_count')
            ])
            ->groupBy('email')
            ->when($search, function ($query, $search) {
                $query->havingRaw('MAX(name_english) like ?', ["%{$search}%"])
                    ->orHavingRaw('email like ?', ["%{$search}%"])
                    ->orHavingRaw('MAX(phone) like ?', ["%{$search}%"])
                    ->orHavingRaw('MAX(citizenship_number) like ?', ["%{$search}%"]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => ApplicationForm::distinct('email')->count('email'),
            'this_month' => ApplicationForm::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->distinct('email')
                ->count('email'),
            'with_applications' => ApplicationForm::distinct('email')->count('email'),
        ];

        return view('admin.candidates.index', compact('candidates', 'stats', 'search'));
    }

    /**
     * Show single candidate details
     */
    public function show($id)
    {
        // Get the specific application form record
        $application = ApplicationForm::findOrFail($id);

        // Get all applications with the same email (same candidate)
        $applications = ApplicationForm::where('email', $application->email)
            ->with('vacancy', 'reviewer')
            ->latest()
            ->get();

        // Parse the full name into parts
        $nameParts = explode(' ', trim($application->name_english));
        $firstName = $nameParts[0] ?? '';
        $lastName = count($nameParts) > 1 ? array_pop($nameParts) : '';
        $middleName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';

        // Create a candidate object from the application form data
        $candidate = (object) [
            'id' => $application->id,
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'name' => $application->name_english,
            'username' => $application->citizenship_number ?? $application->email,
            'email' => $application->email,
            'mobile_number' => $application->phone,
            'city' => $application->temporary_municipality ?? '',
            'state' => $application->temporary_province ?? '',
            'country' => 'Nepal',
            'qualification' => $application->education_level ?? '',
            'status' => 'active',
            'email_verified_at' => null,
            'photo' => $application->passport_size_photo,
            'created_at' => $application->created_at,
            'updated_at' => $application->updated_at,
            'applications_count' => $applications->count(),
        ];

        $applicationStats = [
            'total' => $applications->count(),
            'pending' => $applications->where('status', 'pending')->count(),
            'approved' => $applications->where('status', 'approved')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
            'selected' => $applications->where('status', 'selected')->count(),
        ];

        return view('admin.candidates.show', compact('candidate', 'applications', 'applicationStats'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        // Get the specific application form record
        $application = ApplicationForm::findOrFail($id);

        // Parse the full name into parts
        $nameParts = explode(' ', trim($application->name_english));
        $firstName = $nameParts[0] ?? '';
        $lastName = count($nameParts) > 1 ? array_pop($nameParts) : '';
        $middleName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';

        // Create a candidate object from the application form data
        $candidate = (object) [
            'id' => $application->id,
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'username' => $application->citizenship_number ?? $application->email,
            'email' => $application->email,
            'mobile_number' => $application->phone,
            'city' => $application->temporary_municipality ?? '',
            'state' => $application->temporary_province ?? '',
            'country' => 'Nepal',
            'qualification' => $application->education_level ?? '',
            'status' => 'active',
            'created_at' => $application->created_at,
        ];

        return view('admin.candidates.edit', compact('candidate'));
    }

    /**
     * Update candidate information
     */
    public function update(Request $request, $id)
    {
        $application = ApplicationForm::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile_number' => 'required|string|max:10',
            'qualification' => 'nullable|string|max:255',
        ]);

        // Update the application form with the new data
        $fullName = trim($validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name']);

        $application->update([
            'name_english' => $fullName,
            'email' => $validated['email'],
            'phone' => $validated['mobile_number'],
            'education_level' => $validated['qualification'] ?? $application->education_level,
        ]);

        return redirect()
            ->route('admin.candidates.show', $id)
            ->with('success', 'Candidate information updated successfully!');
    }

    /**
     * Update candidate status - Disabled (status column doesn't exist)
     */
    public function updateStatus(Request $request, $id)
    {
        return back()->with('error', 'Status update is not available.');
    }

    /**
     * Delete candidate (and all their applications with same email)
     */
    public function destroy($id)
    {
        $application = ApplicationForm::findOrFail($id);
        $email = $application->email;

        // Get count of all applications with this email
        $count = ApplicationForm::where('email', $email)->count();

        // Delete all applications with this email
        ApplicationForm::where('email', $email)->delete();

        return redirect()
            ->route('admin.candidates.index')
            ->with('success', "Candidate deleted successfully! ({$count} application(s) removed)");
    }
}