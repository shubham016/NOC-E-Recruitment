<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ __('admin.company_system') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ── Color Variables ────────────────────────────────────── */
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
        }

        .nav-tabs .nav-link { color: var(--navy-primary) !important; }

        .tab-circle { width: 25px !important; height: 25px !important; font-size: 14px; }
        .tab-label  { font-size: 12px !important; }
        .tab-item   { font-size: 14px; margin-right: -20px; }
        .tab-item:last-child { margin-right: 0; }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        /* ── Form elements ──────────────────────────────────────── */
        .card-body { font-size: 13px; }
        .card-body .form-control {
            font-size: 13px;
            padding: 0.4rem 0.6rem;
            height: calc(1.5em + 0.8rem + 2px);
        }
        .card-body .form-select { font-size: 13px; padding: 0.4rem 0.6rem; }
        .card-body label        { font-size: 13px; margin-bottom: 0.3rem; }

        /* ── Notification bell ──────────────────────────────────── */
        .notification-link {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: 40px !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
        .notification-link .bi-bell { font-size: 1rem; line-height: 1; }
        .notification-link .badge.translate-middle {
            width: 14px !important;
            height: 14px !important;
            min-width: 12px !important;
            font-size: 0.5rem !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #fff !important;
            transform: translate(-95%, 45%) !important;
        }

        .language-toggle-btn {
            min-width: auto;
            height: 40px;
            padding: 0 0.4rem;
            border: 0;
            border-radius: 0;
            background: transparent;
            color: #212529;
            font-size: 0.9rem;
            font-weight: 400;
            line-height: 1;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            box-shadow: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .language-toggle-btn:hover,
        .language-toggle-btn:focus {
            background: transparent;
            color: #212529;
            outline: none;
        }

        .language-toggle-btn.lang-en {
            transform: translateX(2px);
        }

        .navbar-separator {
            color: #212529;
            height: 40px;
            padding: 0 0.35rem 0 0.25rem;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .navbar-action-btn {
            height: 40px !important;
            padding-left: 0.35rem !important;
            padding-right: 0.35rem !important;
            font-size: 0.9rem !important;
            font-weight: 400 !important;
            font-family: inherit !important;
            line-height: 1 !important;
            color: #212529 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.25rem !important;
        }

        .navbar-action-btn span,
        .navbar-action-btn i {
            font-size: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            color: inherit !important;
        }

        .navbar-logout-btn,
        .navbar-logout-btn span,
        .navbar-logout-btn i {
            font-size: 0.95rem !important;
        }

        /* ── Navbar ─────────────────────────────────────────────── */
        .navbar {
            box-shadow: 0 2px 6px rgba(0,0,0,0.10);
            position: sticky;
            top: 0;
            z-index: 1030;
            background: linear-gradient(90deg, #ffffff 0%, var(--navbar-bg) 100%) !important;
            padding: 0.4rem 0.75rem;
        }

        /* ── Brand ──────────────────────────────────────────────── */
        .noc-brand-container { display: flex; align-items: center; gap: 12px; flex: 1; }
        .noc-logo            { height: 50px; width: auto; }

        .noc-info h5 {
            margin: 0; font-size: 17px; font-weight: 700;
            color: var(--navy-primary); line-height: 1.2;
        }
        .noc-info p {
            margin: 0; font-size: 13px; color: #555; line-height: 1.2;
        }
        .noc-info small {
            font-size: 11px; color: var(--navy-light); font-style: italic;
            display: block; margin-top: 2px;
        }

        /* ── Sidebar toggle button ──────────────────────────────── */
        .sidebar-toggle-btn {
            background: rgba(26, 58, 107, 0.08);
            border: 1px solid rgba(26, 58, 107, 0.25);
            color: var(--navy-primary);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            transition: background-color 0.2s ease;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        .sidebar-toggle-btn:hover { background: rgba(26, 58, 107, 0.15); }

        /* ── Language switcher border ───────────────────────────── */
        select[name="locale"] {
            border-color: var(--navy-primary) !important;
            color: var(--navy-primary) !important;
        }

        /* ── Layout container ───────────────────────────────────── */
        .layout-container {
            display: flex;
            min-height: calc(100vh - 70px);
        }

        /* ── Sidebar ────────────────────────────────────────────── */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, var(--sidebar-bg1) 0%, var(--sidebar-bg2) 100%);
            color: #2c2c2c;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: hidden;
            flex-shrink: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            z-index: 1025;
            border-right: 1px solid var(--navy-border-lt);
            padding-top: 70px;
        }

        .sidebar.hidden    { transform: translateX(-250px); }
        .sidebar.mobile-open { transform: translateX(0) !important; }

        /* Backdrop for mobile */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1024;
        }
        .sidebar-backdrop.show { display: block; }

        /* ── Sidebar header ─────────────────────────────────────── */
        .sidebar-header {
            padding: 1rem 1.25rem;
            background: rgba(26, 58, 107, 0.08);
            border-bottom: 1px solid var(--navy-border);
            flex-shrink: 0;
            display: flex;
        }

        .user-profile-sidebar { display: flex; align-items: center; gap: 0.75rem; width: 100%; }

        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--navy-light) 0%, var(--navy-dark) 100%);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: #fff;
            flex-shrink: 0; font-size: 0.9rem;
        }

        .user-info { flex: 1; min-width: 0; }

        .user-info h6 {
            margin: 0; font-size: 0.95rem; font-weight: 600;
            color: var(--navy-primary); white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis; max-width: 160px;
            display: block !important;
        }
        .user-info small {
            font-size: 0.75rem; color: var(--navy-light); display: block !important;
        }

        /* ── Sidebar menu ───────────────────────────────────────── */
        .sidebar-menu { padding: 0.75rem 0; flex: 1; overflow-y: auto; }

        .sidebar-menu-item {
            padding: 0.7rem 1.25rem;
            color: #444;
            text-decoration: none;
            display: flex; align-items: center; gap: 0.75rem;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-size: 0.9rem;
        }
        .sidebar-menu-item:hover {
            background: rgba(26, 58, 107, 0.08);
            color: var(--navy-primary);
            border-left-color: var(--navy-primary);
        }
        .sidebar-menu-item.active {
            background: rgba(26, 58, 107, 0.12);
            color: var(--navy-primary);
            border-left-color: var(--navy-primary);
            font-weight: 600;
        }
        .sidebar-menu-item i { font-size: 1.15rem; width: 22px; color: var(--navy-primary); }

        /* ── Scrollbar ──────────────────────────────────────────── */
        .sidebar-menu::-webkit-scrollbar       { width: 6px; }
        .sidebar-menu::-webkit-scrollbar-track { background: rgba(0,0,0,0.03); }
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(26, 58, 107, 0.25); border-radius: 3px;
        }
        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: rgba(26, 58, 107, 0.45);
        }

        /* ── Main content ───────────────────────────────────────── */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 1.5rem;
            background: #f8f9fa;
            overflow-x: hidden;
            transition: margin-left 0.3s ease;
            min-width: 0;
        }
        .main-content.expanded { margin-left: 0; }

        @media (min-width: 992px) {
            .main-content { padding: 2rem; }
        }

        /* ── Footer ─────────────────────────────────────────────── */
        footer {
            background: linear-gradient(90deg, var(--sidebar-bg1) 0%, var(--sidebar-bg2) 100%);
            color: #555;
            padding: 20px 0;
            margin-left: 250px;
            width: calc(100% - 250px);
            transition: margin-left 0.3s ease, width 0.3s ease;
            border-top: 1px solid var(--navy-border-lt);
        }
        footer.expanded { margin-left: 0; width: 100%; }

        /* ── Stat cards ─────────────────────────────────────────── */
        .stat-card {
            border: none; border-radius: 12px;
            padding: 1.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background: white;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(26, 58, 107, 0.12) !important;
        }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 1rem; color: white;
        }
        .stat-icon.blue    { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        .stat-icon.orange  { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
        .stat-icon.emerald { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .stat-icon.slate   { background: linear-gradient(135deg, #64748b 0%, #475569 100%); }
        .stat-icon.navy    { background: linear-gradient(135deg, var(--navy-light) 0%, var(--navy-dark) 100%); }

        /* ── RESPONSIVE ─────────────────────────────────────────── */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-250px);
                padding-top: 0;
            }

            .main-content {
                margin-left: 0 !important;
                padding: 1rem;
            }

            footer {
                margin-left: 0 !important;
                width: 100% !important;
            }

            /* Brand text scaling */
            .noc-info h5    { font-size: 14px; }
            .noc-info p     { font-size: 11px; }
            .noc-info small { font-size: 10px; }
            .noc-logo       { height: 38px; }

            /* Sidebar user info on mobile */
            .user-info h6    { max-width: 120px; font-size: 0.85rem; display: block !important; }
            .user-info small { font-size: 0.7rem; display: block !important; }
            .sidebar-header  { display: flex !important; }

            /* Stat cards — 2 per row on mobile */
            .col-xl-3,
            .col-lg-3,
            .col-md-3,
            .col-md-6 { flex: 0 0 50% !important; max-width: 50% !important; }
            .stat-card { padding: 1rem 0.75rem; }

            /* Keep brand left-aligned on mobile */
            .noc-brand-container { flex: 1; justify-content: flex-start; }
        }

        @media (max-width: 400px) {
            .noc-info p,
            .noc-info small { display: none; }
            .noc-logo { height: 34px; }
        }

        @media (max-width: 767px) {
            .table-responsive-stack { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .card { border-radius: 8px; }
        }

    </style>

    @yield('custom-styles')
    @stack('styles')
</head>

<body>

    <!-- Sidebar backdrop (mobile) -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-light" id="topNavbar">
        <div class="container-fluid">
            <button class="sidebar-toggle-btn" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <div class="noc-brand-container me-auto">
                <img src="{{ asset('images/images.png') }}" alt="Nepal Oil Corporation Logo" class="noc-logo">
                <div class="noc-info">
                    <h5>{{ __('approver.company_name') }}</h5>
                    <p>{{ __('approver.company_address') }}</p>
                    <small>{{ __('approver.company_system') }}</small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-1">

                <!-- Language Switcher -->
                <form method="POST" action="{{ route('language.switch') }}" class="d-flex align-items-center">
                    @csrf
                    @php
                        $nextLocale = app()->getLocale() === 'ne' ? 'en' : 'ne';
                    @endphp
                    <input type="hidden" name="locale" value="{{ $nextLocale }}">
                    <button type="submit" class="language-toggle-btn {{ $nextLocale === 'en' ? 'lang-en' : '' }}" aria-label="Language">
                        {!! $nextLocale === 'ne' ? '&#2344;&#2375;&#2346;&#2366;' : 'En' !!}
                    </button>
                </form>

                <span class="navbar-separator" aria-hidden="true">|</span>

                <!-- Notifications -->
                @php
                    try {
                        $unreadCount = Auth::guard('approver')->check()
                            ? \App\Models\Notification::where('user_id', Auth::guard('approver')->id())
                                ->where('user_type', 'approver')
                                ->where('is_read', false)
                                ->count()
                            : 0;
                    } catch (\Exception $e) {
                        $unreadCount = 0;
                    }
                @endphp
                <a class="nav-link text-dark position-relative notification-link navbar-action-btn"
                   href="{{ route('approver.notifications.index') }}"
                   title="Notifications">
                    <i class="bi bi-bell"></i>
                    @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('approver.logout') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-link nav-link text-dark navbar-action-btn navbar-logout-btn" type="submit"
                        style="white-space:nowrap;">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-sm-inline">{{ __('approver.logout') }}</span>
                    </button>
                </form>

            </div>
        </div>
    </nav>

    <!-- Layout Container -->
    <div class="layout-container">

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="user-profile-sidebar">
                    <div class="user-avatar" style="{{ Auth::guard('approver')->user()->photo ? 'background:none;' : '' }}">
                        @if(Auth::guard('approver')->user()->photo)
                            <img src="{{ asset('storage/' . Auth::guard('approver')->user()->photo) }}"
                                 alt="{{ Auth::guard('approver')->user()->name }}"
                                 style="width:36px;height:36px;border-radius:50%;object-fit:cover;display:block;">
                        @else
                            {{ strtoupper(substr(Auth::guard('approver')->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="user-info">
                        <h6>{{ Auth::guard('approver')->user()->name }}</h6>
                        <small>{{ __('approver.user_role') }}</small>
                    </div>
                </div>
            </div>

            <nav class="sidebar-menu">
                @yield('sidebar-menu')
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>{{ __('approver.validation_errors') }}</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer id="footer">
        <div class="container text-center">
            <p class="mb-0" style="color: #1a3a6b;">&copy; {{ date('Y') }} {{ __('approver.company_name') }}</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const BS_DATA = {
            2081:[31,31,32,31,31,31,30,29,30,29,30,30],
            2082:[31,31,32,31,31,31,30,29,30,29,30,30],
            2083:[31,32,31,32,31,30,30,30,29,29,30,31],
            2084:[30,32,31,32,31,30,30,30,29,30,29,31],
            2085:[31,31,32,31,31,31,30,29,30,29,30,30],
            2086:[31,31,32,32,31,30,30,29,30,29,30,30],
            2087:[31,32,31,32,31,30,30,30,29,29,30,31],
            2088:[30,32,31,32,31,30,30,30,29,30,29,31],
            2089:[31,31,32,31,31,31,30,29,30,29,30,30],
            2090:[31,31,32,32,31,30,30,29,30,29,30,30],
            2091:[31,32,31,32,31,30,30,30,29,29,30,31],
            2092:[30,32,31,32,31,30,30,30,29,30,29,31],
            2093:[31,31,32,31,31,31,30,29,30,29,30,30],
        };
        const NP_MONTHS = ['Baisakh','Jestha','Ashadh','Shrawan','Bhadra','Ashwin',
                           'Kartik','Mangsir','Poush','Magh','Falgun','Chaitra'];

        function getDaysInBSMonth(y, m) {
            return (BS_DATA[y] || [31,31,32,31,31,30,30,29,30,29,30,30])[m];
        }

        function adToBs(adYear, adMonth, adDay) {
            const refAD  = new Date(2024, 3, 14);
            let { year, month, day } = { year:2081, month:0, day:1 };
            let diff = Math.round((new Date(adYear, adMonth, adDay) - refAD) / 86400000);
            if (diff >= 0) {
                while (diff > 0) {
                    const rem = getDaysInBSMonth(year, month) - day;
                    if (diff <= rem) { day += diff; diff = 0; }
                    else { diff -= rem + 1; day = 1; month++; if (month > 11) { month = 0; year++; } }
                }
            } else {
                diff = Math.abs(diff);
                while (diff > 0) {
                    day--;
                    if (day < 1) { month--; if (month < 0) { month = 11; year--; } day = getDaysInBSMonth(year, month); }
                    diff--;
                }
            }
            return { year, month, day };
        }

        function updateDates() {
            const today = new Date();
            const engEl = document.getElementById('english-date');
            const nepEl = document.getElementById('nepali-date');
            if (engEl) engEl.innerText = today.toLocaleDateString('en-US',
                { weekday:'long', year:'numeric', month:'long', day:'numeric' });
            if (nepEl) {
                try {
                    const bs = adToBs(today.getFullYear(), today.getMonth(), today.getDate());
                    nepEl.innerText = `${bs.day} ${NP_MONTHS[bs.month]}, ${bs.year}`;
                } catch(e) { console.error('BS date error:', e); }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const sidebar    = document.getElementById('sidebar');
            const main       = document.getElementById('mainContent');
            const footer     = document.getElementById('footer');
            const toggleBtn  = document.getElementById('sidebarToggle');
            const backdrop   = document.getElementById('sidebarBackdrop');
            const isDesktop  = () => window.innerWidth >= 992;

            let desktopHidden = localStorage.getItem('sidebarHidden') === 'true';

            function applyDesktop() {
                if (desktopHidden) {
                    sidebar.classList.add('hidden');
                    main.classList.add('expanded');
                    if (footer) footer.classList.add('expanded');
                } else {
                    sidebar.classList.remove('hidden');
                    main.classList.remove('expanded');
                    if (footer) footer.classList.remove('expanded');
                }
            }

            let mobileOpen = false;
            function openMobile() {
                mobileOpen = true;
                sidebar.classList.add('mobile-open');
                backdrop.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
            function closeMobile() {
                mobileOpen = false;
                sidebar.classList.remove('mobile-open');
                backdrop.classList.remove('show');
                document.body.style.overflow = '';
            }

            if (isDesktop()) applyDesktop();

            toggleBtn.addEventListener('click', function () {
                if (isDesktop()) {
                    desktopHidden = !desktopHidden;
                    localStorage.setItem('sidebarHidden', desktopHidden);
                    applyDesktop();
                } else {
                    mobileOpen ? closeMobile() : openMobile();
                }
            });

            backdrop.addEventListener('click', closeMobile);

            window.addEventListener('resize', function () {
                if (isDesktop()) {
                    closeMobile();
                    applyDesktop();
                } else {
                    sidebar.classList.remove('hidden');
                    main.classList.remove('expanded');
                    if (footer) footer.classList.remove('expanded');
                }
            });

            updateDates();
            setInterval(updateDates, 60000);
        });
    </script>

    @yield('scripts')
    @stack('scripts')
</body>

</html>
