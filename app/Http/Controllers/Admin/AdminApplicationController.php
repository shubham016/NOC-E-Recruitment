<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Reviewer;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class AdminApplicationController extends Controller
{
    public function index(Request $request)
    {
        // Initialize query
        $query = Application::with(['candidate', 'jobPosting', 'reviewer']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('candidate', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
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

        // Status options
        $statuses = ['pending', 'under_review', 'shortlisted', 'rejected', 'withdrawn'];

        // Calculate statistics
        $stats = [
            'total' => Application::count(),
            'pending' => Application::where('status', 'pending')->count(),
            'under_review' => Application::where('status', 'under_review')->count(),
            'shortlisted' => Application::where('status', 'shortlisted')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
            'withdrawn' => Application::where('status', 'withdrawn')->count(),
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

    public function show(Application $application)
    {
        $application->load(['candidate', 'jobPosting', 'reviewer']);

        $reviewers = Reviewer::where('status', 'active')->get();
        $statuses = ['pending', 'under_review', 'shortlisted', 'rejected', 'withdrawn'];

        return view('admin.applications.show', compact(
            'application',
            'reviewers',
            'statuses'
        ));
    }

    public function updateStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,shortlisted,rejected,withdrawn',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Application status updated successfully!');
    }

    public function assignReviewer(Request $request, Application $application)
    {
        $request->validate([
            'reviewer_id' => 'required|exists:reviewers,id'
        ]);

        $application->update([
            'reviewer_id' => $request->reviewer_id,
            'status' => 'under_review'
        ]);

        return redirect()->back()->with('success', 'Reviewer assigned successfully!');
    }

    public function destroy(Application $application)
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
            'application_ids.*' => 'exists:applications,id',
            'status' => 'required_if:action,update_status|in:pending,under_review,shortlisted,rejected,withdrawn',
            'reviewer_id' => 'required_if:action,assign_reviewer|exists:reviewers,id'
        ]);

        $applicationIds = $request->application_ids;

        switch ($request->action) {
            case 'delete':
                Application::whereIn('id', $applicationIds)->delete();
                $message = 'Selected applications deleted successfully!';
                break;

            case 'update_status':
                Application::whereIn('id', $applicationIds)->update([
                    'status' => $request->status,
                    'reviewed_at' => now(),
                ]);
                $message = 'Status updated for selected applications!';
                break;

            case 'assign_reviewer':
                Application::whereIn('id', $applicationIds)->update([
                    'reviewer_id' => $request->reviewer_id,
                    'status' => 'under_review'
                ]);
                $message = 'Reviewer assigned to selected applications!';
                break;

            default:
                $message = 'Invalid action';
        }

        return redirect()->back()->with('success', $message);
    }
}