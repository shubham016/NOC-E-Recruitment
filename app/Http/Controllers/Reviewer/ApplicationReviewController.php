<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ApplicationReviewController extends Controller
{
    /**
     * Display a listing of applications with filters
     */
    public function index(Request $request)
    {
        $reviewer = Auth::guard('reviewer')->user();

        // Start query - only show applications assigned to this reviewer
        $query = ApplicationForm::with(['jobPosting'])
            ->where('reviewer_id', $reviewer->id)
            ->where('status', '!=', 'draft');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_english', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('jobPosting', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        // If status is "all" or "all status", show everything (no extra filter)
        if ($request->filled('status') && !in_array($request->status, ['all', 'all status'])) {
            $query->where('status', $request->status);
        } else {
            // Default to pending + assigned only IF no status was chosen at all
            if (!$request->filled('status')) {
                $query->whereIn('status', ['pending', 'assigned']);
            }
        }

        // Priority filter (based on deadline)
        if ($request->filled('priority')) {
            $priority = $request->priority;
            $query->whereHas('jobPosting', function ($q) use ($priority) {
                $now = now();

                switch ($priority) {
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

            'assigned' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'assigned')
                ->count(),

            'reviewed' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'reviewed')
                ->count(),

            'approved' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'approved')
                ->count(),

            'rejected' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'rejected')
                ->count(),

            'edit' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'edit')
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
        $application = ApplicationForm::with(['jobPosting', 'reviewer'])
            ->where('reviewer_id', $reviewer->id)
            ->findOrFail($id);

        // Get statistics for sidebar
        $stats = [
            'pending' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'pending')
                ->count(),

            'assigned' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'assigned')
                ->count(),

            'reviewed' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'reviewed')
                ->count(),

            'approved' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'approved')
                ->count(),

            'rejected' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'rejected')
                ->count(),

            'edit' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'edit')
                ->count(),
        ];

        // Get all active approvers for assignment dropdown
        $approvers = \App\Models\Approver::where('status', 'active')->get();

        return view('reviewer.applications.show', compact('application', 'stats', 'approvers'));
    }

    /**
     * Get application details for modal (AJAX)
     */
    public function getDetails($id)
    {
        $reviewer = Auth::guard('reviewer')->user();

        // Only show applications assigned to this reviewer
        $application = ApplicationForm::with(['jobPosting', 'reviewer'])
            ->where('reviewer_id', $reviewer->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'application' => [
                'id' => $application->id,
                'candidate_name' => $application->name_english,
                'candidate_email' => $application->email,
                'candidate_phone' => $application->phone,
                'job_title' => $application->jobPosting->title ?? 'N/A',
                'job_department' => $application->jobPosting->department ?? 'N/A',
                'job_location' => $application->jobPosting->location ?? 'N/A',
                'job_type' => $application->jobPosting->job_type ?? 'N/A',
                'salary_range' => ($application->jobPosting && $application->jobPosting->salary_min && $application->jobPosting->salary_max)
                    ? 'Rs. ' . number_format($application->jobPosting->salary_min) . ' - Rs. ' . number_format($application->jobPosting->salary_max)
                    : 'Not specified',
                'cover_letter' => $application->cover_letter,
                'resume' => $application->resume,
                'status' => $application->status,
                'applied_at' => $application->created_at ? $application->created_at->format('M d, Y h:i A') : 'N/A',
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
            'status' => 'required|in:reviewed,rejected,edit',
            'reviewer_notes' => 'required|string|max:1000',
            'approver_id' => 'required_if:status,reviewed|nullable|exists:approvers,id',
        ], [
            'approver_id.required_if' => 'Please select an approver when marking application as reviewed.',
            'approver_id.exists' => 'The selected approver is invalid.',
        ]);

        $reviewer = Auth::guard('reviewer')->user();

        // Only allow updating applications assigned to this reviewer
        $application = ApplicationForm::with('vacancy')
            ->where('reviewer_id', $reviewer->id)
            ->findOrFail($id);

        $updateData = [
            'status' => $request->status,
            'reviewer_notes' => $request->reviewer_notes,
            'reviewer_id' => $reviewer->id,
            'reviewed_at' => now(),
        ];

        // If marking as reviewed and approver is selected, assign to approver
        if ($request->status === 'reviewed' && $request->approver_id) {
            $updateData['approver_id'] = $request->approver_id;
        }

        $application->update($updateData);

        // Create notification for approver when status is 'reviewed'
        if ($request->status === 'reviewed' && $request->approver_id) {
            $approver = \App\Models\Approver::find($request->approver_id);

            \App\Models\Notification::create([
                'user_id' => $approver->id,
                'user_type' => 'approver',
                'type' => 'application_reviewed',
                'title' => 'Application Ready for Approval',
                'message' => 'An application for "' . ($application->vacancy->title ?? 'N/A') . '" has been reviewed and is ready for your final approval.',
                'related_id' => $application->id,
                'related_type' => 'application',
            ]);
        }

        // Create notification for candidate when sent back for editing
        if ($request->status === 'edit') {
            $candidate = $application->candidate;
            if ($candidate) {
                \App\Models\Notification::create([
                    'user_id' => $candidate->id,
                    'user_type' => 'candidate',
                    'type' => 'application_edit_request',
                    'title' => 'Application Requires Editing',
                    'message' => 'Your application for "' . ($application->vacancy->title ?? 'N/A') . '" has been sent back for corrections by the reviewer. Please review the notes and resubmit.',
                    'related_id' => $application->id,
                    'related_type' => 'application',
                ]);
            }
        }

        // Prepare response message based on status
        if ($request->status === 'reviewed') {
            $approverName = $approver->name ?? 'N/A';
            $message = 'Application reviewed and assigned to Approver: ' . $approverName . ' for final decision.';
        } elseif ($request->status === 'edit') {
            $message = 'Application sent back to candidate for correction successfully!';
        } else {
            $message = 'Application rejected successfully! Candidate will be notified.';
        }

        // If request is AJAX / fetch, return JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'application' => $application
            ]);
        }

        // Normal form submit -> stay on same page
        return redirect()->back()->with('success', $message);
    }

    /**
     * Bulk update applications
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:application_form,id',
            'status' => 'required|in:reviewed,rejected,edit',
        ]);

        $reviewer = Auth::guard('reviewer')->user();

        // Only update applications assigned to this reviewer
        $updatedCount = ApplicationForm::whereIn('id', $request->application_ids)
            ->where('reviewer_id', $reviewer->id)
            ->update([
                'status' => $request->status,
                'reviewer_id' => $reviewer->id,
                'reviewed_at' => now(),
            ]);

        if ($updatedCount === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No applications were updated. Please make sure items are selected.',
            ], 422);
        }

        // Prepare response message
        if ($request->status === 'reviewed') {
            $message = $updatedCount . ' applications marked as reviewed and sent to Approver Portal!';
        } elseif ($request->status === 'edit') {
            $message = $updatedCount . ' applications sent back to candidates for correction!';
        } else {
<<<<<<< HEAD
            $message = $updatedCount . ' applications rejected! Candidates will be notified.';
=======
            $message = $updatedCount . ' applications rejected! Candidates will be notified via SMS when integrated.';
>>>>>>> 55e8c2322fd9818955a408f1f667542e5cee9f98
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Export applications to CSV
     */
    public function exportCsv(Request $request)
    {
        $reviewer = Auth::guard('reviewer')->user();

        // Build query - only for this reviewer's applications
        $query = ApplicationForm::with(['jobPosting'])
            ->where('reviewer_id', $reviewer->id)
            ->where('status', '!=', 'draft');

        // If specific IDs are provided (bulk export), use only those
        if ($request->filled('ids')) {
            $ids = is_array($request->input('ids')) ? $request->input('ids') : explode(',', $request->input('ids'));
            $query->whereIn('id', $ids);
        } else {
            // Otherwise apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name_english', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('jobPosting', function ($q) use ($search) {
                            $q->where('title', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('status') && !in_array($request->status, ['all', 'all status'])) {
                $query->where('status', $request->status);
            }

            if ($request->filled('job_id')) {
                $query->where('job_posting_id', $request->job_id);
            }
        }

        $applications = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $filename = 'applications_export_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($applications) {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Add headers
            fputcsv($file, [
                'ID',
                'Candidate Name',
                'Email',
                'Phone',
                'Position',
                'Department',
                'Status',
                'Priority',
                'Applied Date',
                'Deadline',
                'Days Remaining',
                'Reviewed At',
                'Reviewer Notes'
            ]);

            // Add data
            foreach ($applications as $application) {
                $daysRemaining = ($application->jobPosting && $application->jobPosting->deadline)
                    ? (int) now()->diffInDays($application->jobPosting->deadline, false)
                    : 0;

                $priority = $application->manual_priority
                    ? ucfirst($application->manual_priority)
                    : ($daysRemaining <= 2 ? 'High' : ($daysRemaining <= 5 ? 'Medium' : ($daysRemaining <= 10 ? 'Low' : 'Normal')));

                fputcsv($file, [
                    $application->id,
                    $application->name_english ?? 'N/A',
                    $application->email ?? 'N/A',
                    $application->phone ?? 'N/A',
                    $application->jobPosting->title ?? 'N/A',
                    $application->jobPosting->department ?? 'N/A',
                    ucfirst($application->status),
                    $priority,
                    $application->submitted_at ? $application->submitted_at->format('Y-m-d H:i') : 'N/A',
                    ($application->jobPosting && $application->jobPosting->deadline) ? $application->jobPosting->deadline->format('Y-m-d') : 'N/A',
                    $daysRemaining . ' days',
                    $application->reviewed_at ? $application->reviewed_at->format('Y-m-d H:i') : 'Not Reviewed',
                    $application->reviewer_notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export applications to PDF
     */
    public function exportPdf(Request $request)
    {
        $reviewer = Auth::guard('reviewer')->user();

        // Build query - only for this reviewer's applications
        $query = ApplicationForm::with(['jobPosting'])
            ->where('reviewer_id', $reviewer->id)
            ->where('status', '!=', 'draft');

        // If specific IDs are provided (bulk export), use only those
        if ($request->filled('ids')) {
            $ids = is_array($request->input('ids')) ? $request->input('ids') : explode(',', $request->input('ids'));
            $query->whereIn('id', $ids);
        } else {
            // Otherwise apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name_english', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('jobPosting', function ($q) use ($search) {
                            $q->where('title', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('status') && !in_array($request->status, ['all', 'all status'])) {
                $query->where('status', $request->status);
            }

            if ($request->filled('job_id')) {
                $query->where('job_posting_id', $request->job_id);
            }
        }

        $applications = $query->orderBy('created_at', 'desc')->get();

        // Generate stats
        $stats = [
            'total' => $applications->count(),
            'pending' => $applications->where('status', 'pending')->count(),
            'assigned' => $applications->where('status', 'assigned')->count(),
            'reviewed' => $applications->where('status', 'reviewed')->count(),
            'approved' => $applications->where('status', 'approved')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
            'edit' => $applications->where('status', 'edit')->count(),
        ];

        $pdf = Pdf::loadView('reviewer.applications.pdf', compact('applications', 'stats', 'reviewer'));

        $filename = 'applications_export_' . now()->format('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }
}