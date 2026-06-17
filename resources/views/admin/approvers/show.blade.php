@extends('layouts.dashboard')

@section('title', __('admin.approver_details'))

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
    .profile-card {
        background: white;
        border-radius: 10px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e5e7eb;
    }

    .profile-header-section {
        display: flex;
        align-items: center;
        gap: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f3f4f6;
        margin-bottom: 1.5rem;
    }

    .approver-photo {
        width: 100px;
        height: 100px;
        border-radius: 10px;
        object-fit: cover;
        border: 3px solid #1d6df7;
    }

    .approver-avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 10px;
        background: linear-gradient(135deg, #1d6df7 0%, #1557c0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 2.5rem;
        border: 3px solid #1d6df7;
    }

    .approver-info h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .approver-meta {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .status-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 6px;
        font-size: 0.813rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }

    .info-icon-box {
        width: 48px;
        height: 48px;
        background: #dbeafe;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1d6df7;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .info-details h6 {
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }

    .info-details p {
        font-size: 0.938rem;
        color: #1f2937;
        font-weight: 600;
        margin: 0;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-card.blue .stat-number  { color: #3b82f6; }
    .stat-card.orange .stat-number { color: #f97316; }
    .stat-card.green .stat-number  { color: #10b981; }
    .stat-card.red .stat-number    { color: #ef4444; }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
    }

    .content-section {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .sidebar-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .section-card {
        background: white;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .section-header {
        background: #f9fafb;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .section-header h3 {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-header h3 i { color: #1d6df7; font-size: 1.25rem; }

    .section-body { padding: 1.5rem; }

    .applications-table {
        width: 100%;
        border-collapse: collapse;
    }

    .applications-table thead th {
        padding: 1rem 1.25rem;
        font-weight: 700;
        color: #000;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid #000;
        white-space: nowrap;
        text-align: center;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    }

    .applications-table thead th:first-child,
    .applications-table tbody td:first-child { width: 60px; }

    .applications-table thead th:last-child,
    .applications-table tbody td:last-child { width: 120px; }

    .applications-table tbody td {
        padding: 1rem 1.25rem;
        color: #000;
        font-size: 0.875rem;
        border: 1px solid #060606;
        vertical-align: middle;
        text-align: left;
    }

    .applications-table tbody td:first-child,
    .applications-table tbody td:nth-child(6),
    .applications-table tbody td:last-child { text-align: center; }

    .applications-table tbody tr:hover { background: #f8fafc; }

    .app-status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }

    .app-status-pending  { background: #fed7aa; color: #9a3412; }
    .app-status-approved { background: #d1fae5; color: #065f46; }
    .app-status-rejected { background: #fecaca; color: #991b1b; }

    .btn-view-sm {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.5rem 1rem;
        background: #1d6df7;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.813rem;
        font-weight: 600;
        transition: all 0.2s;
        border: none;
    }

    .btn-view-sm:hover { background: #1557c0; color: white; transform: translateY(-1px); }

    .info-table { width: 100%; }

    .info-table tr { border-bottom: 1px solid #f3f4f6; }
    .info-table tr:last-child { border-bottom: none; }
    .info-table td { padding: 0.875rem 0; }

    .info-table .table-label {
        font-size: 0.813rem;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        width: 40%;
    }

    .info-table .table-value {
        font-size: 0.938rem;
        color: #1f2937;
        font-weight: 500;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .action-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); }

    .action-btn-primary { background: #1d6df7; color: white; }
    .action-btn-warning { background: #f97316; color: white; }
    .action-btn-success { background: #10b981; color: white; }
    .action-btn-danger  { background: #ef4444; color: white; }

    .empty-state { text-align: center; padding: 3rem 1rem; }
    .empty-state i { font-size: 4rem; color: #d1d5db; margin-bottom: 1rem; }
    .empty-state h5 { font-size: 1.125rem; color: #6b7280; margin-bottom: 0.5rem; }
    .empty-state p  { color: #9ca3af; font-size: 0.938rem; }

    @media (max-width: 1200px) { .info-grid { grid-template-columns: 1fr; } }
    @media (max-width: 992px)  { .stats-row { grid-template-columns: repeat(2, 1fr); } .sidebar-row { grid-template-columns: 1fr; } }
    @media (max-width: 768px)  { .profile-header-section { flex-direction: column; text-align: center; } .stats-row { grid-template-columns: 1fr; } .action-buttons { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Profile Card -->
    <div class="profile-card">
        <div class="profile-header-section">
            <div>
                @if($approver->photo)
                    <img src="{{ asset('storage/' . $approver->photo) }}" alt="{{ $approver->name }}" class="approver-photo">
                @else
                    <div class="approver-avatar-placeholder">
                        {{ strtoupper(substr($approver->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <div class="approver-info flex-grow-1">
                <h2>{{ $approver->name }}</h2>
                <div class="approver-meta">
                    <span class="status-badge {{ $approver->status === 'active' ? 'status-active' : 'status-inactive' }}">
                        {{ __('admin.' . $approver->status) }}
                    </span>
                </div>
            </div>

            <div>
                <a href="{{ route('admin.approvers.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> {{ __('admin.back') }}
                </a>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-icon-box">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <div class="info-details">
                    <h6>{{ __('admin.email_address') }}</h6>
                    <p>{{ $approver->email }}</p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon-box">
                    <i class="bi bi-telephone-fill"></i>
                </div>
                <div class="info-details">
                    <h6>{{ __('admin.phone_number') }}</h6>
                    <p>{{ $approver->phone_number ?? __('admin.not_provided') }}</p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon-box">
                    <i class="bi bi-building"></i>
                </div>
                <div class="info-details">
                    <h6>{{ __('admin.department') }}</h6>
                    <p>{{ $approver->department ?? __('admin.not_assigned') }}</p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon-box">
                    <i class="bi bi-award-fill"></i>
                </div>
                <div class="info-details">
                    <h6>{{ __('admin.designation') }}</h6>
                    <p>{{ $approver->designation ?? __('admin.not_specified') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-card blue">
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">{{ __('admin.total_applications') }}</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-number">{{ $stats['pending'] }}</div>
            <div class="stat-label">{{ __('admin.pending_review') }}</div>
        </div>
        <div class="stat-card green">
            <div class="stat-number">{{ $stats['approved'] }}</div>
            <div class="stat-label">{{ __('admin.approved') }}</div>
        </div>
        <div class="stat-card red">
            <div class="stat-number">{{ $stats['rejected'] }}</div>
            <div class="stat-label">{{ __('admin.rejected') }}</div>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="section-card">
        <div class="section-header">
            <h3>
                <i class="bi bi-clock-history"></i>
                {{ __('admin.recent_applications') }} ({{ $recentApplications->count() }})
            </h3>
        </div>
        <div class="section-body p-0">
            @if($recentApplications->count() > 0)
                <div class="table-responsive">
                    <table class="applications-table">
                        <thead>
                            <tr>
                                <th>{{ __('admin.sn') }}</th>
                                <th>{{ __('admin.vacancy_title') }}</th>
                                <th>{{ __('admin.candidate_name') }}</th>
                                <th>{{ __('admin.email') }}</th>
                                <th>{{ __('admin.applied_on') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentApplications as $index => $application)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $application->jobPosting->title ?? 'N/A' }}</td>
                                    <td>{{ $application->name_english }}</td>
                                    <td>{{ $application->email }}</td>
                                    <td>
                                        <div class="nepali-date-bs" data-ad-date="{{ $application->created_at->format('Y-m-d') }}">
                                            <i class="bi bi-hourglass-split"></i> ...
                                        </div>
                                        <small style="color: #718096;">{{ $application->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($application->status) {
                                                'approved' => 'app-status-approved',
                                                'rejected' => 'app-status-rejected',
                                                default    => 'app-status-pending'
                                            };
                                        @endphp
                                        <span class="app-status-badge {{ $statusClass }}">
                                            {{ __('admin.' . $application->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.applications.show', $application->id) }}" class="btn-view-sm">
                                            <i class="bi bi-eye-fill"></i>
                                            {{ __('admin.view') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5>{{ __('admin.no_apps_yet') }}</h5>
                    <p>{{ __('admin.approver_no_apps') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Account Information & Actions Row -->
    <div class="sidebar-row">
        <!-- Account Information -->
        <div class="section-card">
            <div class="section-header">
                <h3>
                    <i class="bi bi-person-vcard"></i>
                    {{ __('admin.account_information') }}
                </h3>
            </div>
            <div class="section-body">
                <table class="info-table">
                    <tr>
                        <td class="table-label">{{ __('admin.name') }}</td>
                        <td class="table-value">{{ $approver->name }}</td>
                    </tr>
                    <tr>
                        <td class="table-label">{{ __('admin.email') }}</td>
                        <td class="table-value">{{ $approver->email }}</td>
                    </tr>
                    <tr>
                        <td class="table-label">{{ __('admin.phone') }}</td>
                        <td class="table-value">{{ $approver->phone_number ?? __('admin.na') }}</td>
                    </tr>
                    <tr>
                        <td class="table-label">{{ __('admin.department') }}</td>
                        <td class="table-value">{{ $approver->department ?? __('admin.na') }}</td>
                    </tr>
                    <tr>
                        <td class="table-label">{{ __('admin.designation') }}</td>
                        <td class="table-value">{{ $approver->designation ?? __('admin.na') }}</td>
                    </tr>
                    <tr>
                        <td class="table-label">{{ __('admin.status') }}</td>
                        <td class="table-value">
                            <span class="status-badge {{ $approver->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                {{ __('admin.' . $approver->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-label">{{ __('admin.joined') }}</td>
                        <td class="table-value">{{ $approver->created_at->format('F d, Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="section-card">
            <div class="section-header">
                <h3>
                    <i class="bi bi-gear-fill"></i>
                    {{ __('admin.actions') }}
                </h3>
            </div>
            <div class="section-body">
                <div class="action-buttons">
                    <a href="{{ route('admin.approvers.edit', $approver->id) }}" class="action-btn action-btn-primary">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('admin.edit') }}
                    </a>

                    <button type="button" class="action-btn action-btn-warning" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                        <i class="bi bi-key-fill"></i>
                        {{ __('admin.reset_password') }}
                    </button>

                    <button type="button" class="action-btn {{ $approver->status === 'active' ? 'action-btn-warning' : 'action-btn-success' }}"
                        data-bs-toggle="modal" data-bs-target="#toggleStatusModal">
                        {{ $approver->status === 'active' ? __('admin.deactivate') : __('admin.activate') }}
                    </button>

                    <button type="button" class="action-btn action-btn-danger" onclick="confirmDelete()">
                        <i class="bi bi-trash-fill"></i>
                        {{ __('admin.delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.reset_approver_password') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.approvers.reset-password', $approver->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.new_password') }}</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.confirm_password') }}</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-key-fill me-1"></i> {{ __('admin.reset_password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toggle Status Modal -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.change_approver_status') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.approvers.toggle-status', $approver->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>{{ __('admin.deactivate_confirm', ['action' => $approver->status === 'active' ? __('admin.deactivate') : __('admin.activate'), 'name' => $approver->name]) }}</p>
                    @if($approver->status === 'active')
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ __('admin.deactivate_warning_approver') }}
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-{{ $approver->status === 'active' ? 'warning' : 'success' }}">
                        {{ $approver->status === 'active' ? __('admin.deactivate') : __('admin.activate') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Approver Form -->
<form id="deleteForm" action="{{ route('admin.approvers.destroy', $approver->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
    function confirmDelete() {
        if (confirm('{{ __('admin.delete_approver_confirm') }}')) {
            document.getElementById('deleteForm').submit();
        }
    }

    function englishToNepali(str) {
        if (!str) return str;
        const map = { '0': '०', '1': '१', '2': '२', '3': '३', '4': '४', '5': '५', '6': '६', '7': '७', '8': '८', '9': '९' };
        return str.replace(/[0-9]/g, d => map[d]);
    }

    document.addEventListener('DOMContentLoaded', function () {
        function waitForConverter() {
            if (!window.nepaliLibrariesReady || typeof window.adToBS !== 'function') {
                setTimeout(waitForConverter, 100);
                return;
            }
            document.querySelectorAll('.nepali-date-bs').forEach(el => {
                const adDate = el.getAttribute('data-ad-date');
                if (adDate) {
                    try {
                        const bsDate = window.adToBS(adDate);
                        el.innerHTML = bsDate ? englishToNepali(bsDate) : '<i class="bi bi-exclamation-circle"></i>';
                    } catch (e) {
                        el.innerHTML = '<i class="bi bi-x-circle"></i>';
                    }
                }
            });
        }
        waitForConverter();
    });
</script>
@endsection
