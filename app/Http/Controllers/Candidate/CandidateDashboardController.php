<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;

class CandidateDashboardController extends Controller
{
    public function index()
    {
        // Get authenticated candidate
        $candidate = Auth::guard('candidate')->user();

        // Get candidate statistics
        $stats = [
            'total_applications' => Application::where('candidate_id', $candidate->id)->count(),
            'pending' => Application::where('candidate_id', $candidate->id)
                ->where('status', 'pending')
                ->count(),
            'under_review' => Application::where('candidate_id', $candidate->id)
                ->where('status', 'under_review')
                ->count(),
            'shortlisted' => Application::where('candidate_id', $candidate->id)
                ->where('status', 'shortlisted')
                ->count(),
            'rejected' => Application::where('candidate_id', $candidate->id)
                ->where('status', 'rejected')
                ->count(),
            'active_jobs' => JobPosting::where('status', 'active')
                ->where('deadline', '>=', now())
                ->count(),
        ];

        // Recent applications
        $recentApplications = Application::where('candidate_id', $candidate->id)
            ->with('jobPosting')
            ->latest()
            ->limit(5)
            ->get();

        // Recommended jobs (jobs the candidate hasn't applied to)
        $appliedJobIds = Application::where('candidate_id', $candidate->id)
            ->pluck('job_posting_id')
            ->toArray();

        $recommendedJobs = JobPosting::where('status', 'active')
            ->where('deadline', '>=', now())
            ->whereNotIn('id', $appliedJobIds)
            ->latest()
            ->limit(6)
            ->get();

        return view('candidate.dashboard', compact(
            'stats',
            'recentApplications',
            'recommendedJobs',
            'candidate'
        ));
    }
}