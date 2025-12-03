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
use App\Http\Controllers\Candidate\CandidateDashboardController;
use App\Http\Controllers\Candidate\JobBrowsingController;
use App\Http\Controllers\Candidate\ApplicationController as CandidateApplicationController;
use App\Http\Controllers\Candidate\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
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