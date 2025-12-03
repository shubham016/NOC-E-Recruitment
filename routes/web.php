<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ReviewerAuthController;
use App\Http\Controllers\Auth\CandidateAuthController;
use App\Http\Controllers\Auth\HRAdministratorAuthController;
use App\Http\Controllers\Reviewer\ReviewerDashboardController;
use App\Http\Controllers\Reviewer\ApplicationReviewController;
use App\Http\Controllers\Admin\AdminApplicationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\JobManagementController;
use App\Http\Controllers\Candidate\CandidateDashboardController;
use App\Http\Controllers\Candidate\JobBrowsingController;
use App\Http\Controllers\Candidate\ApplicationController as CandidateApplicationController;
use App\Http\Controllers\Candidate\ProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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
        | User Management Routes
        */
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
            Route::get('/export-csv', [ApplicationReviewController::class, 'exportCsv'])->name('exportCsv');
            Route::get('/export-pdf', [ApplicationReviewController::class, 'exportPdf'])->name('exportPdf');
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

        // Export routes
        Route::get('/applications/export-csv', [AssignedToMeController::class, 'exportCsv'])->name('applications.exportCsv');
        Route::get('/applications/export-pdf', [AssignedToMeController::class, 'exportPdf'])->name('applications.exportPdf');


        // Assigned Applications
        Route::get('/assigned-to-me', [AssignedToMeController::class, 'index'])->name('assignedtome');
        Route::get('/applications/{id}', [AssignedToMeController::class, 'show'])->name('applications.show');
        Route::post('/applications/{id}/status', [AssignedToMeController::class, 'updateStatus'])->name('applications.updateStatus');

        // My Profile
        Route::get('/my-profile', [MyProfileController::class, 'index'])->name('myprofile');

        
        // Password Change
        Route::post('/change-password', [MyProfileController::class, 'changePassword'])->name('change.password');

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
    Route::get('/login', [CandidateAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CandidateAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [CandidateAuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Protected Candidate Routes (Requires Candidate Authentication)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:candidate'])->group(function () {

        // Candidate Dashboard
        Route::get('/dashboard', [CandidateDashboardController::class, 'index'])->name('dashboard');

        /*
        | Job Browsing Routes
        */
        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/', [JobBrowsingController::class, 'index'])->name('index');
            Route::get('/{id}', [JobBrowsingController::class, 'show'])->name('show');

            // Application Routes (nested under jobs)
            Route::get('/{id}/apply', [CandidateApplicationController::class, 'create'])->name('applications.create');
            Route::post('/{id}/apply', [CandidateApplicationController::class, 'store'])->name('applications.store');
        });

        /*
        | My Applications Routes
        */
        Route::prefix('my-applications')->name('applications.')->group(function () {
            Route::get('/', [CandidateApplicationController::class, 'index'])->name('index');
            Route::get('/{id}', [CandidateApplicationController::class, 'show'])->name('show');
        });

        /*
        | Profile Routes
        */
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
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
            $output .= '<p style="color:red;"><strong>Found potential duplicates:</strong></p>';
            $output .= '<pre>' . print_r($duplicates, true) . '</pre>';
        } else {
            $output .= '<p style="color:green;">No duplicates found</p>';
        }

        $output .= '</body></html>';

        return $output;
    })->name('debug.notifications');
}