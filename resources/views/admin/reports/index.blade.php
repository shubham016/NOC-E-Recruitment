@extends('layouts.dashboard')

@section('title', __('admin.reports'))
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

@section('custom-styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
        border-radius: 12px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(42, 82, 152, 0.3);
    }
    .report-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        margin-bottom: 1.25rem;
        overflow: hidden;
    }
    .report-card-header {
        padding: 0.9rem 1.25rem;
        border-bottom: 2px solid #1a3a6b;
        background: #f5f8fc;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .report-card-header h6 {
        margin: 0;
        font-weight: 700;
        font-size: 0.95rem;
        color: #111827;
    }
    .report-card-header .total-badge {
        font-size: 0.78rem;
        background: #1a3a6b;
        color: #fff;
        font-weight: 600;
        padding: 0.25em 0.7em;
        border-radius: 20px;
    }
    .report-card-body {
        padding: 1.1rem 1.25rem;
    }
    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: flex-end;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .filter-group label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .filter-group .form-control,
    .filter-group .form-select {
        font-size: 0.85rem;
        padding: 0.38rem 0.7rem;
        border-radius: 7px;
        border: 1px solid #d1d5db;
        color: #374151;
        min-width: 140px;
    }
    .filter-group .form-control:focus,
    .filter-group .form-select:focus {
        border-color: #1a3a6b;
        box-shadow: 0 0 0 3px rgba(26,58,107,0.15);
        outline: none;
    }
    .btn-preview {
        background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.42rem 1.2rem;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: opacity 0.15s;
        white-space: nowrap;
    }
    .btn-preview:hover { opacity: 0.88; color: #fff; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h4 class="mb-1 fw-bold">{{ __('admin.reports') }}</h4>
    <p class="mb-0 opacity-75" style="font-size:0.9rem;">{{ __('admin.reports_subtitle') }}</p>
</div>

{{-- Applications --}}
<div class="report-card">
    <div class="report-card-header">
        <h6>{{ __('admin.applications_report') }}</h6>
        <span class="total-badge">{{ number_format($summary['total_applications']) }} {{ __('admin.total') }}</span>
    </div>
    <div class="report-card-body">
        <form method="GET" action="{{ route('admin.reports.preview.applications') }}" target="_blank">
            <div class="filter-row">
                <div class="filter-group">
                    <label>{{ __('admin.search') }}</label>
                    <input type="text" name="search" class="form-control" placeholder="{{ __('admin.ph_search_reports') }}">
                </div>
                <div class="filter-group">
                    <label>{{ __('admin.status') }}</label>
                    <select name="status" class="form-select">
                        <option value="">{{ __('admin.all_status') }}</option>
                        <option value="pending">{{ __('admin.pending') }}</option>
                        <option value="under_review">{{ __('admin.under_review') }}</option>
                        <option value="approved">{{ __('admin.approved') }}</option>
                        <option value="rejected">{{ __('admin.rejected') }}</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>{{ __('admin.from_date') }}</label>
                    <input type="date" name="from" class="form-control">
                </div>
                <div class="filter-group">
                    <label>{{ __('admin.to_date') }}</label>
                    <input type="date" name="to" class="form-control">
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-preview">{{ __('admin.preview_download') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Candidates --}}
<div class="report-card">
    <div class="report-card-header">
        <h6>{{ __('admin.candidates_report') }}</h6>
        <span class="total-badge">{{ number_format($summary['total_candidates']) }} {{ __('admin.total') }}</span>
    </div>
    <div class="report-card-body">
        <form method="GET" action="{{ route('admin.reports.preview.candidates') }}" target="_blank">
            <div class="filter-row">
                <div class="filter-group">
                    <label>{{ __('admin.search') }}</label>
                    <input type="text" name="search" class="form-control" placeholder="{{ __('admin.ph_search_candidates') }}">
                </div>
                <div class="filter-group">
                    <label>{{ __('admin.gender') }}</label>
                    <select name="gender" class="form-select">
                        <option value="">{{ __('admin.all_genders') }}</option>
                        <option value="male">{{ __('admin.male') }}</option>
                        <option value="female">{{ __('admin.female') }}</option>
                        <option value="other">{{ __('admin.other') }}</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>{{ __('admin.from_date') }}</label>
                    <input type="date" name="from" class="form-control">
                </div>
                <div class="filter-group">
                    <label>{{ __('admin.to_date') }}</label>
                    <input type="date" name="to" class="form-control">
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-preview">{{ __('admin.preview_download') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Vacancies --}}
<div class="report-card">
    <div class="report-card-header">
        <h6>{{ __('admin.vacancies_report') }}</h6>
        <span class="total-badge">{{ number_format($summary['total_vacancies']) }} {{ __('admin.total') }}</span>
    </div>
    <div class="report-card-body">
        <form method="GET" action="{{ route('admin.reports.preview.vacancies') }}" target="_blank">
            <div class="filter-row">
                <div class="filter-group">
                    <label>{{ __('admin.search') }}</label>
                    <input type="text" name="search" class="form-control" placeholder="{{ __('admin.ph_position_adv') }}">
                </div>
                <div class="filter-group">
                    <label>{{ __('admin.status') }}</label>
                    <select name="status" class="form-select">
                        <option value="">{{ __('admin.all_status') }}</option>
                        <option value="active">{{ __('admin.active') }}</option>
                        <option value="inactive">{{ __('admin.inactive') }}</option>
                        <option value="draft">{{ __('admin.draft') }}</option>
                        <option value="closed">{{ __('admin.closed') }}</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>{{ __('admin.from_date') }}</label>
                    <input type="date" name="from" class="form-control">
                </div>
                <div class="filter-group">
                    <label>{{ __('admin.to_date') }}</label>
                    <input type="date" name="to" class="form-control">
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-preview">{{ __('admin.preview_download') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Reviewers --}}
<div class="report-card">
    <div class="report-card-header">
        <h6>{{ __('admin.reviewers_report') }}</h6>
        <span class="total-badge">{{ number_format($summary['total_reviewers']) }} {{ __('admin.total') }}</span>
    </div>
    <div class="report-card-body">
        <form method="GET" action="{{ route('admin.reports.preview.reviewers') }}" target="_blank">
            <div class="filter-row">
                <div class="filter-group">
                    <label>{{ __('admin.search') }}</label>
                    <input type="text" name="search" class="form-control" placeholder="{{ __('admin.ph_name_email') }}">
                </div>
                <div class="filter-group">
                    <label>{{ __('admin.status') }}</label>
                    <select name="status" class="form-select">
                        <option value="">{{ __('admin.all_status') }}</option>
                        <option value="active">{{ __('admin.active') }}</option>
                        <option value="inactive">{{ __('admin.inactive') }}</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-preview">{{ __('admin.preview_download') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Approvers --}}
<div class="report-card">
    <div class="report-card-header">
        <h6>{{ __('admin.approvers_report') }}</h6>
        <span class="total-badge">{{ number_format($summary['total_approvers']) }} {{ __('admin.total') }}</span>
    </div>
    <div class="report-card-body">
        <form method="GET" action="{{ route('admin.reports.preview.approvers') }}" target="_blank">
            <div class="filter-row">
                <div class="filter-group">
                    <label>{{ __('admin.search') }}</label>
                    <input type="text" name="search" class="form-control" placeholder="{{ __('admin.ph_name_email') }}">
                </div>
                <div class="filter-group">
                    <label>{{ __('admin.status') }}</label>
                    <select name="status" class="form-select">
                        <option value="">{{ __('admin.all_status') }}</option>
                        <option value="active">{{ __('admin.active') }}</option>
                        <option value="inactive">{{ __('admin.inactive') }}</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-preview">{{ __('admin.preview_download') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
