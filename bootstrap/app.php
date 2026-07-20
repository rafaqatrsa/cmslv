<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\BranchMiddleware;
use App\Http\Middleware\FinancialYearMiddleware;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\EnsureUserIsTeacher;
use App\Http\Middleware\EnsureStudentOrParent;
use App\Http\Middleware\PermissionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'branch' => BranchMiddleware::class,
            'financial.year' => FinancialYearMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'student.parent' => EnsureStudentOrParent::class,
            'teacher' => EnsureUserIsTeacher::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
