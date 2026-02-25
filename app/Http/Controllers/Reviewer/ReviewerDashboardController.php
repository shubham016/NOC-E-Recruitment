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
                    ->orWhere('status', 'approved');
            })
            ->latest()
            ->take(4)
            ->get();

        // Calculate statistics for this reviewer's assigned applications
        $stats = [
            'pending' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'pending')
                ->count(),
            'total_reviewed' => ApplicationForm::where('reviewer_id', $reviewer->id)->count(),
            'approved' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'approved')
                ->count(),
            'rejected' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'rejected')
                ->count(),
            'approval_rate' => $this->calculateApprovalRate($reviewer->id),
        ];

        // Get today's progress
        $todayStats = [
            'reviewed_today' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->whereDate('reviewed_at', today())
                ->count(),
            'daily_target' => 15,
            'approved_today' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->whereDate('reviewed_at', today())
                ->where('status', 'approved')
                ->count(),
            'rejected_today' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->whereDate('reviewed_at', today())
                ->where('status', 'rejected')
                ->count(),
            'on_hold_today' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->whereDate('reviewed_at', today())
                ->where('status', 'approved')
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
        $progressPercentage = $todayStats['daily_target'] > 0
            ? round(($todayStats['reviewed_today'] / $todayStats['daily_target']) * 100)
            : 0;

        return view('reviewer.dashboard', compact(
            'pendingApplications',
            'stats',
            'todayStats',
            'recentActivity',
            'progressPercentage'
        ));
    }

    private function calculateApprovalRate($reviewerId)
    {
        $totalReviewed = ApplicationForm::where('reviewer_id', $reviewerId)->count();

        if ($totalReviewed == 0) {
            return 0;
        }

        $approved = ApplicationForm::where('reviewer_id', $reviewerId)
            ->where('status', 'approved')
            ->count();

        return round(($approved / $totalReviewed) * 100);
    }
}
