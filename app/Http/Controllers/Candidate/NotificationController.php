<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class NotificationController extends Controller
{
    private function getCandidate()
    {
        $candidateId = Session::get('candidate_id');
        if (!$candidateId) {
            return null;
        }
        return DB::table('candidate_registration')->where('id', $candidateId)->first();
    }

    /**
     * Display a listing of notifications
     */
    public function index()
    {
        $candidate = $this->getCandidate();

        if (!$candidate) {
            return redirect()->route('candidate.login')->with('error', 'Please login to view notifications.');
        }

        // Get unread notification IDs before marking as read
        $unreadIds = Notification::forUser($candidate->id, 'candidate')
            ->unread()
            ->pluck('id')
            ->toArray();

        // Automatically mark all unread notifications as read when viewing the page
        if (!empty($unreadIds)) {
            Notification::forUser($candidate->id, 'candidate')
                ->unread()
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);
        }

        // Get all notifications for this candidate, ordered by newest first
        $notifications = Notification::forUser($candidate->id, 'candidate')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('candidate.notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead($id)
    {
        $candidate = $this->getCandidate();

        if (!$candidate) {
            return redirect()->route('candidate.login');
        }

        $notification = Notification::forUser($candidate->id, 'candidate')
            ->findOrFail($id);

        $notification->markAsRead();

        // Redirect to related page if available
        if ($notification->related_type === 'application' && $notification->related_id) {
            return redirect()->route('candidate.applications.show', $notification->related_id)
                ->with('success', 'Notification marked as read.');
        }

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $candidate = $this->getCandidate();

        if (!$candidate) {
            return redirect()->route('candidate.login');
        }

        Notification::forUser($candidate->id, 'candidate')
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $candidate = $this->getCandidate();

        if (!$candidate) {
            return redirect()->route('candidate.login');
        }

        $notification = Notification::forUser($candidate->id, 'candidate')
            ->findOrFail($id);

        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }
}
