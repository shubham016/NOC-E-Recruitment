<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ReviewerAuthController;
use App\Http\Controllers\Auth\CandidateAuthController;
use App\Http\Controllers\Auth\HRAdministratorAuthController;
use App\Http\Controllers\Reviewer\ReviewerDashboardController;
use App\Http\Controllers\Reviewer\ApplicationReviewController;
use App\Http\Controllers\Reviewer\NotificationController as ReviewerNotificationController;
use App\Http\Controllers\Admin\AdminApplicationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\JobManagementController;
use App\Http\Controllers\Admin\CandidateManagementController;
use App\Http\Controllers\Admin\HRAdministratorController;
use App\Http\Controllers\Admin\ApproverController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\HRAdministrator\ProfileController;
use App\Http\Controllers\HRAdministrator\HRAdministratorDashboardController;
use App\Http\Controllers\HRAdministrator\HRJobController;
use App\Http\Controllers\HRAdministrator\HRApplicationController;
use App\Http\Controllers\HRAdministrator\HRCandidateController;
use App\Http\Controllers\HRAdministrator\HRReviewerController;
use App\Http\Controllers\HRAdministrator\NotificationController as HRNotificationController;
use App\Http\Controllers\Candidate\CandidateDashboardController;
use App\Http\Controllers\Candidate\JobBrowsingController;
use App\Http\Controllers\Candidate\ApplicationFormController as CandidateApplicationController;
use App\Http\Controllers\Candidate\PaymentController;
use App\Http\Controllers\Candidate\AdmitCardController;
use App\Http\Controllers\Candidate\CandidateResultController;
use App\Http\Controllers\Candidate\NotificationController;
use App\Http\Controllers\Approver\ApproverAuthController;
use App\Http\Controllers\Approver\AssignedToMeController;
use App\Http\Controllers\Approver\NotificationController as ApproverNotificationController;

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
            Route::get('/download', [JobManagementController::class, 'downloadPDF'])->name('download');
            Route::get('/preview', [JobManagementController::class, 'previewPDF'])->name('preview');
            Route::get('/download-excel', [JobManagementController::class, 'downloadExcel'])->name('download-excel');
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
            Route::get('/export', [AdminApplicationController::class, 'export'])->name('export');
            Route::get('/{application}', [AdminApplicationController::class, 'show'])->name('show');
            Route::post('/{application}/update-status', [AdminApplicationController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/{application}/assign-reviewer', [AdminApplicationController::class, 'assignReviewer'])->name('assignReviewer');
            Route::post('/{application}/set-priority', [AdminApplicationController::class, 'setPriority'])->name('setPriority');
            Route::delete('/{application}', [AdminApplicationController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-action', [AdminApplicationController::class, 'bulkAction'])->name('bulkAction');
            Route::delete('/{application}/reset-payment', [AdminApplicationController::class, 'resetPayment'])->name('resetPayment');
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

        /*
        | Approver Routes
        */
        Route::prefix('approvers')->name('approvers.')->group(function () {
            Route::get('/', [ApproverController::class, 'index'])->name('index');
            Route::get('/create', [ApproverController::class, 'create'])->name('create');
            Route::post('/', [ApproverController::class, 'store'])->name('store');
            Route::get('/{id}', [ApproverController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [ApproverController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ApproverController::class, 'update'])->name('update');
            Route::delete('/{id}', [ApproverController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [ApproverController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{id}/reset-password', [ApproverController::class, 'resetPassword'])->name('reset-password');
        });

        /*
        | Notification Routes
        */
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
            Route::post('/{id}/mark-as-read', [AdminNotificationController::class, 'markAsRead'])->name('markAsRead');
            Route::post('/mark-all-as-read', [AdminNotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
            Route::delete('/{id}', [AdminNotificationController::class, 'destroy'])->name('destroy');
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
            Route::post('/{application}/set-priority', [HRApplicationController::class, 'setPriority'])->name('setPriority');
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

        /*
        | Notification Routes
        */
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [HRNotificationController::class, 'index'])->name('index');
            Route::post('/{id}/mark-as-read', [HRNotificationController::class, 'markAsRead'])->name('markAsRead');
            Route::post('/mark-all-as-read', [HRNotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
            Route::delete('/{id}', [HRNotificationController::class, 'destroy'])->name('destroy');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Reviewer Routes
|--------------------------------------------------------------------------
*/
Route::prefix('reviewer')->name('reviewer.')->group(function () {

    Route::get('/applications', [ApplicationReviewController::class, 'index'])
        ->name('applications.index');

    Route::get('/applications/export-csv', [ApplicationReviewController::class, 'exportCsv'])
        ->name('applications.exportCsv');

    Route::get('/applications/export-pdf', [ApplicationReviewController::class, 'exportPdf'])
        ->name('applications.exportPdf');

    Route::get('/login', [ReviewerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ReviewerAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [ReviewerAuthController::class, 'logout'])->name('logout');

    Route::middleware('reviewer')->group(function () {

        Route::get('/dashboard', [ReviewerDashboardController::class, 'index'])->name('dashboard');

        // Notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [ReviewerNotificationController::class, 'index'])->name('index');
            Route::post('/{id}/mark-as-read', [ReviewerNotificationController::class, 'markAsRead'])->name('markAsRead');
            Route::post('/mark-all-as-read', [ReviewerNotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
            Route::delete('/{id}', [ReviewerNotificationController::class, 'destroy'])->name('destroy');
        });

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
| Approver Routes
|--------------------------------------------------------------------------
*/
Route::prefix('approver')->name('approver.')->group(function () {

    // Public routes (login)
    Route::get('/login', [ApproverAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ApproverAuthController::class, 'login'])->name('login.post');

    // Protected routes (requires authentication)
    Route::middleware('auth:approver')->group(function () {

        // Dashboard
        Route::get('/dashboard', [ApproverAuthController::class, 'dashboard'])->name('dashboard');

        // Logout
        Route::post('/logout', [ApproverAuthController::class, 'logout'])->name('logout');

        // Notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [ApproverNotificationController::class, 'index'])->name('index');
            Route::post('/{id}/mark-as-read', [ApproverNotificationController::class, 'markAsRead'])->name('markAsRead');
            Route::post('/mark-all-as-read', [ApproverNotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
            Route::delete('/{id}', [ApproverNotificationController::class, 'destroy'])->name('destroy');
        });

        // Assigned Applications
        Route::get('/assigned-to-me', [AssignedToMeController::class, 'index'])->name('assignedtome');
        Route::get('/applications/{id}', [AssignedToMeController::class, 'show'])->name('show');
        Route::post('/applications/{id}/status', [AssignedToMeController::class, 'updateStatus'])->name('updateStatus');

        // Export routes
        Route::get('/applications/export-csv', [AssignedToMeController::class, 'exportCsv'])->name('applications.exportCsv');
        Route::get('/applications/export-pdf', [AssignedToMeController::class, 'exportPdf'])->name('applications.exportPdf');

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

        // Notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::post('/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
            Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
            Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        });

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

            // Eligibility check (AJAX)
            Route::get('/{jobId}/check-eligibility', [CandidateApplicationController::class, 'checkEligibilityAjax'])->name('checkEligibility');
        });

        // My Applications Routes (Direct access for list/show/delete)
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [CandidateApplicationController::class, 'index'])->name('index');
            Route::post('/', [CandidateApplicationController::class, 'storeFlat'])->name('store');
            Route::get('/{id}', [CandidateApplicationController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [CandidateApplicationController::class, 'editFlat'])->name('edit');
            Route::put('/{id}', [CandidateApplicationController::class, 'updateFlat'])->name('update');
            Route::delete('/{id}', [CandidateApplicationController::class, 'destroy'])->name('destroy');
        });

        // Payment Routes (eSewa)
        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('/{applicationId}/esewa', [PaymentController::class, 'showEsewa'])->name('esewa');
            Route::get('/success', [PaymentController::class, 'success'])->name('success');
            Route::get('/failure', [PaymentController::class, 'failure'])->name('failure');
        });

        // Admit Card Routes
        Route::prefix('admit-card')->name('admit-card.')->group(function () {
            Route::get('/', [AdmitCardController::class, 'index'])->name('index');
            Route::get('/{id}', [AdmitCardController::class, 'show'])->name('show');
            Route::get('/{id}/download', [AdmitCardController::class, 'download'])->name('download');
        });

        // Results Routes
        Route::prefix('results')->name('results.')->group(function () {
            Route::get('/', [CandidateResultController::class, 'index'])->name('index');
            Route::get('/search', [CandidateResultController::class, 'search'])->name('search');
            Route::get('/{id}', [CandidateResultController::class, 'show'])->name('show');
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

        /*
        | Compatibility Routes (for shradha's view route names)
        */
        Route::get('/view-result', [CandidateResultController::class, 'index'])->name('viewresult');
        Route::get('/view-result/{id}', [CandidateResultController::class, 'show'])->name('result.show');
        Route::get('/admit-card', [AdmitCardController::class, 'index'])->name('admit-card');
        Route::get('/admit-card/{id}/view', [AdmitCardController::class, 'show'])->name('admit-card.view');
        Route::get('/change-password', [App\Http\Controllers\Candidate\SettingsController::class, 'showChangePassword'])->name('change-password');
        Route::post('/change-password', [App\Http\Controllers\Candidate\SettingsController::class, 'updatePassword'])->name('password.update');
        Route::post('/applications/save-draft', [CandidateApplicationController::class, 'saveDraft'])->name('applications.saveDraft');
        Route::get('/payment/esewa/start/{applicationId}', [PaymentController::class, 'showEsewa'])->name('payment.esewa.start');
    });
});

/*
|--------------------------------------------------------------------------
| Debug Routes (Only in Local Environment)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/debug/notifications', function() {
        $recent = \App\Models\Notification::latest()->take(20)->get();

        $output = '<html><head><title>Notification Debug</title>';
        $output .= '<style>body{font-family:monospace;padding:20px;} table{border-collapse:collapse;width:100%;margin:20px 0;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background:#f2f2f2;} .unread{background:#fff3cd;} .candidate{color:#0056b3;} .reviewer{color:#28a745;}</style>';
        $output .= '</head><body>';
        $output .= '<h1>Notification Debug Panel</h1>';
        $output .= '<p><strong>Total Notifications:</strong> ' . \App\Models\Notification::count() . '</p>';
        $output .= '<p><strong>Candidate:</strong> ' . \App\Models\Notification::where('user_type', 'candidate')->count() . ' | ';
        $output .= '<strong>Reviewer:</strong> ' . \App\Models\Notification::where('user_type', 'reviewer')->count() . ' | ';
        $output .= '<strong>Admin:</strong> ' . \App\Models\Notification::where('user_type', 'admin')->count() . '</p>';
        $output .= '<p><strong>Unread:</strong> ' . \App\Models\Notification::where('is_read', false)->count() . '</p>';
        $output .= '<hr><h2>Recent Notifications (Last 20)</h2>';
        $output .= '<table><thead><tr><th>ID</th><th>User ID</th><th>User Type</th><th>Type</th><th>Title</th><th>Read</th><th>Created</th></tr></thead><tbody>';

        foreach($recent as $n) {
            $rowClass = !$n->is_read ? 'unread' : '';
            $typeClass = $n->user_type === 'candidate' ? 'candidate' : ($n->user_type === 'reviewer' ? 'reviewer' : '');
            $output .= "<tr class='{$rowClass}'>";
            $output .= "<td>{$n->id}</td>";
            $output .= "<td>{$n->user_id}</td>";
            $output .= "<td class='{$typeClass}'><strong>{$n->user_type}</strong></td>";
            $output .= "<td>{$n->type}</td>";
            $output .= "<td>{$n->title}</td>";
            $output .= "<td>" . ($n->is_read ? 'Yes' : '<strong>No</strong>') . "</td>";
            $output .= "<td>{$n->created_at->diffForHumans()}</td>";
            $output .= '</tr>';
        }

        $output .= '</tbody></table>';

        // Check for duplicates
        $output .= '<hr><h2>Duplicate Check</h2>';
        $duplicates = \DB::select("
            SELECT user_id, user_type, type, related_id, COUNT(*) as count
            FROM notifications
            GROUP BY user_id, user_type, type, related_id
            HAVING count > 1
        ");

        if (count($duplicates) > 0) {
            $output .= '<p style="color:red;"><strong>⚠ Found potential duplicates:</strong></p>';
            $output .= '<pre>' . print_r($duplicates, true) . '</pre>';
        } else {
            $output .= '<p style="color:green;">✓ No duplicates found</p>';
        }

        $output .= '</body></html>';

        return $output;
    })->name('debug.notifications');
}
