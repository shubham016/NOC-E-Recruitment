<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ReviewerAuthController;
use App\Http\Controllers\Auth\CandidateAuthController;
use App\Http\Controllers\RegistrationFormController;
use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\Reviewer\ReviewerDashboardController;
use App\Http\Controllers\Reviewer\ApplicationReviewController;
use App\Http\Controllers\Admin\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('candidate.dashboard');
})->name('home');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Jobs...
        // Applications...
        // User Management...

    });
});

/*
|--------------------------------------------------------------------------
| Reviewer Routes
|--------------------------------------------------------------------------
*/
Route::prefix('reviewer')->name('reviewer.')->group(function () {

    Route::get('/login', [ReviewerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ReviewerAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [ReviewerAuthController::class, 'logout'])->name('logout');

    Route::middleware('reviewer')->group(function () {

        Route::get('/dashboard', [ReviewerDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [ApplicationReviewController::class, 'index'])->name('index');
            Route::get('/{id}', [ApplicationReviewController::class, 'show'])->name('show');
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
    
    // Public routes (no authentication required)
    Route::get('/register', [\App\Http\Controllers\CandidateController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\CandidateController::class, 'register'])->name('register.post');
    
    Route::get('/login', [\App\Http\Controllers\CandidateController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\CandidateController::class, 'login'])->name('login.post');
    
    Route::post('/logout', [\App\Http\Controllers\CandidateController::class, 'logout'])->name('logout');

    // Protected routes - use custom middleware
    Route::middleware('candidate.session')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\CandidateController::class, 'dashboard'])->name('dashboard');
        
        Route::get('/my-profile', [\App\Http\Controllers\CandidateController::class, 'profile'])->name('my-profile');
        
        // Change Password Routes - FIXED: Removed duplicate 'candidate/' prefix
        Route::get('/change-password', [\App\Http\Controllers\CandidateController::class, 'showChangePasswordForm'])
            ->name('change-password');
        Route::post('/change-password', [\App\Http\Controllers\CandidateController::class, 'updatePassword'])
            ->name('password.update');
            
        // Applications Routes
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [ApplicationFormController::class, 'index'])->name('index');
            Route::get('/create', [ApplicationFormController::class, 'create'])->name('create');
            Route::post('/', [ApplicationFormController::class, 'store'])->name('store');
            Route::get('/{applicationform}', [ApplicationFormController::class, 'show'])->name('show');
            Route::get('/{applicationform}/edit', [ApplicationFormController::class, 'edit'])->name('edit');
            Route::put('/{applicationform}', [ApplicationFormController::class, 'update'])->name('update');
            Route::delete('/{applicationform}', [ApplicationFormController::class, 'destroy'])->name('destroy');
        });
    });
});