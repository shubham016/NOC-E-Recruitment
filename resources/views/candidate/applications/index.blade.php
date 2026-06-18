@extends('layouts.app')

@section('title', 'Your Applications')

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>Vacancy</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-check"></i>
        <span>View Result</span>
    </a>
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
        <h4 class="mb-0"><i class="fas fa-list"></i> All Application Records</h4>
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
            <strong>Double Dastur Fee Notice</strong><br>
            You have {{ $doubleDasturDrafts->count() }} unpaid draft application(s) where the application deadline has passed.
            If you proceed to pay now, the <strong>Double Dastur Fee</strong> will apply instead of the regular application fee.
            Please complete payment before the double dastur deadline to avoid losing your application.
        </div>
        @endif

        @if($forms->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-bg-light">
                        <tr>
                            <th>Application No.</th>
                            <th>Adv. No.</th>
                            <th>Post</th>
                            <th>Status</th>
                            <th>Roll. No.</th>
                            <th>Payment</th>
                            <th width="180">Documents</th>
                            <th width="160">Actions</th>
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
                                    {{ $displayPayment }}
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
                                        <br><small class="fw-semibold" style="color: #664d03;">Double Dastur
                                            : Rs. {{ number_format($fJob->double_dastur_fee) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @if($form->noc_id_card) <span class="badge bg-dark">NOC</span> @endif
                                        @if($form->disability_certificate) <span class="badge bg-dark">DIS</span> @endif
                                        @if($form->citizenship_id_document) <span class="badge bg-dark">CIT</span> @endif
                                        @if($form->work_experience) <span class="badge bg-dark">WE</span> @endif
                                        @if($form->transcript) <span class="badge bg-dark">TC</span> @endif
                                        @if($form->character) <span class="badge bg-dark">CC</span> @endif
                                        @if($form->signature) <span class="badge bg-dark">S</span> @endif
                                        @if($form->ethnic_certificate) <span class="badge bg-dark">ETH</span> @endif
                                        @if($form->passport_size_photo) <span class="badge bg-dark">PSP</span> @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('candidate.applications.show', $form->id) }}" class="btn btn-danger" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                       @php $paymentDone = $form->payment && in_array($form->payment->status, ['completed', 'paid']); @endphp
                                       @if($form->status === 'edit' || ($form->status === 'draft' && !$paymentDone))
                                            <a href="{{ route('candidate.applications.edit', $form->id) }}"
                                            class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif
                                        @if($form->status === 'shortlisted' && !is_null($form->exam_date))
                                            <a href="{{ route('candidate.admit-card.view', $form->id) }}" 
                                            class="btn btn-dark" title="Admit Card">
                                                <i class="fas bi-person-vcard"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-secondary" title="Admit Card Not Generated" disabled>
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
                <i class="fas fa-info-circle"></i> No records found.
            </div>
        @endif
    </div>
</div>
@endsection
