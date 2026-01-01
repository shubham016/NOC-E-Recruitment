<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') NOC E-Recruitment Management System</title>
    <link rel="icon" href="{{ asset('images/noc_logo_tab.png') }}" type="image/png" style="height: auto; width: auto; border-radius: 80;">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-blue: #2563eb;
            --secondary-slate: #64748b;
            --accent-emerald: #10b981;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
        }

        body {
            background-color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            padding: 0;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Company Logo Header */
        .company-logo-header {
            background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
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
            transition: opacity 0.3s ease;
        }

        .company-location {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.75rem;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: opacity 0.3s ease;
        }

        /* Collapsed State - Hide Company Text */
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
            padding: 1.5rem 1.25rem;
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Hamburger Toggle Button */
        .hamburger-toggle {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
            background: rgba(255, 255, 255, 0.15);
            transform: scale(1.05);
        }

        .hamburger-toggle i {
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            color: white;
            font-size: 1.25rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
            overflow: hidden;
        }

        .sidebar-brand i {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .brand-text {
            white-space: nowrap;
            transition: opacity 0.3s ease, width 0.3s ease;
        }

        /* Collapsed State */
        .sidebar-collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-collapsed .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        .sidebar-collapsed .brand-text,
        .sidebar-collapsed .sidebar-menu-item span,
        .sidebar-collapsed .sidebar-menu-item .badge {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar-collapsed .brand-container {
            justify-content: center;
        }

        .sidebar-collapsed .sidebar-menu-item {
            justify-content: center;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .sidebar-collapsed .hamburger-toggle i {
            transform: rotate(90deg);
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-menu-item {
            padding: 0.75rem 1.25rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            border-left-color: var(--primary-blue);
        }

        .sidebar-menu-item.active {
            background: rgba(37, 99, 235, 0.1);
            color: white;
            border-left-color: var(--primary-blue);
        }

        .sidebar-menu-item i {
            font-size: 1.25rem;
            width: 24px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Top Navbar */
        .top-navbar {
            background: #eee;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 999;
            display: flex;
            align-items: stretch;
        }

        /* Company Header in Top Navbar */
        .navbar-company-header {
            background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-right: 1px solid #e5e7eb;
            min-width: 300px;
        }

        .navbar-company-logo {
            width: 50px;
            height: 50px;
            /* object-fit: contain; */
            background: white;
            border-radius: 8px;
            padding: 2px;
            /* flex-shrink: 0; */
        }

        .navbar-company-info h2 {
            color: white;
            font-size: 0.95rem;
            font-weight: 700;
            margin: 0 0 2px 0;
            line-height: 1.2;
            letter-spacing: 0.3px;
        }

        .navbar-company-info p {
            color: rgba(255, 255, 255, 0.95);
            font-size: 0.75rem;
            margin: 0;
        }

        .navbar-right-section {
            flex: 1;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 0.75rem 1.5rem;
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
            background: linear-gradient(135deg, var(--primary-blue) 0%, #1e40af 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        /* Content Area */
        .content-area {
            padding: 2rem 1.5rem;
        }

        /* Stats Cards */
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
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
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
            font-size: 0.95rem;
        }

        /* Responsive */
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

        @yield('custom-styles');
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Company Logo Header -->
        {{-- <div class="company-logo-header">
            <img src="{{ asset('images/noc_logo.png') }}" alt="Nepal Oil Corporation" class="company-logo">
            <div class="company-info">
                <h2 class="company-name">NEPAL OIL CORPORATION LTD.</h2>
                <p class="company-location">Babarmahal, Kathmandu</p>
            </div>
        </div> --}}
        
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
            <!-- Company Header (Left Side) -->
            <div class="navbar-company-header">
                <img src="{{ asset('images/noc_logo.png') }}" alt="Nepal Oil Corporation" class="navbar-company-logo">
                <div class="navbar-company-info">
                    <h2>NEPAL OIL CORPORATION LTD.</h2>
                    <p>Babarmahal, Kathmandu</p>
                </div>
            </div>

            <!-- Right Section (User Menu) -->
            <div class="navbar-right-section">
                <button class="btn btn-link d-md-none text-dark me-3" id="mobileToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>

                <div class="user-menu">
                    <div class="text-end d-none d-md-block">
                        <div class="fw-semibold">@yield('user-name')</div>
                        <small class="text-muted">@yield('user-role')</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0 border-0" type="button" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                @yield('user-initial')
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="@yield('logout-route')">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('sidebarToggle');
            const body = document.body;
            const isMobile = window.innerWidth <= 768;

            // Load saved state (desktop only)
            if (!isMobile) {
                const savedState = localStorage.getItem('sidebarCollapsed');
                if (savedState === 'true') {
                    body.classList.add('sidebar-collapsed');
                }
            }

            // Toggle on button click
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    body.classList.toggle('sidebar-collapsed');

                    // Save state (desktop only)
                    if (!isMobile) {
                        localStorage.setItem('sidebarCollapsed', body.classList.contains('sidebar-collapsed'));
                    }
                });
            }

            // Mobile sidebar toggle
            const mobileToggle = document.getElementById('mobileToggle');
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function () {
                    document.getElementById('sidebar').classList.toggle('show');
                });
            }
        });
    </script>

    @yield('scripts')
</body>

</html>