@extends('layouts.dashboard')

@section('title', __('admin.admit_card_management'))
@section('portal-name', __('admin.portal_name'))
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()?->name ?? 'Guest')
@section('user-role', __('admin.system_administrator'))
@section('user-initial', Auth::guard('admin')->user() ? strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) : 'G')
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('custom-styles')
    <style>
        .admit-header {
            background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
            border-radius: 12px;
            padding: 1.5rem;
            color: #fff;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(26, 58, 107, 0.22);
        }

        .admit-header p {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .admit-total-badge {
            background: #122a52 !important;
            color: #fff !important;
        }

        .admit-progress-badge {
            background: #2a5298 !important;
            color: #fff !important;
        }
    </style>
@endsection

@section('content')

    <div class="admit-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.admit_card_management') }}</h4>
            <p class="mb-0">{{ __('admin.bulk_assign_exam') }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">{{ __('admin.vacancies_with_approved') }}</h6>
        </div>
        <div class="card-body p-0">
            @if($groups->isEmpty())
                <div class="text-center py-5 text-muted">
                    <p class="mb-0">{{ __('admin.no_approved_for_admit') }}</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 align-middle text-center" style="font-size:0.9rem;">
                        <thead style="background:#f9fafb;">
                            <tr>
                                <th>{{ __('admin.notice_no') }}</th>
                                <th>{{ __('admin.adv_no') }}</th>
                                <th>{{ __('admin.position_level') }}</th>
                                <th>{{ __('admin.service_group') }}</th>
                                <th>{{ __('admin.total_applications') }}</th>
                                <th>{{ __('admin.admit_cards_assigned') }}</th>
                                <th>{{ __('admin.exam_date') }}</th>
                                <th>{{ __('admin.venue') }}</th>
                                <th>{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groups as $group)
                                @php $allAssigned = $group->assigned_count >= $group->total_candidates; @endphp
                                <tr>
                                    <td>{{ $group->notice_no ?? '-' }}</td>
                                    <td class="fw-bold">{{ $group->advertisement_no }}</td>
                                    <td>{{ $group->position }}{{ $group->level ? ' / ' . __('admin.level') . ' ' . $group->level : '' }}</td>
                                    <td>{{ $group->service_group ?? '-' }}</td>
                                    <td><span class="badge admit-total-badge">{{ $group->total_candidates }}</span></td>
                                    <td>
                                        @if($allAssigned)
                                            <span class="badge bg-success">{{ $group->assigned_count }} / {{ $group->total_candidates }}</span>
                                        @elseif($group->assigned_count > 0)
                                            <span class="badge admit-progress-badge">{{ $group->assigned_count }} / {{ $group->total_candidates }}</span>
                                        @else
                                            <span class="badge bg-secondary">0 / {{ $group->total_candidates }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $group->exam_date ?? '-' }}</td>
                                    <td>{{ $group->exam_venue ? \Illuminate\Support\Str::limit($group->exam_venue, 40) : '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.admit-card.assign', $group->job_posting_id) }}"
                                           class="btn btn-sm" style="background:#1a3a6b; color:#fff; border:none;">
                                            {{ $allAssigned ? __('admin.reassign') : __('admin.assign_admit_cards') }}
                                        </a>
                                        @if($group->assigned_count > 0)
                                            <a href="{{ route('admin.admit-card.preview', $group->job_posting_id) }}"
                                               class="btn btn-sm btn-outline-secondary ms-1">
                                                {{ __('admin.preview') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection
