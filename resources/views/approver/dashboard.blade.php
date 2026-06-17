@extends('layouts.approver')

@section('title', 'Approver Dashboard')

@section('portal-name', 'Approver Portal')
@section('brand-icon', 'bi bi-person-check')
@section('dashboard-route', route('approver.dashboard'))
@section('user-name', Auth::guard('approver')->user()->name)
@section('user-role', 'Application Approver')
@section('user-initial', strtoupper(substr(Auth::guard('approver')->user()->name, 0, 1)))
@section('logout-route', route('approver.logout'))

@section('sidebar-menu')
    <a href="{{ route('approver.dashboard') }}" class="sidebar-menu-item active">
        <i class="bi bi-speedometer2"></i>
        <span>{{ __('approver.dashboard') }}</span>
    </a>
    <a href="{{ route('approver.assignedtome') }}" class="sidebar-menu-item">
        <i class="bi bi-inbox"></i>
        <span>{{ __('approver.assigned_to_me') }}</span>
    </a>
    <a href="{{ route('approver.myprofile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>{{ __('approver.my_profile') }}</span>
    </a>
    <a href="{{ route('approver.notifications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-bell"></i>
        <span>{{ __('approver.notifications') }}</span>
    </a>
@endsection

@section('custom-styles')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
        border-radius: 10px;
        padding: 1.5rem 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(26, 58, 107, 0.3);
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #d0daea;
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(26, 58, 107, 0.15);
        border-color: #2a5298;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 1rem;
    }

    .quick-action-btn {
        display: block;
        background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 1rem 1.5rem;
        width: 100%;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 58, 107, 0.4);
        color: white;
        text-decoration: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-1">
                    {{ __('approver.welcome') }}, {{ Auth::guard('approver')->user()->name }}!
                </h2>
                <p class="mb-0 opacity-75" style="font-size: 0.95rem;">{{ __('approver.user_role') }}</p>
            </div>
            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                <small class="opacity-90">
                    <span id="english-date"></span><br>
                    <span id="nepali-date"></span>
                </small>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(26,58,107,0.1); color: #1a3a6b;">
                    <i class="bi bi-inbox"></i>
                </div>
                <h3 class="fw-bold mb-0" style="color: #1a3a6b;">{{ $stats['pending_applications'] }}</h3>
                <p class="text-muted mb-0 small">{{ __('approver.pending_applications') }}</p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: #16a34a;">
                    <i class="bi bi-check-circle"></i>
                </div>
                <h3 class="fw-bold mb-0" style="color: #16a34a;">{{ $stats['approved_applications'] }}</h3>
                <p class="text-muted mb-0 small">{{ __('approver.approved') }}</p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: #dc2626;">
                    <i class="bi bi-x-circle"></i>
                </div>
                <h3 class="fw-bold mb-0" style="color: #dc2626;">{{ $stats['rejected_applications'] }}</h3>
                <p class="text-muted mb-0 small">{{ __('approver.rejected') }}</p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(42,82,152,0.1); color: #2a5298;">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <h3 class="fw-bold mb-0" style="color: #2a5298;">{{ $stats['total_applications'] }}</h3>
                <p class="text-muted mb-0 small">{{ __('approver.total_applications') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection