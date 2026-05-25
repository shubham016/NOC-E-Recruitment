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
    public function index(Request $request)
    {
        $approver = Auth::guard('approver')->user();

        $tab = $request->get('tab', 'unseen');

        $query = Notification::forUser($approver->id, 'approver')
            ->orderBy('created_at', 'desc');

        if ($tab === 'seen') {
            $query->where('is_read', true);
        } else {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate(20)->withQueryString();

        $unseenCount = Notification::forUser($approver->id, 'approver')->where('is_read', false)->count();
        $seenCount   = Notification::forUser($approver->id, 'approver')->where('is_read', true)->count();

        return view('approver.notifications.index', compact('notifications', 'tab', 'unseenCount', 'seenCount'));
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

        if ($notification->related_type === 'application' && $notification->related_id) {
            return redirect()->route('approver.assignedtome');
        }

        return redirect()->back();
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

        return redirect()->route('approver.notifications.index', ['tab' => 'seen'])->with('success', 'All notifications marked as seen.');
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
