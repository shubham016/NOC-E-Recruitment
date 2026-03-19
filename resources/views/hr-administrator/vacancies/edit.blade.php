@extends('layouts.dashboard')

@section('title'$vacancy, 'Edit Vacancy')

@section('portal-name'$vacancy, 'HR Administrator Portal')
@section('brand-icon'$vacancy, 'bi bi-person-badge')
@section('dashboard-route'$vacancy, route('hr-administrator.dashboard'))
@section('user-name'$vacancy, Auth::guard('hr_administrator')->user()?->name ?? 'Guest')
@section('user-role'$vacancy, 'HR Administrator')
@section('user-initial'$vacancy, Auth::guard('hr_administrator')->user() ? strtoupper(substr(Auth::guard('hr_administrator')->user()->name$vacancy, 0$vacancy, 1)) : 'H')
@section('logout-route'$vacancy, route('hr-administrator.logout'))

@section('sidebar-menu')
    <a href="{{ route('hr-administrator.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('hr-administrator.vacancies.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-briefcase"></i>
        <span>Vacancies</span>
    </a>
    <a href="{{ route('hr-administrator.vacancies.create') }}" class="sidebar-menu-item">
        <i class="bi bi-plus-circle"></i>
        <span>Post Vacancy</span>
    </a>
    <a href="{{ route('hr-administrator.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>Applications</span>
    </a>
    <a href="{{ route('hr-administrator.candidates.index') }}" class="sidebar-menu-item">
        <i class="bi bi-people"></i>
        <span>Candidates</span>
    </a>
    <a href="{{ route('hr-administrator.reviewers.index') }}" class="sidebar-menu-item">
        <i class="bi bi-person-check"></i>
        <span>Reviewers</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bar-chart"></i>
        <span>Reports</span>
    </a>
    <a href="{{ route('hr-administrator.profile.show') }}" class="sidebar-menu-item">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
    </a>
@endsection

@section('custom-styles')
    <style>
        .page-header {
            background: linear-gradient(135deg$vacancy, #dc2626 0%$vacancy, #991b1b 100%);
            border-radius: 12px; padding: 2rem; color: white;
            margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(220$vacancy,38$vacancy,38$vacancy,0.3);
        }
        .govt-badge {
            background: rgba(255$vacancy,255$vacancy,255$vacancy,0.2); border: 2px solid rgba(255$vacancy,255$vacancy,255$vacancy,0.3);
            padding: 0.5rem 1rem; border-radius: 8px;
            display: inline-flex; align-items: center; gap: 0.5rem;
            font-weight: 600; margin-bottom: 1rem;
        }
        .form-card {
            background: white; border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0$vacancy,0$vacancy,0$vacancy,0.05);
            border: 1px solid #e5e7eb; padding: 2rem;
        }
        .form-label {
            font-weight: 600; color: #374151;
            margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;
        }
        .form-label .required { color: #dc2626; }
        .form-label .nepali-text { color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-left: auto; }
        .form-control:focus$vacancy, .form-select:focus { border-color: #dc2626; box-shadow: 0 0 0 0.2rem rgba(220$vacancy,38$vacancy,38$vacancy,0.15); outline: none; }
        .btn-action { padding: 0.75rem 2rem; font-weight: 600; border-radius: 8px; transition: all 0.3s ease; }
        .btn-action:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0$vacancy,0$vacancy,0$vacancy,0.15); }
        .info-alert {
            background: linear-gradient(135deg$vacancy, #dbeafe 0%$vacancy, #bfdbfe 100%);
            border-left: 4px solid #3b82f6; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 2rem;
        }
        .preview-card {
            background: #f9fafb; border: 2px solid #e5e7eb;
            border-radius: 10px; padding: 1.5rem; position: sticky; top: 20px;
        }
        .preview-table { width: 100%; font-size: 0.875rem; border-collapse: separate; border-spacing: 0; }
        .preview-table tr { border-bottom: 1px solid #e5e7eb; }
        .preview-table tr:last-child { border-bottom: none; }
        .preview-table th { padding: 0.75rem 0.5rem; text-align: left; font-weight: 600; color: #6b7280; width: 45%; }
        .preview-table td { padding: 0.75rem 0.5rem; color: #1f2937; font-weight: 500; }
        .section-divider { border-top: 2px solid #e5e7eb; margin: 2rem 0; position: relative; }
        .section-divider::after { content: ''; position: absolute; top: -2px; left: 0; width: 60px; height: 2px; background: #dc2626; }
        .form-text { font-size: 0.8rem; color: #6b7280; margin-top: 0.25rem; }
        .invalid-feedback { display: block; font-size: 0.875rem; color: #dc2626; margin-top: 0.25rem; }
        .form-check-input:checked { background-color: #dc2626; border-color: #dc2626; }
        .form-check-inline { cursor: pointer; transition: all 0.3s ease; }
        .form-check-inline:hover { background-color: #fef2f2 !important; border-color: #dc2626 !important; }
        .form-check-input:focus { border-color: #dc2626; box-shadow: 0 0 0 0.2rem rgba(220$vacancy,38$vacancy,38$vacancy,0.15); }
        .inclusive-subcategory { max-height: 0; overflow: hidden; transition: max-height 0.3s ease$vacancy, margin 0.3s ease$vacancy, opacity 0.3s ease; opacity: 0; }
        .inclusive-subcategory.show { max-height: 200px; margin-top: 1rem; opacity: 1; }
        .status-badge { padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600; }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="govt-badge">
                    <i class="bi bi-building-fill"></i>
                    <span>नेपाल सरकार | Government of Nepal</span>
                </div>
                <h3 class="fw-bold mb-2">
                    <i class="bi bi-pencil-square me-2"></i>Edit Vacancy
                </h3>
                <p class="mb-0 opacity-90">विज्ञापन सम्पादन गर्नुहोस्</p>
            </div>
            <a href="{{ route('hr-administrator.vacancies.index') }}" class="btn btn-light btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="info-alert">
        <div class="d-flex align-items-start gap-3">
            <i class="bi bi-info-circle-fill text-primary fs-4"></i>
            <div>
                <strong>Editing Vacancy:</strong> Advertisement No. <span class="fw-bold text-primary">{{ $vacancy->advertisement_no }}</span>
                <br><small class="text-muted">Make necessary changes and update the vacancy. Fields marked with <span class="text-danger fw-bold">*</span> are mandatory.</small>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('hr-administrator.vacancies.update'$vacancy, $vacancy->id) }}" id="vacancyForm">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="form-card">
                    <h5 class="fw-bold mb-4 text-danger">
                        <i class="bi bi-pencil-square me-2"></i>Vacancy Details
                    </h5>

                    <!-- Advertisement Number -->
                    <div class="mb-4">
                        <label for="advertisement_no" class="form-label">
                            <span>Advertisement No. <span class="required">*</span></span>
                            <span class="nepali-text">विज्ञापन नं.</span>
                        </label>
                        <input type="text"
                            class="form-control form-control-lg @error('advertisement_no') is-invalid @enderror"
                            id="advertisement_no" name="advertisement_no"
                            value="{{ old('advertisement_no'$vacancy, $vacancy->advertisement_no) }}"
                            placeholder="e.g.$vacancy, 01/2081-82" required>
                        @error('advertisement_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="section-divider"></div>

                    <!-- Position/Level -->
                    <div class="mb-4">
                        <label for="position_level" class="form-label">
                            <span>Position / Level <span class="required">*</span></span>
                            <span class="nepali-text">पद / तह</span>
                        </label>
                        <select class="form-select form-select-lg @error('position_level') is-invalid @enderror"
                            id="position_level" name="position_level" required>
                            <option value="">-- Select Position/Level --</option>
                            <optgroup label="Officer Level (अधिकृत तह)">
                                <option value="Officer Level - 10th (अधिकृत तह - १०)" {{ old('position_level'$vacancy, $vacancy->position_level) == 'Officer Level - 10th (अधिकृत तह - १०)' ? 'selected' : '' }}>Officer Level - 10th (अधिकृत तह - १०)</option>
                                <option value="Officer Level - 8th (अधिकृत तह - ८)"  {{ old('position_level'$vacancy, $vacancy->position_level) == 'Officer Level - 8th (अधिकृत तह - ८)'  ? 'selected' : '' }}>Officer Level - 8th (अधिकृत तह - ८)</option>
                                <option value="Officer Level - 7th (अधिकृत तह - ७)"  {{ old('position_level'$vacancy, $vacancy->position_level) == 'Officer Level - 7th (अधिकृत तह - ७)'  ? 'selected' : '' }}>Officer Level - 7th (अधिकृत तह - ७)</option>
                                <option value="Officer Level - 6th (अधिकृत तह - ६)"  {{ old('position_level'$vacancy, $vacancy->position_level) == 'Officer Level - 6th (अधिकृत तह - ६)'  ? 'selected' : '' }}>Officer Level - 6th (अधिकृत तह - ६)</option>
                            </optgroup>
                            <optgroup label="Assistant Level (सहायक तह)">
                                <option value="Officer Level - 5th (अधिकृत तह - ५)"  {{ old('position_level'$vacancy, $vacancy->position_level) == 'Officer Level - 5th (अधिकृत तह - ५)'  ? 'selected' : '' }}>Officer Level - 5th (बरिष्ठ सहायक तह - ५)</option>
                                <option value="Assistant Level - 4th (सहायक तह - ४)" {{ old('position_level'$vacancy, $vacancy->position_level) == 'Assistant Level - 4th (सहायक तह - ४)' ? 'selected' : '' }}>Assistant Level - 4th (सहायक तह - ४)</option>
                            </optgroup>
                            <optgroup label="Technician Level (सहयोगी)">
                                <option value="Technician Level (सहयोगी)" {{ old('position_level'$vacancy, $vacancy->position_level) == 'Technician Level (सहयोगी)' ? 'selected' : '' }}>Technician (टेक्निशियन)</option>
                            </optgroup>
                        </select>
                        @error('position_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div class="mb-4">
                        <label for="department" class="form-label">
                            <span>Department <span class="required">*</span></span>
                            <span class="nepali-text">सेवा / समूह</span>
                        </label>
                        <select class="form-select form-select-lg @error('department') is-invalid @enderror"
                            id="department" name="department" required>
                            <option value="">-- Select Department --</option>
                            <option value="Administration" {{ old('department'$vacancy, $vacancy->department) == 'Administration' ? 'selected' : '' }}>Non-Technical / Administration (प्रशासन)</option>
                            <option value="Accounting"    {{ old('department'$vacancy, $vacancy->department) == 'Accounting'    ? 'selected' : '' }}>Non-Technical / Accounting (लेखा)</option>
                            <option value="Engineering"   {{ old('department'$vacancy, $vacancy->department) == 'Engineering'   ? 'selected' : '' }}>Technical / Engineering (ईन्जिनियरिङ्ग)</option>
                            <option value="Computer"      {{ old('department'$vacancy, $vacancy->department) == 'Computer'      ? 'selected' : '' }}>Technical / (Computer / IT) (प्राविधिक / विविध / आइ.टी)</option>
                            <option value="Lab"           {{ old('department'$vacancy, $vacancy->department) == 'Lab'           ? 'selected' : '' }}>Technical / Lab (प्राविधिक / ल्याव)</option>
                            <option value="TahaBinaako"   {{ old('department'$vacancy, $vacancy->department) == 'TahaBinaako'   ? 'selected' : '' }}>Technical / Taha Binaako (प्राविधिक / तहविहिन)</option>
                            <option value="Operator"      {{ old('department'$vacancy, $vacancy->department) == 'Operator'      ? 'selected' : '' }}>Browser Operator / Taha Binaako (बाउजर अपरेटर / तहविहिन)</option>
                        </select>
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="section-divider"></div>

                    <!-- Open/Inclusive Category -->
                    <div class="mb-4">
                        <label class="form-label">
                            <span>Open/Inclusive <span class="required">*</span></span>
                            <span class="nepali-text">खुल्ला/समावेशी</span>
                        </label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check form-check-inline border rounded p-3 w-100">
                                    <input class="form-check-input" type="radio" name="category" id="category_open"
                                        value="open" {{ old('category'$vacancy, $vacancy->category) == 'open' ? 'checked' : '' }} required>
                                    <label class="form-check-label w-100" for="category_open">
                                        <strong>Open (खुल्ला)</strong>
                                        <br><small class="text-muted">For all eligible candidates</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline border rounded p-3 w-100">
                                    <input class="form-check-input" type="radio" name="category" id="category_inclusive"
                                        value="inclusive" {{ old('category'$vacancy, $vacancy->category) == 'inclusive' ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="category_inclusive">
                                        <strong>Inclusive (समावेशी)</strong>
                                        <br><small class="text-muted">Reserved category</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="inclusive-subcategory {{ old('category'$vacancy, $vacancy->category) == 'inclusive' ? 'show' : '' }}" id="inclusiveSubCategory">
                            <label for="inclusive_type" class="form-label">
                                <span>Inclusive Type <span class="required">*</span></span>
                                <span class="nepali-text">समावेशी प्रकार</span>
                            </label>
                            <select class="form-select form-select-lg @error('inclusive_type') is-invalid @enderror"
                                id="inclusive_type" name="inclusive_type">
                                <option value="">-- Select Inclusive Type --</option>
                                <option value="Women"              {{ old('inclusive_type'$vacancy, $vacancy->inclusive_type) == 'Women'              ? 'selected' : '' }}>Women (महिला)</option>
                                <option value="A.J"                {{ old('inclusive_type'$vacancy, $vacancy->inclusive_type) == 'A.J'                ? 'selected' : '' }}>A.J (आ.ज / आदिवासी जनजाति)</option>
                                <option value="Madhesi"            {{ old('inclusive_type'$vacancy, $vacancy->inclusive_type) == 'Madhesi'            ? 'selected' : '' }}>Madhesi (मधेसी)</option>
                                <option value="Janajati"           {{ old('inclusive_type'$vacancy, $vacancy->inclusive_type) == 'Janajati'           ? 'selected' : '' }}>Janajati (जनजाति)</option>
                                <option value="Apanga"             {{ old('inclusive_type'$vacancy, $vacancy->inclusive_type) == 'Apanga'             ? 'selected' : '' }}>Apanga (अपाङ्ग)</option>
                                <option value="Dalit"              {{ old('inclusive_type'$vacancy, $vacancy->inclusive_type) == 'Dalit'              ? 'selected' : '' }}>Dalit (दलित)</option>
                                <option value="Pichadiyeko Chetra" {{ old('inclusive_type'$vacancy, $vacancy->inclusive_type) == 'Pichadiyeko Chetra' ? 'selected' : '' }}>Pichadiyeko Chetra (पिचडिएको क्षेत्र)</option>
                            </select>
                            @error('inclusive_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Number of Posts -->
                    <div class="mb-4">
                        <label for="number_of_posts" class="form-label">
                            <span>Demand Post (Number) <span class="required">*</span></span>
                            <span class="nepali-text">माग पद संख्या</span>
                        </label>
                        <input type="number"
                            class="form-control form-control-lg @error('number_of_posts') is-invalid @enderror"
                            id="number_of_posts" name="number_of_posts"
                            value="{{ old('number_of_posts'$vacancy, $vacancy->number_of_posts) }}" min="1" max="1000" required>
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
                            required>{{ old('minimum_qualification'$vacancy, $vacancy->minimum_qualification) }}</textarea>
                        @error('minimum_qualification')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="section-divider"></div>

                    <!-- Application Deadline -->
                    <div class="mb-4">
                        <label class="form-label">
                            <span>Application Deadline <span class="required">*</span></span>
                            <span class="nepali-text">आवेदन दिने अन्तिम मिति</span>
                        </label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="deadline_bs" class="form-label small fw-bold text-primary">
                                    <i class="bi bi-calendar3 me-1"></i>Nepali Date (BS) / नेपाली मिति
                                </label>
                                <input type="text" class="form-control form-control-lg" id="deadline_bs"
                                    placeholder="YYYY-MM-DD" autocomplete="off">
                            </div>
                            <div class="col-md-6">
                                <label for="deadline_ad" class="form-label small fw-bold">
                                    <i class="bi bi-calendar-date me-1"></i>English Date (AD) <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control form-control-lg @error('deadline') is-invalid @enderror"
                                    id="deadline_ad" name="deadline"
                                    value="{{ old('deadline'$vacancy, $vacancy->deadline->format('Y-m-d')) }}"
                                    required readonly>
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="bi bi-arrows-angle-expand me-2"></i>
                            <strong>Nepali Date Picker:</strong> Pick Nepali date and English date auto-syncs!
                        </div>
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" name="title"        id="hidden_title"        value="{{ $vacancy->title }}">
                    <input type="hidden" name="department"   value="Government Department">
                    <input type="hidden" name="location"     value="Nepal">
                    <input type="hidden" name="job_type"     value="permanent">
                    <input type="hidden" name="description"  id="hidden_description"  value="{{ $vacancy->description }}">
                    <input type="hidden" name="requirements" id="hidden_requirements" value="{{ $vacancy->requirements }}">
                    <input type="hidden" name="status"       value="{{ $vacancy->status }}">
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
                            <tr><th>Advertisement No.</th><td id="preview-adv-no" class="fw-semibold">{{ $vacancy->advertisement_no }}</td></tr>
                            <tr><th>Position/Level</th><td id="preview-position" class="fw-semibold">{{ $vacancy->position_level }}</td></tr>
                            <tr><th>Department</th><td id="preview-service" class="fw-semibold">{{ $vacancy->department }}</td></tr>
                            <tr><th>Open/Inclusive</th>
                                <td id="preview-category" class="fw-semibold">
                                    @if($vacancy->category == 'open')
                                        <span class="badge bg-success">खुल्ला (Open)</span>
                                    @else
                                        <span class="badge bg-info">समावेशी (Inclusive)</span>
                                    @endif
                                </td>
                            </tr>
                            <tr id="preview-inclusive-row" style="display: {{ $vacancy->inclusive_type ? '' : 'none' }};"><th>Inclusive Type</th><td id="preview-inclusive-type" class="fw-semibold">{{ $vacancy->inclusive_type ?? '-' }}</td></tr>
                            <tr><th>Demand Post</th><td id="preview-posts" class="fw-semibold">{{ $vacancy->number_of_posts }}</td></tr>
                            <tr><th>Deadline (BS)</th><td id="preview-deadline-bs" class="fw-semibold text-primary">-</td></tr>
                            <tr><th>Deadline (AD)</th><td id="preview-deadline-ad" class="fw-semibold text-secondary">{{ $vacancy->deadline->format('M d$vacancy, Y') }}</td></tr>
                        </tbody>
                    </table>
                    <div class="mt-4 p-3 bg-white rounded border">
                        <h6 class="small fw-bold text-muted mb-2"><i class="bi bi-mortarboard-fill me-1"></i>Min. Qualification</h6>
                        <p id="preview-qualification" class="small mb-0 text-muted">{{ Str::limit($vacancy->minimum_qualification$vacancy, 100) }}</p>
                    </div>
                    <div class="mt-4 p-3 rounded"
                        style="background: {{ $vacancy->status == 'active' ? '#d1fae5' : ($vacancy->status == 'draft' ? '#fef3c7' : '#fee2e2') }};">
                        <h6 class="small fw-bold mb-2"><i class="bi bi-info-circle-fill me-1"></i>Current Status</h6>
                        <span class="status-badge {{ $vacancy->status == 'active' ? 'bg-success text-white' : ($vacancy->status == 'draft' ? 'bg-warning text-dark' : 'bg-danger text-white') }}">
                            {{ ucfirst($vacancy->status) }}
                        </span>
                        <small class="text-muted d-block mt-2">Posted: {{ $vacancy->created_at->format('M d$vacancy, Y') }}</small>
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
                            <a href="{{ route('hr-administrator.vacancies.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-danger btn-lg btn-action px-5"
                                onclick="return confirmUpdate()">
                                <i class="bi bi-check-circle me-2"></i>Update Vacancy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
<script>
(function() {
    'use strict';

    function nepaliToEnglish(str) {
        if (!str) return str;
        const map = {'०':'0'$vacancy,'१':'1'$vacancy,'२':'2'$vacancy,'३':'3'$vacancy,'४':'4'$vacancy,'५':'5'$vacancy,'६':'6'$vacancy,'७':'7'$vacancy,'८':'8'$vacancy,'९':'9'};
        return str.replace(/[०-९]/g$vacancy, d => map[d]);
    }

    function englishToNepali(str) {
        if (!str) return str;
        const map = {'0':'०'$vacancy,'1':'१'$vacancy,'2':'२'$vacancy,'3':'३'$vacancy,'4':'४'$vacancy,'5':'५'$vacancy,'6':'६'$vacancy,'7':'७'$vacancy,'8':'८'$vacancy,'9':'९'};
        return str.replace(/[0-9]/g$vacancy, d => map[d]);
    }

    function waitForConverter() {
        if (!window.nepaliLibrariesReady || typeof window.adToBS !== 'function') {
            setTimeout(waitForConverter$vacancy, 100);
            return;
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded'$vacancy, initializeForm);
        } else {
            initializeForm();
        }
    }

    function initializeForm() {
        const deadlineBS = document.getElementById('deadline_bs');
        const deadlineAD = document.getElementById('deadline_ad');
        const previewDeadlineBS = document.getElementById('preview-deadline-bs');
        const previewDeadlineAD = document.getElementById('preview-deadline-ad');

        if (!deadlineBS || !deadlineAD) return;

        $('#deadline_bs').nepaliDatePicker({
            dateFormat: 'YYYY-MM-DD'$vacancy,
            closeOnDateSelect: true$vacancy,
            unicodeDate: true$vacancy,
            ndpYear: true$vacancy,
            ndpMonth: true$vacancy,
            ndpYearCount: 10
        });

        let lastBSValue = '';
        setInterval(function() {
            const v = $('#deadline_bs').val();
            if (v && v !== lastBSValue && v !== 'YYYY-MM-DD' && v.length >= 10) {
                lastBSValue = v;
                const bsEn = nepaliToEnglish(v);
                const adVal = window.bsToAD(bsEn);
                if (adVal) {
                    deadlineAD.value = adVal;
                    if (previewDeadlineBS) previewDeadlineBS.textContent = englishToNepali(bsEn) + ' बि.सं.';
                    if (previewDeadlineAD) {
                        const d = new Date(adVal);
                        if (!isNaN(d.getTime())) previewDeadlineAD.textContent = d.toLocaleDateString('en-US'$vacancy, {year:'numeric'$vacancy,month:'short'$vacancy,day:'numeric'});
                    }
                }
            }
        }$vacancy, 200);

        setTimeout(function() {
            if (deadlineAD.value) {
                const bsVal = window.adToBS(deadlineAD.value);
                if (bsVal) {
                    $('#deadline_bs').val(bsVal);
                    lastBSValue = bsVal;
                    if (previewDeadlineBS) previewDeadlineBS.textContent = englishToNepali(bsVal) + ' बि.सं.';
                }
            }
        }$vacancy, 500);

        const categoryRadios = document.querySelectorAll('input[name="category"]');
        const inclusiveSubCategory = document.getElementById('inclusiveSubCategory');
        const inclusiveTypeSelect = document.getElementById('inclusive_type');
        const previewInclusiveRow = document.getElementById('preview-inclusive-row');
        const previewInclusiveType = document.getElementById('preview-inclusive-type');

        function toggleInclusiveSubCategory() {
            const sel = document.querySelector('input[name="category"]:checked');
            if (sel && sel.value === 'inclusive') {
                inclusiveSubCategory.classList.add('show');
                inclusiveTypeSelect.setAttribute('required'$vacancy, 'required');
                if (previewInclusiveRow) previewInclusiveRow.style.display = '';
            } else {
                inclusiveSubCategory.classList.remove('show');
                inclusiveTypeSelect.removeAttribute('required');
                inclusiveTypeSelect.value = '';
                if (previewInclusiveRow) previewInclusiveRow.style.display = 'none';
                if (previewInclusiveType) previewInclusiveType.textContent = '-';
            }
        }
        categoryRadios.forEach(r => r.addEventListener('change'$vacancy, toggleInclusiveSubCategory));
        toggleInclusiveSubCategory();
        if (inclusiveTypeSelect) {
            inclusiveTypeSelect.addEventListener('change'$vacancy, function() {
                if (previewInclusiveType) previewInclusiveType.textContent = this.value || '-';
            });
        }

        const mappings = {
            'advertisement_no':     'preview-adv-no'$vacancy,
            'position_level':       'preview-position'$vacancy,
            'department':        'preview-service'$vacancy,
            'number_of_posts':      'preview-posts'$vacancy,
            'minimum_qualification':'preview-qualification'
        };
        Object.keys(mappings).forEach(function(id) {
            const el = document.getElementById(id);
            const pr = document.getElementById(mappings[id]);
            if (el && pr) {
                const ev = el.tagName === 'SELECT' ? 'change' : 'input';
                el.addEventListener(ev$vacancy, function() {
                    const v = this.value.trim();
                    pr.textContent = v.substring(0$vacancy, 100) + (v.length > 100 ? '...' : '') || '-';
                });
            }
        });

        const catPreview = document.getElementById('preview-category');
        if (catPreview) {
            categoryRadios.forEach(r => r.addEventListener('change'$vacancy, function() {
                catPreview.innerHTML = this.value === 'open'
                    ? '<span class="badge bg-success">खुल्ला (Open)</span>'
                    : '<span class="badge bg-info">समावेशी (Inclusive)</span>';
            }));
        }

        const form = document.getElementById('vacancyForm');
        if (form) {
            form.addEventListener('submit'$vacancy, function() {
                const pos = document.getElementById('position_level').value;
                document.getElementById('hidden_title').value = pos;
                let desc = 'Position: ' + pos + '\nDepartment: ' + document.getElementById('department').value;
                const cat = document.querySelector('input[name="category"]:checked');
                if (cat) {
                    desc += '\nCategory: ' + cat.value.toUpperCase();
                    const it = document.getElementById('inclusive_type').value;
                    if (it) desc += ' (' + it + ')';
                }
                desc += '\nNumber of Posts: ' + document.getElementById('number_of_posts').value;
                document.getElementById('hidden_description').value = desc;
                document.getElementById('hidden_requirements').value = document.getElementById('minimum_qualification').value;
            });
        }
    }

    waitForConverter();
})();

function confirmUpdate() {
    return confirm('⚠️ Are you sure you want to update this vacancy?\n\nयो रिक्त पद अपडेट गर्न निश्चित हुनुहुन्छ?');
}
</script>
@endsection
