@extends('layouts.app')

@section('title', __('candidate.view_result'))

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
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>{{ __('candidate.my_applications') }}</span>
    </a>
    <a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item active">
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
<div class="page-header">
    <h1 class="page-title">
        <i class="bi bi-clipboard-data text-dark"></i> {{ __('candidate.view_result') }}
    </h1>
    <p class="page-subtitle">{{ __('candidate.check_results_description') }}</p>
</div>

@if($results->isEmpty())
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox display-1 text-muted mb-3"></i>
            <h4 class="text-muted">{{ __('candidate.no_results_available') }}</h4>
            <p class="text-secondary">{{ __('candidate.no_results_available_description') }}</p>
            <a href="{{ route('candidate.dashboard') }}" class="btn btn-danger mt-3">
                <i class="bi bi-house-door"></i> {{ __('candidate.back_to_dashboard') }}
            </a>
        </div>
    </div>
@else
    <div class="card shadow-sm">
        <div class="card-header bg-light text-dark">
            <h5 class="mb-0">
                <i class="bi bi-table"></i> {{ __('candidate.my_results') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">{{ __('candidate.sn') }}</th>
                            <th>{{ __('candidate.roll_number') }}</th>
                            <th>{{ __('candidate.full_name') }}</th>
                            <th>{{ __('candidate.citizenship_number') }}</th>
                            <th>{{ __('candidate.post') }}</th>
                            <th>{{ __('candidate.advertisement_code') }}</th>
                            <th class="text-center">{{ __('candidate.marks') }}</th>
                            <th>{{ __('candidate.class') }}</th>
                            <th>{{ __('candidate.quota') }}</th>
                            <th>{{ __('candidate.service') }}</th>
                            <th class="text-center">{{ __('candidate.status') }}</th>
                            <th class="text-center">{{ __('candidate.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $index => $result)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <strong class="text-dark">{{ $result->roll_number ?? 'N/A' }}</strong>
                            </td>
                            <td>{{ $result->full_name ?? 'N/A' }}</td>
                            <td>{{ $result->citizenship_number ?? 'N/A' }}</td>
                            <td>
                                <span class="text-dark">
                                    {{ $result->post ?? 'N/A' }}
                                </span>
                            </td>
                            <td>{{ $result->advertisement_code ?? 'N/A' }}</td>
                            <td class="text-center">
                                @if($result->marks !== null)
                                    <strong class="text-success fs-5">{{ $result->marks }}</strong>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($result->class)
                                    <span class=" 
                                        @if(strtolower($result->class) === 'first' || strtolower($result->class) === '1st') 
                        
                                        @elseif(strtolower($result->class) === 'second' || strtolower($result->class) === '2nd')
                                            
                                        @elseif(strtolower($result->class) === 'third' || strtolower($result->class) === '3rd')
                                            
                                        @else
                                            
                                        @endif
                                    ">
                                        {{ $result->class }}
                                    </span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $result->quota ?? 'N/A' }}</td>
                            <td>{{ $result->recommended_service ?? 'N/A' }}</td>
                            <td class="text-center">
                                @if($result->marks !== null && $result->class)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> {{ __('candidate.published') }}
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hourglass-split"></i> {{ __('candidate.pending') }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ url('/candidate/result/' . $result->id) }}" 
                                   class="btn btn-sm btn-outline-danger" 
                                   title="{{ __('candidate.view_details') }}">
                                    <i class="bi bi-eye"></i> {{ __('candidate.details') }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Results Summary -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">{{ __('candidate.total_applications') }}</h6>
                            <h3 class="text-danger mb-0">{{ $results->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">{{ __('candidate.results_published') }}</h6>
                            <h3 class="text-success mb-0">
                                {{ $results->whereNotNull('marks')->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">{{ __('candidate.pending_results') }}</h6>
                            <h3 class="text-warning mb-0">
                                {{ $results->whereNull('marks')->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Download Button -->
            <div class="text-center mt-4">
                <button class="btn btn-danger" onclick="window.print()">
                    <i class="bi bi-printer"></i> Print Results
                </button>
            </div>
        </div>
    </div>
@endif

@push('styles')
<style>
    @media print {
        .sidebar, .navbar, footer, .btn, .page-subtitle {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        /* Hide the Action column when printing */
        th:last-child, td:last-child {
            display: none !important;
        }
    }
</style>
@endpush

@endsection
