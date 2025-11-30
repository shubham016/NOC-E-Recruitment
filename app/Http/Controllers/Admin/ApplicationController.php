<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPosting;
use App\Models\Reviewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    /**
     * Display a listing of applications
     */
    public function index(Request $request)
    {
        $query = Application::with(['jobPosting', 'candidate.user', 'reviewer'])
            ->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('candidate.user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhereHas('jobPosting', function ($q2) use ($search) {
                        $q2->where('advertisement_no', 'like', "%{$search}%")
                            ->orWhere('position_level', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Job posting filter
        if ($request->filled('job_posting_id')) {
            $query->where('job_posting_id', $request->job_posting_id);
        }

        // Reviewer filter
        if ($request->filled('reviewer_id')) {
            $query->where('reviewer_id', $request->reviewer_id);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $applications = $query->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => Application::count(),
            'pending' => Application::where('status', 'pending')->count(),
            'under_review' => Application::where('status', 'under_review')->count(),
            'shortlisted' => Application::where('status', 'shortlisted')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
        ];

        // Get job postings for filter dropdown
        $jobPostings = JobPosting::select('id', 'advertisement_no', 'position_level')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get reviewers for filter dropdown - FIXED LINE
        $reviewers = Reviewer::where('status', 'active')->get();

        return view('admin.applications.index', compact('applications', 'stats', 'jobPostings', 'reviewers'));
    }

    /**
     * Display the specified application
     */
    public function show($id)
    {
        $application = Application::with([
            'jobPosting',
            'candidate.user',
            'reviewer'  // REMOVED ->user from here
        ])->findOrFail($id);

        // Get available reviewers for assignment - FIXED LINE
        $reviewers = Reviewer::where('status', 'active')->get();

        return view('admin.applications.show', compact('application', 'reviewers'));
    }

    /**
     * Update application status
     */
    public function updateStatus(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,under_review,shortlisted,rejected',
            'reviewer_notes' => 'nullable|string',
            'rejection_reason' => 'required_if:status,rejected|nullable|string',
            'application_score' => 'nullable|integer|min:0|max:100',
        ]);

        // Update status-specific timestamps
        if ($validated['status'] == 'under_review') {
            $validated['reviewed_at'] = now();
        } elseif ($validated['status'] == 'shortlisted') {
            $validated['shortlisted_at'] = now();
        } elseif ($validated['status'] == 'rejected') {
            $validated['rejected_at'] = now();
        }

        $application->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Application status updated successfully!');
    }

    /**
     * Assign reviewer to application
     */
    public function assignReviewer(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $validated = $request->validate([
            'reviewer_id' => 'required|exists:reviewers,id',
        ]);

        $application->update([
            'reviewer_id' => $validated['reviewer_id'],
            'status' => 'under_review',
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Reviewer assigned successfully!');
    }

    /**
     * Bulk update applications
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:applications,id',
            'action' => 'required|in:shortlist,reject,assign_reviewer,delete',
            'reviewer_id' => 'required_if:action,assign_reviewer|exists:reviewers,id',
            'rejection_reason' => 'required_if:action,reject|nullable|string',
        ]);

        $applications = Application::whereIn('id', $validated['application_ids']);

        switch ($validated['action']) {
            case 'shortlist':
                $applications->update([
                    'status' => 'shortlisted',
                    'shortlisted_at' => now(),
                ]);
                $message = 'Applications shortlisted successfully!';
                break;

            case 'reject':
                $applications->update([
                    'status' => 'rejected',
                    'rejected_at' => now(),
                    'rejection_reason' => $validated['rejection_reason'],
                ]);
                $message = 'Applications rejected successfully!';
                break;

            case 'assign_reviewer':
                $applications->update([
                    'reviewer_id' => $validated['reviewer_id'],
                    'status' => 'under_review',
                    'reviewed_at' => now(),
                ]);
                $message = 'Reviewer assigned successfully!';
                break;

            case 'delete':
                $applications->delete();
                $message = 'Applications deleted successfully!';
                break;
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }

    /**
     * Download application resume
     */
    public function downloadResume($id)
    {
        $application = Application::findOrFail($id);

        if (!$application->resume_path || !Storage::exists($application->resume_path)) {
            return redirect()
                ->back()
                ->with('error', 'Resume not found!');
        }

        return Storage::download($application->resume_path);
    }

    /**
     * Export applications
     */
    public function export(Request $request)
    {
        // This will be implemented with Excel export functionality
        // For now, return a simple CSV

        $applications = Application::with(['jobPosting', 'candidate.user'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->job_posting_id, fn($q) => $q->where('job_posting_id', $request->job_posting_id))
            ->get();

        $filename = 'applications_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($applications) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Application ID',
                'Job Advertisement No.',
                'Position',
                'Candidate Name',
                'Email',
                'Phone',
                'Status',
                'Applied Date',
                'Reviewer',
                'Score'
            ]);

            // Data rows
            foreach ($applications as $app) {
                fputcsv($file, [
                    $app->id,
                    $app->jobPosting->advertisement_no,
                    $app->jobPosting->position_level,
                    $app->candidate->user->name ?? 'N/A',
                    $app->candidate->user->email ?? 'N/A',
                    $app->candidate->phone ?? 'N/A',
                    $app->status,
                    $app->created_at->format('Y-m-d'),
                    $app->reviewer->name ?? 'Unassigned',
                    $app->application_score ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}