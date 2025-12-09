<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Application;
use Illuminate\Http\Request;

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
                    ->orWhere('phone', 'like', "%{$search}%");
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
            'this_month' => Candidate::whereMonth('created_at', now()->month)->count(),
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
        $candidate->delete();

        return redirect()
            ->route('admin.candidates.index')
            ->with('success', 'Candidate deleted successfully!');
    }
}