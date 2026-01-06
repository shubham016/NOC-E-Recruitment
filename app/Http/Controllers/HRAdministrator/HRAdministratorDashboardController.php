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

        // ADD THIS LINE - Create the variable the view expects
        $hrAdministrator = $hrAdmin;

        // Statistics for jobs posted by this HR Administrator
        $stats = [
            'total_jobs' => JobPosting::where('posted_by', $hrAdmin->id)->count(),
            'active_jobs' => JobPosting::where('posted_by', $hrAdmin->id)
                ->where('status', 'active')
                ->count(),
            'pending_applications' => Application::whereHas('jobPosting', function ($q) use ($hrAdmin) {
                $q->where('posted_by', $hrAdmin->id);
            })->where('status', 'pending')->count(),
            'total_candidates' => Candidate::count(),
            'total_reviewers' => Reviewer::where('status', 'active')->count(),
            'active_reviewers' => Reviewer::where('status', 'active')->count(),

            // ADD THESE - The show.blade.php view expects these exact keys
            'total_jobs_posted' => JobPosting::where('posted_by', $hrAdmin->id)->count(),
            'closed_jobs' => JobPosting::where('posted_by', $hrAdmin->id)
                ->where('status', 'closed')
                ->count(),
            'total_applications' => Application::whereHas('jobPosting', function ($q) use ($hrAdmin) {
                $q->where('posted_by', $hrAdmin->id);
            })->count(),
        ];

        // Growth statistics (this month vs last month)
        $thisMonthStart = now()->startOfMonth();
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        $thisMonth = [
            'jobs_posted' => JobPosting::where('posted_by', $hrAdmin->id)
                ->whereBetween('created_at', [$thisMonthStart, now()])
                ->count(),
            'applications' => Application::whereHas('jobPosting', function ($q) use ($hrAdmin) {
                $q->where('posted_by', $hrAdmin->id);
            })->whereBetween('created_at', [$thisMonthStart, now()])
                ->count(),
            'candidates' => Candidate::whereBetween('created_at', [$thisMonthStart, now()])
                ->count(),
        ];

        $lastMonth = [
            'jobs_posted' => JobPosting::where('posted_by', $hrAdmin->id)
                ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
                ->count(),
            'applications' => Application::whereHas('jobPosting', function ($q) use ($hrAdmin) {
                $q->where('posted_by', $hrAdmin->id);
            })->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
                ->count(),
            'candidates' => Candidate::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
                ->count(),
        ];

        // Calculate growth percentage
        $growth = [
            'jobs_posted' => $lastMonth['jobs_posted'] > 0
                ? round((($thisMonth['jobs_posted'] - $lastMonth['jobs_posted']) / $lastMonth['jobs_posted']) * 100)
                : 0,
            'applications' => $lastMonth['applications'] > 0
                ? round((($thisMonth['applications'] - $lastMonth['applications']) / $lastMonth['applications']) * 100)
                : 0,
            'candidates' => $lastMonth['candidates'] > 0
                ? round((($thisMonth['candidates'] - $lastMonth['candidates']) / $lastMonth['candidates']) * 100)
                : 0,
        ];

        // Recent applications for this HR Admin's jobs
        $recentApplications = Application::with(['candidate', 'jobPosting'])
            ->whereHas('jobPosting', function ($q) use ($hrAdmin) {
                $q->where('posted_by', $hrAdmin->id);
            })
            ->latest()
            ->take(5)
            ->get();

        // ADD THIS - The show.blade.php view expects $recentJobs
        $recentJobs = JobPosting::where('posted_by', $hrAdmin->id)
            ->latest()
            ->take(5)
            ->get();

        // Top jobs by application count
        $topJobs = JobPosting::where('posted_by', $hrAdmin->id)
            ->withCount('applications')
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

        // FIXED - Now passing all required variables
        return view('admin.hr-administrators.show', compact(
            'hrAdministrator',  // ✅ ADDED THIS
            'stats',
            'recentJobs',       // ✅ ADDED THIS
            'growth',
            'thisMonth',
            'recentApplications',
            'topJobs',
            'reviewerStats'
        ));
    }
}