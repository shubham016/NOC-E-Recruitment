@extends('layouts.dashboard')

@section('title', __('admin.notifications'))

@section('portal-name', __('admin.portal_name'))
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()->name)
@section('user-role', __('admin.system_administrator'))
@section('user-initial', strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('content')
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

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                {{ __('admin.notifications') }}
                <span class="badge bg-danger" style="font-size: 0.6rem; vertical-align: middle;">ADMIN PORTAL</span>
            </h2>
            <p class="text-muted mb-0">{{ __('admin.view_manage_notifications') }}</p>
        </div>
        @if($notifications->where('is_read', false)->count() > 0)
            <form method="POST" action="{{ route('admin.notifications.markAllAsRead') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-check2-all"></i> {{ __('admin.mark_all_as_read') }}
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
                                    @if(str_contains($notification->type, 'approved')) success
                                    @elseif(str_contains($notification->type, 'rejected')) danger
                                    @else info
                                    @endif">
                                    @if(str_contains($notification->type, 'application'))
                                        <i class="bi bi-file-earmark-text-fill"></i>
                                    @elseif(str_contains($notification->type, 'reviewer'))
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
                                                <span class="badge bg-warning text-dark">{{ __('admin.new') }}</span>
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
                                            <a href="{{ route('admin.applications.show', $notification->related_id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> {{ __('admin.view_application') }}
                                            </a>
                                        @endif

                                        @if(!$notification->is_read)
                                            <form method="POST" action="{{ route('admin.notifications.markAsRead', $notification->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-check"></i> {{ __('admin.mark_as_read') }}
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.notifications.destroy', $notification->id) }}" class="d-inline"
                                              onsubmit="return confirm('{{ __('admin.delete_notification_confirm') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> {{ __('admin.delete') }}
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
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    @else
        <!-- Empty State -->
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-bell-slash display-1 text-muted mb-3"></i>
                <h4 class="text-muted">{{ __('admin.no_notifications') }}</h4>
                <p class="text-secondary">{{ __('admin.no_notifications_now') }}</p>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-house-door"></i> {{ __('admin.back_to_dashboard') }}
                </a>
            </div>
        </div>
    @endif
@endsection
