@extends('layouts.dashboard')

@section('title', 'Send SMS')

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
    .form-card {
        background: white;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 2rem;
    }
    .char-counter {
        font-size: 0.8rem;
        color: #6b7280;
    }
    .applicant-panel {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .applicant-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }
    .applicant-table thead th {
        background: #f9fafb;
        padding: 0.75rem 1rem;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #000;
        border: 0.5px solid #e5e7eb;
        text-align: center;
        white-space: nowrap;
    }
    .applicant-table tbody td {
        padding: 0.7rem 1rem;
        border: 0.5px solid #e5e7eb;
        vertical-align: middle;
        font-size: 0.875rem;
        color: #000;
        text-align: center;
    }
    .applicant-table tbody tr {
        background: #ffffff;
        cursor: pointer;
        transition: background 0.15s;
    }
    .applicant-table tbody tr.row-selected td {
        background: #fffbf0;
    }
    .applicant-table tbody tr:hover td {
        background: #eff1f3;
    }
    .applicant-table tbody tr.row-selected:hover td {
        background: #fff3d6;
    }
    .credits-badge {
        background: linear-gradient(135deg, #c9a84c, #a07828);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
    }
    .status-badge {
        display: inline-block;
        padding: 0.2em 0.55em;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-paid { background: #d4edda; color: #155724; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-approved { background: #cce5ff; color: #004085; }
    .status-rejected { background: #f8d7da; color: #721c24; }
    .status-default { background: #e2e3e5; color: #383d41; }
    .loading-spinner {
        display: none;
        text-align: center;
        padding: 2rem;
        color: #6b7280;
    }
    .no-applicants {
        text-align: center;
        padding: 2rem;
        color: #6b7280;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Send SMS to Applicants</h4>
            <p class="text-muted mb-0">Select a vacancy and send SMS to its applicants</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="credits-badge">Credits: {{ $credits['credits_available'] ?? 'N/A' }}</span>
            <a href="{{ route('admin.sms.index') }}" class="btn btn-outline-dark btn-sm px-3 py-2">Back to SMS Logs</a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.sms.store') }}" id="smsForm">
        @csrf
        <div class="form-card">
            <div class="row g-4">

                {{-- Step 1: Select Vacancy --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Select Vacancy <span class="text-danger">*</span></label>
                    <select name="job_posting_id" id="vacancySelect" class="form-select" required>
                        <option value="">-- Select a vacancy --</option>
                        @foreach($jobs as $job)
                            <option value="{{ $job->id }}" {{ old('job_posting_id') == $job->id ? 'selected' : '' }}>
                                {{ $job->advertisement_no ?? '-' }} - {{ $job->position }}{{ $job->level ? ' / Level ' . $job->level : '' }}{{ $job->category ? ' (' . ucfirst($job->category) . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Only applicants with a phone number on their application will be listed.</div>
                </div>

                {{-- Step 2: Applicant List (loaded via AJAX) --}}
                <div class="col-12" id="applicantSection" style="display: none;">
                    <label class="form-label fw-semibold">Applicants <span class="text-danger">*</span></label>
                    <div class="d-flex gap-2 mb-2">
                        <button type="button" class="btn btn-sm btn-outline-dark" id="selectAll">Select All</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">Deselect All</button>
                        <input type="text" class="form-control form-control-sm" id="applicantSearch" placeholder="Filter applicants..." style="max-width: 300px;">
                        <span class="align-self-center text-muted small ms-auto"><span id="selectedCount">0</span> / <span id="totalCount">0</span> selected</span>
                    </div>

                    <div class="applicant-panel" style="max-height: 400px; overflow-y: auto;">
                        <table class="applicant-table">
                            <thead>
                                <tr>
                                    <th style="width:40px;">
                                        <input type="checkbox" id="selectAllInline" class="form-check-input" checked>
                                    </th>
                                    <th>S.N.</th>
                                    <th>Name</th>
                                    <th>Citizenship No.</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="applicantList">
                                {{-- Populated via JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Loading --}}
                <div class="col-12 loading-spinner" id="loadingSpinner">
                    Loading applicants...
                </div>

                {{-- Step 3: Message --}}
                <div class="col-12" id="messageSection" style="display: none;">
                    <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                    <textarea name="message" id="messageInput" class="form-control" rows="5"
                              required maxlength="500" placeholder="Type your SMS message here...">{{ old('message') }}</textarea>
                    <div class="d-flex justify-content-between mt-1">
                        <span class="char-counter"><span id="charCount">0</span> / 500 characters</span>
                        <span class="char-counter"><span id="smsCount">0</span> SMS part(s)</span>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="col-12" id="submitSection" style="display: none;">
                    <button type="submit" class="btn px-4 py-2" style="background: linear-gradient(135deg, #c9a84c, #a07828); color: white; border: none; border-radius: 6px;">
                        Send SMS to Selected Applicants
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const vacancySelect = document.getElementById('vacancySelect');
    const applicantSection = document.getElementById('applicantSection');
    const applicantList = document.getElementById('applicantList');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const messageSection = document.getElementById('messageSection');
    const submitSection = document.getElementById('submitSection');
    const selectedCountEl = document.getElementById('selectedCount');
    const totalCountEl = document.getElementById('totalCount');
    const applicantSearch = document.getElementById('applicantSearch');

    function getStatusClass(status) {
        if (!status) return 'status-default';
        const s = status.toLowerCase();
        if (s === 'paid' || s === 'approved') return 'status-' + s;
        if (s === 'pending' || s === 'draft') return 'status-pending';
        if (s === 'rejected') return 'status-rejected';
        return 'status-default';
    }

    // Load applicants when vacancy changes
    vacancySelect.addEventListener('change', function() {
        const jobId = this.value;

        applicantSection.style.display = 'none';
        messageSection.style.display = 'none';
        submitSection.style.display = 'none';
        applicantList.innerHTML = '';

        if (!jobId) return;

        loadingSpinner.style.display = 'block';

        fetch("{{ route('admin.sms.applicants') }}?job_posting_id=" + jobId, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            loadingSpinner.style.display = 'none';

            if (data.length === 0) {
                applicantList.innerHTML = '<div class="no-applicants">No applicants with phone numbers found for this vacancy.</div>';
                applicantSection.style.display = 'block';
                return;
            }

            let html = '';
            data.forEach(function(app, i) {
                html += '<tr class="applicant-row row-selected" data-name="' + (app.name_english || '').toLowerCase() + '">'
                    + '<td><input type="checkbox" name="application_ids[]" value="' + app.id + '" class="form-check-input applicant-cb" checked></td>'
                    + '<td>' + (i + 1) + '</td>'
                    + '<td>' + (app.name_english || 'N/A') + '</td>'
                    + '<td>' + (app.citizenship_number || '-') + '</td>'
                    + '<td>' + (app.phone || '-') + '</td>'
                    + '<td><span class="status-badge ' + getStatusClass(app.status) + '">' + (app.status || 'N/A') + '</span></td>'
                    + '</tr>';
            });

            applicantList.innerHTML = html;
            totalCountEl.textContent = data.length;
            updateSelectedCount();

            applicantSection.style.display = 'block';
            messageSection.style.display = 'block';
            submitSection.style.display = 'block';

            // Row click toggles checkbox
            document.querySelectorAll('.applicant-row').forEach(function(row) {
                row.addEventListener('click', function(e) {
                    if (e.target.type === 'checkbox') return;
                    var cb = row.querySelector('.applicant-cb');
                    cb.checked = !cb.checked;
                    row.classList.toggle('row-selected', cb.checked);
                    updateSelectedCount();
                });
            });

            // Bind checkbox change
            document.querySelectorAll('.applicant-cb').forEach(function(cb) {
                cb.addEventListener('change', function() {
                    this.closest('.applicant-row').classList.toggle('row-selected', this.checked);
                    updateSelectedCount();
                });
            });

            // Inline select-all header checkbox
            var selectAllInline = document.getElementById('selectAllInline');
            if (selectAllInline) {
                selectAllInline.addEventListener('change', function() {
                    document.querySelectorAll('.applicant-cb').forEach(function(cb) {
                        cb.checked = selectAllInline.checked;
                        cb.closest('.applicant-row').classList.toggle('row-selected', selectAllInline.checked);
                    });
                    updateSelectedCount();
                });
            }
        })
        .catch(function() {
            loadingSpinner.style.display = 'none';
            applicantList.innerHTML = '<div class="no-applicants text-danger">Failed to load applicants. Please try again.</div>';
            applicantSection.style.display = 'block';
        });
    });

    function updateSelectedCount() {
        const count = document.querySelectorAll('.applicant-cb:checked').length;
        selectedCountEl.textContent = count;
    }

    // Select All / Deselect All buttons
    document.getElementById('selectAll').addEventListener('click', function() {
        document.querySelectorAll('.applicant-row:not([style*="display: none"]) .applicant-cb').forEach(function(cb) {
            cb.checked = true;
            cb.closest('.applicant-row').classList.add('row-selected');
        });
        var si = document.getElementById('selectAllInline');
        if (si) si.checked = true;
        updateSelectedCount();
    });

    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('.applicant-cb').forEach(function(cb) {
            cb.checked = false;
            cb.closest('.applicant-row').classList.remove('row-selected');
        });
        var si = document.getElementById('selectAllInline');
        if (si) si.checked = false;
        updateSelectedCount();
    });

    // Filter applicants
    applicantSearch.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.applicant-row').forEach(function(row) {
            row.style.display = row.getAttribute('data-name').includes(query) ? '' : 'none';
        });
    });

    // Character counter
    const messageInput = document.getElementById('messageInput');
    const charCount = document.getElementById('charCount');
    const smsCount = document.getElementById('smsCount');

    messageInput.addEventListener('input', function() {
        const len = this.value.length;
        charCount.textContent = len;
        if (len === 0) {
            smsCount.textContent = '0';
        } else if (len <= 160) {
            smsCount.textContent = '1';
        } else {
            smsCount.textContent = Math.ceil(len / 153);
        }
    });

    // Confirm before submit
    document.getElementById('smsForm').addEventListener('submit', function(e) {
        const count = document.querySelectorAll('.applicant-cb:checked').length;
        if (count === 0) {
            e.preventDefault();
            alert('Please select at least one applicant.');
            return;
        }
        if (!confirm('Send SMS to ' + count + ' applicant(s)?')) {
            e.preventDefault();
        }
    });

    // Trigger load if old value exists
    if (vacancySelect.value) {
        vacancySelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
