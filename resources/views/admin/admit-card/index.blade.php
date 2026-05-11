@extends('layouts.dashboard')

@section('title', 'Admit Card Management')
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
            <h4 class="fw-bold mb-1">Admit Card Management</h4>
            <p class="text-muted mb-0">Bulk assign exam details and roll numbers by vacancy.</p>
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
            <h6 class="fw-bold mb-0">Vacancies with Approved Applications</h6>
        </div>
        <div class="card-body p-0">
            @if($groups->isEmpty())
                <div class="text-center py-5 text-muted">
                    <p class="mb-0">No approved applications available for admit card assignment.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 align-middle text-center" style="font-size:0.9rem;">
                        <thead style="background:#f9fafb;">
                            <tr>
                                <th>Notice No.</th>
                                <th>Advertisement No.</th>
                                <th>Position / Level</th>
                                <th>Service / Group</th>
                                <th>Total Candidates</th>
                                <th>Admit Cards Assigned</th>
                                <th>Exam Date</th>
                                <th>Venue</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groups as $group)
                                @php $allAssigned = $group->assigned_count >= $group->total_candidates; @endphp
                                <tr>
                                    <td>{{ $group->notice_no ?? '-' }}</td>
                                    <td class="fw-bold">{{ $group->advertisement_no }}</td>
                                    <td>{{ $group->position }}{{ $group->level ? ' / Level ' . $group->level : '' }}</td>
                                    <td>{{ $group->service_group ?? '-' }}</td>
                                    <td><span class="badge bg-primary">{{ $group->total_candidates }}</span></td>
                                    <td>
                                        @if($allAssigned)
                                            <span class="badge bg-success">{{ $group->assigned_count }} / {{ $group->total_candidates }}</span>
                                        @elseif($group->assigned_count > 0)
                                            <span class="badge bg-warning text-dark">{{ $group->assigned_count }} / {{ $group->total_candidates }}</span>
                                        @else
                                            <span class="badge bg-secondary">0 / {{ $group->total_candidates }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $group->exam_date ?? '-' }}</td>
                                    <td>{{ $group->exam_venue ? \Illuminate\Support\Str::limit($group->exam_venue, 40) : '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.admit-card.assign', $group->job_posting_id) }}"
                                           class="btn btn-sm" style="background:#c9a84c; color:#fff; border:none;">
                                            {{ $allAssigned ? 'Re-assign' : 'Assign Admit Cards' }}
                                        </a>
                                        @if($group->assigned_count > 0)
                                            <a href="{{ route('admin.admit-card.preview', $group->job_posting_id) }}"
                                               class="btn btn-sm btn-outline-secondary ms-1">
                                                Preview
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
