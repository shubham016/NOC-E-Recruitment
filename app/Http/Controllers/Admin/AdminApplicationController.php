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
            'status' => 'approved'
        ]);

        return redirect()->back()->with('success', 'Reviewer assigned successfully!');
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
            'action' => 'required|in:delete,update_status,assign_reviewer',
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:application_form,id',
            'status' => 'required_if:action,update_status|in:pending,approved,rejected',
            'reviewer_id' => 'required_if:action,assign_reviewer|exists:reviewers,id'
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
                ApplicationForm::whereIn('id', $applicationIds)->update([
                    'reviewer_id' => $request->reviewer_id,
                    'status' => 'approved'
                ]);
                $message = 'Reviewer assigned to selected applications!';
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
