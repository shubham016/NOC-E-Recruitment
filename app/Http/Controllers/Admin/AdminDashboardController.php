<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\Vacancy;
use App\Models\Candidate;
use App\Models\Reviewer;
use App\Models\HRAdministrator;
use App\Models\Approver;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Overall Statistics
        $stats = [
            'total_vacancies' => Vacancy::count(),
            'active_vacancies' => Vacancy::where('status', 'active')->count(),
            'closed_vacancies' => Vacancy::where('status', 'closed')->count(),
            'draft_vacancies' => Vacancy::where('status', 'draft')->count(),
            'total_applications' => ApplicationForm::count(),
            'pending_applications' => ApplicationForm::where('status', 'pending')->count(),
            'approved' => ApplicationForm::where('status', 'approved')->count(),
            'rejected' => ApplicationForm::where('status', 'rejected')->count(),
            'total_candidates' => Candidate::count(),
            'total_reviewers' => Reviewer::count(),
            'active_reviewers' => Reviewer::where('status', 'active')->count(),
            'total_hr_admins' => HRAdministrator::count(),
            'active_hr_admins' => HRAdministrator::where('status', 'active')->count(),
            'total_approvers' => Approver::count(),
            'active_approvers' => Approver::where('status', 'active')->count(),
        ];

        // This Month Statistics
        $thisMonth = [
            'jobs_posted' => Vacancy::whereMonth('created_at', now()->month)
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
            'jobs_posted' => Vacancy::whereMonth('created_at', now()->subMonth()->month)
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

        // Top Vacancies by Applications
        $topVacancies = Vacancy::withCount('applicationForms')
            ->orderBy('application_forms_count', 'desc')
            ->limit(5)
            ->get();

        // Recent Applications (last 10)
        $recentApplications = ApplicationForm::with(['candidate', 'vacancy'])
            ->latest()
            ->limit(10)
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
            ->limit(5)
            ->get();

        // HR Administrator Performance
        // Using raw query to avoid the snake_case column name issue
        $hrAdminStats = HRAdministrator::where('status', 'active')
            ->select('hr_administrators.*')
            ->selectSub(function ($query) {
                $query->selectRaw('count(*)')
                    ->from('vacancies')
                    ->whereColumn('vacancies.posted_by', 'hr_administrators.id')
                    ->whereMonth('vacancies.created_at', now()->month)
                    ->whereYear('vacancies.created_at', now()->year);
            }, 'jobs_posted')
            ->orderBy('jobs_posted', 'desc')
            ->limit(5)
            ->get();

        // Recent Job Postings
        $recentVacancies = Vacancy::latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'thisMonth',
            'growth',
            'topVacancies',
            'recentApplications',
            'reviewerStats',
            'hrAdminStats',
            'recentVacancies'
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