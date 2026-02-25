<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\JobPosting;
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

        // Start query - only show applications assigned to this reviewer
        $query = ApplicationForm::with(['candidate', 'jobPosting'])
            ->where('reviewer_id', $reviewer->id)
            ->where('status', '!=', 'draft');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('candidate', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('jobPosting', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default to pending and approved (under review)
            $query->whereIn('status', ['pending', 'approved']);
        }

        // Priority filter (based on deadline)
        if ($request->filled('priority')) {
            $priority = $request->priority;
            $query->whereHas('jobPosting', function($q) use ($priority) {
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
            $query->where('job_posting_id', $request->job_id);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'deadline') {
            $query->join('job_postings', 'application_form.job_posting_id', '=', 'job_postings.id')
                  ->orderBy('job_postings.deadline', $sortOrder)
                  ->select('application_form.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Paginate
        $applications = $query->paginate(15)->withQueryString();

        // Get all jobs for filter dropdown
        $jobs = JobPosting::orderBy('title')->get();

        // Statistics - only for this reviewer's assigned applications
        $stats = [
            'total' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', '!=', 'draft')
                ->count(),
            'pending' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'pending')
                ->count(),
            'approved' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'approved')
                ->count(),
            'rejected' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'rejected')
                ->count(),
        ];

        return view('reviewer.applications.index', compact('applications', 'jobs', 'stats'));
    }

    /**
     * Show application details for review
     */
    public function show($id)
    {
        $reviewer = Auth::guard('reviewer')->user();

        // Only show applications assigned to this reviewer
        $application = ApplicationForm::with(['candidate', 'jobPosting', 'reviewer'])
            ->where('reviewer_id', $reviewer->id)
            ->findOrFail($id);

        return view('reviewer.applications.show', compact('application'));
    }

    /**
     * Get application details for modal (AJAX)
     */
    public function getDetails($id)
    {
        $reviewer = Auth::guard('reviewer')->user();

        // Only show applications assigned to this reviewer
        $application = ApplicationForm::with(['candidate', 'jobPosting', 'reviewer'])
            ->where('reviewer_id', $reviewer->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'application' => [
                'id' => $application->id,
                'candidate_name' => $application->candidate->name,
                'candidate_email' => $application->candidate->email,
                'candidate_phone' => $application->phone ?? $application->candidate->mobile_number,
                'job_title' => $application->jobPosting->title,
                'job_department' => $application->jobPosting->department,
                'job_location' => $application->jobPosting->location,
                'job_type' => $application->jobPosting->job_type,
                'salary_range' => $application->jobPosting->salary_min && $application->jobPosting->salary_max
                    ? 'Rs. ' . number_format($application->jobPosting->salary_min) . ' - Rs. ' . number_format($application->jobPosting->salary_max)
                    : 'Not specified',
                'cover_letter' => $application->cover_letter,
                'resume' => $application->resume,
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
            'status' => 'required|in:pending,approved,rejected',
            'reviewer_notes' => 'nullable|string|max:1000',
        ]);

        $reviewer = Auth::guard('reviewer')->user();

        // Only allow updating applications assigned to this reviewer
        $application = ApplicationForm::where('reviewer_id', $reviewer->id)
            ->findOrFail($id);

        $application->update([
            'status' => $request->status,
            'reviewer_notes' => $request->reviewer_notes,
            'reviewer_id' => $reviewer->id,
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
            'application_ids.*' => 'exists:application_form,id',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $reviewer = Auth::guard('reviewer')->user();

        // Only update applications assigned to this reviewer
        ApplicationForm::whereIn('id', $request->application_ids)
            ->where('reviewer_id', $reviewer->id)
            ->update([
                'status' => $request->status,
                'reviewer_id' => $reviewer->id,
                'reviewed_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => count($request->application_ids) . ' applications updated successfully!',
        ]);
    }
}
