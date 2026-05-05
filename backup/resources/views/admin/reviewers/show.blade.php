@extends('layouts.dashboard')

@section('title', 'Reviewer Details')

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

    .reviewer-photo {
        width: 100px;
        height: 100px;
        border-radius: 10px;
        object-fit: cover;
        border: 3px solid #c9a84c;
    }

    .reviewer-avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 10px;
        background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 2.5rem;
        border: 3px solid #c9a84c;
    }

    .reviewer-info h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .reviewer-meta {
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

    .reviewer-id-badge {
        background: #fef3c7;
        color: #92400e;
        padding: 0.375rem 0.875rem;
        border-radius: 6px;
        font-size: 0.813rem;
        font-weight: 600;
        font-family: 'Courier New', monospace;
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
        background: #fef3c7;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #c9a84c;
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

    /* Stats Cards */
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

    .stat-card.blue .stat-number {
        color: #3b82f6;
    }

    .stat-card.orange .stat-number {
        color: #f97316;
    }

    .stat-card.green .stat-number {
        color: #10b981;
    }

    .stat-card.red .stat-number {
        color: #ef4444;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
    }

    /* Content Section */
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

    .section-header h3 i {
        color: #c9a84c;
        font-size: 1.25rem;
    }

    .section-body {
        padding: 1.5rem;
    }

    /* Applications Table */
    .applications-table {
        width: 100%;
        border-collapse: collapse;
    }

    .applications-table thead {
        background: #f9fafb;
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
    .applications-table tbody td:first-child {
        width: 60px;
    }

    .applications-table thead th:last-child,
    .applications-table tbody td:last-child {
        width: 120px;
    }

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
    .applications-table tbody td:last-child {
        text-align: center;
    }

    .applications-table tbody tr {
        transition: all 0.2s;
    }

    .applications-table tbody tr:hover {
        background: #f8fafc;
    }

    .app-status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }

    .app-status-pending {
        background: #fed7aa;
        color: #9a3412;
    }

    .app-status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .app-status-rejected {
        background: #fecaca;
        color: #991b1b;
    }

    .btn-view-sm {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.5rem 1rem;
        background: #c9a84c;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.813rem;
        font-weight: 600;
        transition: all 0.2s;
        border: none;
    }

    .btn-view-sm:hover {
        background: #a07828;
        color: white;
        transform: translateY(-1px);
    }

    /* Account Info Table */
    .info-table {
        width: 100%;
    }

    .info-table tr {
        border-bottom: 1px solid #f3f4f6;
    }

    .info-table tr:last-child {
        border-bottom: none;
    }

    .info-table td {
        padding: 0.875rem 0;
    }

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

    /* Action Buttons */
    .action-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        /* margin-top: 1.5rem; */
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

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .action-btn-primary {
        background: #c9a84c;
        color: white;
    }

    .action-btn-warning {
        background: #f97316;
        color: white;
    }

    .action-btn-success {
        background: #10b981;
        color: white;
    }

    .action-btn-danger {
        background: #ef4444;
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        font-size: 1.125rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #9ca3af;
        font-size: 0.938rem;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 992px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .sidebar-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .profile-header-section {
            flex-direction: column;
            text-align: center;
        }

        .stats-row {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            grid-template-columns: 1fr;
        }

        .app-details {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Success/Error Messages -->
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
                @if($reviewer->photo)
                    <img src="{{ asset('storage/' . $reviewer->photo) }}" alt="{{ $reviewer->name }}" class="reviewer-photo">
                @else
                    <div class="reviewer-avatar-placeholder">
                        {{ strtoupper(substr($reviewer->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <div class="reviewer-info flex-grow-1">
                <h2>{{ $reviewer->name }}</h2>
                <div class="reviewer-meta">
                    <span class="status-badge {{ $reviewer->status === 'active' ? 'status-active' : 'status-inactive' }}">
                        <i class="bi bi-{{ $reviewer->status === 'active' ? 'check-circle-fill' : 'x-circle-fill' }} me-1"></i>
                        {{ ucfirst($reviewer->status) }}
                    </span>
                    <!-- <span class="reviewer-id-badge">
                        <i class="bi bi-hash"></i>{{ str_pad($reviewer->id, 4, '0', STR_PAD_LEFT) }}
                    </span> -->
                </div>
            </div>

            <!-- Back Button -->
            <div>
                <a href="{{ route('admin.reviewers.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Reviewers
                </a>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-icon-box">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <div class="info-details">
                    <h6>Email Address</h6>
                    <p>{{ $reviewer->email }}</p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon-box">
                    <i class="bi bi-telephone-fill"></i>
                </div>
                <div class="info-details">
                    <h6>Phone Number</h6>
                    <p>{{ $reviewer->phone ?? 'Not provided' }}</p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon-box">
                    <i class="bi bi-building"></i>
                </div>
                <div class="info-details">
                    <h6>Department</h6>
                    <p>{{ $reviewer->department ?? 'Not assigned' }}</p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon-box">
                    <i class="bi bi-award-fill"></i>
                </div>
                <div class="info-details">
                    <h6>Designation</h6>
                    <p>{{ $reviewer->designation ?? 'Not specified' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-card blue">
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Applications</div>
        </div>

        <div class="stat-card orange">
            <div class="stat-number">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending Review</div>
        </div>

        <div class="stat-card green">
            <div class="stat-number">{{ $stats['reviewed'] }}</div>
            <div class="stat-label">Reviewed</div>
        </div>

        <div class="stat-card red">
            <div class="stat-number">{{ $stats['rejected'] }}</div>
            <div class="stat-label">Rejected</div>
        </div>
    </div>

    <!-- Recent Applications - Full Width -->
    <div class="section-card">
        <div class="section-header">
            <h3>
                <i class="bi bi-clock-history"></i>
                Recent Applications ({{ $recentApplications->count() }})
            </h3>
        </div>
        <div class="section-body p-0">
            @if($recentApplications->count() > 0)
                <div class="table-responsive">
                    <table class="applications-table">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Vacancy Title</th>
                                <th>Candidate Name</th>
                                <th>Email</th>
                                <th>Applied On</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentApplications as $index => $application)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $application->vacancy->title ?? 'N/A' }}</td>
                                    <td>{{ $application->name_english }}</td>
                                    <td>{{ $application->email }}</td>
                                    <td>
                                        <div class="nepali-date-bs" data-ad-date="{{ $application->created_at->format('Y-m-d') }}">
                                            <i class="bi bi-hourglass-split"></i> Converting...
                                        </div>
                                        <small style="color: #718096;">{{ $application->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($application->status) {
                                                'approved' => 'app-status-approved',
                                                'rejected' => 'app-status-rejected',
                                                default => 'app-status-pending'
                                            };
                                        @endphp
                                        <span class="app-status-badge {{ $statusClass }}">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.applications.show', $application->id) }}" class="btn-view-sm">
                                            <i class="bi bi-eye-fill"></i>
                                            View
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
                    <h5>No Applications Yet</h5>
                    <p>This reviewer hasn't been assigned any applications.</p>
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
                    Account Information
                </h3>
            </div>
            <div class="section-body">
                <table class="info-table">
                    <tr>
                        <td class="table-label">Full Name</td>
                        <td class="table-value">{{ $reviewer->name }}</td>
                    </tr>
                    <tr>
                        <td class="table-label">Email</td>
                        <td class="table-value">{{ $reviewer->email }}</td>
                    </tr>
                    <tr>
                        <td class="table-label">Phone</td>
                        <td class="table-value">{{ $reviewer->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="table-label">Department</td>
                        <td class="table-value">{{ $reviewer->department ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="table-label">Designation</td>
                        <td class="table-value">{{ $reviewer->designation ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="table-label">Status</td>
                        <td class="table-value">
                            <span class="status-badge {{ $reviewer->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                {{ ucfirst($reviewer->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-label">Joined</td>
                        <td class="table-value">{{ $reviewer->created_at->format('F d, Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="section-card">
            <div class="section-header">
                <h3>
                    <i class="bi bi-gear-fill"></i>
                    Actions
                </h3>
            </div>
            <div class="section-body">
                <div class="action-buttons">
                    <a href="{{ route('admin.reviewers.edit', $reviewer->id) }}" class="action-btn action-btn-primary">
                        <i class="bi bi-pencil-square"></i>
                        Edit Profile
                    </a>

                    <button type="button" class="action-btn action-btn-warning" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                        <i class="bi bi-key-fill"></i>
                        Reset Password
                    </button>

                    <button type="button" class="action-btn {{ $reviewer->status === 'active' ? 'action-btn-warning' : 'action-btn-success' }}"
                        data-bs-toggle="modal" data-bs-target="#toggleStatusModal">
                        <i class="bi bi-{{ $reviewer->status === 'active' ? 'pause-circle-fill' : 'play-circle-fill' }}"></i>
                        {{ $reviewer->status === 'active' ? 'Deactivate' : 'Activate' }}
                    </button>

                    <button type="button" class="action-btn action-btn-danger" onclick="confirmDelete()">
                        <i class="bi bi-trash-fill"></i>
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End sidebar-row -->
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Reviewer Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.reviewers.reset-password', $reviewer->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                        <small class="text-muted">Minimum 8 characters with letters and numbers</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-key-fill me-1"></i> Reset Password
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
                <h5 class="modal-title">Change Reviewer Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.reviewers.toggle-status', $reviewer->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to <strong>{{ $reviewer->status === 'active' ? 'deactivate' : 'activate' }}</strong> {{ $reviewer->name }}?</p>
                    @if($reviewer->status === 'active')
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            This reviewer will not be able to log in once deactivated.
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-{{ $reviewer->status === 'active' ? 'warning' : 'success' }}">
                        {{ $reviewer->status === 'active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Reviewer Form -->
<form id="deleteForm" action="{{ route('admin.reviewers.destroy', $reviewer->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
    function confirmDelete() {
        if (confirm('⚠️ Are you sure you want to delete this reviewer?\n\nThis action cannot be undone.')) {
            document.getElementById('deleteForm').submit();
        }
    }

    // Convert English numerals to Nepali numerals
    function englishToNepali(str) {
        if (!str) return str;
        const map = { '0': '०', '1': '१', '2': '२', '3': '३', '4': '४', '5': '५', '6': '६', '7': '७', '8': '८', '9': '९' };
        return str.replace(/[0-9]/g, d => map[d]);
    }

    document.addEventListener('DOMContentLoaded', function () {
        console.log('🔧 Initializing Nepali date conversion for applications table...');

        // Wait for converter to be ready
        function waitForConverter() {
            if (!window.nepaliLibrariesReady || typeof window.adToBS !== 'function') {
                setTimeout(waitForConverter, 100);
                return;
            }

            console.log('✅ Converter ready, converting all dates...');
            convertAllDates();
        }

        function convertAllDates() {
            // Find all elements with Nepali date class
            const dateElements = document.querySelectorAll('.nepali-date-bs');

            console.log(`📅 Found ${dateElements.length} dates to convert`);

            dateElements.forEach((element, index) => {
                const adDate = element.getAttribute('data-ad-date');

                if (adDate) {
                    try {
                        // Convert AD to BS (returns English numerals like 2082-11-05)
                        const bsDate = window.adToBS(adDate);

                        if (bsDate) {
                            // Convert to Nepali numerals (२०८२-११-०५)
                            const bsNepali = englishToNepali(bsDate);

                            // Update the element with Nepali numeral date
                            element.innerHTML = `${bsNepali}`;
                            console.log(`✅ Row ${index + 1}: ${adDate} → ${bsDate} → ${bsNepali}`);
                        } else {
                            element.innerHTML = '<i class="bi bi-exclamation-circle"></i> Error';
                            element.style.color = '#f56565';
                        }
                    } catch (error) {
                        console.error(`❌ Error converting date ${adDate}:`, error);
                        element.innerHTML = '<i class="bi bi-x-circle"></i> Error';
                        element.style.color = '#f56565';
                    }
                }
            });

            console.log('✅ All dates converted successfully!');
        }

        // Start the conversion process
        waitForConverter();
    });
</script>
@endsection
