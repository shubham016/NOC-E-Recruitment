<?php

namespace App\Http\Controllers\HRAdministrator;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\Reviewer;
use App\Models\Vacancy;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HRApplicationController extends Controller
{
    public function index(Request $request)
    {
        // Initialize query - show all submitted applications
        $query = ApplicationForm::with(['candidate', 'vacancy', 'reviewer'])
            ->where('status', '!=', 'draft');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('candidate', function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('vacancy', function ($q2) use ($search) {
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
            $query->where('vacancy_id', $request->job_id);
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

        // Get all vacancies for filter dropdown
        $vacancies = Vacancy::select('id', 'title', 'advertisement_no')->get();

        // Get all active reviewers for filter dropdown
        $reviewers = Reviewer::select('id', 'name', 'email')
            ->where('status', 'active')
            ->get();

        // Status options
        $statuses = ['pending', 'approved', 'rejected'];

        // Calculate statistics
        $stats = [
            'total' => ApplicationForm::where('status', '!=', 'draft')->count(),
            'pending' => ApplicationForm::where('status', 'pending')->count(),
            'approved' => ApplicationForm::where('status', 'approved')->count(),
            'rejected' => ApplicationForm::where('status', 'rejected')->count(),
        ];

        return view('hr-administrator.applications.index', compact(
            'applications',
            'jobs',
            'reviewers',
            'statuses',
            'stats'
        ));
    }

    public function show(ApplicationForm $application)
    {
        $application->load(['candidate', 'vacancy', 'reviewer']);

        $reviewers = Reviewer::where('status', 'active')->get();
        $statuses = ['pending', 'approved', 'rejected'];

        return view('hr-administrator.applications.show', compact(
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

        // Create notification for candidate
        $notificationMessages = [
            'approved' => [
                'title' => 'Application Approved',
                'message' => 'Congratulations! Your application for "' . $application->vacancy->title . '" has been approved by HR.',
                'type' => 'application_approved'
            ],
            'rejected' => [
                'title' => 'Application Rejected',
                'message' => 'Your application for "' . $application->vacancy->title . '" has been rejected. Please check the HR notes for more details.',
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

        // Create notification for candidate
        Notification::create([
            'user_id' => $application->candidate_id,
            'user_type' => 'candidate',
            'type' => 'reviewer_assigned',
            'title' => 'Reviewer Assigned',
            'message' => 'Your application for "' . $application->vacancy->title . '" has been assigned to a reviewer for evaluation.',
            'related_id' => $application->id,
            'related_type' => 'application',
        ]);

        // Create notification for reviewer
        Notification::create([
            'user_id' => $request->reviewer_id,
            'user_type' => 'reviewer',
            'type' => 'application_assigned',
            'title' => 'New Application Assigned',
            'message' => 'A new application for "' . $application->vacancy->title . '" has been assigned to you for review.',
            'related_id' => $application->id,
            'related_type' => 'application',
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

        return redirect()->route('hr-administrator.applications.index')
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
}
