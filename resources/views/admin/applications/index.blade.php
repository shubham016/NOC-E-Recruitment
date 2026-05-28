@extends('layouts.dashboard')

@section('title', __('admin.applications_management'))

@section('portal-name', __('admin.portal_name'))
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()->name)
@section('user-role', __('admin.system_administrator'))
@section('user-initial', strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/government-professional.css') }}">
<style>
    /* Page Header Spacing */
    .gov-page-header {
        margin-bottom: 2rem !important;
        padding: 2rem;
        background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
        border-radius: 10px;
    }

    .gov-page-header .gov-page-title {
        color: white !important;
    }

    .gov-page-header .gov-page-subtitle {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    /* Modern Table - Matching Vacancy List Style */
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
        color: #000;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid #000;
        white-space: nowrap;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        text-align: center;
    }

    .modern-table tbody td {
        padding: 1rem 1.25rem;
        color: #000;
        border: 1px solid #060606;
        vertical-align: middle;
    }

    .modern-table tbody tr {
        transition: all 0.2s;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
    }

    /* =============================================
       APPLICATION DETAIL MODAL — Professional
       ============================================= */
    .app-detail-modal .modal-dialog {
        max-width: 860px;
    }

    .app-detail-modal .modal-content {
        border: 1px solid rgba(201, 168, 76, 0.25);
        border-radius: 14px;
        box-shadow: 0 32px 64px -12px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255,255,255,0.05);
        overflow: hidden;
        background: #fff;
    }

    /* Glassy header — matches reviewers table thead gradient with glass overlay */
    .app-detail-modal .modal-header {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: none;
        border-top: 4px solid #c9a84c;
        padding: 1.1rem 1.75rem;
        position: relative;
    }

    .app-detail-modal .modal-header::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(201,168,76,0.4), transparent);
    }

    .app-detail-modal .modal-header .modal-title {
        color: #1f2937;
        font-weight: 700;
        font-size: 1rem;
    }

    .app-detail-modal .modal-header .btn-close {
        opacity: 0.5;
        transition: opacity 0.2s;
    }

    .app-detail-modal .modal-header .btn-close:hover {
        opacity: 1;
    }

    /* Body — scrollable */
    .app-detail-modal .modal-body {
        padding: 0;
        max-height: 72vh;
        overflow-y: auto;
        background: #fff;
    }

    .app-detail-modal .modal-body::-webkit-scrollbar { width: 5px; }
    .app-detail-modal .modal-body::-webkit-scrollbar-track { background: #f3f4f6; }
    .app-detail-modal .modal-body::-webkit-scrollbar-thumb { background: #c9a84c; border-radius: 4px; }

    /* Profile Hero — centered */
    .app-modal-profile {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 2rem 2rem 1.5rem;
        background: linear-gradient(160deg, #fefdf8 0%, #faf8f0 100%);
        border-bottom: 1px solid #f0e8d0;
        position: relative;
    }

    .app-modal-profile::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #c9a84c, #f0d080, #c9a84c);
    }

    .app-modal-avatar-wrap {
        position: relative;
        margin-bottom: 1rem;
    }

    .app-modal-avatar {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 0 0 3px #c9a84c, 0 8px 24px rgba(160,120,40,0.2);
    }

    .app-modal-avatar-placeholder {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: linear-gradient(135deg, #c9a84c, #a07828);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.25rem;
        font-weight: 700;
        border: 3px solid #fff;
        box-shadow: 0 0 0 3px #c9a84c, 0 8px 24px rgba(160,120,40,0.2);
    }

    .app-modal-appid {
        position: absolute;
        bottom: -4px;
        right: -4px;
        background: #c9a84c;
        color: #fff;
        font-size: 0.6rem;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 10px;
        border: 2px solid #fff;
        white-space: nowrap;
    }

    .app-modal-name {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.2rem;
        line-height: 1.2;
    }

    .app-modal-name-np {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 0.75rem;
    }

    .app-modal-meta {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.5rem 1.25rem;
        font-size: 0.82rem;
        color: #6b7280;
        margin-bottom: 0.85rem;
    }

    .app-modal-meta span {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .app-modal-badges {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.4rem;
    }

    .app-modal-status {
        display: inline-block;
        padding: 0.3rem 0.9rem;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    .app-modal-status.pending    { background: #fef3c7; color: #92400e; }
    .app-modal-status.submitted  { background: #dbeafe; color: #1e40af; }
    .app-modal-status.assigned   { background: #e0e7ff; color: #3730a3; }
    .app-modal-status.approved   { background: #d1fae5; color: #065f46; }
    .app-modal-status.rejected   { background: #fee2e2; color: #991b1b; }
    .app-modal-status.under_review { background: #dbeafe; color: #1e40af; }
    .app-modal-status.edited     { background: #fef3c7; color: #92400e; }
    .app-modal-status.edit       { background: #fef3c7; color: #92400e; }
    .app-modal-status.reviewed   { background: #e0e7ff; color: #4338ca; }

    .app-modal-cat-badge {
        display: inline-block;
        padding: 0.3rem 0.9rem;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        background: linear-gradient(135deg, rgba(201,168,76,0.15), rgba(160,120,40,0.1));
        color: #a07828;
        border: 1px solid rgba(201,168,76,0.35);
    }

    /* Sections */
    .app-modal-sections {
        padding: 0 1.5rem 1rem;
    }

    .app-modal-section {
        padding: 1.1rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .app-modal-section:last-child {
        border-bottom: none;
    }

    .app-modal-section-title {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #a07828;
        margin-bottom: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .app-modal-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, #f0e2ba, transparent);
    }

    .app-modal-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem 1.5rem;
    }

    .app-modal-grid-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .app-modal-field {
        display: flex;
        flex-direction: column;
    }

    .app-modal-field-label {
        font-size: 0.68rem;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 0.2rem;
    }

    .app-modal-field-value {
        font-size: 0.875rem;
        color: #1f2937;
        font-weight: 500;
        line-height: 1.4;
    }

    /* Documents grid */
    .app-modal-docs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 0.75rem;
    }

    .app-modal-doc-card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.2s;
        background: #f9fafb;
    }

    .app-modal-doc-card:hover {
        border-color: #c9a84c;
        box-shadow: 0 4px 12px rgba(201,168,76,0.15);
        transform: translateY(-2px);
    }

    .app-modal-doc-preview {
        width: 100%;
        height: 100px;
        object-fit: cover;
        display: block;
        background: #e5e7eb;
    }

    .app-modal-doc-preview-icon {
        width: 100%;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        font-size: 2rem;
        color: #9ca3af;
    }

    .app-modal-doc-label {
        padding: 0.45rem 0.6rem;
        background: #fff;
        border-top: 1px solid #e5e7eb;
    }

    .app-modal-doc-label a {
        font-size: 0.72rem;
        font-weight: 600;
        color: #374151;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .app-modal-doc-label a:hover {
        color: #a07828;
    }

    /* Loading */
    .app-modal-loading {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 4rem 2rem;
        color: #6b7280;
        gap: 0.75rem;
    }

    .app-modal-loading .spinner-border {
        width: 2.5rem;
        height: 2.5rem;
        color: #c9a84c;
    }

    /* Footer */
    .app-modal-footer {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-top: 1px solid #e5e7eb;
        padding: 0.85rem 1.75rem;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.5rem;
    }

    @media (max-width: 768px) {
        .app-modal-grid { grid-template-columns: repeat(2, 1fr); }
        .app-detail-modal .modal-dialog { max-width: 95%; margin: 0.5rem auto; }
        .app-modal-docs-grid { grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); }
    }

    @media (max-width: 480px) {
        .app-modal-grid { grid-template-columns: 1fr; }
    }

    /* Compact columns for NOC Employee and Employee Code */
    .modern-table th.compact-col-noc {
        width: 90px;
        max-width: 90px;
        white-space: normal;
        word-wrap: break-word;
        padding: 0.75rem 0.5rem !important;
        line-height: 1.2;
        font-size: 0.75rem;
    }

    .modern-table td.compact-col-noc {
        width: 90px;
        max-width: 90px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 0.5rem !important;
    }

    .modern-table th.compact-col-code {
        width: 110px;
        max-width: 110px;
        white-space: normal;
        word-wrap: break-word;
        padding: 0.75rem 0.5rem !important;
        line-height: 1.2;
        font-size: 0.75rem;
    }

    .modern-table td.compact-col-code {
        width: 110px;
        max-width: 110px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 0.5rem !important;
    }

</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4 gov-page-container">

    <!-- Professional Page Header -->
    <div class="gov-page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="gov-page-title">{{ __('admin.applications_management') }}</h1>
                <p class="gov-page-subtitle">{{ __('admin.system_subtitle') }}</p>
            </div>
            <div style="position: relative; z-index: 10;">
                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#bulkActionModal"
                   style="border: 2px solid white; color: white; padding: 0.5rem 1.5rem; font-weight: 600; border-radius: 6px; transition: all 0.2s; cursor: pointer; background: transparent;">
                    <i class="bi bi-lightning-fill me-1"></i> {{ __('admin.bulk_actions') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="gov-alert gov-alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="gov-alert gov-alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    <!-- Statistics Cards - Modern Design -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-3 col-md-6">
            <div class="gov-stats-card">
                <div class="gov-stats-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);">
                    <i class="bi bi-file-earmark-text" style="color: white;"></i>
                </div>
                <h3 class="gov-stats-number">{{ $stats['total'] }}</h3>
                <p class="gov-stats-label">{{ __('admin.total') }} {{ __('admin.applications_label') }}</p>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6">
            <div class="gov-stats-card">
                <div class="gov-stats-icon" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">
                    <i class="bi bi-clock-history" style="color: white;"></i>
                </div>
                <h3 class="gov-stats-number">{{ $stats['pending'] }}</h3>
                <p class="gov-stats-label">{{ __('admin.pending_review') }}</p>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6">
            <div class="gov-stats-card">
                <div class="gov-stats-icon" style="background: linear-gradient(135deg, #34d399 0%, #10b981 100%);">
                    <i class="bi bi-check-circle-fill" style="color: white;"></i>
                </div>
                <h3 class="gov-stats-number">{{ $stats['approved'] ?? 0 }}</h3>
                <p class="gov-stats-label">{{ __('admin.approved') }}</p>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6">
            <div class="gov-stats-card">
                <div class="gov-stats-icon" style="background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);">
                    <i class="bi bi-x-circle" style="color: white;"></i>
                </div>
                <h3 class="gov-stats-number">{{ $stats['rejected'] }}</h3>
                <p class="gov-stats-label">{{ __('admin.rejected') }}</p>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="gov-card">
        <div class="gov-card-header">
            <i class="bi bi-funnel"></i>
            <span>{{ __('admin.filter_applications') }}</span>
        </div>
        <div class="gov-card-body">
            <form action="{{ route('admin.applications.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-3">
                        <label class="gov-form-label d-block mb-2">{{ __('admin.search') }}</label>
                        <input type="text" name="search" class="form-control gov-form-control"
                               placeholder="{{ __('admin.ph_search_applications') }}"
                               value="{{ request('search') }}"
                               style="height: 45px;">
                    </div>
                    <div class="col-lg-2">
                        <label class="gov-form-label d-block mb-2">{{ __('admin.status') }}</label>
                        <select name="status" class="form-select" style="height: 45px;">
                            <option value="">{{ __('admin.all_status') }}</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ __('admin.' . $status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="gov-form-label d-block mb-2">{{ __('admin.vacancy_position') }}</label>
                        <select name="job_id" class="form-select" style="height: 45px;">
                            <option value="">{{ __('admin.all_positions') }}</option>
                            @foreach($vacancies as $vacancy)
                                <option value="{{ $vacancy->id }}" {{ request('job_id') == $vacancy->id ? 'selected' : '' }}>
                                    {{ $vacancy->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="gov-form-label d-block mb-2">{{ __('admin.assigned_reviewer') }}</label>
                        <select name="reviewer_id" class="form-select" style="height: 45px;">
                            <option value="">{{ __('admin.all_reviewers') }}</option>
                            @foreach($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}" {{ request('reviewer_id') == $reviewer->id ? 'selected' : '' }}>
                                    {{ $reviewer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="gov-form-label d-block mb-2">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="gov-btn gov-btn-primary flex-grow-1" style="height: 45px;">
                                <i class="bi bi-search"></i> {{ __('admin.search') }}
                            </button>
                            <!-- <a href="{{ route('admin.applications.index') }}" class="gov-btn gov-btn-secondary" style="height: 45px; width: 45px; display: flex; align-items: center; justify-content: center; padding: 0;">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a> -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar" class="gov-card" style="display: none; margin-bottom: 1.5rem;">
        <div class="gov-card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold text-dark me-3">
                        <i class="bi bi-check-square me-2"></i>
                        <span id="selectedCount">0</span> {{ __('admin.apps_selected') }}
                    </span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">
                        <i class="bi bi-x-circle me-1"></i>{{ __('admin.clear_selection') }}
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-success" onclick="exportSelected('csv')">
                        <i class="bi bi-file-earmark-excel me-1"></i>{{ __('admin.export_to_excel') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="exportSelected('pdf')">
                        <i class="bi bi-file-earmark-pdf me-1"></i>{{ __('admin.export_to_pdf') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="gov-card">
        <div class="gov-card-header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <span><i class="bi bi-table"></i> {{ __('admin.applications_list') }}</span>
                <span class="gov-badge gov-badge-primary">{{ $applications->total() }} {{ __('admin.total') }}</span>
            </div>
        </div>
        <div class="gov-card-body-no-padding">
            @if($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 modern-table w-100">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center text-uppercase">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th class="text-center text-uppercase">{{ __('admin.sn') }}</th>
                                <th class="text-center text-uppercase">{{ __('admin.candidate_information') }}</th>
                                <th class="text-center text-uppercase">{{ __('admin.vacancy_applied_for') }}</th>
                                <th class="text-center text-uppercase">{{ __('admin.contact_details') }}</th>
                                <th class="text-center text-uppercase">{{ __('admin.applied_date') }}</th>
                                <th class="text-center text-uppercase compact-col-noc">{{ __('admin.noc_employee') }}</th>
                                <th class="text-center text-uppercase compact-col-code">{{ __('admin.employee_code') }}</th>
                                <th class="text-center text-uppercase">{{ __('admin.assigned_reviewer') }}</th>
                                <th class="text-center text-uppercase">{{ __('admin.assigned_approver') }}</th>
                                <th class="text-center text-uppercase">{{ __('admin.status') }}</th>
                                <th class="text-center text-uppercase">{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-center align-middle">
                            @foreach($applications as $index => $application)
                                <tr class="application-row {{ $application->status }}">
                                    <td class="text-center">
                                        <input type="checkbox" name="application_ids[]"
                                               value="{{ $application->id }}"
                                               class="form-check-input application-checkbox">
                                    </td>
                                    <td class="text-center">
                                        <span class="gov-badge gov-badge-secondary">{{ $applications->firstItem() + $index }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($application->passport_size_photo)
                                                <img src="{{ asset('storage/' . $application->passport_size_photo) }}"
                                                     class="gov-avatar me-3"
                                                     alt="Photo">
                                            @else
                                                <div class="gov-avatar-placeholder me-3">
                                                    {{ strtoupper(substr($application->name_english ?? '?', 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="gov-font-semibold gov-text-md" style="color: #1f2937;">
                                                    {{ $application->name_english ?? '-' }}
                                                </div>
                                                <small class="gov-text-sm" style="color: #6b7280;">
                                                    {{ __('admin.application_id_label') }} {{ $application->id }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="gov-font-semibold gov-text-md" style="color: #1f2937;">
                                            {{ $application->applying_position ?? '-' }}
                                        </div>
                                        <small class="gov-text-sm" style="color: #6b7280;">
                                            {{ $application->advertisement_no ?? '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="gov-text-sm d-flex align-items-center" style="line-height: 1.6; margin-bottom: 0.25rem;">
                                            <i class="bi bi-envelope me-2" style="color: #6b7280; flex-shrink: 0;"></i>
                                            <span>{{ Str::limit(trim($application->email ?? '-'), 22) }}</span>
                                        </div>
                                        <div class="gov-text-sm d-flex align-items-center" style="line-height: 1.6;">
                                            <i class="bi bi-telephone me-2" style="color: #6b7280; flex-shrink: 0;"></i>
                                            <span>{{ trim($application->phone ?? '-') }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="gov-font-semibold gov-text-sm" style="color: #1f2937;">
                                            {{ adToBS($application->submitted_at ?? $application->created_at) }}
                                        </div>
                                        <small class="gov-text-sm" style="color: #6b7280;">
                                            {{ ($application->submitted_at ?? $application->created_at)->format('h:i A') }}
                                        </small>
                                    </td>
                                    <td class="compact-col-noc">
                                        @php
                                            $nocVal = strtolower($application->candidateRegistration->noc_employee ?? $application->noc_employee ?? 'no');
                                        @endphp
                                        {{ $nocVal === 'yes' ? __('admin.yes') : __('admin.no') }}
                                    </td>
                                    <td class="compact-col-code">
                                        {{ $nocVal === 'yes' ? ($application->candidateRegistration->employee_id ?? '-') : '-' }}
                                    </td>
                                    <td>
                                        @if($application->reviewer)
                                            <div class="gov-font-semibold gov-text-sm" style="color: #1f2937;">
                                                {{ $application->reviewer->name }}
                                            </div>
                                            <small class="gov-text-sm" style="color: #6b7280;">
                                                {{ Str::limit($application->reviewer->email, 20) }}
                                            </small>
                                        @else
                                            <span class="gov-badge gov-badge-secondary">{{ __('admin.not_assigned') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($application->approver)
                                            <div class="gov-font-semibold gov-text-sm" style="color: #1f2937;">
                                                {{ $application->approver->name }}
                                            </div>
                                            <small class="gov-text-sm" style="color: #6b7280;">
                                                {{ Str::limit($application->approver->email, 20) }}
                                            </small>
                                        @else
                                            <span class="gov-badge gov-badge-secondary">{{ __('admin.not_assigned') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="gov-font-semibold gov-text-sm" style="color: #1f2937; font-weight: 600;">
                                            {{ __('admin.' . $application->status) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button"
                                               class="gov-action-btn"
                                               title="{{ __('admin.view_details') }}"
                                               onclick="viewApplication({{ $application->id }})">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @if($application->reviewer_id)
                                                <button type="button"
                                                        class="gov-action-btn gov-action-btn-success"
                                                        title="{{ __('admin.reviewer_already_assigned') }} {{ $application->reviewer->name ?? 'N/A' }}"
                                                        disabled style="opacity:0.45; cursor:not-allowed;">
                                                    <i class="bi bi-person-check"></i>
                                                </button>
                                            @else
                                                <button type="button"
                                                        class="gov-action-btn gov-action-btn-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#assignModal{{ $application->id }}"
                                                        title="{{ __('admin.assign_reviewer') }}">
                                                    <i class="bi bi-person-plus"></i>
                                                </button>
                                            @endif

                                            @if($application->approver_id)
                                                <button type="button"
                                                        class="gov-action-btn gov-action-btn-success"
                                                        title="{{ __('admin.approver_already_assigned') }} {{ $application->approver->name ?? 'N/A' }}"
                                                        disabled style="opacity:0.45; cursor:not-allowed;">
                                                    <i class="bi bi-person-check"></i>
                                                </button>
                                            @else
                                                <button type="button"
                                                        class="gov-action-btn gov-action-btn-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#assignApproverModal{{ $application->id }}"
                                                        title="{{ __('admin.assign_approver') }}">
                                                    <i class="bi bi-person-check"></i>
                                                </button>
                                            @endif
                                            <button type="button"
                                                    class="gov-action-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#statusModal{{ $application->id }}"
                                                    title="{{ __('admin.update_status') }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button"
                                                    class="gov-action-btn gov-action-btn-danger"
                                                    onclick="deleteApplication({{ $application->id }})"
                                                    title="{{ __('admin.delete') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Status Update Modal -->
                                <div class="modal fade" id="statusModal{{ $application->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
                                            <form action="{{ route('admin.applications.updateStatus', $application) }}" method="POST">
                                                @csrf
                                                <div class="modal-header" style="background: linear-gradient(to bottom, white 0%, #f9fafb 100%); border-bottom: 2px solid #e5e7eb; padding: 1.5rem;">
                                                    <h5 class="modal-title fw-bold" style="color: #1f2937;">
                                                        <i class="bi bi-pencil-square me-2" style="color: #1e40af;"></i>{{ __('admin.update_application_status') }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="gov-form-label">{{ __('admin.select_new_status') }}</label>
                                                        <select name="status" class="form-select gov-form-select" required style="height: 50px;">
                                                            @foreach($statuses as $status)
                                                                <option value="{{ $status }}" {{ $application->status == $status ? 'selected' : '' }}>
                                                                    {{ __('admin.' . $status) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="gov-form-label">{{ __('admin.admin_notes') }}</label>
                                                        <textarea name="admin_notes" class="form-control gov-form-control" rows="4"
                                                                  placeholder="{{ __('admin.ph_remarks') }}"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="background: linear-gradient(to top, #f9fafb 0%, white 100%); border-top: 2px solid #e5e7eb; padding: 1.25rem;">
                                                    <button type="button" class="gov-btn gov-btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                                                    <button type="submit" class="gov-btn gov-btn-primary">
                                                        <i class="bi bi-check-circle"></i> {{ __('admin.update_status') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Assign Reviewer Modal -->
                                <div class="modal fade" id="assignModal{{ $application->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
                                            <form action="{{ route('admin.applications.assignReviewer', $application) }}" method="POST">
                                                @csrf
                                                <div class="modal-header" style="background: linear-gradient(to bottom, white 0%, #f9fafb 100%); border-bottom: 2px solid #e5e7eb; padding: 1.5rem;">
                                                    <h5 class="modal-title fw-bold" style="color: #1f2937;">
                                                        <i class="bi bi-person-plus me-2" style="color: #059669;"></i>{{ __('admin.assign_reviewer') }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="gov-form-label">{{ __('admin.assigned_reviewer') }}</label>
                                                        <select name="reviewer_id" class="form-select gov-form-select" required style="height: 50px;">
                                                            <option value="">{{ __('admin.choose_reviewer') }}</option>
                                                            @foreach($reviewers as $reviewer)
                                                                <option value="{{ $reviewer->id }}" {{ $application->reviewer_id == $reviewer->id ? 'selected' : '' }}>
                                                                    {{ $reviewer->name }} ({{ $reviewer->email }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="background: linear-gradient(to top, #f9fafb 0%, white 100%); border-top: 2px solid #e5e7eb; padding: 1.25rem;">
                                                    <button type="button" class="gov-btn gov-btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                                                    <button type="submit" class="gov-btn gov-btn-primary">
                                                        <i class="bi bi-person-check"></i> {{ __('admin.assign_reviewer') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Assign Approver Modal -->
                                <div class="modal fade" id="assignApproverModal{{ $application->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
                                            <form action="{{ route('admin.applications.assignApprover', $application) }}" method="POST">
                                                @csrf
                                                <div class="modal-header" style="background: linear-gradient(to bottom, white 0%, #f9fafb 100%); border-bottom: 2px solid #e5e7eb; padding: 1.5rem;">
                                                    <h5 class="modal-title fw-bold" style="color: #1f2937;">
                                                        <i class="bi bi-person-check-fill me-2" style="color: #7c3aed;"></i>{{ __('admin.assign_approver') }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="gov-form-label">{{ __('admin.assigned_approver') }}</label>
                                                        <select name="approver_id" class="form-select gov-form-select" required style="height: 50px;">
                                                            <option value="">{{ __('admin.choose_approver') }}</option>
                                                            @foreach($approvers as $approver)
                                                                <option value="{{ $approver->id }}" {{ $application->approver_id == $approver->id ? 'selected' : '' }}>
                                                                    {{ $approver->name }} ({{ $approver->email }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="background: linear-gradient(to top, #f9fafb 0%, white 100%); border-top: 2px solid #e5e7eb; padding: 1.25rem;">
                                                    <button type="button" class="gov-btn gov-btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                                                    <button type="submit" class="gov-btn gov-btn-primary" style="background-color: #7c3aed; border-color: #7c3aed;">
                                                        <i class="bi bi-person-check-fill"></i> {{ __('admin.assign_approver') }}
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

                @if($applications->hasPages())
                <!-- Pagination Footer -->
                <div class="gov-pagination-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="gov-text-md" style="color: #6b7280;">
                            {{ __('admin.showing') }} <strong style="color: #1f2937;">{{ $applications->firstItem() }}</strong> {{ __('admin.to') }}
                            <strong style="color: #1f2937;">{{ $applications->lastItem() }}</strong> {{ __('admin.of') }}
                            <strong style="color: #1f2937;">{{ $applications->total() }}</strong> {{ __('admin.applications_label') }}
                        </div>
                        <div>
                            {{ $applications->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="gov-empty-state">
                    <div class="gov-empty-state-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h4 class="gov-empty-state-title">{{ __('admin.no_applications_found') }}</h4>
                    <p class="gov-empty-state-text">
                        @if(request()->hasAny(['search', 'status', 'job_id', 'reviewer_id']))
                            {{ __('admin.no_apps_filter_msg') }}<br>
                            {{ __('admin.no_apps_filter_hint') }}
                        @else
                            {{ __('admin.no_apps_system_msg') }}<br>
                            {{ __('admin.no_apps_system_hint') }}
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'status', 'job_id', 'reviewer_id']))
                        <a href="{{ route('admin.applications.index') }}" class="gov-btn gov-btn-primary">
                            <i class="bi bi-arrow-clockwise"></i> {{ __('admin.clear_all_filters') }}
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
            <form id="bulkActionForm" action="{{ route('admin.applications.bulkAction') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: linear-gradient(to bottom, white 0%, #f9fafb 100%); border-bottom: 2px solid #e5e7eb; padding: 1.5rem;">
                    <h5 class="modal-title fw-bold" style="color: #1f2937;">
                        <i class="bi bi-check2-square me-2" style="color: #1e40af;"></i>{{ __('admin.bulk_actions') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>{{ __('admin.validation_error') }}</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="gov-form-label">{{ __('admin.select_action_type') }}</label>
                        <select name="action" id="bulkAction" class="form-select gov-form-select" required style="height: 50px;">
                            <option value="">{{ __('admin.choose_action') }}</option>
                            <option value="update_status">{{ __('admin.update_status') }}</option>
                            <option value="assign_reviewer">{{ __('admin.assign_reviewer') }}</option>
                            <option value="assign_approver">{{ __('admin.assign_approver') }}</option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="statusSelection">
                        <label class="gov-form-label">{{ __('admin.new_status') }}</label>
                        <select name="status" class="form-select gov-form-select">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}">{{ __('admin.' . $status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="reviewerSelection">
                        <label class="gov-form-label">{{ __('admin.assign_to_reviewer') }}</label>
                        <select name="reviewer_id" class="form-select gov-form-select">
                            <option value="">{{ __('admin.choose_reviewer') }}</option>
                            @foreach($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}">{{ $reviewer->name }} ({{ $reviewer->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="approverSelection">
                        <label class="gov-form-label">{{ __('admin.assign_to_approver') }}</label>
                        <select name="approver_id" class="form-select gov-form-select">
                            <option value="">{{ __('admin.choose_approver') }}</option>
                            @foreach($approvers as $approver)
                                <option value="{{ $approver->id }}">{{ $approver->name }} ({{ $approver->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Advertisement Number assignment — shown when reviewer or approver is selected --}}
                    <div class="mb-3 d-none" id="advertisementSelection">
                        <hr class="my-3">
                        <label class="gov-form-label fw-bold">{{ __('admin.assign_by_adv_no') }}</label>
                        <small class="d-block text-muted mb-2">{{ __('admin.select_adv_to_assign') }} <strong>{{ __('admin.all_its_applications') }}</strong> {{ __('admin.to_the_person_above') }}</small>
                        <select name="job_posting_id" id="jobPostingSelect" class="form-select gov-form-select">
                            <option value="">{{ __('admin.select_adv_no') }}</option>
                            @foreach($vacancies as $vacancy)
                                <option value="{{ $vacancy->id }}" data-count="{{ $vacancy->group_applications_count }}">
                                    {{ $vacancy->advertisement_no }} - {{ $vacancy->title }}{{ $vacancy->level ? ' - ' . __('admin.level') . ' ' . $vacancy->level : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted mt-1 d-block">{{ __('admin.leave_blank_checkbox') }}</small>
                    </div>

                    <div class="gov-alert gov-alert-info mb-0" id="modalInfoAlert">
                        <i class="bi bi-info-circle"></i>
                        <span id="modalInfoText"><span id="modalSelectedCount">0</span> {{ __('admin.apps_selected_checkbox') }}</span>
                    </div>
                </div>
                <div class="modal-footer" style="background: linear-gradient(to top, #f9fafb 0%, white 100%); border-top: 2px solid #e5e7eb; padding: 1.25rem;">
                    <button type="button" class="gov-btn gov-btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="gov-btn gov-btn-primary">
                        <i class="bi bi-check-circle"></i> {{ __('admin.apply_action') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Application Detail Modal -->
<div class="modal fade app-detail-modal" id="applicationDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.application_details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="applicationDetailBody">
                <div class="app-modal-loading">
                    <div class="spinner-border" role="status"></div>
                    <span>{{ __('admin.loading_application') }}</span>
                </div>
            </div>
            <div class="app-modal-footer">
                <button type="button" class="gov-btn gov-btn-secondary" data-bs-dismiss="modal">{{ __('admin.close') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Forms -->
@foreach($applications as $application)
    <form id="deleteForm{{ $application->id }}" action="{{ route('admin.applications.destroy', $application) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endforeach

@endsection

@section('scripts')
<script>
    // PHP → JS translation map (server-side locale applied)
    const _t = {
        vacancy_information:    "{{ __('admin.vacancy_information') }}",
        personal_information:   "{{ __('admin.personal_information') }}",
        family_details:         "{{ __('admin.family_details') }}",
        address_information:    "{{ __('admin.address_information') }}",
        educational_background: "{{ __('admin.educational_background') }}",
        work_experience:        "{{ __('admin.work_experience') }}",
        assignment_timeline:    "{{ __('admin.assignment_timeline') }}",
        uploaded_documents:     "{{ __('admin.uploaded_documents') }}",
        application_status_history: "{{ __('admin.application_status_history') }}",
        position:               "{{ __('admin.position') }}",
        advertisement_no:       "{{ __('admin.advertisement_no') }}",
        department:             "{{ __('admin.department') }}",
        citizenship_no:         "{{ __('admin.citizenship_no') }}",
        issue_district:         "{{ __('admin.issue_district') }}",
        nationality:            "{{ __('admin.nationality') }}",
        religion:               "{{ __('admin.religion') }}",
        community:              "{{ __('admin.community') }}",
        noc_employee:           "{{ __('admin.noc_employee') }}",
        marital_status:         "{{ __('admin.marital_status') }}",
        father:                 "{{ __('admin.father') }}",
        mother:                 "{{ __('admin.mother') }}",
        grandfather:            "{{ __('admin.grandfather') }}",
        spouse:                 "{{ __('admin.spouse') }}",
        permanent_address:      "{{ __('admin.permanent_address') }}",
        mailing_address:        "{{ __('admin.mailing_address') }}",
        education_level:        "{{ __('admin.education_level') }}",
        field_of_study:         "{{ __('admin.field_of_study') }}",
        institution:            "{{ __('admin.institution') }}",
        university:             "{{ __('admin.university') }}",
        graduation_year:        "{{ __('admin.graduation_year') }}",
        reviewer:               "{{ __('admin.reviewer') }}",
        approver:               "{{ __('admin.approver') }}",
        applied_on:             "{{ __('admin.applied_on_col') }}",
        last_updated:           "{{ __('admin.last_updated') }}",
        view_document:          "{{ __('admin.view_document') }}",
        sn:                     "{{ __('admin.sn') }}",
        stage_name:             "{{ __('admin.stage_name') }}",
        done_by:                "{{ __('admin.done_by') }}",
        date_time:              "{{ __('admin.date') }}",
        remarks:                "{{ __('admin.remarks') }}",
        no_history:             "{{ __('admin.no_history') }}",
        dob_bs:                 "{{ __('admin.dob_bs_short') }}",
        dob_ad:                 "{{ __('admin.dob_ad_short') }}",
        experience:             "{{ __('admin.experience_n') }}",
        experience_n:           "{{ __('admin.experience_n') }}",
        age_years:              "{{ __('admin.age_years') }}",
        age_months:             "{{ __('admin.age_months') }}",
        age_days:               "{{ __('admin.age_days') }}",
        age_yrs:                "{{ __('admin.age_yrs') }}",
        gender_male:            "{{ __('admin.gender_male') }}",
        gender_female:          "{{ __('admin.gender_female') }}",
        gender_other:           "{{ __('admin.gender_other') }}",
        yes_label:              "{{ __('admin.yes_label') }}",
        no_label:               "{{ __('admin.no_label') }}",
        // alert / confirm / inline strings
        failed_load_details:    "{{ __('admin.failed_load_details') }}",
        delete_app_confirm:     "{{ __('admin.delete_app_confirm') }}",
        select_adv_or_app:      "{{ __('admin.select_adv_or_app') }}",
        update_status_select:   "{{ __('admin.update_status_select_one') }}",
        exporting:              "{{ __('admin.exporting') }}",
        apps_assigned_from_adv: "{{ __('admin.apps_assigned_from_adv') }}",
        apps_selected_checkbox: "{{ __('admin.apps_selected_checkbox') }}",
        will_be_assigned_to:    "{{ __('admin.will_be_assigned_to') }}",
        ward_prefix:            "{{ __('admin.ward_prefix') }}",
        present_str:            "{{ __('admin.present_str') }}",
        id_colon:               "{{ __('admin.id_colon') }}",
        age_colon:              "{{ __('admin.age_colon') }}",
        pdf_label:              "{{ __('admin.pdf_label') }}",
        select_app_export:      "{{ __('admin.select_app_export') }}",
        // document labels
        doc_passport_photo:         "{{ __('admin.doc_passport_photo') }}",
        doc_citizenship:            "{{ __('admin.doc_citizenship') }}",
        doc_noc_id_card:            "{{ __('admin.doc_noc_id_card') }}",
        doc_ethnic_certificate:     "{{ __('admin.doc_ethnic_certificate') }}",
        doc_disability_certificate: "{{ __('admin.doc_disability_certificate') }}",
        doc_signature:              "{{ __('admin.doc_signature') }}",
        doc_transcript:             "{{ __('admin.doc_transcript') }}",
        doc_character_certificate:  "{{ __('admin.doc_character_certificate') }}",
    };

    // Function to open bulk action modal
    function openBulkActionModal() {
        console.log('openBulkActionModal() called');
        const modalElement = document.getElementById('bulkActionModal');
        if (modalElement) {
            try {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
                console.log('✓ Modal opened via openBulkActionModal()');
                return true;
            } catch (error) {
                console.error('✗ Error opening modal:', error);
                return false;
            }
        } else {
            console.error('✗ Modal element not found!');
            return false;
        }
    }

    // Debug: Check if Bootstrap is loaded
    console.log('Bootstrap loaded:', typeof bootstrap !== 'undefined');
    console.log('Modal element exists:', document.getElementById('bulkActionModal') !== null);

    // Test modal opening on page load
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('bulkActionModal');

        // IMPORTANT: Force close any open modals on page load to prevent blocking
        if (modalElement) {
            // Remove show class and hide modal if it's open
            modalElement.classList.remove('show');
            modalElement.style.display = 'none';
            modalElement.setAttribute('aria-hidden', 'true');
            modalElement.removeAttribute('aria-modal');

            // Remove modal backdrop if exists
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }

            // Remove modal-open class from body
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';

            console.log('✓ Bulk Action Modal found and ensured closed');
        }

        if (modalElement) {
            console.log('✓ Bulk Action Modal ready');

            // Update count when modal is shown
            modalElement.addEventListener('show.bs.modal', function() {
                const count = document.querySelectorAll('.application-checkbox:checked').length;
                const modalCountElement = document.getElementById('modalSelectedCount');
                if (modalCountElement) {
                    modalCountElement.textContent = count;
                }
                console.log('Modal opening with', count, 'applications selected');
            });
        } else {
            console.error('✗ Bulk Action Modal NOT found');
        }



    // Select All Checkbox
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.application-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        updateSelectedCount();
    });

    // Individual Checkbox
    document.querySelectorAll('.application-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            const allChecked = document.querySelectorAll('.application-checkbox:checked').length ===
                              document.querySelectorAll('.application-checkbox').length;
            if (document.getElementById('selectAll')) {
                document.getElementById('selectAll').checked = allChecked;
            }
        });
    });

    // Update Count and Show/Hide Bulk Actions Bar
    function updateSelectedCount() {
        const count = document.querySelectorAll('.application-checkbox:checked').length;
        const countElement = document.getElementById('selectedCount');
        const bulkActionsBar = document.getElementById('bulkActionsBar');

        if (countElement) {
            countElement.textContent = count;
        }

        // Only update modal info if no advertisement number is selected
        const jobPostingSelect = document.getElementById('jobPostingSelect');
        if (!jobPostingSelect || !jobPostingSelect.value) {
            const infoText = document.getElementById('modalInfoText');
            if (infoText) {
                infoText.innerHTML = '<span id="modalSelectedCount">' + count + '</span> ' + _t.apps_selected_checkbox;
            }
        }

        // Show/hide bulk actions bar based on selection
        if (bulkActionsBar) {
            if (count > 0) {
                bulkActionsBar.style.display = 'block';
            } else {
                bulkActionsBar.style.display = 'none';
            }
        }
    }

    // Clear Selection
    function clearSelection() {
        document.querySelectorAll('.application-checkbox:checked').forEach(cb => {
            cb.checked = false;
        });
        if (document.getElementById('selectAll')) {
            document.getElementById('selectAll').checked = false;
        }
        updateSelectedCount();
    }

    // Export Selected Applications
    function exportSelected(type) {
        const selected = [];

        document.querySelectorAll('.application-checkbox:checked').forEach(cb => {
            selected.push(cb.value);
        });

        if (selected.length === 0) {
            alert(_t.select_app_export);
            return;
        }

        // Use the existing export route with type and selected IDs
        let url = "{{ route('admin.applications.export') }}";
        url += '?type=' + type + '&ids=' + selected.join(',');

        window.location.href = url;
    }

    // Make functions globally accessible
    window.clearSelection = clearSelection;
    window.exportSelected = exportSelected;

    // Bulk Action Type
    const bulkActionSelect = document.getElementById('bulkAction');
    if (bulkActionSelect) {
        bulkActionSelect.addEventListener('change', function() {
            const statusDiv      = document.getElementById('statusSelection');
            const reviewerDiv    = document.getElementById('reviewerSelection');
            const approverDiv    = document.getElementById('approverSelection');
            const advertisementDiv = document.getElementById('advertisementSelection');

            // Hide all sections first
            if (statusDiv)        statusDiv.classList.add('d-none');
            if (reviewerDiv)      reviewerDiv.classList.add('d-none');
            if (approverDiv)      approverDiv.classList.add('d-none');
            if (advertisementDiv) advertisementDiv.classList.add('d-none');

            // Reset advertisement dropdown and info text
            const jobPostingSelect = document.getElementById('jobPostingSelect');
            if (jobPostingSelect) jobPostingSelect.value = '';
            updateModalInfoText();

            // Show relevant section based on action
            if (this.value === 'update_status') {
                if (statusDiv) statusDiv.classList.remove('d-none');
            } else if (this.value === 'assign_reviewer') {
                if (reviewerDiv)      reviewerDiv.classList.remove('d-none');
                if (advertisementDiv) advertisementDiv.classList.remove('d-none');
            } else if (this.value === 'assign_approver') {
                if (approverDiv)      approverDiv.classList.remove('d-none');
                if (advertisementDiv) advertisementDiv.classList.remove('d-none');
            }
        });
    }

    // Update the info alert based on advertisement selection vs checkbox selection
    function updateModalInfoText() {
        const jobPostingSelect   = document.getElementById('jobPostingSelect');
        const infoText           = document.getElementById('modalInfoText');
        const bulkActionVal      = document.getElementById('bulkAction')?.value;
        if (!infoText) return;

        const selectedOption = jobPostingSelect ? jobPostingSelect.options[jobPostingSelect.selectedIndex] : null;
        const jobCount       = selectedOption && selectedOption.value ? parseInt(selectedOption.dataset.count || 0) : null;

        // Get the selected reviewer or approver name
        let assigneeName = '';
        if (bulkActionVal === 'assign_reviewer') {
            const reviewerSel = document.querySelector('#reviewerSelection select');
            const reviewerOpt = reviewerSel ? reviewerSel.options[reviewerSel.selectedIndex] : null;
            if (reviewerOpt && reviewerOpt.value) assigneeName = reviewerOpt.text;
        } else if (bulkActionVal === 'assign_approver') {
            const approverSel = document.querySelector('#approverSelection select');
            const approverOpt = approverSel ? approverSel.options[approverSel.selectedIndex] : null;
            if (approverOpt && approverOpt.value) assigneeName = approverOpt.text;
        }

        if (jobCount !== null) {
            const advLabel = selectedOption.text.trim();
            let msg = '<strong>' + jobCount + '</strong> ' + _t.apps_assigned_from_adv + ' <strong>' + advLabel + '</strong>';
            if (assigneeName) msg += ' ' + _t.will_be_assigned_to + ' <strong>' + assigneeName + '</strong>';
            infoText.innerHTML = msg;
        } else {
            const checkboxCount = document.querySelectorAll('.application-checkbox:checked').length;
            let msg = '<span id="modalSelectedCount">' + checkboxCount + '</span> ' + _t.apps_selected_checkbox;
            if (assigneeName) msg += ' — ' + _t.will_be_assigned_to + ' <strong>' + assigneeName + '</strong>';
            infoText.innerHTML = msg;
        }
    }

    // Wire advertisement, reviewer, and approver dropdowns to update info text
    document.getElementById('jobPostingSelect')?.addEventListener('change', updateModalInfoText);
    document.querySelector('#reviewerSelection select')?.addEventListener('change', updateModalInfoText);
    document.querySelector('#approverSelection select')?.addEventListener('change', updateModalInfoText);

    // Bulk Form Submit
    const bulkForm = document.getElementById('bulkActionForm');
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const action         = document.getElementById('bulkAction')?.value;
            const jobPostingId   = document.getElementById('jobPostingSelect')?.value;
            const selected       = document.querySelectorAll('.application-checkbox:checked');

            // For assign actions: either an advertisement number OR checkbox selection is required
            if ((action === 'assign_reviewer' || action === 'assign_approver')) {
                if (!jobPostingId && selected.length === 0) {
                    e.preventDefault();
                    alert(_t.select_adv_or_app);
                    return false;
                }
                // If advertisement number chosen, no need to append checkbox IDs
                if (jobPostingId) {
                    return true; // job_posting_id is already in the form as a named select
                }
            }

            // For update_status: checkboxes required
            if (action === 'update_status' && selected.length === 0) {
                e.preventDefault();
                alert(_t.update_status_select);
                return false;
            }

            // Append checked checkbox IDs as hidden inputs
            selected.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'application_ids[]';
                input.value = checkbox.value;
                this.appendChild(input);
            });
        });
    }

    // Delete Single
    function deleteApplication(id) {
        if (confirm(_t.delete_app_confirm)) {
            const form = document.getElementById('deleteForm' + id);
            if (form) form.submit();
        }
    }

    // Export Data with current filters
    function exportData() {
        console.log('Exporting data with current filters...');

        // Get current filter values from the form
        const search = document.querySelector('input[name="search"]')?.value || '';
        const status = document.querySelector('select[name="status"]')?.value || '';
        const jobId = document.querySelector('select[name="job_id"]')?.value || '';
        const reviewerId = document.querySelector('select[name="reviewer_id"]')?.value || '';
        const dateFrom = document.querySelector('input[name="date_from"]')?.value || '';
        const dateTo = document.querySelector('input[name="date_to"]')?.value || '';
        const sortBy = '{{ request("sort_by", "created_at") }}';
        const sortOrder = '{{ request("sort_order", "desc") }}';

        // Build export URL with filters
        const exportUrl = new URL('{{ route("admin.applications.export") }}', window.location.origin);

        if (search) exportUrl.searchParams.append('search', search);
        if (status) exportUrl.searchParams.append('status', status);
        if (jobId) exportUrl.searchParams.append('job_id', jobId);
        if (reviewerId) exportUrl.searchParams.append('reviewer_id', reviewerId);
        if (dateFrom) exportUrl.searchParams.append('date_from', dateFrom);
        if (dateTo) exportUrl.searchParams.append('date_to', dateTo);
        exportUrl.searchParams.append('sort_by', sortBy);
        exportUrl.searchParams.append('sort_order', sortOrder);

        console.log('Export URL:', exportUrl.toString());

        // Show loading indicator
        const btn = document.getElementById('exportDataButton');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> ' + _t.exporting;
        btn.disabled = true;

        // Redirect to export URL (will download file)
        window.location.href = exportUrl.toString();

        // Reset button after delay
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }, 3000);
    }

    // Auto-dismiss alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const bsAlert = bootstrap.Alert.getInstance(alert) || new bootstrap.Alert(alert);
            if (bsAlert) bsAlert.close();
        });
    }, 5000);

    // Reopen bulk action modal if there are validation errors (only after modal cleanup)
    @if($errors->any())
        setTimeout(() => {
            const modalEl = document.getElementById('bulkActionModal');
            if (modalEl) {
                const bulkModal = new bootstrap.Modal(modalEl);
                bulkModal.show();
                console.log('ℹ️ Modal reopened due to validation errors');
            }
        }, 600); // Wait for cleanup to complete
    @endif

    // Debug: Log form submission
    document.getElementById('bulkActionForm')?.addEventListener('submit', function(e) {
        console.log('Form submitting with action:', document.getElementById('bulkAction')?.value);
        console.log('Selected applications:', document.querySelectorAll('.application-checkbox:checked').length);
    });

    }); // end DOMContentLoaded

    // View Application in Modal
    function viewApplication(id) {
        const modal = new bootstrap.Modal(document.getElementById('applicationDetailModal'));
        const body = document.getElementById('applicationDetailBody');

        // Show loading
        body.innerHTML = '<div class="app-modal-loading"><div class="spinner-border" role="status"></div><span>{{ __('admin.loading_application') }}</span></div>';
        modal.show();

        fetch('/admin/applications/' + id, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(d => {
            body.innerHTML = renderApplicationDetail(d);
            if (typeof window._convertToNepaliNum === 'function') {
                window._convertToNepaliNum(body);
            }
        })
        .catch(() => {
            body.innerHTML = '<div class="app-modal-loading"><i class="bi bi-exclamation-triangle" style="font-size:2rem;color:#ef4444;"></i><span style="margin-top:0.5rem;">' + _t.failed_load_details + '</span></div>';
        });
    }

    function renderApplicationDetail(d) {
        const na = `<span style="color:#c4c9d4;font-style:italic;">N/A</span>`;
        const val = (v) => (v && String(v).trim()) ? String(v) : na;

        // Translate status to locale label
        const _statusMap = {
            'pending':              "{{ __('admin.pending') }}",
            'assigned':             "{{ __('admin.assigned') }}",
            'reviewed':             "{{ __('admin.reviewed') }}",
            'edited':               "{{ __('admin.edited') }}",
            'approved':             "{{ __('admin.approved') }}",
            'rejected':             "{{ __('admin.rejected') }}",
            'verified':             "{{ __('admin.verified') }}",
            'submitted':            "{{ __('admin.submitted') }}",
        };
        const statusLabel = _statusMap[d.status] || (d.status || 'pending').replace(/_/g, ' ');

        // Translate gender
        const _genderMap = {
            'Male':   _t.gender_male,
            'Female': _t.gender_female,
            'Other':  _t.gender_other,
        };
        const genderLabel = d.gender ? (_genderMap[d.gender] || d.gender) : '';

        // Translate age string ("27 years 9 months 3 days")
        const translateAge = (s) => s
            ? s.replace(/\byears?\b/g, _t.age_years)
               .replace(/\bmonths?\b/g, _t.age_months)
               .replace(/\bdays?\b/g, _t.age_days)
            : s;

        // ── Category badges — resolved labels from server (vacancy DB + candidate DB) ──
        let catBadges = '';
        const cats = Array.isArray(d.category_labels) ? d.category_labels : [];
        cats.forEach(label => {
            catBadges += `<span class="app-modal-cat-badge">${label}</span>`;
        });

        // ── Documents grid with image previews ──
        const imgExts = ['jpg','jpeg','png','gif','webp','bmp'];
        const isImage = (url) => url && imgExts.some(ext => url.toLowerCase().split('?')[0].endsWith('.' + ext));

        const docMap = [
            ['passport_size_photo',    _t.doc_passport_photo],
            ['citizenship_id_document',_t.doc_citizenship],
            ['noc_id_card',            _t.doc_noc_id_card],
            ['ethnic_certificate',     _t.doc_ethnic_certificate],
            ['disability_certificate', _t.doc_disability_certificate],
            ['signature',              _t.doc_signature],
            ['transcript',             _t.doc_transcript],
            ['character',              _t.doc_character_certificate],
        ];

        let docsHtml = '';
        docMap.forEach(([key, label]) => {
            if (!d[key]) return;
            const url = d[key];
            const preview = isImage(url)
                ? `<img src="${url}" class="app-modal-doc-preview" alt="${label}" loading="lazy">`
                : `<div class="app-modal-doc-preview-icon">${_t.pdf_label}</div>`;
            docsHtml += `
                <div class="app-modal-doc-card">
                    <a href="${url}" target="_blank">${preview}</a>
                    <div class="app-modal-doc-label">
                        <a href="${url}" target="_blank">${label}</a>
                    </div>
                </div>`;
        });

        // ── Work Experience ──
        let expHtml = '';
        const experiences = Array.isArray(d.experiences) ? d.experiences : [];
        const hasExp = d.has_work_experience === 'Yes' || experiences.length > 0;
        if (hasExp) {
            let expItems = '';
            experiences.forEach((exp, i) => {
                if (!exp.organization) return;
                const period = [exp.start_date_bs, exp.end_date_bs || _t.present_str].filter(Boolean).join(' – ');
                expItems += `<div class="app-modal-field">
                    <span class="app-modal-field-label">${_t.experience_n} ${i + 1}</span>
                    <span class="app-modal-field-value">${exp.position ? exp.position + ', ' : ''}${exp.organization}<br>
                    <small style="color:#9ca3af;">${period}${exp.years ? ' (' + exp.years + ' ' + _t.age_yrs + ')' : ''}</small>
                    ${exp.document ? `<br><a href="${exp.document}" target="_blank" class="small">${_t.view_document}</a>` : ''}
                    </span>
                </div>`;
            });
            if (expItems) {
                expHtml = `<div class="app-modal-section">
                    <div class="app-modal-section-title">${_t.work_experience}</div>
                    <div class="app-modal-grid app-modal-grid-2">${expItems}</div>
                </div>`;
            }
        }

        // ── Address helpers ──
        const permAddr = [d.permanent_tole, d.permanent_municipality, d.permanent_ward ? _t.ward_prefix+d.permanent_ward : '', d.permanent_district, d.permanent_province].filter(Boolean).join(', ');
        const mailAddr = [d.mailing_tole, d.mailing_municipality, d.mailing_ward ? _t.ward_prefix+d.mailing_ward : '', d.mailing_district, d.mailing_province].filter(Boolean).join(', ');

        return `
        <div class="app-modal-profile">
            <div class="app-modal-avatar-wrap">
                ${d.passport_size_photo
                    ? `<img src="${d.passport_size_photo}" class="app-modal-avatar" alt="Photo">`
                    : `<div class="app-modal-avatar-placeholder">${(d.name_english || '?')[0].toUpperCase()}</div>`
                }
            </div>
            <div class="app-modal-name">${val(d.name_english)}</div>
            ${d.name_nepali ? `<div class="app-modal-name-np">${d.name_nepali}</div>` : ''}
            <div class="app-modal-meta">
                <span>${_t.id_colon} ${d.id}</span>
                <span>${val(d.email)}</span>
                <span>${val(d.phone)}</span>
                ${genderLabel ? `<span>${genderLabel}</span>` : ''}
                ${d.age    ? `<span>${_t.age_colon} ${translateAge(d.age)}</span>` : ''}
            </div>
            <div class="app-modal-badges">
                <span class="app-modal-status ${d.status}">${statusLabel}</span>
                ${catBadges}
            </div>
        </div>

        <div class="app-modal-sections">

            <div class="app-modal-section">
                <div class="app-modal-section-title">${_t.vacancy_information}</div>
                <div class="app-modal-grid">
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.position}</span><span class="app-modal-field-value">${val(d.applying_position)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.advertisement_no}</span><span class="app-modal-field-value">${val(d.advertisement_no)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.department}</span><span class="app-modal-field-value">${val(d.vacancy_department)}</span></div>
                </div>
            </div>

            <div class="app-modal-section">
                <div class="app-modal-section-title">${_t.personal_information}</div>
                <div class="app-modal-grid">
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.dob_bs}</span><span class="app-modal-field-value">${val(d.birth_date_bs)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.dob_ad}</span><span class="app-modal-field-value">${val(d.birth_date_ad)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.marital_status}</span><span class="app-modal-field-value">${val(d.marital_status)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.citizenship_no}</span><span class="app-modal-field-value">${val(d.citizenship_number)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.issue_district}</span><span class="app-modal-field-value">${val(d.citizenship_issue_district)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.nationality}</span><span class="app-modal-field-value">${val(d.nationality)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.religion}</span><span class="app-modal-field-value">${val(d.religion)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.community}</span><span class="app-modal-field-value">${val(d.community)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.noc_employee}</span><span class="app-modal-field-value">${d.noc_employee === 'yes' || d.noc_employee === 'Yes' ? _t.yes_label : d.noc_employee === 'no' || d.noc_employee === 'No' ? _t.no_label : val(d.noc_employee)}</span></div>
                </div>
            </div>

            <div class="app-modal-section">
                <div class="app-modal-section-title">${_t.family_details}</div>
                <div class="app-modal-grid">
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.father}</span><span class="app-modal-field-value">${val(d.father_name_english)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.mother}</span><span class="app-modal-field-value">${val(d.mother_name_english)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.grandfather}</span><span class="app-modal-field-value">${val(d.grandfather_name_english)}</span></div>
                    ${d.spouse_name_english ? `<div class="app-modal-field"><span class="app-modal-field-label">${_t.spouse}</span><span class="app-modal-field-value">${d.spouse_name_english}</span></div>` : ''}
                </div>
            </div>

            <div class="app-modal-section">
                <div class="app-modal-section-title">${_t.address_information}</div>
                <div class="app-modal-grid app-modal-grid-2">
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.permanent_address}</span><span class="app-modal-field-value">${permAddr || na}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.mailing_address}</span><span class="app-modal-field-value">${mailAddr || na}</span></div>
                </div>
            </div>

            <div class="app-modal-section">
                <div class="app-modal-section-title">${_t.educational_background}</div>
                <div class="app-modal-grid">
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.education_level}</span><span class="app-modal-field-value">${val(d.education_level)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.field_of_study}</span><span class="app-modal-field-value">${val(d.field_of_study)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.institution}</span><span class="app-modal-field-value">${val(d.institution_name)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.university}</span><span class="app-modal-field-value">${val(d.university)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.graduation_year}</span><span class="app-modal-field-value">${val(d.graduation_year)}</span></div>
                </div>
            </div>

            ${expHtml}

            <div class="app-modal-section">
                <div class="app-modal-section-title">${_t.assignment_timeline}</div>
                <div class="app-modal-grid">
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.reviewer}</span><span class="app-modal-field-value">${d.reviewer_name ? `${d.reviewer_name}<br><small style="color:#9ca3af;">${d.reviewer_email||''}</small>` : na}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.approver}</span><span class="app-modal-field-value">${d.approver_name ? `${d.approver_name}<br><small style="color:#9ca3af;">${d.approver_email||''}</small>` : na}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.applied_on}</span><span class="app-modal-field-value">${val(d.created_at)}</span></div>
                    <div class="app-modal-field"><span class="app-modal-field-label">${_t.last_updated}</span><span class="app-modal-field-value">${val(d.updated_at)}</span></div>
                </div>
            </div>

            ${docsHtml ? `
            <div class="app-modal-section">
                <div class="app-modal-section-title">${_t.uploaded_documents}</div>
                <div class="app-modal-docs-grid">${docsHtml}</div>
            </div>` : ''}

            <div class="app-modal-section">
                <div class="app-modal-section-title">${_t.application_status_history}</div>
                ${(Array.isArray(d.status_histories) && d.status_histories.length > 0) ? (() => {
                    const badgeMap = {
                        'Approved': 'bg-success',
                        'Rejected': 'bg-danger',
                        'Verified': 'bg-primary',
                        'Allow Edit': 'bg-warning text-dark',
                    };
                    const stageMap = {
                        'Assigned to Reviewer': '{{ __("admin.stage_assigned_reviewer") }}',
                        'Assigned to Approver': '{{ __("admin.stage_assigned_approver") }}',
                        'Allow Edit':           '{{ __("admin.stage_allow_edit") }}',
                        'Approved':             '{{ __("admin.approved") }}',
                        'Rejected':             '{{ __("admin.rejected") }}',
                        'Pending':              '{{ __("admin.pending") }}',
                        'Verified':             '{{ __("admin.verified") }}',
                        'Reviewed':             '{{ __("admin.reviewed") }}',
                        'Edited':               '{{ __("admin.edited") }}',
                        'Submitted':            '{{ __("admin.submitted") }}',
                        'Assigned':             '{{ __("admin.assigned") }}',
                    };
                    const roleMap = {
                        'admin':     '{{ __("admin.role_admin") }}',
                        'reviewer':  '{{ __("admin.role_reviewer") }}',
                        'approver':  '{{ __("admin.role_approver") }}',
                        'candidate': '{{ __("admin.role_candidate") }}',
                    };
                    let rows = '';
                    d.status_histories.forEach((h, i) => {
                        const bc = badgeMap[h.stage_name] || 'bg-secondary';
                        const stageName = stageMap[h.stage_name] || h.stage_name;
                        const role = h.done_by_type ? (roleMap[h.done_by_type] || (h.done_by_type.charAt(0).toUpperCase() + h.done_by_type.slice(1))) : '';
                        rows += `<tr>
                            <td>${i + 1}</td>
                            <td><span class="badge ${bc}">${stageName}</span></td>
                            <td>${h.done_by}<br><small class="text-muted">${role}</small></td>
                            <td>${h.created_at}</td>
                            <td>${h.remarks || '—'}</td>
                        </tr>`;
                    });
                    return `<div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0" style="font-size:0.85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px">${_t.sn}</th>
                                    <th>${_t.stage_name}</th>
                                    <th>${_t.done_by}</th>
                                    <th>${_t.date_time}</th>
                                    <th>${_t.remarks}</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>`;
                })() : `<p style="color:#9ca3af;font-style:italic;margin:0;">${_t.no_history}</p>`}
            </div>

        </div>`;
    }

    window.viewApplication = viewApplication;
</script>
@endsection
