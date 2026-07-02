<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ReviewerAuthController;
use App\Http\Controllers\Auth\CandidateAuthController;
use App\Http\Controllers\Reviewer\ReviewerDashboardController;
use App\Http\Controllers\Reviewer\ApplicationReviewController;
use App\Http\Controllers\Reviewer\NotificationController as ReviewerNotificationController;
use App\Http\Controllers\Reviewer\MyProfileController as ReviewerMyProfileController;
use App\Http\Controllers\Admin\AdminApplicationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\VacancyManagementController;
use App\Http\Controllers\Admin\CandidateManagementController;
use App\Http\Controllers\Admin\ApproverController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Candidate\CandidateDashboardController;
use App\Http\Controllers\Candidate\VacancyBrowsingController;
use App\Http\Controllers\Candidate\ApplicationFormController as CandidateApplicationController;
use App\Http\Controllers\Candidate\AdmitCardController;
use App\Http\Controllers\Candidate\CandidateResultController;
use App\Http\Controllers\Candidate\NotificationController;
use App\Http\Controllers\Approver\ApproverAuthController;
use App\Http\Controllers\Approver\AssignedToMeController;
use App\Http\Controllers\Approver\NotificationController as ApproverNotificationController;
use App\Http\Controllers\Candidate\JobBrowsingController;
use App\Http\Controllers\PaymentController as ShradhaPaymentController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\Approver\MyProfileController as ApproverMyProfileController;
use App\Http\Controllers\Admin\SmsController as AdminSmsController;
use App\Http\Controllers\Candidate\CandidateProfileController;
use App\Http\Controllers\Candidate\SmsController as CandidateSmsController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Language Switcher
Route::post('/language/switch', function (\Illuminate\Http\Request $request) {
    $locale = $request->input('locale', 'en');
    if (in_array($locale, ['en', 'ne'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');

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
        | Profile Routes
        */
        Route::get('/profile', [App\Http\Controllers\Admin\AdminProfileController::class, 'show'])->name('profile');
        Route::get('/profile/edit', [App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('profile.update');
        Route::get('/change-password', [App\Http\Controllers\Admin\AdminProfileController::class, 'showChangePasswordForm'])->name('change-password');
        Route::post('/change-password', [App\Http\Controllers\Admin\AdminProfileController::class, 'changePassword'])->name('change-password.post');
        Route::get('/settings', [App\Http\Controllers\Admin\AdminProfileController::class, 'settings'])->name('settings');

        /*
        | Admit Card Routes
        */
        Route::prefix('admit-card')->name('admit-card.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AdmitCardController::class, 'index'])->name('index');
            Route::get('/{job_posting_id}/assign', [App\Http\Controllers\Admin\AdmitCardController::class, 'assign'])->name('assign');
            Route::post('/{job_posting_id}/store', [App\Http\Controllers\Admin\AdmitCardController::class, 'store'])->name('store');
            Route::get('/{job_posting_id}/preview', [App\Http\Controllers\Admin\AdmitCardController::class, 'preview'])->name('preview');
        });

        // Reports
        Route::get('/reports', [App\Http\Controllers\Admin\AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/preview/applications', [App\Http\Controllers\Admin\AdminReportController::class, 'previewApplications'])->name('reports.preview.applications');
        Route::get('/reports/preview/candidates',   [App\Http\Controllers\Admin\AdminReportController::class, 'previewCandidates'])->name('reports.preview.candidates');
        Route::get('/reports/preview/vacancies',    [App\Http\Controllers\Admin\AdminReportController::class, 'previewVacancies'])->name('reports.preview.vacancies');
        Route::get('/reports/preview/reviewers',    [App\Http\Controllers\Admin\AdminReportController::class, 'previewReviewers'])->name('reports.preview.reviewers');
        Route::get('/reports/preview/approvers',    [App\Http\Controllers\Admin\AdminReportController::class, 'previewApprovers'])->name('reports.preview.approvers');
        Route::get('/reports/download/applications',[App\Http\Controllers\Admin\AdminReportController::class, 'downloadApplications'])->name('reports.download.applications');
        Route::get('/reports/download/candidates',  [App\Http\Controllers\Admin\AdminReportController::class, 'downloadCandidates'])->name('reports.download.candidates');
        Route::get('/reports/download/vacancies',   [App\Http\Controllers\Admin\AdminReportController::class, 'downloadVacancies'])->name('reports.download.vacancies');
        Route::get('/reports/download/reviewers',   [App\Http\Controllers\Admin\AdminReportController::class, 'downloadReviewers'])->name('reports.download.reviewers');
        Route::get('/reports/download/approvers',   [App\Http\Controllers\Admin\AdminReportController::class, 'downloadApprovers'])->name('reports.download.approvers');

        /*
        | Job Management Routes
        */
        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/', [VacancyManagementController::class, 'index'])->name('index');
            Route::get('/lookup-position', [VacancyManagementController::class, 'lookupByPosition'])->name('lookupPosition');
            Route::get('/create', [VacancyManagementController::class, 'create'])->name('create');
            Route::get('/download', [VacancyManagementController::class, 'downloadPDF'])->name('download');
            Route::get('/preview', [VacancyManagementController::class, 'previewPDF'])->name('preview');
            Route::get('/download-excel', [VacancyManagementController::class, 'downloadExcel'])->name('download-excel');
            Route::post('/', [VacancyManagementController::class, 'store'])->name('store');
            Route::get('/{id}', [VacancyManagementController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [VacancyManagementController::class, 'edit'])->name('edit');
            Route::put('/{id}', [VacancyManagementController::class, 'update'])->name('update');
            Route::delete('/{id}', [VacancyManagementController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/duplicate', [VacancyManagementController::class, 'duplicate'])->name('duplicate');
            Route::post('/{id}/status', [VacancyManagementController::class, 'changeStatus'])->name('changeStatus');
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
            Route::post('/{application}/assign-approver', [AdminApplicationController::class, 'assignApprover'])->name('assignApprover');
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
            Route::post('/{id}/assign-vacancy', [ApproverController::class, 'assignVacancy'])->name('assign-vacancy');
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

        /*
        | SMS Management Routes
        */
        Route::prefix('sms')->name('sms.')->group(function () {
            Route::get('/', [AdminSmsController::class, 'index'])->name('index');
            Route::get('/create', [AdminSmsController::class, 'create'])->name('create');
            Route::post('/', [AdminSmsController::class, 'store'])->name('store');
            Route::get('/applicants', [AdminSmsController::class, 'getApplicants'])->name('applicants');
            Route::get('/{smsLog}', [AdminSmsController::class, 'show'])->name('show');
        });

        /*
        | Audit Logs
        */
        Route::get('/audit', [App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit.index');
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
        Route::get('/my-profile', [ReviewerMyProfileController::class, 'index'])->name('myprofile');
        // Change Password
        Route::post('/change-password', [ReviewerMyProfileController::class, 'changePassword'])->name('change.password');
        // Notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [ReviewerNotificationController::class, 'index'])->name('index');
            Route::post('/{id}/mark-as-read', [ReviewerNotificationController::class, 'markAsRead'])->name('markAsRead');
            Route::post('/mark-all-as-read', [ReviewerNotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
            Route::delete('/{id}', [ReviewerNotificationController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [ApplicationReviewController::class, 'index'])->name('index');
            Route::get('/export-csv', [ApplicationReviewController::class, 'exportCsv'])->name('exportCsv');
            Route::get('/export-pdf', [ApplicationReviewController::class, 'exportPdf'])->name('exportPdf');
            Route::get('/{id}', [ApplicationReviewController::class, 'show'])->name('show');
            Route::post('/{id}/status', [ApplicationReviewController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/bulk-update', [ApplicationReviewController::class, 'bulkUpdate'])->name('bulkUpdate');
        });

        // My Profile
        Route::get('/myprofile', [ReviewerMyProfileController::class, 'index'])->name('myprofile.legacy');
        Route::post('/myprofile/update', [ReviewerMyProfileController::class, 'update'])->name('myprofile.update');

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
    Route::middleware('approver')->group(function () {

        // Dashboard
        Route::get('/dashboard', [ApproverAuthController::class, 'dashboard'])->name('dashboard');

        // Logout
        Route::post('/logout', [ApproverAuthController::class, 'logout'])->name('logout');

        // Notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Approver\NotificationController::class, 'index'])->name('index');
            Route::post('/{id}/mark-as-read', [\App\Http\Controllers\Approver\NotificationController::class, 'markAsRead'])->name('markAsRead');
            Route::post('/mark-all-as-read', [\App\Http\Controllers\Approver\NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
            Route::delete('/{id}', [\App\Http\Controllers\Approver\NotificationController::class, 'destroy'])->name('destroy');
        });

        // Assigned Applications
        Route::get('/assigned-to-me', [AssignedToMeController::class, 'index'])->name('assignedtome');
        Route::get('/applications/export-csv', [AssignedToMeController::class, 'exportCsv'])->name('applications.exportCsv');
        Route::get('/applications/export-pdf', [AssignedToMeController::class, 'exportPdf'])->name('applications.exportPdf');
        Route::get('/applications/{id}', [AssignedToMeController::class, 'show'])->name('applications.show');
        Route::post('/applications/{id}/status', [AssignedToMeController::class, 'updateStatus'])->name('applications.updateStatus');

        // My Profile
        Route::get('/my-profile', [ApproverMyProfileController::class, 'index'])->name('myprofile');

        // Change Password for Approver
        Route::post('/change-password', [ApproverMyProfileController::class, 'changePassword'])->name('change.password');

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
    Route::get('/login', [CandidateController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CandidateController::class, 'login'])->name('login.post');

    // Registration
    Route::get('/register', [CandidateController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [CandidateController::class, 'register'])->name('register.post');
    Route::get('/verify-otp', [CandidateController::class, 'showVerifyOtpForm'])->name('verify.otp');
    Route::post('/verify-otp', [CandidateController::class, 'verifyOtp'])->name('verify.otp.post');
    Route::post('/resend-otp', [CandidateController::class, 'resendOtp'])->name('resend.otp');

    // Forgot Password
    Route::get('/forgot-password', [CandidateController::class, 'showForgotPasswordForm'])->name('forgot.password');
    Route::post('/forgot-password', [CandidateController::class, 'sendResetLink'])->name('forgot.password.post');

    // Reset Password (token-based)
    Route::get('/reset-password/{token}', [CandidateController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [CandidateController::class, 'resetPassword'])->name('password.reset.post');

    // Logout
    Route::post('/logout', [CandidateController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Protected Candidate Routes (Requires Candidate Authentication)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['candidate.session'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [CandidateController::class, 'dashboard'])->name('dashboard');

        // Notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::post('/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
            Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
            Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        });

        // My Profile Rou
        Route::get('/my-profile/edit',   [CandidateProfileController::class, 'edit'])->name('my-profile.edit');
        Route::put('/my-profile/update', [CandidateProfileController::class, 'update'])->name('my-profile.update');

        // Vacancies Browsing (HEAD's VacancyBrowsingController)
        Route::prefix('vacancies')->name('vacancies.')->group(function () {
            Route::get('/', [VacancyBrowsingController::class, 'index'])->name('index');
            Route::get('/{id}', [VacancyBrowsingController::class, 'show'])->name('show');

            // Application Routes (nested under vacancies for create/store/edit/update)
            Route::prefix('{vacancyId}/applications')->name('applications.')->group(function () {
                Route::get('/create', [CandidateApplicationController::class, 'create'])->name('create');
                Route::post('/', [CandidateApplicationController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [CandidateApplicationController::class, 'edit'])->name('edit');
                Route::put('/{id}', [CandidateApplicationController::class, 'update'])->name('update');
            });

            // Eligibility check (AJAX)
            Route::get('/{vacancyId}/check-eligibility', [CandidateApplicationController::class, 'checkEligibilityAjax'])->name('checkEligibility');
        });

        // Jobs Browsing (shradha's JobBrowsingController)
        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/', [JobBrowsingController::class, 'index'])->name('index');
            Route::get('/{id}', [JobBrowsingController::class, 'show'])->name('show');
            Route::get('/{jobId}/check-eligibility', [CandidateApplicationController::class, 'checkEligibility'])->name('check-eligibility');

            // Application Routes (nested under jobs)
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
            Route::post('/', [CandidateApplicationController::class, 'store'])->name('store');
            Route::get('/{applicationform}', [CandidateApplicationController::class, 'show'])->name('show');
            Route::get('/{applicationform}/edit', [CandidateApplicationController::class, 'edit'])->name('edit');
            Route::put('/{applicationform}', [CandidateApplicationController::class, 'update'])->name('update');
            Route::delete('/{applicationform}', [CandidateApplicationController::class, 'destroy'])->name('destroy');
        });

        // Payment Routes (HEAD's eSewa PaymentController)
        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('/esewa/start/{draftId}', [ShradhaPaymentController::class, 'startEsewa'])->name('esewa.start');
            Route::get('/esewa/success', [ShradhaPaymentController::class, 'esewaSuccess'])->name('esewa.success');
            Route::get('/esewa/failure', [ShradhaPaymentController::class, 'esewaFailure'])->name('esewa.failure');

            Route::get('/khalti/start/{draftId}', [ShradhaPaymentController::class, 'startKhalti'])->name('khalti.start');
            Route::post('/khalti/verify', [ShradhaPaymentController::class, 'verifyKhalti'])->name('khalti.verify');
            Route::get('/khalti/success', [ShradhaPaymentController::class, 'khaltiSuccess'])->name('khalti.success');

            Route::get('/connectips/start/{draftId}', [ShradhaPaymentController::class, 'startConnectIps'])->name('connectips.start');
            Route::get('/connectips/success', [ShradhaPaymentController::class, 'connectipsSuccess'])->name('connectips.success');
            Route::get('/connectips/failure', [ShradhaPaymentController::class, 'connectipsFailure'])->name('connectips.failure');
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
        Route::get('/change-password', [CandidateController::class, 'showChangePasswordForm'])->name('change-password');
        Route::post('/change-password', [CandidateController::class, 'updatePassword'])->name('change-password.post');
        Route::get('/my-profile',      [CandidateProfileController::class, 'show'])->name('my-profile');
        Route::get('/edit-profile',    [CandidateProfileController::class, 'edit'])->name('edit-profile');
                // ApplicationForm routes (shradha's flat application routes)
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::post('/save-draft', [CandidateApplicationController::class, 'saveDraft'])->name('saveDraft');
        });
    });
});
