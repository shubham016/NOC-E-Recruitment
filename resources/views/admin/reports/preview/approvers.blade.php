@extends('layouts.dashboard')

@section('title', 'Approvers Report — Preview')
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
    .preview-header {
        background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
        border-radius: 12px;
        padding: 1.5rem 2rem;
        color: white;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        box-shadow: 0 4px 12px rgba(42,82,152,0.3);
    }
    .preview-header h5 { margin: 0; font-weight: 700; }
    .preview-header .meta { font-size: 0.82rem; opacity: 0.85; margin-top: 2px; }
    .btn-back {
        background: rgba(255,255,255,0.2);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: 8px;
        padding: 0.4rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: background 0.15s;
    }
    .btn-back:hover { background: rgba(255,255,255,0.3); color: #fff; }
    .btn-download-pdf {
        background: #fff;
        color: #122a52;
        border: none;
        border-radius: 8px;
        padding: 0.4rem 1.1rem;
        font-size: 0.85rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: opacity 0.15s;
    }
    .btn-download-pdf:hover { opacity: 0.88; color: #122a52; }
    .preview-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .preview-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .preview-table th {
        background: #1a3a6b;
        color: #fff;
        font-weight: 700;
        padding: 0.65rem 0.75rem;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        white-space: nowrap;
        border: 1px solid #1a3a6b;
    }
    .preview-table td {
        padding: 0.6rem 0.75rem;
        border: 1px solid #f3f4f6;
        color: #374151;
        vertical-align: middle;
    }
    .preview-table tbody tr:hover { background: #f5f8fc; }
    .preview-table tbody tr:nth-child(even) { background: #fafafa; }
    .preview-table tbody tr:nth-child(even):hover { background: #f5f8fc; }
    .text-muted-sm { font-size: 0.78rem; color: #9ca3af; }
    .empty-state { text-align: center; padding: 3rem 1rem; color: #9ca3af; }
</style>
@endsection

@section('content')
@php
    $qs = request()->getQueryString();
    $downloadUrl = route('admin.reports.download.approvers') . ($qs ? '?' . $qs : '');
@endphp

<div class="preview-header">
    <div>
        <h5>{{ __('admin.report_preview_approvers') }}</h5>
        <div class="meta">{{ $approvers->count() }} {{ __('admin.record_s') }} &nbsp;|&nbsp; {{ __('admin.generated_label') }} {{ now()->format('d M Y, h:i A') }}</div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.reports.index') }}" class="btn-back">{{ __('admin.back_to_reports') }}</a>
        <a href="{{ $downloadUrl }}" class="btn-download-pdf">{{ __('admin.download_pdf') }}</a>
    </div>
</div>

<div class="preview-card">
    <div class="table-responsive">
        <table class="preview-table">
            <thead>
                <tr>
                    <th>{{ __('admin.sn') }}</th>
                    <th>{{ __('admin.name') }}</th>
                    <th>{{ __('admin.email') }}</th>
                    <th>{{ __('admin.status') }}</th>
                    <th>{{ __('admin.approved') }}</th>
                    <th>{{ __('admin.rejected') }}</th>
                    <th>{{ __('admin.total_actioned') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($approvers as $i => $a)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $a->name }}</td>
                        <td class="text-muted-sm">{{ $a->email }}</td>
                        <td>{{ __('admin.' . ($a->status ?? 'active')) }}</td>
                        <td>{{ $a->approved_count ?? 0 }}</td>
                        <td>{{ $a->rejected_count ?? 0 }}</td>
                        <td>{{ ($a->approved_count ?? 0) + ($a->rejected_count ?? 0) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">No approvers found for the selected filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
