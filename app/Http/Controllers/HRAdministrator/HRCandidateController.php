<?php

namespace App\Http\Controllers\HRAdministrator;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\ApplicationForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HRCandidateController extends Controller
{
    /**
     * Get the authenticated HR Administrator
     */
    private function getAuthUser()
    {
        return Auth::guard('hr_administrator')->user();
    }

    /**
     * Display a listing of candidates
     */
    public function index(Request $request)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $query = Candidate::withCount('applications');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('mobile_number', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $candidates = $query->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => Candidate::count(),
            'active' => Candidate::where('status', 'active')->count(),
            'inactive' => Candidate::where('status', 'inactive')->count(),
            'verified' => Candidate::whereNotNull('email_verified_at')->count(),
        ];

        return view('hr-administrator.candidates.index', compact('candidates', 'stats', 'hrAdmin'));
    }

    /**
     * Display the specified candidate
     */
    public function show($id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $candidate = Candidate::with(['applicationForms.jobPosting'])->findOrFail($id);

        // Get application statistics for this candidate
        $applicationStats = [
            'total' => $candidate->applicationForms->count(),
            'pending' => $candidate->applicationForms->where('status', 'pending')->count(),
            'shortlisted' => $candidate->applicationForms->where('status', 'shortlisted')->count(),
            'rejected' => $candidate->applicationForms->where('status', 'rejected')->count(),
        ];

        return view('hr-administrator.candidates.show', compact('candidate', 'applicationStats', 'hrAdmin'));
    }

    /**
     * Show the form for editing the specified candidate
     */
    public function edit($id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $candidate = Candidate::findOrFail($id);

        return view('hr-administrator.candidates.edit', compact('candidate', 'hrAdmin'));
    }

    /**
     * Update the specified candidate
     */
    public function update(Request $request, $id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $candidate = Candidate::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email,' . $id,
            'mobile_number' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        $candidate->update($validated);

        return redirect()
            ->route('hr-administrator.candidates.show', $id)
            ->with('success', 'Candidate updated successfully!');
    }

    /**
     * Update candidate status
     */
    public function updateStatus(Request $request, $id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $candidate = Candidate::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $candidate->update(['status' => $validated['status']]);

        return redirect()
            ->back()
            ->with('success', 'Candidate status updated successfully!');
    }

    /**
     * Remove the specified candidate
     */
    public function destroy($id)
    {
        $hrAdmin = $this->getAuthUser();

        if (!$hrAdmin) {
            return redirect()->route('hr-administrator.login')
                ->with('error', 'Please login to continue.');
        }

        $candidate = Candidate::findOrFail($id);

        // Check if candidate has applications
        if ($candidate->applicationForms()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete candidate with existing applications.');
        }

        $candidate->delete();

        return redirect()
            ->route('hr-administrator.candidates.index')
            ->with('success', 'Candidate deleted successfully!');
    }
}