<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewerDashboardController extends Controller
{
    public function index()
    {
        $reviewer = Auth::guard('reviewer')->user();

        // Get pending applications assigned to this reviewer
        $pendingApplications = ApplicationForm::with(['candidate', 'jobPosting'])
            ->where('reviewer_id', $reviewer->id)
            ->where('status', '!=', 'draft')
            ->where(function ($q) {
                $q->where('status', 'pending')
                    ->orWhere('status', 'assigned');
            })
            ->latest()
            ->take(4)
            ->get();

        // Calculate statistics for this reviewer's assigned applications
        $status = [
            'pending' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'pending')
                ->count(),
            'assigned' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'assigned')
                ->count(),
            'reviewed' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'reviewed')
                ->count(),
            'approved' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'approved')
                ->count(),
            'rejected' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'rejected')
                ->count(),
            'total_reviewed' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->whereNotNull('reviewed_at')
                ->count(),
            'completion_rate' => $this->calculateCompletionRate($reviewer->id),
        ];

        // Get today's progress
        $todaystatus = [
            'reviewed_today' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->whereDate('reviewed_at', today())
                ->count(),
            'daily_target' => 15,
            'pending_review' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->whereIn('status', ['pending', 'assigned'])
                ->count(),
        ];

        // Get recent activity
        $recentActivity = ApplicationForm::with(['candidate', 'jobPosting', 'reviewer'])
            ->where('reviewer_id', $reviewer->id)
            ->whereNotNull('reviewed_at')
            ->latest('reviewed_at')
            ->take(3)
            ->get();

        // Calculate progress percentage
        $progressPercentage = $todaystatus['daily_target'] > 0
            ? round(($todaystatus['reviewed_today'] / $todaystatus['daily_target']) * 100)
            : 0;

        return view('reviewer.dashboard', compact(
            'pendingApplications',
            'status',
            'todaystatus',
            'recentActivity',
            'progressPercentage'
        ));
    }

    private function calculateCompletionRate($reviewerId)
    {
        $totalAssigned = ApplicationForm::where('reviewer_id', $reviewerId)
            ->where('status', '!=', 'draft')
            ->count();

        if ($totalAssigned == 0) {
            return 0;
        }

        $reviewed = ApplicationForm::where('reviewer_id', $reviewerId)
            ->whereNotNull('reviewed_at')
            ->count();

        return round(($reviewed / $totalAssigned) * 100);
    }
}
