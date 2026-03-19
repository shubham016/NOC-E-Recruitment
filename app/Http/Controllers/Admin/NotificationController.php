<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $adminId = Auth::guard('admin')->id();

        // Get unread notification IDs before marking as read
        $unreadIds = Notification::forUser($adminId, 'admin')
            ->unread()
            ->pluck('id')
            ->toArray();

        // Automatically mark all unread notifications as read when viewing the page
        if (!empty($unreadIds)) {
            Notification::forUser($adminId, 'admin')
                ->unread()
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

            \Log::info('Admin viewed notifications - auto marked as read', [
                'admin_id' => $adminId,
                'marked_count' => count($unreadIds),
            ]);
        }

        $notifications = Notification::forUser($adminId, 'admin')
            ->latest()
            ->paginate(15);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::forUser(Auth::guard('admin')->id(), 'admin')
            ->findOrFail($id);

        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        Notification::forUser(Auth::guard('admin')->id(), 'admin')
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function destroy($id)
    {
        $notification = Notification::forUser(Auth::guard('admin')->id(), 'admin')
            ->findOrFail($id);

        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted.');
    }
}
