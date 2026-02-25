<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
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
            'total_applications' => ApplicationForm::where('candidate_id', $candidate->id)
                ->where('status', '!=', 'draft')
                ->count(),
            'drafts' => ApplicationForm::where('candidate_id', $candidate->id)
                ->where('status', 'draft')
                ->count(),
            'pending' => ApplicationForm::where('candidate_id', $candidate->id)
                ->where('status', 'pending')
                ->count(),
            'approved' => ApplicationForm::where('candidate_id', $candidate->id)
                ->where('status', 'approved')
                ->count(),
            'shortlisted' => ApplicationForm::where('candidate_id', $candidate->id)
                ->where('status', 'shortlisted')
                ->count(),
            'rejected' => ApplicationForm::where('candidate_id', $candidate->id)
                ->where('status', 'rejected')
                ->count(),
            'active_jobs' => JobPosting::where('status', 'active')
                ->where('deadline', '>=', now())
                ->count(),
        ];

        // Recent applications (non-draft)
        $recentApplications = ApplicationForm::where('candidate_id', $candidate->id)
            ->where('status', '!=', 'draft')
            ->with('jobPosting')
            ->latest()
            ->limit(5)
            ->get();

        // Recommended jobs (jobs the candidate hasn't applied to)
        $appliedJobIds = ApplicationForm::where('candidate_id', $candidate->id)
            ->pluck('job_posting_id')
            ->toArray();

        $recommendedJobs = JobPosting::where('status', 'active')
            ->where('deadline', '>=', now())
            ->whereNotIn('id', $appliedJobIds)
            ->latest()
            ->limit(6)
            ->get();

        // Flat counts for shradha's dashboard view
        $applicationsCount = $stats['total_applications'];
        $jobpostingsCount = $stats['active_jobs'];

        // Set candidate name in session for layout
        session(['candidate_name' => $candidate->name]);

        return view('candidate.dashboard', compact(
            'stats',
            'recentApplications',
            'recommendedJobs',
            'candidate',
            'applicationsCount',
            'jobpostingsCount'
        ));
    }
}
