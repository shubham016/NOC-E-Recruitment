<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ __('admin.system_title') }}</title>
    <link rel="icon" href="{{ asset('images/noc_logo_tab.png') }}" type="image/png">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Nepali Datepicker CSS -->
    <link href="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/css/nepali.datepicker.v5.0.6.min.css"
        rel="stylesheet" type="text/css" />

    <style>
        /* Raise picker calendar above sidebar (z-index:1020) and navbar (z-index:1030) */
        .ndp-container { z-index: 9999 !important; }

        .nav-tabs .nav-link {
            color: #a07828 !important;
        }

        /* Make circles smaller */
        .tab-circle {
            width: 25px !important;
            height: 25px !important;
            font-size: 14px;
        }

        .tab-label {
            font-size: 12px !important;
        }

        /* Notification icon styling */
        .notification-link {
            display: inline-flex !important;
            align-items: center !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .notification-link .bi-bell {
            font-size: 1rem;
            line-height: 1.5;
        }

        .notification-badge {
            position: absolute;
            top: 6px;
            right: -2px;
            font-size: 0.6rem;
            padding: 0.15em 0.35em;
            min-width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        /* Bell badge: smaller circle, number centered, shifted left */
        .notification-link .badge.translate-middle {
            width: 14px !important;
            height: 14px !important;
            min-width: 12px !important;
            font-size: 0.5rem !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transform: translate(-128%, 44%) !important;
        }

        .tab-item {
            font-size: 14px;
            margin-right: -20px;
        }

        .tab-item:last-child {
            margin-right: 0px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        /* Make all form elements smaller */
        .card-body {
            font-size: 13px;
        }

        .card-body .form-control {
            font-size: 13px;
            padding: 0.4rem 0.6rem;
            height: calc(1.5em + 0.8rem + 2px);
        }

        .card-body .form-select {
            font-size: 13px;
            padding: 0.4rem 0.6rem;
        }

        .card-body label {
            font-size: 13px;
            margin-bottom: 0.3rem;
        }

        /* Top Navbar - light warm white with gold bottom border */
        .navbar {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 1030;
            transition: padding-left 0.3s ease;
            background: linear-gradient(90deg, #ffffff 0%, #fdf9f2 100%) !important;
        }

        /* NOC Logo and Brand Styles */
        .noc-brand-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .noc-logo {
            height: 50px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .noc-info h5 {
            margin: 0;
            font-size: 17px;
            font-weight: 600;
            color: #1a2a4a;
            line-height: 1.2;
        }

        .noc-info p {
            margin: 0;
            font-size: 13px;
            color: #555;
            line-height: 1.2;
        }

        .noc-info small {
            font-size: 11px;
            color: #c9a84c;
            font-style: italic;
            display: block;
            margin-top: 2px;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle-btn {
            background: rgba(201, 168, 76, 0.1);
            border: 1px solid rgba(201, 168, 76, 0.35);
            color: #a07828;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            transition: background-color 0.2s ease;
            margin-right: 1rem;
        }

        .sidebar-toggle-btn:hover {
            background: rgba(201, 168, 76, 0.2);
        }

        /* Layout Container */
        .layout-container {
            display: flex;
            min-height: calc(100vh - 70px);
            transition: margin-left 0.3s ease;
        }

        /* Sidebar - light warm grey */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #f7f5f0 0%, #f0ede6 100%);
            color: #2c2c2c;
            position: fixed;
            left: 0;
            top: 70px;
            height: calc(100vh - 56px);
            overflow-y: hidden;
            flex-shrink: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.07);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            z-index: 1020;
            border-right: 1px solid #e8e2d4;
        }

        .sidebar.hidden {
            transform: translateX(-260px);
        }

        .sidebar-header {
            padding: 1rem 1.25rem;
            background: rgba(201, 168, 76, 0.1);
            border-bottom: 1px solid #e0d5b8;
            flex-shrink: 0;
        }

        .user-profile-sidebar {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            font-size: 0.9rem;
        }

        .user-info h6 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 600;
            color: #1a2a4a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 160px;
        }

        .user-info small {
            font-size: 0.75rem;
            color: #a07828;
            display: block;
        }

        .sidebar-menu {
            padding: 0.75rem 0;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-menu-item {
            padding: 0.7rem 1.25rem;
            color: #444;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-size: 0.9rem;
        }

        .sidebar-menu-item:hover {
            background: rgba(201, 168, 76, 0.1);
            color: #1a2a4a;
            border-left-color: #c9a84c;
        }

        .sidebar-menu-item.active {
            background: rgba(201, 168, 76, 0.15);
            color: #1a2a4a;
            border-left-color: #c9a84c;
            font-weight: 500;
        }

        .sidebar-menu-item i {
            font-size: 1.15rem;
            width: 22px;
            color: #a07828;
        }

        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 2rem;
            background: #f8f9fa;
            overflow-x: hidden;
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Footer - light warm tone with gold top border */
        footer {
            background: linear-gradient(90deg, #f7f5f0 0%, #f0ede6 100%);
            color: #555;
            padding: 20px 0;
            margin-left: 260px;
            width: calc(100% - 260px);
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        footer.expanded {
            margin-left: 0;
            width: 100%;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 1rem;
        }

        /* Stat Cards */
        .stat-card {
            border: none;
            border-radius: 12px;
            padding: 1.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background: white;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: white;
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, #f63b3b 0%, #eb2525 100%);
        }

        .stat-icon.orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        }

        .stat-icon.emerald {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .stat-icon.slate {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        }

        /* ─── My Profile navbar dropdown ─── */
        .profile-nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            border: 1px solid rgba(201, 168, 76, 0.35);
            background: rgba(201, 168, 76, 0.08);
            color: #1a2a4a;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s ease, border-color 0.2s ease;
            text-decoration: none;
            white-space: nowrap;
        }

        .profile-nav-btn:hover,
        .profile-nav-btn:focus,
        .show > .profile-nav-btn {
            background: rgba(201, 168, 76, 0.18);
            border-color: #c9a84c;
            color: #a07828;
            text-decoration: none;
            outline: none;
        }

        .profile-nav-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .profile-nav-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .profile-dropdown-menu {
            min-width: 210px;
            border: 1px solid #e8e2d4;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 0.4rem 0;
            background: #fff;
        }

        .profile-dropdown-header {
            padding: 0.65rem 1rem;
            border-bottom: 1px solid #f0ede6;
            margin-bottom: 0.25rem;
        }

        .profile-dropdown-header .name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1a2a4a;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 170px;
        }

        .profile-dropdown-header .role {
            font-size: 0.72rem;
            color: #a07828;
        }

        .profile-dropdown-menu .dropdown-item {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            color: #444;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.15s;
        }

        .profile-dropdown-menu .dropdown-item i {
            color: #a07828;
            font-size: 0.95rem;
            width: 16px;
        }

        .profile-dropdown-menu .dropdown-item:hover {
            background: rgba(201, 168, 76, 0.1);
            color: #1a2a4a;
        }

        .profile-dropdown-menu .dropdown-divider {
            border-color: #f0ede6;
            margin: 0.25rem 0;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .layout-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                top: 0;
                transform: none !important;
            }

            .sidebar.hidden {
                display: none;
            }

            .main-content {
                margin-left: 0 !important;
            }

            footer {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .main-content {
                padding: 1rem;
            }

            .noc-info h5 {
                font-size: 15px;
            }

            .noc-info p {
                font-size: 12px;
            }

            .noc-info small {
                font-size: 10px;
            }

            .noc-logo {
                height: 40px;
            }
        }

        /* Custom Scrollbar for Sidebar Menu */
        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.03);
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(201, 168, 76, 0.3);
            border-radius: 3px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: rgba(201, 168, 76, 0.5);
        }
    </style>
    @yield('custom-styles')

    @stack('styles')
</head>

<body>

    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light" id="topNavbar">
        <div class="container-fluid">
            <!-- Sidebar Toggle Button -->
            <button class="sidebar-toggle-btn" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <!-- NOC Logo and Brand -->
            <div class="noc-brand-container">
                <img src="/images/images.png" alt="Nepal Oil Corporation Logo" class="noc-logo"
                    style="height: 50px; width: auto; display: block;">
                <div class="noc-info">
                    <h5>NEPAL OIL CORPORATION LTD.</h5>
                    <p>Babarmahal, Kathmandu</p>
                    <small>Online Recruitment Management System</small>
                </div>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">

                    @if(request()->is('candidate/*'))

                        {{-- Notifications --}}
                        <li class="nav-item">
                            <a class="nav-link text-dark position-relative notification-link"
                               href="{{ route('candidate.notifications.index') }}"
                               title="Notifications">
                                <i class="bi bi-bell"></i>
                                @php
                                    try {
                                        $bellCandidateId = Auth::guard('candidate')->id();
                                        if ($bellCandidateId) {
                                            $unreadCount = \App\Models\Notification::where('user_id', $bellCandidateId)
                                                ->where('user_type', 'candidate')
                                                ->where('is_read', false)
                                                ->count();
                                            if ($unreadCount > 0) {
                                                echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">' . min($unreadCount, 99) . '</span>';
                                            }
                                        }
                                    } catch (\Exception $e) {}
                                @endphp
                            </a>
                        </li>

                        {{-- My Profile Dropdown --}}
                        @php
                            try {
                                $navCandidate     = Auth::guard('candidate')->user();
                                $navCandidateName = $navCandidate->name ?? 'Candidate';
                                $navInitial       = strtoupper(substr($navCandidateName, 0, 1));
                                $navPhoto         = null;
                                if (!empty($navCandidate->citizenship_number)) {
                                    $navPhoto = \DB::table('application_form')
                                        ->where('citizenship_number', $navCandidate->citizenship_number)
                                        ->whereNotNull('passport_size_photo')
                                        ->orderBy('created_at', 'desc')
                                        ->value('passport_size_photo');
                                }
                            } catch (\Exception $e) {
                                $navCandidateName = 'Candidate';
                                $navInitial       = 'C';
                                $navPhoto         = null;
                            }
                        @endphp
                        <li class="nav-item dropdown ms-2">
                            <a class="profile-nav-btn dropdown-toggle"
                               href="#"
                               id="profileNavDropdown"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">
                                <!-- <span class="profile-nav-avatar">
                                    @if($navPhoto)
                                        <img src="{{ asset('storage/' . $navPhoto) }}" alt="{{ $navCandidateName }}">
                                    @else
                                        {{ $navInitial }}
                                    @endif
                                </span> -->
                                <span class="d-none d-md-inline">My Profile</span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end profile-dropdown-menu"
                                aria-labelledby="profileNavDropdown">

                                <!-- {{-- Header: name + role --}}
                                <li>
                                    <div class="profile-dropdown-header">
                                        <div class="name" title="{{ $navCandidateName }}">{{ $navCandidateName }}</div>
                                        <div class="role">Applicant</div>
                                    </div>
                                </li> -->

                                {{-- View Profile --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('candidate.my-profile') }}">
                                        <i class="bi bi-person"></i> View Profile
                                    </a>
                                </li>

                                {{-- Edit Profile --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('candidate.edit-profile') }}">
                                        <i class="bi bi-pencil"></i> Edit Profile
                                    </a>
                                </li>

                                <!-- {{-- Change Password --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('candidate.change-password') }}">
                                        <i class="bi bi-lock"></i> Change Password
                                    </a>
                                </li> -->
                            </ul>
                        </li>

                        {{-- Standalone Logout (always visible for candidates) --}}
                        <li class="nav-item ms-1">
                            <form method="POST" action="{{ route('candidate.logout') }}" class="d-inline">
                                @csrf
                                <button class="btn btn-link nav-link text-dark" type="submit" title="Logout">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>

                    @elseif(request()->is('reviewer/*'))

                        <li class="nav-item">
                            <a class="nav-link text-dark position-relative notification-link"
                               href="{{ route('reviewer.notifications.index') }}"
                               title="Notifications">
                                <i class="bi bi-bell"></i>
                                @php
                                    try {
                                        if (Auth::guard('reviewer')->check()) {
                                            $unreadCount = \App\Models\Notification::where('user_id', Auth::guard('reviewer')->id())
                                                ->where('user_type', 'reviewer')
                                                ->where('is_read', false)
                                                ->count();
                                            if ($unreadCount > 0) {
                                                echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">' . min($unreadCount, 99) . '</span>';
                                            }
                                        }
                                    } catch (\Exception $e) {}
                                @endphp
                            </a>
                        </li>

                    @elseif(request()->is('approver/*'))

                        <li class="nav-item">
                            <a class="nav-link text-dark position-relative notification-link"
                               href="{{ route('approver.notifications.index') }}"
                               title="Notifications">
                                <i class="bi bi-bell"></i>
                                @php
                                    try {
                                        if (Auth::guard('approver')->check()) {
                                            $unreadCount = \App\Models\Notification::where('user_id', Auth::guard('approver')->id())
                                                ->where('user_type', 'approver')
                                                ->where('is_read', false)
                                                ->count();
                                            if ($unreadCount > 0) {
                                                echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">' . min($unreadCount, 99) . '</span>';
                                            }
                                        }
                                    } catch (\Exception $e) {}
                                @endphp
                            </a>
                        </li>

                    @endif

                    {{-- Logout for non-candidate roles (reviewer/approver/admin) --}}
                    @if(!request()->is('candidate/*'))
                    <li class="nav-item">
                        @if(request()->is('reviewer/*'))
                            <form method="POST" action="{{ route('reviewer.logout') }}" class="d-inline">
                                @csrf
                                <button class="btn btn-link nav-link text-dark" type="submit">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        @elseif(request()->is('approver/*'))
                            <form method="POST" action="{{ route('approver.logout') }}" class="d-inline">
                                @csrf
                                <button class="btn btn-link nav-link text-dark" type="submit">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        @elseif(request()->is('admin/*'))
                            <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                                @csrf
                                <button class="btn btn-link nav-link text-dark" type="submit">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        @endif
                    </li>
                    @endif

                </ul>
            </div>
        </div>
    </nav>

    <!-- Layout Container: Sidebar + Main Content -->
    <div class="layout-container">

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="user-profile-sidebar">
                    @php
                        $candidateName    = 'User';
                        $candidateInitial = 'U';
                        $candidatePhoto   = null;

                        $sidebarCandidate = Auth::guard('candidate')->user();
                        if ($sidebarCandidate) {
                            if (!empty($sidebarCandidate->name)) {
                                $candidateName    = $sidebarCandidate->name;
                                $candidateInitial = strtoupper(substr($candidateName, 0, 1));
                            } elseif (!empty($sidebarCandidate->email)) {
                                $candidateName    = explode('@', $sidebarCandidate->email)[0];
                                $candidateInitial = strtoupper(substr($candidateName, 0, 1));
                            }

                            // Get latest passport photo submitted in any application
                            if (!empty($sidebarCandidate->citizenship_number)) {
                                $candidatePhoto = \DB::table('application_form')
                                    ->where('citizenship_number', $sidebarCandidate->citizenship_number)
                                    ->whereNotNull('passport_size_photo')
                                    ->orderBy('created_at', 'desc')
                                    ->value('passport_size_photo');
                            }
                        }
                    @endphp
                    <div class="user-avatar" style="{{ $candidatePhoto ? 'background:none;' : '' }}">
                        @if($candidatePhoto)
                            <img src="{{ asset('storage/' . $candidatePhoto) }}"
                                 alt="{{ $candidateName }}"
                                 style="width:36px;height:36px;border-radius:50%;object-fit:cover;display:block;">
                        @else
                            {{ $candidateInitial }}
                        @endif
                    </div>
                    <div class="user-info">
                        <h6 title="{{ $candidateName }}">{{ $candidateName }}</h6>
                        <small>Applicant</small>
                    </div>
                </div>
            </div>

            <nav class="sidebar-menu">
                @yield('sidebar-menu')
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer id="footer">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Nepal Oil Corporation</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Nepali Datepicker JS -->
    <script
        src="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/js/nepali.datepicker.v5.0.6.min.js"></script>

    <!-- BS/AD Date Converter -->
    <script>
        // ============================================
        // Nepali (Bikram Sambat) <=> English (AD) Date Converter
        // No external CDN dependency - 100% reliable
        // Data source: Official Nepali Calendar
        // ============================================

        (function () {
            'use strict';

            // Official Nepali Calendar Data (days in each month for each year)
            // This is the ACCURATE data used by official converters
            const bsMonthData = {
                1975: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                1976: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                1977: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
                1978: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                1979: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                1980: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                1981: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
                1982: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                1983: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                1984: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                1985: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
                1986: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                1987: [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                1988: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                1989: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
                1990: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                1991: [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                1992: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
                1993: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
                1994: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                1995: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
                1996: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
                1997: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                1998: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                1999: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2000: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
                2001: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2002: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2003: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2004: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
                2005: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2006: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2007: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2008: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
                2009: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2010: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2011: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2012: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
                2013: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2014: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2015: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2016: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
                2017: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2018: [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2019: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
                2020: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
                2021: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2022: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
                2023: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
                2024: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
                2025: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2026: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2027: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
                2028: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2029: [31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
                2030: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2031: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
                2032: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2033: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2034: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2035: [30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
                2036: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2037: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2038: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2039: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
                2040: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2041: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2042: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2043: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
                2044: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2045: [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2046: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2047: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
                2048: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2049: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
                2050: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
                2051: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
                2052: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2053: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
                2054: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
                2055: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2056: [31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
                2057: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2058: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
                2059: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2060: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2061: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2062: [30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31],
                2063: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2064: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2065: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2066: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
                2067: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2068: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2069: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2070: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
                2071: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2072: [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2073: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2074: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
                2075: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2076: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
                2077: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
                2078: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
                2079: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2080: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
                2081: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
                2082: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
                2083: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2084: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2085: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
                2086: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2087: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2088: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2089: [30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31],
                2090: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2091: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2092: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2093: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
                2094: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2095: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
                2096: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
                2097: [30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
                2098: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
                2099: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30]
            };

            const bsStartYear = 2000;
            const bsStartMonth = 1;
            const bsStartDay = 1;
            const adRefDate = new Date(1943, 3, 14);

            function getTotalDaysInBsYear(year) {
                if (!bsMonthData[year]) return 365;
                return bsMonthData[year].reduce((sum, days) => sum + days, 0);
            }

            function getDaysInBsMonth(year, month) {
                if (!bsMonthData[year]) return 30;
                return bsMonthData[year][month - 1] || 30;
            }

            function countBsDays(year, month, day) {
                let totalDays = 0;
                for (let y = bsStartYear; y < year; y++) {
                    totalDays += getTotalDaysInBsYear(y);
                }
                for (let m = 1; m < month; m++) {
                    totalDays += getDaysInBsMonth(year, m);
                }
                totalDays += day - bsStartDay;
                return totalDays;
            }

            window.bsToAD = function (bsDateStr) {
                try {
                    const parts = bsDateStr.split('-').map(Number);
                    const bsYear = parts[0], bsMonth = parts[1], bsDay = parts[2];
                    if (!bsYear || !bsMonth || !bsDay) return '';
                    const totalDays = countBsDays(bsYear, bsMonth, bsDay);
                    const adDate = new Date(adRefDate);
                    adDate.setDate(adDate.getDate() + totalDays);
                    return adDate.getFullYear() + '-' +
                        String(adDate.getMonth() + 1).padStart(2, '0') + '-' +
                        String(adDate.getDate()).padStart(2, '0');
                } catch (e) { return ''; }
            };

            window.adToBS = function (adDateStr) {
                try {
                    const adDate = new Date(adDateStr);
                    if (isNaN(adDate.getTime())) return '';
                    const diffTime = adDate.getTime() - adRefDate.getTime();
                    let totalDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                    let bsYear = bsStartYear, bsMonth = bsStartMonth, bsDay = bsStartDay;
                    bsDay += totalDays;
                    while (bsDay > getDaysInBsMonth(bsYear, bsMonth)) {
                        bsDay -= getDaysInBsMonth(bsYear, bsMonth);
                        bsMonth++;
                        if (bsMonth > 12) { bsMonth = 1; bsYear++; }
                    }
                    while (bsDay < 1) {
                        bsMonth--;
                        if (bsMonth < 1) { bsMonth = 12; bsYear--; }
                        bsDay += getDaysInBsMonth(bsYear, bsMonth);
                    }
                    return bsYear + '-' +
                        String(bsMonth).padStart(2, '0') + '-' +
                        String(bsDay).padStart(2, '0');
                } catch (e) { return ''; }
            };

            window.nepaliLibrariesReady = true;
        })();
    </script>

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const footer = document.getElementById('footer');
            const toggleBtn = document.getElementById('sidebarToggle');

            let isHidden = localStorage.getItem('sidebarHidden') === 'true';

            if (isHidden) {
                sidebar.classList.add('hidden');
                mainContent.classList.add('expanded');
                footer.classList.add('expanded');
            }

            toggleBtn.addEventListener('click', function () {
                isHidden = !isHidden;
                if (isHidden) {
                    sidebar.classList.add('hidden');
                    mainContent.classList.add('expanded');
                    footer.classList.add('expanded');
                } else {
                    sidebar.classList.remove('hidden');
                    mainContent.classList.remove('expanded');
                    footer.classList.remove('expanded');
                }
                localStorage.setItem('sidebarHidden', isHidden);
            });
        });
    </script>

    @stack('scripts')

</body>

</html>