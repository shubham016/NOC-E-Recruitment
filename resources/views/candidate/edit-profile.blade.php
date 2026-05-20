@extends('layouts.app')

@section('title', 'Edit Profile')

@push('styles')
<style>
    /* Override the layout's card-body height constraint for date picker inputs */
    #date_of_birth_bs,
    #citizenship_issue_date_bs {
        height: auto !important;
        min-height: calc(1.5em + 0.75rem + 2px);
    }
</style>
@endpush

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>Vacancy</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-check"></i>
        <span>View Result</span>
    </a>
    <!-- <a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item active">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a> -->
    <a href="{{ route('candidate.admit-card') }}" class="sidebar-menu-item">
        <i class="bi bi-box-arrow-down"></i>
        <span>Download Admit Card</span>
    </a>
    <a href="{{ route('candidate.change-password') }}" class="sidebar-menu-item">
        <i class="bi bi-lock"></i>
        <span>Change Password</span>
    </a>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-light text-dark d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Profile</h4>
        <a href="{{ route('candidate.my-profile') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Profile
        </a>
    </div>

    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('candidate.my-profile.update') }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            {{-- Personal Info --}}
            <h6 class="text-uppercase text-muted fw-semibold mb-3 border-bottom pb-2"
                style="font-size:.75rem;letter-spacing:.08em;">Personal Information</h6>

            <div class="row g-3 mb-4">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $candidate->name) }}"
                           placeholder="Full name"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                    <input type="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $candidate->email) }}"
                           placeholder="Email address"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                    <input type="text"
                           name="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone', $candidate->phone) }}"
                           placeholder="e.g. 98XXXXXXXX"
                           required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- FIX 1: name="gender", values capitalised to match DB (Male/Female/Other)
                     and controller validation rule 'in:Male,Female,Other' --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                    <select name="gender"
                            class="form-select @error('gender') is-invalid @enderror"
                            required>
                        <option value="">-- Select Gender --</option>
                        <option value="Male"   {{ old('gender', $candidate->gender) === 'Male'   ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $candidate->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other"  {{ old('gender', $candidate->gender) === 'Other'  ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Date of Birth (BS) <span class="text-danger">*</span></label>
                    <input type="text"
                           id="date_of_birth_bs"
                           name="date_of_birth_bs"
                           class="form-control @error('date_of_birth_bs') is-invalid @enderror"
                           value="{{ old('date_of_birth_bs', $candidate->date_of_birth_bs) }}"
                           placeholder="YYYY-MM-DD"
                           autocomplete="off">
                    <input type="hidden" id="birth_date_ad" name="birth_date_ad"
                           value="{{ old('birth_date_ad', $candidate->birth_date_ad) }}">
                    @error('date_of_birth_bs')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- FIX 2: name was incorrectly set to "gender" — changed to "noc_employee"
                     Values stored as Yes/No string in candidate_registration --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">NOC Employee <span class="text-danger">*</span></label>
                    <select name="noc_employee"
                            id="nocEmployeeSelect"
                            class="form-select @error('noc_employee') is-invalid @enderror">
                        <option value="">-- Select --</option>
                        <option value="Yes" {{ old('noc_employee', $candidate->noc_employee) === 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No"  {{ old('noc_employee', $candidate->noc_employee) === 'No'  ? 'selected' : '' }}>No</option>
                    </select>
                    @error('noc_employee')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- employee_id shown only when NOC Employee = Yes --}}
                <div class="col-md-4" id="employeeIdWrapper"
     style="{{ old('noc_employee', $candidate?->noc_employee) === 'Yes' ? '' : 'display:none;' }}">

    <label class="form-label fw-semibold">
        Employee ID

        @if(old('noc_employee', $candidate?->noc_employee) === 'Yes')
            <span class="text-danger">*</span>
        @endif
    </label>

        <input type="text"
            name="employee_id"
            class="form-control @error('employee_id') is-invalid @enderror"
            value="{{ old('employee_id', $candidate?->employee_id) }}"
            placeholder="Employee ID"
            {{ old('noc_employee', $candidate?->noc_employee) === 'Yes' ? 'required' : '' }}>

        @error('employee_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

            </div>

            {{-- Citizenship & NID --}}
            <h6 class="text-uppercase text-muted fw-semibold mb-3 border-bottom pb-2"
                style="font-size:.75rem;letter-spacing:.08em;">Citizenship and National ID Details</h6>

            <div class="row g-3 mb-4">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Citizenship Number <span class="text-danger">*</span></label>
                    <input type="text"
                           name="citizenship_number"
                           class="form-control @error('citizenship_number') is-invalid @enderror"
                           value="{{ old('citizenship_number', $candidate->citizenship_number) }}"
                           placeholder="Citizenship number">
                    @error('citizenship_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">National ID Number <span class="text-danger">*</span></label>
                    <input type="text"
                           name="nid"
                           class="form-control @error('nid') is-invalid @enderror"
                           value="{{ old('nid', $candidate->nid) }}"
                           placeholder="NID number">
                    @error('nid')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- citizenship_issue_distric — one 't', matches DB column exactly --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Issue District <span class="text-danger">*</span></label>
                    <input type="text"
                           name="citizenship_issue_distric"
                           class="form-control @error('citizenship_issue_distric') is-invalid @enderror"
                           value="{{ old('citizenship_issue_distric', $candidate->citizenship_issue_distric) }}"
                           placeholder="e.g. Kathmandu">
                    @error('citizenship_issue_distric')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Issue Date (BS) <span class="text-danger">*</span></label>
                    <input type="text"
                           id="citizenship_issue_date_bs"
                           name="citizenship_issue_date_bs"
                           class="form-control @error('citizenship_issue_date_bs') is-invalid @enderror"
                           value="{{ old('citizenship_issue_date_bs', $candidate->citizenship_issue_date_bs) }}"
                           placeholder="YYYY-MM-DD"
                           autocomplete="off">
                    @error('citizenship_issue_date_bs')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            {{-- Actions --}}
            <div class="d-flex gap-2 justify-content-end border-top pt-3">
                <a href="{{ route('candidate.my-profile') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-check-circle me-1"></i> Save Changes
                </button>
            </div>

        </form>
    </div>
</div>

@push('scripts')
{{-- BS ↔ AD Converter --}}
<script>
(function () {
    'use strict';
    const bsMonthData = {
        1975:[31,31,32,32,31,30,30,29,30,29,30,30],1976:[31,32,31,32,31,30,30,30,29,29,30,31],
        1977:[30,32,31,32,31,30,30,30,29,30,29,31],1978:[31,31,32,31,31,31,30,29,30,29,30,30],
        1979:[31,31,32,32,31,30,30,29,30,29,30,30],1980:[31,32,31,32,31,30,30,30,29,29,30,31],
        1981:[31,31,31,32,31,31,29,30,30,29,30,30],1982:[31,31,32,31,31,31,30,29,30,29,30,30],
        1983:[31,31,32,32,31,30,30,29,30,29,30,30],1984:[31,32,31,32,31,30,30,30,29,29,30,31],
        1985:[31,31,31,32,31,31,29,30,30,29,30,30],1986:[31,31,32,31,31,31,30,29,30,29,30,30],
        1987:[31,32,31,32,31,30,30,29,30,29,30,30],1988:[31,32,31,32,31,30,30,30,29,29,30,31],
        1989:[31,31,31,32,31,31,30,29,30,29,30,30],1990:[31,31,32,31,31,31,30,29,30,29,30,30],
        1991:[31,32,31,32,31,30,30,29,30,29,30,30],1992:[31,32,31,32,31,30,30,30,29,30,29,31],
        1993:[31,31,31,32,31,31,30,29,30,29,30,30],1994:[31,31,32,31,31,31,30,29,30,29,30,30],
        1995:[31,32,31,32,31,30,30,30,29,29,30,30],1996:[31,32,31,32,31,30,30,30,29,30,29,31],
        1997:[31,31,32,31,31,31,30,29,30,29,30,30],1998:[31,31,32,31,31,31,30,29,30,29,30,30],
        1999:[31,32,31,32,31,30,30,30,29,29,30,31],2000:[30,32,31,32,31,30,30,30,29,30,29,31],
        2001:[31,31,32,31,31,31,30,29,30,29,30,30],2002:[31,31,32,32,31,30,30,29,30,29,30,30],
        2003:[31,32,31,32,31,30,30,30,29,29,30,31],2004:[30,32,31,32,31,30,30,30,29,30,29,31],
        2005:[31,31,32,31,31,31,30,29,30,29,30,30],2006:[31,31,32,32,31,30,30,29,30,29,30,30],
        2007:[31,32,31,32,31,30,30,30,29,29,30,31],2008:[31,31,31,32,31,31,29,30,30,29,29,31],
        2009:[31,31,32,31,31,31,30,29,30,29,30,30],2010:[31,31,32,32,31,30,30,29,30,29,30,30],
        2011:[31,32,31,32,31,30,30,30,29,29,30,31],2012:[31,31,31,32,31,31,29,30,30,29,30,30],
        2013:[31,31,32,31,31,31,30,29,30,29,30,30],2014:[31,31,32,32,31,30,30,29,30,29,30,30],
        2015:[31,32,31,32,31,30,30,30,29,29,30,31],2016:[31,31,31,32,31,31,29,30,30,29,30,30],
        2017:[31,31,32,31,31,31,30,29,30,29,30,30],2018:[31,32,31,32,31,30,30,29,30,29,30,30],
        2019:[31,32,31,32,31,30,30,30,29,30,29,31],2020:[31,31,31,32,31,31,30,29,30,29,30,30],
        2021:[31,31,32,31,31,31,30,29,30,29,30,30],2022:[31,32,31,32,31,30,30,30,29,29,30,30],
        2023:[31,32,31,32,31,30,30,30,29,30,29,31],2024:[31,31,31,32,31,31,30,29,30,29,30,30],
        2025:[31,31,32,31,31,31,30,29,30,29,30,30],2026:[31,32,31,32,31,30,30,30,29,29,30,31],
        2027:[30,32,31,32,31,30,30,30,29,30,29,31],2028:[31,31,32,31,31,31,30,29,30,29,30,30],
        2029:[31,31,32,31,32,30,30,29,30,29,30,30],2030:[31,32,31,32,31,30,30,30,29,29,30,31],
        2031:[30,32,31,32,31,30,30,30,29,30,29,31],2032:[31,31,32,31,31,31,30,29,30,29,30,30],
        2033:[31,31,32,32,31,30,30,29,30,29,30,30],2034:[31,32,31,32,31,30,30,30,29,29,30,31],
        2035:[30,32,31,32,31,31,29,30,30,29,29,31],2036:[31,31,32,31,31,31,30,29,30,29,30,30],
        2037:[31,31,32,32,31,30,30,29,30,29,30,30],2038:[31,32,31,32,31,30,30,30,29,29,30,31],
        2039:[31,31,31,32,31,31,29,30,30,29,30,30],2040:[31,31,32,31,31,31,30,29,30,29,30,30],
        2041:[31,31,32,32,31,30,30,29,30,29,30,30],2042:[31,32,31,32,31,30,30,30,29,29,30,31],
        2043:[31,31,31,32,31,31,29,30,30,29,30,30],2044:[31,31,32,31,31,31,30,29,30,29,30,30],
        2045:[31,32,31,32,31,30,30,29,30,29,30,30],2046:[31,32,31,32,31,30,30,30,29,29,30,31],
        2047:[31,31,31,32,31,31,30,29,30,29,30,30],2048:[31,31,32,31,31,31,30,29,30,29,30,30],
        2049:[31,32,31,32,31,30,30,30,29,29,30,30],2050:[31,32,31,32,31,30,30,30,29,30,29,31],
        2051:[31,31,31,32,31,31,30,29,30,29,30,30],2052:[31,31,32,31,31,31,30,29,30,29,30,30],
        2053:[31,32,31,32,31,30,30,30,29,29,30,30],2054:[31,32,31,32,31,30,30,30,29,30,29,31],
        2055:[31,31,32,31,31,31,30,29,30,29,30,30],2056:[31,31,32,31,32,30,30,29,30,29,30,30],
        2057:[31,32,31,32,31,30,30,30,29,29,30,31],2058:[30,32,31,32,31,30,30,30,29,30,29,31],
        2059:[31,31,32,31,31,31,30,29,30,29,30,30],2060:[31,31,32,32,31,30,30,29,30,29,30,30],
        2061:[31,32,31,32,31,30,30,30,29,29,30,31],2062:[30,32,31,32,31,31,29,30,29,30,29,31],
        2063:[31,31,32,31,31,31,30,29,30,29,30,30],2064:[31,31,32,32,31,30,30,29,30,29,30,30],
        2065:[31,32,31,32,31,30,30,30,29,29,30,31],2066:[31,31,31,32,31,31,29,30,30,29,29,31],
        2067:[31,31,32,31,31,31,30,29,30,29,30,30],2068:[31,31,32,32,31,30,30,29,30,29,30,30],
        2069:[31,32,31,32,31,30,30,30,29,29,30,31],2070:[31,31,31,32,31,31,29,30,30,29,30,30],
        2071:[31,31,32,31,31,31,30,29,30,29,30,30],2072:[31,32,31,32,31,30,30,29,30,29,30,30],
        2073:[31,32,31,32,31,30,30,30,29,29,30,31],2074:[31,31,31,32,31,31,30,29,30,29,30,30],
        2075:[31,31,32,31,31,31,30,29,30,29,30,30],2076:[31,32,31,32,31,30,30,30,29,29,30,30],
        2077:[31,32,31,32,31,30,30,30,29,30,29,31],2078:[31,31,31,32,31,31,30,29,30,29,30,30],
        2079:[31,31,32,31,31,31,30,29,30,29,30,30],2080:[31,32,31,32,31,30,30,30,29,29,30,30],
        2081:[31,32,31,32,31,30,30,30,29,30,29,30],2082:[31,31,31,32,31,31,30,29,30,29,30,31],
        2083:[31,31,32,31,31,31,30,29,30,29,30,30],2084:[31,32,31,32,31,30,30,30,29,29,30,31],
        2085:[30,32,31,32,31,30,30,30,29,30,29,31],2086:[31,31,32,31,31,31,30,29,30,29,30,30],
        2087:[31,31,32,32,31,30,30,29,30,29,30,30],2088:[31,32,31,32,31,30,30,30,29,29,30,31],
        2089:[30,32,31,32,31,31,29,30,29,30,29,31],2090:[31,31,32,31,31,31,30,29,30,29,30,30],
        2091:[31,31,32,32,31,30,30,29,30,29,30,30],2092:[31,32,31,32,31,30,30,30,29,29,30,31],
        2093:[31,31,31,32,31,31,29,30,30,29,29,31],2094:[31,31,32,31,31,31,30,29,30,29,30,30],
        2095:[31,31,32,32,31,30,30,29,30,29,30,30],2096:[31,32,31,32,31,30,30,30,29,29,30,31],
        2097:[30,32,31,32,31,31,29,30,30,29,29,31],2098:[31,31,32,31,31,31,30,29,30,29,30,30],
        2099:[31,31,32,32,31,30,30,29,30,29,30,30]
    };
    const bsStart = { y: 2000, m: 1, d: 1 };
    const adRef   = new Date(1943, 3, 14); // April 14, 1943 = BS 2000-01-01

    function daysInMonth(y, m) { return (bsMonthData[y] || [])[m - 1] || 30; }
    function daysInYear(y)     { return bsMonthData[y] ? bsMonthData[y].reduce((s,d) => s+d, 0) : 365; }

    function countDaysFromRef(y, m, d) {
        let total = 0;
        for (let i = bsStart.y; i < y; i++) total += daysInYear(i);
        for (let i = 1; i < m; i++) total += daysInMonth(y, i);
        return total + (d - bsStart.d);
    }

    window.bsToAD = function (bsStr) {
        try {
            const [y, m, d] = bsStr.split('-').map(Number);
            if (!y || !m || !d) return '';
            const ad = new Date(adRef);
            ad.setDate(ad.getDate() + countDaysFromRef(y, m, d));
            return ad.getFullYear() + '-'
                 + String(ad.getMonth() + 1).padStart(2, '0') + '-'
                 + String(ad.getDate()).padStart(2, '0');
        } catch { return ''; }
    };
})();
</script>

{{-- Date Picker Init + NOC Employee Toggle --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Nepali Date Pickers ──────────────────────────────────
    var dobInput   = document.getElementById('date_of_birth_bs');
    var issueInput = document.getElementById('citizenship_issue_date_bs');
    var adHidden   = document.getElementById('birth_date_ad');

    if (dobInput && typeof dobInput.nepaliDatePicker === 'function') {
        dobInput.nepaliDatePicker({ ndpYear: true, ndpMonth: true, ndpYearCount: 100 });
    }
    if (issueInput && typeof issueInput.nepaliDatePicker === 'function') {
        issueInput.nepaliDatePicker({ ndpYear: true, ndpMonth: true, ndpYearCount: 100 });
    }

    // Fallback: fire on manual typing / tab-away
    if (dobInput) {
        dobInput.addEventListener('change', function () {
            if (adHidden && dobInput.value && typeof window.bsToAD === 'function') {
                var ad = window.bsToAD(dobInput.value);
                if (ad) adHidden.value = ad;
            }
        });
    }

    // Page-load: convert current BS to AD
    if (adHidden && dobInput && dobInput.value && typeof window.bsToAD === 'function') {
        var ad0 = window.bsToAD(dobInput.value);
        if (ad0) adHidden.value = ad0;
    }

    // Poll every 300ms — detects picker selection reliably (onChange is unreliable)
    var lastDobBsVal = dobInput ? dobInput.value : '';
    setInterval(function () {
        if (!dobInput || !adHidden) return;
        var cur = dobInput.value;
        if (cur && cur !== lastDobBsVal && cur.length >= 10) {
            lastDobBsVal = cur;
            if (typeof window.bsToAD === 'function') {
                var ad = window.bsToAD(cur);
                if (ad) adHidden.value = ad;
            }
        }
    }, 300);

    // ── NOC Employee Toggle ──────────────────────────────────
    var nocSelect       = document.getElementById('nocEmployeeSelect');
    var employeeWrapper = document.getElementById('employeeIdWrapper');
    if (nocSelect && employeeWrapper) {
        nocSelect.addEventListener('change', function () {
            employeeWrapper.style.display = this.value === 'Yes' ? '' : 'none';
        });
    }
});
</script>
@endpush

@endsection