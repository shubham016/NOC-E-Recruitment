<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ReviewerAuthController;
use App\Http\Controllers\Auth\CandidateAuthController;

// Public route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    });
});

// Reviewer Authentication Routes
Route::prefix('reviewer')->name('reviewer.')->group(function () {
    Route::get('/login', [ReviewerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ReviewerAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [ReviewerAuthController::class, 'logout'])->name('logout');

    // Protected Reviewer Routes
    Route::middleware('reviewer')->group(function () {
        Route::get('/dashboard', function () {
            return view('reviewer.dashboard');
        })->name('dashboard');
    });
});

// Candidate Authentication Routes
Route::prefix('candidate')->name('candidate.')->group(function () {
    Route::get('/login', [CandidateAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CandidateAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [CandidateAuthController::class, 'logout'])->name('logout');

    // Protected Candidate Routes
    Route::middleware('candidate')->group(function () {
        Route::get('/dashboard', function () {
            return view('candidate.dashboard');
        })->name('dashboard');
    });
});

// Reviewer Authentication Routes
Route::prefix('reviewer')->name('reviewer.')->group(function () {
    Route::get('/login', [ReviewerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ReviewerAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [ReviewerAuthController::class, 'logout'])->name('logout');

    // Protected Reviewer Routes
    Route::middleware('reviewer')->group(function () {
        // Dashboard - UPDATE THIS LINE
        Route::get('/dashboard', [App\Http\Controllers\Reviewer\ReviewerDashboardController::class, 'index'])->name('dashboard');

        // Application Review Routes
        Route::get('/applications', [App\Http\Controllers\Reviewer\ApplicationReviewController::class, 'index'])->name('applications.index');
        Route::get('/applications/{id}', [App\Http\Controllers\Reviewer\ApplicationReviewController::class, 'show'])->name('applications.show');
        Route::get('/applications/{id}/details', [App\Http\Controllers\Reviewer\ApplicationReviewController::class, 'getDetails'])->name('applications.details');
        Route::post('/applications/{id}/status', [App\Http\Controllers\Reviewer\ApplicationReviewController::class, 'updateStatus'])->name('applications.updateStatus');
        Route::post('/applications/{id}/accept', [App\Http\Controllers\Reviewer\ApplicationReviewController::class, 'accept'])->name('applications.accept');
        Route::post('/applications/{id}/reject', [App\Http\Controllers\Reviewer\ApplicationReviewController::class, 'reject'])->name('applications.reject');
        Route::post('/applications/{id}/shortlist', [App\Http\Controllers\Reviewer\ApplicationReviewController::class, 'shortlist'])->name('applications.shortlist');
        Route::post('/applications/{id}/notes', [App\Http\Controllers\Reviewer\ApplicationReviewController::class, 'updateNotes'])->name('applications.updateNotes');
        Route::post('/applications/bulk-update', [App\Http\Controllers\Reviewer\ApplicationReviewController::class, 'bulkUpdate'])->name('applications.bulkUpdate');
    });
});