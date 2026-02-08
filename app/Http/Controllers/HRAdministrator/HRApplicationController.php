<?php

namespace App\Http\Controllers\HRAdministrator;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Reviewer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HRAdministratorDashboardController extends Controller
{
    /**
     * Display HR Administrator dashboard
     */
    public function index()
    {
        $hrAdmin = Auth::guard('hr_administrator')->user();
        $hrAdministrator = $hrAdmin; // Required by the view

        // SIMPLE FIX: Show ALL jobs in the system
        // This makes sense for a government recruitment system where
        // HR admins should see all active vacancies

        $stats = [
            // Show ALL jobs statistics
            'total_jobs' => JobPosting::count(),
            'active_jobs' => JobPosting::where('status', 'active')->count(),
            'closed_jobs' => JobPosting::where('status', 'closed')->count(),
            'draft_jobs' => JobPosting::where('status', 'draft')->count(),

            // Show ALL applications statistics
            'pending_applications' => Application::where('status', 'pending')->count(),
            'total_applications' => Application::count(),

            // Other statistics
            'total_candidates' => Candidate::count(),
            'total_reviewers' => Reviewer::where('status', 'active')->count(),
            'active_reviewers' => Reviewer::where('status', 'active')->count(),
        ];

        // For backward compatibility with the view
        $stats['total_jobs_posted'] = $stats['total_jobs'];

        // Growth statistics (this month vs last month)
        $thisMonthStart = now()->startOfMonth();
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        $thisMonth = [
            'jobs_posted' => JobPosting::whereBetween('created_at', [$thisMonthStart, now()])->count(),
            'applications' => Application::whereBetween('created_at', [$thisMonthStart, now()])->count(),
            'candidates' => Candidate::whereBetween('created_at', [$thisMonthStart, now()])->count(),
        ];

        $lastMonth = [
            'jobs_posted' => JobPosting::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count(),
            'applications' => Application::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count(),
            'candidates' => Candidate::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count(),
        ];

        // Calculate growth percentage
        $growth = [
            'jobs_posted' => $this->calculateGrowth($thisMonth['jobs_posted'], $lastMonth['jobs_posted']),
            'applications' => $this->calculateGrowth($thisMonth['applications'], $lastMonth['applications']),
            'candidates' => $this->calculateGrowth($thisMonth['candidates'], $lastMonth['candidates']),
        ];

        // Recent applications - show ALL recent applications
        $recentApplications = Application::with(['candidate', 'jobPosting'])
            ->latest()
            ->take(5)
            ->get();

        // Recent jobs - show ALL recent jobs
        $recentJobs = JobPosting::latest()
            ->take(5)
            ->get();

        // Top jobs by application count - show ALL jobs
        $topJobs = JobPosting::withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->take(5)
            ->get();

        // Active reviewers with stats
        $reviewerStats = Reviewer::where('status', 'active')
            ->withCount([
                'applications as total_reviewed' => function ($q) {
                    $q->whereIn('status', ['reviewed', 'shortlisted', 'rejected']);
                },
                'applications as pending' => function ($q) {
                    $q->where('status', 'under_review');
                }
            ])
            ->take(5)
            ->get();

        return view('admin.hr-administrators.show', compact(
            'hrAdministrator',
            'stats',
            'recentJobs',
            'growth',
            'thisMonth',
            'recentApplications',
            'topJobs',
            'reviewerStats'
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