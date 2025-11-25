<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicationReviewController extends Controller
{
    /**
     * Display a listing of applications with filters
     */
    public function index(Request $request)
    {
        $reviewer = Auth::guard('reviewer')->user();
        
        // Start query
        $query = Application::with(['candidate', 'job']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('candidate', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('job', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default to pending and under_review
            $query->whereIn('status', ['pending', 'under_review']);
        }

        // Priority filter (based on deadline)
        if ($request->filled('priority')) {
            $priority = $request->priority;
            $query->whereHas('job', function($q) use ($priority) {
                $now = now();
                switch($priority) {
                    case 'high':
                        $q->whereBetween('deadline', [$now, $now->copy()->addDays(2)]);
                        break;
                    case 'medium':
                        $q->whereBetween('deadline', [$now->copy()->addDays(2), $now->copy()->addDays(5)]);
                        break;
                    case 'low':
                        $q->whereBetween('deadline', [$now->copy()->addDays(5), $now->copy()->addDays(10)]);
                        break;
                    case 'normal':
                        $q->where('deadline', '>', $now->copy()->addDays(10));
                        break;
                }
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Job filter
        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'deadline') {
            $query->join('job_postings', 'applications.job_posting_id', '=', 'job_postings.id')
                  ->orderBy('job_postings.deadline', $sortOrder)
                  ->select('applications.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Paginate
        $applications = $query->paginate(15)->withQueryString();

        // Get all jobs for filter dropdown
        $jobs = Job::orderBy('title')->get();

        // Statistics
        $stats = [
            'total' => Application::count(),
            'pending' => Application::whereIn('status', ['pending', 'under_review'])->count(),
            'shortlisted' => Application::where('status', 'shortlisted')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
        ];

        return view('reviewer.applications.index', compact('applications', 'jobs', 'stats'));
    }

    /**
     * Show application details for review
     */
    public function show($id)
    {
        $application = Application::with(['candidate', 'job', 'reviewer'])
            ->findOrFail($id);

        return view('reviewer.applications.show', compact('application'));
    }

    /**
     * Get application details for modal (AJAX)
     */
    public function getDetails($id)
    {
        $application = Application::with(['candidate', 'job', 'reviewer'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'application' => [
                'id' => $application->id,
                'candidate_name' => $application->candidate->name,
                'candidate_email' => $application->candidate->email,
                'candidate_phone' => $application->candidate->phone,
                'candidate_address' => $application->candidate->address,
                'job_title' => $application->job->title,
                'job_department' => $application->job->department,
                'job_location' => $application->job->location,
                'job_type' => $application->job->job_type,
                'salary_range' => $application->job->salary_min && $application->job->salary_max 
                    ? '$' . number_format($application->job->salary_min) . ' - $' . number_format($application->job->salary_max)
                    : 'Not specified',
                'cover_letter' => $application->cover_letter,
                'resume' => $application->candidate->resume,
                'status' => $application->status,
                'applied_at' => $application->created_at->format('M d, Y h:i A'),
                'reviewer_notes' => $application->reviewer_notes,
                'reviewed_by' => $application->reviewer ? $application->reviewer->name : null,
                'reviewed_at' => $application->reviewed_at ? $application->reviewed_at->format('M d, Y h:i A') : null,
            ]
        ]);
    }

    /**
     * Update application status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:under_review,shortlisted,rejected,accepted',
            'reviewer_notes' => 'nullable|string|max:1000',
        ]);

        $application = Application::findOrFail($id);
        $reviewer = Auth::guard('reviewer')->user();

        $application->update([
            'status' => $request->status,
            'reviewer_notes' => $request->reviewer_notes,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application status updated successfully!',
            'application' => $application
        ]);
    }

    /**
     * Bulk update applications
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:applications,id',
            'status' => 'required|in:under_review,shortlisted,rejected',
        ]);

        $reviewer = Auth::guard('reviewer')->user();

        Application::whereIn('id', $request->application_ids)
            ->update([
                'status' => $request->status,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => count($request->application_ids) . ' applications updated successfully!',
        ]);
    }

    /**
     * Export applications to Excel
     */
    public function export(Request $request)
    {
        // This will be implemented later with Laravel Excel
        return response()->json([
            'success' => false,
            'message' => 'Export feature coming soon!'
        ]);
    }
}