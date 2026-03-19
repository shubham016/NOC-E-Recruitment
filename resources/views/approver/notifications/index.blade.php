@extends('layouts.app')

@section('title', 'Notifications')

@section('portal-name', 'Approver Portal')
@section('brand-icon', 'bi bi-person-check')
@section('dashboard-route', route('approver.dashboard'))
@section('user-name', Auth::guard('approver')->user()->name)
@section('user-role', 'Application Approver')
@section('user-initial', strtoupper(substr(Auth::guard('approver')->user()->name, 0, 1)))
@section('logout-route', route('approver.logout'))

@section('sidebar-menu')
    <a href="{{ route('approver.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('approver.assignedtome') }}" class="sidebar-menu-item">
        <i class="bi bi-inbox"></i>
        <span>Assigned to Me</span>
    </a>
    <a href="{{ route('approver.notifications.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-bell"></i>
        <span>Notifications</span>
    </a>
@endsection

@section('custom-styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(201, 168, 76, 0.3);
    }

    .notification-item {
        background: white;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 0.75rem;
        border-left: 4px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .notification-item:hover {
        transform: translateX(4px);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    }

    .notification-item.unread {
        background: #fef3c7;
        border-left-color: #c9a84c;
    }

    .notification-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1f2937;
    }

    .notification-message {
        color: #6b7280;
        font-size: 0.9rem;
    }

    .notification-time {
        color: #9ca3af;
        font-size: 0.85rem;
    }

    .btn-gold {
        background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
        color: white;
        border: none;
    }

    .btn-gold:hover {
        background: linear-gradient(135deg, #a07828 0%, #c9a84c 100%);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-1">
                    <i class="bi bi-bell me-2"></i>Notifications
                </h3>
                <p class="mb-0 opacity-90 small">Manage your notifications</p>
            </div>
            <div>
                <form action="{{ route('approver.notifications.markAllAsRead') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm">
                        <i class="bi bi-check-all me-1"></i>Mark All as Read
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Notifications List -->
    <div class="notifications-list">
        @forelse($notifications as $notification)
            <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="notification-title">
                            {{ $notification->title }}
                        </div>
                        <div class="notification-message">
                            {{ $notification->message }}
                        </div>
                        <div class="notification-time mt-2">
                            <i class="bi bi-clock me-1"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        @if(!$notification->is_read)
                            <form action="{{ route('approver.notifications.markAsRead', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-gold" title="Mark as read">
                                    <i class="bi bi-check"></i>
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('approver.notifications.destroy', $notification->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-bell-slash fs-1 text-muted d-block mb-3"></i>
                <p class="text-muted">No notifications found</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
