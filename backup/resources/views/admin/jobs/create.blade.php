@extends('layouts.dashboard')

@section('title', 'Post New Vacancy')

@section('portal-name', 'Admin Portal')
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()?->name ?? 'Guest')
@section('user-role', 'System Administrator')
@section('user-initial', Auth::guard('admin')->user() ? strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) : 'G')
@section('logout-route', route('admin.logout'))

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
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid #f59e0b;
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

        /* Notice sub-section grouping */
        .notice-sub-section {
            margin-top: 1rem;
            /* background: #fafafa; */
            border-radius: 0 8px 8px 0;
            padding: 1.25rem 0 0.25rem 0;
        }

        .notice-sub-section-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #dc2626;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
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
                    <!-- <i class="bi bi-file-earmark-post-fill me-2"> -->

                    </i>Post New Vacancy
                </h3>
                <p class="mb-0 opacity-90">रिक्त पदको लागि विज्ञापन प्रकाशित गर्नुहोस्</p>
            </div>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-light btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <!-- Information Alert -->
    <div class="info-alert">
        <div class="d-flex align-items-start gap-3">
            <i class="bi bi-info-circle-fill text-warning fs-4"></i>
            <div>
                <strong>Important Notice:</strong> All fields marked with <span class="text-danger fw-bold">*</span> are
                mandatory.
                Please ensure all information is accurate before publishing. The Sr. No. will be auto-generated in the
                vacancy list.
                <br><small class="text-muted">सबै तारे चिन्ह (*) भएका फिल्डहरू अनिवार्य छन्।</small>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.jobs.store') }}" id="vacancyForm">
        @csrf

        <!-- Validation Errors Display -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Validation Errors
                </h5>
                <hr>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

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
                        <input type="text"
                            class="form-control form-control-lg @error('notice_no') is-invalid @enderror"
                            id="notice_no" name="notice_no"
                            value="{{ old('notice_no', $prefillNoticeNo ?? '') }}"
                            placeholder="e.g., 36/2082-83"
                            required>
                        @error('notice_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="section-divider"></div>

                    <!-- Fields grouped under Notice No. -->
                    <div class="notice-sub-section">
                        <div class="notice-sub-section-label">
                            Advertisement under this Notice No.
                            @if(!empty($prefillNoticeNo))
                                <span class="ms-2 badge bg-danger">{{ $prefillNoticeNo }}</span>
                            @endif
                        </div>

                        @if(!empty($prefillNoticeNo))
                            <div class="alert alert-warning py-2 px-3 mb-3" style="font-size:0.85rem;">
                                Adding another advertisement under <strong>Notice No. {{ $prefillNoticeNo }}</strong>.
                                Fill in the details for this new advertisement.
                            </div>
                        @endif

                        
                    <!-- Advertisement Number -->
                    <div class="mb-4">
                        <label for="advertisement_no" class="form-label">
                            <span>Advertisement No. <span class="required">*</span></span>
                            <span class="nepali-text">विज्ञापन नं.</span>
                        </label>
                        <input type="text"
                            class="form-control form-control-lg @error('advertisement_no') is-invalid @enderror"
                            id="advertisement_no" name="advertisement_no" value="{{ old('advertisement_no') }}"
                            placeholder="e.g., 01/2082-83" required>
                        @error('advertisement_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="section-divider"></div>

                    <!-- Position / Level (Text Inputs) -->
                    <div class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="position_input" class="form-label">
                                    <span>Position <span class="required">*</span></span>
                                    <span class="nepali-text">पद</span>
                                </label>
                                <input type="text"
                                    class="form-control form-control-lg @error('position') is-invalid @enderror"
                                    id="position_input"
                                    name="position"
                                    placeholder="e.g. Deputy Manager"
                                    value="{{ old('position') }}"
                                    required>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="level_input" class="form-label">
                                    <span>Level <span class="required">*</span></span>
                                    <span class="nepali-text">तह</span>
                                </label>
                                <input type="number"
                                    class="form-control form-control-lg @error('level') is-invalid @enderror"
                                    id="level_input"
                                    name="level"
                                    placeholder="e.g. 7"
                                    value="{{ old('level') }}"
                                    min="1" max="99"
                                    required>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Existing ads for this position/level -->
                    <div id="existing-ads-panel" style="display:none;" class="mt-2 mb-3">
                        <div class="alert alert-info py-2 px-3 mb-0" style="font-size:0.85rem;">
                            <strong>Existing advertisements for this position/level:</strong>
                            <div id="existing-ads-list" class="mt-1"></div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Service / Group -->
                    <div class="mb-4">
                        <label for="service_group" class="form-label">
                            <span>Service / Group <span class="required">*</span></span>
                            <span class="nepali-text">सेवा / समूह</span>
                        </label>
                        <input type="text"
                            class="form-control form-control-lg @error('service_group') is-invalid @enderror"
                            id="service_group" name="service_group"
                            value="{{ old('service_group') }}"
                            placeholder="e.g. Deputy Manager / Administration"
                            required>
                        @error('service_group')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="section-divider"></div>

                    <!-- Category Checkboxes - Hierarchical Structure -->
                    <div class="mb-4">
                        <label class="form-label">
                            <span>Category / Type <span class="required">*</span></span>
                            <span class="nepali-text">श्रेणी / प्रकार</span>
                        </label>

                        <div class="border rounded p-3 bg-light">
                            <!-- Open Category (Main) -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input category-checkbox"
                                           type="checkbox"
                                           id="has_open"
                                           value="1"
                                           {{ old('has_open') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="has_open">
                                        Open (खुल्ला)
                                    </label>
                                </div>
                            </div>

                            <!-- Inclusive Types (standalone — always visible, independent of Open) -->
                            <div class="mb-3" id="inclusiveTypesToggle">
                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="has_inclusive_toggle"
                                           value="1"
                                           {{ old('has_inclusive_toggle') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="has_inclusive_toggle">
                                        Inclusive Types: <small class="text-muted fw-normal">(समावेशी प्रकारहरू)</small>
                                    </label>
                                </div>

                                <!-- Individual Inclusive Type Checkboxes (shown when Inclusive Types is checked) -->
                                <div id="inclusiveTypesSection" style="display: none; margin-left: 60px; margin-top: 10px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input inclusive-type-checkbox"
                                                       type="checkbox"
                                                       id="incl_women"
                                                       name="inclusive_types[]"
                                                       value="Women"
                                                       {{ is_array(old('inclusive_types')) && in_array('Women', old('inclusive_types')) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="incl_women">
                                                    Women (महिला)
                                                </label>
                                            </div>

                                            <div class="form-check mb-2">
                                                <input class="form-check-input inclusive-type-checkbox"
                                                       type="checkbox"
                                                       id="incl_aj"
                                                       name="inclusive_types[]"
                                                       value="A.J"
                                                       {{ is_array(old('inclusive_types')) && in_array('A.J', old('inclusive_types')) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="incl_aj">
                                                    A.J (आ.ज / आदिवासी जनजाति)
                                                </label>
                                            </div>

                                            <div class="form-check mb-2">
                                                <input class="form-check-input inclusive-type-checkbox"
                                                       type="checkbox"
                                                       id="incl_madhesi"
                                                       name="inclusive_types[]"
                                                       value="Madhesi"
                                                       {{ is_array(old('inclusive_types')) && in_array('Madhesi', old('inclusive_types')) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="incl_madhesi">
                                                    Madhesi (मधेसी)
                                                </label>
                                            </div>

                                            <div class="form-check mb-2">
                                                <input class="form-check-input inclusive-type-checkbox"
                                                       type="checkbox"
                                                       id="incl_janajati"
                                                       name="inclusive_types[]"
                                                       value="Janajati"
                                                       {{ is_array(old('inclusive_types')) && in_array('Janajati', old('inclusive_types')) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="incl_janajati">
                                                    Janajati (जनजाति)
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input inclusive-type-checkbox"
                                                       type="checkbox"
                                                       id="incl_apanga"
                                                       name="inclusive_types[]"
                                                       value="Apanga"
                                                       {{ is_array(old('inclusive_types')) && in_array('Apanga', old('inclusive_types')) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="incl_apanga">
                                                    Apanga (अपाङ्ग)
                                                </label>
                                            </div>

                                            <div class="form-check mb-2">
                                                <input class="form-check-input inclusive-type-checkbox"
                                                       type="checkbox"
                                                       id="incl_dalit"
                                                       name="inclusive_types[]"
                                                       value="Dalit"
                                                       {{ is_array(old('inclusive_types')) && in_array('Dalit', old('inclusive_types')) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="incl_dalit">
                                                    Dalit (दलित)
                                                </label>
                                            </div>

                                            <div class="form-check mb-2">
                                                <input class="form-check-input inclusive-type-checkbox"
                                                       type="checkbox"
                                                       id="incl_pichadiyeko"
                                                       name="inclusive_types[]"
                                                       value="Pichadiyeko Chetra"
                                                       {{ is_array(old('inclusive_types')) && in_array('Pichadiyeko Chetra', old('inclusive_types')) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="incl_pichadiyeko">
                                                    Pichadiyeko Chetra (पिचडिएको क्षेत्र)
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    @error('inclusive_types')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Internal Category with Sub-categories -->
                            <div class="mb-3">
                                <!-- Level 1: Internal -->
                                <div class="form-check">
                                    <input class="form-check-input category-checkbox"
                                           type="checkbox"
                                           id="has_internal"
                                           value="1"
                                           {{ old('has_internal') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="has_internal">
                                        Internal (आन्तरिक परीक्षा)
                                    </label>
                                    <small class="d-block text-muted ms-4">For NOC employees only</small>
                                </div>

                                <!-- Level 2: Internal Open -->
                                <div id="internalOpenToggle" style="display: none; margin-left: 30px; margin-top: 10px;">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input internal-subcategory-checkbox"
                                               type="checkbox"
                                               id="has_internal_open"
                                               name="has_internal_open"
                                               value="1"
                                               {{ old('has_internal_open') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="has_internal_open">
                                            Internal Open (All NOC Staff)
                                        </label>
                                        <small class="d-block text-muted">Open for all NOC employees</small>
                                    </div>
                                </div>

                                <!-- Level 2: Internal Inclusive Types Toggle -->
                                <div id="internalInclusiveToggle" style="display: none; margin-left: 30px; margin-top: 10px;">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input internal-subcategory-checkbox"
                                               type="checkbox"
                                               id="has_internal_inclusive_toggle"
                                               value="1"
                                               {{ old('has_internal_inclusive') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="has_internal_inclusive_toggle">
                                            Internal Inclusive Types:
                                        </label>
                                        <small class="d-block text-muted">For NOC employees from inclusive categories</small>
                                    </div>
                                </div>

                                <!-- Level 3: Internal Inclusive Individual Types -->
                                <div id="internalInclusiveTypesSection" style="display: none; margin-left: 60px; margin-top: 10px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input internal-inclusive-type-checkbox"
                                                       type="checkbox"
                                                       id="internal_incl_women"
                                                       name="internal_inclusive_types[]"
                                                       value="Women"
                                                       {{ (is_array(old('internal_inclusive_types')) && in_array('Women', old('internal_inclusive_types'))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="internal_incl_women">
                                                    Women (महिला)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input internal-inclusive-type-checkbox"
                                                       type="checkbox"
                                                       id="internal_incl_aj"
                                                       name="internal_inclusive_types[]"
                                                       value="A.J"
                                                       {{ (is_array(old('internal_inclusive_types')) && in_array('A.J', old('internal_inclusive_types'))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="internal_incl_aj">
                                                    A.J (आ.ज / आदिवासी जनजाति)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input internal-inclusive-type-checkbox"
                                                       type="checkbox"
                                                       id="internal_incl_madhesi"
                                                       name="internal_inclusive_types[]"
                                                       value="Madhesi"
                                                       {{ (is_array(old('internal_inclusive_types')) && in_array('Madhesi', old('internal_inclusive_types'))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="internal_incl_madhesi">
                                                    Madhesi (मधेसी)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input internal-inclusive-type-checkbox"
                                                       type="checkbox"
                                                       id="internal_incl_janajati"
                                                       name="internal_inclusive_types[]"
                                                       value="Janajati"
                                                       {{ (is_array(old('internal_inclusive_types')) && in_array('Janajati', old('internal_inclusive_types'))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="internal_incl_janajati">
                                                    Janajati (जनजाति)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input internal-inclusive-type-checkbox"
                                                       type="checkbox"
                                                       id="internal_incl_apanga"
                                                       name="internal_inclusive_types[]"
                                                       value="Apanga"
                                                       {{ (is_array(old('internal_inclusive_types')) && in_array('Apanga', old('internal_inclusive_types'))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="internal_incl_apanga">
                                                    Apanga (अपाङ्ग)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input internal-inclusive-type-checkbox"
                                                       type="checkbox"
                                                       id="internal_incl_dalit"
                                                       name="internal_inclusive_types[]"
                                                       value="Dalit"
                                                       {{ (is_array(old('internal_inclusive_types')) && in_array('Dalit', old('internal_inclusive_types'))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="internal_incl_dalit">
                                                    Dalit (दलित)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input internal-inclusive-type-checkbox"
                                                       type="checkbox"
                                                       id="internal_incl_pichadiyeko"
                                                       name="internal_inclusive_types[]"
                                                       value="Pichadiyeko Chetra"
                                                       {{ (is_array(old('internal_inclusive_types')) && in_array('Pichadiyeko Chetra', old('internal_inclusive_types'))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="internal_incl_pichadiyeko">
                                                    Pichadiyeko Chetra (पिचडिएको क्षेत्र)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Internal Appraisal Category (Exclusive) -->
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_internal_appraisal"
                                           name="is_internal_appraisal"
                                           value="1"
                                           {{ old('is_internal_appraisal') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_internal_appraisal">
                                        Internal Appraisal (आन्तरिक बढुवा)
                                    </label>
                                </div>
                            </div>
                        </div>

                        @error('categories')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <!-- <small class="form-text">
                            <i class="bi bi-lightbulb me-1"></i>
                            Open is selected by default. Check inclusive types if specific groups can apply. Internal Appraisal is exclusive.
                        </small> -->

                        <!-- Hidden fields for backward compatibility -->
                        <input type="hidden" name="category" id="hidden_category" value="{{ old('category', 'open') }}">
                        <input type="hidden" name="has_open" id="hidden_has_open" value="0">
                        <input type="hidden" name="has_inclusive" id="has_inclusive" value="0">
                        <input type="hidden" name="has_internal" id="hidden_has_internal" value="0">
                        <input type="hidden" name="inclusive_type" id="hidden_inclusive_type" value="{{ old('inclusive_type') }}">
                        <input type="hidden" name="has_internal_inclusive" id="has_internal_inclusive" value="0">
                    </div>

                    <div class="section-divider"></div>

                    <!-- Demand Post (Number of Posts) - Dynamic per-type -->
                    <div class="mb-4" id="demand-posts-section">
                        <label class="form-label">
                            <span>Demand Post (Number) <span class="required">*</span></span>
                            <span class="nepali-text">माग पद संख्या</span>
                        </label>
                        <div id="demand-posts-container">
                            {{-- Default single field (shown when no type is selected) --}}
                            <div id="demand-default">
                                <input type="number"
                                    class="form-control form-control-lg @error('number_of_posts') is-invalid @enderror"
                                    id="number_of_posts_default"
                                    placeholder="Enter number of posts"
                                    value="{{ old('number_of_posts', 1) }}"
                                    min="1" max="1000">
                            </div>
                            {{-- Per-type demand rows are added here dynamically by JS --}}
                        </div>
                        {{-- Hidden field that gets submitted to the controller --}}
                        <input type="hidden" name="number_of_posts" id="number_of_posts" value="{{ old('number_of_posts', 1) }}">
                        @error('number_of_posts')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
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
                            placeholder="Example:&#10;• Bachelor's degree in relevant field from recognized university&#10;• मान्यता प्राप्त विश्वविद्यालयबाट सम्बन्धित विषयमा स्नातक उत्तीर्ण"
                            required>{{ old('minimum_qualification') }}</textarea>
                        @error('minimum_qualification')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <!-- <small class="form-text">
                            <i class="bi bi-lightbulb me-1"></i>Describe the minimum education, certificates, or degrees
                            required for this position
                        </small> -->
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
                                <input type="hidden" name="deadline_bs" id="deadline_bs_hidden">
                                <small class="form-text text-primary">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Click to open Nepali calendar
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
                                    value="{{ old('deadline', '') }}"
                                    required
                                    readonly>
                                <small class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>Auto-set to 21 days from today
                                </small>
                            </div>
                        </div>

                        @error('deadline')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <!-- <div class="alert alert-info mt-3 mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            <strong>Auto-calculated:</strong> Deadline automatically set to 21 days from posting date. You can modify if needed.
                            <br><small>स्वचालित गणना: समय सीमा स्वचालित रूपमा पोस्टिङ मितिबाट २१ दिनमा सेट गरिएको छ।</small>
                        </div> -->
                    </div>

                    <!-- Double Dastur Date - Dual Date Pickers -->
                    <div class="mb-4" id="doubleDasturDateSection">
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
                                <input type="hidden" name="double_dastur_bs" id="double_dastur_bs_hidden">
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
                                    value="{{ old('double_dastur_date', '') }}"
                                    readonly>
                                <small class="form-text text-success">
                                    <i class="bi bi-info-circle me-1"></i>Auto-set to 7 days after deadline (28 days total)
                                </small>
                            </div>
                        </div>

                        @error('double_dastur_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                            <!-- <div class="alert alert-success mt-3 mb-0">
                                <i class="bi bi-calendar-plus me-2"></i>
                                <strong>Extended Period:</strong> Double Dastur allows additional 7 days after the original deadline (total 28 days from posting).
                                <br><small>विस्तारित अवधि: दोहोरो दस्तुरले मूल समय सीमा पछि थप ७ दिन अनुमति दिन्छ।</small>
                            </div> -->
                    </div>

                    <div class="section-divider"></div>

                    <!-- Per-Category Fee Fields (dynamically rendered by JS) -->
                    <div id="individual-fees" class="mb-3"></div>

                    <!-- Total Application Fee & Double Dastur Fee -->
                    <div class="row mb-4">
                        <!-- Total Application Fee -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <span>Total Application Fee <span class="text-danger">*</span></span>
                                <span class="nepali-text">कुल आवेदन शुल्क</span>
                            </label>
                            <input type="number"
                                   class="form-control form-control-lg @error('application_fee') is-invalid @enderror"
                                   id="application_fee"
                                   name="application_fee"
                                   value="{{ old('application_fee') }}"
                                   placeholder="Total Application Fees"
                                   min="0"
                                   step="0.01"
                                   required
                                   readonly>
                            @error('application_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="fee-total-note">Select a category above to enter individual fees.</small>
                        </div>

                        <!-- Double Dastur Fee -->
                        <div class="col-md-6" id="doubleDasturFeeSection">
                            <label class="form-label">
                                <span>Double Dastur Fee<span class="text-danger">*</span></span>
                                <span class="nepali-text">दोहोरो दस्तुर</span>
                            </label>
                            <input type="number"
                                   class="form-control form-control-lg @error('double_dastur_fee') is-invalid @enderror"
                                   id="double_dastur_fee"
                                   name="double_dastur_fee"
                                   value="{{ old('double_dastur_fee') }}"
                                   placeholder="Enter Double Dastur Fee"
                                   min="0"
                                   step="0.01">
                            @error('double_dastur_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    </div>{{-- end .notice-sub-section --}}

                    <!-- Hidden fields for required database columns -->
                    <input type="hidden" name="title" id="hidden_title" value="">
                    <input type="hidden" name="location" value="Nepal">
                    <input type="hidden" name="description" id="hidden_description" value="">
                    <input type="hidden" name="requirements" id="hidden_requirements" value="">
                    <input type="hidden" name="status" value="draft">
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
                                <td id="preview-notice-no" class="fw-semibold">-</td>
                            </tr>
                            <tr>
                                <th>Advertisement No.</th>
                                <td id="preview-adv-no" class="fw-semibold">-</td>
                            </tr>
                            <tr>
                                <th>Position</th>
                                <td id="preview-position" class="fw-semibold">-</td>
                            </tr>
                            <tr>
                                <th>Level</th>
                                <td id="preview-level" class="fw-semibold">-</td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td id="preview-service" class="fw-semibold">-</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td id="preview-category" class="fw-semibold">-</td>
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
                                <td id="preview-posts" class="fw-semibold">-</td>
                            </tr>
                            <tr>
                                <th>Deadline (BS)</th>
                                <td id="preview-deadline-bs" class="fw-semibold text-danger">-</td>
                            </tr>
                            <tr>
                                <th>Deadline (AD)</th>
                                <td id="preview-deadline-ad" class="fw-semibold text-danger">-</td>
                            </tr>
                            <tr id="preview-double-dastur-row" style="display: none;">
                                <th>Double Dastur (BS)</th>
                                <td id="preview-double-dastur-bs" class="fw-semibold text-success">-</td>
                            </tr>
                            <tr id="preview-double-dastur-ad-row" style="display: none;">
                                <th>Double Dastur (AD)</th>
                                <td id="preview-double-dastur-ad" class="fw-semibold text-success">-</td>
                            </tr>
                            <tr id="preview-application-fee-row">
                                <th>Total Application Fee</th>
                                <td id="preview-application-fee" class="fw-semibold text-primary">NPR</td>
                            </tr>
                            <tr id="preview-double-dastur-fee-row" style="display: none;">
                                <th>Double Dastur Fee</th>
                                <td id="preview-double-dastur-fee" class="fw-semibold text-danger">NPR</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4 p-3 bg-white rounded border">
                        <h6 class="small fw-bold text-muted mb-2">
                            <i class="bi bi-mortarboard-fill me-1"></i>Min. Qualification
                        </h6>
                        <p id="preview-qualification" class="small mb-0 text-muted">
                            <em>Not yet entered...</em>
                        </p>
                    </div>

                    <!-- <div class="mt-4 p-3 bg-danger bg-opacity-10 rounded">
                        <h6 class="small fw-bold mb-2">
                            <i class="bi bi-info-circle-fill text-danger me-1"></i>Checklist
                        </h6>
                        <ul class="small mb-0 ps-3">
                            <li class="mb-1">✓ Unique advertisement number</li>
                            <li class="mb-1">✓ Clear position level</li>
                            <li class="mb-1">✓ Correct service/group</li>
                            <li class="mb-1">✓ Accurate post count</li>
                            <li>✓ Complete qualifications</li>
                        </ul>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary btn-lg">
                                Cancel
                            </a>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-warning btn-lg btn-action px-5"
                                    onclick="return confirmSaveDraft()">
                                    Save as Draft
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

    console.log('📝 === Date System Initializing ===');

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
        console.log('🔧 Initializing form...');


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

        // Auto-calculate dates (21 days for deadline, 28 days for double dastur)
        function setDefaultDates() {
            const today = new Date();

            // Deadline: 21 days from today
            const deadlineDate = new Date(today);
            deadlineDate.setDate(deadlineDate.getDate() + 21);
            const deadlineADFormatted = deadlineDate.toISOString().split('T')[0];

            // Double Dastur: 28 days from today (7 days after deadline)
            const doubleDasturDate = new Date(today);
            doubleDasturDate.setDate(doubleDasturDate.getDate() + 28);
            const doubleDasturADFormatted = doubleDasturDate.toISOString().split('T')[0];

            // Set AD dates if empty
            if (!deadlineAD.value) {
                deadlineAD.value = deadlineADFormatted;
                console.log('✅ Auto-set deadline to 21 days:', deadlineADFormatted);

                // Convert to BS and set
                const deadlineBSValue = window.adToBS(deadlineADFormatted);
                if (deadlineBSValue) {
                    const deadlineBSNepali = englishToNepali(deadlineBSValue);
                    $('#deadline_bs').val(deadlineBSNepali);
                    document.getElementById('deadline_bs_hidden').value = deadlineBSValue;

                    // Update preview
                    if (previewDeadlineBS) previewDeadlineBS.textContent = deadlineBSNepali + ' बि.सं.';
                    if (previewDeadlineAD) previewDeadlineAD.textContent = deadlineADFormatted;
                }
            }

            // Set Double Dastur dates if empty
            if (!doubleDasturAD.value) {
                doubleDasturAD.value = doubleDasturADFormatted;
                console.log('✅ Auto-set double dastur to 28 days (7 days after deadline):', doubleDasturADFormatted);

                // Convert to BS and set
                const doubleDasturBSValue = window.adToBS(doubleDasturADFormatted);
                if (doubleDasturBSValue) {
                    const doubleDasturBSNepali = englishToNepali(doubleDasturBSValue);
                    $('#double_dastur_bs').val(doubleDasturBSNepali);
                    document.getElementById('double_dastur_bs_hidden').value = doubleDasturBSValue;

                    // Update preview
                    if (previewDoubleDasturBS) {
                        previewDoubleDasturBS.textContent = doubleDasturBSNepali + ' बि.सं.';
                        previewDoubleDasturRow.style.display = '';
                    }
                    if (previewDoubleDasturAD) {
                        previewDoubleDasturAD.textContent = doubleDasturADFormatted;
                        previewDoubleDasturADRow.style.display = '';
                    }
                }
            }
        }

        // Call after converter is ready
        setTimeout(setDefaultDates, 600);

        // Initialize Nepali Date Pickers
        $('#deadline_bs').nepaliDatePicker({
            dateFormat: 'YYYY-MM-DD',
            unicodeDate: true
        });

        $('#double_dastur_bs').nepaliDatePicker({
            dateFormat: 'YYYY-MM-DD',
            unicodeDate: true
        });

        console.log('✅ Nepali Date Pickers initialized');

        // ============================================
        // WORKING SOLUTION: Use polling to detect changes
        // ============================================
        let lastBSValue = '';
        let lastDoubleDasturBSValue = '';

        const pollInterval = setInterval(function() {
            // Poll for Deadline BS changes
            const currentBSValue = $('#deadline_bs').val();

            // Check if value changed and is valid
            if (currentBSValue &&
                currentBSValue !== lastBSValue &&
                currentBSValue !== 'YYYY-MM-DD' &&
                currentBSValue.length >= 10) {

                console.log('📅 BS Date changed (polling detected):', currentBSValue);
                lastBSValue = currentBSValue;

                // Convert Nepali numerals to English for calculation
                const bsValueEnglish = nepaliToEnglish(currentBSValue);
                console.log('🔢 After numeral conversion:', bsValueEnglish);

                // Update hidden field with English numerals for database
                const hiddenField = document.getElementById('deadline_bs_hidden');
                if (hiddenField) {
                    hiddenField.value = bsValueEnglish;
                    console.log('✅ Hidden BS field updated:', bsValueEnglish);
                }

                // Convert BS to AD
                const adValue = window.bsToAD(bsValueEnglish);
                console.log('✅ AD Result:', adValue);

                if (adValue) {
                    // Update the English date field (this goes to database)
                    deadlineAD.value = adValue;
                    console.log('✅ English date field updated:', adValue);

                    // Update BS preview with Nepali numerals
                    if (previewDeadlineBS) {
                        // Convert back to Nepali numerals for display
                        const bsNepali = englishToNepali(bsValueEnglish);
                        previewDeadlineBS.textContent = bsNepali + ' बि.सं.';
                        console.log('✅ BS Preview:', bsNepali);
                    }

                    // Update AD preview in YYYY-MM-DD format
                    if (previewDeadlineAD) {
                        previewDeadlineAD.textContent = adValue; // Already in YYYY-MM-DD format
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

                // Update hidden field
                const ddHiddenField = document.getElementById('double_dastur_bs_hidden');
                if (ddHiddenField) {
                    ddHiddenField.value = ddBsValueEnglish;
                }

                // Convert BS to AD
                const ddAdValue = window.bsToAD(ddBsValueEnglish);
                console.log('✅ Double Dastur AD Result:', ddAdValue);

                if (ddAdValue) {
                    doubleDasturAD.value = ddAdValue;
                    console.log('✅ Double Dastur AD field updated:', ddAdValue);

                    // Update previews
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
        }, 200); // Check every 200ms

        // Initialize on page load
        setTimeout(function() {
            const existingBSValue = $('#deadline_bs').val();

            // If BS field already has a value (from old input), convert English numerals to Nepali
            if (existingBSValue && existingBSValue.match(/[0-9]/)) {
                console.log('📅 Converting existing Deadline BS to Nepali numerals:', existingBSValue);
                const bsNepali = englishToNepali(existingBSValue);
                $('#deadline_bs').val(bsNepali);
                lastBSValue = bsNepali;

                // Set hidden field with English numerals for database
                const hiddenField = document.getElementById('deadline_bs_hidden');
                if (hiddenField) {
                    hiddenField.value = existingBSValue;
                }

                console.log('✅ Deadline BS converted to Nepali:', bsNepali);

                // Also update AD field if empty
                if (!deadlineAD.value) {
                    const adValue = window.bsToAD(existingBSValue);
                    if (adValue) {
                        deadlineAD.value = adValue;
                    }
                }

                // Update previews
                if (previewDeadlineBS) {
                    previewDeadlineBS.textContent = bsNepali + ' बि.सं.';
                }
                if (previewDeadlineAD) {
                    previewDeadlineAD.textContent = deadlineAD.value;
                }
            }
            // If only AD value exists, convert to BS
            else if (deadlineAD.value && !existingBSValue) {
                console.log('📅 Initializing Deadline BS from existing AD date:', deadlineAD.value);

                const bsValue = window.adToBS(deadlineAD.value);
                console.log('✅ Initial BS (English numerals):', bsValue);

                if (bsValue) {
                    // Convert to Nepali numerals for display in picker
                    const bsNepali = englishToNepali(bsValue);

                    // Set the BS field with Nepali numerals
                    $('#deadline_bs').val(bsNepali);
                    lastBSValue = bsNepali;

                    // Set hidden field with English numerals for database
                    const hiddenField = document.getElementById('deadline_bs_hidden');
                    if (hiddenField) {
                        hiddenField.value = bsValue;
                    }

                    console.log('✅ Initial BS (Nepali numerals):', bsNepali);

                    // Update previews
                    if (previewDeadlineBS) {
                        previewDeadlineBS.textContent = bsNepali + ' बि.सं.';
                    }
                    if (previewDeadlineAD) {
                        previewDeadlineAD.textContent = deadlineAD.value; // Display as YYYY-MM-DD
                    }
                }
            }
        }, 500);

        console.log('✅ Date system ready (using polling method)!');

        // ============================================
        // REST OF FORM - Live Preview for other fields
        // ============================================
        // NOTE: Category checkbox logic has been moved outside initializeForm()
        // to run independently of Nepali date libraries (see initializeCategoryCheckboxes function)

        const previewMappings = {
            'notice_no': { preview: 'preview-notice-no', default: '-' },
            'advertisement_no': { preview: 'preview-adv-no', default: '-' },
            'service_group': { preview: 'preview-service', default: '-' },
            'minimum_qualification': { preview: 'preview-qualification', default: 'Not yet entered...' },
            'application_fee': { preview: 'preview-application-fee', default: '-' },
            'double_dastur_fee': { preview: 'preview-double-dastur-fee', default: '-' }
        };

        Object.keys(previewMappings).forEach(fieldId => {
            const input = document.getElementById(fieldId);
            const preview = document.getElementById(previewMappings[fieldId].preview);

            if (input && preview) {
                const eventType = input.tagName === 'SELECT' ? 'change' : 'input';

                input.addEventListener(eventType, function() {
                    const value = this.value.trim();
                    if (fieldId === 'minimum_qualification') {
                        preview.innerHTML = value ? value.replace(/\n/g, '<br>') : '<em>' + previewMappings[fieldId].default + '</em>';
                    } else if (fieldId === 'application_fee' || fieldId === 'double_dastur_fee') {
                        if (value && parseFloat(value) >= 0) {
                            const numValue = parseFloat(value);
                            // Only show decimals if they exist (not .00)
                            const formattedValue = numValue % 1 === 0
                                ? numValue.toLocaleString('en-NP', { minimumFractionDigits: 0, maximumFractionDigits: 0 })
                                : numValue.toLocaleString('en-NP', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            preview.textContent = 'NPR ' + formattedValue;
                        } else {
                            preview.textContent = 'NPR';
                        }
                    } else if (fieldId === 'notice_no') {
                        preview.textContent = value || '-';
                    } else {
                        preview.textContent = value || previewMappings[fieldId].default;
                    }
                });

                input.dispatchEvent(new Event(eventType));
            }
        });

        // Position + Level: update live preview on input
        function updatePositionLevel() {
            const pos = (document.getElementById('position_input').value || '').trim();
            const lvl = (document.getElementById('level_input').value || '').trim();
            const previewPos = document.getElementById('preview-position');
            const previewLvl = document.getElementById('preview-level');
            if (previewPos) previewPos.textContent = pos || '-';
            if (previewLvl) previewLvl.textContent = lvl || '-';
        }

        document.getElementById('position_input').addEventListener('input', updatePositionLevel);
        document.getElementById('level_input').addEventListener('input', updatePositionLevel);
        updatePositionLevel();

        // Existing ads lookup: show ads for same position+level
        var posLookupTimer = null;
        var lookupUrl = "{{ route('admin.jobs.lookupPosition') }}";

        function lookupExistingAds() {
            var pos = (document.getElementById('position_input').value || '').trim();
            var lvl = (document.getElementById('level_input').value || '').trim();
            var panel = document.getElementById('existing-ads-panel');
            var list  = document.getElementById('existing-ads-list');

            if (!pos && !lvl) { panel.style.display = 'none'; return; }

            clearTimeout(posLookupTimer);
            posLookupTimer = setTimeout(function () {
                var params = new URLSearchParams();
                if (pos) params.set('position', pos);
                if (lvl) params.set('level', lvl);

                fetch(lookupUrl + '?' + params.toString())
                    .then(function (r) { return r.json(); })
                    .then(function (ads) {
                        if (!ads.length) { panel.style.display = 'none'; return; }
                        var html = ads.map(function (ad) {
                            var typesStr = ad.types.length ? ad.types.join(', ') : '-';
                            var statusBadge = ad.status === 'active'
                                ? '<span style="color:#059669;font-weight:600;">Active</span>'
                                : ad.status === 'closed'
                                    ? '<span style="color:#dc2626;">Closed</span>'
                                    : '<span style="color:#6b7280;">Draft</span>';
                            return '<div style="padding:2px 0;">'
                                + '<strong>' + (ad.advertisement_no || '-') + '</strong>'
                                + ' &mdash; ' + (ad.position || '-') + (ad.level ? ' / Level ' + ad.level : '')
                                + (ad.service_group ? ' &mdash; ' + ad.service_group : '')
                                + ' &mdash; ' + typesStr
                                + ' &mdash; ' + statusBadge
                                + '</div>';
                        }).join('');
                        list.innerHTML = html;
                        panel.style.display = 'block';
                    })
                    .catch(function () { panel.style.display = 'none'; });
            }, 400);
        }

        document.getElementById('position_input').addEventListener('input', lookupExistingAds);
        document.getElementById('level_input').addEventListener('input', lookupExistingAds);

        // NOTE: Form submit handler has been moved to initializeCategoryCheckboxes()
        // which runs independently of Nepali date libraries

        console.log('✅ === ALL COMPLETE ===');
    }

    // CRITICAL FIX: Initialize category checkboxes immediately, don't wait for Nepali libraries
    // Date conversion waits for libraries, but category logic should work independently
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeCategoryCheckboxes);
    } else {
        initializeCategoryCheckboxes();
    }

    waitForConverter();
})();

// Initialize Category Checkboxes IMMEDIATELY (independent of date libraries)
function initializeCategoryCheckboxes() {
    console.log('✅ Initializing category checkboxes...');

    // NEW CHECKBOX-BASED CATEGORY LOGIC (Three-level hierarchy)
    const hasOpenCheckbox = document.getElementById('has_open');
    const hasInternalCheckbox = document.getElementById('has_internal');
    const isInternalAppraisalCheckbox = document.getElementById('is_internal_appraisal');
    const inclusiveTypesToggle = document.getElementById('inclusiveTypesToggle');
    const hasInclusiveToggleCheckbox = document.getElementById('has_inclusive_toggle');
    const inclusiveTypesSection = document.getElementById('inclusiveTypesSection');
    const inclusiveTypeCheckboxes = document.querySelectorAll('.inclusive-type-checkbox');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const previewInclusiveRow = document.getElementById('preview-inclusive-row');
    const previewInclusiveType = document.getElementById('preview-inclusive-type');
    const previewCategory = document.getElementById('preview-category');
    const hasInclusiveHidden = document.getElementById('has_inclusive');

    // Internal sub-category elements
    const internalOpenToggle = document.getElementById('internalOpenToggle');
    const hasInternalOpenCheckbox = document.getElementById('has_internal_open');
    const internalInclusiveToggle = document.getElementById('internalInclusiveToggle');
    const hasInternalInclusiveToggleCheckbox = document.getElementById('has_internal_inclusive_toggle');
    const internalInclusiveTypesSection = document.getElementById('internalInclusiveTypesSection');
    const internalInclusiveTypeCheckboxes = document.querySelectorAll('.internal-inclusive-type-checkbox');
    const hasInternalInclusiveHidden = document.getElementById('has_internal_inclusive');

    // Double Dastur elements (not applicable for Internal categories)
    const doubleDasturDateSection = document.getElementById('doubleDasturDateSection');
    const doubleDasturFeeSection = document.getElementById('doubleDasturFeeSection');
    const doubleDasturFeeInput = document.getElementById('double_dastur_fee');

    // Unified exclusivity sync — all 4 types are fully mutually exclusive
    function syncExclusivity() {
        var openChecked      = hasOpenCheckbox             && hasOpenCheckbox.checked;
        var internalChecked  = hasInternalCheckbox         && hasInternalCheckbox.checked;
        var appraisalChecked = isInternalAppraisalCheckbox && isInternalAppraisalCheckbox.checked;
        var inclChecked      = hasInclusiveToggleCheckbox  && hasInclusiveToggleCheckbox.checked;
        var anyChecked       = openChecked || internalChecked || appraisalChecked || inclChecked;

        function applyLock(cb, locked) {
            if (!cb) return;
            cb.disabled = locked;
            var fc = cb.closest('.form-check');
            if (fc) {
                fc.style.opacity = locked ? '0.45' : '';
                fc.style.cursor  = locked ? 'not-allowed' : '';
            }
        }

        applyLock(hasOpenCheckbox,             anyChecked && !openChecked);
        applyLock(hasInternalCheckbox,         anyChecked && !internalChecked);
        applyLock(isInternalAppraisalCheckbox, anyChecked && !appraisalChecked);
        applyLock(hasInclusiveToggleCheckbox,  anyChecked && !inclChecked);
    }

    function handleCategoryExclusivity(selectedCheckbox) {
        if (selectedCheckbox.checked) {
            // Uncheck all others and reset their sections
            if (selectedCheckbox === hasOpenCheckbox) {
                if (hasInternalCheckbox)         hasInternalCheckbox.checked         = false;
                if (isInternalAppraisalCheckbox) isInternalAppraisalCheckbox.checked = false;
                if (hasInclusiveToggleCheckbox)  { hasInclusiveToggleCheckbox.checked = false; inclusiveTypeCheckboxes.forEach(cb => { cb.checked = false; }); toggleInclusiveTypesSection(); }
                hideInternalSections();
                showDoubleDasturSections();
            } else if (selectedCheckbox === hasInternalCheckbox) {
                if (hasOpenCheckbox)             hasOpenCheckbox.checked             = false;
                if (isInternalAppraisalCheckbox) isInternalAppraisalCheckbox.checked = false;
                if (hasInclusiveToggleCheckbox)  { hasInclusiveToggleCheckbox.checked = false; inclusiveTypeCheckboxes.forEach(cb => { cb.checked = false; }); toggleInclusiveTypesSection(); }
                hideOpenSections();
                toggleInternalSubcategories();
                hideDoubleDasturSections();
            } else if (selectedCheckbox === isInternalAppraisalCheckbox) {
                if (hasOpenCheckbox)            hasOpenCheckbox.checked             = false;
                if (hasInternalCheckbox)        hasInternalCheckbox.checked         = false;
                if (hasInclusiveToggleCheckbox) { hasInclusiveToggleCheckbox.checked = false; inclusiveTypeCheckboxes.forEach(cb => { cb.checked = false; }); toggleInclusiveTypesSection(); }
                hideOpenSections();
                hideInternalSections();
                hideDoubleDasturSections();
            }
        } else {
            if (selectedCheckbox === hasOpenCheckbox) {
                hideOpenSections();
            } else if (selectedCheckbox === hasInternalCheckbox) {
                hideInternalSections();
            }
            showDoubleDasturSections();
        }
        syncExclusivity();
        updateCategoryDisplay();
    }

    // Hide Open sub-sections (inclusive is now independent — do not hide it here)
    function hideOpenSections() {
        // Inclusive Types is now independent of Open — nothing to hide here
    }

    // Hide Internal sub-sections
    function hideInternalSections() {
        if (internalOpenToggle) {
            internalOpenToggle.style.display = 'none';
        }
        if (internalInclusiveToggle) {
            internalInclusiveToggle.style.display = 'none';
        }
        if (internalInclusiveTypesSection) {
            internalInclusiveTypesSection.style.display = 'none';
        }
        if (hasInternalOpenCheckbox) {
            hasInternalOpenCheckbox.checked = false;
        }
        if (hasInternalInclusiveToggleCheckbox) {
            hasInternalInclusiveToggleCheckbox.checked = false;
        }
        internalInclusiveTypeCheckboxes.forEach(cb => cb.checked = false);
    }

    // Show Double Dastur sections (for Open category)
    function showDoubleDasturSections() {
        if (doubleDasturDateSection) {
            doubleDasturDateSection.style.display = 'block';
        }
        if (doubleDasturFeeSection) {
            doubleDasturFeeSection.style.display = 'block';
        }
        if (doubleDasturFeeInput) {
            doubleDasturFeeInput.removeAttribute('disabled');
        }
        var previewDDFeeRow = document.getElementById('preview-double-dastur-fee-row');
        if (previewDDFeeRow) previewDDFeeRow.style.display = '';
    }

    // Hide Double Dastur sections (for Internal and Internal Appraisal)
    function hideDoubleDasturSections() {
        if (doubleDasturDateSection) {
            doubleDasturDateSection.style.display = 'none';
        }
        if (doubleDasturFeeSection) {
            doubleDasturFeeSection.style.display = 'none';
        }
        if (doubleDasturFeeInput) {
            doubleDasturFeeInput.value = '0'; // Set to 0 for internal categories
            doubleDasturFeeInput.disabled = true;
        }
        var previewDDFeeRow = document.getElementById('preview-double-dastur-fee-row');
        if (previewDDFeeRow) previewDDFeeRow.style.display = 'none';
    }

    // Inclusive Types toggle is now always visible — no toggling needed
    function toggleInclusiveTypesToggle() { /* no-op: inclusive is always visible */ }

    // Show/Hide Individual Inclusive Types (when Inclusive Types checkbox is checked)
    function toggleInclusiveTypesSection() {
        if (hasInclusiveToggleCheckbox && hasInclusiveToggleCheckbox.checked) {
            inclusiveTypesSection.style.display = 'block';
        } else {
            inclusiveTypesSection.style.display = 'none';
            // Uncheck all individual types so they don't submit with the form
            inclusiveTypeCheckboxes.forEach(cb => cb.checked = false);
        }
    }

    // Show/Hide Internal Sub-categories (when Internal is checked)
    function toggleInternalSubcategories() {
        if (hasInternalCheckbox && hasInternalCheckbox.checked &&
            !(isInternalAppraisalCheckbox && isInternalAppraisalCheckbox.checked)) {
            internalOpenToggle.style.display = 'block';
            internalInclusiveToggle.style.display = 'block';
        } else {
            internalOpenToggle.style.display = 'none';
            internalInclusiveToggle.style.display = 'none';
            internalInclusiveTypesSection.style.display = 'none';
            if (hasInternalOpenCheckbox) {
                hasInternalOpenCheckbox.checked = false;
            }
            if (hasInternalInclusiveToggleCheckbox) {
                hasInternalInclusiveToggleCheckbox.checked = false;
            }
            // Uncheck all internal inclusive types
            internalInclusiveTypeCheckboxes.forEach(cb => cb.checked = false);
        }
        updateHasInternalInclusiveHidden();
    }

    // Show/Hide Internal Inclusive Individual Types (when Internal Inclusive Types checkbox is checked)
    function toggleInternalInclusiveTypesSection() {
        if (hasInternalInclusiveToggleCheckbox && hasInternalInclusiveToggleCheckbox.checked) {
            internalInclusiveTypesSection.style.display = 'block';
        } else {
            internalInclusiveTypesSection.style.display = 'none';
            // Uncheck all types when hiding
            internalInclusiveTypeCheckboxes.forEach(cb => cb.checked = false);
        }
        updateHasInternalInclusiveHidden();
    }

    // Update has_internal_inclusive hidden field
    function updateHasInternalInclusiveHidden() {
        if (hasInternalInclusiveToggleCheckbox && hasInternalInclusiveToggleCheckbox.checked) {
            hasInternalInclusiveHidden.value = '1';
        } else {
            hasInternalInclusiveHidden.value = '0';
        }
    }

    // Update Category Preview
    function updateCategoryDisplay() {
        if (!previewCategory) return;

        const categories = [];

        if (isInternalAppraisalCheckbox && isInternalAppraisalCheckbox.checked) {
            categories.push('<span class="badge bg-danger">(Internal Appraisal)</span>');
        } else {
            if (hasOpenCheckbox && hasOpenCheckbox.checked) {
                categories.push('<span class="badge bg-success">खुल्ला (Open)</span>');
            }
            if (hasInternalCheckbox && hasInternalCheckbox.checked) {
                categories.push('<span class="badge bg-warning text-dark">आन्तरिक (Internal)</span>');
            }
            if (!hasOpenCheckbox?.checked && !hasInternalCheckbox?.checked &&
                hasInclusiveToggleCheckbox && hasInclusiveToggleCheckbox.checked) {
                categories.push('<span class="badge bg-info text-dark">समावेशी (Inclusive)</span>');
            }
        }

        previewCategory.innerHTML = categories.length > 0 ? categories.join(' ') : '-';

        // Update inclusive types preview
        updateInclusiveTypesPreview();
        // Update internal sub-categories preview
        updateInternalSubcategoriesPreview();
    }

    // Update Inclusive Types Preview
    function updateInclusiveTypesPreview() {
        if (!previewInclusiveType || !previewInclusiveRow) return;

        const checkedTypes = Array.from(inclusiveTypeCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        if (hasInclusiveToggleCheckbox && hasInclusiveToggleCheckbox.checked &&
            checkedTypes.length > 0) {
            previewInclusiveType.textContent = checkedTypes.join(', ');
            previewInclusiveRow.style.display = '';
            if (hasInclusiveHidden) hasInclusiveHidden.value = '1';
        } else {
            previewInclusiveRow.style.display = 'none';
            if (hasInclusiveHidden) hasInclusiveHidden.value = '0';
        }
    }

    // Update Internal Sub-categories Preview
    function updateInternalSubcategoriesPreview() {
        const previewInternalSubcategoryRow = document.getElementById('preview-internal-subcategory-row');
        const previewInternalSubcategory = document.getElementById('preview-internal-subcategory');

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

    // Validate at least one category is selected
    function validateCategories() {
        const isOpen      = hasOpenCheckbox && hasOpenCheckbox.checked;
        const isInclusive = hasInclusiveToggleCheckbox && hasInclusiveToggleCheckbox.checked;
        const isInternal  = hasInternalCheckbox && hasInternalCheckbox.checked;
        const isAppraisal = isInternalAppraisalCheckbox && isInternalAppraisalCheckbox.checked;

        // Must select at least one main category
        if (!isOpen && !isInclusive && !isInternal && !isAppraisal) {
            alert('कृपया कम्तिमा एक मुख्य श्रेणी छान्नुहोस्!\nPlease select at least one category (Open, Inclusive Types, Internal, or Internal Appraisal)!');
            return false;
        }

        // Open category: Double Dastur Date and Fee are required
        if (isOpen) {
            const ddBS = document.getElementById('double_dastur_bs_hidden');
            if (!ddBS || !ddBS.value) {
                alert('Double Dastur Date (Nepali BS) is required for Open category.\nकृपया दोहोरो दस्तुर मिति (नेपाली) भर्नुहोस्!');
                document.getElementById('double_dastur_bs').focus();
                return false;
            }
            const ddFee = document.getElementById('double_dastur_fee');
            if (!ddFee || !ddFee.value || parseFloat(ddFee.value) <= 0) {
                alert('Double Dastur Fee is required for Open category and must be greater than 0.\nकृपया दोहोरो दस्तुर शुल्क भर्नुहोस्!');
                ddFee && ddFee.focus();
                return false;
            }
        }

        // If Inclusive Types toggle is checked, at least one type must be selected
        if (hasInclusiveToggleCheckbox && hasInclusiveToggleCheckbox.checked) {
            const anyInclusiveTypeChecked = Array.from(inclusiveTypeCheckboxes).some(cb => cb.checked);
            if (!anyInclusiveTypeChecked) {
                alert('कृपया कम्तिमा एक समावेशी प्रकार छान्नुहोस्!\nPlease select at least one inclusive type!');
                return false;
            }
        }

        // If Internal is checked, at least one sub-category must be selected
        if (hasInternalCheckbox && hasInternalCheckbox.checked) {
            const hasInternalOpen = hasInternalOpenCheckbox && hasInternalOpenCheckbox.checked;
            const hasInternalInclusive = hasInternalInclusiveToggleCheckbox && hasInternalInclusiveToggleCheckbox.checked;

            if (!hasInternalOpen && !hasInternalInclusive) {
                alert('कृपया आन्तरिक श्रेणीको लागि कम्तिमा एक उप-श्रेणी छान्नुहोस्!\nPlease select at least one sub-category for Internal!');
                return false;
            }

            // If Internal Inclusive Types toggle is checked, at least one type must be selected
            if (hasInternalInclusive) {
                const anyInternalInclusiveTypeChecked = Array.from(internalInclusiveTypeCheckboxes).some(cb => cb.checked);
                if (!anyInternalInclusiveTypeChecked) {
                    alert('कृपया कम्तिमा एक आन्तरिक समावेशी प्रकार छान्नुहोस्!\nPlease select at least one internal inclusive type!');
                    return false;
                }
            }
        }

        return true;
    }

    // Event Listeners for Main Categories (Mutually Exclusive)
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

    // Event Listeners for Sub-categories
    if (hasInclusiveToggleCheckbox) {
        hasInclusiveToggleCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // Inclusive is mutually exclusive with all others — uncheck and reset them
                if (hasOpenCheckbox)             { hasOpenCheckbox.checked             = false; hideOpenSections(); }
                if (hasInternalCheckbox)         { hasInternalCheckbox.checked         = false; hideInternalSections(); }
                if (isInternalAppraisalCheckbox) { isInternalAppraisalCheckbox.checked = false; }
                hideDoubleDasturSections();
            } else {
                inclusiveTypeCheckboxes.forEach(cb => { cb.checked = false; });
                showDoubleDasturSections();
            }
            toggleInclusiveTypesSection();
            updateInclusiveTypesPreview();
            syncExclusivity();
            updateCategoryDisplay();
        });
    }

    // Internal sub-category checkboxes
    if (hasInternalOpenCheckbox) {
        hasInternalOpenCheckbox.addEventListener('change', updateCategoryDisplay);
    }

    if (hasInternalInclusiveToggleCheckbox) {
        hasInternalInclusiveToggleCheckbox.addEventListener('change', function() {
            toggleInternalInclusiveTypesSection();
            updateInternalSubcategoriesPreview();
        });
    }

    // Internal inclusive type checkboxes
    internalInclusiveTypeCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            updateHasInternalInclusiveHidden();
            updateInternalSubcategoriesPreview();
        });
    });

    // Inclusive type checkboxes
    inclusiveTypeCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateInclusiveTypesPreview);
    });

    // Initialize on page load
    if (hasOpenCheckbox && hasOpenCheckbox.checked) {
        hideInternalSections();
        toggleInclusiveTypesSection();
        showDoubleDasturSections();
    } else if (hasInternalCheckbox && hasInternalCheckbox.checked) {
        hideOpenSections();
        toggleInternalSubcategories();
        toggleInternalInclusiveTypesSection();
        hideDoubleDasturSections();
    } else if (isInternalAppraisalCheckbox && isInternalAppraisalCheckbox.checked) {
        hideOpenSections();
        hideInternalSections();
        hideDoubleDasturSections();
    } else if (hasInclusiveToggleCheckbox && hasInclusiveToggleCheckbox.checked) {
        toggleInclusiveTypesSection();
        hideDoubleDasturSections();
    } else {
        toggleInclusiveTypesSection();
        toggleInternalSubcategories();
        toggleInternalInclusiveTypesSection();
    }
    syncExclusivity();
    updateCategoryDisplay();

    // FORM SUBMIT - Updated for hierarchical categories (Inclusive under Open)
    const form = document.getElementById('vacancyForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('📋 FORM SUBMIT - Checkbox States:');
            console.log('has_open checked:', hasOpenCheckbox?.checked);
            console.log('has_inclusive_toggle checked:', hasInclusiveToggleCheckbox?.checked);

            // Validate categories
            if (!validateCategories()) {
                e.preventDefault();
                return false;
            }

            // Ensure number_of_posts hidden field reflects current state
            if (typeof updateDemandTotal === 'function') updateDemandTotal();

            // CRITICAL: Update ALL hidden fields before submission
            document.getElementById('hidden_has_open').value = (hasOpenCheckbox && hasOpenCheckbox.checked) ? '1' : '0';
            document.getElementById('hidden_has_internal').value = (hasInternalCheckbox && hasInternalCheckbox.checked) ? '1' : '0';
            const hasIncTypes = Array.from(inclusiveTypeCheckboxes).some(cb => cb.checked);
            document.getElementById('has_inclusive').value = (hasInclusiveToggleCheckbox && hasInclusiveToggleCheckbox.checked && hasIncTypes) ? '1' : '0';

            // Update title, description, requirements
            const pos = (document.getElementById('position_input').value || '').trim();
            const lvl = (document.getElementById('level_input').value || '').trim();
            const positionLevel = lvl ? pos + ' - ' + lvl : pos;
            document.getElementById('hidden_title').value = pos;
            document.getElementById('hidden_description').value = 'Position: ' + positionLevel + '\nPosts: ' + document.getElementById('number_of_posts').value;
            document.getElementById('hidden_requirements').value = document.getElementById('minimum_qualification').value;

            // Update category: appraisal → internal → open+inclusive → inclusive-only → open
            if (isInternalAppraisalCheckbox && isInternalAppraisalCheckbox.checked) {
                document.getElementById('hidden_category').value = 'internal_appraisal';
            } else if (hasInternalCheckbox && hasInternalCheckbox.checked) {
                document.getElementById('hidden_category').value = 'internal';
            } else if (hasOpenCheckbox && hasOpenCheckbox.checked) {
                document.getElementById('hidden_category').value = 'open';
            } else {
                // Inclusive-only vacancy
                document.getElementById('hidden_category').value = 'inclusive';
            }

            console.log('✅ ALL FIELDS SET - SUBMITTING');
            return true;
        });
    }

    // ============================================
    // DEMAND POSTS — Dynamic per-type fields
    // ============================================

    var demandTypeMap = {
        'has_open':                'Open (खुल्ला)',
        'incl_women':              'Women (महिला)',
        'incl_aj':                 'A.J (आ.ज)',
        'incl_madhesi':            'Madhesi (मधेसी)',
        'incl_janajati':           'Janajati (जनजाति)',
        'incl_apanga':             'Apanga (अपाङ्ग)',
        'incl_dalit':              'Dalit (दलित)',
        'incl_pichadiyeko':        'Pichadiyeko Chetra (पिचडिएको क्षेत्र)',
        'has_internal_open':       'Internal Open',
        'internal_incl_women':     'Internal / Women (महिला)',
        'internal_incl_aj':        'Internal / A.J (आ.ज)',
        'internal_incl_madhesi':   'Internal / Madhesi (मधेसी)',
        'internal_incl_janajati':  'Internal / Janajati (जनजाति)',
        'internal_incl_apanga':    'Internal / Apanga (अपाङ्ग)',
        'internal_incl_dalit':     'Internal / Dalit (दलित)',
        'internal_incl_pichadiyeko': 'Internal / Pichadiyeko Chetra',
        'is_internal_appraisal':   'Internal Appraisal (आन्तरिक बढुवा)'
    };

    var demandPostsContainer = document.getElementById('demand-posts-container');
    var demandDefaultRow     = document.getElementById('demand-default');
    var demandDefaultInput   = document.getElementById('number_of_posts_default');
    var numberOfPostsHidden  = document.getElementById('number_of_posts');

    function updateDemandFields() {
        if (!demandPostsContainer || !demandDefaultRow) return;

        // Collect checked type IDs in map order
        var checkedTypes = Object.keys(demandTypeMap).filter(function(id) {
            var cb = document.getElementById(id);
            return cb && cb.checked;
        });

        if (checkedTypes.length === 0) {
            // Show the generic single field
            demandDefaultRow.style.display = '';
            demandPostsContainer.querySelectorAll('.demand-type-row').forEach(function(el) { el.remove(); });
            if (numberOfPostsHidden) {
                numberOfPostsHidden.value = demandDefaultInput ? (parseInt(demandDefaultInput.value) || 1) : 1;
            }
        } else {
            // Hide generic field, show per-type rows
            demandDefaultRow.style.display = 'none';

            // Remove rows for unchecked types
            demandPostsContainer.querySelectorAll('.demand-type-row').forEach(function(row) {
                if (!checkedTypes.includes(row.getAttribute('data-type-id'))) {
                    row.remove();
                }
            });

            // Add rows for newly checked types (preserving map order)
            checkedTypes.forEach(function(typeId) {
                if (!demandPostsContainer.querySelector('[data-type-id="' + typeId + '"]')) {
                    var label = demandTypeMap[typeId];
                    var row = document.createElement('div');
                    row.className = 'demand-type-row mb-2';
                    row.setAttribute('data-type-id', typeId);
                    row.innerHTML =
                        '<div class="input-group">' +
                        '<span class="input-group-text fw-semibold" style="min-width:220px;background:#f9fafb;font-size:0.875rem;">' + label + '</span>' +
                        '<input type="number" class="form-control form-control-lg demand-type-input" name="demand_posts[' + typeId + ']" value="1" min="1" max="1000" placeholder="Posts">' +
                        '</div>';
                    demandPostsContainer.appendChild(row);
                    row.querySelector('.demand-type-input').addEventListener('input', updateDemandTotal);
                }
            });

            updateDemandTotal();
        }

        _updateDemandPreview();
    }

    function updateDemandTotal() {
        var total = 0;
        demandPostsContainer.querySelectorAll('.demand-type-input').forEach(function(input) {
            total += parseInt(input.value) || 0;
        });
        if (numberOfPostsHidden) {
            numberOfPostsHidden.value = total > 0 ? total : 1;
        }
        _updateDemandPreview();
    }

    function _updateDemandPreview() {
        var previewPosts = document.getElementById('preview-posts');
        if (!previewPosts) return;

        var typeRows = demandPostsContainer ? demandPostsContainer.querySelectorAll('.demand-type-row') : [];

        if (typeRows.length === 0) {
            previewPosts.textContent = demandDefaultInput ? (demandDefaultInput.value || '-') : '-';
        } else {
            var lines = [];
            typeRows.forEach(function(row) {
                var typeId = row.getAttribute('data-type-id');
                var val = row.querySelector('.demand-type-input').value || '0';
                lines.push(demandTypeMap[typeId] + ': ' + val);
            });
            var total = parseInt(numberOfPostsHidden ? numberOfPostsHidden.value : 0) || 0;
            previewPosts.innerHTML = lines.join('<br>');
        }
    }

    // Hook updateDemandFields into all relevant checkbox events
    [hasOpenCheckbox, hasInternalCheckbox, isInternalAppraisalCheckbox].forEach(function(cb) {
        if (cb) cb.addEventListener('change', updateDemandFields);
    });
    if (hasInclusiveToggleCheckbox)          hasInclusiveToggleCheckbox.addEventListener('change', updateDemandFields);
    if (hasInternalOpenCheckbox)             hasInternalOpenCheckbox.addEventListener('change', updateDemandFields);
    if (hasInternalInclusiveToggleCheckbox)  hasInternalInclusiveToggleCheckbox.addEventListener('change', updateDemandFields);
    inclusiveTypeCheckboxes.forEach(function(cb) { cb.addEventListener('change', updateDemandFields); });
    internalInclusiveTypeCheckboxes.forEach(function(cb) { cb.addEventListener('change', updateDemandFields); });

    // Default input: keep hidden field and preview in sync
    if (demandDefaultInput) {
        demandDefaultInput.addEventListener('input', function() {
            if (numberOfPostsHidden) numberOfPostsHidden.value = parseInt(this.value) || 1;
            _updateDemandPreview();
        });
    }

    // Initialize demand fields on page load
    updateDemandFields();

    console.log('✅ Category checkboxes initialized successfully!');
}

function confirmSaveDraft() {
    return confirm(
        'Save this advertisement as draft?\n\n' +
        'The vacancy will be saved as draft and will NOT be visible to candidates until you change the status to "Active".'
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

// ── Category Fee Management ──────────────────────────────────────────────────
;(function () {
    var existingFees = @json(old('category_fees', []));

    var feeLabels = {
        'open':                              { en: 'Open Application Fee',                    np: 'खुल्ला आवेदन शुल्क' },
        'inclusive_Women':                   { en: 'Inclusive (Women) Fee',                   np: 'समावेशी (महिला) शुल्क' },
        'inclusive_A.J':                     { en: 'Inclusive (A.J) Fee',                     np: 'समावेशी (आ.ज.) शुल्क' },
        'inclusive_Madhesi':                 { en: 'Inclusive (Madhesi) Fee',                 np: 'समावेशी (मधेसी) शुल्क' },
        'inclusive_Janajati':                { en: 'Inclusive (Janajati) Fee',                np: 'समावेशी (जनजाति) शुल्क' },
        'inclusive_Apanga':                  { en: 'Inclusive (Apanga) Fee',                  np: 'समावेशी (अपांग) शुल्क' },
        'inclusive_Dalit':                   { en: 'Inclusive (Dalit) Fee',                   np: 'समावेशी (दलित) शुल्क' },
        'inclusive_Pichadiyeko_Chetra':      { en: 'Inclusive (Pichadiyeko Chetra) Fee',      np: 'समावेशी (पिछडिएको) शुल्क' },
        'internal_open':                     { en: 'Internal Open Fee',                       np: 'आन्तरिक खुल्ला शुल्क' },
        'internal_inclusive_Women':          { en: 'Internal Inclusive (Women) Fee',          np: 'आन्तरिक समावेशी (महिला) शुल्क' },
        'internal_inclusive_A.J':            { en: 'Internal Inclusive (A.J) Fee',            np: 'आन्तरिक समावेशी (आ.ज.) शुल्क' },
        'internal_inclusive_Madhesi':        { en: 'Internal Inclusive (Madhesi) Fee',        np: 'आन्तरिक समावेशी (मधेसी) शुल्क' },
        'internal_inclusive_Janajati':       { en: 'Internal Inclusive (Janajati) Fee',       np: 'आन्तरिक समावेशी (जनजाति) शुल्क' },
        'internal_inclusive_Apanga':         { en: 'Internal Inclusive (Apanga) Fee',         np: 'आन्तरिक समावेशी (अपांग) शुल्क' },
        'internal_inclusive_Dalit':          { en: 'Internal Inclusive (Dalit) Fee',          np: 'आन्तरिक समावेशी (दलित) शुल्क' },
        'internal_inclusive_Pichadiyeko_Chetra': { en: 'Internal Inclusive (Pichadiyeko Chetra) Fee', np: 'आन्तरिक समावेशी (पिछडिएको) शुल्क' },
    };

    function feeKey(prefix, value) {
        return prefix + value.replace(/\s+/g, '_');
    }

    function getFeeKeys() {
        var cbOpen      = document.getElementById('has_open');
        var cbInternal  = document.getElementById('has_internal');
        var cbAppraisal = document.getElementById('is_internal_appraisal');
        var cbInclToggle     = document.getElementById('has_inclusive_toggle');
        var cbIntOpenCb      = document.getElementById('has_internal_open');
        var cbIntInclToggle  = document.getElementById('has_internal_inclusive_toggle');

        var isOpen      = cbOpen      && cbOpen.checked;
        var isInternal  = cbInternal  && cbInternal.checked;
        var isAppraisal = cbAppraisal && cbAppraisal.checked;

        if (isAppraisal) return null; // special: direct input

        var keys = [];

        if (isOpen) {
            keys.push('open');
        }

        if (cbInclToggle && cbInclToggle.checked) {
            document.querySelectorAll('.inclusive-type-checkbox:checked').forEach(function (cb) {
                keys.push(feeKey('inclusive_', cb.value));
            });
        }

        if (isInternal) {
            if (cbIntOpenCb && cbIntOpenCb.checked) keys.push('internal_open');
            if (cbIntInclToggle && cbIntInclToggle.checked) {
                document.querySelectorAll('.internal-inclusive-type-checkbox:checked').forEach(function (cb) {
                    keys.push(feeKey('internal_inclusive_', cb.value));
                });
            }
        }

        return keys;
    }

    function updateFeeFields() {
        var container    = document.getElementById('individual-fees');
        var totalInput   = document.getElementById('application_fee');
        var note         = document.getElementById('fee-total-note');
        var cbAppraisal  = document.getElementById('is_internal_appraisal');
        var isAppraisal  = cbAppraisal && cbAppraisal.checked;

        if (!container || !totalInput) return;

        if (isAppraisal) {
            container.innerHTML = '';
            totalInput.readOnly = false;
            totalInput.placeholder = 'Enter Application Fee';
            if (note) note.textContent = 'Enter the application fee directly for Internal Appraisal.';
            return;
        }

        var keys = getFeeKeys();

        if (!keys || keys.length === 0) {
            container.innerHTML = '';
            totalInput.readOnly = true;
            totalInput.value = '';
            totalInput.placeholder = 'Total Application Fee';
            if (note) note.textContent = 'Select a category above to enter individual fees.';
            return;
        }

        totalInput.readOnly = true;
        if (note) note.textContent = 'Auto-calculated from individual fees above.';

        // Save current typed values before re-render
        var saved = {};
        container.querySelectorAll('.category-fee-input').forEach(function (inp) {
            if (inp.value !== '') saved[inp.dataset.feeKey] = inp.value;
        });

        container.innerHTML = '';

        keys.forEach(function (key) {
            var info = feeLabels[key] || { en: key, np: '' };
            var val  = saved[key] !== undefined ? saved[key]
                     : (existingFees[key] !== undefined ? existingFees[key] : '');

            var div = document.createElement('div');
            div.className = 'fee-type-row mb-2';
            div.innerHTML =
                '<div class="input-group">' +
                    '<span class="input-group-text fw-semibold" style="min-width:220px;background:#f9fafb;font-size:0.875rem;">' +
                        info.en +
                    '</span>' +
                    '<input type="number" ' +
                           'class="form-control form-control-lg category-fee-input" ' +
                           'name="category_fees[' + key + ']" ' +
                           'data-fee-key="' + key + '" ' +
                           'value="' + val + '" ' +
                           'placeholder="Enter amount (NPR)" ' +
                           'min="0" step="0.01">' +
                '</div>';
            container.appendChild(div);
        });

        container.querySelectorAll('.category-fee-input').forEach(function (inp) {
            inp.addEventListener('input', recalculateTotal);
        });

        recalculateTotal();
    }

    function recalculateTotal() {
        var totalInput = document.getElementById('application_fee');
        if (!totalInput || !totalInput.readOnly) return;

        var sum = 0;
        document.querySelectorAll('.category-fee-input').forEach(function (inp) {
            sum += parseFloat(inp.value) || 0;
        });

        totalInput.value = sum > 0 ? sum : '';

        var previewEl = document.getElementById('preview-application-fee');
        if (previewEl) {
            previewEl.textContent = sum > 0 ? 'NPR ' + sum.toLocaleString() : '-';
        }
    }

    // Listen for Total Application Fee direct input (Internal Appraisal)
    document.addEventListener('input', function (e) {
        if (e.target && e.target.id === 'application_fee' && !e.target.readOnly) {
            var previewEl = document.getElementById('preview-application-fee');
            if (previewEl) {
                var val = parseFloat(e.target.value) || 0;
                previewEl.textContent = val > 0 ? 'NPR ' + val.toLocaleString() : '-';
            }
        }
    });

    // Trigger on any category/type checkbox change
    var watchIds = ['has_open','has_internal','is_internal_appraisal',
                    'has_inclusive_toggle','has_internal_open','has_internal_inclusive_toggle'];
    var watchClasses = ['inclusive-type-checkbox','internal-inclusive-type-checkbox',
                        'category-checkbox','internal-subcategory-checkbox'];

    document.addEventListener('change', function (e) {
        var el = e.target;
        var relevant = watchIds.indexOf(el.id) !== -1 ||
                       watchClasses.some(function (c) { return el.classList.contains(c); });
        if (relevant) setTimeout(updateFeeFields, 60);
    });

    // Init on load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updateFeeFields);
    } else {
        setTimeout(updateFeeFields, 150);
    }
})();
</script>
@endsection