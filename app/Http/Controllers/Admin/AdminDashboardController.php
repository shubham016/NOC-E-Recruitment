<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPosting;
use App\Models\Candidate;
use App\Models\Reviewer;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Overall Statistics
        $stats = [
            'total_jobs' => JobPosting::count(),
            'active_jobs' => JobPosting::where('status', 'active')->count(),
            'total_applications' => Application::count(),
            'pending_applications' => Application::whereIn('status', ['pending', 'under_review'])->count(),
            'shortlisted' => Application::where('status', 'shortlisted')->count(),
            'total_candidates' => Candidate::count(),
            'total_reviewers' => Reviewer::count(),
            'active_reviewers' => Reviewer::where('status', 'active')->count(),
        ];

        // This Month Statistics
        $thisMonth = [
            'jobs_posted' => JobPosting::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'applications' => Application::whereMonth('created_at', now()->month)
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
            'applications' => Application::whereMonth('created_at', now()->subMonth()->month)
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
        $topJobs = JobPosting::withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->limit(5)
            ->get();

        // Recent Applications (last 10)
        $recentApplications = Application::with(['candidate', 'jobPosting'])
            ->latest()
            ->limit(10)
            ->get();

        // Reviewer Statistics
        $reviewerStats = Reviewer::where('status', 'active')
            ->withCount([
                'applications as total_reviewed',
                'applications as pending' => function ($query) {
                    $query->where('status', 'pending');
                }
            ])
            ->orderBy('total_reviewed', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'thisMonth',
            'growth',
            'topJobs',
            'recentApplications',
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