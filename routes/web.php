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

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */
    Route::get('/login', [CandidateAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CandidateAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [CandidateAuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Protected Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:candidate')->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            return view('candidate.dashboard');
        })->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Application Form Routes (with Model Binding)
        |--------------------------------------------------------------------------
        */
        Route::prefix('applications')->name('applications.')->group(function () {

            Route::get('/', [ApplicationFormController::class, 'index'])->name('index');
            Route::get('/create', [ApplicationFormController::class, 'create'])->name('create');
            Route::post('/', [ApplicationFormController::class, 'store'])->name('store');

            // Model binding: {applicationform} matches controller parameter
            Route::get('/{applicationform}', [ApplicationFormController::class, 'show'])->name('show');
            Route::get('/{applicationform}/edit', [ApplicationFormController::class, 'edit'])->name('edit');
            Route::put('/{applicationform}', [ApplicationFormController::class, 'update'])->name('update');
            Route::delete('/{applicationform}', [ApplicationFormController::class, 'destroy'])->name('destroy');

        });

    });

});