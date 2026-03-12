<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\Reviewer;
use App\Models\JobPosting;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminApplicationController extends Controller
{
    public function index(Request $request)
    {
        // Initialize query
        $query = ApplicationForm::with(['candidate', 'jobPosting', 'reviewer'])
            ->where('status', '!=', 'draft');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('candidate', function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('jobPosting', function ($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%")
                        ->orWhere('advertisement_no', 'like', "%{$search}%");
                });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Job filter
        if ($request->filled('job_id')) {
            $query->where('job_posting_id', $request->job_id);
        }

        // Reviewer filter
        if ($request->filled('reviewer_id')) {
            $query->where('reviewer_id', $request->reviewer_id);
        }

        // Date filters
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

        // Paginate results
        $applications = $query->paginate(20)->withQueryString();

        // Get all jobs for filter dropdown
        $jobs = JobPosting::select('id', 'title', 'advertisement_no')->get();

        // Get all active reviewers for filter dropdown
        $reviewers = Reviewer::select('id', 'name', 'email')
            ->where('status', 'active')
            ->get();

        // Status options (Admin Portal handles only initial review: pending, approved, rejected)
        $statuses = ['pending', 'approved', 'rejected'];

        // Calculate statistics
        $stats = [
            'total' => ApplicationForm::where('status', '!=', 'draft')->count(),
            'pending' => ApplicationForm::where('status', 'pending')->count(),
            'approved' => ApplicationForm::where('status', 'approved')->count(),
            'rejected' => ApplicationForm::where('status', 'rejected')->count(),
        ];

        // Return view with all variables
        return view('admin.applications.index', compact(
            'applications',
            'jobs',
            'reviewers',
            'statuses',
            'stats'
        ));
    }

    public function export(Request $request)
    {
        // Use same filtering logic as index method
        $query = ApplicationForm::with(['candidate', 'jobPosting', 'reviewer'])
            ->where('status', '!=', 'draft');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('candidate', function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('jobPosting', function ($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%")
                        ->orWhere('advertisement_no', 'like', "%{$search}%");
                });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Job filter
        if ($request->filled('job_id')) {
            $query->where('job_posting_id', $request->job_id);
        }

        // Reviewer filter
        if ($request->filled('reviewer_id')) {
            $query->where('reviewer_id', $request->reviewer_id);
        }

        // Date filters
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

        // Get all matching applications (no pagination for export)
        $applications = $query->get();

        // Generate CSV
        $filename = 'applications_export_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, [
                'Application ID',
                'Candidate Name',
                'Email',
                'Phone',
                'Job Position',
                'Advertisement No',
                'Status',
                'Priority',
                'Reviewer',
                'Applied Date',
                'Reviewed Date',
                'Admin Notes'
            ]);

            // CSV Data Rows
            foreach ($applications as $application) {
                fputcsv($file, [
                    $application->id,
                    $application->candidate ? ($application->candidate->first_name . ' ' . $application->candidate->last_name) : 'N/A',
                    $application->candidate->email ?? 'N/A',
                    $application->candidate->phone ?? 'N/A',
                    $application->jobPosting->title ?? 'N/A',
                    $application->jobPosting->advertisement_no ?? 'N/A',
                    ucfirst($application->status),
                    $application->manual_priority ? ucfirst($application->manual_priority) : 'Auto',
                    $application->reviewer->name ?? 'Not Assigned',
                    $application->created_at ? $application->created_at->format('Y-m-d H:i:s') : 'N/A',
                    $application->reviewed_at ? $application->reviewed_at->format('Y-m-d H:i:s') : 'Not Reviewed',
                    $application->admin_notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show(ApplicationForm $application)
    {
        $application->load(['candidate', 'jobPosting', 'reviewer']);

        $reviewers = Reviewer::where('status', 'active')->get();
        $statuses = ['pending', 'approved', 'rejected'];

        return view('admin.applications.show', compact(
            'application',
            'reviewers',
            'statuses'
        ));
    }

    public function updateStatus(Request $request, ApplicationForm $application)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Application status updated successfully!');
    }

    public function assignReviewer(Request $request, ApplicationForm $application)
    {
        $request->validate([
            'reviewer_id' => 'required|exists:reviewers,id'
        ]);

        $application->update([
            'reviewer_id' => $request->reviewer_id,
            'status' => 'assigned'
        ]);

        return redirect()->back()->with('success', 'Reviewer assigned successfully!');
    }

    public function setPriority(Request $request, ApplicationForm $application)
    {
        $request->validate([
            'manual_priority' => 'required|in:critical,high,medium,low,normal',
            'priority_note' => 'nullable|string|max:500'
        ]);

        $application->update([
            'manual_priority' => $request->manual_priority,
            'priority_note' => $request->priority_note
        ]);

        return redirect()->back()->with('success', 'Priority set successfully!');
    }

    public function destroy(ApplicationForm $application)
    {
        $application->delete();

        return redirect()->route('admin.applications.index')
            ->with('success', 'Application deleted successfully!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,update_status,assign_reviewer,set_priority',
            'application_ids' => 'required|array|min:1',
            'application_ids.*' => 'exists:application_form,id',
            'status' => 'required_if:action,update_status|in:pending,approved,rejected',
            'reviewer_id' => 'required_if:action,assign_reviewer|exists:reviewers,id',
            'manual_priority' => 'required_if:action,set_priority|nullable|in:critical,high,medium,low,normal',
            'priority_note' => 'nullable|string|max:500'
        ], [
            'application_ids.required' => 'Please select at least one application.',
            'application_ids.min' => 'Please select at least one application.',
            'reviewer_id.required_if' => 'Please select a reviewer.',
            'manual_priority.required_if' => 'Please select a priority level.',
        ]);

        $applicationIds = $request->application_ids;

        switch ($request->action) {
            case 'delete':
                ApplicationForm::whereIn('id', $applicationIds)->delete();
                $message = 'Selected applications deleted successfully!';
                break;

            case 'update_status':
                ApplicationForm::whereIn('id', $applicationIds)->update([
                    'status' => $request->status,
                    'reviewed_at' => now(),
                ]);
                $message = 'Status updated for selected applications!';
                break;

            case 'assign_reviewer':
                $updateData = [
                    'reviewer_id' => $request->reviewer_id,
                    'status' => 'assigned'
                ];

                // Optionally set priority during assignment
                if ($request->filled('manual_priority')) {
                    $updateData['manual_priority'] = $request->manual_priority;
                    $updateData['priority_note'] = $request->priority_note;
                }

                ApplicationForm::whereIn('id', $applicationIds)->update($updateData);

                $message = 'Reviewer assigned to selected applications!';
                if ($request->filled('manual_priority')) {
                    $message .= ' Priority set to ' . ucfirst($request->manual_priority) . '.';
                }
                break;

            case 'set_priority':
                ApplicationForm::whereIn('id', $applicationIds)->update([
                    'manual_priority' => $request->manual_priority,
                    'priority_note' => $request->priority_note
                ]);
                $message = 'Priority set for selected applications!';
                break;

            default:
                $message = 'Invalid action';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Reset payment for an application (Admin only - for testing/fixing issues)
     */
    public function resetPayment(ApplicationForm $application)
    {
        // Delete all payment records for this application
        Payment::where('draft_id', $application->id)->delete();

        // Reset application status back to draft
        $application->update([
            'status' => 'draft',
            'submitted_at' => null,
        ]);

        return redirect()
            ->route('admin.applications.show', $application->id)
            ->with('success', 'Payment has been reset successfully. Application status set back to draft.');
    }
}
