<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Online Recruitment Management System</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
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
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
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
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
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
    <!-- Nepali Date JS -->
<!-- <script src="https://cdn.jsdelivr.net/gh/kamayura/nepali-date@master/nepali-date.min.js"></script> -->
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
                <img src="{{ asset('images/images.png') }}" alt="Nepal Oil Corporation Logo" class="noc-logo">
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
                <ul class="navbar-nav ms-auto">
                    <!-- Notifications -->
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark position-relative"
                           href="{{ route('approver.notifications.index') }}"
                           title="Notifications">
                            <i class="bi bi-bell fs-5"></i>
                            @php
                                try {
                                    if (Auth::guard('approver')->check()) {
                                        $unreadCount = \App\Models\Notification::where('user_id', Auth::guard('approver')->id())
                                            ->where('user_type', 'approver')
                                            ->where('is_read', false)
                                            ->count();
                                    } else {
                                        $unreadCount = 0;
                                    }
                                } catch (\Exception $e) {
                                    $unreadCount = 0;
                                }
                            @endphp
                            @if($unreadCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    <!-- Logout -->
                    <li class="nav-item">
                        <form method="POST" action="{{ route('approver.logout') }}" class="d-inline">
                            @csrf
                            <button class="btn btn-link nav-link text-dark" type="submit">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Layout Container: Sidebar + Main Content -->
    <div class="layout-container">
        <div class="sidebar" id="sidebar">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <div class="user-profile-sidebar">
                    <!-- User Avatar with Photo or Initial -->
                    <div class="user-avatar" style="{{ Auth::guard('approver')->user()->photo ? 'background:none;' : '' }}">
                        @if(Auth::guard('approver')->user()->photo)
                            <img src="{{ asset('storage/' . Auth::guard('approver')->user()->photo) }}"
                                 alt="{{ Auth::guard('approver')->user()->name }}"
                                 style="width:36px;height:36px;border-radius:50%;object-fit:cover;display:block;">
                        @else
                            {{ strtoupper(substr(Auth::guard('approver')->user()->name, 0, 1)) }}
                        @endif
                    </div>

                    <!-- User Info -->
                    <div class="user-info">
                        <h6>{{ Auth::guard('approver')->user()->name }}</h6>
                        <!-- <small> Emp id- {{ Auth::guard('approver')->user()->employee_id }} </small> -->
                        <small>Approver</small>
                    </div>
                </div>
            </div>


            <nav class="sidebar-menu">
                @yield('sidebar-menu')
            </nav>
        </div>

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

    
    <!-- Sidebar Toggle Script -->
<script>
    const BS_DATA = {
        2081: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2082: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2083: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2084: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2085: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2086: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2087: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2088: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2089: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2090: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2091: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2092: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2093: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
    };

    const NP_MONTHS = ['Baisakh','Jestha','Ashadh','Shrawan','Bhadra','Ashwin',
                       'Kartik','Mangsir','Poush','Magh','Falgun','Chaitra'];

    function getDaysInBSMonth(year, month) {
        const fallback = [31, 31, 32, 31, 31, 30, 30, 29, 30, 29, 30, 30];
        return (BS_DATA[year] || fallback)[month];
    }

    function adToBs(adYear, adMonth, adDay) {
        // Reference: Baisakh 1, 2081 = April 13, 2024 (AD)
        const refAD = new Date(2024, 3, 14);
        const refBS = { year: 2081, month: 0, day: 1 };

        const given = new Date(adYear, adMonth, adDay);
        let diffDays = Math.round((given - refAD) / 86400000);

        let { year, month, day } = { ...refBS };

        if (diffDays >= 0) {
            while (diffDays > 0) {
                const daysInMonth = getDaysInBSMonth(year, month);
                const remaining = daysInMonth - day;
                if (diffDays <= remaining) {
                    day += diffDays;
                    diffDays = 0;
                } else {
                    diffDays -= remaining + 1;
                    day = 1;
                    month++;
                    if (month > 11) { month = 0; year++; }
                }
            }
        } else {
            diffDays = Math.abs(diffDays);
            while (diffDays > 0) {
                day--;
                if (day < 1) {
                    month--;
                    if (month < 0) { month = 11; year--; }
                    day = getDaysInBSMonth(year, month);
                }
                diffDays--;
            }
        }

        return { year, month, day };
    }

    function updateDates() {
        const today = new Date();
        const englishEl = document.getElementById('english-date');
        const nepaliEl  = document.getElementById('nepali-date');

        if (englishEl) {
            englishEl.innerText = today.toLocaleDateString('en-US', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });
        }

        if (nepaliEl) {
            try {
                const bs = adToBs(today.getFullYear(), today.getMonth(), today.getDate());
                nepaliEl.innerText = `${bs.day} ${NP_MONTHS[bs.month]}, ${bs.year}`;
            } catch(e) {
                console.error('BS date error:', e);
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
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

        toggleBtn.addEventListener('click', function() {
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

    updateDates();
    setInterval(updateDates, 60000);
</script>

@yield('scripts')
</body>

</html>