@extends('layouts.dashboard')

@section('title', 'Assign Admit Cards')
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

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Assign Admit Cards</h4>
            <p class="text-muted mb-0">
                Advertisement No: <strong>{{ $job->advertisement_no }}</strong>
                &nbsp;&mdash;&nbsp; {{ $job->position }}{{ $job->level ? ' / Level ' . $job->level : '' }}
                @if($job->service_group) &nbsp;/ {{ $job->service_group }} @endif
            </p>
        </div>
        <a href="{{ route('admin.admit-card.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.admit-card.store', $job->id) }}">
        @csrf

        {{-- Exam Details --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Exam Details <small class="text-muted fw-normal">(applied to all candidates below)</small></h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Organization Name <span class="text-danger">*</span></label>
                        @php
                            $orgDefault = ($existing->organization_name && $existing->organization_name !== 'Online Recruitment Management System')
                                ? $existing->organization_name
                                : 'Nepal Oil Corporation Ltd';
                        @endphp
                        <input type="text" name="organization_name" class="form-control"
                               value="{{ old('organization_name', $orgDefault) }}"
                               required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">पद / तह <span class="text-danger">*</span></label>
                        <input type="text" name="post_title" class="form-control"
                               value="{{ old('post_title', $existing->post_title ?? ($job->position . ($job->level ? ' / Level ' . $job->level : ''))) }}"
                               placeholder="e.g. व्यवस्थापक / तह ५"
                               required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">सेवा / समूह</label>
                        <input type="text" name="admit_card_service_group" class="form-control"
                               value="{{ old('admit_card_service_group', $existing->admit_card_service_group ?? $job->service_group ?? '') }}"
                               placeholder="e.g. प्राविधिक / अन्य">
                    </div>
                    {{-- प्रथम पत्र --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold text-primary">प्रथम पत्र (First Paper)</label>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">मिति / Date (BS) <span class="text-danger">*</span></label>
                        <input type="text" id="exam_date_first_bs" class="form-control nepali-picker" placeholder="YYYY-MM-DD" autocomplete="off"
                               value="{{ old('exam_date_first', $existing->exam_date_first ?? '') }}">
                        <input type="hidden" name="exam_date_first" id="exam_date_first_val" value="{{ old('exam_date_first', $existing->exam_date_first ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">समय / Time <span class="text-danger">*</span></label>
                        <input type="text" name="exam_time_first" class="form-control"
                               placeholder="e.g. 10:00 AM"
                               value="{{ old('exam_time_first', $existing->exam_time_first ?? '') }}"
                               required>
                    </div>

                    {{-- द्वितीय पत्र --}}
                    <div class="col-12 mt-2">
                        <label class="form-label fw-semibold text-primary">द्वितीय पत्र (Second Paper)</label>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">मिति / Date (BS)</label>
                        <input type="text" id="exam_date_second_bs" class="form-control nepali-picker" placeholder="YYYY-MM-DD" autocomplete="off"
                               value="{{ old('exam_date_second', $existing->exam_date_second ?? '') }}">
                        <input type="hidden" name="exam_date_second" id="exam_date_second_val" value="{{ old('exam_date_second', $existing->exam_date_second ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">समय / Time</label>
                        <input type="text" name="exam_time_second" class="form-control"
                               placeholder="e.g. 2:00 PM"
                               value="{{ old('exam_time_second', $existing->exam_time_second ?? '') }}">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Exam Venue <span class="text-danger">*</span></label>
                        <textarea name="exam_venue" class="form-control" rows="2"
                                  placeholder="Exam center name and address" required>{{ old('exam_venue', $existing->exam_venue ?? '') }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Roll Number Prefix <span class="text-danger">*</span></label>
                        @php
                            preg_match('/(\d+)\/(\d{4})-\d+/', $job->advertisement_no, $m);
                            $defaultPrefix = isset($m[2], $m[1]) ? $m[2] . '-' . $m[1] : preg_replace('/[^a-zA-Z0-9]/', '-', $job->advertisement_no);
                        @endphp
                        <input type="text" name="roll_prefix" class="form-control"
                               value="{{ old('roll_prefix', $defaultPrefix) }}"
                               placeholder="e.g. 2082-35" required>
                        <div class="form-text">Preview: <strong id="rollPreview">{{ $defaultPrefix }}-001</strong>, <strong>{{ $defaultPrefix }}-002</strong>, ...</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Exam Instructions <small class="text-muted">(optional)</small></label>
                        <textarea name="exam_instructions" class="form-control" rows="4"
                                  placeholder="Instructions for candidates...">{{ old('exam_instructions', $existing->exam_instructions ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Candidates Table --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Candidates <span class="badge bg-primary ms-2">{{ $applications->count() }}</span></h6>
                <small class="text-muted">Already-assigned roll numbers are preserved.</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 align-middle text-center" style="font-size:0.88rem;">
                        <thead style="background:#f9fafb;">
                            <tr>
                                <th>S.N.</th>
                                <th>Name</th>
                                <th>Citizenship No.</th>
                                <th>Applied Category</th>
                                <th>Status</th>
                                <th>Roll No.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $i => $app)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="text-start">
                                        <div class="fw-semibold">{{ $app->name_english }}</div>
                                        <div class="text-muted" style="font-size:0.82rem;">{{ $app->name_nepali }}</div>
                                    </td>
                                    <td>{{ $app->citizenship_number }}</td>
                                    <td>
                                        @php
                                            $rawCat = $app->applied_category;
                                            $decoded = is_array($rawCat) ? $rawCat : json_decode($rawCat, true);
                                            $cats = array_values(array_unique(array_filter(is_array($decoded) ? $decoded : [$rawCat])));
                                            $catLabels = [];
                                            foreach ($cats as $cat) {
                                                if ($cat === 'open') {
                                                    $catLabels[] = 'Open';
                                                } elseif ($cat === 'inclusive') {
                                                    $catLabels[] = !empty($inclusiveTypes)
                                                        ? 'Inclusive (' . implode(', ', $inclusiveTypes) . ')'
                                                        : 'Inclusive';
                                                } elseif ($cat === 'internal_open') {
                                                    $catLabels[] = 'Internal Open';
                                                } elseif ($cat === 'internal_inclusive') {
                                                    $catLabels[] = 'Internal Inclusive';
                                                } elseif ($cat === 'internal_appraisal') {
                                                    $catLabels[] = 'Internal Appraisal';
                                                } else {
                                                    $catLabels[] = ucfirst(str_replace('_', ' ', $cat));
                                                }
                                            }
                                        @endphp
                                        {{ implode(', ', $catLabels) ?: '-' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $app->status === 'assigned' ? 'info text-dark' : ($app->status === 'approved' ? 'success' : 'warning text-dark') }}">
                                            {{ ucfirst($app->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($app->roll_number)
                                            <span class="badge bg-info text-dark">{{ $app->roll_number }}</span>
                                            <small class="text-muted d-block">(preserved)</small>
                                        @else
                                            <span class="text-muted">Auto</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn px-4 fw-semibold" style="background:#c9a84c; color:#fff; border:none;">
                Assign Admit Card
            </button>
            <a href="{{ route('admin.admit-card.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>

    </form>

@endsection

@section('scripts')
<script>
    document.querySelector('[name="roll_prefix"]').addEventListener('input', function() {
        var prefix = this.value.trim();
        document.getElementById('rollPreview').textContent = prefix ? prefix + '-001' : '---';
    });

    // Nepali date pickers for प्रथम and द्वितीय exam dates
    (function() {
        function nepaliToEnglish(str) {
            if (!str) return str;
            var map = {'०':'0','१':'1','२':'2','३':'3','४':'4','५':'5','६':'6','७':'7','८':'8','९':'9'};
            return str.replace(/[०-९]/g, function(d) { return map[d]; });
        }

        function waitForLibraries() {
            if (typeof $ === 'undefined' || typeof $.fn.nepaliDatePicker === 'undefined') {
                setTimeout(waitForLibraries, 150);
                return;
            }
            initPickers();
        }

        function initPicker(bsInputId, hiddenInputId) {
            var bsEl     = document.getElementById(bsInputId);
            var hiddenEl = document.getElementById(hiddenInputId);
            if (!bsEl || !hiddenEl) return;

            $(bsEl).nepaliDatePicker({
                dateFormat: 'YYYY-MM-DD',
                unicodeDate: true
            });

            var last = '';
            setInterval(function() {
                var current = $(bsEl).val();
                if (!current || current === last) return;
                last = current;
                // Store BS value directly (no AD conversion needed — stored as BS string)
                hiddenEl.value = nepaliToEnglish(current);
            }, 200);
        }

        function initPickers() {
            initPicker('exam_date_first_bs',  'exam_date_first_val');
            initPicker('exam_date_second_bs', 'exam_date_second_val');
        }

        waitForLibraries();
    })();
</script>
@endsection
