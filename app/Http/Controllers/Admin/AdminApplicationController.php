<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\Reviewer;
use App\Models\Approver;
use App\Models\JobPosting;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminApplicationController extends Controller
{
    public function index(Request $request)
    {
        // Initialize query
        $query = ApplicationForm::with(['vacancy', 'reviewer', 'approver'])
            ->where('status', '!=', 'draft');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_english', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('vacancy', function ($q2) use ($search) {
                        $q2->where('title', 'like', "%{$search}%")
                            ->orWhere('advertisement_no', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Vacancy filter
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

        // Get all jobs for filter dropdown, with application count per vacancy
        $jobs = JobPosting::select('id', 'title', 'advertisement_no', 'level')
            ->withCount(['applications' => function ($q) {
                $q->where('status', '!=', 'draft');
            }])
            ->get();
        $vacancies = $jobs;

        // Get all active reviewers for filter dropdown
        $reviewers = Reviewer::select('id', 'name', 'email')
            ->where('status', 'active')
            ->get();

        // Get all active approvers for filter dropdown
        $approvers = Approver::select('id', 'name', 'email')
            ->where('status', 'active')
            ->get();

        // Status options (Super Admin has ALL access)
        $statuses = ['pending', 'assigned', 'reviewed', 'edit', 'approved', 'rejected'];

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
            'vacancies',
            'reviewers',
            'approvers',
            'statuses',
            'stats'
        ));
    }

    public function export(Request $request)
    {
        // Use same filtering logic as index method
        $query = ApplicationForm::with(['vacancy', 'reviewer'])
            ->where('status', '!=', 'draft');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_english', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('vacancy', function ($q2) use ($search) {
                        $q2->where('title', 'like', "%{$search}%")
                            ->orWhere('advertisement_no', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Vacancy filter
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
                'Vacancy Position',
                'Advertisement No',
                'Status',
                'Reviewer',
                'Applied Date',
                'Reviewed Date',
                'Admin Notes'
            ]);

            // CSV Data Rows
            foreach ($applications as $application) {
                fputcsv($file, [
                    $application->id,
                    $application->name_english ?? 'N/A',
                    $application->email ?? 'N/A',
                    $application->phone ?? 'N/A',
                    $application->vacancy->title ?? 'N/A',
                    $application->vacancy->advertisement_no ?? 'N/A',
                    ucfirst($application->status),
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
        $application->load(['vacancy', 'reviewer']);

        $reviewers = Reviewer::where('status', 'active')->get();
        $statuses = ['pending', 'assigned', 'reviewed', 'edit', 'approved', 'rejected'];

        return view('admin.applications.show', compact(
            'application',
            'reviewers',
            'statuses'
        ));
    }

    public function updateStatus(Request $request, ApplicationForm $application)
    {
        $request->validate([
            'status' => 'required|in:pending,assigned,reviewed,edit,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
            'approver_id' => 'required_if:status,reviewed|nullable|exists:approvers,id',
        ], [
            'approver_id.required_if' => 'Please select an approver when marking as reviewed.',
        ]);

        $updateData = [
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
        ];

        // If marking as reviewed, assign to approver
        if ($request->status === 'reviewed' && $request->approver_id) {
            $updateData['approver_id'] = $request->approver_id;
        }

        $application->update($updateData);

        // Create notification for candidate
        $candidate = $application->candidate;

        if ($request->status === 'reviewed' && $request->approver_id) {
            $approver = \App\Models\Approver::find($request->approver_id);

            // Notify the approver
            \App\Models\Notification::create([
                'user_id' => $approver->id,
                'user_type' => 'approver',
                'type' => 'application_assigned',
                'title' => 'New Application Assigned',
                'message' => 'An application has been reviewed by admin and assigned to you for final approval.',
                'related_id' => $application->id,
                'related_type' => 'application',
            ]);

            return redirect()->back()->with('success', 'Application reviewed and assigned to Approver: ' . ($approver->name ?? 'N/A') . ' for final decision.');
        } elseif ($request->status == 'approved') {
            if ($candidate) {
                Notification::create([
                    'user_id' => $candidate->id,
                    'user_type' => 'candidate',
                    'type' => 'application_approved',
                    'title' => 'Application Approved',
                    'message' => 'Congratulations! Your application for "' . $application->vacancy->title . '" has been approved by the admin.',
                    'related_id' => $application->id,
                    'related_type' => 'application',
                ]);
            }
        } elseif ($request->status == 'edit') {
            if ($candidate) {
                $rejectionReason = $request->admin_notes ? ' Reason: ' . $request->admin_notes : '';
                Notification::create([
                    'user_id' => $candidate->id,
                    'user_type' => 'candidate',
                    'type' => 'application_edit_request',
                    'title' => 'Application Requires Editing',
                    'message' => 'Your application for "' . $application->vacancy->title . '" has been sent back for corrections by the admin.' . $rejectionReason,
                    'related_id' => $application->id,
                    'related_type' => 'application',
                ]);
            }
        } elseif ($request->status == 'rejected') {
            if ($candidate) {
                $rejectionReason = $request->admin_notes ? ' Reason: ' . $request->admin_notes : '';
                Notification::create([
                    'user_id' => $candidate->id,
                    'user_type' => 'candidate',
                    'type' => 'application_rejected',
                    'title' => 'Application Rejected',
                    'message' => 'Your application for "' . $application->vacancy->title . '" has been rejected by the admin.' . $rejectionReason,
                    'related_id' => $application->id,
                    'related_type' => 'application',
                ]);
            }
        }

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

        $reviewer = Reviewer::find($request->reviewer_id);

        $positionTitle = $application->applying_position ?? $application->advertisement_no ?? 'this position';

        // Look up candidate ID from candidate_registration via citizenship_number
        $candidateRecord = \DB::table('candidate_registration')
            ->where('citizenship_number', $application->citizenship_number)
            ->first();

        // Create notification for candidate (only if candidate record found)
        if ($candidateRecord) {
            Notification::create([
                'user_id'      => $candidateRecord->id,
                'user_type'    => 'candidate',
                'type'         => 'reviewer_assigned',
                'title'        => 'Reviewer Assigned',
                'message'      => 'Your application for "' . $positionTitle . '" has been assigned to a reviewer for evaluation.',
                'related_id'   => $application->id,
                'related_type' => 'application',
            ]);
        }

        // Create notification for reviewer
        Notification::create([
            'user_id'      => $request->reviewer_id,
            'user_type'    => 'reviewer',
            'type'         => 'application_assigned',
            'title'        => 'New Application Assigned',
            'message'      => 'A new application for "' . $positionTitle . '" has been assigned to you for review.',
            'related_id'   => $application->id,
            'related_type' => 'application',
        ]);

        return redirect()->back()->with('success', 'Reviewer assigned successfully!');
    }

    public function assignApprover(Request $request, ApplicationForm $application)
    {
        $request->validate([
            'approver_id' => 'required|exists:approvers,id'
        ]);

        $application->update([
            'approver_id' => $request->approver_id,
        ]);

        $positionTitle = $application->applying_position ?? $application->advertisement_no ?? 'this position';

        // Notification for approver
        Notification::create([
            'user_id'      => $request->approver_id,
            'user_type'    => 'approver',
            'type'         => 'application_assigned',
            'title'        => 'New Application Assigned',
            'message'      => 'An application for "' . $positionTitle . '" has been assigned to you for final approval.',
            'related_id'   => $application->id,
            'related_type' => 'application',
        ]);

        // Notification for candidate
        $candidateRecord = \DB::table('candidate_registration')
            ->where('citizenship_number', $application->citizenship_number)
            ->first();

        if ($candidateRecord) {
            Notification::create([
                'user_id'      => $candidateRecord->id,
                'user_type'    => 'candidate',
                'type'         => 'approver_assigned',
                'title'        => 'Approver Assigned',
                'message'      => 'Your application for "' . $positionTitle . '" has been assigned to an approver for final decision.',
                'related_id'   => $application->id,
                'related_type' => 'application',
            ]);
        }

        return redirect()->back()->with('success', 'Approver assigned successfully!');
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
            'action'          => 'required|in:update_status,assign_reviewer,assign_approver',
            'application_ids' => 'nullable|array',
            'application_ids.*' => 'exists:application_form,id',
            'job_posting_id'  => 'nullable|exists:job_postings,id',
            'status'          => 'required_if:action,update_status|in:pending,assigned,reviewed,edit,approved,rejected',
            'reviewer_id'     => 'nullable|required_if:action,assign_reviewer|exists:reviewers,id',
            'approver_id'     => 'nullable|required_if:action,assign_approver|exists:approvers,id',
        ], [
            'reviewer_id.required_if' => 'Please select a reviewer.',
            'approver_id.required_if' => 'Please select an approver.',
        ]);

        // Resolve application IDs: by advertisement number (all apps) or by checkbox selection
        if ($request->filled('job_posting_id')) {
            $applicationIds = ApplicationForm::where('job_posting_id', $request->job_posting_id)
                ->where('status', '!=', 'draft')
                ->pluck('id')
                ->toArray();

            if (empty($applicationIds)) {
                return redirect()->back()->with('error', 'No applications found for the selected advertisement number.');
            }
        } else {
            $applicationIds = $request->application_ids ?? [];
            if (empty($applicationIds)) {
                return redirect()->back()->with('error', 'Please select an advertisement number or at least one application.');
            }
        }

        switch ($request->action) {
            case 'update_status':
                ApplicationForm::whereIn('id', $applicationIds)->update([
                    'status' => $request->status,
                    'reviewed_at' => now(),
                ]);
                $message = 'Status updated for ' . count($applicationIds) . ' application(s) successfully!';
                break;

            case 'assign_reviewer':
                ApplicationForm::whereIn('id', $applicationIds)->update([
                    'reviewer_id' => $request->reviewer_id,
                    'status'      => 'assigned',
                ]);

                $reviewer     = Reviewer::find($request->reviewer_id);
                $applications = ApplicationForm::whereIn('id', $applicationIds)->get();

                // One consolidated notification to the reviewer
                Notification::create([
                    'user_id'      => $reviewer->id,
                    'user_type'    => 'reviewer',
                    'type'         => 'application_assigned',
                    'title'        => 'New Applications Assigned',
                    'message'      => count($applicationIds) . ' application(s) have been assigned to you for review by the admin.',
                    'related_id'   => $applications->first()?->id,
                    'related_type' => 'application',
                ]);

                // Per-candidate notification
                foreach ($applications as $app) {
                    $positionTitle = $app->applying_position ?? $app->advertisement_no ?? 'this position';
                    $candidateRecord = \DB::table('candidate_registration')
                        ->where('citizenship_number', $app->citizenship_number)
                        ->first();
                    if ($candidateRecord) {
                        Notification::create([
                            'user_id'      => $candidateRecord->id,
                            'user_type'    => 'candidate',
                            'type'         => 'reviewer_assigned',
                            'title'        => 'Reviewer Assigned',
                            'message'      => 'Your application for "' . $positionTitle . '" has been assigned to a reviewer for evaluation.',
                            'related_id'   => $app->id,
                            'related_type' => 'application',
                        ]);
                    }
                }

                $message = 'Reviewer "' . ($reviewer->name ?? 'N/A') . '" assigned to ' . count($applicationIds) . ' application(s) successfully!';
                break;

            case 'assign_approver':
                ApplicationForm::whereIn('id', $applicationIds)->update([
                    'approver_id' => $request->approver_id,
                ]);

                $approver     = Approver::find($request->approver_id);
                $applications = ApplicationForm::whereIn('id', $applicationIds)->get();

                // One consolidated notification to the approver
                Notification::create([
                    'user_id'      => $approver->id,
                    'user_type'    => 'approver',
                    'type'         => 'application_assigned',
                    'title'        => 'New Applications Assigned',
                    'message'      => count($applicationIds) . ' application(s) have been assigned to you for final approval by the admin.',
                    'related_id'   => $applications->first()?->id,
                    'related_type' => 'application',
                ]);

                // Per-candidate notification
                foreach ($applications as $app) {
                    $positionTitle = $app->applying_position ?? $app->advertisement_no ?? 'this position';
                    $candidateRecord = \DB::table('candidate_registration')
                        ->where('citizenship_number', $app->citizenship_number)
                        ->first();
                    if ($candidateRecord) {
                        Notification::create([
                            'user_id'      => $candidateRecord->id,
                            'user_type'    => 'candidate',
                            'type'         => 'approver_assigned',
                            'title'        => 'Approver Assigned',
                            'message'      => 'Your application for "' . $positionTitle . '" has been assigned to an approver for final decision.',
                            'related_id'   => $app->id,
                            'related_type' => 'application',
                        ]);
                    }
                }

                $message = 'Approver "' . ($approver->name ?? 'N/A') . '" assigned to ' . count($applicationIds) . ' application(s) successfully!';
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
