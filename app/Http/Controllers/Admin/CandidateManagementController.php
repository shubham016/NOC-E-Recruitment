<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\CandidateRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidateManagementController extends Controller
{
    /**
     * Display all candidates
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        // Candidate list must come from registered candidate accounts.
        // Applications are optional and are counted through citizenship number.
        $candidates = CandidateRegistration::query()
            ->withCount(['applicationForms as applications_count'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name_english', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('citizenship_number', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => CandidateRegistration::count(),
            'this_month' => CandidateRegistration::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'with_applications' => CandidateRegistration::has('applicationForms')->count(),
        ];

        return view('admin.candidates.index', compact('candidates', 'stats', 'search'));
    }

    /**
     * Show single candidate details
     */
    public function show($id)
    {
        $registration = CandidateRegistration::findOrFail($id);

        $applications = ApplicationForm::where(function ($query) use ($registration) {
                if ($registration->citizenship_number) {
                    $query->where('citizenship_number', $registration->citizenship_number);
                }

                if ($registration->email) {
                    $query->orWhere('email', $registration->email);
                }
            })
            ->with('vacancy', 'reviewer')
            ->latest()
            ->get();

        $candidate = $this->candidateViewData($registration, $applications->count());

        $applicationStats = [
            'total' => $applications->count(),
            'pending' => $applications->whereIn('status', ['pending', 'assigned', 'edited'])->count(),
            'approved' => $applications->where('status', 'approved')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
            'selected' => $applications->where('status', 'selected')->count(),
        ];

        return view('admin.candidates.show', compact('candidate', 'applications', 'applicationStats'));
    }

    private function candidateViewData(CandidateRegistration $registration, int $applicationsCount = 0): object
    {
        $nameParts = explode(' ', trim((string) $registration->name_english));
        $firstName = $nameParts[0] ?? '';
        $lastName = count($nameParts) > 1 ? array_pop($nameParts) : '';
        $middleName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';

        return (object) [
            'id' => $registration->id,
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'name' => $registration->name_english ?: $registration->email,
            'username' => $registration->citizenship_number ?? $registration->email,
            'email' => $registration->email,
            'mobile_number' => $registration->phone,
            'city' => $registration->mailing_municipality ?? $registration->permanent_municipality ?? '',
            'state' => $registration->mailing_province ?? $registration->permanent_province ?? '',
            'country' => 'Nepal',
            'qualification' => $registration->education_level ?? '',
            'status' => 'active',
            'email_verified_at' => null,
            'photo' => $registration->passport_size_photo,
            'created_at' => $registration->created_at,
            'updated_at' => $registration->updated_at,
            'applications_count' => $applicationsCount,
        ];
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $registration = CandidateRegistration::findOrFail($id);
        $candidate = $this->candidateViewData($registration);

        return view('admin.candidates.edit', compact('candidate'));
    }

    /**
     * Update candidate information
     */
    public function update(Request $request, $id)
    {
        $registration = CandidateRegistration::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidate_registration,email,' . $registration->id,
            'mobile_number' => 'required|string|max:20',
            'qualification' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);

        $fullName = trim($validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name']);

        $registration->update([
            'name_english' => $fullName,
            'email' => $validated['email'],
            'phone' => $validated['mobile_number'],
            'education_level' => $validated['qualification'] ?? $registration->education_level,
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
        $registration = CandidateRegistration::findOrFail($id);
        $email = $registration->email;
        $citizenshipNumber = $registration->citizenship_number;

        $applicationsQuery = ApplicationForm::where(function ($query) use ($email, $citizenshipNumber) {
            if ($citizenshipNumber) {
                $query->where('citizenship_number', $citizenshipNumber);
            }

            if ($email) {
                $query->orWhere('email', $email);
            }
        });

        $count = $applicationsQuery->count();

        DB::transaction(function () use ($applicationsQuery, $registration) {
            $applicationsQuery->delete();
            $registration->delete();
        });

        return redirect()
            ->route('admin.candidates.index')
            ->with('success', "Candidate deleted successfully! ({$count} application(s) removed)");
    }
}
