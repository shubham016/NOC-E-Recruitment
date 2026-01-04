@extends('layouts.app')

@section('title', 'Your Applications')

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="#" class="sidebar-menu-item">
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
    <a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
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
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="fas fa-list"></i> All Application Records</h4>
        <a href="{{ route('candidate.applications.create') }}" class="btn btn-light btn-sm">
            <i class="fas fa-plus"></i> New Application
        </a>
    </div>

    <div class="card-body">
        @if($forms->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-bg-primary">
                        <tr>
                            <th width="80">Photo</th>
                            <th>I.D</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Citizenship No.</th>
                            <th>Submitted</th>
                            <th width="180">Documents</th>
                            <th width="160">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($forms as $form)
                            <tr>
                                <td class="text-center">
                                    @if($form->passport_size_photo)
                                        <img src="{{ asset('storage/' . $form->passport_size_photo) }}"
                                             class="rounded-circle border"
                                             width="50" height="50"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle" style="width:50px;height:50px;"></div>
                                    @endif
                                </td>
                                <td>{{ $loop->iteration + ($forms->currentPage() - 1) * $forms->perPage() }}</td>
                                <td><strong>{{ $form->name_english ?? '-' }}</strong></td>
                                <td>{{ $form->phone ?? '-' }}</td>
                                <td>{{ $form->citizenship_number ?? '-' }}</td>
                                <td>{{ $form->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @if($form->noc_id_card) <span class="badge bg-info">NOC</span> @endif
                                        @if($form->disability_certificate) <span class="badge bg-warning">DIS</span> @endif
                                        @if($form->citizenship_id_document) <span class="badge bg-success">CIT</span> @endif
                                        @if($form->resume_cv) <span class="badge bg-primary">CV</span> @endif
                                        @if($form->educational_certificates) <span class="badge bg-secondary">EDU</span> @endif
                                        @if($form->ethnic_certificate) <span class="badge bg-dark">ETH</span> @endif
                                        @if($form->passport_size_photo) <span class="badge bg-primary">PSP</span> @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('candidate.applications.show', $form->id) }}" class="btn btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('candidate.applications.edit', $form->id) }}" class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('candidate.applications.destroy', $form->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this record permanently?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $forms->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> No records found.
                <a href="{{ route('candidate.applications.create') }}" class="alert-link">Create first registration</a>
            </div>
        @endif
    </div>
</div>
@endsection