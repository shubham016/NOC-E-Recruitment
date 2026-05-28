<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ReviewerMiddleware;
use App\Http\Middleware\ApproverMiddleware;
use App\Http\Middleware\CandidateMiddleware;
use App\Http\Middleware\CandidateSessionMiddleware;
use App\Http\Middleware\SetLocale;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            SetLocale::class,
        ]);
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'reviewer' => ReviewerMiddleware::class,
            'approver' => ApproverMiddleware::class,
            'candidate' => CandidateMiddleware::class,
            'candidate.session' => CandidateSessionMiddleware::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        // Automatically close expired vacancies every day at midnight
        $schedule->command('vacancies:close-expired')->daily();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            return redirect()->back()
                ->withInput($request->except(['_token', 'password', 'password_confirmation']))
                ->withErrors(['error' => 'Your session has expired. Please try again.']);
        });
    })->create();
