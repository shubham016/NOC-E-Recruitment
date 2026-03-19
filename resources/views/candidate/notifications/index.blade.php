@extends('layouts.app')

@section('title', 'Notifications')

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>Vacancy</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="{{ route('candidate.notifications.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-bell"></i>
        <span>Notifications</span>
    </a>
    <a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-check"></i>
        <span>View Result</span>
    </a>
    <a href="{{ route('candidate.admit-card') }}" class="sidebar-menu-item">
        <i class="bi bi-box-arrow-down"></i>
        <span>Download Admit Card</span>
    </a>
    <a href="{{ route('candidate.change-password') }}" class="sidebar-menu-item">
        <i class="bi bi-lock"></i>
        <span>Change Password</span>
    </a>
@endsection

@section('custom-styles')
    <style>
        .notification-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .notification-card:hover {
            border-left-color: #c9a84c;
            transform: translateX(5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .notification-card.unread {
            background-color: #fffbf0;
            border-left-color: #c9a84c;
        }

        .notification-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .notification-icon.info {
            background: linear-gradient(135deg, rgba(201, 168, 76, 0.15), rgba(160, 120, 40, 0.1));
            color: #a07828;
        }

        .notification-icon.success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.1));
            color: #059669;
        }

        .notification-icon.danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(220, 38, 38, 0.1));
            color: #dc2626;
        }

        .notification-time {
            font-size: 0.75rem;
            color: #9ca3af;
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                Notifications
                <span class="badge bg-primary" style="font-size: 0.6rem; vertical-align: middle;">CANDIDATE PORTAL</span>
            </h2>
            <p class="text-muted mb-0">View and manage your notifications</p>
        </div>
        @if($notifications->where('is_read', false)->count() > 0)
            <form method="POST" action="{{ route('candidate.notifications.markAllAsRead') }}" class="d-inline">
                @csrf
                <button type="submit" class="in btn-sm btn-outline-secondary">
                    <i class="bi bi-check2-all"></i> Mark All as Read
                </button>
            </form>
        @endif
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Notifications List -->
    @if($notifications->count() > 0)
        <div class="row">
            <div class="col-12">
                @foreach($notifications as $notification)
                    <div class="card notification-card mb-3 {{ $notification->is_read ? '' : 'unread' }}">
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <!-- Notification Icon -->
                                <div class="notification-icon
                                    @if($notification->type === 'application_approved') success
                                    @elseif($notification->type === 'application_rejected') danger
                                    @else info
                                    @endif">
                                    @if($notification->type === 'application_sent_back')
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    @elseif($notification->type === 'application_approved')
                                        <i class="bi bi-check-circle-fill"></i>
                                    @elseif($notification->type === 'application_rejected')
                                        <i class="bi bi-x-circle-fill"></i>
                                    @elseif($notification->type === 'application_reviewed')
                                        <i class="bi bi-eye-fill"></i>
                                    @elseif($notification->type === 'reviewer_assigned')
                                        <i class="bi bi-person-check-fill"></i>
                                    @else
                                        <i class="bi bi-bell-fill"></i>
                                    @endif
                                </div>

                                <!-- Notification Content -->
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0 fw-semibold">{{ $notification->title }}</h6>
                                        <div class="d-flex gap-2 align-items-center">
                                            @if(!$notification->is_read)
                                                <span class="badge bg-warning text-dark">New</span>
                                            @endif
                                            <span class="notification-time">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-muted mb-3">{{ $notification->message }}</p>

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2">
                                        @if($notification->related_type === 'application' && $notification->related_id)
                                            <a href="{{ route('candidate.applications.show', $notification->related_id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> View Application
                                            </a>
                                        @endif

                                        @if(!$notification->is_read)
                                            <form method="POST" action="{{ route('candidate.notifications.markAsRead', $notification->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-check"></i> Mark as Read
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('candidate.notifications.destroy', $notification->id) }}" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this notification?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-bell-slash display-1 text-muted mb-3"></i>
                <h4 class="text-muted">No Notifications Available</h4>
                <p class="text-secondary">You don't have any notifications at the moment.</p>
                <a href="{{ route('candidate.dashboard') }}" class="btn btn-danger mt-3">
                    <i class="bi bi-house-door"></i> Back to Dashboard
                </a>
            </div>
        </div>
    @endif
@endsection
