<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Application;
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

        $candidates = Candidate::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('mobile_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->withCount('applications')
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Candidate::count(),
            'active' => Candidate::where('status', 'active')->count(),
            'inactive' => Candidate::where('status', 'inactive')->count(),
            'this_month' => Candidate::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('admin.candidates.index', compact('candidates', 'stats', 'search', 'status'));
    }

    /**
     * Show single candidate details
     */
    public function show($id)
    {
        $candidate = Candidate::withCount('applications')->findOrFail($id);

        $applications = Application::where('candidate_id', $id)
            ->with('jobPosting', 'reviewer')
            ->latest()
            ->get();

        $applicationStats = [
            'total' => $applications->count(),
            'pending' => $applications->where('status', 'pending')->count(),
            'under_review' => $applications->where('status', 'under_review')->count(),
            'shortlisted' => $applications->where('status', 'shortlisted')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
        ];

        return view('admin.candidates.show', compact('candidate', 'applications', 'applicationStats'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $candidate = Candidate::findOrFail($id);
        return view('admin.candidates.edit', compact('candidate'));
    }

    /**
     * Update candidate information
     */
    public function update(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:candidates,username,' . $id,
            'email' => 'required|email|unique:candidates,email,' . $id,
            'mobile_number' => 'required|string|max:10|unique:candidates,mobile_number,' . $id,
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $candidate->update($validated);

        return redirect()
            ->route('admin.candidates.show', $id)
            ->with('success', 'Candidate profile updated successfully!');
    }

    /**
     * Update candidate status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $candidate = Candidate::findOrFail($id);
        $candidate->update(['status' => $request->status]);

        return back()->with('success', 'Candidate status updated successfully!');
    }

    /**
     * Delete candidate
     */
    public function destroy($id)
    {
        $candidate = Candidate::findOrFail($id);

        // Check if candidate has applications
        if ($candidate->applications()->count() > 0) {
            return back()->with('error', 'Cannot delete candidate with existing applications.');
        }

        $candidate->delete();

        return redirect()
            ->route('admin.candidates.index')
            ->with('success', 'Candidate deleted successfully!');
    }
}