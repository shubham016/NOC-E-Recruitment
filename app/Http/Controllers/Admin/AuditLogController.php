<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::query()->latest('attempted_at');

        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('user_identifier', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('attempted_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('attempted_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(25)->withQueryString();

        $stats = [
            'total' => AuditLog::count(),
            'successful_logins' => AuditLog::where('action', 'login')->where('status', 'success')->count(),
            'failed_logins' => AuditLog::where('action', 'login')->where('status', 'failed')->count(),
            'logouts' => AuditLog::where('action', 'logout')->where('status', 'success')->count(),
        ];

        return view('admin.audit.index', compact('logs', 'stats'));
    }
}
