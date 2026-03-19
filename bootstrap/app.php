<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ReviewerMiddleware;
use App\Http\Middleware\ApproverMiddleware;
use App\Http\Middleware\CandidateMiddleware;
use App\Http\Middleware\HRAdministratorMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'reviewer' => ReviewerMiddleware::class,
            'approver' => ApproverMiddleware::class,
            'candidate' => CandidateMiddleware::class,
            'hr_administrator' => HRAdministratorMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();