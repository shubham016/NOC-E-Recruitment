<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPosting;
use App\Models\Candidate;
use App\Models\Reviewer;
use App\Models\Admin;
use Illuminate\Http\Request;
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

        // Applications by Status (for pie chart)
        $applicationsByStatus = Application::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Recent Applications (last 7 days trend)
        $recentApplicationsTrend = Application::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top Jobs by Applications
        $topJobs = JobPosting::withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->limit(5)
            ->get();

        // Recent Applications (last 10)
        $recentApplications = Application::with(['candidate', 'job', 'reviewer'])
            ->latest()
            ->limit(10)
            ->get();

        // Recent Jobs Posted (last 5)
        $recentJobs = JobPosting::latest()
            ->limit(5)
            ->get();

        // Active Reviewers with their stats
        $reviewerStats = Reviewer::where('status', 'active')
            ->withCount([
                'applications as total_reviewed',
                'applications as pending' => function ($query) {
                    $query->whereIn('status', ['pending', 'under_review']);
                }
            ])
            ->limit(5)
            ->get();

        // This Month Statistics
        $thisMonth = [
            'applications' => Application::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'jobs_posted' => JobPosting::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'candidates_registered' => Candidate::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Calculate growth percentages
        $lastMonth = [
            'applications' => Application::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count(),
            'jobs_posted' => JobPosting::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count(),
            'candidates_registered' => Candidate::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count(),
        ];

        $growth = [
            'applications' => $this->calculateGrowth($thisMonth['applications'], $lastMonth['applications']),
            'jobs_posted' => $this->calculateGrowth($thisMonth['jobs_posted'], $lastMonth['jobs_posted']),
            'candidates_registered' => $this->calculateGrowth($thisMonth['candidates_registered'], $lastMonth['candidates_registered']),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'applicationsByStatus',
            'recentApplicationsTrend',
            'topJobs',
            'recentApplications',
            'recentJobs',
            'reviewerStats',
            'thisMonth',
            'growth'
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