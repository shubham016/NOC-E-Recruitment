@extends('layouts.dashboard')

@section('title', 'HR Administrators Management')

@section('portal-name', 'Admin Portal')
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()->name)
@section('user-role', 'System Administrator')
@section('user-initial', strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.jobs.create') }}" class="sidebar-menu-item">
        <i class="bi bi-briefcase"></i>
        <span>Post Vacancy</span>
    </a>
    <a href="{{ route('admin.jobs.create') }}" class="sidebar-menu-item">
        <i class="bi bi-briefcase"></i>
        <span>Post Vacancy</span>
    </a>
    {{-- <a href="{{ route('admin.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>Vacancy List</span>
        <span class="badge bg-primary ms-auto">{{ $stats['total_jobs'] }}</span>
    </a> --}}
    <a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>Applications</span>
    </a>
    <a href="{{ route('admin.candidates.index') }}" class="sidebar-menu-item">
        <i class="bi bi-people"></i>
        <span>Candidates</span>
    </a>
    <a href="{{ route('admin.hr-administrators.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-person-badge"></i>
        <span>HR Administrators</span>
    </a>
    <a href="{{ route('admin.reviewers.index') }}" class="sidebar-menu-item">
        <i class="bi bi-person-check"></i>
        <span>Reviewers</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bar-chart"></i>
        <span>Reports</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
    </a>
@endsection

@section('content')
    <style>
        /* Modern Container */
        .modern-container {
            background: linear-gradient(to bottom, #eff6ff 0%, #ffffff 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        /* Hero Header */
        .hero-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);
            border-radius: 20px;
            padding: 3rem 2.5rem;
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(30, 58, 138, 0.2);
        }

        .hero-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 900;
            color: white;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .hero-title i {
            background: rgba(255, 255, 255, 0.2);
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }

        .hero-subtitle {
            font-size: 1.125rem;
            color: rgba(255, 255, 255, 0.95);
            margin: 0;
            font-weight: 400;
        }

        .hero-cta {
            background: white;
            color: #1e3a8a;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .hero-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            color: #2563eb;
        }

        /* Alert Styles */
        .modern-alert {
            border: none;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            animation: slideDown 0.4s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success-modern {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .alert-error-modern {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        .alert-icon-modern {
            font-size: 2rem;
            flex-shrink: 0;
        }

        .alert-close-modern {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: inherit;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
            flex-shrink: 0;
        }

        .alert-close-modern:hover {
            opacity: 1;
        }

        /* Premium Stats Grid */
        .premium-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .premium-stat-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .premium-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #3b82f6, #2563eb);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .premium-stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(59, 130, 246, 0.15);
            border-color: #3b82f6;
        }

        .premium-stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-icon-wrapper {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.25rem;
            position: relative;
        }

        .stat-icon-wrapper::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 14px;
            background: inherit;
            opacity: 0.2;
            filter: blur(8px);
        }

        .stat-number {
            font-size: 2.75rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            font-size: 0.9375rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Advanced Filters */
        .advanced-filters {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border: 1px solid #f3f4f6;
        }

        .filters-heading {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1.25rem;
            border-bottom: 2px solid #f3f4f6;
        }

        .filters-icon-badge {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .filters-heading-text h3 {
            font-size: 1.375rem;
            font-weight: 800;
            color: #1e3a8a;
            margin: 0;
        }

        .filters-heading-text p {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }

        .filter-field {
            margin-bottom: 0;
            display: flex;
            flex-direction: column;
        }

        .filter-field label {
            font-weight: 700;
            color: #374151;
            margin-bottom: 0.625rem;
            font-size: 0.875rem;
            display: block;
            height: 20px;
            line-height: 20px;
        }

        .filter-field input,
        .filter-field select {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            transition: all 0.2s;
            width: 100%;
            height: 48px;
        }

        .filter-field input:focus,
        .filter-field select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .filter-actions-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            height: 48px;
            margin-top: auto;
        }

        .btn-search-modern {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 0 2rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9375rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            height: 48px;
            flex: 1;
            white-space: nowrap;
        }

        .btn-search-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        .btn-reset-modern {
            background: #f3f4f6;
            color: #374151;
            border: 2px solid #e5e7eb;
            padding: 0;
            border-radius: 10px;
            transition: all 0.2s;
            width: 48px;
            height: 48px;
            min-width: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            flex-shrink: 0;
            text-decoration: none;
        }

        .btn-reset-modern:hover {
            background: #e5e7eb;
            border-color: #d1d5db;
            color: #374151;
        }

        /* Administrators Table View */
        .administrators-table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.1);
        }

        .table-header {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            padding: 1.75rem 2rem;
            border-bottom: 2px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 1.375rem;
            font-weight: 800;
            color: #1e3a8a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .table-title i {
            font-size: 1.5rem;
        }

        .total-badge {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 25px;
            font-size: 0.9375rem;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        /* Modern Table */
        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }

        .modern-table thead {
            background: #f9fafb;
        }

        .modern-table thead th {
            padding: 1.25rem 1.5rem;
            font-weight: 700;
            color: #374151;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 2px solid #e5e7eb;
            white-space: nowrap;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        }

        .modern-table tbody td {
            padding: 1.5rem 1.5rem;
            color: #1f2937;
            border: 2px solid #e5e7eb;
            vertical-align: middle;
        }

        .modern-table tbody tr {
            transition: all 0.2s;
        }

        .modern-table tbody tr:hover {
            background: #f9fafb;
        }

        /* Administrator Cell */
        .administrator-cell {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .administrator-avatar-table {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #e5e7eb;
            flex-shrink: 0;
        }

        .administrator-avatar-placeholder-table {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1.5rem;
            flex-shrink: 0;
            border: 2px solid #e5e7eb;
        }

        .administrator-info-table {
            min-width: 0;
        }

        .administrator-name-table {
            font-weight: 700;
            color: #1e3a8a;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .administrator-email-table {
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* Contact Cell */
        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.375rem;
            font-size: 0.9375rem;
        }

        .contact-item:last-child {
            margin-bottom: 0;
        }

        .contact-icon {
            color: #2563eb;
            font-size: 0.875rem;
        }

        /* Status Badge Table */
        .status-badge-table {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 700;
        }

        .status-active-table {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 2px solid #6ee7b7;
        }

        .status-inactive-table {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: #6b7280;
            border: 2px solid #d1d5db;
        }

        .status-dot-table {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
        }

        /* Action Buttons Table */
        .actions-cell {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .action-btn-table {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9375rem;
            color: #6b7280;
            text-decoration: none;
        }

        .action-btn-table:hover {
            transform: scale(1.15);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .action-view-table:hover {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .action-edit-table:hover {
            background: #f59e0b;
            border-color: #f59e0b;
            color: white;
        }

        .action-toggle-table:hover {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }

        .action-delete-table:hover {
            background: #ef4444;
            border-color: #ef4444;
            color: white;
        }

        /* Pagination */
        .pagination-premium {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            padding: 1.75rem 2rem;
            border-top: 2px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pagination-info-premium {
            font-size: 0.9375rem;
            color: #6b7280;
            font-weight: 600;
        }

        .pagination-info-premium strong {
            color: #1e3a8a;
            font-weight: 800;
        }

        /* Premium Empty State */
        .empty-state-premium {
            text-align: center;
            padding: 5rem 2rem;
        }

        .empty-icon-premium {
            width: 140px;
            height: 140px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 5rem;
            position: relative;
        }

        .empty-icon-premium::after {
            content: '';
            position: absolute;
            inset: -12px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            opacity: 0.3;
            filter: blur(20px);
        }

        .empty-title-premium {
            font-size: 2rem;
            font-weight: 900;
            color: #1e3a8a;
            margin-bottom: 1rem;
        }

        .empty-text-premium {
            font-size: 1.125rem;
            color: #6b7280;
            margin-bottom: 2.5rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .btn-empty-action-premium {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1.125rem;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-empty-action-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modern-container {
                padding: 1rem;
            }

            .hero-header {
                padding: 2rem 1.5rem;
            }

            .hero-title {
                font-size: 1.75rem;
            }

            .premium-stats {
                grid-template-columns: 1fr;
            }

            .modern-table {
                font-size: 0.875rem;
            }

            .modern-table thead th,
            .modern-table tbody td {
                padding: 1rem;
            }

            .pagination-premium {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>

    <div class="modern-container">
        <!-- Hero Header -->
        <div class="hero-header">
            <div class="row align-items-center hero-content">
                <div class="col-lg-8">
                    <h1 class="hero-title">
                        <i class="bi bi-person-badge-fill"></i>
                        HR Administrators Management
                    </h1>
                    <p class="hero-subtitle">
                        Manage HR Administrator accounts, permissions, and access control
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="{{ route('admin.hr-administrators.create') }}" class="hero-cta">
                        <i class="bi bi-plus-circle-fill"></i>
                        Create Administrator
                    </a>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="modern-alert alert-success-modern" id="successAlert">
                <i class="bi bi-check-circle-fill alert-icon-modern"></i>
                <div class="flex-grow-1">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
                <button class="alert-close-modern" onclick="this.parentElement.remove()">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="modern-alert alert-error-modern" id="errorAlert">
                <i class="bi bi-exclamation-triangle-fill alert-icon-modern"></i>
                <div class="flex-grow-1">
                    <strong>Error!</strong> {{ session('error') }}
                </div>
                <button class="alert-close-modern" onclick="this.parentElement.remove()">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        @endif

        <!-- Premium Statistics -->
        <div class="premium-stats">
            <div class="premium-stat-card">
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); color: #1e40af;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-number">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Administrators</div>
            </div>
            <div class="premium-stat-card">
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-number">{{ $stats['active'] }}</div>
                <div class="stat-label">Active Administrators</div>
            </div>
            <div class="premium-stat-card">
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #991b1b;">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="stat-number">{{ $stats['inactive'] }}</div>
                <div class="stat-label">Inactive Administrators</div>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="advanced-filters">
            <div class="filters-heading">
                <div class="filters-icon-badge">
                    <i class="bi bi-sliders"></i>
                </div>
                <div class="filters-heading-text">
                    <h3>Advanced Filters</h3>
                    <p>Refine your search with multiple criteria</p>
                </div>
            </div>
            <form action="{{ route('admin.hr-administrators.index') }}" method="GET">
                <div class="row g-3">
                    <!-- Search Query -->
                    <div class="col-lg-4 col-md-6">
                        <div class="filter-field">
                            <label>Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Name, email, phone..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-lg-3 col-md-6">
                        <div class="filter-field">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="col-lg-3 col-md-12">
                        <div class="filter-field">
                            <label>&nbsp;</label>
                            <div class="filter-actions-wrapper">
                                <button type="submit" class="btn-search-modern">
                                    <i class="bi bi-search"></i>
                                    Search
                                </button>
                                <a href="{{ route('admin.hr-administrators.index') }}" class="btn-reset-modern">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Administrators Table -->
        <div class="administrators-table-container">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="bi bi-table"></i>
                    All Administrators
                </h3>
                <span class="total-badge">{{ $administrators->total() }} Total</span>
            </div>

            @if($administrators->count() > 0)
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">S.N</th>
                                <th style="width: 300px;">Administrator</th>
                                <th style="width: 250px;">Contact</th>
                                <th class="text-center" style="width: 150px;">Registered</th>
                                <th class="text-center" style="width: 150px;">Status</th>
                                <th class="text-center" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($administrators as $index => $admin)
                                <tr>
                                    <td class="text-center">
                                        <strong style="color: #2563eb;">{{ $administrators->firstItem() + $index }}</strong>
                                    </td>
                                    <td>
                                        <div class="administrator-cell">
                                            @if($admin->photo)
                                                <img src="{{ asset('storage/' . $admin->photo) }}" alt="{{ $admin->name }}" class="administrator-avatar-table">
                                            @else
                                                <div class="administrator-avatar-placeholder-table">
                                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div class="administrator-info-table">
                                                <div class="administrator-name-table">{{ $admin->name }}</div>
                                                <div class="administrator-email-table">ID: {{ $admin->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="contact-item">
                                            <i class="bi bi-envelope-fill contact-icon"></i>
                                            <span>{{ $admin->email }}</span>
                                        </div>
                                        @if($admin->phone)
                                            <div class="contact-item">
                                                <i class="bi bi-telephone-fill contact-icon"></i>
                                                <span>{{ $admin->phone }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div style="font-weight: 600; color: #374151;">{{ $admin->created_at->format('M d, Y') }}</div>
                                        <div style="font-size: 0.875rem; color: #6b7280;">{{ $admin->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="status-badge-table {{ $admin->status == 'active' ? 'status-active-table' : 'status-inactive-table' }}">
                                            <span class="status-dot-table"></span>
                                            {{ ucfirst($admin->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions-cell">
                                            <a href="{{ route('admin.hr-administrators.show', $admin->id) }}" class="action-btn-table action-view-table" title="View">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="{{ route('admin.hr-administrators.edit', $admin->id) }}" class="action-btn-table action-edit-table" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <button type="button" class="action-btn-table action-toggle-table" data-bs-toggle="modal" data-bs-target="#toggleModal{{ $admin->id }}" title="Toggle">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                            <button type="button" class="action-btn-table action-delete-table" onclick="deleteAdmin({{ $admin->id }})" title="Delete">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Toggle Status Modal -->
                                <div class="modal fade" id="toggleModal{{ $admin->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                            <form action="{{ route('admin.hr-administrators.toggle-status', $admin->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-bottom" style="padding: 1.5rem;">
                                                    <h5 class="modal-title fw-bold" style="color: #1e3a8a;">
                                                        <i class="bi bi-arrow-repeat me-2"></i>Change Administrator Status
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <p class="mb-3" style="font-size: 1.0625rem;">
                                                        Are you sure you want to {{ $admin->status == 'active' ? 'deactivate' : 'activate' }} 
                                                        <strong>{{ $admin->name }}</strong>?
                                                    </p>
                                                    @if($admin->status == 'active')
                                                        <div class="alert alert-warning mb-0">
                                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                                            <small>This administrator will not be able to log in once deactivated.</small>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer border-top" style="padding: 1rem 1.5rem;">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="bi bi-x-circle me-2"></i>Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-{{ $admin->status == 'active' ? 'warning' : 'success' }}">
                                                        <i class="bi bi-check-circle me-2"></i>
                                                        {{ $admin->status == 'active' ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-premium">
                    <div class="pagination-info-premium">
                        Showing <strong>{{ $administrators->firstItem() }}</strong> to 
                        <strong>{{ $administrators->lastItem() }}</strong> of 
                        <strong>{{ $administrators->total() }}</strong> administrators
                    </div>
                    <div class="pagination-links">
                        {{ $administrators->links() }}
                    </div>
                </div>
            @else
                <div class="empty-state-premium">
                    <div class="empty-icon-premium">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <h4 class="empty-title-premium">No Administrators Found</h4>
                    <p class="empty-text-premium">
                        @if(request()->hasAny(['search', 'status']))
                            No administrators match your current filters. Try adjusting your search criteria to find what you're looking for.
                        @else
                            You haven't added any HR administrators yet. Create your first administrator to get started.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.hr-administrators.index') }}" class="btn-empty-action-premium">
                            <i class="bi bi-arrow-clockwise"></i>
                            Clear All Filters
                        </a>
                    @else
                        <a href="{{ route('admin.hr-administrators.create') }}" class="btn-empty-action-premium">
                            <i class="bi bi-plus-circle-fill"></i>
                            Create First Administrator
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Forms -->
    @foreach($administrators as $admin)
        <form id="deleteForm{{ $admin->id }}" action="{{ route('admin.hr-administrators.destroy', $admin->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

@endsection

@section('scripts')
    <script>
        function deleteAdmin(id) {
            if (confirm('⚠️ Are you sure you want to delete this administrator?\n\nThis action cannot be undone and will remove all associated data.')) {
                document.getElementById('deleteForm' + id).submit();
            }
        }

        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');

            if (successAlert) {
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 300);
            }

            if (errorAlert) {
                errorAlert.style.opacity = '0';
                setTimeout(() => errorAlert.remove(), 300);
            }
        }, 5000);
    </script>
@endsection