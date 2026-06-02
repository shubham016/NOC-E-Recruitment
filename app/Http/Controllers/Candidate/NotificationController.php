<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private function getCandidate()
    {
        return Auth::guard('candidate')->user();
    }

    /**
     * Display a listing of notifications
     */
    public function index(Request $request)
    {
        $candidate = $this->getCandidate();

        if (!$candidate) {
            return redirect()->route('candidate.login')->with('error', 'Please login to view notifications.');
        }

        $tab = $request->get('tab', 'unseen');

        $query = Notification::forUser($candidate->id, 'candidate')
            ->orderBy('created_at', 'desc');

        if ($tab === 'seen') {
            $query->where('is_read', true);
        } else {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate(20)->withQueryString();

        $unseenCount = Notification::forUser($candidate->id, 'candidate')->where('is_read', false)->count();
        $seenCount   = Notification::forUser($candidate->id, 'candidate')->where('is_read', true)->count();

        return view('candidate.notifications.index', compact('notifications', 'tab', 'unseenCount', 'seenCount'));
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
            return redirect()->route('candidate.applications.show', $notification->related_id);
        }

        return redirect()->back();
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

        return redirect()->route('candidate.notifications.index', ['tab' => 'seen'])->with('success', 'All notifications marked as seen.');
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
