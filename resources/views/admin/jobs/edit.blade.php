@extends('layouts.dashboard')

@section('title', 'Edit Vacancy')

@section('portal-name', 'Admin Portal')
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()->name)
@section('user-role', 'System Administrator')
@section('user-initial', strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)))
@section('logout-route', route('admin.logout'))

@push('styles')
<style>
/* Category mutual exclusivity lock */
.cat-locked {
    opacity: 0.45;
    cursor: not-allowed;
}
.cat-locked * {
    pointer-events: none;
}
</style>
@endpush

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('custom-styles')
    <style>
        .page-header {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            border-radius: 12px;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .govt-badge {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label .required {
            color: #dc2626;
        }

        .form-label .nepali-text {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            margin-left: auto;
        }

        .form-control:focus,
        .form-select:focus,
        .form-control:focus-visible {
            border-color: #dc2626;
            box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.15);
            outline: none;
        }

        .form-select {
            cursor: pointer;
        }

        .btn-action {
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .info-alert {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-left: 4px solid #3b82f6;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .preview-card {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 1.5rem;
            position: sticky;
            top: 20px;
        }

        .preview-table {
            width: 100%;
            font-size: 0.875rem;
            border-collapse: separate;
            border-spacing: 0;
        }

        .preview-table tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .preview-table tr:last-child {
            border-bottom: none;
        }

        .preview-table th {
            padding: 0.75rem 0.5rem;
            text-align: left;
            font-weight: 600;
            color: #6b7280;
            width: 45%;
        }

        .preview-table td {
            padding: 0.75rem 0.5rem;
            color: #1f2937;
            font-weight: 500;
        }

        .section-divider {
            border-top: 2px solid #e5e7eb;
            margin: 2rem 0;
            position: relative;
        }

        .section-divider::after {
            content: '';
            position: absolute;
            top: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: #dc2626;
        }

        .form-text {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .invalid-feedback {
            display: block;
            font-size: 0.875rem;
            color: #dc2626;
            margin-top: 0.25rem;
        }

        /* Radio button styling */
        .form-check-input:checked {
            background-color: #dc2626;
            border-color: #dc2626;
        }

        .form-check-inline {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-inline:hover {
            background-color: #fef2f2 !important;
            border-color: #dc2626 !important;
        }

        .form-check-input:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.15);
        }

        /* Inclusive sub-category animation */
        .inclusive-subcategory {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, margin 0.3s ease, opacity 0.3s ease;
            opacity: 0;
        }

        .inclusive-subcategory.show {
            max-height: 200px;
            margin-top: 1rem;
            opacity: 1;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        /* Scroll to Top Button */
        .stp {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
            opacity: 0;
            transition: opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1),
                        transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        box-shadow 0.3s ease;
            z-index: 9999;
            will-change: transform, opacity;
        }

        .stp:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.6);
            background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%);
        }

        .stp:active {
            transform: translateY(-2px);
        }

        /* Force smooth rendering */
        * {
            scroll-behavior: auto !important;
        }

        html, body {
            scroll-behavior: auto !important;
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="govt-badge">
                    <!-- <i class="bi bi-building-fill"></i> -->
                    <span>नेपाल सरकार | Government of Nepal</span>
                </div>
                <h3 class="fw-bold mb-2">
                    <!-- <i class="bi bi-pencil-square me-2"></i> -->
                    Edit Vacancy
                </h3>
                <p class="mb-0 opacity-90">विज्ञापन सम्पादन गर्नुहोस्</p>
            </div>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-light btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <!-- Information Alert -->
    <div class="info-alert">
        <div class="d-flex align-items-start gap-3">
            <!-- <i class="bi bi-info-circle-fill text-primary fs-4"></i> -->
            <div>
                <strong>Editing Vacancy:</strong> Advertisement No. <span
                    class="fw-bold text-primary">{{ $job->advertisement_no }}</span>
                <br><small class="text-muted">Make necessary changes and update the vacancy. Fields marked with <span
                        class="text-danger fw-bold">*</span> are mandatory.</small>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.jobs.update', $job->id) }}" id="vacancyForm">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Main Form Column -->
            <div class="col-lg-8">
                <div class="form-card">
                    <h5 class="fw-bold mb-4 text-danger">
                        <!-- <i class="bi bi-pencil-square me-2"></i> -->
                        Vacancy Details
                    </h5>

                    <!-- Notice Number -->
                    <div class="mb-4">
                        <label for="notice_no" class="form-label">
                            <span>Notice No. <span class="required">*</span></span>
                            <span class="nepali-text">सूचना नं.</span>
                        </label>
                        <input type="text" class="form-control form-control-lg @error('notice_no') is-invalid @enderror"
                            id="notice_no" name="notice_no"
                            value="{{ old('notice_no', $job->notice_no) }}"
                            placeholder="e.g., 36/2082-83">
                        @error('notice_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <!-- <small class="form-text">
                            <i class="bi bi-lightbulb me-1"></i>Internal notice reference number (must be unique)
                        </small> -->
                    </div>

                    <!-- Advertisement Number -->
                    <div class="mb-4">
                        <label for="advertisement_no" class="form-label">
                            <span>Advertisement No. <span class="required">*</span></span>
                            <span class="nepali-text">विज्ञापन नं.</span>
                        </label>
                        <input type="text"
                            class="form-control form-control-lg @error('advertisement_no') is-invalid @enderror"
                            id="advertisement_no" name="advertisement_no"
                            value="{{ old('advertisement_no', $job->advertisement_no) }}" placeholder="e.g., 01/2081-82"
                            required>
                        @error('advertisement_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <!-- <small class="form-text">
                            <i class="bi bi-lightbulb me-1"></i>Format: Number/Fiscal Year (e.g., 01/2081-82)
                        </small> -->
                    </div>

                    <div class="section-divider"></div>

                    <!-- Position/Level (Dropdown) -->
                    <div class="mb-4">
                        <label for="position_level" class="form-label">
                            <span>Position / Level <span class="required">*</span></span>
                            <span class="nepali-text">पद / तह</span>
                        </label>
                        <select class="form-select form-select-lg @error('position_level') is-invalid @enderror"
                            id="position_level" name="position_level" required>
                            <option value="">-- Select Position/Level --</option>
                            <optgroup label="Officer Level (अधिकृत तह)">
                                <option value="Officer Level - 10th (अधिकृत तह - १०)" {{ old('position_level', $job->position_level) == 'Officer Level - 10th (अधिकृत तह - १०)' ? 'selected' : '' }}>
                                    Officer Level - 10th (अधिकृत तह - १०)</option>
                                <option value="Officer Level - 9th (अधिकृत तह - ९)" {{ old('position_level', $job->position_level) == 'Officer Level - 9th (अधिकृत तह - ९)' ? 'selected' : '' }}>
                                    Officer Level - 9th (अधिकृत तह - ९)</option>
                                <option value="Officer Level - 8th (अधिकृत तह - ८)" {{ old('position_level', $job->position_level) == 'Officer Level - 8th (अधिकृत तह - ८)' ? 'selected' : '' }}>
                                    Officer Level - 8th (अधिकृत तह - ८)</option>
                                <option value="Officer Level - 7th (अधिकृत तह - ७)" {{ old('position_level', $job->position_level) == 'Officer Level - 7th (अधिकृत तह - ७)' ? 'selected' : '' }}>
                                    Officer Level - 7th (अधिकृत तह - ७)</option>
                                <option value="Officer Level - 6th (अधिकृत तह - ६)" {{ old('position_level', $job->position_level) == 'Officer Level - 6th (अधिकृत तह - ६)' ? 'selected' : '' }}>
                                    Officer Level - 6th (अधिकृत तह - ६)</option>
                            </optgroup>
                            <optgroup label="Assistant Level (सहायक तह)">
                                <option value="Officer Level - 5th (अधिकृत तह - ५)" {{ old('position_level', $job->position_level) == 'Officer Level - 5th (अधिकृत तह - ५)' ? 'selected' : '' }}>
                                    Officer Level - 5th (बरिष्ठ सहायक तह - ५)</option>
                                <option value="Assistant Level - 4th (सहायक तह - ४)" {{ old('position_level', $job->position_level) == 'Assistant Level - 4th (सहायक तह - ४)' ? 'selected' : '' }}>
                                    Assistant Level - 4th (सहायक तह - ४)</option>
                            </optgroup>
                            <optgroup label="Technician Level (सहयोगी)">
                                <option value="Technician Level (सहयोगी)" {{ old('position_level', $job->position_level) == 'Technician Level (सहयोगी)' ? 'selected' : '' }}>Technician
                                    (टेक्निशियन)</option>
                            </optgroup>
                        </select>
                        @error('position_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <!-- <small class="form-text">
                            <i class="bi bi-lightbulb me-1"></i>Select the government position level from the dropdown
                        </small> -->
                    </div>

                    <!-- Service/Group -->
                    <div class="mb-4">
                        <label for="service_group" class="form-label">
                            <span>Service / Group <span class="required">*</span></span>
                            <span class="nepali-text">सेवा / समूह</span>
                        </label>
                        <input type="text"
                            class="form-control form-control-lg @error('service_group') is-invalid @enderror"
                            id="service_group" name="service_group"
                            value="{{ old('service_group', $job->service_group) }}"
                            placeholder="e.g. Non-Technical / Administration"
                            required>
                        @error('service_group')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="section-divider"></div>

                    @php
                        // Backward compatibility: jobs created with old system have category='open'/'internal'
                        // but has_open/has_internal are false. Fall back to old category field.
                        $noNewFlags = !$job->has_open && !$job->has_internal;
                        $effectiveHasOpen = $job->has_open || ($noNewFlags && in_array($job->category, ['open', 'inclusive']));
                        $effectiveHasInternal = $job->has_internal || ($noNewFlags && $job->category === 'internal');
                        $effectiveIsAppraisal = $job->category === 'internal_appraisal';

                        // Decode inclusive_type: stored as JSON array (new) or plain string (old)
                        $storedInclusiveTypes = [];
                        if (!empty($job->inclusive_type)) {
                            $decoded = json_decode($job->inclusive_type, true);
                            $storedInclusiveTypes = is_array($decoded) ? $decoded : [$job->inclusive_type];
                        }
                        $effectiveHasInclusive = $job->has_inclusive
                            || ($noNewFlags && $job->category === 'inclusive')
                            || count($storedInclusiveTypes) > 0;
                    @endphp

                    <!-- Category Selection - Multi-Category System -->
                    <div class="mb-4">
                        <label class="form-label">
                            <span>Category / Type <span class="required">*</span></span>
                            <span class="nepali-text">श्रेणी / प्रकार</span>
                        </label>
                        <!-- <small class="d-block text-muted mb-3">
                            <i class="bi bi-info-circle me-1"></i>Select one or more categories for this vacancy. Candidates will choose which category to apply under.
                        </small> -->

                        @error('categories')
                            <div class="alert alert-danger mb-3">{{ $message }}</div>
                        @enderror

                        <div class="border rounded p-3 bg-light">
                            <!-- Level 1: Open -->
                            <div class="mb-3">
                                <div class="form-check" id="fc_has_open">
                                    <input class="form-check-input category-checkbox" type="checkbox"
                                           id="has_open" value="1"
                                           {{ old('has_open', $effectiveHasOpen) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="has_open">
                                        Open (खुल्ला)
                                    </label>
                                </div>

                                <!-- Level 2: Inclusive Types Toggle (shown when Open is checked) -->
                                <div id="inclusiveTypesToggle" style="display: {{ old('has_open', $effectiveHasOpen) && !old('is_internal_appraisal', $effectiveIsAppraisal) ? 'block' : 'none' }}; margin-left: 30px; margin-top: 10px;">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               id="has_inclusive_toggle" value="1"
                                               {{ old('has_inclusive', $effectiveHasInclusive) || (old('inclusive_types') && count(old('inclusive_types')) > 0) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="has_inclusive_toggle">
                                            Inclusive Types:
                                        </label>
                                    </div>
                                </div>

                                <!-- Level 3: Individual Inclusive Types (shown when Inclusive Types is checked) -->
                                <div id="inclusiveTypesSection" style="display: {{ old('has_inclusive', $effectiveHasInclusive) || (old('inclusive_types') && count(old('inclusive_types')) > 0) ? 'block' : 'none' }}; margin-left: 60px; margin-top: 10px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input inclusive-type-checkbox" type="checkbox"
                                                       id="incl_women" name="inclusive_types[]" value="Women"
                                                       {{ (is_array(old('inclusive_types')) && in_array('Women', old('inclusive_types'))) || (!old('inclusive_types') && in_array('Women', $storedInclusiveTypes)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="incl_women">
                                                    Women (महिला)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input inclusive-type-checkbox" type="checkbox"
                                                       id="incl_aj" name="inclusive_types[]" value="A.J"
                                                       {{ (is_array(old('inclusive_types')) && in_array('A.J', old('inclusive_types'))) || (!old('inclusive_types') && in_array('A.J', $storedInclusiveTypes)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="incl_aj">
                                                    A.J (आ.ज / आदिवासी जनजाति)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input inclusive-type-checkbox" type="checkbox"
                                                       id="incl_madhesi" name="inclusive_types[]" value="Madhesi"
                                                       {{ (is_array(old('inclusive_types')) && in_array('Madhesi', old('inclusive_types'))) || (!old('inclusive_types') && in_array('Madhesi', $storedInclusiveTypes)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="incl_madhesi">
                                                    Madhesi (मधेसी)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input inclusive-type-checkbox" type="checkbox"
                                                       id="incl_janajati" name="inclusive_types[]" value="Janajati"
                                                       {{ (is_array(old('inclusive_types')) && in_array('Janajati', old('inclusive_types'))) || (!old('inclusive_types') && in_array('Janajati', $storedInclusiveTypes)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="incl_janajati">
                                                    Janajati (जनजाति)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input inclusive-type-checkbox" type="checkbox"
                                                       id="incl_apanga" name="inclusive_types[]" value="Apanga"
                                                       {{ (is_array(old('inclusive_types')) && in_array('Apanga', old('inclusive_types'))) || (!old('inclusive_types') && in_array('Apanga', $storedInclusiveTypes)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="incl_apanga">
                                                    Apanga (अपाङ्ग)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input inclusive-type-checkbox" type="checkbox"
                                                       id="incl_dalit" name="inclusive_types[]" value="Dalit"
                                                       {{ (is_array(old('inclusive_types')) && in_array('Dalit', old('inclusive_types'))) || (!old('inclusive_types') && in_array('Dalit', $storedInclusiveTypes)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="incl_dalit">
                                                    Dalit (दलित)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input inclusive-type-checkbox" type="checkbox"
                                                       id="incl_pichadiyeko" name="inclusive_types[]" value="Pichadiyeko Chetra"
                                                       {{ (is_array(old('inclusive_types')) && in_array('Pichadiyeko Chetra', old('inclusive_types'))) || (!old('inclusive_types') && in_array('Pichadiyeko Chetra', $storedInclusiveTypes)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="incl_pichadiyeko">
                                                    Pichadiyeko Chetra (पिचडिएको क्षेत्र)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Level 1: Internal -->
                            <div class="mb-3">
                                <div class="form-check" id="fc_has_internal">
                                    <input class="form-check-input category-checkbox" type="checkbox"
                                           id="has_internal" value="1"
                                           {{ old('has_internal', $effectiveHasInternal) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="has_internal">
                                        Internal (आन्तरिक परीक्षा)
                                    </label>
                                    <small class="d-block text-muted ms-4">For NOC employees only</small>
                                </div>

                                <!-- Level 2: Internal Open (shown when Internal is checked) -->
                                <div id="internalOpenToggle" style="display: {{ old('has_internal', $effectiveHasInternal) && !old('is_internal_appraisal', $effectiveIsAppraisal) ? 'block' : 'none' }}; margin-left: 30px; margin-top: 10px;">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               id="has_internal_open" name="has_internal_open" value="1"
                                               {{ old('has_internal_open', $job->has_internal_open) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_internal_open">
                                            Internal Open (All NOC Staff)
                                        </label>
                                    </div>
                                </div>

                                <!-- Level 2: Internal Inclusive Types Toggle (shown when Internal is checked) -->
                                <div id="internalInclusiveToggle" style="display: {{ old('has_internal', $effectiveHasInternal) && !old('is_internal_appraisal', $effectiveIsAppraisal) ? 'block' : 'none' }}; margin-left: 30px; margin-top: 10px;">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               id="has_internal_inclusive_toggle" value="1"
                                               {{ old('has_internal_inclusive', $job->has_internal_inclusive) || (old('internal_inclusive_types') && is_array(old('internal_inclusive_types')) && count(old('internal_inclusive_types')) > 0) || (!old('internal_inclusive_types') && $job->internal_inclusive_types && is_array($job->internal_inclusive_types) && count($job->internal_inclusive_types) > 0) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="has_internal_inclusive_toggle">
                                            Internal Inclusive Types:
                                        </label>
                                    </div>
                                </div>

                                <!-- Level 3: Individual Internal Inclusive Types (shown when Internal Inclusive is checked) -->
                                <div id="internalInclusiveTypesSection" style="display: {{ old('has_internal_inclusive', $job->has_internal_inclusive) || (old('internal_inclusive_types') && is_array(old('internal_inclusive_types')) && count(old('internal_inclusive_types')) > 0) || (!old('internal_inclusive_types') && $job->internal_inclusive_types && is_array($job->internal_inclusive_types) && count($job->internal_inclusive_types) > 0) ? 'block' : 'none' }}; margin-left: 60px; margin-top: 10px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input internal-inclusive-type-checkbox" type="checkbox"
                                                       id="int_incl_women" name="internal_inclusive_types[]" value="Women"
                                                       {{ (is_array(old('internal_inclusive_types')) && in_array('Women', old('internal_inclusive_types'))) || (!old('internal_inclusive_types') && $job->internal_inclusive_types && is_array($job->internal_inclusive_types) && in_array('Women', $job->internal_inclusive_types)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="int_incl_women">
                                                    Women (महिला)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input internal-inclusive-type-checkbox" type="checkbox"
                                                       id="int_incl_aj" name="internal_inclusive_types[]" value="A.J"
                                                       {{ (is_array(old('internal_inclusive_types')) && in_array('A.J', old('internal_inclusive_types'))) || (!old('internal_inclusive_types') && $job->internal_inclusive_types && is_array($job->internal_inclusive_types) && in_array('A.J', $job->internal_inclusive_types)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="int_incl_aj">
                                                    A.J (आ.ज / आदिवासी जनजाति)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input internal-inclusive-type-checkbox" type="checkbox"
                                                       id="int_incl_madhesi" name="internal_inclusive_types[]" value="Madhesi"
                                                       {{ (is_array(old('internal_inclusive_types')) && in_array('Madhesi', old('internal_inclusive_types'))) || (!old('internal_inclusive_types') && $job->internal_inclusive_types && is_array($job->internal_inclusive_types) && in_array('Madhesi', $job->internal_inclusive_types)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="int_incl_madhesi">
                                                    Madhesi (मधेसी)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input internal-inclusive-type-checkbox" type="checkbox"
                                                       id="int_incl_janajati" name="internal_inclusive_types[]" value="Janajati"
                                                       {{ (is_array(old('internal_inclusive_types')) && in_array('Janajati', old('internal_inclusive_types'))) || (!old('internal_inclusive_types') && $job->internal_inclusive_types && is_array($job->internal_inclusive_types) && in_array('Janajati', $job->internal_inclusive_types)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="int_incl_janajati">
                                                    Janajati (जनजाति)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input internal-inclusive-type-checkbox" type="checkbox"
                                                       id="int_incl_apanga" name="internal_inclusive_types[]" value="Apanga"
                                                       {{ (is_array(old('internal_inclusive_types')) && in_array('Apanga', old('internal_inclusive_types'))) || (!old('internal_inclusive_types') && $job->internal_inclusive_types && is_array($job->internal_inclusive_types) && in_array('Apanga', $job->internal_inclusive_types)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="int_incl_apanga">
                                                    Apanga (अपाङ्ग)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input internal-inclusive-type-checkbox" type="checkbox"
                                                       id="int_incl_dalit" name="internal_inclusive_types[]" value="Dalit"
                                                       {{ (is_array(old('internal_inclusive_types')) && in_array('Dalit', old('internal_inclusive_types'))) || (!old('internal_inclusive_types') && $job->internal_inclusive_types && is_array($job->internal_inclusive_types) && in_array('Dalit', $job->internal_inclusive_types)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="int_incl_dalit">
                                                    Dalit (दलित)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input internal-inclusive-type-checkbox" type="checkbox"
                                                       id="int_incl_pichadiyeko" name="internal_inclusive_types[]" value="Pichadiyeko Chetra"
                                                       {{ (is_array(old('internal_inclusive_types')) && in_array('Pichadiyeko Chetra', old('internal_inclusive_types'))) || (!old('internal_inclusive_types') && $job->internal_inclusive_types && is_array($job->internal_inclusive_types) && in_array('Pichadiyeko Chetra', $job->internal_inclusive_types)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="int_incl_pichadiyeko">
                                                    Pichadiyeko Chetra (पिचडिएको क्षेत्र)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden field for has_internal_inclusive -->
                                <input type="hidden" name="has_internal_inclusive" id="has_internal_inclusive" value="{{ old('has_internal_inclusive', $job->has_internal_inclusive ? '1' : '0') }}">
                            </div>

                            <!-- Internal Appraisal (Mutually Exclusive) -->
                            <div>
                                <div class="form-check" id="fc_is_internal_appraisal">
                                    <input class="form-check-input" type="checkbox"
                                           id="is_internal_appraisal" name="is_internal_appraisal" value="1"
                                           {{ old('is_internal_appraisal', $job->category == 'internal_appraisal') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_internal_appraisal">
                                        Internal Appraisal (आन्तरिक बढुवा)
                                    </label>
                                    <small class="d-block text-muted ms-4">
                                        <!-- <i class="bi bi-info-circle me-1"></i> -->
                                        NOC Employees Internal Appraisal
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields for backward compatibility -->
                        <input type="hidden" name="category" id="hidden_category" value="{{ old('category', $job->category) }}">
                        <input type="hidden" name="has_open" id="hidden_has_open" value="{{ old('has_open', $effectiveHasOpen ? '1' : '0') }}">
                        <input type="hidden" name="has_inclusive" id="has_inclusive" value="{{ old('has_inclusive', $effectiveHasInclusive ? '1' : '0') }}">
                        <input type="hidden" name="has_internal" id="hidden_has_internal" value="{{ old('has_internal', $effectiveHasInternal ? '1' : '0') }}">
                        <input type="hidden" name="has_internal_inclusive" id="has_internal_inclusive" value="{{ old('has_internal_inclusive', $job->has_internal_inclusive ? '1' : '0') }}">
                        <input type="hidden" name="inclusive_type" id="hidden_inclusive_type" value="{{ old('inclusive_type', $job->inclusive_type) }}">
                        <input type="hidden" name="internal_type" id="hidden_internal_type" value="{{ old('internal_type', $job->internal_type) }}">
                    </div>

                    {{-- =====================================================
                         Category Mutual Exclusivity + Section Show/Hide
                         Inline script — runs immediately after checkboxes are
                         parsed. Completely isolated from all other scripts.
                         Same logic as create page initializeCategoryCheckboxes.
                         ===================================================== --}}
                    <script>
                    (function () {
                        // Main category checkboxes
                        var cbOpen      = document.getElementById('has_open');
                        var cbInternal  = document.getElementById('has_internal');
                        var cbAppraisal = document.getElementById('is_internal_appraisal');

                        // Wrapper divs for visual lock
                        var fcOpen      = document.getElementById('fc_has_open');
                        var fcInternal  = document.getElementById('fc_has_internal');
                        var fcAppraisal = document.getElementById('fc_is_internal_appraisal');

                        // Open sub-sections
                        var inclusiveTypesToggle  = document.getElementById('inclusiveTypesToggle');
                        var inclusiveTypesSection  = document.getElementById('inclusiveTypesSection');
                        var cbInclusiveToggle      = document.getElementById('has_inclusive_toggle');
                        var inclusiveTypeCbs       = document.querySelectorAll('.inclusive-type-checkbox');

                        // Internal sub-sections
                        var internalOpenToggle           = document.getElementById('internalOpenToggle');
                        var internalInclusiveToggle      = document.getElementById('internalInclusiveToggle');
                        var internalInclusiveTypesSection= document.getElementById('internalInclusiveTypesSection');
                        var cbInternalOpen               = document.getElementById('has_internal_open');
                        var cbInternalInclusiveToggle    = document.getElementById('has_internal_inclusive_toggle');
                        var internalInclusiveCbs         = document.querySelectorAll('.internal-inclusive-type-checkbox');

                        // --- Helpers ---
                        function hideOpenSections() {
                            if (inclusiveTypesToggle) inclusiveTypesToggle.style.display = 'none';
                            if (inclusiveTypesSection) inclusiveTypesSection.style.display = 'none';
                            if (cbInclusiveToggle) cbInclusiveToggle.checked = false;
                            inclusiveTypeCbs.forEach(function(c){ c.checked = false; });
                            updatePreviewInclusiveTypes();
                        }

                        function showOpenSections() {
                            if (inclusiveTypesToggle) inclusiveTypesToggle.style.display = 'block';
                        }

                        function hideInternalSections() {
                            if (internalOpenToggle) internalOpenToggle.style.display = 'none';
                            if (internalInclusiveToggle) internalInclusiveToggle.style.display = 'none';
                            if (internalInclusiveTypesSection) internalInclusiveTypesSection.style.display = 'none';
                            if (cbInternalOpen) cbInternalOpen.checked = false;
                            if (cbInternalInclusiveToggle) cbInternalInclusiveToggle.checked = false;
                            internalInclusiveCbs.forEach(function(c){ c.checked = false; });
                            updatePreviewInternalTypes();
                        }

                        function showInternalSections() {
                            if (internalOpenToggle) internalOpenToggle.style.display = 'block';
                            if (internalInclusiveToggle) internalInclusiveToggle.style.display = 'block';
                        }

                        function showDoubleDastur() {
                            var _ddDate = document.getElementById('doubleDasturDateSection');
                            var _ddFee  = document.getElementById('doubleDasturFeeSection');
                            var _ddFeeInput = document.getElementById('double_dastur_fee');
                            var _pvBs  = document.getElementById('preview-double-dastur-row');
                            var _pvAd  = document.getElementById('preview-double-dastur-ad-row');
                            var _pvFee = document.getElementById('preview-double-dastur-fee-row');
                            if (_ddDate) _ddDate.style.display = 'block';
                            if (_ddFee)  _ddFee.style.display  = 'block';
                            if (_ddFeeInput) _ddFeeInput.removeAttribute('disabled');
                            if (_pvBs)  _pvBs.style.display  = '';
                            if (_pvAd)  _pvAd.style.display   = '';
                            if (_pvFee) _pvFee.style.display  = '';
                        }

                        function hideDoubleDastur() {
                            var _ddDate = document.getElementById('doubleDasturDateSection');
                            var _ddFee  = document.getElementById('doubleDasturFeeSection');
                            var _ddFeeInput = document.getElementById('double_dastur_fee');
                            var _pvBs  = document.getElementById('preview-double-dastur-row');
                            var _pvAd  = document.getElementById('preview-double-dastur-ad-row');
                            var _pvFee = document.getElementById('preview-double-dastur-fee-row');
                            if (_ddDate) _ddDate.style.display = 'none';
                            if (_ddFee)  _ddFee.style.display  = 'none';
                            if (_ddFeeInput) _ddFeeInput.setAttribute('disabled', 'disabled');
                            if (_pvBs)  _pvBs.style.display  = 'none';
                            if (_pvAd)  _pvAd.style.display   = 'none';
                            if (_pvFee) _pvFee.style.display  = 'none';
                        }

                        function setLock(activeCb) {
                            var all = [cbOpen, cbInternal, cbAppraisal];
                            var fcs = [fcOpen, fcInternal, fcAppraisal];
                            all.forEach(function(cb, i) {
                                if (!cb) return;
                                var lock = activeCb !== null && cb !== activeCb;
                                cb.disabled = lock;
                                if (fcs[i]) {
                                    fcs[i].style.opacity = lock ? '0.45' : '';
                                    fcs[i].style.cursor  = lock ? 'not-allowed' : '';
                                }
                            });
                        }

                        // --- Live preview: Category badge ---
                        function updatePreviewCategory() {
                            var el = document.getElementById('preview-category');
                            if (!el) return;
                            if (cbAppraisal && cbAppraisal.checked) {
                                el.innerHTML = '<span class="badge bg-danger">आन्तरिक बढुवा (Internal Appraisal)</span>';
                            } else if (cbInternal && cbInternal.checked) {
                                el.innerHTML = '<span class="badge bg-warning text-dark">आन्तरिक (Internal)</span>';
                            } else if (cbOpen && cbOpen.checked) {
                                el.innerHTML = '<span class="badge bg-success">खुल्ला (Open)</span>';
                            } else {
                                el.innerHTML = '-';
                            }
                        }

                        // --- Live preview: Open → Inclusive Types row ---
                        function updatePreviewInclusiveTypes() {
                            var row  = document.getElementById('preview-inclusive-row');
                            var cell = document.getElementById('preview-inclusive-type');
                            if (!row || !cell) return;
                            var isOpenChecked      = cbOpen && cbOpen.checked;
                            var isToggleChecked    = cbInclusiveToggle && cbInclusiveToggle.checked;
                            var checked = Array.prototype.filter.call(inclusiveTypeCbs, function(c){ return c.checked; });
                            var labels  = checked.map(function(c){ return c.value; });
                            if (isOpenChecked && isToggleChecked && labels.length > 0) {
                                cell.textContent    = labels.join(', ');
                                row.style.display   = '';
                            } else {
                                row.style.display   = 'none';
                            }
                        }

                        // --- Live preview: Internal → Internal Type row ---
                        function updatePreviewInternalTypes() {
                            var row  = document.getElementById('preview-internal-subcategory-row');
                            var cell = document.getElementById('preview-internal-subcategory');
                            if (!row || !cell) return;
                            if (!(cbInternal && cbInternal.checked)) {
                                row.style.display = 'none';
                                return;
                            }
                            var parts = [];
                            if (cbInternalOpen && cbInternalOpen.checked) {
                                parts.push('Internal Open (All NOC Staff)');
                            }
                            if (cbInternalInclusiveToggle && cbInternalInclusiveToggle.checked) {
                                var intChecked = Array.prototype.filter.call(internalInclusiveCbs, function(c){ return c.checked; });
                                var intLabels  = intChecked.map(function(c){ return c.value; });
                                if (intLabels.length > 0) {
                                    parts.push('Internal Inclusive (' + intLabels.join(', ') + ')');
                                }
                            }
                            if (parts.length > 0) {
                                cell.textContent  = parts.join(' | ');
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }

                        // --- Main handler ---
                        function handleChange(cb) {
                            if (cb.checked) {
                                if (cb === cbOpen) {
                                    if (cbInternal)  cbInternal.checked  = false;
                                    if (cbAppraisal) cbAppraisal.checked = false;
                                    hideInternalSections();
                                    showOpenSections();
                                    showDoubleDastur();
                                } else if (cb === cbInternal) {
                                    if (cbOpen)      cbOpen.checked      = false;
                                    if (cbAppraisal) cbAppraisal.checked = false;
                                    hideOpenSections();
                                    showInternalSections();
                                    hideDoubleDastur();
                                } else if (cb === cbAppraisal) {
                                    if (cbOpen)     cbOpen.checked     = false;
                                    if (cbInternal) cbInternal.checked = false;
                                    hideOpenSections();
                                    hideInternalSections();
                                    hideDoubleDastur();
                                }
                                setLock(cb);
                            } else {
                                if (cb === cbOpen)     hideOpenSections();
                                if (cb === cbInternal) hideInternalSections();
                                showDoubleDastur();
                                setLock(null);
                            }
                            updatePreviewCategory();
                            syncHiddenCategoryFields();
                        }

                        // Sync has_open / has_internal / category hidden fields immediately
                        // (safety net — runs even before IIFE's updateHiddenFields is available)
                        function syncHiddenCategoryFields() {
                            var isOpen      = cbOpen      && cbOpen.checked;
                            var isInternal  = cbInternal  && cbInternal.checked;
                            var isAppraisal = cbAppraisal && cbAppraisal.checked;

                            var elOpen = document.getElementById('hidden_has_open');
                            if (elOpen) elOpen.value = isOpen ? '1' : '0';

                            var elInternal = document.getElementById('hidden_has_internal');
                            if (elInternal) elInternal.value = isInternal ? '1' : '0';

                            var elCat = document.getElementById('hidden_category');
                            if (elCat) {
                                elCat.value = isAppraisal ? 'internal_appraisal'
                                            : isInternal  ? 'internal'
                                            : 'open';
                            }
                        }

                        // Sub-toggle: Open → Inclusive Types list
                        if (cbInclusiveToggle) {
                            cbInclusiveToggle.addEventListener('change', function() {
                                if (inclusiveTypesSection) {
                                    inclusiveTypesSection.style.display = this.checked ? 'block' : 'none';
                                }
                                if (!this.checked) {
                                    inclusiveTypeCbs.forEach(function(c){ c.checked = false; });
                                }
                                updatePreviewInclusiveTypes();
                            });
                        }

                        // Individual inclusive type checkboxes
                        inclusiveTypeCbs.forEach(function(c) {
                            c.addEventListener('change', function() {
                                updatePreviewInclusiveTypes();
                            });
                        });

                        // Sub-toggle: Internal → Internal Inclusive Types list
                        if (cbInternalInclusiveToggle) {
                            cbInternalInclusiveToggle.addEventListener('change', function() {
                                if (internalInclusiveTypesSection) {
                                    internalInclusiveTypesSection.style.display = this.checked ? 'block' : 'none';
                                }
                                if (!this.checked) {
                                    internalInclusiveCbs.forEach(function(c){ c.checked = false; });
                                }
                                updatePreviewInternalTypes();
                            });
                        }

                        // Internal Open checkbox
                        if (cbInternalOpen) {
                            cbInternalOpen.addEventListener('change', function() {
                                updatePreviewInternalTypes();
                            });
                        }

                        // Individual internal inclusive type checkboxes
                        internalInclusiveCbs.forEach(function(c) {
                            c.addEventListener('change', function() {
                                updatePreviewInternalTypes();
                            });
                        });

                        // Attach main category listeners
                        [cbOpen, cbInternal, cbAppraisal].forEach(function(cb) {
                            if (cb) cb.addEventListener('change', function(){ handleChange(cb); });
                        });

                        // --- Init: lock + section show/hide (checkboxes are already in DOM here) ---
                        if (cbOpen && cbOpen.checked) {
                            setLock(cbOpen);
                            showOpenSections();
                            if (cbInclusiveToggle && cbInclusiveToggle.checked && inclusiveTypesSection) {
                                inclusiveTypesSection.style.display = 'block';
                            }
                        } else if (cbInternal && cbInternal.checked) {
                            setLock(cbInternal);
                            showInternalSections();
                            if (cbInternalInclusiveToggle && cbInternalInclusiveToggle.checked && internalInclusiveTypesSection) {
                                internalInclusiveTypesSection.style.display = 'block';
                            }
                        } else if (cbAppraisal && cbAppraisal.checked) {
                            setLock(cbAppraisal);
                        } else {
                            setLock(null);
                        }

                        // --- Preview rows are further down in the DOM (right column).
                        //     Defer until DOMContentLoaded so those elements exist. ---
                        document.addEventListener('DOMContentLoaded', function () {
                            updatePreviewCategory();
                            updatePreviewInclusiveTypes();
                            updatePreviewInternalTypes();
                        });
                    })();
                    </script>

                    <!-- Demand Post (Number of Posts) -->
                    <div class="mb-4">
                        <label for="number_of_posts" class="form-label">
                            <span>Demand Post (Number) <span class="required">*</span></span>
                            <span class="nepali-text">माग पद संख्या</span>
                        </label>
                        <input type="number"
                            class="form-control form-control-lg @error('number_of_posts') is-invalid @enderror"
                            id="number_of_posts" name="number_of_posts"
                            value="{{ old('number_of_posts', $job->number_of_posts) }}" min="1" max="1000" required>
                        @error('number_of_posts')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="section-divider"></div>

                    <!-- Minimum Educational Qualification -->
                    <div class="mb-4">
                        <label for="minimum_qualification" class="form-label">
                            <span>Minimum Educational Qualification <span class="required">*</span></span>
                            <span class="nepali-text">आवश्यक शिक्षक योग्यता</span>
                        </label>
                        <textarea class="form-control @error('minimum_qualification') is-invalid @enderror"
                            id="minimum_qualification" name="minimum_qualification" rows="5"
                            required>{{ old('minimum_qualification', $job->minimum_qualification) }}</textarea>
                        @error('minimum_qualification')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="section-divider"></div>

                    <!-- Application Deadline - Dual Date Pickers -->
                    <div class="mb-4">
                        <label class="form-label">
                            <span>Application Deadline <span class="required">*</span></span>
                            <span class="nepali-text">आवेदन दिने अन्तिम मिति</span>
                        </label>

                        <div class="row g-3">
                            <!-- Nepali Date (BS) Picker -->
                            <div class="col-md-6">
                                <label for="deadline_bs" class="form-label small fw-bold text-primary">
                                    <!-- <i class="bi bi-calendar3 me-1"></i> -->
                                    Nepali Date (BS) / नेपाली मिति
                                </label>
                                <input type="text"
                                    class="form-control form-control-lg"
                                    id="deadline_bs"
                                    placeholder="YYYY-MM-DD"
                                    autocomplete="off">
                                <input type="hidden" name="deadline_bs" id="deadline_bs_hidden" value="{{ old('deadline_bs', $job->deadline_bs) }}">
                                <small class="form-text text-primary">
                                    <i class="bi bi-info-circle me-1"></i>Click to open Nepali calendar
                                </small>
                            </div>

                            <!-- English Date (AD) - Database Field -->
                            <div class="col-md-6">
                                <label for="deadline_ad" class="form-label small fw-bold">
                                    <!-- <i class="bi bi-calendar-date me-1"></i> -->
                                    English Date (AD) <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control form-control-lg @error('deadline') is-invalid @enderror"
                                    id="deadline_ad"
                                    name="deadline"
                                    placeholder="YYYY-MM-DD"
                                    value="{{ old('deadline', $job->deadline->format('Y-m-d')) }}"
                                    required
                                    readonly>
                                <small class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>Current deadline date
                                </small>
                            </div>
                        </div>

                        @error('deadline')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <!-- <div class="alert alert-info mt-3 mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            <strong>Current Deadline:</strong> {{ $job->deadline->format('Y-m-d') }} - You can update if needed.
                            <br><small>हालको समय सीमा: {{ $job->deadline->format('Y-m-d') }} - आवश्यक भएमा अपडेट गर्न सक्नुहुन्छ।</small>
                        </div> -->
                    </div>

                    <!-- Double Dastur Date - Dual Date Pickers -->
                    <div class="mb-4" id="doubleDasturDateSection" style="display: {{ ($effectiveHasInternal || $effectiveIsAppraisal) ? 'none' : 'block' }};">
                        <label class="form-label">
                            <span>Double Dastur Date</span>
                            <span class="nepali-text">दोहोरो दस्तुर मिति</span>
                        </label>

                        <div class="row g-3">
                            <!-- Nepali Date (BS) Picker -->
                            <div class="col-md-6">
                                <label for="double_dastur_bs" class="form-label small fw-bold text-success">
                                    <!-- <i class="bi bi-calendar3 me-1"></i> -->
                                    Nepali Date (BS) / नेपाली मिति
                                </label>
                                <input type="text"
                                    class="form-control form-control-lg"
                                    id="double_dastur_bs"
                                    placeholder="YYYY-MM-DD"
                                    autocomplete="off">
                                <input type="hidden" name="double_dastur_bs" id="double_dastur_bs_hidden" value="{{ old('double_dastur_bs', $job->double_dastur_bs) }}">
                                <!-- <small class="form-text text-success">
                                    <i class="bi bi-info-circle me-1"></i>Optional extended deadline
                                </small> -->
                            </div>

                            <!-- English Date (AD) - Database Field -->
                            <div class="col-md-6">
                                <label for="double_dastur_ad" class="form-label small fw-bold text-success">
                                    <!-- <i class="bi bi-calendar-date me-1"></i> -->
                                    English Date (AD)
                                </label>
                                <input type="text"
                                    class="form-control form-control-lg @error('double_dastur_date') is-invalid @enderror"
                                    id="double_dastur_ad"
                                    name="double_dastur_date"
                                    placeholder="YYYY-MM-DD"
                                    value="{{ old('double_dastur_date', $job->double_dastur_date ? \Carbon\Carbon::parse($job->double_dastur_date)->format('Y-m-d') : '') }}"
                                    readonly>
                                <small class="form-text text-success">
                                    <!-- <i class="bi bi-info-circle me-1"></i>Optional extended deadline -->
                                </small>
                            </div>
                        </div>

                        @error('double_dastur_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <!-- <div class="alert alert-success mt-3 mb-0">
                            <i class="bi bi-calendar-plus me-2"></i>
                            <strong>Extended Period:</strong> Double Dastur allows additional time after the original deadline.
                            <br><small>विस्तारित अवधि: दोहोरो दस्तुरले मूल समय सीमा पछि थप समय अनुमति दिन्छ।</small>
                        </div> -->
                    </div>

                    <!-- Application Fee & Double Dastur Fee Row -->
                    <div class="row mb-4">
                        <!-- Application Fee -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <span>Application Fee<span class="text-danger">*</span></span>
                                <span class="nepali-text">आवेदन शुल्क</span>
                            </label>
                            <input type="number"
                                   class="form-control form-control-lg @error('application_fee') is-invalid @enderror"
                                   id="application_fee"
                                   name="application_fee"
                                   value="{{ old('application_fee', $job->application_fee ? rtrim(rtrim(sprintf('%.2f', $job->application_fee), '0'), '.') : '') }}"
                                   placeholder="Enter Application Fee"
                                   min="0"
                                   step="0.01"
                                   required>
                            @error('application_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <!-- <div class="alert alert-info mt-3 mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Note:</strong> Enter the application fee amount in Nepali Rupees (required field).
                                <br><small>नोट: नेपाली रुपैयाँमा आवेदन शुल्क रकम प्रविष्ट गर्नुहोस् (अनिवार्य)।</small>
                            </div> -->
                        </div>

                        <!-- Double Dastur Fee -->
                        <div class="col-md-6" id="doubleDasturFeeSection" style="display: {{ ($effectiveHasInternal || $effectiveIsAppraisal) ? 'none' : 'block' }};">
                            <label class="form-label">
                                <span>Double Dastur Fee</span>
                                <span class="nepali-text">दोहोरो दस्तुर शुल्क</span>
                            </label>
                            <input type="number"
                                   class="form-control form-control-lg @error('double_dastur_fee') is-invalid @enderror"
                                   id="double_dastur_fee"
                                   name="double_dastur_fee"
                                   value="{{ old('double_dastur_fee', $job->double_dastur_fee ? rtrim(rtrim(sprintf('%.2f', $job->double_dastur_fee), '0'), '.') : '') }}"
                                   placeholder="Enter Double Dastur Fee"
                                   min="0"
                                   step="0.01">
                            @error('double_dastur_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <!-- <div class="alert alert-warning mt-3 mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Note:</strong> Fee for extended application period (required field).
                                <br><small>नोट: विस्तारित आवेदन अवधिको लागि शुल्क (अनिवार्य)।</small>
                            </div> -->
                        </div>
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" name="title" id="hidden_title" value="{{ $job->title }}">
                    <input type="hidden" name="location" value="Nepal">
                    <input type="hidden" name="description" id="hidden_description" value="{{ $job->description }}">
                    <input type="hidden" name="requirements" id="hidden_requirements" value="{{ $job->requirements }}">
                    <input type="hidden" name="status" value="{{ $job->status }}">
                </div>
            </div>

            <!-- Preview Column -->
            <div class="col-lg-4">
                <div class="preview-card">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-eye-fill text-danger"></i>
                        Live Preview
                        <span class="badge bg-danger ms-auto">रियल टाइम</span>
                    </h6>

                    <table class="preview-table">
                        <tbody>
                            <tr>
                                <th>Sr. No.</th>
                                <td class="text-muted"><em>Auto-generated</em></td>
                            </tr>
                            <tr id="preview-notice-row">
                                <th>Notice No.</th>
                                <td id="preview-notice-no" class="fw-semibold">{{ $job->notice_no ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>Advertisement No.</th>
                                <td id="preview-adv-no" class="fw-semibold">{{ $job->advertisement_no }}</td>
                            </tr>
                            <tr>
                                <th>Position/Level</th>
                                <td id="preview-position" class="fw-semibold">{{ $job->position_level }}</td>
                            </tr>
                            <tr>
                                <th>Service/Group</th>
                                <td id="preview-service" class="fw-semibold">{{ $job->service_group }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td id="preview-category" class="fw-semibold">
                                    @if($job->category == 'open')
                                        <span class="badge bg-success">खुल्ला (Open)</span>
                                    @elseif($job->category == 'inclusive')
                                        <span class="badge bg-info">समावेशी (Inclusive)</span>
                                    @elseif($job->category == 'internal')
                                        <span class="badge bg-warning text-dark">आन्तरिक (Internal)</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr id="preview-inclusive-row" style="display: none;">
                                <th>Inclusive Type</th>
                                <td id="preview-inclusive-type" class="fw-semibold">-</td>
                            </tr>
                            <tr id="preview-internal-subcategory-row" style="display: none;">
                                <th>Internal Type</th>
                                <td id="preview-internal-subcategory" class="fw-semibold">-</td>
                            </tr>
                            <tr>
                                <th>Demand Post</th>
                                <td id="preview-posts" class="fw-semibold">{{ $job->number_of_posts }}</td>
                            </tr>
                            <tr>
                                <th>Deadline (BS)</th>
                                <td id="preview-deadline-bs" class="fw-semibold text-danger">
                                    @if($job->deadline_bs)
                                        {{ strtr($job->deadline_bs, ['0'=>'०','1'=>'१','2'=>'२','3'=>'३','4'=>'४','5'=>'५','6'=>'६','7'=>'७','8'=>'८','9'=>'९']) }} बि.सं.
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Deadline (AD)</th>
                                <td id="preview-deadline-ad" class="fw-semibold text-danger">{{ $job->deadline->format('Y-m-d') }}</td>
                            </tr>
                            <tr id="preview-double-dastur-row" style="display: {{ $job->double_dastur_bs ? '' : 'none' }};">
                                <th>Double Dastur (BS)</th>
                                <td id="preview-double-dastur-bs" class="fw-semibold text-success">
                                    @if($job->double_dastur_bs)
                                        {{ strtr($job->double_dastur_bs, ['0'=>'०','1'=>'१','2'=>'२','3'=>'३','4'=>'४','5'=>'५','6'=>'६','7'=>'७','8'=>'८','9'=>'९']) }} बि.सं.
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr id="preview-double-dastur-ad-row" style="display: {{ $job->double_dastur_date ? '' : 'none' }};">
                                <th>Double Dastur (AD)</th>
                                <td id="preview-double-dastur-ad" class="fw-semibold text-success">
                                    {{ $job->double_dastur_date ? \Carbon\Carbon::parse($job->double_dastur_date)->format('Y-m-d') : '-' }}
                                </td>
                            </tr>
                            <tr id="preview-application-fee-row">
                                <th>Application Fee</th>
                                <td id="preview-application-fee" class="fw-semibold text-primary">
                                    @if($job->application_fee)
                                        NPR {{ number_format($job->application_fee, ($job->application_fee == floor($job->application_fee) ? 0 : 2)) }}
                                    @else
                                        NPR
                                    @endif
                                </td>
                            </tr>
                            <tr id="preview-double-dastur-fee-row" style="display: {{ ($effectiveHasInternal || $effectiveIsAppraisal) ? 'none' : '' }};">
                                <th>Double Dastur Fee</th>
                                <td id="preview-double-dastur-fee" class="fw-semibold text-danger">
                                    @if($job->double_dastur_fee)
                                        NPR {{ number_format($job->double_dastur_fee, ($job->double_dastur_fee == floor($job->double_dastur_fee) ? 0 : 2)) }}
                                    @else
                                        NPR
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4 p-3 bg-white rounded border">
                        <h6 class="small fw-bold text-muted mb-2">
                            <i class="bi bi-mortarboard-fill me-1"></i>Min. Qualification
                        </h6>
                        <p id="preview-qualification" class="small mb-0 text-muted">
                            {{ Str::limit($job->minimum_qualification, 100) }}
                        </p>
                    </div>

                    <div class="mt-4 p-3 rounded"
                        style="background: {{ $job->status == 'active' ? '#d1fae5' : ($job->status == 'draft' ? '#fef3c7' : '#fee2e2') }};">
                        <h6 class="small fw-bold mb-2">
                            <i class="bi bi-info-circle-fill me-1"></i>Current Status
                        </h6>
                        <p class="mb-0">
                            <span
                                class="status-badge {{ $job->status == 'active' ? 'bg-success text-white' : ($job->status == 'draft' ? 'bg-warning text-dark' : 'bg-danger text-white') }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </p>
                        <small class="text-muted d-block mt-2">
                            <!-- <strong>Posted :</strong> {{ $job->created_at->format('Y-M-d, \a\t h:i A') }} -->
                            <strong>Posted :</strong> {{ $job->created_at->format('Y/m/d, h:i A') }}
                            <br>
                            <!-- <strong>Posted :</strong>
                            <span class="nepali-date-bs" data-ad-date="{{ $job->created_at->format('Y-m-d') }}">
                                Loading...
                            </span> -->
                            <!-- <span class="nepali-time">{{ $job->created_at->format('h:i A') }}</span> -->
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary btn-lg">
                                <!-- <i class="bi bi-x-circle me-2"></i> -->
                                Cancel
                            </a>
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-success btn-lg btn-action px-5"
                                    onclick="return confirmUpdate()">
                                    <!-- <i class="bi bi-check-circle me-2"></i> -->
                                    Update Vacancy
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Scroll to Top Button -->
    <button class="stp" id="stp">
        <i class="fas fa-chevron-up"></i>
    </button>

@endsection

@section('scripts')
<script>
(function() {
    'use strict';

    console.log('📝 === Edit Page Date System Initializing ===');

    // ============================================
    // CRITICAL: Numeral conversion functions
    // ============================================

    // Convert Nepali numerals to English
    function nepaliToEnglish(str) {
        if (!str) return str;
        const map = {'०':'0', '१':'1', '२':'2', '३':'3', '४':'4', '५':'5', '६':'6', '७':'7', '८':'8', '९':'9'};
        return str.replace(/[०-९]/g, d => map[d]);
    }

    // Convert English numerals to Nepali for display
    function englishToNepali(str) {
        if (!str) return str;
        const map = {'0':'०', '1':'१', '2':'२', '3':'३', '4':'४', '5':'५', '6':'६', '7':'७', '8':'८', '9':'९'};
        return str.replace(/[0-9]/g, d => map[d]);
    }

    function waitForConverter() {
        if (!window.nepaliLibrariesReady || typeof window.adToBS !== 'function') {
            console.log('⏳ Waiting for converter...');
            setTimeout(waitForConverter, 100);
            return;
        }

        console.log('✅ Converter ready!');

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeForm);
        } else {
            initializeForm();
        }
    }

    function initializeForm() {
        console.log('🔧 Initializing edit form...');

        const deadlineBS = document.getElementById('deadline_bs');
        const deadlineAD = document.getElementById('deadline_ad');
        const doubleDasturBS = document.getElementById('double_dastur_bs');
        const doubleDasturAD = document.getElementById('double_dastur_ad');
        const previewDeadlineBS = document.getElementById('preview-deadline-bs');
        const previewDeadlineAD = document.getElementById('preview-deadline-ad');
        const previewDoubleDasturBS = document.getElementById('preview-double-dastur-bs');
        const previewDoubleDasturAD = document.getElementById('preview-double-dastur-ad');
        const previewDoubleDasturRow = document.getElementById('preview-double-dastur-row');
        const previewDoubleDasturADRow = document.getElementById('preview-double-dastur-ad-row');

        if (!deadlineBS || !deadlineAD) {
            console.error('❌ Date elements not found!');
            return;
        }

        // ============================================
        // Polling to detect picker changes and sync hidden fields
        // ============================================
        let lastBSValue = '';
        let lastDoubleDasturBSValue = '';

        const pollInterval = setInterval(function() {
            // Poll for Deadline BS changes
            const currentBSValue = $('#deadline_bs').val();

            if (currentBSValue &&
                currentBSValue !== lastBSValue &&
                currentBSValue !== 'YYYY-MM-DD' &&
                currentBSValue.length >= 10) {

                console.log('📅 BS Date changed (polling detected):', currentBSValue);
                lastBSValue = currentBSValue;

                const bsValueEnglish = nepaliToEnglish(currentBSValue);
                console.log('🔢 After numeral conversion:', bsValueEnglish);

                const hiddenField = document.getElementById('deadline_bs_hidden');
                if (hiddenField) {
                    hiddenField.value = bsValueEnglish;
                    console.log('✅ Hidden BS field updated:', bsValueEnglish);
                }

                const adValue = window.bsToAD(bsValueEnglish);
                console.log('✅ AD Result:', adValue);

                if (adValue) {
                    deadlineAD.value = adValue;
                    console.log('✅ English date field updated:', adValue);

                    if (previewDeadlineBS) {
                        const bsNepali = englishToNepali(bsValueEnglish);
                        previewDeadlineBS.textContent = bsNepali + ' बि.सं.';
                        console.log('✅ BS Preview:', bsNepali);
                    }

                    if (previewDeadlineAD) {
                        previewDeadlineAD.textContent = adValue;
                        console.log('✅ AD Preview updated:', adValue);
                    }
                }
            }

            // Poll for Double Dastur BS changes
            const currentDoubleDasturBSValue = $('#double_dastur_bs').val();

            if (currentDoubleDasturBSValue &&
                currentDoubleDasturBSValue !== lastDoubleDasturBSValue &&
                currentDoubleDasturBSValue !== 'YYYY-MM-DD' &&
                currentDoubleDasturBSValue.length >= 10) {

                console.log('📅 Double Dastur BS Date changed:', currentDoubleDasturBSValue);
                lastDoubleDasturBSValue = currentDoubleDasturBSValue;

                const ddBsValueEnglish = nepaliToEnglish(currentDoubleDasturBSValue);
                console.log('🔢 Double Dastur after numeral conversion:', ddBsValueEnglish);

                const ddHiddenField = document.getElementById('double_dastur_bs_hidden');
                if (ddHiddenField) {
                    ddHiddenField.value = ddBsValueEnglish;
                }

                const ddAdValue = window.bsToAD(ddBsValueEnglish);
                console.log('✅ Double Dastur AD Result:', ddAdValue);

                if (ddAdValue) {
                    doubleDasturAD.value = ddAdValue;
                    console.log('✅ Double Dastur AD field updated:', ddAdValue);

                    if (previewDoubleDasturBS) {
                        const ddBsNepali = englishToNepali(ddBsValueEnglish);
                        previewDoubleDasturBS.textContent = ddBsNepali + ' बि.सं.';
                        previewDoubleDasturRow.style.display = '';
                    }

                    if (previewDoubleDasturAD) {
                        previewDoubleDasturAD.textContent = ddAdValue;
                        previewDoubleDasturADRow.style.display = '';
                    }
                }
            }
        }, 200);

        // Restore existing BS dates into the picker after it has initialised.
        // The picker starts empty (no value attr in HTML, just like create page).
        // We convert the existing AD date → BS and set it into the picker via JS.
        setTimeout(function() {
            // --- deadline_bs ---
            if (deadlineAD.value) {
                const bsValue = window.adToBS(deadlineAD.value);
                if (bsValue) {
                    const bsNepali = englishToNepali(bsValue);
                    $('#deadline_bs').val(bsNepali);
                    lastBSValue = bsNepali;
                    document.getElementById('deadline_bs_hidden').value = bsValue;
                    if (previewDeadlineBS) previewDeadlineBS.textContent = bsNepali + ' बि.सं.';
                    if (previewDeadlineAD) previewDeadlineAD.textContent = deadlineAD.value;
                }
            }

            // --- double_dastur_bs ---
            if (doubleDasturAD && doubleDasturAD.value) {
                const ddBsValue = window.adToBS(doubleDasturAD.value);
                if (ddBsValue) {
                    const ddBsNepali = englishToNepali(ddBsValue);
                    $('#double_dastur_bs').val(ddBsNepali);
                    lastDoubleDasturBSValue = ddBsNepali;
                    document.getElementById('double_dastur_bs_hidden').value = ddBsValue;
                    if (previewDoubleDasturBS) {
                        previewDoubleDasturBS.textContent = ddBsNepali + ' बि.सं.';
                        if (previewDoubleDasturRow) previewDoubleDasturRow.style.display = '';
                    }
                    if (previewDoubleDasturAD) {
                        previewDoubleDasturAD.textContent = doubleDasturAD.value;
                        if (previewDoubleDasturADRow) previewDoubleDasturADRow.style.display = '';
                    }
                }
            }
        }, 600);

        console.log('✅ Date system ready (edit page)!');

        // ============================================
        // Convert Posted Date (created_at) to Nepali
        // ============================================
        setTimeout(function() {
            const postedDateElements = document.querySelectorAll('.nepali-date-bs');
            postedDateElements.forEach(function(element) {
                const adDate = element.getAttribute('data-ad-date');
                if (adDate && window.adToBS) {
                    const bsDate = window.adToBS(adDate);
                    if (bsDate) {
                        const bsNepali = englishToNepali(bsDate);
                        element.textContent = bsNepali;
                    }
                }
            });
        }, 600);

        // ============================================
        // REST OF FORM - Live Preview and other logic
        // ============================================

        // ===========================================
        // NEW CATEGORY SYSTEM - Three-Level Hierarchy
        // ===========================================
        (function() {
            console.log('🎯 EDIT PAGE CATEGORY SYSTEM INITIALIZING...');
            console.log('📊 Database values from blade:', {
                has_open: {{ $job->has_open ? 'true' : 'false' }},
                has_inclusive: {{ $job->has_inclusive ? 'true' : 'false' }},
                inclusive_type: '{{ $job->inclusive_type ?? 'null' }}',
                category: '{{ $job->category ?? 'null' }}'
            });

            const hasOpenCheckbox = document.getElementById('has_open');
            const hasInternalCheckbox = document.getElementById('has_internal');
            const isInternalAppraisalCheckbox = document.getElementById('is_internal_appraisal');
            const hasInclusiveToggleCheckbox = document.getElementById('has_inclusive_toggle');
            const inclusiveTypesToggle = document.getElementById('inclusiveTypesToggle');
            const inclusiveTypesSection = document.getElementById('inclusiveTypesSection');
            const inclusiveTypeCheckboxes = document.querySelectorAll('.inclusive-type-checkbox');
            const hasInclusiveHidden = document.getElementById('has_inclusive');
            const hiddenCategory = document.getElementById('hidden_category');
            const hiddenInclusiveType = document.getElementById('hidden_inclusive_type');

            console.log('✅ Checkbox states BEFORE initialization:', {
                hasOpenChecked: hasOpenCheckbox?.checked,
                hasInclusiveToggleChecked: hasInclusiveToggleCheckbox?.checked,
                inclusiveTypesToggleDisplay: inclusiveTypesToggle?.style.display,
                inclusiveTypesSectionDisplay: inclusiveTypesSection?.style.display
            });

            // Internal sub-category elements
            const internalOpenToggle = document.getElementById('internalOpenToggle');
            const hasInternalOpenCheckbox = document.getElementById('has_internal_open');
            const internalInclusiveToggle = document.getElementById('internalInclusiveToggle');
            const hasInternalInclusiveToggleCheckbox = document.getElementById('has_internal_inclusive_toggle');
            const internalInclusiveTypesSection = document.getElementById('internalInclusiveTypesSection');
            const internalInclusiveTypeCheckboxes = document.querySelectorAll('.internal-inclusive-type-checkbox');
            const hasInternalInclusiveHidden = document.getElementById('has_internal_inclusive');

            // Double Dastur elements
            const doubleDasturDateSection = document.getElementById('doubleDasturDateSection');
            const doubleDasturFeeSection = document.getElementById('doubleDasturFeeSection');
            const doubleDasturFeeInput = document.getElementById('double_dastur_fee');

            // ============================================
            // Helper Functions for Showing/Hiding Sections
            // ============================================

            function showDoubleDasturSections() {
                if (doubleDasturDateSection) doubleDasturDateSection.style.display = 'block';
                if (doubleDasturFeeSection) doubleDasturFeeSection.style.display = 'block';
                if (doubleDasturFeeInput) doubleDasturFeeInput.removeAttribute('disabled');
                // Show preview rows
                const previewFeeRow = document.getElementById('preview-double-dastur-fee-row');
                const previewBSRow  = document.getElementById('preview-double-dastur-row');
                const previewADRow  = document.getElementById('preview-double-dastur-ad-row');
                if (previewFeeRow) previewFeeRow.style.display = '';
                if (previewBSRow)  previewBSRow.style.display  = '';
                if (previewADRow)  previewADRow.style.display  = '';
            }

            function hideDoubleDasturSections() {
                if (doubleDasturDateSection) doubleDasturDateSection.style.display = 'none';
                if (doubleDasturFeeSection) doubleDasturFeeSection.style.display = 'none';
                if (doubleDasturFeeInput) {
                    doubleDasturFeeInput.value = '0';
                    doubleDasturFeeInput.disabled = true;
                }
                // Hide preview rows
                const previewFeeRow = document.getElementById('preview-double-dastur-fee-row');
                const previewBSRow  = document.getElementById('preview-double-dastur-row');
                const previewADRow  = document.getElementById('preview-double-dastur-ad-row');
                if (previewFeeRow) previewFeeRow.style.display = 'none';
                if (previewBSRow)  previewBSRow.style.display  = 'none';
                if (previewADRow)  previewADRow.style.display  = 'none';
            }

            function hideOpenSections() {
                if (inclusiveTypesToggle) inclusiveTypesToggle.style.display = 'none';
                if (inclusiveTypesSection) inclusiveTypesSection.style.display = 'none';
                if (hasInclusiveToggleCheckbox) hasInclusiveToggleCheckbox.checked = false;
                inclusiveTypeCheckboxes.forEach(cb => cb.checked = false);
            }

            function hideInternalSections() {
                if (internalOpenToggle) internalOpenToggle.style.display = 'none';
                if (internalInclusiveToggle) internalInclusiveToggle.style.display = 'none';
                if (internalInclusiveTypesSection) internalInclusiveTypesSection.style.display = 'none';
                if (hasInternalOpenCheckbox) hasInternalOpenCheckbox.checked = false;
                if (hasInternalInclusiveToggleCheckbox) hasInternalInclusiveToggleCheckbox.checked = false;
                internalInclusiveTypeCheckboxes.forEach(cb => cb.checked = false);
            }

            // ============================================
            // Mutual Exclusivity: Disable / Enable Helpers
            // ============================================

            function disableOtherMainCategories(activeCheckbox) {
                const allMain = [hasOpenCheckbox, hasInternalCheckbox, isInternalAppraisalCheckbox];
                allMain.forEach(function(cb) {
                    if (cb && cb !== activeCheckbox) {
                        const fc = cb.closest('.form-check');
                        if (fc) fc.classList.add('cat-locked');
                    }
                });
            }

            function enableAllMainCategories() {
                const allMain = [hasOpenCheckbox, hasInternalCheckbox, isInternalAppraisalCheckbox];
                allMain.forEach(function(cb) {
                    if (cb) {
                        const fc = cb.closest('.form-check');
                        if (fc) fc.classList.remove('cat-locked');
                    }
                });
            }

            // ============================================
            // Mutual Exclusivity Handler
            // ============================================

            function handleCategoryExclusivity(selectedCheckbox) {
                if (selectedCheckbox.checked) {
                    // Uncheck the other two (locking handled by separate syncLock script)
                    if (selectedCheckbox === hasOpenCheckbox) {
                        if (hasInternalCheckbox)         hasInternalCheckbox.checked         = false;
                        if (isInternalAppraisalCheckbox) isInternalAppraisalCheckbox.checked = false;
                        hideInternalSections();
                        toggleInclusiveTypesToggle();
                        showDoubleDasturSections();
                    } else if (selectedCheckbox === hasInternalCheckbox) {
                        if (hasOpenCheckbox)             hasOpenCheckbox.checked             = false;
                        if (isInternalAppraisalCheckbox) isInternalAppraisalCheckbox.checked = false;
                        hideOpenSections();
                        toggleInternalSubcategories();
                        hideDoubleDasturSections();
                    } else if (selectedCheckbox === isInternalAppraisalCheckbox) {
                        if (hasOpenCheckbox)     hasOpenCheckbox.checked     = false;
                        if (hasInternalCheckbox) hasInternalCheckbox.checked = false;
                        hideOpenSections();
                        hideInternalSections();
                        hideDoubleDasturSections();
                    }
                } else {
                    // Unchecked — reset sections (unlocking handled by separate syncLock script)
                    if (selectedCheckbox === hasOpenCheckbox) {
                        hideOpenSections();
                    } else if (selectedCheckbox === hasInternalCheckbox) {
                        hideInternalSections();
                    }
                    showDoubleDasturSections();
                }
                updateCategoryDisplay();
                updateHiddenFields();
            }

            // ============================================
            // Open Category Functions
            // ============================================

            // Show/Hide Inclusive Types Toggle (when Open is checked)
            function toggleInclusiveTypesToggle() {
                if (hasOpenCheckbox && hasOpenCheckbox.checked &&
                    !(isInternalAppraisalCheckbox && isInternalAppraisalCheckbox.checked)) {
                    if (inclusiveTypesToggle) inclusiveTypesToggle.style.display = 'block';
                } else {
                    if (inclusiveTypesToggle) inclusiveTypesToggle.style.display = 'none';
                    if (inclusiveTypesSection) inclusiveTypesSection.style.display = 'none';
                }
                updateHiddenFields();
            }

            // Show/Hide Individual Types (when Inclusive Types checkbox is checked)
            function toggleInclusiveTypesSection() {
                if (hasInclusiveToggleCheckbox && hasInclusiveToggleCheckbox.checked) {
                    if (inclusiveTypesSection) inclusiveTypesSection.style.display = 'block';
                } else {
                    if (inclusiveTypesSection) inclusiveTypesSection.style.display = 'none';
                }
                updateHiddenFields();
            }

            // ============================================
            // Internal Category Functions
            // ============================================

            // Show/Hide Internal sub-categories (when Internal is checked)
            function toggleInternalSubcategories() {
                if (hasInternalCheckbox && hasInternalCheckbox.checked &&
                    !(isInternalAppraisalCheckbox && isInternalAppraisalCheckbox.checked)) {
                    internalOpenToggle.style.display = 'block';
                    internalInclusiveToggle.style.display = 'block';
                } else {
                    internalOpenToggle.style.display = 'none';
                    internalInclusiveToggle.style.display = 'none';
                    internalInclusiveTypesSection.style.display = 'none';
                    if (hasInternalOpenCheckbox) hasInternalOpenCheckbox.checked = false;
                    if (hasInternalInclusiveToggleCheckbox) hasInternalInclusiveToggleCheckbox.checked = false;
                    internalInclusiveTypeCheckboxes.forEach(cb => cb.checked = false);
                }
                updateHiddenFields();
            }

            // Show/Hide Internal Inclusive Types (when Internal Inclusive toggle is checked)
            function toggleInternalInclusiveTypesSection() {
                if (hasInternalInclusiveToggleCheckbox && hasInternalInclusiveToggleCheckbox.checked) {
                    internalInclusiveTypesSection.style.display = 'block';
                } else {
                    internalInclusiveTypesSection.style.display = 'none';
                    internalInclusiveTypeCheckboxes.forEach(cb => cb.checked = false);
                }
                updateHiddenInternalInclusiveField();
                updateHiddenFields();
            }

            // Update hidden field for has_internal_inclusive
            function updateHiddenInternalInclusiveField() {
                if (hasInternalInclusiveHidden) {
                    if (hasInternalInclusiveToggleCheckbox && hasInternalInclusiveToggleCheckbox.checked) {
                        hasInternalInclusiveHidden.value = '1';
                    } else {
                        hasInternalInclusiveHidden.value = '0';
                    }
                }
            }

            // ============================================
            // Live Preview Update Functions
            // ============================================

            const previewCategory = document.getElementById('preview-category');
            const previewInclusiveRow = document.getElementById('preview-inclusive-row');
            const previewInclusiveType = document.getElementById('preview-inclusive-type');
            const previewInternalSubcategoryRow = document.getElementById('preview-internal-subcategory-row');
            const previewInternalSubcategory = document.getElementById('preview-internal-subcategory');

            // Update Category Preview
            function updateCategoryDisplay() {
                if (!previewCategory) return;

                const categories = [];

                if (isInternalAppraisalCheckbox && isInternalAppraisalCheckbox.checked) {
                    categories.push('<span class="badge bg-danger">आन्तरिक बढुवा (Internal Appraisal)</span>');
                } else {
                    if (hasOpenCheckbox && hasOpenCheckbox.checked) {
                        categories.push('<span class="badge bg-success">खुल्ला (Open)</span>');
                    }
                    if (hasInternalCheckbox && hasInternalCheckbox.checked) {
                        categories.push('<span class="badge bg-warning text-dark">आन्तरिक (Internal)</span>');
                    }
                }

                previewCategory.innerHTML = categories.length > 0 ? categories.join(' ') : '-';

                // Update inclusive types preview
                updateInclusiveTypesPreview();
                // Update internal sub-categories preview
                updateInternalSubcategoriesPreview();
            }

            // Update Open Inclusive Types Preview
            function updateInclusiveTypesPreview() {
                if (!previewInclusiveType || !previewInclusiveRow) return;

                const checkedTypes = Array.from(inclusiveTypeCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                if (hasOpenCheckbox && hasOpenCheckbox.checked &&
                    hasInclusiveToggleCheckbox && hasInclusiveToggleCheckbox.checked &&
                    checkedTypes.length > 0) {
                    previewInclusiveType.textContent = checkedTypes.join(', ');
                    previewInclusiveRow.style.display = '';
                    // Set has_inclusive hidden field to 1 if any inclusive type is selected
                    if (hasInclusiveHidden) {
                        hasInclusiveHidden.value = '1';
                    }
                } else {
                    previewInclusiveRow.style.display = 'none';
                    if (hasInclusiveHidden) {
                        hasInclusiveHidden.value = '0';
                    }
                }
            }

            // Update Internal Sub-categories Preview
            function updateInternalSubcategoriesPreview() {
                if (!previewInternalSubcategory || !previewInternalSubcategoryRow) return;

                const subcategories = [];

                if (hasInternalCheckbox && hasInternalCheckbox.checked) {
                    if (hasInternalOpenCheckbox && hasInternalOpenCheckbox.checked) {
                        subcategories.push('Internal Open (All NOC Staff)');
                    }

                    if (hasInternalInclusiveToggleCheckbox && hasInternalInclusiveToggleCheckbox.checked) {
                        const checkedTypes = Array.from(internalInclusiveTypeCheckboxes)
                            .filter(cb => cb.checked)
                            .map(cb => cb.value);

                        if (checkedTypes.length > 0) {
                            subcategories.push('Internal Inclusive (' + checkedTypes.join(', ') + ')');
                        }
                    }
                }

                if (subcategories.length > 0) {
                    previewInternalSubcategory.textContent = subcategories.join(' | ');
                    previewInternalSubcategoryRow.style.display = '';
                } else {
                    previewInternalSubcategoryRow.style.display = 'none';
                }
            }

            // Update hidden fields for backward compatibility
            function updateHiddenFields() {
                const isOpen      = hasOpenCheckbox      && hasOpenCheckbox.checked;
                const isInternal  = hasInternalCheckbox  && hasInternalCheckbox.checked;
                const isAppraisal = isInternalAppraisalCheckbox && isInternalAppraisalCheckbox.checked;

                // has_open
                const hiddenHasOpen = document.getElementById('hidden_has_open');
                if (hiddenHasOpen) hiddenHasOpen.value = isOpen ? '1' : '0';

                // has_internal
                const hiddenHasInternal = document.getElementById('hidden_has_internal');
                if (hiddenHasInternal) hiddenHasInternal.value = isInternal ? '1' : '0';

                // has_inclusive
                const isInclusiveToggle = hasInclusiveToggleCheckbox && hasInclusiveToggleCheckbox.checked;
                if (hasInclusiveHidden) hasInclusiveHidden.value = (isOpen && isInclusiveToggle) ? '1' : '0';

                // has_internal_inclusive
                const isInternalInclusive = hasInternalInclusiveToggleCheckbox && hasInternalInclusiveToggleCheckbox.checked;
                const hiddenHasInternalInclusive = document.getElementById('has_internal_inclusive');
                if (hiddenHasInternalInclusive) hiddenHasInternalInclusive.value = (isInternal && isInternalInclusive) ? '1' : '0';

                // category
                if (isAppraisal) {
                    hiddenCategory.value = 'internal_appraisal';
                } else if (isInternal) {
                    hiddenCategory.value = 'internal';
                } else if (isOpen) {
                    hiddenCategory.value = isInclusiveToggle ? 'inclusive' : 'open';
                } else {
                    hiddenCategory.value = 'open';
                }

                // inclusive_type (JSON array of checked inclusive types — only relevant for Open)
                const checkedTypes = Array.from(inclusiveTypeCheckboxes).filter(cb => cb.checked);
                if (hiddenInclusiveType) {
                    hiddenInclusiveType.value = (isOpen && checkedTypes.length > 0)
                        ? checkedTypes[0].value
                        : '';
                }

                // Update live preview
                updateCategoryDisplay();
            }

            // Validate at least one category is selected
            function validateCategories() {
                const isOpen = hasOpenCheckbox && hasOpenCheckbox.checked;
                const isInternal = hasInternalCheckbox && hasInternalCheckbox.checked;
                const isAppraisal = isInternalAppraisalCheckbox && isInternalAppraisalCheckbox.checked;

                if (!isOpen && !isInternal && !isAppraisal) {
                    alert('कृपया एक मुख्य श्रेणी छान्नुहोस्!\nPlease select one main category!');
                    return false;
                }

                // Validate Open sub-categories
                if (isOpen) {
                    // Double Dastur Date (BS) is required for Open category
                    const ddBS = document.getElementById('double_dastur_bs_hidden');
                    if (!ddBS || !ddBS.value) {
                        alert('Double Dastur Date (Nepali BS) is required for Open category.\nकृपया दोहोरो दस्तुर मिति (नेपाली) भर्नुहोस्!');
                        document.getElementById('double_dastur_bs').focus();
                        return false;
                    }

                    // Double Dastur Fee is required for Open category
                    const ddFee = document.getElementById('double_dastur_fee');
                    if (!ddFee || !ddFee.value || parseFloat(ddFee.value) <= 0) {
                        alert('Double Dastur Fee is required for Open category and must be greater than 0.\nकृपया दोहोरो दस्तुर शुल्क भर्नुहोस्!');
                        ddFee && ddFee.focus();
                        return false;
                    }

                    // If Inclusive Types toggle is checked, at least one type must be selected
                    if (hasInclusiveToggleCheckbox && hasInclusiveToggleCheckbox.checked) {
                        const anyInclusiveTypeChecked = Array.from(inclusiveTypeCheckboxes).some(cb => cb.checked);
                        if (!anyInclusiveTypeChecked) {
                            alert('कृपया कम्तिमा एक समावेशी प्रकार छान्नुहोस्!\nPlease select at least one inclusive type!');
                            return false;
                        }
                    }
                }

                // Validate Internal sub-categories
                if (isInternal) {
                    const hasInternalOpen = hasInternalOpenCheckbox && hasInternalOpenCheckbox.checked;
                    const hasInternalInclusive = hasInternalInclusiveToggleCheckbox && hasInternalInclusiveToggleCheckbox.checked;

                    if (!hasInternalOpen && !hasInternalInclusive) {
                        alert('Please select at least one sub-category for Internal!');
                        return false;
                    }

                    // If Internal Inclusive is checked, at least one type must be selected
                    if (hasInternalInclusive) {
                        const anyTypeChecked = Array.from(internalInclusiveTypeCheckboxes).some(cb => cb.checked);
                        if (!anyTypeChecked) {
                            alert('Please select at least one internal inclusive type!');
                            return false;
                        }
                    }
                }

                return true;
            }

            // ============================================
            // Event Listeners
            // ============================================

            if (hasOpenCheckbox) {
                hasOpenCheckbox.addEventListener('change', function() {
                    handleCategoryExclusivity(this);
                });
            }

            if (hasInternalCheckbox) {
                hasInternalCheckbox.addEventListener('change', function() {
                    handleCategoryExclusivity(this);
                });
            }

            if (isInternalAppraisalCheckbox) {
                isInternalAppraisalCheckbox.addEventListener('change', function() {
                    handleCategoryExclusivity(this);
                });
            }

            if (hasInclusiveToggleCheckbox) {
                hasInclusiveToggleCheckbox.addEventListener('change', function() {
                    toggleInclusiveTypesSection();
                    updateInclusiveTypesPreview();
                });
            }

            if (hasInternalOpenCheckbox) {
                hasInternalOpenCheckbox.addEventListener('change', function() {
                    updateInternalSubcategoriesPreview();
                });
            }

            if (hasInternalInclusiveToggleCheckbox) {
                hasInternalInclusiveToggleCheckbox.addEventListener('change', function() {
                    toggleInternalInclusiveTypesSection();
                    updateInternalSubcategoriesPreview();
                });
            }

            inclusiveTypeCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    updateHiddenFields();
                    updateInclusiveTypesPreview();
                });
            });

            internalInclusiveTypeCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    updateHiddenInternalInclusiveField();
                    updateHiddenFields();
                    updateInternalSubcategoriesPreview();
                });
            });

            // Initialize on page load
            // Check which main category is selected and set up accordingly
            if (hasOpenCheckbox && hasOpenCheckbox.checked) {
                // Show Open sub-categories first
                toggleInclusiveTypesToggle();
                // Then show Inclusive Types section if it was previously selected
                toggleInclusiveTypesSection();
                handleCategoryExclusivity(hasOpenCheckbox);
            } else if (hasInternalCheckbox && hasInternalCheckbox.checked) {
                // Show Internal sub-categories first
                toggleInternalSubcategories();
                // Then show Internal Inclusive Types if they were previously selected
                toggleInternalInclusiveTypesSection();
                handleCategoryExclusivity(hasInternalCheckbox);
            } else if (isInternalAppraisalCheckbox && isInternalAppraisalCheckbox.checked) {
                handleCategoryExclusivity(isInternalAppraisalCheckbox);
            } else {
                // No category selected - all enabled
                toggleInclusiveTypesToggle();
                toggleInclusiveTypesSection();
                toggleInternalSubcategories();
                toggleInternalInclusiveTypesSection();
            }
            updateHiddenFields();
            updateCategoryDisplay(); // Initialize preview

            console.log('✅ Checkbox states AFTER initialization:', {
                hasOpenChecked: hasOpenCheckbox?.checked,
                hasInclusiveToggleChecked: hasInclusiveToggleCheckbox?.checked,
                inclusiveTypesToggleDisplay: inclusiveTypesToggle?.style.display,
                inclusiveTypesSectionDisplay: inclusiveTypesSection?.style.display,
                womenCheckboxChecked: document.getElementById('incl_women')?.checked
            });

            // ===========================================
            // PREVIEW UPDATES
            // ===========================================

            const previewMappings = {
                'notice_no': { preview: 'preview-notice-no', default: '-' },
                'advertisement_no': { preview: 'preview-adv-no', default: '-' },
                'position_level': { preview: 'preview-position', default: '-' },
                'service_group': { preview: 'preview-service', default: '-' },
                'number_of_posts': { preview: 'preview-posts', default: '-' },
                'minimum_qualification': { preview: 'preview-qualification', default: 'Not entered...' },
                'application_fee': { preview: 'preview-application-fee', default: '-' },
                'double_dastur_fee': { preview: 'preview-double-dastur-fee', default: '-' }
            };

            Object.keys(previewMappings).forEach(fieldId => {
                const input = document.getElementById(fieldId);
                const preview = document.getElementById(previewMappings[fieldId].preview);

                if (input && preview) {
                    const eventType = input.tagName === 'SELECT' ? 'change' : 'input';

                    input.addEventListener(eventType, function () {
                        const value = this.value.trim();

                        // Special handling for notice_no
                        if (fieldId === 'notice_no') {
                            preview.textContent = value || '-';
                        } else if (fieldId === 'minimum_qualification') {
                            preview.textContent = value.substring(0, 100) + (value.length > 100 ? '...' : '');
                        } else if (fieldId === 'application_fee' || fieldId === 'double_dastur_fee') {
                            const feeRow = document.getElementById(`preview-${fieldId}-row`);
                            if (value && parseFloat(value) >= 0) {
                                const numValue = parseFloat(value);
                                const formattedValue = numValue % 1 === 0
                                    ? numValue.toLocaleString('en-NP', { minimumFractionDigits: 0, maximumFractionDigits: 0 })
                                    : numValue.toLocaleString('en-NP', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                preview.textContent = 'NPR ' + formattedValue;
                            } else {
                                preview.textContent = 'NPR';
                            }
                            if (feeRow) feeRow.style.display = '';
                        } else {
                            preview.textContent = value || previewMappings[fieldId].default;
                        }
                    });
                }
            });

            // ===========================================
            // FORM SUBMISSION
            // ===========================================

            const form = document.getElementById('vacancyForm');
            form.addEventListener('submit', function (e) {
                // Validate categories before submission
                if (!validateCategories()) {
                    e.preventDefault();
                    return false;
                }

                // Update ALL hidden fields
                document.getElementById('hidden_has_open').value = (hasOpenCheckbox && hasOpenCheckbox.checked) ? '1' : '0';
                document.getElementById('hidden_has_internal').value = (hasInternalCheckbox && hasInternalCheckbox.checked) ? '1' : '0';
                const hasIncTypes = Array.from(inclusiveTypeCheckboxes).some(cb => cb.checked);
                document.getElementById('has_inclusive').value = (hasInclusiveToggleCheckbox && hasInclusiveToggleCheckbox.checked && hasIncTypes) ? '1' : '0';

                // Update title, description, requirements
                const positionLevel = document.getElementById('position_level').value;
                document.getElementById('hidden_title').value = positionLevel;
                document.getElementById('hidden_description').value = 'Position: ' + positionLevel;
                document.getElementById('hidden_requirements').value = document.getElementById('minimum_qualification').value;

                console.log('✅ EDIT FORM - ALL FIELDS SET');
                return true;

                // CRITICAL FIX: Re-enable all checkboxes before submission
                // Disabled checkboxes don't submit with the form!
                if (hasOpenCheckbox) hasOpenCheckbox.disabled = false;
                if (hasInternalCheckbox) hasInternalCheckbox.disabled = false;
                if (isInternalAppraisalCheckbox) isInternalAppraisalCheckbox.disabled = false;

                // CRITICAL: Force update ALL hidden fields RIGHT NOW
                const noticeNoField = document.getElementById('notice_no');
                const hasOpenHidden = document.getElementById('hidden_has_open');
                const hasInclusiveHidden = document.getElementById('has_inclusive');
                const hasInternalHidden = document.getElementById('hidden_has_internal');

                if (hasOpenHidden) {
                    hasOpenHidden.value = (hasOpenCheckbox && hasOpenCheckbox.checked) ? '1' : '0';
                }
                if (hasInternalHidden) {
                    hasInternalHidden.value = (hasInternalCheckbox && hasInternalCheckbox.checked) ? '1' : '0';
                }

                // Check if any inclusive type is selected
                const hasInclusiveTypes = Array.from(inclusiveTypeCheckboxes).some(cb => cb.checked);
                if (hasInclusiveHidden) {
                    hasInclusiveHidden.value = (hasInclusiveToggleCheckbox && hasInclusiveToggleCheckbox.checked && hasInclusiveTypes) ? '1' : '0';
                }

                console.log('✅ EDIT FORM - FINAL HIDDEN FIELD VALUES:', {
                    notice_no: noticeNoField?.value,
                    has_open: hasOpenHidden?.value,
                    has_inclusive: hasInclusiveHidden?.value,
                    has_internal: hasInternalHidden?.value
                });

                // Set primary category based on what's checked
                const hiddenCategoryField = document.getElementById('hidden_category');
                if (isInternalAppraisalCheckbox && isInternalAppraisalCheckbox.checked) {
                    hiddenCategoryField.value = 'internal_appraisal';
                } else if (hasInternalCheckbox && hasInternalCheckbox.checked) {
                    hiddenCategoryField.value = 'internal';
                } else if (hasOpenCheckbox && hasOpenCheckbox.checked) {
                    hiddenCategoryField.value = 'open';
                }

                // Set inclusive_type (first checked inclusive type)
                const hiddenInclusiveTypeField = document.getElementById('hidden_inclusive_type');
                const firstInclusiveType = Array.from(inclusiveTypeCheckboxes).find(cb => cb.checked);
                if (firstInclusiveType) {
                    hiddenInclusiveTypeField.value = firstInclusiveType.value;
                }

                const positionLevel = document.getElementById('position_level').value;
                document.getElementById('hidden_title').value = positionLevel;

                let descriptionText = 'Position: ' + positionLevel + '\n' +
                    'Service/Group: ' + document.getElementById('service_group').value + '\n';

                // Build category description
                const categories = [];

                // Open category
                if (hasOpenCheckbox && hasOpenCheckbox.checked) {
                    categories.push('Open');

                    // Open Inclusive sub-category
                    if (hasInclusiveToggleCheckbox && hasInclusiveToggleCheckbox.checked) {
                        const types = Array.from(inclusiveTypeCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
                        if (types.length > 0) {
                            categories.push('  - Inclusive (' + types.join(', ') + ')');
                        }
                    }
                }

                // Internal category
                if (hasInternalCheckbox && hasInternalCheckbox.checked) {
                    categories.push('Internal');

                    // Internal Open sub-category
                    if (hasInternalOpenCheckbox && hasInternalOpenCheckbox.checked) {
                        categories.push('  - Internal Open (All NOC Staff)');
                    }

                    // Internal Inclusive sub-category
                    if (hasInternalInclusiveToggleCheckbox && hasInternalInclusiveToggleCheckbox.checked) {
                        const intTypes = Array.from(internalInclusiveTypeCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
                        if (intTypes.length > 0) {
                            categories.push('  - Internal Inclusive (' + intTypes.join(', ') + ')');
                        }
                    }
                }

                // Internal Appraisal category
                if (isInternalAppraisalCheckbox && isInternalAppraisalCheckbox.checked) {
                    categories.push('Internal Appraisal');
                }

                descriptionText += 'Categories: ' + (categories.length > 0 ? categories.join(', ') : 'N/A') + '\n';
                descriptionText += 'Number of Posts: ' + document.getElementById('number_of_posts').value;

                document.getElementById('hidden_description').value = descriptionText;
                document.getElementById('hidden_requirements').value = document.getElementById('minimum_qualification').value;

                // BULLETPROOF FIX: Use FormData to ensure values are sent
                const formData = new FormData(form);

                // Force set the critical values
                formData.set('has_open', (hasOpenCheckbox && hasOpenCheckbox.checked) ? '1' : '0');
                formData.set('has_inclusive', (hasInclusiveToggleCheckbox && hasInclusiveToggleCheckbox.checked && Array.from(inclusiveTypeCheckboxes).some(cb => cb.checked)) ? '1' : '0');
                formData.set('has_internal', (hasInternalCheckbox && hasInternalCheckbox.checked) ? '1' : '0');

                console.log('🚀 EDIT FORM SUBMITTING WITH FORMDATA:', {
                    notice_no: formData.get('notice_no'),
                    has_open: formData.get('has_open'),
                    has_inclusive: formData.get('has_inclusive'),
                    has_internal: formData.get('has_internal')
                });

                // Submit using fetch
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        return response.text();
                    }
                })
                .then(html => {
                    if (html) {
                        document.open();
                        document.write(html);
                        document.close();
                    }
                })
                .catch(error => {
                    console.error('Submit error:', error);
                    alert('Error submitting form. Please try again.');
                });
            });
        })();

        console.log('✅ === ALL COMPLETE (Edit Page) ===');
    }

    waitForConverter();
})();

function confirmUpdate() {
    return confirm(
        '⚠️ Are you sure you want to update this vacancy?\n\n' +
        'यो रिक्त पद अपडेट गर्न निश्चित हुनुहुन्छ?\n\n' +
        'The changes will be saved immediately.'
    );
}

// Scroll to Top Button - Zero Delays
(function() {
    'use strict';

    const btn = document.getElementById('stp');
    if (!btn) return;

    let isAnimating = false;
    let animationId = null;

    btn.style.display = 'none';

    function easeInOutQuad(t) {
        return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
    }

    function scrollToTop() {
        if (animationId) {
            cancelAnimationFrame(animationId);
        }

        if (isAnimating) return;

        isAnimating = true;

        const startPosition = window.pageYOffset || document.documentElement.scrollTop;
        const startTime = performance.now();
        const duration = 700;

        function animate(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            const easeProgress = easeInOutQuad(progress);
            const currentPosition = startPosition * (1 - easeProgress);

            window.scrollTo(0, currentPosition);

            if (progress < 1) {
                animationId = requestAnimationFrame(animate);
            } else {
                isAnimating = false;
                animationId = null;
            }
        }

        animationId = requestAnimationFrame(animate);
    }

    btn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        scrollToTop();
        return false;
    }, true);

    let visible = false;

    function checkScroll() {
        const scrolled = window.pageYOffset || document.documentElement.scrollTop;

        if (scrolled > 200) {
            if (!visible) {
                btn.style.display = 'flex';
                setTimeout(function() {
                    btn.style.opacity = '1';
                }, 10);
                visible = true;
            }
        } else {
            if (visible) {
                btn.style.opacity = '0';
                setTimeout(function() {
                    btn.style.display = 'none';
                }, 300);
                visible = false;
            }
        }
    }

    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                checkScroll();
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });

    checkScroll();

})();


</script>

{{-- CDN nepaliDatePicker — isolated block, handles init + restore + sync --}}
<script>
$(function () {

    // ── 1. Init CDN pickers ──────────────────────────────────────
    $('#deadline_bs').nepaliDatePicker({ dateFormat: 'YYYY-MM-DD', unicodeDate: true });
    $('#double_dastur_bs').nepaliDatePicker({ dateFormat: 'YYYY-MM-DD', unicodeDate: true });

    // ── 2. Numeral helpers ───────────────────────────────────────
    var N = '\u0966\u0967\u0968\u0969\u096A\u096B\u096C\u096D\u096E\u096F';
    function toNep(s) {
        return s ? String(s).replace(/[0-9]/g, function (d) { return N[+d]; }) : '';
    }
    function toEng(s) {
        if (!s) return '';
        var r = String(s);
        for (var i = 0; i < 10; i++) r = r.split(N[i]).join(String(i));
        return r;
    }

    // ── 3. Restore stored BS dates into visible inputs ───────────
    var dh = document.getElementById('deadline_bs_hidden');
    if (dh && dh.value) {
        $('#deadline_bs').val(toNep(dh.value));
    }

    var ddh = document.getElementById('double_dastur_bs_hidden');
    if (ddh && ddh.value) {
        $('#double_dastur_bs').val(toNep(ddh.value));
    } else if (typeof window.adToBS === 'function') {
        var ddADel = document.getElementById('double_dastur_ad');
        if (ddADel && ddADel.value) {
            var converted = window.adToBS(ddADel.value);
            if (converted) {
                $('#double_dastur_bs').val(toNep(converted));
                if (ddh) ddh.value = converted;
            }
        }
    }

    // ── 4. Snapshot current values so polling ignores them ───────
    var lastDeadline = $('#deadline_bs').val();
    var lastDDastur  = $('#double_dastur_bs').val();

    // ── 5. Sync helper — runs whenever a picker value changes ────
    function syncBS(bsNep, hiddenId, adId, prevBsId, prevAdId, showRowIds) {
        var bsEng = toEng(bsNep);
        if (!/^\d{4}-\d{2}-\d{2}$/.test(bsEng)) return;

        // Update hidden field (for form submission)
        var hid = document.getElementById(hiddenId);
        if (hid) hid.value = bsEng;

        // Convert BS → AD and update AD field + preview
        if (typeof window.bsToAD === 'function') {
            var ad = window.bsToAD(bsEng);
            if (ad) {
                var adEl = document.getElementById(adId);
                if (adEl) adEl.value = ad;

                var pBS = document.getElementById(prevBsId);
                if (pBS) pBS.textContent = bsNep + ' \u092C\u093F.\u0938\u0902.';

                var pAD = document.getElementById(prevAdId);
                if (pAD) pAD.textContent = ad;

                if (showRowIds) {
                    showRowIds.forEach(function (id) {
                        var el = document.getElementById(id);
                        if (el) el.style.display = '';
                    });
                }
            }
        }
    }

    // ── 6. Poll every 200 ms for picker value changes ────────────
    setInterval(function () {

        var dVal = $('#deadline_bs').val();
        if (dVal && dVal !== lastDeadline) {
            lastDeadline = dVal;
            syncBS(dVal,
                'deadline_bs_hidden',
                'deadline_ad',
                'preview-deadline-bs',
                'preview-deadline-ad',
                null
            );
        }

        var ddVal = $('#double_dastur_bs').val();
        if (ddVal && ddVal !== lastDDastur) {
            lastDDastur = ddVal;
            syncBS(ddVal,
                'double_dastur_bs_hidden',
                'double_dastur_ad',
                'preview-double-dastur-bs',
                'preview-double-dastur-ad',
                ['preview-double-dastur-row', 'preview-double-dastur-ad-row']
            );
        }

    }, 200);

});
</script>

{{-- Isolated Open-category guard — runs after all other scripts, no IIFE dependency --}}
<script>
$(function () {
    var form = document.getElementById('vacancyForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        var cbOpen = document.getElementById('has_open');
        var cbInternal  = document.getElementById('has_internal');
        var cbAppraisal = document.getElementById('is_internal_appraisal');

        var isOpen      = cbOpen      && cbOpen.checked;
        var isInternal  = cbInternal  && cbInternal.checked;
        var isAppraisal = cbAppraisal && cbAppraisal.checked;

        if (!isOpen || isInternal || isAppraisal) return; // only validate for Open category

        var ddBS  = document.getElementById('double_dastur_bs_hidden');
        var ddFee = document.getElementById('double_dastur_fee');

        if (!ddBS || !ddBS.value) {
            e.preventDefault();
            alert('Double Dastur Date (Nepali BS) is required for Open category.\n\u0915\u0943\u092A\u092F\u093E \u0926\u094B\u0939\u094B\u0930\u094B \u0926\u0938\u094D\u0924\u0941\u0930 \u092E\u093F\u0924\u093F (\u0928\u0947\u092A\u093E\u0932\u0940) \u092D\u0930\u094D\u0928\u0941\u0939\u094B\u0938\u094D!');
            var bsInput = document.getElementById('double_dastur_bs');
            if (bsInput) bsInput.focus();
            return false;
        }

        if (!ddFee || !ddFee.value || parseFloat(ddFee.value) <= 0) {
            e.preventDefault();
            alert('Double Dastur Fee is required for Open category and must be greater than 0.\n\u0915\u0943\u092A\u092F\u093E \u0926\u094B\u0939\u094B\u0930\u094B \u0926\u0938\u094D\u0924\u0941\u0930 \u0936\u0941\u0932\u094D\u0915 \u092D\u0930\u094D\u0928\u0941\u0939\u094B\u0938\u094D!');
            if (ddFee) ddFee.focus();
            return false;
        }
    }, true); // capture phase — fires before other listeners
});
</script>
@endsection