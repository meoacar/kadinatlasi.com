<?php

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
        $middleware->alias([
            'cache' => \App\Http\Middleware\CacheResponse::class,
            'premium' => \App\Http\Middleware\CheckPremiumAccess::class,
            'admin.auth' => \App\Http\Middleware\AdminAuthMiddleware::class,
            'admin.security' => \App\Http\Middleware\AdminSecurityMiddleware::class,
            'production.security' => \App\Http\Middleware\ProductionSecurityMiddleware::class,
        ]);
        
        // Production ortamında global middleware ekle
        if (env('APP_ENV') === 'production') {
            $middleware->web(append: [
                \App\Http\Middleware\ProductionSecurityMiddleware::class,
            ]);
        }
        
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
