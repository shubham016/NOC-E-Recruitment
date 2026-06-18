@extends('layouts.app')

@section('title', __('candidate.change_password'))

@section('content')
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
    <a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-check"></i>
        <span>{{ __('candidate.view_result') }}</span>
    </a>
    <a href="{{ route('candidate.admit-card') }}" class="sidebar-menu-item">
        <i class="bi bi-box-arrow-down"></i>
        <span>{{ __('candidate.download_admit_card') }}</span>
    </a>
    <a href="{{ route('candidate.change-password') }}" class="sidebar-menu-item active">
        <i class="bi bi-lock"></i>
        <span>{{ __('candidate.change_password') }}</span>
    </a>
@endsection

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light text-dark">
                    <h4 class="mb-0">{{ __('candidate.change_password') }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('candidate.change-password.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{ __('candidate.current_password') }}</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">{{ __('candidate.new_password') }}</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" name="new_password" required>
                            <small class="form-text text-muted">
                                {{ __('candidate.password_min_8') }}
                            </small>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">{{ __('candidate.confirm_new_password') }}</label>
                            <input type="password" class="form-control" 
                                   id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-check-circle"></i> {{ __('candidate.update_password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
