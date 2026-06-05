@extends('layouts.app')

@section('title', 'SMS Messages')

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>Vacancy</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="{{ route('candidate.sms.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-chat-dots"></i>
        <span>SMS Messages</span>
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
<div class="container-fluid py-4">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">SMS Messages</h4>
        <p class="text-muted mb-0">View SMS messages sent to you by the administration</p>
    </div>

    @if($logs->isEmpty())
        <div class="bg-white rounded-3 border p-5 text-center">
            <p class="text-muted mb-0">No SMS messages found.</p>
        </div>
    @else
        <div class="bg-white rounded-3 border" style="overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: #f9fafb;">
                        <tr>
                            <th class="fw-bold text-uppercase text-center" style="font-size: 0.85rem; letter-spacing: 0.5px; padding: 1rem;">S.N.</th>
                            <th class="fw-bold text-uppercase text-center" style="font-size: 0.85rem; letter-spacing: 0.5px; padding: 1rem;">Date</th>
                            <th class="fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px; padding: 1rem;">Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $index => $log)
                            <tr>
                                <td class="text-center" style="padding: 1rem;">{{ $logs->firstItem() + $index }}</td>
                                <td class="text-center text-nowrap" style="padding: 1rem;">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                <td style="padding: 1rem;">{{ $log->message }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($logs->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @endif
</div>
@endsection
