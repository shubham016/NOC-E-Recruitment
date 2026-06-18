@extends('layouts.app')
@section('title', __('candidate.create_application_form'))
@section('content')
@section('sidebar-menu')
<a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
    <i class="bi bi-speedometer2"></i>
    <span>{{ __('candidate.dashboard') }}</span>
</a>
<a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item">
    <i class="bi bi-person"></i>
    <span>{{ __('candidate.my_profile') }}</span>
</a>
<a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item">
    <i class="bi bi-search"></i>
    <span>{{ __('candidate.vacancy') }}</span>
</a>
<a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item active">
    <i class="bi bi-file-earmark-text"></i>
    <span>{{ __('candidate.my_applications') }}</span>
</a>
<a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item">
    <i class="bi bi-file-earmark-check"></i>
    <span>{{ __('candidate.view_result') }}</span>
</a>
<a href="{{ route('candidate.admit-card') }}" class="sidebar-menu-item">
    <i class="bi bi-box-arrow-down"></i>
    <span>{{ __('candidate.download_admit_card') }}</span>
</a>
<a href="{{ route('candidate.change-password') }}" class="sidebar-menu-item">
    <i class="bi bi-lock"></i>
    <span>{{ __('candidate.change_password') }}</span>
</a>
@endsection

@push('styles')
<link rel="stylesheet" href="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/css/nepali.datepicker.v5.0.6.min.css">
<style>
    /* ── Step tabs ── */
    .step-tabs {
        position: relative;
        margin-bottom: 2.5rem;
    }

    .step-tabs .d-flex {
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 10px;
    }

    .tab-item {
        flex: 1;
        text-align: center;
        padding: 15px 8px;
        cursor: pointer;
        transition: all .3s;
        position: relative;
        min-width: 120px;
        user-select: none;
    }

    .tab-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: #e9ecef;
        color: #6c757d;
        border-radius: 50%;
        font-weight: bold;
        font-size: 1.1rem;
        transition: all .3s;
        margin-bottom: 8px;
    }

    .tab-label {
        font-size: .9rem;
        color: #6c757d;
        display: block;
        transition: color .3s;
    }

    .tab-item.active .tab-circle,
    .tab-item.completed .tab-circle {
        background: #000;
        color: #fff;
    }

    .tab-item.active .tab-label,
    .tab-item.completed .tab-label {
        color: #000;
        font-weight: 600;
    }

    .tab-item:hover .tab-circle {
        background: #000;
        color: #fff;
    }

    .tab-item:hover .tab-label {
        color: #000;
    }

    .progress-line {
        position: absolute;
        bottom: -1px;
        left: 0;
        height: 4px;
        background: #ff0000;
        width: 14.28%;
        transition: width .4s;
        z-index: 1;
    }

    @media (max-width: 768px) {
        .tab-label {
            font-size: .8rem;
        }

        .tab-item {
            padding: 12px 4px;
        }

        .tab-circle {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }
    }

    /* ── Step visibility ── */
    .step {
        transition: opacity .4s;
    }

    .step.active {
        opacity: 1;
    }

    .step.d-none {
        opacity: 0;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        pointer-events: none;
        visibility: hidden;
    }

    /* ── Validation ── */
    .is-invalid {
        border-color: #dc3545 !important;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: .875rem;
        margin-top: .25rem;
        display: block;
    }

    /* ── Payment boxes ── */
    .payment-box {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 10px;
        cursor: pointer;
        transition: .3s;
        height: 160px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .payment-box:hover {
        background: #f5f5f5;
    }

    .payment-logo {
        width: 150px;
        height: 60px;
        object-fit: contain;
        margin-bottom: 10px;
    }

    /* ── Nepali date picker icon wrapper ── */
    .ndp-wrapper {
        position: relative;
    }

    .ndp-wrapper input {
        padding-right: 2.25rem;
    }

    .ndp-icon {
        position: absolute;
        right: .65rem;
        top: 50%;
        transform: translateY(-50%);
        color: #bbb;
        font-size: .9rem;
        pointer-events: none;
        z-index: 2;
    }

    .ndp-wrapper:focus-within .ndp-icon {
        color: #1a2a4a;
    }
</style>
@endpush

<div class="container my-2">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-light text-dark text-center py-2">
            <h3 class="mb-0 fw-bold">NOC | New Application Form</h3>
        </div>
        <div class="card-body px-5 pt-3 pb-5">

            {{-- Validation Errors --}}
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>There were some problems with your input:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Clickable Tabs Navigation --}}
            <div class="step-tabs mb-5">
                <div class="d-flex justify-content-evenly border-bottom position-relative">
                    <div class="tab-item active" data-step="1"><span class="tab-circle">1</span><span class="tab-label d-none d-md-inline">Personal</span></div>
                    <div class="tab-item" data-step="2"><span class="tab-circle">2</span><span class="tab-label d-none d-md-inline">General</span></div>
                    <div class="tab-item" data-step="3"><span class="tab-circle">3</span><span class="tab-label d-none d-md-inline">Address</span></div>
                    <div class="tab-item" data-step="4"><span class="tab-circle">4</span><span class="tab-label d-none d-md-inline">Education</span></div>
                    <div class="tab-item" data-step="5"><span class="tab-circle">5</span><span class="tab-label d-none d-md-inline">Experience</span></div>
                    <div class="tab-item" data-step="6"><span class="tab-circle">6</span><span class="tab-label d-none d-md-inline">Documents</span></div>
                    <div class="tab-item" data-step="7"><span class="tab-circle">7</span><span class="tab-label d-none d-md-inline">Preview</span></div>
                    <div class="tab-item" data-step="8"><span class="tab-circle">8</span><span class="tab-label d-none d-md-inline">Payment</span></div>
                </div>
            </div>

            <form action="{{ route('candidate.jobs.applications.store', ['jobId' => $job->id]) }}" method="POST" enctype="multipart/form-data" id="applicationform" novalidate>
                @csrf
                <input type="hidden" name="draft_id" id="draft_id" value="{{ $draftApplication->id ?? '' }}">
                {{-- Age limit data for JS validation --}}
                <input type="hidden" id="job_min_age_male" value="{{ $job->min_age_male ?? '' }}">
                <input type="hidden" id="job_max_age_male" value="{{ $job->max_age_male ?? '' }}">
                <input type="hidden" id="job_min_age_female" value="{{ $job->min_age_female ?? '' }}">
                <input type="hidden" id="job_max_age_female" value="{{ $job->max_age_female ?? '' }}">
                <input type="hidden" id="job_min_age_disabled" value="{{ $job->min_age_disabled ?? '' }}">
                <input type="hidden" id="job_max_age_disabled" value="{{ $job->max_age_disabled ?? '' }}">
                <input type="hidden" id="job_min_age" value="{{ $job->min_age ?? '' }}">
                <input type="hidden" id="job_max_age" value="{{ $job->max_age ?? '' }}">
                <input type="hidden" name="total_fee" id="total_fee" value="0">
                @if($job)
                <input type="hidden" name="job_posting_id" value="{{ $job->id }}">
                @endif

                {{-- ══════════════════════════════════════════════════════
                     STEP 1 — Personal Information
                     ══════════════════════════════════════════════════════ --}}
                <div class="step" id="step1">
                    <h5 class="mb-4 text-dark">Step 1 — Personal Information</h5>

                    {{-- Category Selection --}}
                    @if($job)
                    @php
                    $savedCategories = $draftApplication->applied_category ?? [];
                    if (!is_array($savedCategories)) $savedCategories = [$savedCategories];
                    $oldCategories = old('applied_category', $savedCategories);
                    $groupJobs = $groupJobs ?? collect([$job]);
                    @endphp

                    <div class="alert alert-info mb-4">
                        <h6 class="mb-3">
                            <i class="bi bi-info-circle-fill me-2"></i><strong>Select Application Category</strong>
                            <span class="nepali-text ms-2">(आवेदन श्रेणी छान्नुहोस्)</span>
                        </h6>
                        <p class="small mb-3">
                            Select one or more categories under which you wish to apply.
                            <br><span class="text-muted">तपाईं आवेदन दिन चाहनुभएको एक वा बढी श्रेणीहरू छान्नुहोस्।</span>
                        </p>

                        @if($job->category == 'internal_appraisal')
                        @php
                        $appraisalFee = ($job->deadline && now()->gt($job->deadline) && $job->double_dastur_fee && $job->double_dastur_date && now()->lte($job->double_dastur_date))
                        ? $job->double_dastur_fee
                        : ($job->application_fee ?? 0);
                        $appraisalAdvNo = $job->advertisement_no ?? '';
                        @endphp
                        <div class="d-flex flex-wrap gap-3">
                            <div class="border rounded p-3 category-option" style="min-width:180px;"
                                data-adv-no="{{ $appraisalAdvNo }}" data-fee="{{ $appraisalFee }}">
                                <div class="form-check mb-0">
                                    <input class="form-check-input category-cb" type="checkbox"
                                        name="applied_category[]" id="cat_internal_appraisal"
                                        value="internal_appraisal" checked>
                                    <label class="form-check-label fw-bold" for="cat_internal_appraisal">
                                        Internal Appraisal (आन्तरिक बढुवा)
                                        <br><small class="text-muted fw-normal">Performance-based promotion</small>
                                    </label>
                                </div>
                                <div class="adv-no-display mt-2 text-primary small fw-semibold" style="display:none;">
                                    {{ $appraisalAdvNo }}
                                </div>
                                <div class="mt-1 text-success small fw-semibold">
                                    @if($appraisalFee > 0)Application Fee: Rs. {{ number_format($appraisalFee, 0) }}@endif
                                </div>
                            </div>
                        </div>

                        <div id="feeSummaryBar" class="mt-3 p-3 rounded border bg-white" style="display:none;">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <span class="fw-bold">Selected Categories:</span>
                                    <span id="selectedCategoryNames" class="ms-2 text-secondary small"></span>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold fs-5 text-success">
                                        Total Fee: Rs. <span id="totalFeeDisplay">0</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="d-flex flex-wrap gap-3" id="categoryCheckboxGroup">

                            @foreach($groupJobs as $gjIdx => $gJob)
                            @php
                            $gAdvNo = $gJob->advertisement_no ?? '';
                            $gIsDoubleDastur = $gJob->deadline && now()->gt($gJob->deadline)
                            && $gJob->double_dastur_fee
                            && $gJob->double_dastur_date
                            && now()->lte($gJob->double_dastur_date);
                            $gEffectiveFee = $gIsDoubleDastur ? $gJob->double_dastur_fee : ($gJob->application_fee ?? 0);
                            $gInclusiveTypes = [];
                            if ($gJob->category == 'inclusive') {
                            if ($gJob->inclusive_type) {
                            $gDecoded = json_decode($gJob->inclusive_type, true);
                            $gInclusiveTypes = is_array($gDecoded) ? $gDecoded : [$gJob->inclusive_type];
                            }
                            }
                            $gInternalInclusiveTypes = [];
                            if ($gJob->category == 'internal' && $gJob->has_internal_inclusive && is_array($gJob->internal_inclusive_types)) {
                            $gInternalInclusiveTypes = $gJob->internal_inclusive_types;
                            }
                            @endphp

                            @if($gJob->category == 'open')
                            <div class="border rounded p-3 category-option" style="min-width:180px;" data-adv-no="{{ $gAdvNo }}" data-fee="{{ $gEffectiveFee }}">
                                <div class="form-check mb-0">
                                    <input class="form-check-input category-cb" type="checkbox"
                                        name="applied_category[]" id="cat_open_{{ $gjIdx }}" value="open"
                                        {{ in_array('open', $oldCategories) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="cat_open_{{ $gjIdx }}">
                                        Open (खुल्ला)
                                        <br><small class="text-muted fw-normal">Open for all eligible candidates</small>
                                    </label>
                                </div>
                                <div class="adv-no-display mt-2 text-primary small fw-semibold" style="display:none;">
                                    {{ $gAdvNo }}
                                </div>
                                <div class="mt-1 text-success small fw-semibold">
                                    @if($gIsDoubleDastur)Double Dastur @endif Application Fee: Rs. {{ number_format($gEffectiveFee, 0) }}
                                </div>
                            </div>
                            @endif

                            @foreach($gInclusiveTypes as $idx => $type)
                            <div class="border rounded p-3 category-option" style="min-width:180px;" data-adv-no="{{ $gAdvNo }}" data-fee="{{ $gEffectiveFee }}">
                                <div class="form-check mb-0">
                                    <input class="form-check-input category-cb" type="checkbox"
                                        name="applied_category[]" id="cat_inclusive_{{ $gjIdx }}_{{ $idx }}" value="inclusive"
                                        {{ in_array('inclusive', $oldCategories) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="cat_inclusive_{{ $gjIdx }}_{{ $idx }}">
                                        Inclusive — {{ $type }}
                                        <br><small class="text-muted fw-normal">समावेशी — {{ $type }}</small>
                                    </label>
                                </div>
                                <div class="adv-no-display mt-2 text-primary small fw-semibold" style="display:none;">
                                    {{ $gAdvNo }}
                                </div>
                                <div class="mt-1 text-success small fw-semibold">
                                    @if($gIsDoubleDastur)<span class="badge bg-warning text-dark me-1">Double Dastur</span>@endif
                                    @if($gIsDoubleDastur)Double Dastur @endif Application Fee: Rs. {{ number_format($gEffectiveFee, 0) }}
                                </div>
                            </div>
                            @endforeach

                            @if($gJob->category == 'inclusive' && count($gInclusiveTypes) === 0)
                            <div class="border rounded p-3 category-option" style="min-width:180px;" data-adv-no="{{ $gAdvNo }}" data-fee="{{ $gEffectiveFee }}">
                                <div class="form-check mb-0">
                                    <input class="form-check-input category-cb" type="checkbox"
                                        name="applied_category[]" id="cat_inclusive_{{ $gjIdx }}" value="inclusive"
                                        {{ in_array('inclusive', $oldCategories) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="cat_inclusive_{{ $gjIdx }}">
                                        Inclusive (समावेशी)
                                        <br><small class="text-muted fw-normal">Reserved for inclusive category</small>
                                    </label>
                                </div>
                                <div class="adv-no-display mt-2 text-primary small fw-semibold" style="display:none;">{{ $gAdvNo }}</div>
                            </div>
                            @endif

                            @if($gJob->category == 'internal' && $gJob->has_internal_open)
                            <div class="border rounded p-3 category-option" style="min-width:180px;" data-adv-no="{{ $gAdvNo }}" data-fee="{{ $gEffectiveFee }}">
                                <div class="form-check mb-0">
                                    <input class="form-check-input category-cb" type="checkbox"
                                        name="applied_category[]" id="cat_internal_open_{{ $gjIdx }}" value="internal_open"
                                        {{ in_array('internal_open', $oldCategories) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="cat_internal_open_{{ $gjIdx }}">
                                        Internal Open
                                        <br><small class="text-muted fw-normal">आन्तरिक खुल्ला — All NOC Staff</small>
                                    </label>
                                </div>
                                <div class="adv-no-display mt-2 text-primary small fw-semibold" style="display:none;">
                                    {{ $gAdvNo }}
                                </div>
                                <div class="mt-1 text-success small fw-semibold">
                                    @if($gIsDoubleDastur)<span class="badge bg-warning text-dark me-1">Double Dastur</span>@endif
                                    @if($gIsDoubleDastur)Double Dastur @endif Application Fee: Rs. {{ number_format($gEffectiveFee, 0) }}
                                </div>
                            </div>
                            @endif

                            @foreach($gInternalInclusiveTypes as $idx => $type)
                            <div class="border rounded p-3 category-option" style="min-width:180px;" data-adv-no="{{ $gAdvNo }}" data-fee="{{ $gEffectiveFee }}">
                                <div class="form-check mb-0">
                                    <input class="form-check-input category-cb" type="checkbox"
                                        name="applied_category[]" id="cat_int_incl_{{ $gjIdx }}_{{ $idx }}" value="internal_inclusive"
                                        {{ in_array('internal_inclusive', $oldCategories) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="cat_int_incl_{{ $gjIdx }}_{{ $idx }}">
                                        Internal Inclusive — {{ $type }}
                                        <br><small class="text-muted fw-normal">आन्तरिक समावेशी — {{ $type }} (NOC only)</small>
                                    </label>
                                </div>
                                <div class="adv-no-display mt-2 text-primary small fw-semibold" style="display:none;">
                                    {{ $gAdvNo }}
                                </div>
                                <div class="mt-1 text-success small fw-semibold">
                                    @if($gIsDoubleDastur)<span class="badge bg-warning text-dark me-1">Double Dastur</span>@endif
                                    @if($gIsDoubleDastur)Double Dastur @endif Application Fee: Rs. {{ number_format($gEffectiveFee, 0) }}
                                </div>
                            </div>
                            @endforeach

                            @endforeach

                        </div>

                        <div id="feeSummaryBar" class="mt-3 p-3 rounded border bg-white" style="display:none;">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <span class="fw-bold">Selected Categories:</span>
                                    <span id="selectedCategoryNames" class="ms-2 text-secondary small"></span>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold fs-5 text-success">
                                        Total Fee: Rs. <span id="totalFeeDisplay">0</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div id="categoryError" class="text-danger small mt-2" style="display:none;">
                            Please select at least one category.
                        </div>
                        @error('applied_category')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                        @endif
                    </div>
                    @endif

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            function updateAdvertisementNo() {
                                var checked = document.querySelectorAll('.category-cb:checked');
                                var adNos = [];
                                var seen = {};
                                checked.forEach(function(cb) {
                                    var option = cb.closest('.category-option');
                                    if (option) {
                                        var adv = option.getAttribute('data-adv-no');
                                        if (adv && !seen[adv]) {
                                            seen[adv] = true;
                                            adNos.push(adv);
                                        }
                                    }
                                });
                                var field = document.getElementById('advertisement_no');
                                if (field) field.value = adNos.join(', ');
                            }

                            function updateTotalFee() {
                                var checkedBoxes = Array.from(document.querySelectorAll('.category-cb:checked'));
                                if (checkedBoxes.length === 0) {
                                    var totalFeeInput = document.getElementById('total_fee');
                                    if (totalFeeInput) totalFeeInput.value = '0';
                                    var bar = document.getElementById('feeSummaryBar');
                                    if (bar) bar.style.display = 'none';
                                    var step8Fee = document.getElementById('step8TotalFee');
                                    if (step8Fee) step8Fee.textContent = '0';
                                    return;
                                }

                                var seenAdvNos = {};
                                var total = 0;
                                var hasOpen = checkedBoxes.some(function(cb) {
                                    return cb.value === 'open' || cb.value === 'internal_open';
                                });
                                var hasInclusive = checkedBoxes.some(function(cb) {
                                    return cb.value === 'inclusive' || cb.value === 'internal_inclusive';
                                });
                                var feeBoxes = checkedBoxes;
                                if (hasInclusive && !hasOpen) {
                                    var openBoxes = Array.from(document.querySelectorAll('.category-cb[value="open"], .category-cb[value="internal_open"]'));
                                    var nonInclusiveChecked = checkedBoxes.filter(function(cb) {
                                        return cb.value !== 'inclusive' && cb.value !== 'internal_inclusive';
                                    });
                                    feeBoxes = nonInclusiveChecked.concat(openBoxes);
                                }
                                feeBoxes.forEach(function(cb) {
                                    var option = cb.closest('.category-option');
                                    if (!option) return;
                                    var advNo = option.getAttribute('data-adv-no') || cb.id;
                                    if (!seenAdvNos[advNo]) {
                                        seenAdvNos[advNo] = true;
                                        total += parseFloat(option.getAttribute('data-fee') || 0);
                                    }
                                });

                                var names = [];
                                checkedBoxes.forEach(function(cb) {
                                    var option = cb.closest('.category-option');
                                    if (!option) return;
                                    var label = option.querySelector('.form-check-label');
                                    if (label) {
                                        var labelText = (label.firstChild && label.firstChild.textContent) ?
                                            label.firstChild.textContent.trim() :
                                            label.textContent.split('\n')[0].trim();
                                        if (labelText) names.push(labelText);
                                    }
                                });

                                var totalFeeInput = document.getElementById('total_fee');
                                if (totalFeeInput) totalFeeInput.value = total.toFixed(2);
                                var bar = document.getElementById('feeSummaryBar');
                                var display = document.getElementById('totalFeeDisplay');
                                var nameSpan = document.getElementById('selectedCategoryNames');
                                if (bar) bar.style.display = total > 0 ? '' : 'none';
                                if (display) display.textContent = total.toLocaleString('en-NP', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                });
                                if (nameSpan) nameSpan.textContent = names.join(' + ') || '—';
                                var step8Fee = document.getElementById('step8TotalFee');
                                var step8Names = document.getElementById('step8CategoryNames');
                                var step8Pos = document.getElementById('step8Position');
                                if (step8Fee) step8Fee.textContent = total.toLocaleString('en-NP', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                });
                                if (step8Names) step8Names.textContent = names.join(' + ') || '—';
                                if (step8Pos) step8Pos.textContent = document.getElementById('applying_position') ? document.getElementById('applying_position').value : '—';
                            }

                            document.querySelectorAll('.category-cb').forEach(function(cb) {
                                var advDiv = cb.closest('.category-option') ? cb.closest('.category-option').querySelector('.adv-no-display') : null;
                                if (cb.checked && advDiv) advDiv.style.display = '';
                                cb.addEventListener('change', function() {
                                    if (advDiv) advDiv.style.display = this.checked ? '' : 'none';
                                    updateAdvertisementNo();
                                    updateTotalFee();
                                });
                            });

                            updateAdvertisementNo();
                            updateTotalFee();
                        });
                    </script>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name_english" class="form-label">Full Name (English) <span class="text-danger">*</span> <small>(पुरा नाम अंग्रेजी)</small></label>
                            <input type="text" name="name_english" id="name_english" class="form-control"
                                value="{{ old('name_english', $draftApplication->name_english ?? $candidate->name_english ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="name_nepali" class="form-label">Full Name (Nepali) <span class="text-danger">*</span> <small>(पुरा नाम नेपाली)</small></label>
                            <input type="text" name="name_nepali" id="name_nepali" class="form-control"
                                placeholder="नेपालीमा नाम लेख्नुहोस्"
                                value="{{ old('name_nepali', $draftApplication->name_nepali ?? $candidate->name_nepali ?? '') }}"
                                required autocomplete="off" inputmode="text" style="ime-mode: active;">
                            <small class="text-muted">Only Devanagari (नेपाली) characters allowed</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="birth_date_bs" class="form-label">Birth Date (B.S) <span class="text-danger">*</span> <small>(जन्म मिति B.S)</small></label>
                            <div class="ndp-wrapper">
                                <input type="text" name="birth_date_bs" id="birth_date_bs" class="form-control"
                                    placeholder="YYYY-MM-DD" autocomplete="off"
                                    value="{{ old('birth_date_bs', $draftApplication->birth_date_bs ?? $candidate->birth_date_bs ?? '') }}" required>
                                <span class="ndp-icon"><i class="bi bi-calendar-event"></i></span>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="birth_date_ad" class="form-label">Birth Date (A.D) <span class="text-danger">*</span> <small>(जन्म मिति A.D)</small></label>
                            <input type="text" id="birth_date_ad_display" class="form-control bg-light" placeholder="YYYY-MMM-DD"
                                value="{{ old('birth_date_ad', $draftApplication->birth_date_ad ?? ($candidate->birth_date_ad ? \Carbon\Carbon::parse($candidate->birth_date_ad)->format('Y-M-d') : '')) }}"
                                readonly>
                            <input type="hidden" name="birth_date_ad" id="birth_date_ad"
                                value="{{ old('birth_date_ad', $draftApplication->birth_date_ad ?? ($candidate->birth_date_ad ? \Carbon\Carbon::parse($candidate->birth_date_ad)->format('Y-m-d') : '')) }}">
                            <small class="text-muted">Auto-filled from B.S date above</small>
                        </div>

                        <div class="col-md-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="text" name="email" id="email" class="form-control"
                                value="{{ old('email', $draftApplication->email ?? $candidate->email ?? '') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control"
                                value="{{ old('phone', $draftApplication->phone ?? $candidate->phone ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span> <small>(लिङ्ग)</small></label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="">-- Select / छान्नुहोस् --</option>
                                <option value="Male" {{ old('gender', $draftApplication->gender ?? $candidate->gender ?? '') == 'Male'   ? 'selected' : '' }}>Male / पुरुष</option>
                                <option value="Female" {{ old('gender', $draftApplication->gender ?? $candidate->gender ?? '') == 'Female' ? 'selected' : '' }}>Female / महिला</option>
                                <option value="Other" {{ old('gender', $draftApplication->gender ?? $candidate->gender ?? '') == 'Other'  ? 'selected' : '' }}>Other / अन्य</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="noc_employee" class="form-label">Are you NOC Employee? <span class="text-danger">*</span></label>
                            <select name="noc_employee" id="noc_employee" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="yes" {{ old('noc_employee', $draftApplication->noc_employee ?? $candidate->noc_employee ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ old('noc_employee', $draftApplication->noc_employee ?? $candidate->noc_employee ?? '') == 'no'  ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        @php
                        $nocIdCard = $draftApplication->noc_id_card ?? $candidate->noc_id_card ?? null;
                        @endphp

                        <div class="col-md-4">
                            <label for="noc_id_card" class="form-label">NOC ID Card</label>

                            <input type="file"
                                name="noc_id_card"
                                id="noc_id_card"
                                class="form-control"
                                accept="image/*,application/pdf"
                                {{ $nocIdCard ? 'data-existing-file=1' : '' }}>

                            <small class="text-muted d-block">Max Size: 700KB</small>

                            @if($nocIdCard)
                            <small class="text-success d-block mt-1">
                                Previously uploaded:
                                <a href="{{ asset('storage/'.$nocIdCard) }}" target="_blank">View</a>
                                — upload new to replace
                            </small>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="citizenship_number" class="form-label">Citizenship Number <span class="text-danger">*</span></label>
                            <input type="text" name="citizenship_number" id="citizenship_number" class="form-control"
                                value="{{ old('citizenship_number', $draftApplication->citizenship_number ?? $candidate->citizenship_number ?? '') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label for="citizenship_issue_date_bs" class="form-label">Citizenship Issue Date (B.S) <span class="text-danger">*</span></label>
                            <div class="ndp-wrapper">
                                <input type="text" name="citizenship_issue_date_bs" id="citizenship_issue_date_bs" class="form-control"
                                    placeholder="YYYY-MM-DD" autocomplete="off"
                                    value="{{ old('citizenship_issue_date_bs', $draftApplication->citizenship_issue_date_bs ?? $candidate->citizenship_issue_date_bs ?? '') }}" required>
                                <span class="ndp-icon"><i class="bi bi-calendar-check"></i></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="citizenship_issue_district" class="form-label">Citizenship Issue District <span class="text-danger">*</span></label>
                            <input type="text" name="citizenship_issue_district" id="citizenship_issue_district" class="form-control"
                                value="{{ old('citizenship_issue_district', $draftApplication->citizenship_issue_district ?? $candidate->citizenship_issue_district ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="advertisement_no" class="form-label">Advertisement Number <span class="text-danger">*</span></label>
                            <input type="text" name="advertisement_no" id="advertisement_no" class="form-control"
                                value="{{ old('advertisement_no', $draftApplication->advertisement_no ?? $job->advertisement_no ?? '') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="applying_position" class="form-label">Applying Position <span class="text-danger">*</span></label>
                            <input type="text" name="applying_position" id="applying_position" class="form-control"
                                value="{{ old('applying_position', $draftApplication->applying_position ?? $job->title ?? '') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                            <input type="text" name="department" id="department" class="form-control"
                                value="{{ $job ? ($job->service_group ?: $job->department) : ($draftApplication->department ?? '') }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="physical_disability" class="form-label">Physical Disability <span class="text-danger">*</span> <small>(कुनै पनि असक्षमता?)</small></label>
                            <select name="physical_disability" id="physical_disability" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="yes" {{ old('physical_disability', $draftApplication->physical_disability ?? $candidate->physical_disability ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ old('physical_disability', $draftApplication->physical_disability ?? $candidate->physical_disability ?? '') == 'no'  ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        @php
                        $disabilityCertificate = $draftApplication->disability_certificate
                        ?? $candidate->disability_certificate
                        ?? null;
                        @endphp

                        <div class="col-md-4">
                            <label for="disability_certificate" class="form-label">
                                Disability Certificate (If Any)
                            </label>

                            <input type="file"
                                name="disability_certificate"
                                id="disability_certificate"
                                class="form-control"
                                accept="image/*,application/pdf"
                                {{ $disabilityCertificate ? 'data-existing-file=1' : '' }}>

                            <small class="text-muted d-block">Max Size: 700KB</small>

                            @if($disabilityCertificate)
                            <small class="text-success d-block mt-1">
                                Previously uploaded:
                                <a href="{{ asset('storage/'.$disabilityCertificate) }}" target="_blank">View</a>
                                — upload new to replace
                            </small>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                            <input type="text" name="nationality" id="nationality" class="form-control"
                                value="{{ old('nationality', $draftApplication->nationality ?? $candidate->nationality ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="marital_status" class="form-label">Marital Status <span class="text-danger">*</span></label>
                            <select name="marital_status" id="marital_status" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Single" {{ old('marital_status', $draftApplication->marital_status ?? $candidate->marital_status ?? '') == 'Single'   ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('marital_status', $draftApplication->marital_status ?? $candidate->marital_status ?? '') == 'Married'  ? 'selected' : '' }}>Married</option>
                                <option value="Divorced" {{ old('marital_status', $draftApplication->marital_status ?? $candidate->marital_status ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="Widowed" {{ old('marital_status', $draftApplication->marital_status ?? $candidate->marital_status ?? '') == 'Widowed'  ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="spouse_name_english" class="form-label">Spouse Name (If Married)</label>
                            <input type="text" name="spouse_name_english" id="spouse_name_english" class="form-control"
                                value="{{ old('spouse_name_english', $draftApplication->spouse_name_english ?? $candidate->spouse_name ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="spouse_nationality" class="form-label">Spouse Nationality (If Married)</label>
                            <input type="text" name="spouse_nationality" id="spouse_nationality" class="form-control"
                                value="{{ old('spouse_nationality', $draftApplication->spouse_nationality ?? $candidate->spouse_nationality ?? '') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="father_name_english" class="form-label">Father Name <span class="text-danger">*</span></label>
                            <input type="text" name="father_name_english" id="father_name_english" class="form-control"
                                value="{{ old('father_name_english', $draftApplication->father_name_english ?? $candidate->father_name_english?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="mother_name_english" class="form-label">Mother Name <span class="text-danger">*</span></label>
                            <input type="text" name="mother_name_english" id="mother_name_english" class="form-control"
                                value="{{ old('mother_name_english', $draftApplication->mother_name_english ?? $candidate->mother_name_english ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="grandfather_name_english" class="form-label">Grandfather Name <span class="text-danger">*</span></label>
                            <input type="text" name="grandfather_name_english" id="grandfather_name_english" class="form-control"
                                value="{{ old('grandfather_name_english', $draftApplication->grandfather_name_english ?? $candidate->grandfather_name_english ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="father_name_nepali" class="form-label">
                                Father Name in Nepali (बुबाको नाम नेपालीमा) <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="father_name_nepali" id="father_name_nepali"
                                class="form-control nepali-only"
                                placeholder="नेपालीमा नाम लेख्नुहोस्"
                                value="{{ old('father_name_nepali', $draftApplication->father_name_nepali ?? $candidate->father_name_nepali ?? '') }}"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label for="mother_name_nepali" class="form-label">
                                Mother Name in Nepali (आमाको नाम नेपालीमा) <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="mother_name_nepali" id="mother_name_nepali"
                                class="form-control nepali-only"
                                placeholder="नेपालीमा नाम लेख्नुहोस्"
                                value="{{ old('mother_name_nepali', $draftApplication->mother_name_nepali ?? $candidate->mother_name_nepali ?? '') }}"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label for="grandfather_name_nepali" class="form-label">
                                Grandfather in Nepali (हजुरबुबाको नाम नेपालीमा) <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="grandfather_name_nepali" id="grandfather_name_nepali"
                                class="form-control nepali-only"
                                placeholder="नेपालीमा नाम लेख्नुहोस्"
                                value="{{ old('grandfather_name_nepali', $draftApplication->grandfather_name_nepali ?? $candidate->grandfather_name_nepali ?? '') }}"
                                required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="father_qualification" class="form-label">Father's Qualification (बुबाको योग्यता)</label>
                            <input type="text" name="father_qualification" id="father_qualification" class="form-control"
                                value="{{ old('father_qualification', $draftApplication->father_qualification ?? $candidate->father_qualification ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="mother_qualification" class="form-label">Mother's Qualification (आमाको योग्यता)</label>
                            <input type="text" name="mother_qualification" id="mother_qualification" class="form-control"
                                value="{{ old('mother_qualification', $draftApplication->mother_qualification ?? $candidate->mother_qualification ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="parent_occupation" class="form-label">Parent's Occupation <span class="text-danger">*</span></label>
                            <input type="text" name="parent_occupation" id="parent_occupation" class="form-control"
                                value="{{ old('parent_occupation', $draftApplication->parent_occupation ?? $candidate->parents_occupation ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="blood_group" class="form-label">Blood Group <span class="text-danger">*</span></label>
                            <input type="text" name="blood_group" id="blood_group" class="form-control"
                                value="{{ old('blood_group', $draftApplication->blood_group ?? $candidate->blood_group ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="age" class="form-label">Age <span class="text-danger">*</span> <small>(उमेर)</small></label>
                            <input type="text" name="age" id="age" class="form-control" min="0"
                                value="{{ old('age', $draftApplication->age ?? '') }}" required readonly>
                            <input type="hidden" id="age_numeric" name="age_numeric" value="">
                        </div>
                        <!-- <div class="col-md-4">
                            <label for="alternate_phone_number" class="form-label">Alternate Phone Number <small>(वैकल्पिक फोन नम्बर)</small></label>
                            <input type="text" name="alternate_phone_number" id="alternate_phone_number" class="form-control"
                                value="{{ old('alternate_phone_number', $draftApplication->alternate_phone_number ?? $candidate->alternate_phone ?? '') }}">
                        </div> -->
                        <div class="col-md-4">
                            <label for="alternate_phone_number" class="form-label">Alternate Phone Number <small>(वैकल्पिक फोन नम्बर)</small></label>
                            <input type="text" name="alternate_phone_number" id="alternate_phone_number" class="form-control"
                                value="{{ old('alternate_phone_number', $draftApplication->alternate_phone_number ?? $candidate->alternate_phone ?? '') }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════
                     STEP 2 — General Information
                     ══════════════════════════════════════════════════════ --}}
                <div class="step d-none" id="step2">
                    <h5 class="mb-4 text-dark">Step 2 — General Information</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="religion" class="form-label">Religion <span class="text-danger">*</span> <small>(धर्म)</small></label>
                            <select name="religion" id="religion" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Hindu" {{ old('religion', $draftApplication->religion ?? $candidate->religion ?? '') == 'Hindu'     ? 'selected' : '' }}>Hindu / हिन्दू</option>
                                <option value="Buddhist" {{ old('religion', $draftApplication->religion ?? $candidate->religion ?? '') == 'Buddhist'  ? 'selected' : '' }}>Buddhist / बौद्ध</option>
                                <option value="Christian" {{ old('religion', $draftApplication->religion ?? $candidate->religion ?? '') == 'Christian' ? 'selected' : '' }}>Christian / ख्रीष्टिय</option>
                                <option value="Muslim" {{ old('religion', $draftApplication->religion ?? $candidate->religion ?? '') == 'Muslim'    ? 'selected' : '' }}>Muslim / मुस्लिम</option>
                                <option value="Other" {{ old('religion', $draftApplication->religion ?? $candidate->religion ?? '') == 'Other'     ? 'selected' : '' }}>Other / अन्य</option>
                            </select>
                            <input type="text" name="religion_other" id="religion_other" class="form-control mt-2 d-none"
                                placeholder="If other, specify" value="{{ old('religion_other', $draftApplication->religion_other ?? $candidate->religion_other ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="community" class="form-label">Community <span class="text-danger">*</span> <small>(तपाई आफैलाई के बोलाउन रुचाउनुहुन्छ)</small></label>
                            <select name="community" id="community" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Male" {{ old('community', $draftApplication->community ?? $candidate->community ?? '') == 'Male'   ? 'selected' : '' }}>पुरुष</option>
                                <option value="Female" {{ old('community', $draftApplication->community ?? $candidate->community ?? '') == 'Female' ? 'selected' : '' }}>महिला</option>
                                <option value="LGBTQ" {{ old('community', $draftApplication->community ?? $candidate->community ?? '') == 'LGBTQ'  ? 'selected' : '' }}>LGBTQ+</option>
                                <option value="Other" {{ old('community', $draftApplication->community ?? $candidate->community ?? '') == 'Other'  ? 'selected' : '' }}>Other / अन्य</option>
                            </select>
                            <input type="text" name="community_other" id="community_other" class="form-control mt-2 d-none"
                                placeholder="If other, specify" value="{{ old('community_other', $draftApplication->community_other ?? $candidate->community_other ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="ethnic_group" class="form-label">Ethnic Group <span class="text-danger">*</span> <small>(जातीय समूह)</small></label>
                            <select name="ethnic_group" id="ethnic_group" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Dalit" {{ old('ethnic_group', $draftApplication->ethnic_group ?? $candidate->ethnic_group ?? '') == 'Dalit'           ? 'selected' : '' }}>Dalit</option>
                                <option value="Janajati" {{ old('ethnic_group', $draftApplication->ethnic_group ?? $candidate->ethnic_group ?? '') == 'Janajati'        ? 'selected' : '' }}>Janajati</option>
                                <option value="Madhesi" {{ old('ethnic_group', $draftApplication->ethnic_group ?? $candidate->ethnic_group ?? '') == 'Madhesi'         ? 'selected' : '' }}>Madhesi</option>
                                <option value="Brahmin/Chhetri" {{ old('ethnic_group', $draftApplication->ethnic_group ?? $candidate->ethnic_group ?? '') == 'Brahmin/Chhetri' ? 'selected' : '' }}>Brahmin / Chhetri</option>
                                <option value="Other" {{ old('ethnic_group', $draftApplication->ethnic_group ?? $candidate->ethnic_group ?? '') == 'Other'           ? 'selected' : '' }}>Other</option>
                            </select>
                            <input type="text" name="ethnic_group_other" id="ethnic_group_other" class="form-control mt-2 d-none"
                                placeholder="If other, specify" value="{{ old('ethnic_group_other', $draftApplication->ethnic_group_other ?? $candidate->ethnic_group_other ?? '') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        @php
                        $ethnicCertificate = $draftApplication->ethnic_certificate
                        ?? $candidate->ethnic_certificate
                        ?? null;
                        @endphp

                        <div class="col-md-6">
                            <label for="ethnic_certificate" class="form-label">Ethnic Certificate</label>

                            <input type="file"
                                name="ethnic_certificate"
                                id="ethnic_certificate"
                                class="form-control"
                                accept="image/*,application/pdf"
                                {{ $ethnicCertificate ? 'data-existing-file=1' : '' }}>

                            <small class="text-muted">Max Size: 700KB</small>

                            @if($ethnicCertificate)
                            <small class="text-success d-block mt-1">
                                Previously uploaded:
                                <a href="{{ asset('storage/'.$ethnicCertificate) }}" target="_blank">View</a>
                                — upload new to replace
                            </small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="mother_tongue" class="form-label">Mother Tongue <span class="text-danger">*</span> <small>(मातृभाषा)</small></label>
                            <input type="text" name="mother_tongue" id="mother_tongue" class="form-control"
                                value="{{ old('mother_tongue', $draftApplication->mother_tongue ?? $candidate->mother_tongue ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="employment_status" class="form-label">Employment Status <span class="text-danger">*</span> <small>(रोजगार स्थिति)</small></label>
                            <select name="employment_status" id="employment_status" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="employed" {{ old('employment_status', $draftApplication->employment_status ?? $candidate->employment_status ?? '') == 'employed'   ? 'selected' : '' }}>Employed</option>
                                <option value="unemployed" {{ old('employment_status', $draftApplication->employment_status ?? $candidate->employment_status ?? '') == 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════
                     STEP 3 — Address
                     ══════════════════════════════════════════════════════ --}}
                <div class="step d-none" id="step3">
                    <h5 class="mb-4 text-dark">Step 3 — Permanent Address</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="permanent_province" class="form-label">Province <span class="text-danger">*</span></label>
                            <select name="permanent_province" id="permanent_province" class="form-select" required onchange="cascadeDistrict('permanent')">
                                <option value="">-- Select Province --</option>
                                @foreach(['Koshi','Madhesh','Bagmati','Gandaki','Lumbini','Karnali','Sudurpashchim'] as $province)
                                <option value="{{ $province }}" {{ old('permanent_province', $draftApplication->permanent_province ?? $candidate->permanent_province ?? '') == $province ? 'selected' : '' }}>{{ $province }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="permanent_district" class="form-label">District <span class="text-danger">*</span></label>
                            <select name="permanent_district" id="permanent_district" class="form-select" required onchange="cascadeMunicipality('permanent')" disabled>
                                <option value="">-- Select District --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="permanent_municipality" class="form-label">Municipality <span class="text-danger">*</span></label>
                            <select name="permanent_municipality" id="permanent_municipality" class="form-select" required disabled>
                                <option value="">-- Select Municipality --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="permanent_ward" class="form-label">Ward No. <span class="text-danger">*</span></label>
                            <input type="text" name="permanent_ward" id="permanent_ward" class="form-control"
                                value="{{ old('permanent_ward', $draftApplication->permanent_ward ?? $candidate->permanent_ward ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="permanent_tole" class="form-label">Tole</label>
                            <input type="text" name="permanent_tole" id="permanent_tole" class="form-control"
                                value="{{ old('permanent_tole', $draftApplication->permanent_tole ?? $candidate->permanent_tole ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="permanent_house_number" class="form-label">House Number</label>
                            <input type="text" name="permanent_house_number" id="permanent_house_number" class="form-control"
                                value="{{ old('permanent_house_number', $draftApplication->permanent_house_number ?? $candidate->permanent_house_number ?? '') }}">
                        </div>
                    </div>

                    <h5 class="mb-4 text-dark mt-4">Mailing/Current Address</h5>
                    <div class="form-check mb-4">
                        <input type="checkbox" class="form-check-input" id="same_as_permanent" name="same_as_permanent" value="1"
                            {{ old('same_as_permanent') ? 'checked' : '' }} onchange="toggleSameAsPermanent()">
                        <label class="form-check-label" for="same_as_permanent">Same as Permanent Address</label>
                    </div>

                    <div id="mailing_fields">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="mailing_province" class="form-label">Province <span class="text-danger">*</span></label>
                                <select name="mailing_province" id="mailing_province" class="form-select" required onchange="cascadeDistrict('mailing')">
                                    <option value="">-- Select Province --</option>
                                    @foreach(['Koshi','Madhesh','Bagmati','Gandaki','Lumbini','Karnali','Sudurpashchim'] as $province)
                                    <option value="{{ $province }}" {{ old('mailing_province', $draftApplication->mailing_province ?? $candidate->mailing_province ?? '') == $province ? 'selected' : '' }}>{{ $province }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="mailing_district" class="form-label">District <span class="text-danger">*</span></label>
                                <select name="mailing_district" id="mailing_district" class="form-select" required onchange="cascadeMunicipality('mailing')" disabled>
                                    <option value="">-- Select District --</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="mailing_municipality" class="form-label">Municipality <span class="text-danger">*</span></label>
                                <select name="mailing_municipality" id="mailing_municipality" class="form-select" required disabled>
                                    <option value="">-- Select Municipality --</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="mailing_ward" class="form-label">Ward No. <span class="text-danger">*</span></label>
                                <input type="text" name="mailing_ward" id="mailing_ward" class="form-control"
                                    value="{{ old('mailing_ward', $draftApplication->mailing_ward ?? $candidate->mailing_ward ?? '') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="mailing_tole" class="form-label">Tole</label>
                                <input type="text" name="mailing_tole" id="mailing_tole" class="form-control"
                                    value="{{ old('mailing_tole', $draftApplication->mailing_tole ?? $candidate->mailing_tole ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="mailing_house_number" class="form-label">House Number</label>
                                <input type="text" name="mailing_house_number" id="mailing_house_number" class="form-control"
                                    value="{{ old('mailing_house_number', $draftApplication->mailing_house_number ?? $candidate->mailing_house_number ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════
                     STEP 4 — Educational Background
                     ══════════════════════════════════════════════════════ --}}
                <div class="step d-none" id="step4">
                    <h5 class="mb-4 text-dark">Step 4 — Educational Background</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="education_level" class="form-label">Highest Education Level <span class="text-danger">*</span></label>
                            <select name="education_level" id="education_level" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Under SLC" {{ old('education_level', $draftApplication->education_level ?? $candidate->education_level ?? '') == 'Under SLC'       ? 'selected' : '' }}>Under SLC</option>
                                <option value="SLC/SEE" {{ old('education_level', $draftApplication->education_level ?? $candidate->education_level ?? '') == 'SLC/SEE'         ? 'selected' : '' }}>SLC/SEE</option>
                                <option value="+2/Intermediate" {{ old('education_level', $draftApplication->education_level ?? $candidate->education_level ?? '') == '+2/Intermediate' ? 'selected' : '' }}>+2/Intermediate</option>
                                <option value="Bachelor" {{ old('education_level', $draftApplication->education_level ?? $candidate->education_level ?? '') == 'Bachelor'        ? 'selected' : '' }}>Bachelor</option>
                                <option value="Master" {{ old('education_level', $draftApplication->education_level ?? $candidate->education_level ?? '') == 'Master'          ? 'selected' : '' }}>Master</option>
                                <option value="PhD" {{ old('education_level', $draftApplication->education_level ?? $candidate->education_level ?? '') == 'PhD'             ? 'selected' : '' }}>PhD</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="field_of_study" class="form-label">Field of Study <span class="text-danger">*</span></label>
                            <input type="text" name="field_of_study" id="field_of_study" class="form-control"
                                value="{{ old('field_of_study', $draftApplication->field_of_study ?? $candidate->field_of_study ?? '') }}" required>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-end">
                        <div class="col-md-6">
                            <label for="institution_name" class="form-label">Institution Name <span class="text-danger">*</span></label>
                            <input type="text" name="institution_name" id="institution_name" class="form-control"
                                value="{{ old('institution_name', $draftApplication->institution_name ?? $candidate->institution_name ?? '') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="graduation_year" class="form-label">Passed Year in BS <span class="text-danger">*</span></label>
                            <input type="text" name="graduation_year" id="graduation_year" class="form-control"
                                placeholder="YYYY" inputmode="numeric" maxlength="4" autocomplete="off"
                                value="{{ old('graduation_year', $draftApplication->graduation_year ?? $candidate->graduation_year ?? '') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="graduation_year_english" class="form-label">Passed Year in AD <span class="text-danger">*</span></label>
                            <input type="text" name="graduation_year_english" id="graduation_year_english" class="form-control"
                                placeholder="YYYY" inputmode="numeric" maxlength="4" autocomplete="off"
                                value="{{ old('graduation_year_english', $draftApplication->graduation_year_english ?? $candidate->graduation_year_english ?? '') }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="university" class="form-label">University Name <span class="text-danger">*</span></label>
                            <input type="text" name="university" id="university" class="form-control"
                                value="{{ old('university', $draftApplication->university ?? $candidate->university ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="transcript" class="form-label">
                                Transcript Certificate <span class="text-danger">*</span>
                            </label>

                            <input type="file"
                                name="transcript"
                                id="transcript"
                                class="form-control"
                                accept="image/*,application/pdf"
                                {{ (($draftApplication && $draftApplication->transcript) || ($candidate && $candidate->transcript))
                                ? 'data-existing-file="1"'
                                : 'required' }}>

                            <small class="text-muted d-block">Max Size: 700KB</small>

                            @if(($draftApplication && $draftApplication->transcript) || ($candidate && $candidate->transcript))
                            <small class="text-success d-block mt-1">
                                Previously uploaded:
                                <a href="{{ asset('storage/' . ($draftApplication->transcript ?? $candidate->transcript)) }}"
                                    target="_blank">
                                    View
                                </a>
                                — upload new to replace
                            </small>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="character" class="form-label">
                                Character Certificate <span class="text-danger">*</span>
                            </label>

                            <input type="file"
                                name="character"
                                id="character"
                                class="form-control"
                                accept="image/*,application/pdf"
                                {{ $candidate && $candidate->character_certificate ? 'data-existing-file="1"' : 'required' }}>

                            <small class="text-muted d-block">Max Size: 700KB</small>

                            @if($candidate && $candidate->character_certificate)
                            <small class="text-success d-block mt-1">
                                Previously uploaded:
                                <a href="{{ asset('storage/'.$candidate->character_certificate) }}" target="_blank">
                                    View
                                </a>
                                — upload new to replace
                            </small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="equivalency" class="form-label">
                                Equivalency Certificate (If your degree is out of Nepal)
                            </label>

                            <input type="file"
                                name="equivalency"
                                id="equivalency"
                                class="form-control"
                                accept="image/*,application/pdf">

                            <small class="text-muted d-block">Max Size: 700KB</small>

                            @if($candidate && $candidate->equivalency_certificate)
                            <small class="text-success d-block mt-1">
                                Previously uploaded:
                                <a href="{{ asset('storage/'.$candidate->equivalency_certificate) }}" target="_blank">
                                    View
                                </a>
                                — upload new to replace
                            </small>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════
                     STEP 5 — Work Experience
                     ══════════════════════════════════════════════════════ --}}
                <div class="step d-none" id="step5">
                    <h5 class="mb-4 text-dark">Step 5 — Work Experience</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="has_work_experience" class="form-label">Do you have work experience? <span class="text-danger">*</span></label>
                            <select name="has_work_experience" id="has_work_experience" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Yes" {{ old('has_work_experience', $draftApplication->has_work_experience ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('has_work_experience', $draftApplication->has_work_experience ?? '') == 'No'  ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <div id="experience_table_wrapper" style="display:none;">

                        <div id="experience_rows">
    @php
        // Collect experiences that actually have data, in order 1-10
        $profileExps = collect([$exp1, $exp2, $exp3, $exp4, $exp5, $exp6, $exp7, $exp8, $exp9, $exp10])
            ->filter()
            ->values();
        // If draft already has experiences saved against the application_form, prefer those
        if (isset($draftApplication) && $draftApplication && $draftApplication->experiences && $draftApplication->experiences->count() > 0) {
            $profileExps = $draftApplication->experiences->sortBy('exp_number')->values();
        }
        $rowCount = max($profileExps->count(), 1);
    @endphp

    @for ($i = 1; $i <= $rowCount; $i++)
        @php $exp = $profileExps->get($i - 1); @endphp
        <div class="experience-row border rounded p-3 mb-3" data-row="{{ $i }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong class="text-muted" style="font-size:.9rem;">
                    Experience #<span class="row-number">{{ $i }}</span>
                </strong>
                <button type="button" class="btn btn-sm btn-outline-danger remove-exp-row" style="{{ $rowCount > 1 ? '' : 'display:none;' }}">
                    <i class="bi bi-trash"></i> Remove
                </button>
            </div>

            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small">Organization</label>
                    <input type="text"
                        name="exp{{ $i }}_organization"
                        class="form-control form-control-sm"
                        value="{{ old("exp{$i}_organization", $exp?->organization ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label small">Position</label>
                    <input type="text"
                        name="exp{{ $i }}_position"
                        class="form-control form-control-sm"
                        value="{{ old("exp{$i}_position", $exp?->position ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label small">Start Date (B.S)</label>
                    <input type="text"
                        name="exp{{ $i }}_start_date_bs"
                        class="form-control form-control-sm exp-nepali-date"
                        placeholder="YYYY-MM-DD"
                        data-target="exp{{ $i }}_start_date"
                        autocomplete="off"
                        value="{{ old("exp{$i}_start_date_bs", $exp?->start_date_bs ?? '') }}">

                    <input type="hidden"
                        name="exp{{ $i }}_start_date"
                        value="{{ old("exp{$i}_start_date", $exp?->start_date ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label small">End Date (B.S)</label>
                    <input type="text"
                        name="exp{{ $i }}_end_date_bs"
                        class="form-control form-control-sm exp-nepali-date"
                        placeholder="YYYY-MM-DD"
                        data-target="exp{{ $i }}_end_date"
                        autocomplete="off"
                        value="{{ old("exp{$i}_end_date_bs", $exp?->end_date_bs ?? '') }}">

                    <input type="hidden"
                        name="exp{{ $i }}_end_date"
                        value="{{ old("exp{$i}_end_date", $exp?->end_date ?? '') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small">Years</label>
                    <input type="number"
                        step="0.5"
                        name="exp{{ $i }}_years"
                        class="form-control form-control-sm"
                        value="{{ old("exp{$i}_years", $exp?->years ?? '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label small">Document</label>

                    <input type="file"
                        name="exp{{ $i }}_document"
                        class="form-control form-control-sm"
                        accept="image/*,application/pdf">

                    @if($exp?->document)
                    <small class="text-success">
                        <i class="bi bi-check-circle"></i>
                        File uploaded:
                        <a href="{{ asset('storage/' . $exp->document) }}" target="_blank">View</a>
                    </small>
                    @endif
                </div>
            </div>
        </div>
    @endfor
</div>

                        <div class="d-flex align-items-center gap-3 mt-2 mb-3">
                            <button type="button" id="addExpRow" class="btn btn-sm btn-outline-dark">
                                <i class="bi bi-plus-circle"></i> Add Experience
                            </button>
                            <span class="text-muted small" id="expRowCount">1 / 10 entries</span>
                        </div>

                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════
                     STEP 6 — Upload Documents
                     ══════════════════════════════════════════════════════ --}}
                <div class="step d-none" id="step6">
                    <h5 class="mb-4 text-dark">Step 6 — Upload Documents</h5>
                    <div class="row mb-3">
                        @php
                        $passportPhoto = $draftApplication->passport_size_photo ?? $candidate->passport_size_photo ?? null;
                        @endphp

                        <div class="col-md-6">
                            <label for="passport_size_photo" class="form-label">
                                Passport Size Photo <span class="text-danger">*</span>
                            </label>

                            <input type="file"
                                name="passport_size_photo"
                                id="passport_size_photo"
                                class="form-control"
                                accept="image/*,application/pdf"
                                {{ $passportPhoto ? 'data-existing-file=1' : 'required' }}>

                            <small class="text-muted d-block">Max Size: 700KB</small>

                            @if($passportPhoto)
                            <small class="text-success d-block mt-1">
                                Previously uploaded:
                                <a href="{{ asset('storage/'.$passportPhoto) }}" target="_blank">View</a>
                                — upload new to replace
                            </small>
                            @endif
                        </div>
                        @php
                        $citizenshipDoc = $draftApplication->citizenship_id_document ?? $candidate->citizenship_id_document ?? null;
                        @endphp

                        <div class="col-md-6">
                            <label for="citizenship_id_document" class="form-label">
                                Citizenship/ID Document
                                <span class="text-danger">
                                    <small>(Please upload front and back in same page)</small> *
                                </span>
                            </label>

                            <input type="file"
                                name="citizenship_id_document"
                                id="citizenship_id_document"
                                class="form-control"
                                accept="image/*,application/pdf"
                                {{ $citizenshipDoc ? 'data-existing-file=1' : 'required' }}>

                            <small class="text-muted d-block">Max Size: 700KB</small>

                            @if($citizenshipDoc)
                            <small class="text-success d-block mt-1">
                                Previously uploaded:
                                <a href="{{ asset('storage/'.$citizenshipDoc) }}" target="_blank">View</a>
                                — upload new to replace
                            </small>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        @php
                        $signature = $draftApplication->signature ?? $candidate->signature ?? null;
                        @endphp

                        <div class="col-md-6">
                            <label for="signature" class="form-label">
                                Signature <span class="text-danger">*</span>
                            </label>

                            <input type="file"
                                name="signature"
                                id="signature"
                                class="form-control"
                                accept="image/*,application/pdf"
                                {{ $signature ? 'data-existing-file=1' : 'required' }}>

                            <small class="text-muted d-block">Max Size: 700KB</small>

                            @if($signature)
                            <small class="text-success d-block mt-1">
                                Previously uploaded:
                                <a href="{{ asset('storage/'.$signature) }}" target="_blank">View</a>
                                — upload new to replace
                            </small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="additional_documents" class="form-label">Additional Document</label>
                            <input type="file" name="additional_documents" id="additional_documents" class="form-control" accept="image/*,application/pdf">
                            <small class="text-muted d-block">Max Size: 700KB</small>
                            @if($draftApplication && $draftApplication->additional_documents)
                            <small class="text-success d-block mt-1">
                                Previously uploaded: <a href="{{ asset('storage/'.$draftApplication->additional_documents) }}" target="_blank">View</a> — upload new to replace
                            </small>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════
                     STEP 7 — Preview
                     ══════════════════════════════════════════════════════ --}}
                <div class="step d-none" id="step7">
                    <h5 class="mb-4 text-dark">Step 7 — Preview Application Before Payment</h5>
                    <div class="alert alert-info">Please review all your details carefully before proceeding to payment.</div>
                    <div id="previewContainer">
                        <h6 class="text-secondary mt-3">Personal Information</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Full Name (English)</th>
                                <td id="p_name_english"></td>
                            </tr>
                            <tr>
                                <th>Full Name (Nepali)</th>
                                <td id="p_name_nepali"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td id="p_email"></td>
                            </tr>
                            <tr>
                                <th>Birth Date (AD)</th>
                                <td id="p_birth_date_ad"></td>
                            </tr>
                            <tr>
                                <th>Birth Date (BS)</th>
                                <td id="p_birth_date_bs"></td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td id="p_phone"></td>
                            </tr>
                            <tr>
                                <th>Advertisement Number</th>
                                <td id="p_advertisement_no"></td>
                            </tr>
                            <tr>
                                <th>Applying Position</th>
                                <td id="p_applying_position"></td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td id="p_department"></td>
                            </tr>
                            <tr>
                                <th>Age</th>
                                <td id="p_age"></td>
                            </tr>
                            <tr>
                                <th>Alternate Phone Number</th>
                                <td id="p_alternate_phone_number"></td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td id="p_gender"></td>
                            </tr>
                            <tr>
                                <th>Marital Status</th>
                                <td id="p_marital_status"></td>
                            </tr>
                            <tr>
                                <th>Spouse Name (If Married)</th>
                                <td id="spouse_name_english"></td>
                            </tr>
                            <tr>
                                <th>Spouse Nationality (If Married)</th>
                                <td id="p_spouse_nationality"></td>
                            </tr>
                            <tr>
                                <th>Citizenship Number</th>
                                <td id="p_citizenship_number"></td>
                            </tr>
                            <tr>
                                <th>Citizenship Issue Date (B.S)</th>
                                <td id="p_citizenship_issue_date_bs"></td>
                            </tr>
                            <tr>
                                <th>Citizenship Issue District</th>
                                <td id="p_citizenship_issue_district"></td>
                            </tr>
                            <tr>
                                <th>Father Name</th>
                                <td id="p_father_name_english"></td>
                            </tr>
                            <tr>
                                <th>Mother Name</th>
                                <td id="p_mother_name_english"></td>
                            </tr>
                            <tr>
                                <th>Grandfather Name</th>
                                <td id="p_grandfather_name_english"></td>
                            </tr>
                            <tr>
                                <th>Father Name in Nepali (बुबाको नाम नेपालीमा)</th>
                                <td id="p_father_name_nepali"></td>
                            </tr>
                            <tr>
                                <th>Mother Name in Nepali (आमाको नाम नेपालीमा)</th>
                                <td id="p_mother_name_nepali"></td>
                            </tr>
                            <tr>
                                <th>Grandfather Name in Nepali (हजुरबुबाको नाम नेपालीमा)</th>
                                <td id="p_grandfather_name_nepali"></td>
                            </tr>
                            <tr>
                                <th>Father's Qualification</th>
                                <td id="p_father_qualification"></td>
                            </tr>
                            <tr>
                                <th>Mother's Qualification</th>
                                <td id="p_mother_qualification"></td>
                            </tr>
                            <tr>
                                <th>Parent's Occupation</th>
                                <td id="p_parent_occupation"></td>
                            </tr>
                            <tr>
                                <th>Blood Group</th>
                                <td id="p_blood_group"></td>
                            </tr>
                            <tr>
                                <th>Nationality</th>
                                <td id="p_nationality"></td>
                            </tr>
                            <tr>
                                <th>Are you NOC Employee?</th>
                                <td id="p_noc_employee"></td>
                            </tr>
                            <tr>
                                <th>NOC ID Card</th>
                                <td id="p_noc_id_card"></td>
                            </tr>
                        </table>

                        <h6 class="text-secondary mt-3">General Information</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Religion</th>
                                <td id="p_religion"></td>
                            </tr>
                            <tr>
                                <th>Community</th>
                                <td id="p_community"></td>
                            </tr>
                            <tr>
                                <th>Ethnic Group</th>
                                <td id="p_ethnic_group"></td>
                            </tr>
                            <tr>
                                <th>Mother Tongue</th>
                                <td id="p_mother_tongue"></td>
                            </tr>
                            <tr>
                                <th>Employment Status</th>
                                <td id="p_employment_status"></td>
                            </tr>
                            <tr>
                                <th>Physical Disability</th>
                                <td id="p_physical_disability"></td>
                            </tr>
                            <tr>
                                <th>Ethnic Certificate</th>
                                <td id="p_ethnic_certificate"></td>
                            </tr>
                            <tr>
                                <th>Disability Certificate</th>
                                <td id="p_disability_certificate"></td>
                            </tr>
                        </table>

                        <h6 class="text-secondary mt-4">Address Information</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Permanent Address</th>
                                <td id="p_permanent_address"></td>
                            </tr>
                            <tr>
                                <th>Mailing Address</th>
                                <td id="p_mailing_address"></td>
                            </tr>
                        </table>

                        <h6 class="text-secondary mt-4">Education</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Education Level</th>
                                <td id="p_education_level"></td>
                            </tr>
                            <tr>
                                <th>Field of Study</th>
                                <td id="p_field_of_study"></td>
                            </tr>
                            <tr>
                                <th>Institution</th>
                                <td id="p_institution_name"></td>
                            </tr>
                            <tr>
                                <th>Passed Year</th>
                                <td id="p_graduation_year"></td>
                            </tr>
                        </table>

                        <h6 class="text-secondary mt-4">Work Experience</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Has Experience</th>
                                <td id="p_has_work_experience"></td>
                            </tr>
                            <tr>
                                <th>Experience Details</th>
                                <td>
                                    <div id="experience_preview"></div>
                                </td>
                            </tr>
                        </table>

                        <h6 class="text-secondary mt-4">Uploaded Documents</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Passport Size Photo</th>
                                <td id="p_photo"></td>
                            </tr>
                            <tr>
                                <th>Citizenship / ID Document</th>
                                <td id="p_citizenship"></td>
                            </tr>
                            <tr>
                                <th>Transcript</th>
                                <td id="p_transcript"></td>
                            </tr>
                            <tr>
                                <th>Character</th>
                                <td id="p_character"></td>
                            </tr>
                            <tr>
                                <th>Equivalent</th>
                                <td id="p_equivalent"></td>
                            </tr>
                            <tr>
                                <th>Signature</th>
                                <td id="p_signature"></td>
                            </tr>
                            <tr>
                                <th>Additional Document</th>
                                <td id="p_additional_documents"></td>
                            </tr>
                        </table>

                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="terms_agree" name="terms_agree" required>
                            <label class="form-check-label" for="terms_agree">
                                I hereby declare that all information provided is true and correct.(म यसैद्वारा घोषणा गर्दछु कि प्रदान गरिएको सबै जानकारी सत्य र सही छ।) <span class="text-danger">*</span>
                            </label>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-btn">Back</button>
                            <button type="button" class="btn btn-light next-btn">Next</button>
                        </div>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════
                     STEP 8 — Payment
                     ══════════════════════════════════════════════════════ --}}
                <div class="step d-none" id="step8">
                    <h5 class="mb-4 text-dark">Step 8 — Payment & Declaration</h5>
                    <div id="paymentSection">
                        <div class="card border-success mb-4">
                            <div class="card-body">
                                <h6 class="card-title text-success mb-3">
                                    <i class="bi bi-receipt me-2"></i>Payment Summary
                                </h6>
                                @php
                                $isDoubleDasturPayment = $job && $job->deadline
                                && now()->gt($job->deadline)
                                && $job->double_dastur_fee
                                && $job->double_dastur_date
                                && now()->lte($job->double_dastur_date);
                                @endphp
                                @if($isDoubleDasturPayment)
                                <div class="alert alert-warning py-2 mb-3" style="font-size:13px;">
                                    Application deadline has passed. Double Dastur fee has been implemented.
                                </div>
                                @endif
                                <table class="table table-sm table-bordered mb-0">
                                    <tbody>
                                        <tr>
                                            <th width="50%">Selected Categories</th>
                                            <td id="step8CategoryNames" class="text-secondary">—</td>
                                        </tr>
                                        <tr>
                                            <th>Applying Position</th>
                                            <td id="step8Position">—</td>
                                        </tr>
                                        <tr class="table-success">
                                            <th class="fw-bold fs-5">Total Amount Payable</th>
                                            <td class="fw-bold fs-5 text-success">
                                                Rs. <span id="step8TotalFee">0</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if(isset($payment) && in_array($payment->status, ['paid', 'completed']))
                        <div class="alert alert-success mb-3">✓ Payment already completed via {{ strtoupper($payment->gateway) }}</div>
                        @endif
                        <h6 class="mb-3">Choose Payment Gateway</h6>
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <button type="button" class="payment-box payment-gateway-btn w-100 bg-white" data-gateway="esewa">
                                    <img src="/images/esewalogo.jpg" alt="eSewa" class="payment-logo">
                                    <div>Pay with eSewa</div>
                                </button>
                            </div>
                            <div class="col-md-4 mb-3">
                                <button type="button" class="payment-box payment-gateway-btn w-100 bg-white" data-gateway="khalti">
                                    <img src="/images/khaltilogo.jpg" alt="Khalti" class="payment-logo">
                                    <div>Pay with Khalti</div>
                                </button>
                            </div>
                            <div class="col-md-4 mb-3">
                                <button type="button" class="payment-box payment-gateway-btn w-100 bg-white" data-gateway="connectips">
                                    <img src="/images/cipslogo.jpg" alt="ConnectIPS" class="payment-logo">
                                    <div>Pay with ConnectIPS</div>
                                </button>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-btn">Back</button>
                                <button type="button" id="saveDraftBtn" class="btn btn-danger">Save Application and Pay Later</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     Nepali Date Picker JS
     ══════════════════════════════════════════════════════ --}}
<script src="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/js/nepali.datepicker.v5.0.6.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        function initNDP(el, opts) {
            if (!el || typeof el.nepaliDatePicker !== 'function') return;
            el.nepaliDatePicker(Object.assign({
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 100,
                onChange: function() {
                    el.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                    el.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }
            }, opts || {}));
        }

        document.querySelectorAll('.nepali-date').forEach(function(el) {
            initNDP(el);
        });

        initNDP(document.getElementById('birth_date_bs'));
        initNDP(document.getElementById('citizenship_issue_date_bs'));

        var bsInput = document.getElementById('birth_date_bs');
        var adEl = document.getElementById('birth_date_ad');
        var lastBsVal = bsInput ? bsInput.value : '';

        if (bsInput && bsInput.value && adEl && !adEl.value && typeof window.bsToAD === 'function') {
            var ad0 = window.bsToAD(bsInput.value);
            if (ad0) {
                adEl.value = ad0;
                var adDisp0 = document.getElementById('birth_date_ad_display');
                if (adDisp0 && window.formatADDisplay) adDisp0.value = window.formatADDisplay(ad0);
                adEl.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            }
        }

        setInterval(function() {
            if (!bsInput || !adEl) return;
            var cur = bsInput.value;
            if (cur && cur !== lastBsVal && cur.length >= 10) {
                lastBsVal = cur;
                if (typeof window.bsToAD === 'function') {
                    var ad = window.bsToAD(cur);
                    if (ad) {
                        adEl.value = ad;
                        var adDisp = document.getElementById('birth_date_ad_display');
                        if (adDisp && window.formatADDisplay) adDisp.value = window.formatADDisplay(ad);
                        adEl.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                        adEl.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    }
                }
            }
        }, 300);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const workExperienceSelect = document.getElementById('has_work_experience');
        const experienceTable = document.getElementById('experience_table_wrapper');

        function toggleExperienceTable() {
            if (!workExperienceSelect || !experienceTable) return;
            experienceTable.style.display = workExperienceSelect.value === 'Yes' ? 'block' : 'none';
        }

        toggleExperienceTable();
        workExperienceSelect.addEventListener('change', toggleExperienceTable);
    });
</script>

{{-- ══════════════════════════════════════════════════════
     BS ↔ AD Converter
     ══════════════════════════════════════════════════════ --}}
<script>
    (function() {
        'use strict';

        const bsMonthData = {
            1975: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            1976: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            1977: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            1978: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1979: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            1980: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            1981: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            1982: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1983: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            1984: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            1985: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            1986: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1987: [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            1988: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            1989: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            1990: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1991: [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            1992: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            1993: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            1994: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1995: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            1996: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            1997: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1998: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1999: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2000: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2001: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2002: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2003: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2004: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2005: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2006: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2007: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2008: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            2009: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2010: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2011: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2012: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            2013: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2014: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2015: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2016: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            2017: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2018: [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2019: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2020: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2021: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2022: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            2023: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2024: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2025: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2026: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2027: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2028: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2029: [31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
            2030: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2031: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2032: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2033: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2034: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2035: [30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            2036: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2037: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2038: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2039: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            2040: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2041: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2042: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2043: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            2044: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2045: [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2046: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2047: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2048: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2049: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            2050: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2051: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2052: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2053: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            2054: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2055: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2056: [31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
            2057: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2058: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2059: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2060: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2061: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2062: [30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31],
            2063: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2064: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2065: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2066: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            2067: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2068: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2069: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2070: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            2071: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2072: [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2073: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2074: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2075: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2076: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            2077: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2078: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2079: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2080: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            2081: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 30],
            2082: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 31],
            2083: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2084: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2085: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2086: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2087: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2088: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2089: [30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31],
            2090: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2091: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2092: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2093: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            2094: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2095: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2096: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2097: [30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            2098: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2099: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30]
        };

        const bsStartYear = 2000,
            bsStartMonth = 1,
            bsStartDay = 1;
        const adRefDate = new Date(1943, 3, 14);

        function daysInBsMonth(y, m) {
            return (bsMonthData[y] || [])[m - 1] || 30;
        }

        function totalDaysInBsYear(y) {
            if (!bsMonthData[y]) return 365;
            return bsMonthData[y].reduce((s, d) => s + d, 0);
        }

        function countBsDays(year, month, day) {
            let total = 0;
            for (let y = bsStartYear; y < year; y++) total += totalDaysInBsYear(y);
            for (let m = 1; m < month; m++) total += daysInBsMonth(year, m);
            return total + (day - bsStartDay);
        }

        window.bsToAD = function(bsDateStr) {
            try {
                const [y, m, d] = bsDateStr.split('-').map(Number);
                if (!y || !m || !d) return '';
                const ad = new Date(adRefDate);
                ad.setDate(ad.getDate() + countBsDays(y, m, d));
                return ad.getFullYear() + '-' +
                    String(ad.getMonth() + 1).padStart(2, '0') + '-' +
                    String(ad.getDate()).padStart(2, '0');
            } catch {
                return '';
            }
        };

        window.adToBS = function(adDateStr) {
            try {
                const adDate = new Date(adDateStr);
                if (isNaN(adDate.getTime())) return '';
                let days = Math.floor((adDate - adRefDate) / 86400000);
                let y = bsStartYear,
                    m = bsStartMonth,
                    d = bsStartDay + days;
                while (d > daysInBsMonth(y, m)) {
                    d -= daysInBsMonth(y, m);
                    if (++m > 12) {
                        m = 1;
                        y++;
                    }
                }
                while (d < 1) {
                    if (--m < 1) {
                        m = 12;
                        y--;
                    }
                    d += daysInBsMonth(y, m);
                }
                return y + '-' + String(m).padStart(2, '0') + '-' + String(d).padStart(2, '0');
            } catch {
                return '';
            }
        };

        window.nepaliLibrariesReady = true;
    })();

    window.formatADDisplay = function(yyyymmdd) {
        if (!yyyymmdd) return '';
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var d = new Date(yyyymmdd);
        if (isNaN(d.getTime())) return yyyymmdd;
        return d.getFullYear() + '-' + months[d.getMonth()] + '-' + ('0' + d.getDate()).slice(-2);
    };
</script>

{{-- ══════════════════════════════════════════════════════
     Nepal Address Cascade Data
     ══════════════════════════════════════════════════════ --}}
<script>
    const NEPAL_DATA = {
        Koshi: {
            Bhojpur: ["Bhojpur Municipality", "Shadananda Municipality", "Hatuwagadhi Rural Municipality", "Arun Rural Municipality", "Tyamke Maiyum Rural Municipality", "Ramprasad Rai Rural Municipality", "Pauwadungma Rural Municipality", "Salpasilichho Rural Municipality"],
            Dhankuta: ["Dhankuta Municipality", "Pakhribas Municipality", "Mahalaxmi Municipality", "Chhathar Jorpati Rural Municipality", "Sangurigadhi Rural Municipality", "Sahidbhumi Rural Municipality", "Khalsa Rural Municipality"],
            Ilam: ["Ilam Municipality", "Deumai Municipality", "Mai Municipality", "Suryodaya Municipality", "Maijogmai Rural Municipality", "Sandakpur Rural Municipality", "Chulachuli Rural Municipality", "Mangsebung Rural Municipality", "Rong Rural Municipality", "Phakphokthum Rural Municipality"],
            Jhapa: ["Arjundhara Municipality", "Bhadrapur Municipality", "Birtamod Municipality", "Damak Municipality", "Kankai Municipality", "Mechinagar Municipality", "Shivasataxi Municipality", "Gauradaha Municipality", "Haldibari Municipality", "Buddhashanti Rural Municipality", "Barhadashi Rural Municipality", "Kabeli Rural Municipality", "Kachankawal Rural Municipality", "Gaurigunj Rural Municipality"],
            Khotang: ["Diktel Rupakot Majhuwagadhi Municipality", "Halesi Tuwachung Municipality", "Khotehang Rural Municipality", "Barahpokhari Rural Municipality", "Kepilasgadhi Rural Municipality", "Ainselukhark Rural Municipality", "Lamidanda Rural Municipality", "Sakela Rural Municipality", "Rawabesi Rural Municipality", "Diprung Chuichumma Rural Municipality"],
            Morang: ["Biratnagar Metropolitan City", "Rangeli Municipality", "Sundarharaicha Municipality", "Letang Municipality", "Belbari Municipality", "Pathari Shanischare Municipality", "Ratuwamai Municipality", "Jahada Rural Municipality", "Budhiganga Rural Municipality", "Gramthan Rural Municipality", "Katahari Rural Municipality", "Kerabari Rural Municipality", "Miklajung Rural Municipality", "Sunawarshi Rural Municipality", "Uralabari Rural Municipality"],
            Okhaldhunga: ["Siddhicharan Municipality", "Molung Rural Municipality", "Champadevi Rural Municipality", "Chisankhugadhi Rural Municipality", "Khijidemba Rural Municipality", "Likhu Rural Municipality", "Manebhanjyang Rural Municipality", "Sunkoshi Rural Municipality"],
            Panchthar: ["Phidim Municipality", "Falgunanda Rural Municipality", "Hilihang Rural Municipality", "Kummayak Rural Municipality", "Miklajung Rural Municipality", "Phalelung Rural Municipality", "Tumbewa Rural Municipality", "Yashokchhap Rural Municipality"],
            Sankhuwasabha: ["Chainpur Municipality", "Dharmadevi Municipality", "Khandbari Municipality", "Madi Municipality", "Panchkhapan Municipality", "Chichila Rural Municipality", "Makalu Rural Municipality", "Sabhapokhari Rural Municipality", "Silichong Rural Municipality"],
            Solukhumbu: ["Solududhkunda Municipality", "Salleri Municipality", "Thulung Dudhkoshi Rural Municipality", "Sotang Rural Municipality", "Mahakulung Rural Municipality", "Khumbu Pasanglhamu Rural Municipality", "Likhupike Rural Municipality", "Nechasalyan Rural Municipality"],
            Sunsari: ["Dharan Sub-Metropolitan City", "Itahari Sub-Metropolitan City", "Inaruwa Municipality", "Duhabi Municipality", "Barahakshetra Municipality", "Ramdhuni Municipality", "Harinagara Rural Municipality", "Koshi Rural Municipality", "Gadhi Rural Municipality", "Barju Rural Municipality"],
            Taplejung: ["Phungling Municipality", "Sidingba Rural Municipality", "Aathrai Tribeni Rural Municipality", "Meringden Rural Municipality", "Mikwakhola Rural Municipality", "Pathibhara Yangwarak Rural Municipality", "Sirijangha Rural Municipality", "Phaktanglung Rural Municipality"],
            Terhathum: ["Myanglung Municipality", "Laligurans Municipality", "Aathrai Rural Municipality", "Chhathar Rural Municipality", "Phedap Rural Municipality"]
        },
        Madhesh: {
            Bara: ["Kalaiya Sub-Metropolitan City", "Jitpur Simara Sub-Metropolitan City", "Nijgadh Municipality", "Mahagadhimai Municipality", "Simraungadh Municipality", "Pacharauta Municipality", "Prasauni Rural Municipality", "Bishrampur Rural Municipality", "Devtal Rural Municipality", "Pheta Rural Municipality", "Kaudena Rural Municipality", "Adarshkotwal Rural Municipality", "Suwarna Rural Municipality", "Baragadhi Rural Municipality", "Kolhabi Rural Municipality"],
            Dhanusha: ["Janakpur Sub-Metropolitan City", "Mithila Municipality", "Dhanusha Municipality", "Sabaila Municipality", "Kamala Municipality", "Mithila Bihari Municipality", "Dhanushadham Municipality", "Bideha Municipality", "Aurahi Rural Municipality", "Bateshwar Rural Municipality", "Chhireshwarnath Rural Municipality", "Dhanauji Rural Municipality", "Ganeshman Charnath Rural Municipality", "Hansapur Rural Municipality", "Hans Rupa Rural Municipality", "Janaknandini Rural Municipality", "Lakshminiya Rural Municipality", "Mukhiyapatti Musaharmiya Rural Municipality", "Nagarain Rural Municipality", "Shankarpur Rural Municipality"],
            Mahottari: ["Jaleshwar Municipality", "Gaushala Municipality", "Matihani Municipality", "Bardibas Municipality", "Bhangaha Municipality", "Loharpatti Municipality", "Manra Siswa Municipality", "Samsi Municipality", "Sonama Rural Municipality", "Ekdara Rural Municipality", "Mahottari Rural Municipality", "Pipra Rural Municipality", "Ramgopalpur Rural Municipality"],
            Parsa: ["Birgunj Metropolitan City", "Bahudarmai Municipality", "Parsagadhi Municipality", "Pokhariya Municipality", "Bindabasini Rural Municipality", "Chhipaharmai Rural Municipality", "Dhobini Rural Municipality", "Jirabhawani Rural Municipality", "Kalikamai Rural Municipality", "Pakaha Mainpur Rural Municipality", "Paterwas Rural Municipality", "Paterwa Sugauli Rural Municipality", "Sakhuwa Prasauni Rural Municipality", "Thori Rural Municipality"],
            Rautahat: ["Chandrapur Municipality", "Gaur Municipality", "Baudha Rural Municipality", "Garuda Rural Municipality", "Gujara Rural Municipality", "Katahariya Rural Municipality", "Madhav Narayan Rural Municipality", "Maulapur Rural Municipality", "Paroha Rural Municipality", "Phatuwa Bijayapur Rural Municipality", "Rajdevi Rural Municipality", "Rajpur Rural Municipality", "Brindaban Rural Municipality", "Dumarwana Rural Municipality", "Ishanath Rural Municipality", "Dewahi Gonahi Rural Municipality", "Yamunamai Rural Municipality"],
            Saptari: ["Rajbiraj Municipality", "Kanchanrup Municipality", "Surunga Municipality", "Agnisair Krishna Savaran Rural Municipality", "Balan-Bihul Rural Municipality", "Bishnupur Rural Municipality", "Chhinnamasta Rural Municipality", "Dakneshwari Rural Municipality", "Hanumannagar Kankalini Municipality", "Khadak Rural Municipality", "Mahadewa Rural Municipality", "Rajgadh Rural Municipality", "Rupani Rural Municipality", "Shambhunath Municipality", "Tirahut Rural Municipality", "Saptakoshi Rural Municipality"],
            Sarlahi: ["Lalbandi Municipality", "Haripur Municipality", "Hariwan Municipality", "Barahathawa Municipality", "Ishworpur Municipality", "Malangawa Municipality", "Bagmati Rural Municipality", "Ballara Rural Municipality", "Brahampuri Rural Municipality", "Chandranagar Rural Municipality", "Chakraghatta Rural Municipality", "Dhankaul Rural Municipality", "Godaita Municipality", "Haripurwa Rural Municipality", "Kabilasi Rural Municipality", "Parsa Rural Municipality", "Ramnagar Rural Municipality"],
            Siraha: ["Lahan Municipality", "Siraha Municipality", "Golbazar Municipality", "Mirchaiya Municipality", "Kalyanpur Municipality", "Sukhipur Municipality", "Aurahi Rural Municipality", "Bishnupur Rural Municipality", "Bariyarpatti Rural Municipality", "Dhangadhimai Municipality", "Karjanha Rural Municipality", "Lakshmipur Patari Rural Municipality", "Nawarajpur Rural Municipality", "Sakhuwanankarkatti Rural Municipality", "Shyam Sundar Madi Rural Municipality"]
        },
        Bagmati: {
            Bhaktapur: ["Bhaktapur Municipality", "Changunarayan Municipality", "Madhyapur Thimi Municipality", "Suryabinayak Municipality"],
            Chitwan: ["Bharatpur Metropolitan City", "Ratnanagar Municipality", "Ichchhakamana Rural Municipality", "Kalika Municipality", "Khairahani Municipality", "Madi Municipality", "Rapti Municipality", "Rapti Sonari Rural Municipality"],
            Dhading: ["Nilkantha Municipality", "Benighat Rorang Rural Municipality", "Gajuri Rural Municipality", "Galchhi Rural Municipality", "Gangajamuna Rural Municipality", "Jwalamukhi Rural Municipality", "Khaniyabas Rural Municipality", "Netrawati Daijee Rural Municipality", "Rubi Valley Rural Municipality", "Siddhalek Rural Municipality", "Thakre Rural Municipality", "Tripura Sundari Rural Municipality"],
            Dolakha: ["Bhimeshwar Municipality", "Jiri Municipality", "Bigu Rural Municipality", "Baiteshwar Rural Municipality", "Gaurishankar Rural Municipality", "Kalinchok Rural Municipality", "Melung Rural Municipality", "Shailung Rural Municipality", "Tamakoshi Rural Municipality"],
            Kathmandu: ["Kathmandu Metropolitan City", "Kirtipur Municipality", "Budhanilkantha Municipality", "Chandragiri Municipality", "Dakshinkali Municipality", "Gokarneshwar Municipality", "Kageshwari Manohara Municipality", "Nagarjun Municipality", "Shankharapur Municipality", "Tarakeshwar Municipality", "Tokha Municipality"],
            Kavrepalanchok: ["Banepa Municipality", "Dhulikhel Municipality", "Panauti Municipality", "Namobuddha Municipality", "Mandandeupur Municipality", "Panchkhal Municipality", "Bethanchok Rural Municipality", "Bhumlu Rural Municipality", "Chaurideurali Rural Municipality", "Khanikhola Rural Municipality", "Mahabharat Rural Municipality", "Roshi Rural Municipality", "Temal Rural Municipality"],
            Lalitpur: ["Lalitpur Metropolitan City", "Godawari Municipality", "Mahalaxmi Municipality", "Konjyosom Rural Municipality", "Bagmati Rural Municipality"],
            Makwanpur: ["Hetauda Sub-Metropolitan City", "Thaha Municipality", "Bagmati Rural Municipality", "Bakaiya Rural Municipality", "Bhimphedi Rural Municipality", "Indrasarowar Rural Municipality", "Kailash Rural Municipality", "Makawanpurgadhi Rural Municipality", "Manahari Rural Municipality", "Raksirang Rural Municipality"],
            Nuwakot: ["Bidur Municipality", "Belkotgadhi Municipality", "Kakani Rural Municipality", "Dupcheshwar Rural Municipality", "Meghang Rural Municipality", "Myagang Rural Municipality", "Panchakanya Rural Municipality", "Shivapuri Rural Municipality", "Suryagadhi Rural Municipality", "Tadi Rural Municipality", "Tarkeshwar Rural Municipality", "Likhu Rural Municipality"],
            Ramechhap: ["Manthali Municipality", "Ramechhap Municipality", "Doramba Rural Municipality", "Gokulganga Rural Municipality", "Khandadevi Rural Municipality", "Likhu Tamakoshi Rural Municipality", "Saipatithan Rural Municipality", "Sunapati Rural Municipality"],
            Rasuwa: ["Kalika Rural Municipality", "Naukunda Rural Municipality", "Gosaikunda Rural Municipality", "Aamachhodingmo Rural Municipality", "Uttargaya Rural Municipality"],
            Sindhuli: ["Kamalamai Municipality", "Dudhauli Municipality", "Golanjor Rural Municipality", "Ghyanglekh Rural Municipality", "Hariharpurgadhi Rural Municipality", "Marin Rural Municipality", "Phikkal Rural Municipality", "Sunkoshi Rural Municipality", "Tinpatan Rural Municipality"],
            Sindhupalchok: ["Chautara Sangachokgadhi Municipality", "Melamchi Municipality", "Balephi Rural Municipality", "Barhabise Rural Municipality", "Bhotekoshi Rural Municipality", "Helambu Rural Municipality", "Indrawati Rural Municipality", "Jugal Rural Municipality", "Lisankhu Pakhar Rural Municipality", "Panchpokhari Thangpal Rural Municipality", "Sunkoshi Rural Municipality", "Tripurasundari Rural Municipality"]
        },
        Gandaki: {
            Baglung: ["Baglung Municipality", "Galkot Municipality", "Dhorpatan Municipality", "Taman Khola Rural Municipality", "Nisikhola Rural Municipality", "Jaimuni Municipality", "Bareng Rural Municipality", "Kanthekhola Rural Municipality", "Tatopani Rural Municipality"],
            Gorkha: ["Gorkha Municipality", "Palungtar Municipality", "Arughat Rural Municipality", "Arpak Dudhapokhara Rural Municipality", "Bhimsen Rural Municipality", "Barpak Sulikot Rural Municipality", "Dharche Rural Municipality", "Gandaki Rural Municipality", "Ajirkot Rural Municipality", "Chum Nubri Rural Municipality", "Sahid Lakhan Rural Municipality", "Siranchok Rural Municipality", "Tsum Nubri Rural Municipality"],
            Kaski: ["Pokhara Metropolitan City", "Annapurna Rural Municipality", "Machhapuchchhre Rural Municipality", "Madi Rural Municipality", "Rupa Rural Municipality"],
            Lamjung: ["Besisahar Municipality", "Rainas Municipality", "Sundarbazar Municipality", "Dordi Rural Municipality", "Dudhpokhari Rural Municipality", "Kwholasothar Rural Municipality", "Marsyangdi Rural Municipality", "Madhya Nepal Rural Municipality", "Chamje Rural Municipality"],
            Manang: ["Chame Rural Municipality", "Narphu Rural Municipality", "Nasong Rural Municipality"],
            Mustang: ["Gharpajhong Rural Municipality", "Lomanthang Rural Municipality", "Thasang Rural Municipality", "Waragung Muktikhola Rural Municipality", "Dalome Rural Municipality"],
            Myagdi: ["Beni Municipality", "Annapurna Rural Municipality", "Dhaulagiri Rural Municipality", "Mangala Rural Municipality", "Malika Rural Municipality", "Raghuganga Rural Municipality"],
            Nawalpur: ["Kawasoti Municipality", "Devchuli Municipality", "Bardaghat Municipality", "Gaindakot Municipality", "Hupsekot Municipality", "Binayi Tribeni Rural Municipality", "Bulingtar Rural Municipality", "Madhyabindu Municipality", "Palhi Nandan Rural Municipality", "Pratappur Rural Municipality", "Rainas Rural Municipality", "Sarawal Rural Municipality"],
            Parbat: ["Kushma Municipality", "Phalewas Municipality", "Airawati Rural Municipality", "Bihadi Rural Municipality", "Jaljala Rural Municipality", "Mahashila Rural Municipality", "Modi Rural Municipality", "Painyu Rural Municipality"],
            Syangja: ["Waling Municipality", "Putalibazar Municipality", "Galyang Municipality", "Bhirkot Municipality", "Arjunchaupari Rural Municipality", "Biruwa Rural Municipality", "Aandhikhola Rural Municipality", "Harinas Rural Municipality", "Kaligandaki Rural Municipality", "Phedikhola Rural Municipality"],
            Tanahun: ["Damauli Municipality", "Bhimad Municipality", "Byas Municipality", "Shuklagandaki Municipality", "Bandipure Rural Municipality", "Ghiring Rural Municipality", "Myagde Rural Municipality", "Rhishing Rural Municipality", "Devghat Rural Municipality", "Anbukhaireni Rural Municipality"]
        },
        Lumbini: {
            Arghakhanchi: ["Sandhikharka Municipality", "Sitganga Municipality", "Chhatradev Rural Municipality", "Bhumekasthan Rural Municipality", "Malarani Rural Municipality", "Panini Rural Municipality", "Shivarajpur Rural Municipality"],
            Banke: ["Nepalgunj Sub-Metropolitan City", "Kohalpur Municipality", "Narainapur Rural Municipality", "Khajura Rural Municipality", "Janaki Rural Municipality", "Raptisonari Rural Municipality", "Duduwa Rural Municipality"],
            Bardiya: ["Gulariya Municipality", "Rajapur Municipality", "Madhuwan Municipality", "Barbardiya Municipality", "Thakurbaba Municipality", "Badhaiyatal Rural Municipality", "Bansgadhi Municipality", "Geruwa Rural Municipality"],
            Dang: ["Tulsipur Sub-Metropolitan City", "Ghorahi Sub-Metropolitan City", "Lamahi Municipality", "Shantinagar Rural Municipality", "Babai Rural Municipality", "Bangalachuli Rural Municipality", "Gadhawa Rural Municipality", "Rajpur Rural Municipality", "Rapti Rural Municipality", "Dangisharan Rural Municipality"],
            Gulmi: ["Musikot Municipality", "Resunga Municipality", "Isma Rural Municipality", "Chatrakot Rural Municipality", "Chandrakot Rural Municipality", "Kaligandaki Rural Municipality", "Madane Rural Municipality", "Malika Rural Municipality", "Ruru Rural Municipality", "Satyawati Rural Municipality", "Gulmi Durbar Rural Municipality"],
            Kapilvastu: ["Banganga Municipality", "Buddhabhumi Municipality", "Kapilvastu Municipality", "Krishnanagar Municipality", "Maharajgunj Municipality", "Shivaraj Municipality", "Bijaynagar Rural Municipality", "Motipur Rural Municipality", "Suddhodhan Rural Municipality", "Yashodhara Rural Municipality"],
            Palpa: ["Tansen Municipality", "Rampur Municipality", "Rainadevi Chhahara Rural Municipality", "Bagnaskali Rural Municipality", "Mathagadhi Rural Municipality", "Nisdi Rural Municipality", "Purbakhola Rural Municipality", "Rambha Rural Municipality", "Ribdikot Rural Municipality", "Tinau Rural Municipality"],
            Pyuthan: ["Pyuthan Municipality", "Swargadwari Municipality", "Ayirawati Rural Municipality", "Gaumukhi Rural Municipality", "Jhimruk Rural Municipality", "Lungri Rural Municipality", "Mallarani Rural Municipality", "Mandavi Rural Municipality", "Naubahini Rural Municipality", "Sarumarani Rural Municipality"],
            Rolpa: ["Rolpa Municipality", "Runtigadhi Rural Municipality", "Sunchhahari Rural Municipality", "Thawang Rural Municipality", "Tribeni Rural Municipality", "Madi Rural Municipality", "Lungri Rural Municipality", "Pariwartan Rural Municipality", "Gangadev Rural Municipality"],
            Rupandehi: ["Butwal Sub-Metropolitan City", "Siddharthanagar Sub-Metropolitan City", "Devdaha Municipality", "Lumbini Sanskritik Municipality", "Marchawar Municipality", "Omsatiya Municipality", "Saljhandi Rural Municipality", "Sammarimai Rural Municipality", "Rohini Rural Municipality", "Kanchan Rural Municipality", "Kotahimai Rural Municipality", "Gaidahawa Rural Municipality", "Sainamaina Municipality", "Tillotama Municipality", "Mayadevi Rural Municipality", "Siyari Rural Municipality", "Sudhdhodhan Rural Municipality"]
        },
        Karnali: {
            Dailekh: ["Narayan Municipality", "Chamunda Bindrasaini Municipality", "Dullu Municipality", "Aathabis Municipality", "Bhairabi Municipality", "Gurans Rural Municipality", "Mahabu Rural Municipality", "Naumule Rural Municipality", "Dungeshwar Rural Municipality", "Bhagawatimai Rural Municipality", "Thatikandh Rural Municipality"],
            Dolpa: ["Thuli Bheri Municipality", "Tripurasundari Municipality", "Dolpo Buddha Rural Municipality", "Kaike Rural Municipality", "Mudkechula Rural Municipality", "She Phoksundo Rural Municipality", "Jagadulla Rural Municipality", "Chharka Tangsong Rural Municipality"],
            Humla: ["Simkot Rural Municipality", "Kharpunath Rural Municipality", "Adanchuli Rural Municipality", "Chankheli Rural Municipality", "Namkha Rural Municipality", "Sarkegad Rural Municipality", "Tanjakot Rural Municipality"],
            Jajarkot: ["Bheri Municipality", "Chhedagad Municipality", "Barekot Rural Municipality", "Junichande Rural Municipality", "Kuse Rural Municipality", "Nalagad Municipality", "Shiwalaya Rural Municipality"],
            Jumla: ["Chandannath Municipality", "Sinja Rural Municipality", "Tatopani Rural Municipality", "Guthichaur Rural Municipality", "Kankasundari Rural Municipality", "Patarasi Rural Municipality", "Hima Rural Municipality"],
            Kalikot: ["Manma Municipality", "Sanni Triveni Rural Municipality", "Raskot Municipality", "Bajura Rural Municipality", "Mahawai Rural Municipality", "Palata Rural Municipality", "Shubha Kalika Municipality", "Pachaljharana Rural Municipality", "Tilagufa Municipality", "Khandachakra Municipality"],
            Mugu: ["Chhayanath Rara Municipality", "Mugum Karmarong Rural Municipality", "Khatyad Rural Municipality", "Soru Rural Municipality"],
            Salyan: ["Sharada Municipality", "Bangad Kupinde Municipality", "Bagchaur Municipality", "Kalimati Rural Municipality", "Darma Rural Municipality", "Kumakh Rural Municipality", "Siddha Kumakh Rural Municipality", "Triveni Rural Municipality"],
            Surkhet: ["Birendranagar Municipality", "Bheriganga Municipality", "Gurbhakot Municipality", "Lekbesi Municipality", "Panchpuri Municipality", "Barahtal Rural Municipality", "Simta Rural Municipality", "Chaukune Rural Municipality", "Chingad Rural Municipality"]
        },
        Sudurpashchim: {
            Achham: ["Mangalsen Municipality", "Kamalbazar Municipality", "Mellekh Rural Municipality", "Bannigadhi Jayagadh Rural Municipality", "Ramaroshan Rural Municipality", "Sanphebagar Municipality", "Dhakari Rural Municipality", "Chaurpati Rural Municipality", "Turmakhand Rural Municipality"],
            Baitadi: ["Dasharathchand Municipality", "Purnagiri Municipality", "Sigas Rural Municipality", "Dogadakedar Rural Municipality", "Purchaudi Municipality", "Dilasaini Rural Municipality", "Melauli Rural Municipality", "Surnaya Rural Municipality", "Patan Rural Municipality", "Shivnath Rural Municipality"],
            Bajhang: ["Jaya Prithvi Municipality", "Bungal Municipality", "Talkot Municipality", "Masta Rural Municipality", "Kuldevmandu Rural Municipality", "Saipal Rural Municipality", "Khaptadchhanna Rural Municipality", "Thalara Rural Municipality", "Surma Rural Municipality", "Chhededaha Rural Municipality", "Bithadchir Rural Municipality", "Durgathali Rural Municipality", "Kanda Rural Municipality"],
            Bajura: ["Badimalika Municipality", "Budhiganga Municipality", "Budhinanda Municipality", "Gaumul Rural Municipality", "Himali Rural Municipality", "Jagannath Rural Municipality", "Khaptad Chhanna Rural Municipality", "Swami Kartik Rural Municipality", "Triveni Rural Municipality"],
            Dadeldhura: ["Amargadhi Municipality", "Aalital Rural Municipality", "Ajayameru Rural Municipality", "Bhageshwar Rural Municipality", "Ganyapadhura Rural Municipality", "Nawadurga Rural Municipality", "Parashuram Municipality"],
            Darchula: ["Shailyashikhar Municipality", "Malikarjun Rural Municipality", "Apihimal Rural Municipality", "Byash Rural Municipality", "Naugad Rural Municipality", "Duhu Rural Municipality", "Lekam Rural Municipality", "Marma Rural Municipality", "Mahakali Municipality"],
            Dothi: ["Shikhar Municipality", "Dipayal Silgadhi Municipality", "Badikedar Rural Municipality", "Bogtan Phago Rural Municipality", "Jorayal Rural Municipality", "K.I.Singh Rural Municipality", "Purbichauki Rural Municipality", "Aadarsha Rural Municipality", "Sayal Rural Municipality"],
            Kailali: ["Dhangadhi Sub-Metropolitan City", "Tikapur Municipality", "Bhajani Municipality", "Ghodaghodi Municipality", "Godawari Municipality", "Kailari Rural Municipality", "Bardagoriya Rural Municipality", "Chure Rural Municipality", "Gauriganga Municipality", "Joshipur Rural Municipality", "Mohanyal Rural Municipality", "Phatepur Rural Municipality", "Janaki Rural Municipality", "Lamkichuha Municipality"],
            Kanchanpur: ["Bhimdatta Municipality", "Belauri Municipality", "Bedkot Municipality", "Punarbas Municipality", "Shuklaphanta Municipality", "Beldandi Rural Municipality", "Laljhadi Rural Municipality", "Mahakali Municipality", "Pipaladi Rural Municipality"]
        }
    };

    function populateSelect(sel, opts, placeholder) {
        sel.innerHTML = `<option value="">${placeholder}</option>`;
        opts.forEach(o => {
            const el = document.createElement('option');
            el.value = el.textContent = o;
            sel.appendChild(el);
        });
    }

    function cascadeDistrict(prefix) {
        const prov = document.getElementById(prefix + '_province').value;
        const distSel = document.getElementById(prefix + '_district');
        const munSel = document.getElementById(prefix + '_municipality');
        munSel.innerHTML = '<option value="">-- Select Municipality --</option>';
        munSel.disabled = true;
        if (prov && NEPAL_DATA[prov]) {
            populateSelect(distSel, Object.keys(NEPAL_DATA[prov]).sort(), '-- Select District --');
            distSel.disabled = false;
        } else {
            distSel.innerHTML = '<option value="">-- Select District --</option>';
            distSel.disabled = true;
        }
    }

    function cascadeMunicipality(prefix) {
        const prov = document.getElementById(prefix + '_province').value;
        const dist = document.getElementById(prefix + '_district').value;
        const munSel = document.getElementById(prefix + '_municipality');
        if (prov && dist && NEPAL_DATA[prov]?.[dist]) {
            populateSelect(munSel, NEPAL_DATA[prov][dist], '-- Select Municipality --');
            munSel.disabled = false;
        } else {
            munSel.innerHTML = '<option value="">-- Select Municipality --</option>';
            munSel.disabled = true;
        }
    }

    function toggleSameAsPermanent() {
        const checked = document.getElementById('same_as_permanent').checked;
        const mf = document.getElementById('mailing_fields');
        if (checked) {
            document.getElementById('mailing_province').value = document.getElementById('permanent_province').value;
            cascadeDistrict('mailing');
            setTimeout(() => {
                document.getElementById('mailing_district').value = document.getElementById('permanent_district').value;
                cascadeMunicipality('mailing');
                setTimeout(() => {
                    document.getElementById('mailing_municipality').value = document.getElementById('permanent_municipality').value;
                }, 50);
            }, 50);
            ['ward', 'tole', 'house_number'].forEach(f => {
                document.getElementById('mailing_' + f).value = document.getElementById('permanent_' + f)?.value || '';
            });
            mf.style.opacity = '0.5';
            mf.style.pointerEvents = 'none';
        } else {
            mf.style.opacity = '1';
            mf.style.pointerEvents = '';
        }
    }

    // Restore cascade on page load — now includes $candidate fallback for all address fields
    (function() {
        const op = '{{ old("permanent_province", $draftApplication->permanent_province ?? $candidate->permanent_province ?? "") }}';
        const od = '{{ old("permanent_district",  $draftApplication->permanent_district  ?? $candidate->permanent_district  ?? "") }}';
        const om = '{{ old("permanent_municipality", $draftApplication->permanent_municipality ?? $candidate->permanent_municipality ?? "") }}';
        const mp = '{{ old("mailing_province",  $draftApplication->mailing_province  ?? $candidate->mailing_province  ?? "") }}';
        const md = '{{ old("mailing_district",  $draftApplication->mailing_district   ?? $candidate->mailing_district   ?? "") }}';
        const mm = '{{ old("mailing_municipality", $draftApplication->mailing_municipality ?? $candidate->mailing_municipality ?? "") }}';

        if (op) {
            cascadeDistrict('permanent');
            setTimeout(() => {
                if (od) {
                    document.getElementById('permanent_district').value = od;
                    cascadeMunicipality('permanent');
                    setTimeout(() => {
                        if (om) document.getElementById('permanent_municipality').value = om;
                    }, 50);
                }
            }, 50);
        }
        if (mp) {
            cascadeDistrict('mailing');
            setTimeout(() => {
                if (md) {
                    document.getElementById('mailing_district').value = md;
                    cascadeMunicipality('mailing');
                    setTimeout(() => {
                        if (mm) document.getElementById('mailing_municipality').value = mm;
                    }, 50);
                }
            }, 50);
        }
    })();
</script>

{{-- ══════════════════════════════════════════════════════
     Main Form Logic
     ══════════════════════════════════════════════════════ --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ── Save Draft button ─────────────────────────────────────
        setTimeout(() => {
            const saveDraftBtn = document.getElementById('saveDraftBtn');
            if (!saveDraftBtn) return;

            saveDraftBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                let allValid = true;
                for (let i = 1; i <= 7; i++) {
                    if (!validateStep(i)) {
                        allValid = false;
                        showStep(i);
                        showAutoSaveStatus('⚠ Please complete all required fields before saving', 'danger');
                        return;
                    }
                }
                if (!allValid) return;
                saveDraftBtn.disabled = true;
                saveDraftBtn.textContent = 'Saving...';
                showAutoSaveStatus('💾 Saving your application...', 'info');
                const formData = new FormData(form);
                if (draftIdInput?.value) formData.set('draft_id', draftIdInput.value);
                fetch('{{ route("candidate.applications.saveDraft") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(r => r.json()).then(data => {
                    if (data.success) {
                        showAutoSaveStatus('✓ Application saved successfully! You can complete payment later.', 'success');
                        if (data.draft_id) draftIdInput.value = data.draft_id;
                        setTimeout(() => {
                            window.location.href = '{{ route("candidate.applications.index") }}';
                        }, 2000);
                    } else {
                        showAutoSaveStatus('✕ Failed to save: ' + (data.message || 'Unknown error'), 'danger');
                        saveDraftBtn.disabled = false;
                        saveDraftBtn.textContent = 'Save Application and Pay Later';
                    }
                }).catch(err => {
                    showAutoSaveStatus('✕ Error saving application: ' + err.message, 'danger');
                    saveDraftBtn.disabled = false;
                    saveDraftBtn.textContent = 'Save Application and Pay Later';
                });
            });
        }, 1000);

        // ── Core state ────────────────────────────────────────────
        let currentStep = 1;
        const totalSteps = 8;
        const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
        const form = document.getElementById('applicationform');
        const draftIdInput = document.getElementById('draft_id');
        let autoSaveTimeout, isSaving = false;

        // ── Auto-save indicator ───────────────────────────────────
        const autoSaveIndicator = document.createElement('div');
        autoSaveIndicator.id = 'autosave-indicator';
        autoSaveIndicator.style.cssText = 'position:fixed;top:80px;right:20px;padding:12px 24px;border-radius:8px;z-index:9999;display:none;font-weight:600;box-shadow:0 4px 12px rgba(0,0,0,.15);';
        document.body.appendChild(autoSaveIndicator);

        function showAutoSaveStatus(msg, type = 'info') {
            autoSaveIndicator.textContent = msg;
            autoSaveIndicator.className = `alert alert-${type} mb-0`;
            autoSaveIndicator.style.display = 'block';
            setTimeout(() => {
                autoSaveIndicator.style.display = 'none';
            }, 3000);
        }

        function autoSave() {
            if (isSaving) return;
            isSaving = true;
            showAutoSaveStatus('💾 Saving draft...', 'info');
            const fd = new FormData(form);
            if (draftIdInput?.value) fd.set('draft_id', draftIdInput.value);
            fetch('{{ route("candidate.applications.saveDraft") }}', {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(r => {
                    if (!r.ok) throw new Error(`HTTP ${r.status}`);
                    return r.json();
                })
                .then(data => {
                    if (data.success) {
                        showAutoSaveStatus('✓ Draft saved', 'success');
                        if (data.draft_id && !draftIdInput.value) draftIdInput.value = data.draft_id;
                    } else {
                        showAutoSaveStatus('⚠ ' + (data.message || 'Failed to save'), 'warning');
                    }
                })
                .catch(err => {
                    showAutoSaveStatus('✕ Save failed: ' + err.message, 'danger');
                })
                .finally(() => {
                    isSaving = false;
                });
        }

        // ── Conditional file requirements ─────────────────────────
        function conditionalFile(triggerEl, fileEl, labelEl, triggerValues) {
            if (!triggerEl || !fileEl) return;

            function update() {
                const match = triggerValues.includes(triggerEl.value);
                if (match) {
                    fileEl.setAttribute('required', 'required');
                    if (labelEl && !labelEl.querySelector('.text-danger')) labelEl.innerHTML += ' <span class="text-danger">*</span>';
                } else {
                    fileEl.removeAttribute('required');
                    const sp = labelEl?.querySelector('.text-danger');
                    if (sp) sp.remove();
                }
            }
            update();
            triggerEl.addEventListener('change', update);
        }

        conditionalFile(
            document.getElementById('noc_employee'),
            document.getElementById('noc_id_card'),
            document.getElementById('noc_id_card')?.closest('.col-md-4')?.querySelector('label'),
            ['yes']
        );
        conditionalFile(
            document.getElementById('physical_disability'),
            document.getElementById('disability_certificate'),
            document.getElementById('disability_certificate')?.closest('.col-md-4')?.querySelector('label'),
            ['yes']
        );
        conditionalFile(
            document.getElementById('ethnic_group'),
            document.getElementById('ethnic_certificate'),
            document.getElementById('ethnic_certificate')?.closest('.col-md-6')?.querySelector('label'),
            ['Dalit', 'Janajati', 'Madhesi']
        );

        // ── Tabs & progress ───────────────────────────────────────
        function updateTabsAndProgress() {
            document.querySelectorAll('.tab-item').forEach((tab, i) => {
                tab.classList.remove('active', 'completed');
                if (i + 1 < currentStep) tab.classList.add('completed');
                else if (i + 1 === currentStep) tab.classList.add('active');
            });
            const line = document.querySelector('.progress-line');
            if (line) line.style.width = (((currentStep - 1) / (totalSteps - 1)) * 100) + '%';
        }

        function showStep(step) {
            document.querySelectorAll('.step').forEach(s => s.classList.add('d-none'));
            const el = document.getElementById('step' + step);
            if (el) {
                el.classList.remove('d-none');
                el.classList.add('active');
            }
            currentStep = step;
            if (step === 7) {
        populatePreview();
        if (window.populateExperiencePreview) window.populateExperiencePreview();
    }
            updateTabsAndProgress();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function validateStep(step) {
            const stepEl = document.getElementById('step' + step);
            if (!stepEl) return true;
            const wasHidden = stepEl.classList.contains('d-none');
            if (wasHidden) {
                stepEl.classList.remove('d-none');
                stepEl.style.visibility = 'hidden';
            }
            stepEl.querySelectorAll('.is-invalid, .invalid-feedback').forEach(el => {
                el.classList.remove('is-invalid');
                if (el.classList.contains('invalid-feedback')) el.remove();
            });
            let isValid = true,
                firstInvalid = null;
            stepEl.querySelectorAll('input[required],select[required],textarea[required]').forEach(field => {
                if (field.parentElement?.closest('.conditionally-hidden')) return;
                if (field.type === 'checkbox') {
                    if (!field.checked) {
                        isValid = false;
                        field.classList.add('is-invalid');
                        addErr(field, 'You must agree before continuing');
                        if (!firstInvalid) firstInvalid = field;
                    }
                    return;
                }
                if (field.type === 'file') {
                    if (field.files.length === 0 && !field.dataset.existingFile) {
                        isValid = false;
                        field.classList.add('is-invalid');
                        addErr(field, 'This file is required');
                        if (!firstInvalid) firstInvalid = field;
                    }
                    return;
                }
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    addErr(field, 'This field is required');
                    if (!firstInvalid) firstInvalid = field;
                }
            });
            if (wasHidden) {
                stepEl.classList.add('d-none');
                stepEl.style.visibility = '';
            }
            if (!isValid && firstInvalid && !wasHidden) {
                firstInvalid.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                firstInvalid.focus();
                showAutoSaveStatus('⚠ Please fill all required fields', 'warning');
            }
            return isValid;
        }

        function addErr(field, msg) {
            const d = document.createElement('div');
            d.className = 'invalid-feedback';
            d.textContent = msg;
            field.parentNode.appendChild(d);
        }

        // Clickable tabs
        document.querySelectorAll('.tab-item').forEach(tab => {
            tab.addEventListener('click', e => {
                e.preventDefault();
                e.stopPropagation();
                const target = parseInt(tab.dataset.step);
                if (target === currentStep) return;
                if (target < currentStep) {
                    showStep(target);
                    return;
                }
                let ok = true;
                for (let i = currentStep; i < target; i++) {
                    if (!validateStep(i)) {
                        ok = false;
                        showAutoSaveStatus(`⚠ Please complete Step ${i} first`, 'danger');
                        break;
                    }
                }
                if (ok) showStep(target);
            });
        });

        document.querySelectorAll('.next-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                e.stopPropagation();
                if (!validateStep(currentStep)) return;
                if (currentStep < totalSteps) {
                    clearTimeout(autoSaveTimeout);
                    autoSave();
                    setTimeout(() => showStep(currentStep + 1), 500);
                }
            });
        });

        document.querySelectorAll('.prev-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                e.stopPropagation();
                if (currentStep > 1) {
                    clearTimeout(autoSaveTimeout);
                    autoSave();
                    setTimeout(() => showStep(currentStep - 1), 500);
                }
            });
        });

        // Show "Other" text inputs
        ['religion', 'community', 'ethnic_group'].forEach(id => {
            const sel = document.getElementById(id),
                other = document.getElementById(id + '_other');
            if (!sel || !other) return;
            const toggle = () => {
                const show = sel.value === 'Other';
                other.classList.toggle('d-none', !show);
                show ? other.setAttribute('required', 'required') : (other.removeAttribute('required'), other.value = '');
            };
            sel.addEventListener('change', toggle);
            toggle();
        });

        // Auto-save on input / change
        form.addEventListener('input', e => {
            if (e.target.type === 'file') return;
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(autoSave, 2000);
            populatePreview();
        });
        form.addEventListener('change', e => {
            if (e.target.tagName === 'SELECT' || e.target.type === 'checkbox' || e.target.type === 'radio') {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(autoSave, 1000);
            }
            populatePreview();
        });

        // Initialize
        if (hasErrors) {
            setTimeout(() => {
                const inv = document.querySelector('.is-invalid');
                if (inv) {
                    const se = inv.closest('.step');
                    if (se) {
                        showStep(parseInt(se.id.replace('step', '')));
                        return;
                    }
                }
                showStep(1);
            }, 150);
        } else {
            showStep(1);
        }

        // Final submit validation
        form.addEventListener('submit', e => {
            for (let i = 1; i <= totalSteps; i++) {
                if (!validateStep(i)) {
                    showStep(i);
                    e.preventDefault();
                    showAutoSaveStatus('⚠ Please complete all required fields', 'danger');
                    return;
                }
            }
            clearTimeout(autoSaveTimeout);
            showAutoSaveStatus('📤 Submitting...', 'light');
        });

        // ── Preview ───────────────────────────────────────────────
        function populatePreview() {
            const v = id => document.getElementById(id)?.value || '-';
            const s = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = val;
            };

            s('p_name_english', v('name_english'));
            s('p_name_nepali', v('name_nepali'));
            s('p_email', v('email'));
            s('p_birth_date_ad', v('birth_date_ad_display'));
            s('p_birth_date_bs', v('birth_date_bs'));
            s('p_phone', v('phone'));
            s('p_advertisement_no', v('advertisement_no'));
            s('p_applying_position', v('applying_position'));
            s('p_department', v('department') !== '-' ? v('department') : (@json($job ? ($job->service_group ? : $job->department ? : '') : '') || '-'));
            s('p_age', v('age'));
            s('p_alternate_phone_number', v('alternate_phone_number'));
            s('p_gender', v('gender'));
            s('p_marital_status', v('marital_status'));
            s('spouse_name_english', v('spouse_name_english'));
            s('p_spouse_nationality', v('spouse_nationality'));
            s('p_citizenship_number', v('citizenship_number'));
            s('p_citizenship_issue_date_bs', v('citizenship_issue_date_bs'));
            s('p_citizenship_issue_district', v('citizenship_issue_district'));
            s('p_father_name_english', v('father_name_english'));
            s('p_mother_name_english', v('mother_name_english'));
            s('p_grandfather_name_english', v('grandfather_name_english'));
            s('p_father_name_nepali', v('father_name_nepali'));
            s('p_mother_name_nepali', v('mother_name_nepali'));
            s('p_grandfather_name_nepali', v('grandfather_name_nepali'));
            s('p_father_qualification', v('father_qualification'));
            s('p_mother_qualification', v('mother_qualification'));
            s('p_parent_occupation', v('parent_occupation'));
            s('p_blood_group', v('blood_group'));
            s('p_nationality', v('nationality'));
            s('p_noc_employee', v('noc_employee'));
            s('p_religion', v('religion'));
            s('p_community', v('community'));
            s('p_ethnic_group', v('ethnic_group'));
            s('p_mother_tongue', v('mother_tongue'));
            s('p_employment_status', v('employment_status'));
            s('p_physical_disability', v('physical_disability'));
            s('p_permanent_address', v('permanent_province') + ', ' + v('permanent_district') + ', ' + v('permanent_municipality') + ' - ' + v('permanent_ward'));
            s('p_mailing_address', v('mailing_province') + ', ' + v('mailing_district') + ', ' + v('mailing_municipality') + ' - ' + v('mailing_ward'));
            s('p_education_level', v('education_level'));
            s('p_field_of_study', v('field_of_study'));
            s('p_institution_name', v('institution_name'));
            s('p_graduation_year', v('graduation_year'));
            s('p_has_work_experience', v('has_work_experience'));

            function renderStoredFile(containerId, url) {
                const container = document.getElementById(containerId);
                if (!container || !url) return;

                const ext = url.split('.').pop().toLowerCase().split('?')[0];
                const imageExts = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];

                if (imageExts.includes(ext)) {
                    const escapedUrl = url.replace(/"/g, '&quot;');
                    container.innerHTML = `<a href="${escapedUrl}" target="_blank"><img src="${escapedUrl}" class="img-thumbnail" style="max-width:150px;max-height:150px;display:block;margin-bottom:4px;"></a><div class="small text-muted">Click to view full size</div>`;
                } else if (ext === 'pdf') {
                    container.innerHTML = `<a href="${url}" target="_blank" class="btn btn-sm btn-outline-secondary">View PDF</a>`;
                } else {
                    container.innerHTML = `<a href="${url}" target="_blank" class="btn btn-sm btn-outline-secondary">View File</a>`;
                }
            }

            function previewFile(containerId, inputName) {
                const input = document.querySelector(`input[name="${inputName}"]`);
                const container = document.getElementById(containerId);
                if (!container) return;
                container.innerHTML = '';
                if (!input?.files?.length) {
                    if (input && input.dataset.existingFile) {
                        const existingLink = input.parentElement ? input.parentElement.querySelector('a') : null;
                        if (existingLink) {
                            renderStoredFile(containerId, existingLink.href);
                        } else {
                            container.textContent = 'Previously uploaded';
                        }
                    } else {
                        container.textContent = 'Not Uploaded';
                    }
                    return;
                }
                const file = input.files[0],
                    url = URL.createObjectURL(file);
                container.innerHTML = file.type.startsWith('image/') ?
                    `<img src="${url}" class="img-thumbnail" style="max-width:150px;max-height:150px;"><div class="mt-1 small text-muted">${file.name}</div>` :
                    `<a href="${url}" target="_blank">${file.name}</a>`;
            }
            previewFile('p_photo', 'passport_size_photo');
            previewFile('p_citizenship', 'citizenship_id_document');
            previewFile('p_transcript', 'transcript');
            previewFile('p_character', 'character');
            previewFile('p_equivalent', 'equivalent');
            previewFile('p_signature', 'signature');
            previewFile('p_noc_id_card', 'noc_id_card');
            previewFile('p_ethnic_certificate', 'ethnic_certificate');
            previewFile('p_disability_certificate', 'disability_certificate');
            previewFile('p_additional_documents', 'additional_documents');
        }

        // ── Payment ───────────────────────────────────────────────
        function paymentUrl(gateway, draftId) {
            const urls = {
                esewa: '/candidate/payment/esewa/start/',
                khalti: '/candidate/payment/khalti/start/',
                connectips: '/candidate/payment/connectips/start/'
            };
            return urls[gateway] ? urls[gateway] + draftId : null;
        }

        function saveDraftForPayment() {
            const fd = new FormData(form);
            if (draftIdInput?.value) fd.set('draft_id', draftIdInput.value);

            return fetch('{{ route("candidate.applications.saveDraft") }}', {
                method: 'POST',
                body: fd,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.json()).then(data => {
                if (!data.success || !data.draft_id) {
                    throw new Error(data.message || 'Could not save application draft.');
                }
                draftIdInput.value = data.draft_id;
                return data.draft_id;
            });
        }

        let paymentStartInProgress = false;

        function setPaymentButtonsDisabled(disabled) {
            document.querySelectorAll('.payment-gateway-btn').forEach(button => {
                button.disabled = disabled;
                button.classList.toggle('opacity-50', disabled);
            });
        }

        window.startPayment = function(gateway) {
            if (paymentStartInProgress) return;

            const url = paymentUrl(gateway, draftIdInput?.value);
            if (url) {
                window.location.href = url;
                return;
            }

            paymentStartInProgress = true;
            setPaymentButtonsDisabled(true);
            showAutoSaveStatus('Saving application before payment...', 'info');
            saveDraftForPayment()
                .then(draftId => {
                    window.location.href = paymentUrl(gateway, draftId);
                })
                .catch(error => {
                    paymentStartInProgress = false;
                    setPaymentButtonsDisabled(false);
                    showAutoSaveStatus('Payment cannot start: ' + error.message, 'danger');
                });
        };

        document.querySelectorAll('.payment-gateway-btn').forEach(button => {
            button.addEventListener('click', () => window.startPayment(button.dataset.gateway));
        });
    });

    // ── Age calculation ───────────────────────────────────────────
    (function() {
        function calculateExactAge(dateString) {
            if (!dateString) return '';
            const birthDate = new Date(dateString);
            if (isNaN(birthDate.getTime())) return '';
            const today = new Date();
            let years = today.getFullYear() - birthDate.getFullYear();
            let months = today.getMonth() - birthDate.getMonth();
            let days = today.getDate() - birthDate.getDate();
            if (days < 0) {
                months--;
                const lastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                days += lastMonth.getDate();
            }
            if (months < 0) {
                years--;
                months += 12;
            }
            return `${years} years ${months} months ${days} days`;
        }

        function updateAge(dateValue) {
            const ageField = document.getElementById('age');
            if (!ageField) return;
            ageField.value = calculateExactAge(dateValue);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const adField = document.getElementById('birth_date_ad');
            const bsField = document.getElementById('birth_date_bs');

            if (adField) {
                adField.addEventListener('change', function() {
                    updateAge(this.value);
                });
                adField.addEventListener('input', function() {
                    updateAge(this.value);
                });
            }

            if (bsField) {
                bsField.addEventListener('change', function() {
                    if (typeof window.bsToAD === 'function') {
                        const adDate = window.bsToAD(this.value);
                        if (adDate) {
                            if (adField) {
                                adField.value = adDate;
                                const adDisp = document.getElementById('birth_date_ad_display');
                                if (adDisp && window.formatADDisplay) adDisp.value = window.formatADDisplay(adDate);
                            }
                            updateAge(adDate);
                        }
                    }
                });
            }

            if (bsField && bsField.value && adField && !adField.value) {
                if (typeof window.bsToAD === 'function') {
                    const adDate = window.bsToAD(bsField.value);
                    if (adDate) {
                        adField.value = adDate;
                        const adDisp = document.getElementById('birth_date_ad_display');
                        if (adDisp && window.formatADDisplay) adDisp.value = window.formatADDisplay(adDate);
                        updateAge(adDate);
                    }
                }
            }

            if (adField && adField.value) {
                updateAge(adField.value);
            }
        });
    })();

    // ── Devanagari-only enforcement ───────────────────────────────
    (function() {
        const fields = document.querySelectorAll('#name_nepali, .nepali-only');
        fields.forEach(function(field) {
            if (!field) return;
            const ok = /[\u0900-\u097F\s.\-]/;
            const clean = str => str.split('').filter(c => ok.test(c)).join('');
            field.addEventListener('keydown', e => {
                if (e.ctrlKey || e.metaKey || e.altKey || ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End', 'Shift'].includes(e.key)) return;
                if (e.key.length === 1 && !ok.test(e.key)) e.preventDefault();
            });
            field.addEventListener('input', function() {
                const pos = this.selectionStart,
                    cleaned = clean(this.value);
                if (cleaned !== this.value) {
                    this.value = cleaned;
                    this.setSelectionRange(Math.min(pos, cleaned.length), Math.min(pos, cleaned.length));
                }
            });
            field.addEventListener('paste', e => {
                e.preventDefault();
                const cleaned = clean((e.clipboardData || window.clipboardData).getData('text'));
                if (!cleaned) return;
                const s = field.selectionStart,
                    en = field.selectionEnd;
                field.value = field.value.slice(0, s) + cleaned + field.value.slice(en);
                field.setSelectionRange(s + cleaned.length, s + cleaned.length);
                field.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
            });
            field.addEventListener('drop', e => {
                e.preventDefault();
                const cleaned = clean(e.dataTransfer.getData('text'));
                if (!cleaned) return;
                const pos = field.selectionStart;
                field.value = field.value.slice(0, pos) + cleaned + field.value.slice(pos);
                field.setSelectionRange(pos + cleaned.length, pos + cleaned.length);
                field.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
            });
        });
    })();

    document.querySelectorAll('.nepali-date').forEach(function(input) {
        input.addEventListener('change', function() {
            const bsDate = this.value;
            if (!bsDate) return;
            try {
                const adDate = window.bsToAD(bsDate);
                const targetName = this.getAttribute('data-target');
                if (!targetName) return;
                const hidden = document.querySelector(`[name="${targetName}"]`);
                if (hidden) hidden.value = adDate;
            } catch (e) {
                console.log("BS conversion failed:", bsDate, e);
            }
        });
    });

    // ── Work Experiences ──────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function() {

        const hasExpSelect = document.getElementById('has_work_experience');
        const expWrapper = document.getElementById('experience_table_wrapper');

        function toggleExpWrapper() {
            if (!hasExpSelect || !expWrapper) return;
            expWrapper.style.display = hasExpSelect.value === 'Yes' ? 'block' : 'none';
        }
        toggleExpWrapper();
        hasExpSelect?.addEventListener('change', toggleExpWrapper);

        const MAX_ROWS = 10;
        const rowsWrap = document.getElementById('experience_rows');
        const addBtn = document.getElementById('addExpRow');
        const countSpan = document.getElementById('expRowCount');

        if (!rowsWrap || !addBtn) return;

        function initNDPOnRow(row) {
            row.querySelectorAll('.exp-nepali-date').forEach(function(el) {
                if (typeof el.nepaliDatePicker === 'function') {
                    el.nepaliDatePicker({
                        ndpYear: true,
                        ndpMonth: true,
                        ndpYearCount: 100,
                        onChange: function() {
                            el.dispatchEvent(new Event('input', {
                                bubbles: true
                            }));
                            el.dispatchEvent(new Event('change', {
                                bubbles: true
                            }));
                        }
                    });
                }
                el.addEventListener('change', function() {
                    const bsDate = this.value;
                    if (!bsDate || typeof window.bsToAD !== 'function') return;
                    const adDate = window.bsToAD(bsDate);
                    const targetName = this.getAttribute('data-target');
                    if (!targetName) return;
                    const hiddenEl = row.querySelector(`input[name="${targetName}"]`);
                    if (hiddenEl) hiddenEl.value = adDate;
                });
            });
        }

        rowsWrap.querySelectorAll('.experience-row').forEach(initNDPOnRow);

        function updateCounter() {
            const rows = rowsWrap.querySelectorAll('.experience-row');
            const n = rows.length;
            if (countSpan) countSpan.textContent = `${n} / ${MAX_ROWS} entr${n === 1 ? 'y' : 'ies'}`;
            addBtn.disabled = n >= MAX_ROWS;
            rows.forEach((row, i) => {
                row.dataset.row = i + 1;
                row.querySelector('.row-number').textContent = i + 1;
                row.querySelector('.remove-exp-row').style.display = n > 1 ? '' : 'none';
            });
        }
        updateCounter();

        function buildRow(n) {
            const div = document.createElement('div');
            div.className = 'experience-row border rounded p-3 mb-3';
            div.dataset.row = n;
            div.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong class="text-muted" style="font-size:.9rem;">
                    Experience #<span class="row-number">${n}</span>
                </strong>
                <button type="button" class="btn btn-sm btn-outline-danger remove-exp-row">
                    <i class="bi bi-trash"></i> Remove
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small">Organization</label>
                    <input type="text" name="exp${n}_organization" class="form-control form-control-sm">
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Position</label>
                    <input type="text" name="exp${n}_position" class="form-control form-control-sm">
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Start Date (B.S)</label>
                    <input type="text" name="exp${n}_start_date_bs"
                        class="form-control form-control-sm exp-nepali-date"
                        placeholder="YYYY-MM-DD" data-target="exp${n}_start_date" autocomplete="off">
                    <input type="hidden" name="exp${n}_start_date">
                </div>
                <div class="col-md-4">
                    <label class="form-label small">End Date (B.S)</label>
                    <input type="text" name="exp${n}_end_date_bs"
                        class="form-control form-control-sm exp-nepali-date"
                        placeholder="YYYY-MM-DD" data-target="exp${n}_end_date" autocomplete="off">
                    <input type="hidden" name="exp${n}_end_date">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Years</label>
                    <input type="number" step="0.5" name="exp${n}_years" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label class="form-label small">Document</label>
                    <input type="file" name="exp${n}_document"
                        class="form-control form-control-sm" accept="image/*,application/pdf">
                </div>
            </div>`;
            return div;
        }

        addBtn.addEventListener('click', function() {
            const current = rowsWrap.querySelectorAll('.experience-row').length;
            if (current >= MAX_ROWS) return;
            const newRow = buildRow(current + 1);
            rowsWrap.appendChild(newRow);
            initNDPOnRow(newRow);
            updateCounter();
            newRow.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        });

        rowsWrap.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-exp-row');
            if (!btn) return;
            const row = btn.closest('.experience-row');
            if (rowsWrap.querySelectorAll('.experience-row').length <= 1) return;
            row.remove();
            rowsWrap.querySelectorAll('.experience-row').forEach((r, i) => {
                const idx = i + 1;
                r.dataset.row = idx;
                r.querySelector('.row-number').textContent = idx;
                r.querySelectorAll('input[name]').forEach(inp => {
                    inp.name = inp.name.replace(/^exp\d+_/, `exp${idx}_`);
                    const dt = inp.getAttribute('data-target');
                    if (dt) inp.setAttribute('data-target', dt.replace(/^exp\d+_/, `exp${idx}_`));
                });
            });
            updateCounter();
            if (window.populateExperiencePreview) window.populateExperiencePreview();
        });

        window.populateExperiencePreview = function() {
            const rows = rowsWrap.querySelectorAll('.experience-row');
            const preview = document.getElementById('experience_preview');
            if (!preview) return;
            let html = '';
            rows.forEach(function(row, i) {
                const n = i + 1;
                const get = name => row.querySelector(`[name="exp${n}_${name}"]`)?.value || '-';
                const fileInput = row.querySelector(`[name="exp${n}_document"]`);
        const file = fileInput?.files?.[0];
        const existingUrl = row.querySelector('a[href*="storage"]')?.getAttribute('href') || null;
        html += `<div style="margin-bottom:14px;padding:10px 12px;border:1px solid #dee2e6;border-radius:8px;">
        <strong style="font-size:.9rem;">Experience ${n}</strong><br>
        <span class="text-muted small">
            <b>Org:</b> ${get('organization')} &nbsp;|&nbsp;
            <b>Position:</b> ${get('position')} &nbsp;|&nbsp;
            <b>From:</b> ${get('start_date_bs')} &nbsp;|&nbsp;
            <b>To:</b> ${get('end_date_bs')} &nbsp;|&nbsp;
            <b>Years:</b> ${get('years')}
        </span>`;
        if (file) {
            const url = URL.createObjectURL(file);
            html += file.type.startsWith('image/') ?
                `<div class="mt-1"><img src="${url}" style="max-width:120px;border:1px solid #ccc;padding:2px;border-radius:4px;"></div>` :
                `<div class="mt-1"><a href="${url}" target="_blank" class="small">${file.name}</a></div>`;
        } else if (existingUrl) {
            const ext = existingUrl.split('.').pop().toLowerCase().split('?')[0];
            const imgExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            html += imgExts.indexOf(ext) !== -1 ?
                `<div class="mt-1"><a href="${existingUrl}" target="_blank"><img src="${existingUrl}" style="max-width:120px;border:1px solid #ccc;padding:2px;border-radius:4px;"></a></div>` :
                `<div class="mt-1"><a href="${existingUrl}" target="_blank" class="btn btn-sm btn-outline-secondary">View Document</a></div>`;
        } else {
            html += `<div class="text-muted" style="font-size:.8rem;">No document uploaded</div>`;
        }
        html += `</div>`;
            });
            preview.innerHTML = html || "<span class='text-muted'>No experience entries</span>";
        };

        rowsWrap.addEventListener('input', function() {
            if (window.populateExperiencePreview) window.populateExperiencePreview();
        });
    });

    // ── Age Validation (male / female / disabled / NOC) ──────────
    (function() {
        'use strict';

        function getLimits() {
            const v = id => parseInt(document.getElementById(id)?.value || '0') || null;
            return {
                male: {
                    min: v('job_min_age_male'),
                    max: v('job_max_age_male')
                },
                female: {
                    min: v('job_min_age_female'),
                    max: v('job_max_age_female')
                },
                disabled: {
                    min: v('job_min_age_disabled'),
                    max: v('job_max_age_disabled')
                },
                general: {
                    min: v('job_min_age'),
                    max: v('job_max_age')
                },
            };
        }

        function resolveLimit(limits) {
            const noc = document.getElementById('noc_employee')?.value;
            const disabled = document.getElementById('physical_disability')?.value;
            const gender = document.getElementById('gender')?.value;
            if (noc === 'yes') return null;
            if (disabled === 'yes' && (limits.disabled.min || limits.disabled.max)) return limits.disabled;
            if (gender === 'Female' && (limits.female.min || limits.female.max)) return limits.female;
            if ((gender === 'Male' || gender === 'Other') && (limits.male.min || limits.male.max)) return limits.male;
            if (limits.general.min || limits.general.max) return limits.general;
            return null;
        }

        function calcPreciseAge() {
            const birthVal = document.getElementById('birth_date_ad')?.value;
            if (!birthVal) return null;
            const birth = new Date(birthVal);
            if (isNaN(birth.getTime())) return null;
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            birth.setHours(0, 0, 0, 0);
            let age = today.getFullYear() - birth.getFullYear();
            const monthDiff = today.getMonth() - birth.getMonth();
            const dayDiff = today.getDate() - birth.getDate();
            if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) age--;
            return age;
        }

        function getOrCreateBanner() {
            let banner = document.getElementById('age-limit-banner');
            if (!banner) {
                banner = document.createElement('div');
                banner.id = 'age-limit-banner';
                banner.style.cssText = 'margin-top:6px;font-size:.875rem;padding:8px 12px;border-radius:6px;display:none;';
                const ageField = document.getElementById('age');
                if (ageField) ageField.parentNode.appendChild(banner);
            }
            return banner;
        }

        function showBanner(type, msg) {
            const banner = getOrCreateBanner();
            const styles = {
                error: {
                    bg: '#fde8e8',
                    border: '#dc3545',
                    color: '#721c24'
                },
                success: {
                    bg: '#d4edda',
                    border: '#28a745',
                    color: '#155724'
                },
                info: {
                    bg: '#e8f4fd',
                    border: '#0c63e4',
                    color: '#084298'
                },
            };
            const s = styles[type] || styles.info;
            banner.style.background = s.bg;
            banner.style.border = `1px solid ${s.border}`;
            banner.style.color = s.color;
            banner.textContent = msg;
            banner.style.display = 'block';
        }

        function hideBanner() {
            const b = document.getElementById('age-limit-banner');
            if (b) b.style.display = 'none';
        }

        function validateAge() {
            const ageInput = document.getElementById('age');
            const numericFld = document.getElementById('age_numeric');
            const limits = getLimits();
            const limit = resolveLimit(limits);
            const noc = document.getElementById('noc_employee')?.value;
            const gender = document.getElementById('gender')?.value;
            const disabled = document.getElementById('physical_disability')?.value;

            if (ageInput) ageInput.classList.remove('is-invalid', 'is-valid');

            if (noc === 'yes') {
                showBanner('info', 'NOC employees have no age restriction for this vacancy.');
                if (ageInput) ageInput.classList.add('is-valid');
                return;
            }

            const age = calcPreciseAge();

            if (age === null) {
                if (limit) {
                    const parts = [];
                    if (limit.min) parts.push(`Minimum: ${limit.min} yrs`);
                    if (limit.max) parts.push(`Maximum: ${limit.max} yrs`);
                    showBanner('info', `📋 Age limit for your category — ${parts.join('  |  ')}`);
                } else {
                    hideBanner();
                }
                return;
            }

            if (numericFld) numericFld.value = age;

            if (!limit) {
                hideBanner();
                if (ageInput) ageInput.classList.add('is-valid');
                return;
            }

            const tooYoung = limit.min && age <= limit.min;
            const tooOld = limit.max && age >= limit.max;

            if (tooYoung || tooOld) {
                if (ageInput) ageInput.classList.add('is-invalid');
                const label = disabled === 'yes' ? 'Disabled' :
                    gender === 'Female' ? 'Female' :
                    (gender === 'Male' || gender === 'Other') ? 'Male/Other' :
                    'this category';
                const msg = tooYoung ?
                    `Your age (${age}) is below the minimum of ${limit.min} years for ${label} applicants.` :
                    `Your age (${age}) exceeds the maximum of ${limit.max} years for ${label} applicants.`;
                showBanner('error', msg);
            } else {
                if (ageInput) ageInput.classList.add('is-valid');
                const parts = [];
                if (limit.min) parts.push(`Min: ${limit.min}`);
                if (limit.max) parts.push(`Max: ${limit.max}`);
                showBanner('success', `Age ${age} is within the allowed range (${parts.join(' – ')}) for your category.`);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            ['gender', 'noc_employee', 'physical_disability', 'birth_date_ad'].forEach(function(id) {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('change', validateAge);
                    el.addEventListener('input', validateAge);
                }
            });

            validateAge();

            document.querySelectorAll('.next-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    const activeStep = document.querySelector('.step:not(.d-none)');
                    if (!activeStep || activeStep.id !== 'step1') return;
                    if (document.getElementById('noc_employee')?.value === 'yes') return;
                    const ageInput = document.getElementById('age');
                    if (ageInput && ageInput.classList.contains('is-invalid')) {
                        e.stopImmediatePropagation();
                        validateAge();
                    }
                }, true);
            });
        });

    })();
</script>
@endsection
