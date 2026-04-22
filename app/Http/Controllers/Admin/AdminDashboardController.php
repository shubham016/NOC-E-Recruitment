<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\JobPosting;
use App\Models\Candidate;
use App\Models\Reviewer;
use App\Models\Approver;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Overall Statistics
        $stats = [
            'total_vacancies' => JobPosting::count(),
            'active_vacancies' => JobPosting::where('status', 'active')->count(),
            'closed_vacancies' => JobPosting::where('status', 'closed')->count(),
            'draft_vacancies' => JobPosting::where('status', 'draft')->count(),
            'total_applications' => ApplicationForm::count(),
            'pending_applications' => ApplicationForm::where('status', 'pending')->count(),
            'approved' => ApplicationForm::where('status', 'approved')->count(),
            'rejected' => ApplicationForm::where('status', 'rejected')->count(),
            'total_candidates' => Candidate::count(),
            'total_reviewers' => Reviewer::count(),
            'active_reviewers' => Reviewer::where('status', 'active')->count(),
            'total_approvers' => Approver::count(),
            'active_approvers' => Approver::where('status', 'active')->count(),
        ];

        // This Month Statistics
        $thisMonth = [
            'jobs_posted' => JobPosting::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'applications' => ApplicationForm::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'candidates' => Candidate::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Last Month Statistics
        $lastMonth = [
            'jobs_posted' => JobPosting::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count(),
            'applications' => ApplicationForm::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count(),
            'candidates' => Candidate::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count(),
        ];

        // Growth calculations
        $growth = [
            'jobs_posted' => $this->calculateGrowth($thisMonth['jobs_posted'], $lastMonth['jobs_posted']),
            'applications' => $this->calculateGrowth($thisMonth['applications'], $lastMonth['applications']),
            'candidates' => $this->calculateGrowth($thisMonth['candidates'], $lastMonth['candidates']),
        ];

        // Top Jobs by Applications
        $topJobs = JobPosting::withCount('applicationForms')
            ->orderBy('application_forms_count', 'desc')
            ->limit(4)
            ->get();

        // Recent Applications (last 4)
        $recentApplications = ApplicationForm::with(['job'])
            ->latest()
            ->limit(4)
            ->get();

        // Reviewer Statistics
        $reviewerStats = Reviewer::where('status', 'active')
            ->withCount([
                'applicationForms as total_reviewed' => function ($query) {
                    // Only count as reviewed if reviewer has actually reviewed (added notes or reviewed_at is set)
                    $query->where(function ($q) {
                        $q->whereNotNull('reviewed_at')
                          ->orWhereNotNull('reviewer_notes');
                    });
                },
                'applicationForms as pending' => function ($query) {
                    // Assigned to reviewer but not yet reviewed
                    $query->whereNull('reviewed_at')
                          ->whereNull('reviewer_notes');
                }
            ])
            ->orderBy('total_reviewed', 'desc')
            ->limit(2)
            ->get();

        // Approver Statistics
        $approverStats = Approver::where('status', 'active')
            ->withCount([
                'applicationForms as approved_count' => function ($query) {
                    $query->where('status', 'approved');
                },
                'applicationForms as rejected_count' => function ($query) {
                    $query->where('status', 'rejected');
                }
            ])
            ->orderBy('approved_count', 'desc')
            ->limit(2)
            ->get();

        // Recent Job Postings
        $recentJobs = JobPosting::latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'thisMonth',
            'growth',
            'topJobs',
            'recentApplications',
            'reviewerStats',
            'approverStats',
            'recentJobs'
        ));
    }

    /**
     * Calculate growth percentage
     */
    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 1);
    }
}