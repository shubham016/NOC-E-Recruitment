<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewerDashboardController extends Controller
{
    public function index()
    {
        $reviewer = Auth::guard('reviewer')->user();
        
        // Get pending applications with relationships
        $pendingApplications = Application::with(['candidate', 'job'])
            ->where('status', 'pending')
            ->orWhere('status', 'under_review')
            ->latest()
            ->take(4)
            ->get();

        // Calculate statistics
        $stats = [
            'pending' => Application::whereIn('status', ['pending', 'under_review'])->count(),
            'total_reviewed' => Application::where('reviewed_by', $reviewer->id)->count(),
            'shortlisted' => Application::where('reviewed_by', $reviewer->id)
                ->where('status', 'shortlisted')
                ->count(),
            'approval_rate' => $this->calculateApprovalRate($reviewer->id),
        ];

        // Get today's progress
        $todayStats = [
            'reviewed_today' => Application::where('reviewed_by', $reviewer->id)
                ->whereDate('reviewed_at', today())
                ->count(),
            'daily_target' => 15,
            'approved_today' => Application::where('reviewed_by', $reviewer->id)
                ->whereDate('reviewed_at', today())
                ->where('status', 'shortlisted')
                ->count(),
            'rejected_today' => Application::where('reviewed_by', $reviewer->id)
                ->whereDate('reviewed_at', today())
                ->where('status', 'rejected')
                ->count(),
            'on_hold_today' => Application::where('reviewed_by', $reviewer->id)
                ->whereDate('reviewed_at', today())
                ->where('status', 'under_review')
                ->count(),
        ];

        // Get recent activity
        $recentActivity = Application::with(['candidate', 'job', 'reviewer'])
            ->where('reviewed_by', $reviewer->id)
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
        $totalReviewed = Application::where('reviewed_by', $reviewerId)->count();
        
        if ($totalReviewed == 0) {
            return 0;
        }

        $approved = Application::where('reviewed_by', $reviewerId)
            ->whereIn('status', ['shortlisted', 'accepted'])
            ->count();

        return round(($approved / $totalReviewed) * 100);
    }
}