@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('portal-name', 'Admin Portal')
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()->name)
@section('user-role', 'System Administrator')
@section('user-initial', strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('custom-styles')
    <style>
        /* Perfect Alignment System */
        * {
            box-sizing: border-box;
        }

        :root {
            /* Gold Brand Colors */
            --gold-primary: #c9a84c;
            --gold-dark: #a07828;
            --gold-light: #d4af37;

            /* Status Colors */
            --success: #10b981;
            --warning: #f59e0b;
            --info: #3b82f6;
            --danger: #ef4444;
            --purple: #8b5cf6;

            /* Grays */
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;

            --white: #ffffff;
            --border: 1px solid #e5e7eb;
            --radius: 12px;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);

            /* Animation Timing */
            --spring-smooth: cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #2196F3 0%, #1976d2 100%);
            padding: 32px;
            border-radius: var(--radius);
            margin-bottom: 32px;
            color: var(--white);
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 8px 0;
            line-height: 1.2;
        }

        .header-subtitle {
            font-size: 16px;
            opacity: 0.95;
            margin: 0;
        }

        .header-date {
            text-align: right;
            font-size: 14px;
            opacity: 0.95;
        }

        /* Premium Gradient Background */
        .container-fluid {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            position: relative;
        }

        .container-fluid::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(201, 168, 76, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(160, 120, 40, 0.15) 0%, transparent 50%);
            pointer-events: none;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Stats Grid - 5 Column Layout */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-top: -22px;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        /* Premium Glassmorphism Stat Cards */
        .stat-box {
            background: linear-gradient(135deg,
                    rgba(255, 255, 255, 0.25),
                    rgba(255, 255, 255, 0.15));
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 14px;
            padding: 12px 10px;
            text-align: center;
            transition: all 0.5s var(--spring-smooth);
            cursor: pointer;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: inherit;
            position: relative;
            overflow: hidden;
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.1),
                inset 0 1px 1px rgba(255, 255, 255, 0.3);
            transform: translateY(30px);
            opacity: 0;
            animation: cardEntrance 0.8s var(--spring-smooth) forwards;
            will-change: transform, opacity;
            min-height: 120px;
        }

        /* Staggered Animation Delays */
        .stat-box:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stat-box:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stat-box:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stat-box:nth-child(4) {
            animation-delay: 0.4s;
        }

        .stat-box:nth-child(5) {
            animation-delay: 0.5s;
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Stripe-Style Mouse Tracking Effect */
        .stat-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(600px circle at var(--mouse-x, 50%) var(--mouse-y, 50%),
                    rgba(201, 168, 76, 0.2),
                    transparent 40%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .stat-box:hover::before {
            opacity: 1;
        }

        /* Gold Gradient Top Border */
        .stat-box::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold-light), var(--gold-primary), var(--gold-dark), var(--gold-primary), var(--gold-light));
            border-radius: 20px 20px 0 0;
            transform: scaleX(0);
            transition: transform 0.5s var(--spring-smooth);
            opacity: 0.9;
        }

        .stat-box:hover::after {
            transform: scaleX(1);
        }

        /* Hover State */
        .stat-box:hover {
            background: linear-gradient(135deg,
                    rgba(255, 255, 255, 0.35),
                    rgba(255, 255, 255, 0.25));
            transform: translateY(-12px) scale(1.02);
            box-shadow:
                0 20px 60px rgba(201, 168, 76, 0.25),
                0 12px 40px rgba(0, 0, 0, 0.15),
                inset 0 1px 1px rgba(255, 255, 255, 0.5);
            border-color: rgba(201, 168, 76, 0.4);
            text-decoration: none;
            transition-duration: 0.3s;
        }

        /* Icon Styling */
        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(145deg, var(--gold-primary), var(--gold-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin: 0 auto 10px;
            color: white;
            box-shadow:
                0 4px 16px rgba(201, 168, 76, 0.35),
                inset 0 1px 1px rgba(255, 255, 255, 0.2);
            transition: transform 0.4s var(--spring-smooth);
        }

        .stat-box:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        /* Number Value */
        .stat-value {
            font-family: 'Rajdhani', sans-serif;
            font-size: 28px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 5px;
            color: var(--gray-900);
            text-shadow:
                0 2px 12px rgba(255, 255, 255, 0.8),
                0 4px 20px rgba(255, 255, 255, 0.4),
                0 1px 3px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 1;
        }

        /* Label Text */
        .stat-label {
            font-size: 16px;
            color: var(--gray-700);
            font-weight: 600;
            margin-bottom: 0;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            line-height: 1.4;
            text-shadow:
                0 2px 8px rgba(255, 255, 255, 0.9),
                0 1px 3px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }

        .stat-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 12px;
            border-top: 1px solid var(--gray-100);
        }

        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-up {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-down {
            background: #fee2e2;
            color: #991b1b;
        }

        .stat-text {
            font-size: 13px;
            color: var(--gray-500);
        }

        /* Purple Color Utilities */
        .bg-purple {
            background-color: var(--purple) !important;
        }

        .text-purple {
            color: var(--purple) !important;
        }

        .bg-purple.bg-opacity-10 {
            background-color: rgba(139, 92, 246, 0.1) !important;
        }

        /* Top navbar positioning is handled by layout.dashboard */

        /* Content Layout - Full Width Stacked */
        .content-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .job-middle {
            text-align: center;
            min-width: 0;
            word-break: break-word;
        }
        .job-mid-level {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-800);
            line-height: 1.4;
            margin-bottom: 4px;
        }
        .job-mid-level i {
            color: #c9a84c;
            margin-right: 4px;
        }
        .job-mid-date {
            font-size: 12px;
            color: var(--gray-400);
        }
        .job-mid-date i {
            margin-right: 3px;
        }

        .item-middle {
            text-align: center;
        }

        .job-count-box {
            text-align: center;
        }

        .item-right {
            display: flex;
            justify-content: flex-end;
        }

        .job-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            margin-right: 4px;
        }

        .tag-active   { background: #d1fae5; color: #065f46; }
        .tag-draft    { background: #f3f4f6; color: #374151; }
        .tag-closed   { background: #fee2e2; color: #991b1b; }
        .tag-open, .tag-inclusive { background: #dbeafe; color: #1e40af; }
        .tag-internal { background: #ede9fe; color: #4c1d95; }
        .tag-internal-appraisal, .tag-internal\ appraisal { background: #fef3c7; color: #92400e; }
        .tag-deadline { background: #f9fafb; color: #6b7280; border: 1px solid #e5e7eb; }

        /* Card Component */
        .card {
            background: var(--white);
            border: var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 24px;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-link {
            font-size: 14px;
            font-weight: 500;
            color: #1565C0;
            text-decoration: none;
        }

        .card-link:hover {
            color: #1976d2;
        }

        .card-body {
            padding: 24px;
        }

        /* List Items — 3 equal columns: left | center | right */
        .list-item {
            padding: 18px 24px;
            border-bottom: 1px solid var(--gray-100);
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            align-items: center;
            gap: 16px;
            transition: background 0.15s ease;
        }

        .list-item:hover {
            background: var(--gray-50);
        }

        .list-item:last-child {
            border-bottom: none;
        }

        /* Left section: avatar + name side by side */
        .item-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .item-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .item-content {
            min-width: 0;
        }

        .item-name {
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 4px 0;
        }

        .item-text {
            font-size: 14px;
            color: var(--gray-600);
            margin: 0;
        }

        .item-meta {
            font-size: 13px;
            color: var(--gray-500);
            margin: 6px 0 0 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .item-badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-approved {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-selected {
            background: #e0e7ff;
            color: #3730a3;
        }

        .badge-shortlisted {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Job Cards — 3 equal columns: left | center | right */
        .job-card {
            padding: 18px 24px;
            border-bottom: 1px solid var(--gray-100);
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            align-items: center;
            gap: 16px;
            transition: background 0.15s ease;
        }

        .job-card:hover {
            background: var(--gray-50);
        }

        .job-card:last-child {
            border-bottom: none;
        }

        .job-info {
            min-width: 0;
        }

        .job-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 6px 0;
        }

        .job-meta {
            font-size: 13px;
            color: var(--gray-500);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .job-count-box {
            text-align: center;
            min-width: 80px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
        }

        .job-count {
            font-size: 28px;
            font-weight: 700;
            color: #1976d2;
            line-height: 1;
            margin: 0 0 4px 0;
        }

        .job-count-label {
            font-size: 11px;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Sidebar Widgets */
        .widget {
            background: var(--white);
            border: var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 24px;
        }

        .widget-header {
            padding: 16px 20px;
            border-bottom: var(--border);
        }

        .widget-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Collapsible Widget */
        .widget-header[data-bs-toggle="collapse"]:hover {
            background: rgba(0, 0, 0, 0.02);
        }

        .toggle-icon.collapsed {
            transform: rotate(-90deg);
        }

        .widget-body {
            padding: 16px 20px;
        }

        /* Action Buttons */
        .btn-action {
            width: 100%;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 12px;
            text-decoration: none;
        }

        .btn-action:last-child {
            margin-bottom: 0;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2196F3, #2196F3);
            color: var(--white);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
            color: var(--white);
        }

        .btn-secondary {
            background: var(--white);
            color: #2196F3;
            border: 2px solid var(--gray-200);
        }

        .btn-secondary:hover {
            border-color: #2196F3;
            background: #eef2ff;
            color: #1976d2;
        }

        /* Reviewer Items */
        .reviewer-item {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-100);
            transition: background 0.15s ease;
        }

        .reviewer-item:hover {
            background: var(--gray-50);
        }

        .reviewer-item:last-child {
            border-bottom: none;
        }

        .reviewer-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .reviewer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .reviewer-info {
            flex: 1;
            min-width: 0;
        }

        .reviewer-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 2px 0;
        }

        .reviewer-email {
            font-size: 12px;
            color: var(--gray-500);
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .reviewer-stats {
            display: flex;
            gap: 16px;
            font-size: 13px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Status Rows */
        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .status-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .status-label {
            font-size: 14px;
            color: var(--gray-700);
            font-weight: 500;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            background: #d1fae5;
            color: #065f46;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 24px;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-400);
            font-size: 28px;
        }

        .empty-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 6px 0;
        }

        .empty-text {
            font-size: 14px;
            color: var(--gray-500);
            margin: 0;
        }

        /* Purple Color Utilities (Kept for compatibility) */
        .bg-purple {
            background-color: var(--purple) !important;
        }

        .text-purple {
            color: var(--purple) !important;
        }

        .bg-purple.bg-opacity-10 {
            background-color: rgba(139, 92, 246, 0.1) !important;
        }

        /* Responsive Design - Keep 5 columns as long as possible */
        @media (max-width: 1400px) {
            .stats-grid {
                grid-template-columns: repeat(5, 1fr);
                gap: 10px;
            }

            .stat-box {
                padding: 10px 8px;
                min-height: 110px;
            }

            .stat-icon {
                width: 36px;
                height: 36px;
                font-size: 16px;
                margin-bottom: 8px;
            }

            .stat-value {
                font-size: 24px;
            }

            .stat-label {
                font-size: 13px;
            }
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 14px;
            }

            .stat-box {
                padding: 12px 10px;
                min-height: 120px;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }

            .stat-value {
                font-size: 26px;
            }

            .stat-label {
                font-size: 14px;
            }
        }

        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 14px;
            }

            .stat-box {
                padding: 14px 12px;
                min-height: 130px;
            }

            .stat-icon {
                width: 44px;
                height: 44px;
                font-size: 18px;
                margin-bottom: 10px;
            }

            .stat-value {
                font-size: 28px;
            }

            .stat-label {
                font-size: 14px;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
                margin-top: 0;
            }

            .page-header {
                padding: 24px;
            }

            .header-title {
                font-size: 24px;
            }

            .stat-box {
                min-height: 120px;
            }

            .stat-value {
                font-size: 26px;
            }

            .stat-label {
                font-size: 14px;
            }
        }

        @media (max-width: 576px) {
            .stat-box {
                padding: 12px 10px;
                min-height: 110px;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
                margin-bottom: 8px;
            }

            .stat-value {
                font-size: 24px;
                margin-bottom: 4px;
            }

            .stat-label {
                font-size: 13px;
            }
        }

        /* Accessibility: Reduced Motion Support */
        @media (prefers-reduced-motion: reduce) {

            .stat-box,
            .stat-icon,
            .container-fluid {
                animation: none !important;
                transition-duration: 0.01ms !important;
            }

            .stat-box {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Accessibility: Focus States */
        .stat-box:focus-visible {
            outline: 3px solid var(--gold-primary);
            outline-offset: 4px;
            border-color: var(--gold-primary);
        }

        /* Dark Mode Support (Optional Enhancement) */
        @media (prefers-color-scheme: dark) {
            .stat-box {
                background: linear-gradient(135deg,
                        rgba(30, 41, 59, 0.7),
                        rgba(30, 41, 59, 0.5));
                border-color: rgba(255, 255, 255, 0.15);
            }

            .stat-value {
                color: var(--white);
                text-shadow:
                    0 2px 12px rgba(0, 0, 0, 0.8),
                    0 1px 3px rgba(255, 255, 255, 0.2);
            }

            .stat-label {
                color: var(--gray-300);
                text-shadow:
                    0 2px 8px rgba(0, 0, 0, 0.9),
                    0 1px 3px rgba(255, 255, 255, 0.1);
            }
        }

        /* Performance Optimization */
        .stat-box {
            /* Enable hardware acceleration */
            transform: translateZ(0);
            backface-visibility: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Print Styles */
        @media print {
            .container-fluid {
                background: white !important;
            }

            .stat-box {
                background: white !important;
                border: 2px solid var(--gray-300) !important;
                backdrop-filter: none !important;
                break-inside: avoid;
            }

            .stat-box::before,
            .stat-box::after {
                display: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
<<<<<<< HEAD
    <!-- <div class="page-header">
                <div class="header-row">
                    <div>
                        <h1 class="header-title">Welcome back, {{ Auth::guard('admin')->user()->name }}!</h1>
                        <p class="header-subtitle">Here's what's happening with your recruitment system today</p>
                    </div>
                    <div class="header-date">
                        <div style="font-weight: 600; margin-bottom: 4px;">{{ now()->format('l') }}</div>
                        <div>{{ now()->format('F d, Y') }}</div>
                    </div>
                </div>
            </div> -->
=======
    <div class="page-header">
        <div class="header-row">
            <div>
                <h1 class="header-title">Welcome, {{ Auth::guard('admin')->user()->name }}!</h1>
                <p class="header-subtitle">Here's what's happening with your recruitment system today</p>
            </div>
            <div class="header-date">
                <div style="font-weight: 600; margin-bottom: 4px;">{{ now()->format('l') }}</div>
                <div>{{ now()->format('F d, Y') }}</div>
            </div>
        </div>
    </div>
>>>>>>> 55e8c2322fd9818955a408f1f667542e5cee9f98

    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Stat 1 -->
        <a href="{{ route('admin.jobs.index') }}" class="stat-box">
            <!-- <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-briefcase-fill"></i>
                    </div> -->
            <div class="stat-value">{{ $stats['active_vacancies'] }}</div>
            <div class="stat-label">Active Vacancies</div>
            <!-- <div class="stat-meta">
                        @if($growth['jobs_posted'] != 0)
                            <span class="stat-badge {{ $growth['jobs_posted'] > 0 ? 'badge-up' : 'badge-down' }}">
                                <i class="bi bi-arrow-{{ $growth['jobs_posted'] > 0 ? 'up' : 'down' }}"></i>
                                {{ abs($growth['jobs_posted']) }}%
                            </span>
                        @endif
                        <span class="stat-text">{{ $thisMonth['jobs_posted'] }} this month</span>
                    </div> -->
        </a>

        <!-- Stat 2 -->
        <a href="{{ route('admin.applications.index') }}" class="stat-box">
            <!-- <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div> -->
            <div class="stat-value">{{ $stats['pending_applications'] }}</div>
            <div class="stat-label">Pending Reviews</div>
            <!-- <div class="stat-meta">
                        @if($growth['applications'] != 0)
                            <span class="stat-badge {{ $growth['applications'] > 0 ? 'badge-up' : 'badge-down' }}">
                                <i class="bi bi-arrow-{{ $growth['applications'] > 0 ? 'up' : 'down' }}"></i>
                                {{ abs($growth['applications']) }}%
                            </span>
                        @endif
                        <span class="stat-text">{{ $thisMonth['applications'] }} received</span>
                    </div> -->
        </a>

        <!-- Stat 3 - Total Candidates -->
        <a href="{{ route('admin.candidates.index') }}" class="stat-box">
            <!-- <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-people-fill"></i>
                    </div> -->
            <div class="stat-value">{{ $stats['total_candidates'] }}</div>
            <div class="stat-label">Candidates</div>
            <!-- <div class="stat-meta">
                        @if($growth['candidates'] != 0)
                            <span class="stat-badge {{ $growth['candidates'] > 0 ? 'badge-up' : 'badge-down' }}">
                                <i class="bi bi-arrow-{{ $growth['candidates'] > 0 ? 'up' : 'down' }}"></i>
                                {{ abs($growth['candidates']) }}%
                            </span>
                        @endif
                        <span class="stat-text">{{ $thisMonth['candidates'] }} this month</span>
                    </div> -->
        </a>

        <!-- Stat 4 -->
        <a href="{{ route('admin.reviewers.index') }}" class="stat-box">
            <!-- <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-person-badge-fill"></i>
                    </div> -->
            <div class="stat-value">{{ $stats['active_reviewers'] }}</div>
            <div class="stat-label">Active Reviewers</div>
            <!-- <div class="stat-meta">
                        <span class="stat-text">{{ $stats['total_reviewers'] }} total reviewers</span>
                    </div> -->
        </a>

        <!-- Stat 5 - HR Administrators/Approvers -->
        <a href="{{ route('admin.approvers.index') }}" class="stat-box">
            <!-- <div class="stat-icon bg-purple bg-opacity-10 text-purple">
                        <i class="bi bi-person-check-fill"></i>
                    </div> -->
            <div class="stat-value">{{ $stats['active_approvers'] }}</div>
            <div class="stat-label">Active Approvers</div>
            <!-- <div class="stat-meta">
                        <span class="stat-text">{{ $stats['total_approvers'] }} total approvers</span>
                    </div> -->
        </a>
    </div>

    <!-- Content Layout — side by side -->
    <div class="content-layout">
        <!-- Applications per Vacancy -->
        <div class="card" style="margin-bottom:0;">
            <div class="card-header">
                <h3 class="card-title">
                    <!-- <i class="bi bi-briefcase-fill text-primary" style="font-size:16px;"></i> -->
                    Applications per Vacancy
                </h3>
                <a href="{{ route('admin.jobs.index') }}" class="card-link">View All →</a>
            </div>
            <div>
                @forelse($topJobs as $vacancy)
                    <div class="job-card">
                        {{-- Left: title + meta --}}
                        <div class="job-info">
                            <h4 class="job-title">{{ $vacancy->title }}</h4>
                            <p class="job-meta">
                                @if($vacancy->position_level)
                                    <!-- <span><i class="bi bi-person-badge"></i> 
                                    </span> -->
                                  <span>  {{ $vacancy->advertisement_no }} </span>
                                @endif
                                <!-- <span><i class="bi bi-building"></i> 
                                
                            </span> -->
                            <span>{{ $vacancy->service_group ?? $vacancy->department }}</span>
                            
                                <!-- <span><i class="bi bi-geo-alt"></i> {{ $vacancy->location }}</span> -->
                            </p>
                        </div>

                        {{-- Middle: qualification + deadline BS --}}
                        <div class="job-middle">
                            <div style="margin-bottom:5px;">
                                <span style="display:inline-block; background:#f1f5f9; color:var(--gray-700); font-size:11px; font-weight:600; padding:3px 10px; border-radius:20px; max-width:100%; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    <i class="bi bi-mortarboard" style="color:#c9a84c; margin-right:3px;"></i>{{ $vacancy->minimum_qualification ?? 'N/A' }}
                                </span>
                            </div>
                            @if($vacancy->deadline_bs)
                                <div style="font-size:12px; color:var(--gray-400);">
                                    <i class="bi bi-calendar-event" style="margin-right:3px;"></i>{{ $vacancy->deadline_bs }}
                                </div>
                            @elseif($vacancy->deadline)
                                <div style="font-size:12px; color:var(--gray-400);">
                                    <i class="bi bi-calendar-event" style="margin-right:3px;"></i>{{ \Carbon\Carbon::parse($vacancy->deadline)->format('M d, Y') }}
                                </div>
                            @endif
                        </div>

                        {{-- Right: application count --}}
                        <div class="job-count-box">
                            <div class="job-count">{{ $vacancy->application_forms_count ?? 0 }}</div>
                            <div class="job-count-label">Applied</div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-briefcase"></i></div>
                        <h4 class="empty-title">No Vacancies Posted</h4>
                        <p class="empty-text">
                            <a href="{{ route('admin.jobs.create') }}" style="color:#1976d2;">Post your first vacancy</a>
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="card" style="margin-bottom:0;">
            <div class="card-header">
                <h3 class="card-title">
                    <!-- <i class="bi bi-clock-history text-warning" style="font-size:16px;"></i> -->
                    Recent Applications
                </h3>
                <a href="{{ route('admin.applications.index') }}" class="card-link">View All →</a>
            </div>
            <div>
                @forelse($recentApplications as $application)
                    <div class="list-item">
                        {{-- LEFT: avatar + name + position --}}
                        <div class="item-left">
                            @if($application->passport_size_photo)
                                <img src="{{ asset('storage/' . $application->passport_size_photo) }}"
                                    alt="{{ $application->name_english }}"
                                    class="item-avatar" style="object-fit:cover;">
                            @else
                                <div class="item-avatar bg-primary bg-opacity-10 text-primary">
                                    {{ strtoupper(substr($application->name_english ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div class="item-content">
                                <h4 class="item-name">{{ $application->name_english ?? 'Unknown' }}</h4>
                                <p class="item-text">
                                    <!-- <i class="bi bi-briefcase" style="font-size:11px;"></i> -->
                                    {{ $application->job->title ?? $application->vacancy->title ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        {{-- CENTER: submitted date + time ago --}}
                        <div class="item-middle">
                            <div style="font-size:13px; color:var(--gray-700); font-weight:600;">
                                <!-- <i class="bi bi-calendar3" style="color:var(--gray-400);"></i> -->
                               Applied on: {{ $application->created_at->format('M d, Y') }}
                            </div>
                            <div style="font-size:12px; color:var(--gray-400); margin-top:3px;">
                                <!-- <i class="bi bi-clock"></i> -->
                                {{ $application->created_at->diffForHumans() }}
                            </div>
                        </div>

                        {{-- RIGHT: status badge --}}
                        <div class="item-right">
                            <span class="item-badge badge-{{ $application->status }}">
                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                        <h4 class="empty-title">No Recent Applications</h4>
                        <p class="empty-text">New applications will appear here</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

        <!-- Sidebar (removed) -->
        <!-- <div> -->
            <!-- Quick Actions -->
            <!-- <div class="widget">
                        <div class="widget-header">
                            <h3 class="widget-title">

                                Quick Actions
                            </h3>
                        </div>
                        <div class="widget-body">
                            <a href="{{ route('admin.jobs.create') }}" class="btn-action btn-primary">
                                <i class="bi bi-plus-circle"></i>
                                Post New Vacancy
                            </a>
                            <a href="{{ route('admin.reviewers.create') }}" class="btn-action btn-secondary">
                                <i class="bi bi-person-plus"></i>
                                Add Reviewer
                            </a>
                            <a href="{{ route('admin.approvers.create') }}" class="btn-action btn-secondary">
                                <i class="bi bi-person-plus"></i>
                                Add Approver
                            </a>
                            <button class="btn-action btn-secondary" onclick="alert('Coming soon!')">
                                <i class="bi bi-download"></i>
                                Export Report
                            </button>
                        </div>
                    </div> -->

            <!-- Active Reviewers -->
            <!-- <div class="widget">
                    <div class="widget-header d-flex justify-content-between align-items-center" style="cursor: pointer;"
                        data-bs-toggle="collapse" data-bs-target="#reviewerContent" aria-expanded="true">
                        <h3 class="widget-title">

                        </h3>
                        <i class="bi bi-chevron-down toggle-icon"
                            style="color: #64748b; font-size: 16px; transition: transform 0.3s ease;"></i>
                    </div>
                    <div class="collapse show" id="reviewerContent">
                        @forelse($reviewerStats as $reviewer)
                            <div class="reviewer-item">
                                <div class="reviewer-row">
                                    @if($reviewer->photo)
                                        <img src="{{ asset('storage/' . $reviewer->photo) }}" alt="{{ $reviewer->name }}"
                                            class="reviewer-avatar" style="object-fit: cover;">
                                    @else
                                        <div class="reviewer-avatar bg-success bg-opacity-10 text-success">
                                            {{ strtoupper(substr($reviewer->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="reviewer-info">
                                        <h4 class="reviewer-name">{{ $reviewer->name }}</h4>
                                        <p class="reviewer-email">{{ $reviewer->email }}</p>
                                    </div>
                                </div>
                                <div class="reviewer-stats">
                                    <span class="stat-item text-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ $reviewer->total_reviewed }} reviewed
                                    </span>
                                    <span class="stat-item text-warning">
                                        <i class="bi bi-hourglass"></i>
                                        {{ $reviewer->pending }} pending
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state" style="padding: 40px 20px;">
                                <div class="empty-icon" style="width: 48px; height: 48px; font-size: 20px; margin-bottom: 12px;">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <h4 class="empty-title" style="font-size: 14px;">No Active Reviewers</h4>
                                <p class="empty-text" style="font-size: 13px;">Add reviewers to start</p>
                            </div>
                        @endforelse
                    </div>
                </div> -->

            <!-- Active Approvers -->
            <!-- <div class="widget">
                    <div class="widget-header d-flex justify-content-between align-items-center" style="cursor: pointer;"
                        data-bs-toggle="collapse" data-bs-target="#approverContent" aria-expanded="true">
                        <h3 class="widget-title">

                        </h3>
                        <i class="bi bi-chevron-down toggle-icon"
                            style="color: #64748b; font-size: 16px; transition: transform 0.3s ease;"></i>
                    </div>
                    <div class="collapse show" id="approverContent">
                        @forelse($approverStats ?? [] as $approver)
                            <div class="reviewer-item">
                                <div class="reviewer-row">
                                    @if($approver->photo ?? false)
                                        <img src="{{ asset('storage/' . $approver->photo) }}" alt="{{ $approver->name }}"
                                            class="reviewer-avatar" style="object-fit: cover;">
                                    @else
                                        <div class="reviewer-avatar bg-primary bg-opacity-10 text-primary">
                                            {{ strtoupper(substr($approver->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="reviewer-info">
                                        <h4 class="reviewer-name">{{ $approver->name }}</h4>
                                        <p class="reviewer-email">{{ $approver->email }}</p>
                                    </div>
                                </div>
                                <div class="reviewer-stats">
                                    <span class="stat-item text-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ $approver->approved_count ?? 0 }} approved
                                    </span>
                                    <span class="stat-item text-danger">
                                        <i class="bi bi-x-circle-fill"></i>
                                        {{ $approver->rejected_count ?? 0 }} rejected
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state" style="padding: 40px 20px;">
                                <div class="empty-icon" style="width: 48px; height: 48px; font-size: 20px; margin-bottom: 12px;">
                                    <i class="bi bi-person-check"></i>
                                </div>
                                <h4 class="empty-title" style="font-size: 14px;">No Active Approvers</h4>
                                <p class="empty-text" style="font-size: 13px;">Add approvers to start</p>
                            </div>
                        @endforelse
                    </div>
                </div> -->

            <!-- System Status -->
            <!-- <div class="widget">
                        <div class="widget-header">
                            <h3 class="widget-title">
                                <i class="bi bi-activity text-info"></i>
                                System Status
                            </h3>
                        </div>
                        <div class="widget-body">
                            <div class="status-row">
                                <span class="status-label">Database</span>
                                <span class="status-indicator">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Healthy
                                </span>
                            </div>
                            <div class="status-row">
                                <span class="status-label">Storage</span>
                                <span class="status-indicator">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Healthy
                                </span>
                            </div>
                            <div class="status-row">
                                <span class="status-label">System Load</span>
                                <span class="status-indicator">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Normal
                                </span>
                            </div>
                        </div>
                    </div> -->
        <!-- </div> -->
@endsection

@section('scripts')
    <script>
        console.log('✅ Premium Dashboard Loaded!');

        document.addEventListener('DOMContentLoaded', function () {
            // ========================================
            // STRIPE-STYLE MOUSE TRACKING EFFECT
            // ========================================
            const statCards = document.querySelectorAll('.stat-box');

            statCards.forEach(card => {
                card.addEventListener('mousemove', (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    card.style.setProperty('--mouse-x', `${x}px`);
                    card.style.setProperty('--mouse-y', `${y}px`);
                });

                // Dynamic will-change for performance
                card.addEventListener('mouseenter', () => {
                    card.style.willChange = 'transform, box-shadow';
                });

                card.addEventListener('mouseleave', () => {
                    setTimeout(() => {
                        card.style.willChange = 'auto';
                    }, 500);
                });
            });

            // ========================================
            // ANIMATED NUMBER COUNTERS
            // ========================================
            function animateCounter(element, target, duration = 2000) {
                const startTime = performance.now();

                function update(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    // Ease-out cubic for smooth deceleration
                    const easeOut = 1 - Math.pow(1 - progress, 3);
                    const current = Math.floor(target * easeOut);

                    element.textContent = current.toLocaleString();

                    if (progress < 1) {
                        requestAnimationFrame(update);
                    } else {
                        element.textContent = target.toLocaleString();
                    }
                }

                requestAnimationFrame(update);
            }

            // Trigger counter animations after card entrance animations
            setTimeout(() => {
                document.querySelectorAll('.stat-value').forEach((element, index) => {
                    const target = parseInt(element.textContent.replace(/,/g, ''));
                    if (!isNaN(target)) {
                        setTimeout(() => {
                            animateCounter(element, target, 2000);
                        }, index * 100);
                    }
                });
            }, 600);

            // ========================================
            // WIDGET COLLAPSE TOGGLES
            // ========================================
            const reviewerContent = document.getElementById('reviewerContent');
            const approverContent = document.getElementById('approverContent');

            if (reviewerContent) {
                reviewerContent.addEventListener('show.bs.collapse', function () {
                    const icon = document.querySelector('[data-bs-target="#reviewerContent"] .toggle-icon');
                    if (icon) icon.classList.remove('collapsed');
                });

                reviewerContent.addEventListener('hide.bs.collapse', function () {
                    const icon = document.querySelector('[data-bs-target="#reviewerContent"] .toggle-icon');
                    if (icon) icon.classList.add('collapsed');
                });
            }

            if (approverContent) {
                approverContent.addEventListener('show.bs.collapse', function () {
                    const icon = document.querySelector('[data-bs-target="#approverContent"] .toggle-icon');
                    if (icon) icon.classList.remove('collapsed');
                });

                approverContent.addEventListener('hide.bs.collapse', function () {
                    const icon = document.querySelector('[data-bs-target="#approverContent"] .toggle-icon');
                    if (icon) icon.classList.add('collapsed');
                });
            }
        });
    </script>
@endsection