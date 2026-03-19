<?php

namespace App\Http\Controllers\HrAdministrator;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:hr_administrator');
    }

    public function index()
    {
        $hrAdminId = Auth::guard('hr_administrator')->id();

        // Get unread notification IDs before marking as read
        $unreadIds = Notification::forUser($hrAdminId, 'hr_administrator')
            ->unread()
            ->pluck('id')
            ->toArray();

        // Automatically mark all unread notifications as read when viewing the page
        if (!empty($unreadIds)) {
            Notification::forUser($hrAdminId, 'hr_administrator')
                ->unread()
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

            \Log::info('HR Admin viewed notifications - auto marked as read', [
                'hr_admin_id' => $hrAdminId,
                'marked_count' => count($unreadIds),
            ]);
        }

        $notifications = Notification::forUser($hrAdminId, 'hr_administrator')
            ->latest()
            ->paginate(15);

        return view('hr-administrator.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::forUser(Auth::guard('hr_administrator')->id(), 'hr_administrator')
            ->findOrFail($id);

        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        Notification::forUser(Auth::guard('hr_administrator')->id(), 'hr_administrator')
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function destroy($id)
    {
        $notification = Notification::forUser(Auth::guard('hr_administrator')->id(), 'hr_administrator')
            ->findOrFail($id);

        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted.');
    }
}
