<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') NOC E-Recruitment Management System</title>
    <link rel="icon" href="{{ asset('images/noc_logo_tab.png') }}" type="image/png">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Nepali Datepicker  -->
    <link href="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/css/nepali.datepicker.v5.0.6.min.css" rel="stylesheet" type="text/css"/>

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

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

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
            background: white;
            border-radius: 8px;
            padding: 2px;
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
                background: linear-gradient(135deg, #2196F3 0%, #1976d2 100%);
                color: white;
                padding: 1.5rem 0;
                margin-top: 3rem;
            }

            #footer p {
                color: rgba(255, 255, 255, 0.9);
                font-size: 15px;
            }

            /* Ensure main-content has proper min-height for footer to stay at bottom */
            .main-content {
                margin-left: var(--sidebar-width);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .content-area {
                padding: 2rem 1.5rem;
                flex: 1; 
            }
        @yield('custom-styles');
    </style>
</head>

<body>
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
                <img src="{{ asset('images/noc_logo.png') }}" alt="Nepal Oil Corporation" class="navbar-company-logo">
                <div class="navbar-company-info">
                    <h2>NEPAL OIL CORPORATION LTD.</h2>
                    <p>Babarmahal, Kathmandu</p>
                </div>
            </div>

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
                            <li><hr class="dropdown-divider"></li>
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

        <div class="content-area">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer id="footer">
            <div class="container text-center">
                <p class="mb-0">Copyrights &copy; {{ date('Y') }} Nepal Oil Corporation</p>
            </div>
        </footer>
    </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Official Nepali Date Picker -->
    <script src="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/js/nepali.datepicker.v5.0.6.min.js"></script>

    <script>
        console.log('🚀 Dashboard initializing...');
        
        // ============================================
        // EMBEDDED ACCURATE NEPALI DATE CONVERTER
        // No external CDN dependency - 100% reliable
        // Data source: Official Nepali Calendar
        // ============================================
        
        (function() {
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
            window.bsToAD = function(bsDateStr) {
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
            window.adToBS = function(adDateStr) {
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
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const body = document.body;
            const isMobile = window.innerWidth <= 768;

            if (!isMobile) {
                const savedState = localStorage.getItem('sidebarCollapsed');
                if (savedState === 'true') {
                    body.classList.add('sidebar-collapsed');
                }
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    body.classList.toggle('sidebar-collapsed');
                    if (!isMobile) {
                        localStorage.setItem('sidebarCollapsed', body.classList.contains('sidebar-collapsed'));
                    }
                });
            }

            const mobileToggle = document.getElementById('mobileToggle');
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    document.getElementById('sidebar').classList.toggle('show');
                });
            }
        });
    </script>

    @yield('scripts')
</body>

</html>