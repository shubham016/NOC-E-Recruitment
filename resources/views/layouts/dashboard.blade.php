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

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Nepali Datepicker  -->
    <link href="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/css/nepali.datepicker.v5.0.6.min.css"
        rel="stylesheet" type="text/css" />

    <style>
        :root {
            --navy-primary:   #1a3a6b;
            --navy-dark:      #122a52;
            --navy-light:     #2a5298;
            --navy-pale:      #e8eef6;
            --navy-border:    #c8d4e8;
            --navy-border-lt: #d0daea;
            --sidebar-bg1:    #f0f4f9;
            --sidebar-bg2:    #e8eef6;
            --navbar-bg:      #f5f8fc;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
            --sidebar-speed: 0.3s ease;
        }

        /* Suppress all transitions during initial page load to prevent flash */
        .no-transition,
        .no-transition * {
            transition: none !important;
        }

        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--sidebar-bg1) 0%, var(--sidebar-bg2) 100%);
            border-right: 1px solid var(--navy-border-lt);
            padding: 0;
            z-index: 1000;
            box-shadow: 2px 0 8px rgba(26, 58, 107, 0.08);
            overflow-y: auto;
            transition: width var(--sidebar-speed);
            display: flex;
            flex-direction: column;
        }

        .company-logo-header {
            background: linear-gradient(135deg, var(--navy-light) 0%, var(--navy-dark) 100%);
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 2px solid rgba(26, 58, 107, 0.3);
            transition: all var(--sidebar-speed);
        }

        .company-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            background: white;
            border-radius: 50%;
            padding: 8px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .company-info {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            transition: opacity var(--sidebar-speed), width var(--sidebar-speed);
        }

        .company-name {
            color: white;
            font-size: 0.95rem;
            font-weight: 700;
            margin: 0 0 4px 0;
            line-height: 1.2;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: opacity var(--sidebar-speed);
        }

        .company-location {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.75rem;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: opacity var(--sidebar-speed);
        }

        .sidebar-collapsed .company-info {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar-collapsed .company-logo-header {
            justify-content: center;
            padding: 1rem 0.5rem;
        }

        .sidebar-collapsed .company-logo {
            width: 50px;
            height: 50px;
        }

        .sidebar-header {
            padding: 1rem 1.25rem;
            background: rgba(26, 58, 107, 0.1);
            border-bottom: 1px solid var(--navy-border);
            flex-shrink: 0;
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .hamburger-toggle {
            width: 40px;
            height: 40px;
            background: rgba(26, 58, 107, 0.1);
            border: 1px solid rgba(26, 58, 107, 0.3);
            border-radius: 8px;
            color: white;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .hamburger-toggle:hover {
            background: rgba(26, 58, 107, 0.2);
            transform: scale(1.05);
        }

        .hamburger-toggle i {
            color: var(--navy-primary);
            transition: transform var(--sidebar-speed);
        }

        .sidebar-brand {
            color: #1a2a4a;
            font-size: 1.1rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
            overflow: hidden;
        }

        .sidebar-brand i {
            font-size: 1.4rem;
            flex-shrink: 0;
            color: var(--navy-light);
        }

        .brand-text {
            white-space: nowrap;
            transition: opacity var(--sidebar-speed), width var(--sidebar-speed);
        }

        .sidebar-collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-collapsed .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        .sidebar-collapsed .top-navbar {
            left: var(--sidebar-collapsed-width);
        }

        .sidebar-collapsed .brand-text,
        .sidebar-collapsed .sidebar-menu-item span,
        .sidebar-collapsed .sidebar-menu-item .badge {
            display: none;
        }

        .sidebar-collapsed .brand-container {
            justify-content: center;
        }

        .sidebar-collapsed .sidebar-menu-item {
            justify-content: center;
            padding: 0.85rem 0;
            display: flex;
            align-items: center;
            min-height: 50px;
            width: 100%;
            border-left: none;
            gap: 0;
        }

        .sidebar-collapsed .sidebar-menu-item i {
            width: auto;
            text-align: center;
            margin: 0;
            flex-shrink: 0;
        }

        .sidebar-collapsed .hamburger-toggle i {
            transform: rotate(90deg);
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-collapsed .sidebar-menu {
            padding: 1rem 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar-menu-item {
            padding: 0.7rem 1.25rem;
            color: #444;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all var(--sidebar-speed);
            border-left: 3px solid transparent;
            font-size: 0.9rem;
            min-height: 44px;
        }

        .sidebar-menu-item:hover {
            background: rgba(26, 58, 107, 0.1);
            color: #1a2a4a;
            border-left-color: var(--navy-primary);
        }

        .sidebar-menu-item.active {
            background: rgba(26, 58, 107, 0.15);
            color: #1a2a4a;
            border-left-color: var(--navy-primary);
            font-weight: 500;
        }

        .sidebar-menu-item i {
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
            color: var(--navy-primary);
            flex-shrink: 0;
            transition: all var(--sidebar-speed);
        }

        .sidebar-menu-item span {
            transition: opacity var(--sidebar-speed);
        }

        .sidebar-menu-item .badge {
            transition: opacity var(--sidebar-speed);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left var(--sidebar-speed);
            overflow-x: hidden;
        }

        .top-navbar {
            background: linear-gradient(90deg, #ffffff 0%, var(--navbar-bg) 100%);
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            padding: 0.75rem 1.5rem;
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: left var(--sidebar-speed);
        }

        .navbar-company-header {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .navbar-company-logo {
            height: 50px;
            width: auto;
        }

        .navbar-company-info h2 {
            color: #1a2a4a;
            font-size: 17px;
            font-weight: 600;
            margin: 0;
            line-height: 1.2;
        }

        .navbar-company-info p {
            color: #555;
            font-size: 13px;
            margin: 0;
            line-height: 1.2;
        }

        .navbar-company-info small {
            font-size: 11px;
            color: var(--navy-light);
            font-style: italic;
            display: block;
            margin-top: 2px;
        }

        .navbar-right-section {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: -3px 8px 0px 0px;
            height: 40px;
        }

        .navbar-right-section .nav-link {
            color: #1a2a4a;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            padding: 0;
            transition: color 0.2s;
            line-height: 1;
            height: 40px;
        }

        .navbar-right-section .nav-link:hover {
            color: var(--navy-light);
        }

        .navbar-right-section .nav-link i {
            font-size: 1rem;
            line-height: 1;
        }

        /* Notification bell link */
        .notification-link {
            display: inline-flex !important;
            align-items: center !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .notification-link .bi-bell {
            font-size: 1rem !important;
            line-height: 1.5 !important;
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

        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--navy-light) 0%, var(--navy-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(26, 58, 107, 0.3);
        }

        /* User Dropdown Styles */
        #userDropdown {
            display: flex !important;
            align-items: center !important;
            gap: 0;
            padding: 0 !important;
            line-height: 1;
            height: 40px !important;
        }

        #userDropdown .toggle-icon {
            font-size: 0.875rem;
            color: #1a2a4a;
            transition: transform 0.2s ease;
            line-height: 1;
            position: relative;
            top: 1px;
            margin-left: 0.6rem;
        }

        #userDropdown[aria-expanded="true"] .toggle-icon {
            transform: rotate(180deg);
        }

        #userDropdown .user-name {
            line-height: 1;
            display: flex;
            align-items: center;
            font-weight: 400;
        }

        .dropdown-menu {
            border: 1px solid var(--navy-border-lt);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 0.5rem 0;
            min-width: 200px;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            padding: 0.65rem 1.25rem;
            font-size: 0.9rem;
            color: #444;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .dropdown-item i {
            width: 18px;
            text-align: center;
            font-size: 1rem;
            color: var(--navy-primary);
        }

        .dropdown-item:hover {
            background: rgba(26, 58, 107, 0.1);
            color: #1a2a4a;
        }

        .dropdown-item.text-danger:hover {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .dropdown-item.text-danger i {
            color: #dc3545;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: var(--navy-border-lt);
        }

        .content-area {
            padding: 2rem 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(26, 58, 107, 0.12);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: var(--primary-blue);
        }

        .stat-icon.emerald {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: var(--accent-emerald);
        }

        .stat-icon.slate {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            color: var(--secondary-slate);
        }

        .stat-icon.orange {
            background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%);
            color: #ea580c;
        }

        .stat-icon.navy {
            background: linear-gradient(135deg, var(--navy-pale) 0%, var(--navy-border) 100%);
            color: var(--navy-primary);
        }

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
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .top-navbar {
                left: 0;
            }

            .navbar-company-header {
                min-width: auto;
                padding: 0.75rem 1rem;
                flex: 1;
            }

            .navbar-company-logo {
                width: 40px;
                height: 40px;
            }

            .navbar-company-info h2 {
                font-size: 0.85rem;
            }

            .navbar-company-info p {
                font-size: 0.7rem;
            }

            .navbar-right-section {
                padding: 0.75rem 1rem;
            }
        }

        @media (max-width: 480px) {
            .navbar-company-info p {
                display: none;
            }

            .navbar-company-info h2 {
                font-size: 0.8rem;
            }
        }

        /* Footer Styles */

        #footer {
            background: linear-gradient(135deg, var(--navy-light) 0%, var(--navy-dark) 100%);
            color: white;
            padding: 1.5rem 0;
            margin-top: 3rem;
        }

        #footer p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
        }

        .content-area {
            padding: 2rem 1.5rem;
            flex: 1;
            overflow-y: auto;
            margin-top: 70px; /* Height of fixed navbar */
        }

        @yield('custom-styles');
    </style>

    @stack('styles')
</head>

<body class="sidebar-collapsed no-transition">
<script>
    // Runs synchronously before first paint — no flash, no animation on load
    (function () {
        if (sessionStorage.getItem('adminSidebarExpanded') === 'true') {
            document.body.classList.remove('sidebar-collapsed');
        }
    })();
</script>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="brand-container">
                <a href="@yield('dashboard-route')" class="sidebar-brand">
                    <i class="@yield('brand-icon')"></i>
                    <span class="brand-text">@yield('portal-name')</span>
                </a>
                <button class="hamburger-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>

        <div class="sidebar-menu">
            @yield('sidebar-menu')
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="navbar-company-header">
                <img src="/images/images.png" alt="Nepal Oil Corporation" class="navbar-company-logo">
                <div class="navbar-company-info">
                    <h2>{{ __('admin.company_name') }}</h2>
                    <p>{{ __('admin.company_address') }}</p>
                    <small>{{ __('admin.company_system') }}</small>
                </div>
            </div>

            <div class="navbar-right-section">
                <!-- Language Switcher -->
                <form method="POST" action="{{ route('language.switch') }}" style="display:inline;">
                    @csrf
                    <select name="locale" onchange="this.form.submit()"
                        style="height:32px;padding:0 8px;font-size:0.8rem;border:1px solid #1a3a6b;border-radius:6px;background:#fff;color:#1a2a4a;cursor:pointer;outline:none;">
                        <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>EN</option>
                        <option value="ne" {{ app()->getLocale() === 'ne' ? 'selected' : '' }}>नेपाली</option>
                    </select>
                </form>

                <!-- Notifications -->
                @if(request()->is('admin/*'))
                    <a class="nav-link text-dark position-relative notification-link" href="{{ route('admin.notifications.index') }}" title="Notifications">
                        <i class="bi bi-bell"></i>
                        @php
                            try {
                                if (Auth::guard('admin')->check()) {
                                    $unreadCount = \App\Models\Notification::where('user_id', Auth::guard('admin')->id())
                                        ->where('user_type', 'admin')
                                        ->where('is_read', false)
                                        ->count();
                                    if ($unreadCount > 0) {
                                        echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">' . min($unreadCount, 99) . '</span>';
                                    }
                                }
                            } catch (\Exception $e) {
                                // Silently fail if there's an error
                            }
                        @endphp
                    </a>
                @endif

                <!-- User Dropdown Menu -->
                <div class="dropdown">
                    <a class="nav-link d-flex align-items-center" href="#" id="userDropdown"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="user-name">
                            {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
                        </span>
                        <i class="bi bi-chevron-down toggle-icon ms-2"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
                        @if(request()->is('admin/*'))
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                    <i class="bi bi-person me-2"></i> {{ __('admin.my_profile') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.change-password') }}">
                                    <i class="bi bi-lock me-2"></i> {{ __('admin.change_password') }}
                                </a>
                            </li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="@yield('logout-route')" class="d-inline w-100">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">
                                    <i class="bi bi-box-arrow-right me-2"></i> {{ __('admin.log_out') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content-area">
            @yield('content')
        </div>

        <!-- Footer -->
       <footer id="footer">
            <div class="container text-center">
                <p class="mb-0">{{ __('admin.copyright') }} &copy; {{ date('Y') }}. {{ __('admin.company_name') }}</p>
            </div>
        </footer>
    </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Official Nepali Date Picker -->
    <script
        src="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/js/nepali.datepicker.v5.0.6.min.js"></script>

    <script>
        console.log('🚀 Dashboard initializing...');

        // ============================================
        // EMBEDDED ACCURATE NEPALI DATE CONVERTER
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

            // Reference point: 2000-01-01 BS = 1943-04-14 AD
            const bsStartYear = 2000;
            const bsStartMonth = 1;
            const bsStartDay = 1;
            const adRefDate = new Date(1943, 3, 14); // April 14, 1943

            // Get total days in a BS year
            function getTotalDaysInBsYear(year) {
                if (!bsMonthData[year]) return 365;
                return bsMonthData[year].reduce((sum, days) => sum + days, 0);
            }

            // Get days in a specific BS month
            function getDaysInBsMonth(year, month) {
                if (!bsMonthData[year]) return 30;
                return bsMonthData[year][month - 1] || 30;
            }

            // Count total days from BS reference date to given BS date
            function countBsDays(year, month, day) {
                let totalDays = 0;

                // Add days for complete years
                for (let y = bsStartYear; y < year; y++) {
                    totalDays += getTotalDaysInBsYear(y);
                }

                // Add days for complete months in the target year
                for (let m = 1; m < month; m++) {
                    totalDays += getDaysInBsMonth(year, m);
                }

                // Add remaining days
                totalDays += day - bsStartDay;

                return totalDays;
            }

            // BS to AD conversion
            window.bsToAD = function (bsDateStr) {
                try {
                    console.log('🔄 Converting BS→AD:', bsDateStr);

                    const parts = bsDateStr.split('-').map(Number);
                    const bsYear = parts[0];
                    const bsMonth = parts[1];
                    const bsDay = parts[2];

                    if (!bsYear || !bsMonth || !bsDay) {
                        console.error('Invalid BS date format');
                        return '';
                    }

                    // Calculate total days from reference
                    const totalDays = countBsDays(bsYear, bsMonth, bsDay);

                    // Add days to AD reference date
                    const adDate = new Date(adRefDate);
                    adDate.setDate(adDate.getDate() + totalDays);

                    const result = adDate.getFullYear() + '-' +
                        String(adDate.getMonth() + 1).padStart(2, '0') + '-' +
                        String(adDate.getDate()).padStart(2, '0');

                    console.log('✅ BS→AD Result:', result);
                    return result;
                } catch (error) {
                    console.error('❌ BS to AD error:', error);
                    return '';
                }
            };

            // AD to BS conversion
            window.adToBS = function (adDateStr) {
                try {
                    console.log('🔄 Converting AD→BS:', adDateStr);

                    const adDate = new Date(adDateStr);
                    if (isNaN(adDate.getTime())) {
                        console.error('Invalid AD date');
                        return '';
                    }

                    // Calculate days difference from reference
                    const diffTime = adDate.getTime() - adRefDate.getTime();
                    let totalDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

                    // Find BS date
                    let bsYear = bsStartYear;
                    let bsMonth = bsStartMonth;
                    let bsDay = bsStartDay;

                    // Add days to find the BS date
                    bsDay += totalDays;

                    // Normalize the date
                    while (bsDay > getDaysInBsMonth(bsYear, bsMonth)) {
                        bsDay -= getDaysInBsMonth(bsYear, bsMonth);
                        bsMonth++;
                        if (bsMonth > 12) {
                            bsMonth = 1;
                            bsYear++;
                        }
                    }

                    while (bsDay < 1) {
                        bsMonth--;
                        if (bsMonth < 1) {
                            bsMonth = 12;
                            bsYear--;
                        }
                        bsDay += getDaysInBsMonth(bsYear, bsMonth);
                    }

                    const result = bsYear + '-' +
                        String(bsMonth).padStart(2, '0') + '-' +
                        String(bsDay).padStart(2, '0');

                    console.log('✅ AD→BS Result:', result);
                    return result;
                } catch (error) {
                    console.error('❌ AD to BS error:', error);
                    return '';
                }
            };

            // Mark as ready
            window.nepaliLibrariesReady = true;
            console.log('✅ Nepali Date Converter ready (embedded version with accurate data)!');
        })();

        // Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.body;

            // Re-enable transitions after first paint (no-transition was set on <body>)
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    body.classList.remove('no-transition');
                });
            });

            const toggleBtn = document.getElementById('sidebarToggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    body.classList.toggle('sidebar-collapsed');
                    sessionStorage.setItem('adminSidebarExpanded',
                        !body.classList.contains('sidebar-collapsed'));
                });
            }

            const mobileToggle = document.getElementById('mobileToggle');
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function () {
                    document.getElementById('sidebar').classList.toggle('show');
                });
            }
        });
    </script>


    @yield('scripts')

    @if(app()->getLocale() === 'ne')
    <script>
    (function() {
        const NP = ['०','१','२','३','४','५','६','७','८','९'];
        function toNP(s) { return s.replace(/[0-9]/g, d => NP[+d]); }

        // Month, day, meridiem translation maps
        const MONTHS_FULL = {
            'January':'जनवरी','February':'फेब्रुवरी','March':'मार्च',
            'April':'अप्रिल','May':'मे','June':'जुन',
            'July':'जुलाई','August':'अगस्त','September':'सेप्टेम्बर',
            'October':'अक्टोबर','November':'नोभेम्बर','December':'डिसेम्बर'
        };
        const MONTHS_SHORT = {
            'Jan':'जन','Feb':'फेब','Mar':'मार्च',
            'Apr':'अप्र','Jun':'जुन','Jul':'जुल',
            'Aug':'अग','Sep':'सेप','Oct':'अक्ट',
            'Nov':'नोभ','Dec':'डिस'
        };
        const DAYS = {
            'Monday':'सोमबार','Tuesday':'मंगलबार','Wednesday':'बुधबार',
            'Thursday':'बिहीबार','Friday':'शुक्रबार','Saturday':'शनिबार','Sunday':'आइतबार'
        };

        function translateText(s) {
            // Replace full month names
            s = s.replace(/\b(January|February|March|April|May|June|July|August|September|October|November|December)\b/g,
                m => MONTHS_FULL[m] || m);
            // Replace short month names (only if NOT followed by a letter, to avoid partial matches)
            s = s.replace(/\b(Jan|Feb|Mar|Apr|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\b/g,
                m => MONTHS_SHORT[m] || m);
            // Replace day names
            s = s.replace(/\b(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday)\b/g,
                d => DAYS[d] || d);
            // Replace AM/PM
            s = s.replace(/\bAM\b/g, 'बिहान').replace(/\bPM\b/g, 'साँझ');
            // Convert digits
            s = toNP(s);
            return s;
        }

        function convertNodes(root) {
            const skip = new Set(['SCRIPT','STYLE','CODE','PRE','TEXTAREA','INPUT','SELECT','OPTION']);
            const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT);
            const nodes = [];
            let n;
            while ((n = walker.nextNode())) {
                if (!n.parentElement || skip.has(n.parentElement.tagName)) continue;
                if (n.textContent.includes('@')) continue; // skip emails
                if (/[0-9]|January|February|March|April|May|June|July|August|September|October|November|December|Jan|Feb|Mar|Apr|Jun|Jul|Aug|Sep|Oct|Nov|Dec|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday|\bAM\b|\bPM\b/.test(n.textContent)) nodes.push(n);
            }
            nodes.forEach(n => { n.textContent = translateText(n.textContent); });

            // Convert placeholders on non-email/password inputs
            root.querySelectorAll && root.querySelectorAll('input[placeholder],textarea[placeholder]').forEach(el => {
                if (el.type === 'email' || el.type === 'password') return;
                if (/[0-9]/.test(el.placeholder)) el.placeholder = toNP(el.placeholder);
            });
        }

        window._convertToNepaliNum = convertNodes;
        window._toNP = toNP;

        document.addEventListener('DOMContentLoaded', function() {
            convertNodes(document.body);
            // Re-run after async date conversions (nepali-date-bs)
            setTimeout(function() { convertNodes(document.body); }, 600);
            setTimeout(function() { convertNodes(document.body); }, 1500);
        });
    })();
    </script>
    @endif
</body>

</html>