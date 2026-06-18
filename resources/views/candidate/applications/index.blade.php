@extends('layouts.app')

@section('title', __('candidate.your_applications'))

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

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-light text-dark d-flex justify-content-between align-items-center">
        <!-- <h4 class="mb-0"><i class="fas fa-list"></i> All Application Records</h4> -->
        <h4 class="mb-0">{{ __('candidate.all_application_records') }}</h4>
    </div>

    <div class="card-body">
        @php
            $paidStatuses = ['paid', 'completed'];
            $doubleDasturDrafts = $forms->filter(function($form) use ($paidStatuses) {
                if (!in_array($form->status, ['draft', 'edit'])) return false;
                if ($form->payment && in_array($form->payment->status, $paidStatuses)) return false;
                $job = $form->jobPosting;
                if (!$job) return false;
                return $job->deadline
                    && now()->gt($job->deadline)
                    && $job->double_dastur_fee
                    && $job->double_dastur_date
                    && now()->lte($job->double_dastur_date);
            });
        @endphp
        @if($doubleDasturDrafts->count() > 0)
        <div class="alert alert-warning mb-3">
            <strong>{{ __('candidate.double_dastur_fee_notice') }}</strong><br>
            {{ __('candidate.double_dastur_fee_notice_text', ['count' => $doubleDasturDrafts->count()]) }}
        </div>
        @endif

        @if($forms->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-bg-light">
                        <tr>
                            <th>{{ __('candidate.application_no') }}</th>
                            <th>{{ __('candidate.adv_no') }}</th>
                            <th>{{ __('candidate.post') }}</th>
                            <th>{{ __('candidate.status') }}</th>
                            <th>{{ __('candidate.roll_no') }}</th>
                            <th>{{ __('candidate.payment') }}</th>
                            <th width="180">{{ __('candidate.documents') }}</th>
                            <th width="160">{{ __('candidate.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($forms as $form)
                            <tr>
                                <td>{{ $form->id ?? '-' }}</td>
                                <td>{{ $form->advertisement_no ?? '-' }}</td>
                                
                                
                                <td>{{ $form->applying_position ?? '-' }}</td>
                                
                                <td>{{ $form->status ?? '-' }}</td>
                                <td>{{ $form->roll_number ?? '-' }}</td>
                                <td>
                                    @php
                                        $submittedStatuses = ['edit', 'edited', 'pending', 'shortlisted', 'approved', 'rejected', 'reviewed'];
                                        $displayPayment = in_array($form->status, $submittedStatuses) ? 'paid' : ($form->payment->status ?? 'unpaid');
                                    @endphp
                                    {{ __("candidate.$displayPayment") }}
                                    @php
                                        $fJob = $form->jobPosting;
                                        $fInDoubleDastur = $fJob && $fJob->deadline
                                            && now()->gt($fJob->deadline)
                                            && $fJob->double_dastur_fee
                                            && $fJob->double_dastur_date
                                            && now()->lte($fJob->double_dastur_date)
                                            && in_array($form->status, ['draft', 'edit'])
                                            && (!$form->payment || !in_array($form->payment->status, $paidStatuses));
                                    @endphp
                                    @if($fInDoubleDastur)
                                        <br><small class="fw-semibold" style="color: #664d03;">{{ __('candidate.double_dastur') }}
                                            : Rs. {{ number_format($fJob->double_dastur_fee) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $candidateProfile = $form->candidateRegistration;
                                        $documentPath = function ($applicationField, $profileField = null) use ($form, $candidateProfile) {
                                            $profileField = $profileField ?: $applicationField;

                                            return $form->{$applicationField}
                                                ?? ($candidateProfile ? $candidateProfile->{$profileField} : null);
                                        };
                                    @endphp
                                    <div class="d-flex flex-wrap justify-content-center gap-1">
                                        @if($documentPath('noc_id_card')) <span class="badge bg-dark">NOC</span> @endif
                                        @if($documentPath('disability_certificate')) <span class="badge bg-dark">DIS</span> @endif
                                        @if($documentPath('citizenship_id_document')) <span class="badge bg-dark">CIT</span> @endif
                                        @if($documentPath('work_experience')) <span class="badge bg-dark">WE</span> @endif
                                        @if($documentPath('transcript')) <span class="badge bg-dark">TC</span> @endif
                                        @if($documentPath('character', 'character_certificate')) <span class="badge bg-dark">CC</span> @endif
                                        @if($documentPath('signature')) <span class="badge bg-dark">S</span> @endif
                                        @if($documentPath('ethnic_certificate')) <span class="badge bg-dark">ETH</span> @endif
                                        @if($documentPath('passport_size_photo')) <span class="badge bg-dark">PSP</span> @endif
                                        @if($documentPath('equivalent', 'equivalency_certificate')) <span class="badge bg-dark">EQ</span> @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm justify-content-center">
                                        <a href="{{ route('candidate.applications.show', $form->id) }}" class="btn btn-danger" title="{{ __('candidate.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                       @php $paymentDone = $form->payment && in_array($form->payment->status, ['completed', 'paid']); @endphp
                                       @if($form->status === 'edit' || ($form->status === 'draft' && !$paymentDone))
                                            <a href="{{ route('candidate.applications.edit', $form->id) }}"
                                            class="btn btn-sm btn-warning" title="{{ __('candidate.edit') }}">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif
                                        @if($form->status === 'shortlisted' && !is_null($form->exam_date))
                                            <a href="{{ route('candidate.admit-card.view', $form->id) }}" 
                                            class="btn btn-dark" title="{{ __('candidate.admit_card') }}">
                                                <i class="fas bi-person-vcard"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-secondary" title="{{ __('candidate.admit_card_not_generated') }}" disabled>
                                                <i class="fas fa-id-card"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $forms->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> {{ __('candidate.no_records_found') }}
            </div>
        @endif
    </div>
</div>
@endsection
