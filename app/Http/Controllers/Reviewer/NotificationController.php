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
    public function index(Request $request)
    {
        $reviewer = Auth::guard('reviewer')->user();

        $tab = $request->get('tab', 'unseen');

        $query = Notification::forUser($reviewer->id, 'reviewer')
            ->orderBy('created_at', 'desc');

        if ($tab === 'seen') {
            $query->where('is_read', true);
        } else {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate(20)->withQueryString();

        $unseenCount = Notification::forUser($reviewer->id, 'reviewer')->where('is_read', false)->count();
        $seenCount   = Notification::forUser($reviewer->id, 'reviewer')->where('is_read', true)->count();

        return view('reviewer.notifications.index', compact('notifications', 'tab', 'unseenCount', 'seenCount'));
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

        if ($notification->related_type === 'application' && $notification->related_id) {
            return redirect()->route('reviewer.applications.show', $notification->related_id);
        }

        return redirect()->back();
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

        return redirect()->route('reviewer.notifications.index', ['tab' => 'seen'])->with('success', 'All notifications marked as seen.');
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
