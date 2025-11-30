<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ReviewerAuthController;
use App\Http\Controllers\Auth\CandidateAuthController;
use App\Http\Controllers\Reviewer\ReviewerDashboardController;
use App\Http\Controllers\Reviewer\ApplicationReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public route
Route::get('/', function () {
    return view('welcome');
})->name('home');


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Authentication Routes (Public)
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');

        // Job Management
        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\JobManagementController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\JobManagementController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\JobManagementController::class, 'store'])->name('store');
            Route::get('/{id}', [App\Http\Controllers\Admin\JobManagementController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\JobManagementController::class, 'edit'])->name('edit');
            Route::put('/{id}', [App\Http\Controllers\Admin\JobManagementController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\JobManagementController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/duplicate', [App\Http\Controllers\Admin\JobManagementController::class, 'duplicate'])->name('duplicate');
            Route::post('/{id}/status', [App\Http\Controllers\Admin\JobManagementController::class, 'changeStatus'])->name('changeStatus');
        });

        // Applications Management (MOVED INSIDE MIDDLEWARE)
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ApplicationController::class, 'index'])->name('index');
            Route::get('/{id}', [App\Http\Controllers\Admin\ApplicationController::class, 'show'])->name('show');
            Route::post('/{id}/status', [App\Http\Controllers\Admin\ApplicationController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/{id}/assign-reviewer', [App\Http\Controllers\Admin\ApplicationController::class, 'assignReviewer'])->name('assignReviewer');
            Route::post('/bulk-update', [App\Http\Controllers\Admin\ApplicationController::class, 'bulkUpdate'])->name('bulkUpdate');
            Route::get('/{id}/download-resume', [App\Http\Controllers\Admin\ApplicationController::class, 'downloadResume'])->name('downloadResume');
            Route::get('/export/csv', [App\Http\Controllers\Admin\ApplicationController::class, 'export'])->name('export');
        });

        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/reviewers', function () {
                return view('admin.users.reviewers');
            })->name('reviewers');
            Route::get('/candidates', function () {
                return view('admin.users.candidates');
            })->name('candidates');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Reviewer Routes
|--------------------------------------------------------------------------
*/
Route::prefix('reviewer')->name('reviewer.')->group(function () {
    // Authentication Routes
    Route::get('/login', [ReviewerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ReviewerAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [ReviewerAuthController::class, 'logout'])->name('logout');

    // Protected Reviewer Routes
    Route::middleware('reviewer')->group(function () {
        // Dashboard
        Route::get('/dashboard', [ReviewerDashboardController::class, 'index'])->name('dashboard');

        // Application Management Routes
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [ApplicationReviewController::class, 'index'])->name('index');
            Route::get('/{id}', [ApplicationReviewController::class, 'show'])->name('show');
            Route::get('/{id}/details', [ApplicationReviewController::class, 'getDetails'])->name('details');
            Route::post('/{id}/status', [ApplicationReviewController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/bulk-update', [ApplicationReviewController::class, 'bulkUpdate'])->name('bulkUpdate');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Candidate Routes
|--------------------------------------------------------------------------
*/
Route::prefix('candidate')->name('candidate.')->group(function () {
    // Authentication Routes
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