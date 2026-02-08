<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ReviewerAuthController;
use App\Http\Controllers\Auth\CandidateAuthController;
use App\Http\Controllers\Auth\HRAdministratorAuthController;
use App\Http\Controllers\Reviewer\ReviewerDashboardController;
use App\Http\Controllers\Reviewer\ApplicationReviewController;
use App\Http\Controllers\Admin\AdminApplicationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\JobManagementController;
use App\Http\Controllers\Admin\CandidateManagementController;
use App\Http\Controllers\Admin\HRAdministratorController;
use App\Http\Controllers\HRAdministrator\ProfileController;
use App\Http\Controllers\HRAdministrator\HRAdministratorDashboardController;
use App\Http\Controllers\HRAdministrator\HRJobController;
use App\Http\Controllers\HRAdministrator\HRApplicationController;
use App\Http\Controllers\HRAdministrator\HRCandidateController;
use App\Http\Controllers\HRAdministrator\HRReviewerController;
use App\Http\Controllers\Candidate\CandidateDashboardController;
use App\Http\Controllers\Candidate\JobBrowsingController;
use App\Http\Controllers\Candidate\ApplicationController as CandidateApplicationController;

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
})->name('login');

// Language Switcher Route (accessible to all users)
Route::get('/language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch');

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
    Route::middleware(['admin'])->group(function () {

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
            Route::get('/', [CandidateManagementController::class, 'index'])->name('index');
            Route::get('/{id}', [CandidateManagementController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [CandidateManagementController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CandidateManagementController::class, 'update'])->name('update');
            Route::post('/{id}/status', [CandidateManagementController::class, 'updateStatus'])->name('updateStatus');
            Route::delete('/{id}', [CandidateManagementController::class, 'destroy'])->name('destroy');
        });

        /*
        | Reviewer Management Routes
        */
        Route::prefix('reviewers')->name('reviewers.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ReviewerController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\ReviewerController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\ReviewerController::class, 'store'])->name('store');
            Route::get('/{id}', [App\Http\Controllers\Admin\ReviewerController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\ReviewerController::class, 'edit'])->name('edit');
            Route::put('/{id}', [App\Http\Controllers\Admin\ReviewerController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\ReviewerController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [App\Http\Controllers\Admin\ReviewerController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{id}/reset-password', [App\Http\Controllers\Admin\ReviewerController::class, 'resetPassword'])->name('reset-password');
        });

        /*
        | HR Administrator Management Routes
        */
        Route::prefix('hr-administrators')->name('hr-administrators.')->group(function () {
            Route::get('/', [HRAdministratorController::class, 'index'])->name('index');
            Route::get('/create', [HRAdministratorController::class, 'create'])->name('create');
            Route::post('/', [HRAdministratorController::class, 'store'])->name('store');
            Route::get('/{id}', [HRAdministratorController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [HRAdministratorController::class, 'edit'])->name('edit');
            Route::put('/{id}', [HRAdministratorController::class, 'update'])->name('update');
            Route::delete('/{id}', [HRAdministratorController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [HRAdministratorController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{id}/reset-password', [HRAdministratorController::class, 'resetPassword'])->name('reset-password');
        });
    });
});

/*
|--------------------------------------------------------------------------
| HR Administrator Routes
|--------------------------------------------------------------------------
| IMPORTANT: These routes use SEPARATE controllers from Admin routes
| to avoid authentication conflicts
|--------------------------------------------------------------------------
*/
Route::prefix('hr-administrator')->name('hr-administrator.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | HR Administrator Authentication Routes (Public - No Middleware)
    |--------------------------------------------------------------------------
    */
    Route::get('/login', [HRAdministratorAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [HRAdministratorAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [HRAdministratorAuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Protected HR Administrator Routes (Requires HR Administrator Authentication)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['hr_administrator'])->group(function () {

        // HR Administrator Dashboard
        Route::get('/dashboard', [HRAdministratorDashboardController::class, 'index'])->name('dashboard');

        /*
        | Profile Routes
        */
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'show'])->name('show');
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
            Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        });

        /*
        | Job Management Routes - Using HRJobController (NOT Admin's JobManagementController)
        */
        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/', [HRJobController::class, 'index'])->name('index');
            Route::get('/create', [HRJobController::class, 'create'])->name('create');
            Route::post('/', [HRJobController::class, 'store'])->name('store');
            Route::get('/{id}', [HRJobController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [HRJobController::class, 'edit'])->name('edit');
            Route::put('/{id}', [HRJobController::class, 'update'])->name('update');
            Route::delete('/{id}', [HRJobController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/duplicate', [HRJobController::class, 'duplicate'])->name('duplicate');
            Route::post('/{id}/status', [HRJobController::class, 'changeStatus'])->name('changeStatus');
        });

        /*
        | Applications Management Routes - Using HRApplicationController
        */
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [HRApplicationController::class, 'index'])->name('index');
            Route::get('/{application}', [HRApplicationController::class, 'show'])->name('show');
            Route::post('/{application}/update-status', [HRApplicationController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/{application}/assign-reviewer', [HRApplicationController::class, 'assignReviewer'])->name('assignReviewer');
            Route::delete('/{application}', [HRApplicationController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-action', [HRApplicationController::class, 'bulkAction'])->name('bulkAction');
        });

        /*
        | Candidate Management Routes - Using HRCandidateController
        */
        Route::prefix('candidates')->name('candidates.')->group(function () {
            Route::get('/', [HRCandidateController::class, 'index'])->name('index');
            Route::get('/{id}', [HRCandidateController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [HRCandidateController::class, 'edit'])->name('edit');
            Route::put('/{id}', [HRCandidateController::class, 'update'])->name('update');
            Route::post('/{id}/status', [HRCandidateController::class, 'updateStatus'])->name('updateStatus');
            Route::delete('/{id}', [HRCandidateController::class, 'destroy'])->name('destroy');
        });

        /*
        | Reviewer Management Routes - Using HRReviewerController
        */
        Route::prefix('reviewers')->name('reviewers.')->group(function () {
            Route::get('/', [HRReviewerController::class, 'index'])->name('index');
            Route::get('/create', [HRReviewerController::class, 'create'])->name('create');
            Route::post('/', [HRReviewerController::class, 'store'])->name('store');
            Route::get('/{id}', [HRReviewerController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [HRReviewerController::class, 'edit'])->name('edit');
            Route::put('/{id}', [HRReviewerController::class, 'update'])->name('update');
            Route::delete('/{id}', [HRReviewerController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [HRReviewerController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{id}/reset-password', [HRReviewerController::class, 'resetPassword'])->name('reset-password');
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
    Route::middleware(['reviewer'])->group(function () {

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
    Route::middleware(['candidate'])->group(function () {

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
            Route::get('/', [App\Http\Controllers\Candidate\ProfileController::class, 'show'])->name('show');
            Route::get('/edit', [App\Http\Controllers\Candidate\ProfileController::class, 'edit'])->name('edit');
            Route::put('/', [App\Http\Controllers\Candidate\ProfileController::class, 'update'])->name('update');
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



//test

Route::get('/test-translation', function () {
    return view('test-translation');
});