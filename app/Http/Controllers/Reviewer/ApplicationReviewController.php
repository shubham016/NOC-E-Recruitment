<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\Vacancy;
use App\Models\Notification;
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
        $query = ApplicationForm::with(['candidate', 'vacancy'])
            ->where('reviewer_id', $reviewer->id)
            ->where('status', '!=', 'draft');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('candidate', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('vacancy', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default to pending and assigned (under review)
            $query->whereIn('status', ['pending', 'assigned']);
        }

        // Priority filter (based on deadline)
        if ($request->filled('priority')) {
            $priority = $request->priority;
            $query->whereHas('vacancy', function ($q) use ($priority) {
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

        // Vacancy filter
        if ($request->filled('job_id')) {
            $query->where('vacancy_id', $request->job_id);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'deadline') {
            $query->join('vacancies', 'application_form.vacancy_id', '=', 'vacancies.id')
                ->orderBy('vacancies.deadline', $sortOrder)
                ->select('application_form.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Paginate
        $applications = $query->paginate(15)->withQueryString();

        // Get all vacancies for filter dropdown
        $vacancies = Vacancy::orderBy('title')->get();

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

        return view('reviewer.applications.index', compact('applications', 'vacancies', 'stats'));
    }

    /**
     * Show application details for review
     */
    public function show($id)
    {
        $reviewer = Auth::guard('reviewer')->user();

        // Only show applications assigned to this reviewer
        $application = ApplicationForm::with(['candidate', 'vacancy', 'reviewer'])
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

        return view('reviewer.applications.show', compact('application', 'stats'));
    }

    /**
     * Get application details for modal (AJAX)
     */
    public function getDetails($id)
    {
        $reviewer = Auth::guard('reviewer')->user();

        // Only show applications assigned to this reviewer
        $application = ApplicationForm::with(['candidate', 'vacancy', 'reviewer'])
            ->where('reviewer_id', $reviewer->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'application' => [
                'id' => $application->id,
                'candidate_name' => $application->candidate->name,
                'candidate_email' => $application->candidate->email,
                'candidate_phone' => $application->phone ?? $application->candidate->mobile_number,
                'job_title' => $application->vacancy->title,
                'job_department' => $application->vacancy->department,
                'job_location' => $application->vacancy->location,
                'job_type' => $application->vacancy->job_type,
                'salary_range' => $application->vacancy->salary_min && $application->vacancy->salary_max
                    ? 'Rs. ' . number_format($application->vacancy->salary_min) . ' - Rs. ' . number_format($application->vacancy->salary_max)
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
            'status' => 'required|in:reviewed,rejected,edit',
            'reviewer_notes' => 'required|string|max:1000',
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

        // Create notifications for candidate based on status
        $notificationMessages = [
            'edit' => [
                'title' => 'Application Requires Correction',
                'message' => 'Your application for "' . $application->vacancy->title . '" has been sent back for correction. Please review the reviewer\'s notes and resubmit.',
                'type' => 'application_sent_back'
            ],
            'reviewed' => [
                'title' => 'Application Under Review',
                'message' => 'Your application for "' . $application->vacancy->title . '" has been reviewed and forwarded to the admin for final decision.',
                'type' => 'application_reviewed'
            ],
            'rejected' => [
                'title' => 'Application Rejected',
                'message' => 'Your application for "' . $application->vacancy->title . '" has been rejected by the reviewer. Please check the reviewer\'s notes for more details.',
                'type' => 'application_rejected'
            ],
        ];

        if (isset($notificationMessages[$request->status])) {
            Notification::create([
                'user_id' => $application->candidate_id,
                'user_type' => 'candidate',
                'type' => $notificationMessages[$request->status]['type'],
                'title' => $notificationMessages[$request->status]['title'],
                'message' => $notificationMessages[$request->status]['message'],
                'related_id' => $application->id,
                'related_type' => 'application',
            ]);
        }

        // Prepare response message based on status
        if ($request->status === 'reviewed') {
            $message = 'Application reviewed successfully! It will now be sent to the Approver Portal for final decision.';
        } elseif ($request->status === 'edit') {
            $message = 'Application sent back to candidate for correction successfully!';
        } else {
            $message = 'Application rejected successfully! Candidate will be notified via SMS when Sparrow SMS is integrated.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
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
            'status' => 'required|in:reviewed,rejected,edit',
        ]);

        $reviewer = Auth::guard('reviewer')->user();

        // Get applications before updating them (needed for notifications)
        $applications = ApplicationForm::whereIn('id', $request->application_ids)
            ->where('reviewer_id', $reviewer->id)
            ->get();

        // Only update applications assigned to this reviewer
        ApplicationForm::whereIn('id', $request->application_ids)
            ->where('reviewer_id', $reviewer->id)
            ->update([
                'status' => $request->status,
                'reviewer_id' => $reviewer->id,
                'reviewed_at' => now(),
            ]);

        // Create notifications for candidates based on status
        $notificationMessages = [
            'edit' => [
                'title' => 'Application Requires Correction',
                'message' => 'Your application for "' . '{job_title}' . '" has been sent back for correction. Please review the reviewer\'s notes and resubmit.',
                'type' => 'application_sent_back'
            ],
            'reviewed' => [
                'title' => 'Application Under Review',
                'message' => 'Your application for "' . '{job_title}' . '" has been reviewed and forwarded to the admin for final decision.',
                'type' => 'application_reviewed'
            ],
            'rejected' => [
                'title' => 'Application Rejected',
                'message' => 'Your application for "' . '{job_title}' . '" has been rejected by the reviewer. Please check the reviewer\'s notes for more details.',
                'type' => 'application_rejected'
            ],
        ];

        if (isset($notificationMessages[$request->status])) {
            foreach ($applications as $application) {
                $message = str_replace('{job_title}', $application->vacancy->title, $notificationMessages[$request->status]['message']);

                Notification::create([
                    'user_id' => $application->candidate_id,
                    'user_type' => 'candidate',
                    'type' => $notificationMessages[$request->status]['type'],
                    'title' => $notificationMessages[$request->status]['title'],
                    'message' => $message,
                    'related_id' => $application->id,
                    'related_type' => 'application',
                ]);
            }
        }

        // Prepare response message
        $count = count($request->application_ids);

        if ($request->status === 'reviewed') {
            $message = $count . ' applications marked as reviewed and sent to Approver Portal!';
        } elseif ($request->status === 'edit') {
            $message = $count . ' applications sent back to candidates for correction!';
        } else {
            $message = $count . ' applications rejected! Candidates will be notified via SMS when integrated.';
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
        $query = ApplicationForm::with(['candidate', 'vacancy'])
            ->where('reviewer_id', $reviewer->id)
            ->where('status', '!=', 'draft');

        // If specific IDs are provided (bulk export), use only those
        if ($request->filled('ids')) {
            $ids = $request->input('ids', []);
            $query->whereIn('id', $ids);
        } else {
            // Otherwise apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name_english', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('vacancy', function ($q) use ($search) {
                            $q->where('title', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('job_id')) {
                $query->where('vacancy_id', $request->job_id);
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
                $daysRemaining = $application->vacancy
                    ? (int) now()->diffInDays($application->vacancy->deadline, false)
                    : 0;

                $priority = $application->manual_priority
                    ? ucfirst($application->manual_priority)
                    : ($daysRemaining <= 2 ? 'High' : ($daysRemaining <= 5 ? 'Medium' : ($daysRemaining <= 10 ? 'Low' : 'Normal')));

                fputcsv($file, [
                    $application->id,
                    $application->name_english ?? 'N/A',
                    $application->email ?? 'N/A',
                    $application->phone ?? 'N/A',
                    $application->vacancy->title ?? 'N/A',
                    $application->vacancy->department ?? 'N/A',
                    ucfirst($application->status),
                    $priority,
                    $application->submitted_at ? $application->submitted_at->format('Y-m-d H:i') : 'N/A',
                    $application->vacancy && $application->vacancy->deadline ? $application->vacancy->deadline->format('Y-m-d') : 'N/A',
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
        $query = ApplicationForm::with(['candidate', 'vacancy'])
            ->where('reviewer_id', $reviewer->id)
            ->where('status', '!=', 'draft');

        // If specific IDs are provided (bulk export), use only those
        if ($request->filled('ids')) {
            $ids = $request->input('ids', []);
            $query->whereIn('id', $ids);
        } else {
            // Otherwise apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name_english', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('vacancy', function ($q) use ($search) {
                            $q->where('title', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('job_id')) {
                $query->where('vacancy_id', $request->job_id);
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