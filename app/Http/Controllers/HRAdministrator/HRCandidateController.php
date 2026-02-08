<?php

namespace App\Http\Controllers\HRAdministrator;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Application;
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
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
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

        $candidate = Candidate::with(['applications.jobPosting'])->findOrFail($id);

        // Get application statistics for this candidate
        $applicationStats = [
            'total' => $candidate->applications->count(),
            'pending' => $candidate->applications->where('status', 'pending')->count(),
            'shortlisted' => $candidate->applications->where('status', 'shortlisted')->count(),
            'rejected' => $candidate->applications->where('status', 'rejected')->count(),
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email,' . $id,
            'phone' => 'nullable|string|max:20',
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
        if ($candidate->applications()->count() > 0) {
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