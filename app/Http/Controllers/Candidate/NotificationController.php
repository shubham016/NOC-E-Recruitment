<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications
     */
    public function index()
    {
        $candidate = Auth::guard('candidate')->user();

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

        // Log notification access
        \Log::info('Candidate viewed notifications page', [
            'candidate_id' => $candidate->id,
            'total_notifications' => $notifications->total(),
            'auto_marked_read' => count($unreadIds),
            'marked_notification_ids' => $unreadIds,
        ]);

        return view('candidate.notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead($id)
    {
        $candidate = Auth::guard('candidate')->user();

        $notification = Notification::forUser($candidate->id, 'candidate')
            ->findOrFail($id);

        // Log before marking as read
        \Log::info('Candidate marking notification as read', [
            'notification_id' => $notification->id,
            'candidate_id' => $candidate->id,
            'user_type' => $notification->user_type,
            'notification_type' => $notification->type,
            'was_read' => $notification->is_read,
        ]);

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
        $candidate = Auth::guard('candidate')->user();

        // Get unread notification IDs before updating
        $unreadIds = Notification::forUser($candidate->id, 'candidate')
            ->unread()
            ->pluck('id')
            ->toArray();

        $affectedRows = Notification::forUser($candidate->id, 'candidate')
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // Log the action
        \Log::info('Candidate marked all notifications as read', [
            'candidate_id' => $candidate->id,
            'affected_rows' => $affectedRows,
            'notification_ids' => $unreadIds,
        ]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $candidate = Auth::guard('candidate')->user();

        $notification = Notification::forUser($candidate->id, 'candidate')
            ->findOrFail($id);

        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }
}
