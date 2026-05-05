@extends('layouts.dashboard')

@section('title', 'Reports')
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
    .page-header {
        background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
        border-radius: 12px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(201, 168, 76, 0.3);
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
        border-bottom: 2px solid #c9a84c;
        background: #fffbf0;
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
        background: #c9a84c;
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
        border-color: #c9a84c;
        box-shadow: 0 0 0 3px rgba(201,168,76,0.15);
        outline: none;
    }
    .btn-preview {
        background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
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
    <h4 class="mb-1 fw-bold">Reports</h4>
    <p class="mb-0 opacity-75" style="font-size:0.9rem;">Apply filters, preview the data, then download as PDF</p>
</div>

{{-- Applications --}}
<div class="report-card">
    <div class="report-card-header">
        <h6>Applications Report</h6>
        <span class="total-badge">{{ number_format($summary['total_applications']) }} total</span>
    </div>
    <div class="report-card-body">
        <form method="GET" action="{{ route('admin.reports.preview.applications') }}" target="_blank">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Name, email, adv. no., position...">
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="under_review">Under Review</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>From Date</label>
                    <input type="date" name="from" class="form-control">
                </div>
                <div class="filter-group">
                    <label>To Date</label>
                    <input type="date" name="to" class="form-control">
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-preview">Preview &amp; Download</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Candidates --}}
<div class="report-card">
    <div class="report-card-header">
        <h6>Registered Candidates Report</h6>
        <span class="total-badge">{{ number_format($summary['total_candidates']) }} total</span>
    </div>
    <div class="report-card-body">
        <form method="GET" action="{{ route('admin.reports.preview.candidates') }}" target="_blank">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Name, email, username, mobile...">
                </div>
                <div class="filter-group">
                    <label>Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">All Genders</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>From Date</label>
                    <input type="date" name="from" class="form-control">
                </div>
                <div class="filter-group">
                    <label>To Date</label>
                    <input type="date" name="to" class="form-control">
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-preview">Preview &amp; Download</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Vacancies --}}
<div class="report-card">
    <div class="report-card-header">
        <h6>Vacancies Report</h6>
        <span class="total-badge">{{ number_format($summary['total_vacancies']) }} total</span>
    </div>
    <div class="report-card-body">
        <form method="GET" action="{{ route('admin.reports.preview.vacancies') }}" target="_blank">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Position, adv. no., service/group...">
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="draft">Draft</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>From Date</label>
                    <input type="date" name="from" class="form-control">
                </div>
                <div class="filter-group">
                    <label>To Date</label>
                    <input type="date" name="to" class="form-control">
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-preview">Preview &amp; Download</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Reviewers --}}
<div class="report-card">
    <div class="report-card-header">
        <h6>Reviewers Report</h6>
        <span class="total-badge">{{ number_format($summary['total_reviewers']) }} total</span>
    </div>
    <div class="report-card-body">
        <form method="GET" action="{{ route('admin.reports.preview.reviewers') }}" target="_blank">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Name or email...">
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-preview">Preview &amp; Download</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Approvers --}}
<div class="report-card">
    <div class="report-card-header">
        <h6>Approvers Report</h6>
        <span class="total-badge">{{ number_format($summary['total_approvers']) }} total</span>
    </div>
    <div class="report-card-body">
        <form method="GET" action="{{ route('admin.reports.preview.approvers') }}" target="_blank">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Name or email...">
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-preview">Preview &amp; Download</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
