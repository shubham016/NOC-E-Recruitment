@extends('layouts.dashboard')

@section('title', 'SMS Management')

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
<style>
    :root {
        --gold-primary: #c9a84c;
        --gold-dark: #a07828;
        --gold-light: #d4af37;
        --spring-smooth: cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Page Header — same as jobs index */
    .page-header {
        background: linear-gradient(135deg, #2196F3 0%, #1976d2 100%);
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 1.5rem;
    }

    .stat-box {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 1.5rem 1rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: all 0.4s var(--spring-smooth);
        min-height: 120px;
        transform: translateY(30px);
        opacity: 0;
        animation: cardEntrance 0.7s var(--spring-smooth) forwards;
    }

    .stat-box:nth-child(1) { animation-delay: 0.05s; }
    .stat-box:nth-child(2) { animation-delay: 0.15s; }
    .stat-box:nth-child(3) { animation-delay: 0.25s; }
    .stat-box:nth-child(4) { animation-delay: 0.35s; }

    @keyframes cardEntrance {
        from { opacity: 0; transform: translateY(30px) scale(0.95); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Gold top border reveal on hover */
    .stat-box::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--gold-light), var(--gold-primary), var(--gold-dark), var(--gold-primary), var(--gold-light));
        border-radius: 14px 14px 0 0;
        transform: scaleX(0);
        transition: transform 0.4s var(--spring-smooth);
    }

    .stat-box:hover::after { transform: scaleX(1); }

    .stat-box:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 32px rgba(201,168,76,0.18), 0 4px 12px rgba(0,0,0,0.08);
        border-color: rgba(201,168,76,0.35);
    }

    .stat-value {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 6px;
        color: #c9a84c;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.3;
    }

    /* Modern Table — jobs index style */
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
        border: 0.5px solid #e5e7eb !important;
        white-space: nowrap;
        background: #f9fafb;
        text-align: center;
    }

    .modern-table tbody td {
        color: #000;
        border: 0.5px solid #e5e7eb;
        vertical-align: middle;
        padding: 1rem 1.25rem;
    }

    .modern-table tbody tr {
        background: #ffffff;
    }

    .modern-table tbody tr.row-hovered td {
        background: #eff1f3;
    }

    .sms-message-cell {
        max-width: 280px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sms-status-sent {
        display: inline-block;
        padding: 0.3rem 0.9rem;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        background: #d1fae5;
        color: #065f46;
    }

    .sms-status-failed {
        display: inline-block;
        padding: 0.3rem 0.9rem;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        background: #fee2e2;
        color: #991b1b;
    }

    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 576px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

    {{-- Page Header --}}
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">SMS Management</h4>
                <p class="mb-0 opacity-90">Send and track SMS messages to vacancy applicants</p>
            </div>
            <div>
                <a href="{{ route('admin.sms.create') }}" class="btn btn-light">
                    Send SMS
                </a>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-value">{{ \App\Models\SmsLog::count() }}</div>
            <div class="stat-label">Total SMS Sent</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ \App\Models\SmsLog::whereDate('created_at', today())->count() }}</div>
            <div class="stat-label">Sent Today</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ \App\Models\SmsLog::where('response_code', 200)->count() }}</div>
            <div class="stat-label">Delivered</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $credits['credits_available'] ?? 'N/A' }}</div>
            <div class="stat-label">Credits Available</div>
        </div>
    </div>

    {{-- Search and Filter — jobs index style --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.sms.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search"
                               placeholder="Search by phone, message, or candidate..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="vacancy">
                            <option value="">All Vacancies</option>
                            @foreach(\App\Models\JobPosting::orderBy('title')->get(['id', 'title']) as $job)
                                <option value="{{ $job->id }}" {{ request('vacancy') == $job->id ? 'selected' : '' }}>
                                    {{ $job->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                Search
                            </button>
                            @if(request()->hasAny(['search', 'vacancy', 'status']))
                                <a href="{{ route('admin.sms.index') }}" class="btn btn-outline-secondary">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- SMS Log Table — jobs index style --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 ps-4">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">SMS Logs</h6>
                <span class="badge bg-primary ms-2">Total {{ $logs->total() }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 modern-table w-100"
                       style="table-layout: auto; white-space: nowrap; border: none;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center text-uppercase" style="border: none;">S.N.</th>
                            <th class="text-center text-uppercase" style="border: none;">Date</th>
                            <th class="text-center text-uppercase" style="border: none;">Advt. No</th>
                            <th class="text-center text-uppercase" style="border: none;">Position / Level</th>
                            <th class="text-center text-uppercase" style="border: none;">Candidate</th>
                            <th class="text-center text-uppercase" style="border: none;">Phone</th>
                            <th class="text-center text-uppercase" style="border: none;">Message</th>
                            <th class="text-center text-uppercase" style="border: none;">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle" style="border: none;">
                        @forelse($logs as $index => $log)
                            <tr class="sms-row">
                                <td class="text-center">{{ $logs->firstItem() + $index }}</td>
                                <td class="text-center text-nowrap">
                                    <small class="d-block fw-semibold nepali-date-bs" data-ad-date="{{ $log->created_at->format('Y-m-d') }}">{{ $log->created_at->format('Y-m-d') }}</small>
                                    <small class="text-muted">{{ $log->created_at->format('h:i A') }}</small>
                                </td>
                                <td class="text-center">{{ $log->jobPosting?->advertisement_no ?? '-' }}</td>
                                <td class="text-center">
                                    @if($log->jobPosting)
                                        {{ $log->jobPosting->position }}{{ $log->jobPosting->level ? ' / Level ' . $log->jobPosting->level : '' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">{{ $log->candidate?->name ?? '-' }}</td>
                                <td class="text-center">{{ $log->phone }}</td>
                                <td class="sms-message-cell" title="{{ $log->message }}">{{ $log->message }}</td>
                                <td class="text-center">
                                    @if($log->response_code == 200)
                                        <span class="sms-status-sent">Sent</span>
                                    @else
                                        <span class="sms-status-failed">Failed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5" style="border: none;">
                                    <div class="text-muted">
                                        <p class="mb-2">No SMS logs found.</p>
                                        @if(request()->hasAny(['search', 'vacancy', 'status']))
                                            <a href="{{ route('admin.sms.index') }}" class="btn btn-sm btn-outline-dark">Clear All Filters</a>
                                        @else
                                            <a href="{{ route('admin.sms.create') }}" class="btn btn-sm btn-light">Send Your First SMS</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if($logs->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $logs->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @endif

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Stat box mouse tracking
    document.querySelectorAll('.stat-box').forEach(function(card) {
        card.addEventListener('mousemove', function(e) {
            const rect = card.getBoundingClientRect();
            card.style.setProperty('--mouse-x', (e.clientX - rect.left) + 'px');
            card.style.setProperty('--mouse-y', (e.clientY - rect.top) + 'px');
        });
        card.addEventListener('mouseenter', function() {
            card.style.willChange = 'transform, box-shadow';
        });
        card.addEventListener('mouseleave', function() {
            setTimeout(function() { card.style.willChange = 'auto'; }, 500);
        });
    });

    // Nepali date conversion
    function englishToNepali(str) {
        if (!str) return str;
        const map = {'0':'०','1':'१','2':'२','3':'३','4':'४','5':'५','6':'६','7':'७','8':'८','9':'९'};
        return str.replace(/[0-9]/g, function(d) { return map[d]; });
    }

    function convertDates() {
        document.querySelectorAll('.nepali-date-bs').forEach(function(el) {
            const adDate = el.getAttribute('data-ad-date');
            if (!adDate) return;
            try {
                const bsDate = window.adToBS(adDate);
                if (bsDate) el.textContent = englishToNepali(bsDate);
            } catch(e) {}
        });
    }

    function waitForConverter() {
        if (typeof window.adToBS === 'function') {
            convertDates();
        } else {
            setTimeout(waitForConverter, 100);
        }
    }
    waitForConverter();

    // Table row hover — same as jobs index
    document.querySelectorAll('.sms-row').forEach(function(row) {
        row.addEventListener('mouseover', function() {
            this.classList.add('row-hovered');
        });
        row.addEventListener('mouseleave', function() {
            this.classList.remove('row-hovered');
        });
    });
});
</script>
@endsection
