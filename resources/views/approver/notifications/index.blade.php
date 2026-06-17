@extends('layouts.approver')

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
        <span>{{ __('reviewer.dashboard') }}</span>
    </a>
    <a href="{{ route('approver.assignedtome') }}" class="sidebar-menu-item">
        <i class="bi bi-inbox"></i>
        <span>{{ __('reviewer.assigned_to_me') }}</span>
    </a>
    <a href="{{ route('approver.myprofile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>{{ __('approver.my_profile') }}</span>
    </a>
    <a href="{{ route('approver.notifications.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-bell"></i>
        <span>{{ __('approver.notifications') }}</span>
    </a>
@endsection

@section('custom-styles')
<style>
    .notification-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .notification-card:hover {
        border-left-color: #173361;
        transform: translateX(5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .notification-card.unread {
        background-color: #dfe6ed;
        border-left-color: #173361;
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
        color: #173361;
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
    <div class="mb-3">
        <h2 class="mb-1">
            {{ __('approver.notifications') }}
        </h2>
        <p class="text-muted mb-0">{{ __('approver.notifications_description') }}</p>
    </div>

    <!-- Unseen / Seen Tabs + Mark All As Seen -->
    <div class="d-flex justify-content-between align-items-center mb-4" style="border-bottom: 2px solid #dee2e6;">
        <ul class="nav nav-tabs border-0 mb-0">
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'unseen' ? 'active fw-semibold' : 'text-muted' }}"
                   href="{{ route('approver.notifications.index', ['tab' => 'unseen']) }}"
                   style="{{ $tab === 'unseen' ? 'color:#173361 !important; border-bottom: 2px solid #173361; border-top:none; border-left:none; border-right:none; background:none;' : '' }}">
                    {{ __('approver.unseen') }}
                    @if($unseenCount > 0)
                        <span class="badge bg-primary ms-1" style="font-size:0.65rem;">{{ $unseenCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'seen' ? 'active fw-semibold' : 'text-muted' }}"
                   href="{{ route('approver.notifications.index', ['tab' => 'seen']) }}"
                   style="{{ $tab === 'seen' ? 'color:#173361 !important; border-bottom: 2px solid #173361; border-top:none; border-left:none; border-right:none; background:none;' : '' }}">
                    {{ __('approver.seen') }}
                </a>
            </li>
        </ul>
        <form method="POST" action="{{ route('approver.notifications.markAllAsRead') }}" class="d-inline mb-1">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-secondary fw-semibold" style="letter-spacing:0.05em;" {{ $unseenCount === 0 ? 'disabled' : '' }}>
                {{ __('reviewer.mark_all_as_seen') }}
            </button>
        </form>
    </div>

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
                                    @if($notification->type === 'application_assigned') success
                                    @elseif($notification->type === 'application_resubmitted') info
                                    @else info
                                    @endif">
                                    @if($notification->type === 'application_assigned')
                                        <i class="bi bi-clipboard-check-fill"></i>
                                    @elseif($notification->type === 'application_resubmitted')
                                        <i class="bi bi-arrow-repeat"></i>
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
                                                <span class="badge bg-primary text-white">{{ __('reviewer.new') }}</span>
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
                                            <form method="POST" action="{{ route('approver.notifications.markAsRead', $notification->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary">{{ __('reviewer.view_application') }}</button>
                                            </form>
                                        @elseif(!$notification->is_read)
                                            <form method="POST" action="{{ route('approver.notifications.markAsRead', $notification->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">{{ __('reviewer.mark_as_read') }}</button>
                                            </form>
                                        @endif
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
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    @else
        <!-- Empty State -->
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-bell-slash display-1 text-muted mb-3"></i>
                <h4 class="text-muted">
                    {{ $tab === 'seen' ? __('reviewer.no_seen_notifications') : __('reviewer.no_unseen_notifications') }}
                </h4>
                <p class="text-secondary">
                    {{ $tab === 'seen' ? __('reviewer.no_seen_notifications_description') : __('reviewer.no_unseen_notifications_description') }}
                </p>
                <a href="{{ route('approver.dashboard') }}" class="btn btn-danger mt-3">
                    {{ __('reviewer.back_to_dashboard') }}
                </a>
            </div>
        </div>
    @endif
@endsection
