<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\Reviewer;
use App\Models\Approver;
use App\Models\JobPosting;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\ApplicationStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $applications = $query->paginate(20)->withQueryString();

        // Get all jobs for filter dropdown, with application count per vacancy
        $jobs = JobPosting::select('id', 'title', 'advertisement_no', 'level', 'position')
            ->withCount(['applications' => function ($q) {
                $q->where('status', '!=', 'draft');
            }])
            ->get();

        // Compute group-combined counts (siblings sharing same position+level reference point)
        $groupCounts = [];
        foreach ($jobs as $job) {
            $key = ($job->position ?? '') . '|' . ($job->level ?? '');
            if (!isset($groupCounts[$key])) $groupCounts[$key] = 0;
            $groupCounts[$key] += $job->applications_count;
        }
        foreach ($jobs as $job) {
            $key = ($job->position ?? '') . '|' . ($job->level ?? '');
            $job->group_applications_count = $groupCounts[$key];
        }

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
        $application->load(['vacancy', 'reviewer', 'approver', 'statusHistories']);

        // If AJAX request, return JSON for modal view
        if (request()->ajax()) {
            // Build category_labels: for each applied category, resolve its display label
            // by looking up sibling vacancies (same position+level) and matching inclusive sub-types
            $appliedCategories = $application->applied_category; // e.g. ['open','inclusive']
            $categoryLabels = [];

            if (!empty($appliedCategories)) {
                // Load sibling job postings for this position+level
                $siblingJobs = \App\Models\JobPosting::where('position', $application->vacancy->position ?? '')
                    ->where('level', $application->vacancy->level ?? '')
                    ->get(['id', 'category', 'inclusive_type', 'advertisement_no']);

                // Map category → sibling job
                $siblingByCategory = [];
                foreach ($siblingJobs as $sj) {
                    $siblingByCategory[$sj->category] = $sj;
                }

                foreach ($appliedCategories as $cat) {
                    $label = ucfirst(str_replace('_', ' ', $cat));

                    if ($cat === 'inclusive') {
                        $siblingInclusive = $siblingByCategory['inclusive'] ?? null;
                        if ($siblingInclusive) {
                            $types = json_decode($siblingInclusive->inclusive_type ?? '[]', true) ?: [];
                            if (!empty($types)) {
                                $label = 'Inclusive (' . implode(', ', $types) . ')';
                            }
                        }
                    }

                    $categoryLabels[] = $label;
                }
            }

            return response()->json([
                'id' => $application->id,
                'category_labels' => $categoryLabels,
                'name_english' => $application->name_english,
                'name_nepali' => $application->name_nepali,
                'email' => $application->email,
                'phone' => $application->phone,
                'gender' => $application->gender,
                'age' => $application->age,
                'birth_date_bs' => $application->birth_date_bs,
                'birth_date_ad' => $application->birth_date_ad ? $application->birth_date_ad->format('Y-m-d') : null,
                'citizenship_number' => $application->citizenship_number,
                'citizenship_issue_district' => $application->citizenship_issue_district,
                'citizenship_issue_date_bs' => $application->citizenship_issue_date_bs,
                'marital_status' => $application->marital_status,
                'nationality' => $application->nationality,
                'religion' => $application->religion,
                'community' => $application->community,
                'ethnic_group' => $application->ethnic_group,
                'mother_tongue' => $application->mother_tongue,
                'noc_employee' => $application->noc_employee,
                'physical_disability' => $application->physical_disability,
                // Family
                'father_name_english' => $application->father_name_english,
                'mother_name_english' => $application->mother_name_english,
                'grandfather_name_english' => $application->grandfather_name_english,
                'spouse_name_english' => $application->spouse_name_english,
                // Address
                'permanent_province' => $application->permanent_province,
                'permanent_district' => $application->permanent_district,
                'permanent_municipality' => $application->permanent_municipality,
                'permanent_ward' => $application->permanent_ward,
                'permanent_tole' => $application->permanent_tole,
                'mailing_province' => $application->mailing_province,
                'mailing_district' => $application->mailing_district,
                'mailing_municipality' => $application->mailing_municipality,
                'mailing_ward' => $application->mailing_ward,
                'mailing_tole' => $application->mailing_tole,
                // Education
                'education_level' => $application->education_level,
                'field_of_study' => $application->field_of_study,
                'institution_name' => $application->institution_name,
                'university' => $application->university,
                'graduation_year' => $application->graduation_year,
                // Experience
                'has_work_experience' => $application->has_work_experience,
                'years_of_experience' => $application->years_of_experience,
                'exp1_organization' => $application->exp1_organization,
                'exp1_position' => $application->exp1_position,
                'exp1_start_date' => $application->exp1_start_date,
                'exp1_end_date' => $application->exp1_end_date,
                'exp2_organization' => $application->exp2_organization,
                'exp2_position' => $application->exp2_position,
                'exp2_start_date' => $application->exp2_start_date,
                'exp2_end_date' => $application->exp2_end_date,
                'exp3_organization' => $application->exp3_organization,
                'exp3_position' => $application->exp3_position,
                'exp3_start_date' => $application->exp3_start_date,
                'exp3_end_date' => $application->exp3_end_date,
                // Vacancy
                'applying_position' => $application->applying_position,
                'advertisement_no' => $application->advertisement_no,
                'vacancy_title' => $application->vacancy->title ?? null,
                'vacancy_department' => $application->vacancy->department ?? null,
                // Status & Assignment
                'status' => $application->status,
                'reviewer_name' => $application->reviewer->name ?? null,
                'reviewer_email' => $application->reviewer->email ?? null,
                'approver_name' => $application->approver->name ?? null,
                'approver_email' => $application->approver->email ?? null,
                'created_at' => $application->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $application->updated_at->format('Y-m-d H:i:s'),
                // Documents
                'passport_size_photo' => $application->passport_size_photo ? asset('storage/' . $application->passport_size_photo) : null,
                'citizenship_id_document' => $application->citizenship_id_document ? asset('storage/' . $application->citizenship_id_document) : null,
                'noc_id_card' => $application->noc_id_card ? asset('storage/' . $application->noc_id_card) : null,
                'ethnic_certificate' => $application->ethnic_certificate ? asset('storage/' . $application->ethnic_certificate) : null,
                'disability_certificate' => $application->disability_certificate ? asset('storage/' . $application->disability_certificate) : null,
                'signature' => $application->signature ? asset('storage/' . $application->signature) : null,
                'transcript' => $application->transcript ? asset('storage/' . $application->transcript) : null,
                'character' => $application->character ? asset('storage/' . $application->character) : null,
                'exp1_document' => $application->exp1_document ? asset('storage/' . $application->exp1_document) : null,
                'exp2_document' => $application->exp2_document ? asset('storage/' . $application->exp2_document) : null,
                'exp3_document' => $application->exp3_document ? asset('storage/' . $application->exp3_document) : null,
                // Status History
                'status_histories' => $application->statusHistories->map(fn($h) => [
                    'stage_name'   => $h->stage_name,
                    'done_by'      => $h->done_by,
                    'done_by_type' => $h->done_by_type,
                    'remarks'      => $h->remarks,
                    'created_at'   => $h->created_at->format('F d, Y'),
                ])->values()->toArray(),
            ]);
        }

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

        $adminName = Auth::guard('admin')->user()->name ?? 'Admin';
        $adminId   = Auth::guard('admin')->id();

        if ($request->status === 'reviewed' && $request->approver_id) {
            $approver = \App\Models\Approver::find($request->approver_id);
            ApplicationStatusHistory::create([
                'application_form_id' => $application->id,
                'stage_name'          => 'Assigned to Approver',
                'done_by'             => $adminName,
                'done_by_type'        => 'admin',
                'done_by_id'          => $adminId,
                'remarks'             => 'Reviewed and assigned to approver: ' . ($approver->name ?? 'N/A') . ($request->admin_notes ? '. Notes: ' . $request->admin_notes : ''),
            ]);
        } else {
            ApplicationStatusHistory::create([
                'application_form_id' => $application->id,
                'stage_name'          => ApplicationStatusHistory::stageName($request->status),
                'done_by'             => $adminName,
                'done_by_type'        => 'admin',
                'done_by_id'          => $adminId,
                'remarks'             => $request->admin_notes,
            ]);
        }

        // Create notification for candidate (lookup via citizenship_number — no candidate_id FK in application_form)
        $candidate = \DB::table('candidate_registration')
            ->where('citizenship_number', $application->citizenship_number)
            ->first();

        if ($request->status === 'reviewed' && $request->approver_id) {
            $approver = $approver ?? \App\Models\Approver::find($request->approver_id);

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

        if ($application->reviewer_id) {
            $existing = Reviewer::find($application->reviewer_id);
            return redirect()->back()->with('error',
                'Application #' . $application->id . ' is already assigned to reviewer "' . ($existing->name ?? 'N/A') . '" and cannot be reassigned.'
            );
        }

        $application->update([
            'reviewer_id' => $request->reviewer_id,
            'status' => 'assigned'
        ]);

        $reviewer = Reviewer::find($request->reviewer_id);

        ApplicationStatusHistory::create([
            'application_form_id' => $application->id,
            'stage_name'          => 'Assigned to Reviewer',
            'done_by'             => Auth::guard('admin')->user()->name ?? 'Admin',
            'done_by_type'        => 'admin',
            'done_by_id'          => Auth::guard('admin')->id(),
            'remarks'             => 'Assigned to reviewer: ' . ($reviewer->name ?? 'N/A'),
        ]);

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

        if ($application->approver_id) {
            $existing = Approver::find($application->approver_id);
            return redirect()->back()->with('error',
                'Application #' . $application->id . ' is already assigned to approver "' . ($existing->name ?? 'N/A') . '" and cannot be reassigned.'
            );
        }

        $application->update([
            'approver_id' => $request->approver_id,
        ]);

        $approver = Approver::find($request->approver_id);

        ApplicationStatusHistory::create([
            'application_form_id' => $application->id,
            'stage_name'          => 'Assigned to Approver',
            'done_by'             => Auth::guard('admin')->user()->name ?? 'Admin',
            'done_by_type'        => 'admin',
            'done_by_id'          => Auth::guard('admin')->id(),
            'remarks'             => 'Assigned to approver: ' . ($approver->name ?? 'N/A'),
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
            // Find sibling jobs sharing same position+level (reference point)
            $selectedJob = JobPosting::find($request->job_posting_id);
            $siblingIds  = [$request->job_posting_id];
            if ($selectedJob && $selectedJob->position && $selectedJob->level) {
                $siblingIds = JobPosting::where('position', $selectedJob->position)
                    ->where('level', $selectedJob->level)
                    ->pluck('id')
                    ->toArray();
            }

            $applicationIds = ApplicationForm::whereIn('job_posting_id', $siblingIds)
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
                $adminName = Auth::guard('admin')->user()->name ?? 'Admin';
                $adminId   = Auth::guard('admin')->id();
                foreach ($applicationIds as $appId) {
                    ApplicationStatusHistory::create([
                        'application_form_id' => $appId,
                        'stage_name'          => ApplicationStatusHistory::stageName($request->status),
                        'done_by'             => $adminName,
                        'done_by_type'        => 'admin',
                        'done_by_id'          => $adminId,
                        'remarks'             => 'Bulk status update',
                    ]);
                }
                $message = 'Status updated for ' . count($applicationIds) . ' application(s) successfully!';
                break;

            case 'assign_reviewer':
                // Filter out applications already assigned to a reviewer
                $eligibleIds = ApplicationForm::whereIn('id', $applicationIds)
                    ->whereNull('reviewer_id')
                    ->pluck('id')
                    ->toArray();
                $skipped = count($applicationIds) - count($eligibleIds);

                if (empty($eligibleIds)) {
                    return redirect()->back()->with('error',
                        'All selected application(s) are already assigned to a reviewer and cannot be reassigned.'
                    );
                }

                ApplicationForm::whereIn('id', $eligibleIds)->update([
                    'reviewer_id' => $request->reviewer_id,
                    'status'      => 'assigned',
                ]);

                $reviewer     = Reviewer::find($request->reviewer_id);
                $applications = ApplicationForm::whereIn('id', $eligibleIds)->get();

                // One consolidated notification to the reviewer
                Notification::create([
                    'user_id'      => $reviewer->id,
                    'user_type'    => 'reviewer',
                    'type'         => 'application_assigned',
                    'title'        => 'New Applications Assigned',
                    'message'      => count($eligibleIds) . ' application(s) have been assigned to you for review by the admin.',
                    'related_id'   => $applications->first()?->id,
                    'related_type' => 'application',
                ]);

                // Per-candidate notification + history
                $adminName = Auth::guard('admin')->user()->name ?? 'Admin';
                $adminId   = Auth::guard('admin')->id();
                foreach ($applications as $app) {
                    ApplicationStatusHistory::create([
                        'application_form_id' => $app->id,
                        'stage_name'          => 'Assigned to Reviewer',
                        'done_by'             => $adminName,
                        'done_by_type'        => 'admin',
                        'done_by_id'          => $adminId,
                        'remarks'             => 'Bulk assigned to reviewer: ' . ($reviewer->name ?? 'N/A'),
                    ]);

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

                $message = 'Reviewer "' . ($reviewer->name ?? 'N/A') . '" assigned to ' . count($eligibleIds) . ' application(s) successfully!'
                    . ($skipped > 0 ? ' ' . $skipped . ' application(s) skipped (already assigned).' : '');
                break;

            case 'assign_approver':
                // Filter out applications already assigned to an approver
                $eligibleIds = ApplicationForm::whereIn('id', $applicationIds)
                    ->whereNull('approver_id')
                    ->pluck('id')
                    ->toArray();
                $skipped = count($applicationIds) - count($eligibleIds);

                if (empty($eligibleIds)) {
                    return redirect()->back()->with('error',
                        'All selected application(s) are already assigned to an approver and cannot be reassigned.'
                    );
                }

                ApplicationForm::whereIn('id', $eligibleIds)->update([
                    'approver_id' => $request->approver_id,
                ]);

                $approver     = Approver::find($request->approver_id);
                $applications = ApplicationForm::whereIn('id', $eligibleIds)->get();

                // One consolidated notification to the approver
                Notification::create([
                    'user_id'      => $approver->id,
                    'user_type'    => 'approver',
                    'type'         => 'application_assigned',
                    'title'        => 'New Applications Assigned',
                    'message'      => count($eligibleIds) . ' application(s) have been assigned to you for final approval by the admin.',
                    'related_id'   => $applications->first()?->id,
                    'related_type' => 'application',
                ]);

                // Per-candidate notification + history
                $adminName = Auth::guard('admin')->user()->name ?? 'Admin';
                $adminId   = Auth::guard('admin')->id();
                foreach ($applications as $app) {
                    ApplicationStatusHistory::create([
                        'application_form_id' => $app->id,
                        'stage_name'          => 'Assigned to Approver',
                        'done_by'             => $adminName,
                        'done_by_type'        => 'admin',
                        'done_by_id'          => $adminId,
                        'remarks'             => 'Bulk assigned to approver: ' . ($approver->name ?? 'N/A'),
                    ]);

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

                $message = 'Approver "' . ($approver->name ?? 'N/A') . '" assigned to ' . count($eligibleIds) . ' application(s) successfully!'
                    . ($skipped > 0 ? ' ' . $skipped . ' application(s) skipped (already assigned).' : '');
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
