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

        /* Top Navbar */
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            position: sticky;
            top: 0;
            z-index: 1030;
            transition: padding-left 0.3s ease;
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
            color: white;
            line-height: 1.2;
        }

        .noc-info p {
            margin: 0;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.2;
        }

        .noc-info small {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.75);
            font-style: italic;
            display: block;
            margin-top: 2px;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            transition: background-color 0.2s ease;
            margin-right: 1rem;
        }

        .sidebar-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Layout Container */
        .layout-container {
            display: flex;
            min-height: calc(100vh - 70px);
            transition: margin-left 0.3s ease;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            position: fixed;
            left: 0;
            top: 70px;
            height: calc(100vh - 56px);
            overflow-y: hidden;
            flex-shrink: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            z-index: 1020;
        }

        .sidebar.hidden {
            transform: translateX(-260px);
        }

        .sidebar-header {
            padding: 1rem 1.25rem;
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
            font-size: 0.9rem;
        }

        .user-info h6 {
            margin: 0;
            font-size: 0.95rem; 
            font-weight: 600;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 160px;
        }

        .user-info small {
            font-size: 0.75rem; 
            color: rgba(255, 255, 255, 0.6);
            display: block;
        }

        .sidebar-menu {
            padding: 0.75rem 0; 
            flex: 1;
            overflow-y: auto; 
        }

        .sidebar-menu-item {
            padding: 0.7rem 1.25rem; 
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-size: 0.9rem; 
        }

        .sidebar-menu-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            border-left-color: #2563eb;
        }

        .sidebar-menu-item.active {
            background: rgba(37, 99, 235, 0.15);
            color: white;
            border-left-color: #2563eb;
            font-weight: 500;
        }

        .sidebar-menu-item i {
            font-size: 1.15rem; 
            width: 22px; 
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

        /* Footer */
        footer {
            background-color: #2563eb;
            color: white;
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
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

    </style>
    @yield('custom-styles')

    @stack('styles')
</head>

<body>

    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary" id="topNavbar">
        <div class="container-fluid">
            <!-- Sidebar Toggle Button -->
            <button class="sidebar-toggle-btn" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <!-- NOC Logo and Brand -->
            <div class="noc-brand-container">
                <img src="{{ asset('images/noc_logo.jpeg') }}" alt="Nepal Oil Corporation Logo" class="noc-logo">
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
                    <!-- Candidate Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('candidate.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    <!-- My Applications -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('candidate.applications.index') }}">
                            <i class="bi bi-file-earmark-text"></i> My Applications
                        </a>
                    </li>

                    <!-- Logout -->
                    <li class="nav-item">
                        <form method="POST" action="{{ route('candidate.logout') }}" class="d-inline">
                            @csrf
                            <button class="btn btn-link nav-link" type="submit">
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
        
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="user-profile-sidebar">
                    @php
                        $candidateName = 'User';
                        $candidateInitial = 'U';
                        
                        // Get candidate from session
                        if (session()->has('candidate_id')) {
                            $candidateId = session('candidate_id');
                            
                            // Fetch candidate from database
                            $candidate = \DB::table('candidate_registration')
                                ->where('id', $candidateId)
                                ->first();
                            
                            if ($candidate && !empty($candidate->name)) {
                                $candidateName = $candidate->name;
                                $candidateInitial = strtoupper(substr($candidateName, 0, 1));
                            } elseif ($candidate && !empty($candidate->email)) {
                                $candidateName = explode('@', $candidate->email)[0];
                                $candidateInitial = strtoupper(substr($candidateName, 0, 1));
                            }
                        }
                    @endphp
                    <div class="user-avatar">
                        {{ $candidateInitial }}
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

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const footer = document.getElementById('footer');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            // Load saved state from localStorage or default to visible
            let isHidden = localStorage.getItem('sidebarHidden') === 'true';
            
            // Apply initial state
            if (isHidden) {
                sidebar.classList.add('hidden');
                mainContent.classList.add('expanded');
                footer.classList.add('expanded');
            }

            // Toggle functionality
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
                
                // Save state to localStorage
                localStorage.setItem('sidebarHidden', isHidden);
            });
        });
    </script>

    @stack('scripts')

</body>

</html>