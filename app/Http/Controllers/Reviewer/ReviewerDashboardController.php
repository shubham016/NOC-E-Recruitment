<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use Illuminate\Support\Facades\Auth;

class ReviewerDashboardController extends Controller
{
    public function index()
    {
        $reviewer = Auth::guard('reviewer')->user();

        // Get pending applications assigned to this reviewer
        $pendingApplications = ApplicationForm::with(['jobPosting'])
            ->where('reviewer_id', $reviewer->id)
            ->where('status', '!=', 'draft')
            ->whereIn('status', ['pending', 'assigned'])
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

            'edit' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->where('status', 'edit')
                ->count(),

            'total_reviewed' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->whereIn('status', ['reviewed', 'approved', 'rejected', 'shortlisted', 'edit'])
                ->count(),

            'completion_rate' => $this->calculateCompletionRate($reviewer->id),
        ];

        // Get today's progress
        $todaystatus = [
            'reviewed_today' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->whereDate('updated_at', today())
                ->whereIn('status', ['reviewed', 'approved', 'rejected', 'shortlisted', 'edit'])
                ->count(),

            'daily_target' => 15,

            'pending_review' => ApplicationForm::where('reviewer_id', $reviewer->id)
                ->whereIn('status', ['pending', 'assigned'])
                ->count(),
        ];

        // Get recent activity
        $recentActivity = ApplicationForm::with(['jobPosting', 'reviewer'])
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
            ->whereIn('status', ['reviewed', 'approved', 'rejected', 'shortlisted', 'edit'])
            ->count();

        return round(($reviewed / $totalAssigned) * 100);
    }
}
