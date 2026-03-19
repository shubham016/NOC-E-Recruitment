<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display notifications for the approver
     */
    public function index()
    {
        $approver = Auth::guard('approver')->user();

        $notifications = Notification::where('user_type', 'approver')
            ->where('user_id', $approver->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('approver.notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $approver = Auth::guard('approver')->user();

        $notification = Notification::where('user_type', 'approver')
            ->where('user_id', $approver->id)
            ->findOrFail($id);

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $approver = Auth::guard('approver')->user();

        Notification::where('user_type', 'approver')
            ->where('user_id', $approver->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'All notifications marked as read');
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $approver = Auth::guard('approver')->user();

        $notification = Notification::where('user_type', 'approver')
            ->where('user_id', $approver->id)
            ->findOrFail($id);

        $notification->delete();

        return back()->with('success', 'Notification deleted');
    }
}
