<?php

namespace App\Providers;

use App\Models\ApplicationForm;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Route model binding: {application} resolves to ApplicationForm
        Route::model('application', ApplicationForm::class);
    }
}
