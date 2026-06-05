@extends('layouts.dashboard')

@section('title', 'SMS Detail')

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

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">SMS Detail</h4>
        <a href="{{ route('admin.sms.index') }}" class="btn btn-outline-dark btn-sm px-3 py-2">Back to SMS Logs</a>
    </div>

    <div class="bg-white rounded-3 border p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small">SENT TO</label>
                    <p class="mb-0 fs-6">{{ $sm->phone }}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-semibold text-muted small">CANDIDATE</label>
                    <p class="mb-0 fs-6">{{ $sm->candidate?->name ?? 'N/A (manual number)' }}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-semibold text-muted small">SENT BY</label>
                    <p class="mb-0 fs-6">{{ $sm->admin?->name ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small">DATE &amp; TIME</label>
                    <p class="mb-0 fs-6">{{ $sm->created_at->format('Y-m-d H:i:s') }}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-semibold text-muted small">STATUS</label>
                    <p class="mb-0">
                        @if($sm->response_code == 200)
                            <span class="badge bg-success">Sent</span>
                        @else
                            <span class="badge bg-danger">Failed (Code: {{ $sm->response_code }})</span>
                        @endif
                    </p>
                </div>
                <div class="mb-3">
                    <label class="fw-semibold text-muted small">RESPONSE</label>
                    <p class="mb-0 fs-6">{{ $sm->response_message ?? '-' }}</p>
                </div>
            </div>
            <div class="col-12">
                <label class="fw-semibold text-muted small">MESSAGE</label>
                <div class="p-3 rounded" style="background: #f9fafb; border: 1px solid #e5e7eb;">
                    {{ $sm->message }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
