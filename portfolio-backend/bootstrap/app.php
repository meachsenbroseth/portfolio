<?php

use App\Http\Middleware\IsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
    // Remove or comment the problematic line:
    // $middleware->statefulApi();

    // Explicitly add the required middleware for stateful API (Sanctum cookie auth)
    $middleware->api(prepend: [
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ]);

    // If you still want to keep the alias for admin middleware
    // $middleware->alias(['admin' => IsAdmin::class]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
