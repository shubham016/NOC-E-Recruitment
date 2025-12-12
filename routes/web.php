<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ReviewerAuthController;
use App\Http\Controllers\Auth\CandidateAuthController;
use App\Http\Controllers\Reviewer\ReviewerDashboardController;
use App\Http\Controllers\Reviewer\ApplicationReviewController;
use App\Http\Controllers\Admin\AdminApplicationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\JobManagementController;
use App\Http\Controllers\Admin\CandidateManagementController;
use App\Http\Controllers\Candidate\CandidateDashboardController;
use App\Http\Controllers\Candidate\JobBrowsingController;
use App\Http\Controllers\Candidate\ApplicationController as CandidateApplicationController;
use App\Http\Controllers\Candidate\ProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Root route - Redirect to Admin Login
Route::get('/', function () {
    return redirect()->route('admin.login');
})->name('home');

// Default login route - Redirect to Admin Login
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Admin Authentication Routes (Public - No Middleware)
    |--------------------------------------------------------------------------
    */
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Protected Admin Routes (Requires Admin Authentication)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:admin'])->group(function () {

        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        /*
        | Job Management Routes
        */
        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/', [JobManagementController::class, 'index'])->name('index');
            Route::get('/create', [JobManagementController::class, 'create'])->name('create');
            Route::post('/', [JobManagementController::class, 'store'])->name('store');
            Route::get('/{id}', [JobManagementController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [JobManagementController::class, 'edit'])->name('edit');
            Route::put('/{id}', [JobManagementController::class, 'update'])->name('update');
            Route::delete('/{id}', [JobManagementController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/duplicate', [JobManagementController::class, 'duplicate'])->name('duplicate');
            Route::post('/{id}/status', [JobManagementController::class, 'changeStatus'])->name('changeStatus');
        });

        /*
        | Applications Management Routes
        */
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [AdminApplicationController::class, 'index'])->name('index');
            Route::get('/{application}', [AdminApplicationController::class, 'show'])->name('show');
            Route::post('/{application}/update-status', [AdminApplicationController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/{application}/assign-reviewer', [AdminApplicationController::class, 'assignReviewer'])->name('assignReviewer');
            Route::delete('/{application}', [AdminApplicationController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-action', [AdminApplicationController::class, 'bulkAction'])->name('bulkAction');
        });

        /*
        | Candidate Management Routes
        */
        Route::prefix('candidates')->name('candidates.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\CandidateManagementController::class, 'index'])->name('index');
            Route::get('/{id}', [App\Http\Controllers\Admin\CandidateManagementController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\CandidateManagementController::class, 'edit'])->name('edit');
            Route::put('/{id}', [App\Http\Controllers\Admin\CandidateManagementController::class, 'update'])->name('update');
            Route::post('/{id}/status', [App\Http\Controllers\Admin\CandidateManagementController::class, 'updateStatus'])->name('updateStatus');
            Route::delete('/{id}', [App\Http\Controllers\Admin\CandidateManagementController::class, 'destroy'])->name('destroy');
        });

        /*
        | Reviewer Management Routes
        */
        Route::prefix('reviewers')->name('reviewers.')->group(function () {
            Route::get('/', function () {
                return view('admin.reviewers.index');
            })->name('index');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Reviewer Routes
|--------------------------------------------------------------------------
*/
Route::prefix('reviewer')->name('reviewer.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Reviewer Authentication Routes (Public - No Middleware)
    |--------------------------------------------------------------------------
    */
    Route::get('/login', [ReviewerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ReviewerAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [ReviewerAuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Protected Reviewer Routes (Requires Reviewer Authentication)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:reviewer'])->group(function () {

        // Reviewer Dashboard
        Route::get('/dashboard', [ReviewerDashboardController::class, 'index'])->name('dashboard');

        /*
        | Application Review Routes
        */
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

    /*
    |--------------------------------------------------------------------------
    | Candidate Authentication Routes (Public - No Middleware)
    |--------------------------------------------------------------------------
    */
    // Login
    Route::get('/login', [CandidateAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CandidateAuthController::class, 'login'])->name('login.post');

    // Registration
    Route::get('/register', [CandidateAuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [CandidateAuthController::class, 'register'])->name('register.post');

    // Email Verification (OTP)
    Route::get('/verify-otp', [CandidateAuthController::class, 'showVerifyOtpForm'])->name('verify.otp');
    Route::post('/verify-otp', [CandidateAuthController::class, 'verifyOtp'])->name('verify.otp.post');
    Route::post('/resend-otp', [CandidateAuthController::class, 'resendOtp'])->name('resend.otp');

    // Forgot Password
    Route::get('/forgot-password', [CandidateAuthController::class, 'showForgotPasswordForm'])->name('forgot.password');
    Route::post('/forgot-password', [CandidateAuthController::class, 'sendResetOtp'])->name('forgot.password.post');

    // Password Reset OTP Verification
    Route::get('/password/verify-otp', [CandidateAuthController::class, 'showResetOtpForm'])->name('password.verify-otp');
    Route::post('/password/verify-otp', [CandidateAuthController::class, 'verifyResetOtp'])->name('password.verify-otp.post');
    Route::post('/password/resend-otp', [CandidateAuthController::class, 'resendResetOtp'])->name('password.resend-otp');

    // Reset Password
    Route::get('/password/reset', [CandidateAuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/password/reset', [CandidateAuthController::class, 'resetPassword'])->name('password.reset.post');

    // Logout
    Route::post('/logout', [CandidateAuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Protected Candidate Routes (Requires Candidate Authentication)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:candidate'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [CandidateDashboardController::class, 'index'])->name('dashboard');

        // Jobs Browsing
        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/', [JobBrowsingController::class, 'index'])->name('index');
            Route::get('/{id}', [JobBrowsingController::class, 'show'])->name('show');

            // Application Routes (nested under jobs for create/store/edit/update)
            Route::prefix('{jobId}/applications')->name('applications.')->group(function () {
                Route::get('/create', [CandidateApplicationController::class, 'create'])->name('create');
                Route::post('/', [CandidateApplicationController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [CandidateApplicationController::class, 'edit'])->name('edit');
                Route::put('/{id}', [CandidateApplicationController::class, 'update'])->name('update');
            });
        });

        // My Applications Routes (Direct access for list/show/delete)
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [CandidateApplicationController::class, 'index'])->name('index');
            Route::get('/{id}', [CandidateApplicationController::class, 'show'])->name('show');
            Route::delete('/{id}', [CandidateApplicationController::class, 'destroy'])->name('destroy');
        });

        // Profile Routes
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'show'])->name('show');
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
        });

        // Settings Routes
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [App\Http\Controllers\Candidate\SettingsController::class, 'index'])->name('index');
            Route::put('/account', [App\Http\Controllers\Candidate\SettingsController::class, 'updateAccount'])->name('account.update');
            Route::put('/password', [App\Http\Controllers\Candidate\SettingsController::class, 'updatePassword'])->name('password.update');
            Route::put('/notifications', [App\Http\Controllers\Candidate\SettingsController::class, 'updateNotifications'])->name('notifications.update');
            Route::put('/privacy', [App\Http\Controllers\Candidate\SettingsController::class, 'updatePrivacy'])->name('privacy.update');
            Route::delete('/account', [App\Http\Controllers\Candidate\SettingsController::class, 'deleteAccount'])->name('account.delete');
        });
    });
});