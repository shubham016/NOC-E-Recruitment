<?php

namespace App\Http\Controllers\Reviewer;

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
        $reviewer = Auth::guard('reviewer')->user();

        // Get unread notification IDs before marking as read
        $unreadIds = Notification::forUser($reviewer->id, 'reviewer')
            ->unread()
            ->pluck('id')
            ->toArray();

        // Automatically mark all unread notifications as read when viewing the page
        if (!empty($unreadIds)) {
            Notification::forUser($reviewer->id, 'reviewer')
                ->unread()
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);
        }

        // Get all notifications for this reviewer, ordered by newest first
        $notifications = Notification::forUser($reviewer->id, 'reviewer')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Log notification access
        \Log::info('Reviewer viewed notifications page', [
            'reviewer_id' => $reviewer->id,
            'total_notifications' => $notifications->total(),
            'auto_marked_read' => count($unreadIds),
            'marked_notification_ids' => $unreadIds,
        ]);

        return view('reviewer.notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead($id)
    {
        $reviewer = Auth::guard('reviewer')->user();

        $notification = Notification::forUser($reviewer->id, 'reviewer')
            ->findOrFail($id);

        // Log before marking as read
        \Log::info('Reviewer marking notification as read', [
            'notification_id' => $notification->id,
            'reviewer_id' => $reviewer->id,
            'user_type' => $notification->user_type,
            'notification_type' => $notification->type,
            'was_read' => $notification->is_read,
        ]);

        $notification->markAsRead();

        // Redirect to related page if available
        if ($notification->related_type === 'application' && $notification->related_id) {
            return redirect()->route('reviewer.applications.show', $notification->related_id)
                ->with('success', 'Notification marked as read.');
        }

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $reviewer = Auth::guard('reviewer')->user();

        Notification::forUser($reviewer->id, 'reviewer')
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
        $reviewer = Auth::guard('reviewer')->user();

        $notification = Notification::forUser($reviewer->id, 'reviewer')
            ->findOrFail($id);

        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }
}
