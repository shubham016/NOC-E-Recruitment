@extends('layouts.dashboard')

@section('title', __('admin.admit_cards_preview'))
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

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.assigned_admit_cards') }}</h4>
            <p class="text-muted mb-0">
                {{ __('admin.adv_no_colon') }} <strong>{{ $job->advertisement_no }}</strong>
                &nbsp;&mdash;&nbsp; {{ $job->position }}{{ $job->level ? ' / Level ' . $job->level : '' }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.admit-card.assign', $job->id) }}" class="btn btn-sm" style="background:#c9a84c;color:#fff;border:none;">{{ __('admin.reassign') }}</a>
            <a href="{{ route('admin.admit-card.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('admin.back') }}</a>
        </div>
    </div>

    @if($applications->isEmpty())
        <div class="alert alert-info">{{ __('admin.no_admit_cards_assigned') }}</div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">
                    {{ $applications->count() }} {{ __('admin.candidates') }}
                    @if($applications->first())
                        &nbsp;&mdash;&nbsp; {{ __('admin.exam') }}: {{ $applications->first()->exam_date }}
                        &nbsp;|&nbsp; {{ $applications->first()->exam_time }}
                        &nbsp;|&nbsp; {{ $applications->first()->exam_venue }}
                    @endif
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 align-middle text-center" style="font-size:0.88rem;">
                        <thead style="background:#f9fafb;">
                            <tr>
                                <th>{{ __('admin.roll_no') }}</th>
                                <th>{{ __('admin.name') }}</th>
                                <th>{{ __('admin.citizenship_no') }}</th>
                                <th>{{ __('admin.applied_category') }}</th>
                                <th>{{ __('admin.first_paper_datetime') }}</th>
                                <th>{{ __('admin.second_paper_datetime') }}</th>
                                <th>{{ __('admin.venue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $app)
                                <tr>
                                    <td><span class="badge bg-info text-dark fw-bold">{{ $app->roll_number }}</span></td>
                                    <td class="text-start">
                                        <div class="fw-semibold">{{ $app->name_english }}</div>
                                        <div class="text-muted" style="font-size:0.82rem;">{{ $app->name_nepali }}</div>
                                    </td>
                                    <td>{{ $app->citizenship_number }}</td>
                                    <td>
                                        @php
                                            $cats = json_decode($app->applied_category, true);
                                            $cats = is_array($cats) ? $cats : [$app->applied_category];
                                        @endphp
                                        {{ implode(', ', array_filter((array)$cats)) }}
                                    </td>
                                    <td>{{ $app->exam_date_first ?? '-' }}{{ $app->exam_time_first ? ' / ' . $app->exam_time_first : '' }}</td>
                                    <td>{{ $app->exam_date_second ?? '-' }}{{ $app->exam_time_second ? ' / ' . $app->exam_time_second : '' }}</td>
                                    <td class="text-start">{{ $app->exam_venue }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

@endsection
