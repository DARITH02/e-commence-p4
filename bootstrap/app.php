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
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'checkout/*',
            'payment/*', // Allow all payment routes (including simulation) to bipass CSRF
        ]);

        // Enable CORS for API and specified origins
        // Laravel 11/12 uses State of the art CORS handling.
        // We'll also exclude checkout/process from CSRF if keeping in web, 
        // but it's better to move it. For now, let's at least enable CORS.
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
