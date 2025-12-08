@extends('layouts.app')

@section('title', 'Candidate Dashboard')

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item active">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>Browse Jobs</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bookmark"></i>
        <span>Saved Jobs</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-pdf"></i>
        <span>Resume</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bell"></i>
        <span>Notifications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
    </a>
@endsection

@section('custom-styles')
    <style>
        .job-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 4px solid transparent;
        }

        .job-card:hover {
            border-left-color: #10b981;
            transform: translateX(5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .company-logo {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .btn-apply {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .badge-custom {
            padding: 0.35rem 0.65rem;
            font-weight: 500;
            font-size: 0.75rem;
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header mb-4">
        <h1 class="page-title">Welcome back, {{ session('candidate_name') }}! 🎯</h1>
        <p class="page-subtitle">Track your applications and discover new opportunities that match your skills.</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100 shadow-sm">
                <div class="stat-icon blue">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
                <h3 class="h2 fw-bold mb-1">8</h3>
                <p class="text-muted mb-2">Total Applications</p>
                <small class="text-info">
                    <i class="bi bi-info-circle me-1"></i>All time
                </small>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100 shadow-sm">
                <div class="stat-icon orange">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <h3 class="h2 fw-bold mb-1">3</h3>
                <p class="text-muted mb-2">Pending Review</p>
                <small class="text-warning">
                    <i class="bi bi-clock me-1"></i>In progress
                </small>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100 shadow-sm">
                <div class="stat-icon emerald">
                    <i class="bi bi-star-fill"></i>
                </div>
                <h3 class="h2 fw-bold mb-1">2</h3>
                <p class="text-muted mb-2">Shortlisted</p>
                <small class="text-success">
                    <i class="bi bi-check-circle me-1"></i>Great news!
                </small>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100 shadow-sm">
                <div class="stat-icon slate">
                    <i class="bi bi-bookmark-fill"></i>
                </div>
                <h3 class="h2 fw-bold mb-1">12</h3>
                <p class="text-muted mb-2">Saved Jobs</p>
                <small class="text-muted">
                    <i class="bi bi-heart me-1"></i>For later
                </small>
            </div>
        </div>
    </div>
@endsection