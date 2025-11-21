<?php

use App\Http\Middleware\Auth;
use App\Http\Middleware\ContentSecurityPolicy;
use App\Http\Middleware\FrontendAuthMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::redirect('/admin', 'admin/login');
            Route::middleware(['web', HandleInertiaRequests::class])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            Route::middleware(['web', HandleInertiaRequests::class])
                ->name('frontend.')
                ->group(base_path('routes/frontend.php'));
        },
    )
    ->withBroadcasting(
        __DIR__ . '/../routes/channels.php',
        ['prefix' => 'api', 'middleware' => ['api', 'auth:sanctum']],
    )
    ->withBroadcasting(
        __DIR__ . '/../routes/channels.php',
        ['middleware' => ['web', 'auth']],
    )
    ->withMiddleware(function (Middleware $middleware) {
       $middleware->web(append: [
            HandleInertiaRequests::class,
            // ContentSecurityPolicy::class
        ]);
        $middleware->alias([
            'auth' => Auth::class,
            'isAdmin' => IsAdmin::class,
            'frontend.auth' => FrontendAuthMiddleware::class,
            'ContentSecurityPolicy' => ContentSecurityPolicy::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})->create();
