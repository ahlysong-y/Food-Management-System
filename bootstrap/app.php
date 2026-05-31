<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ប្រើប្រាស់មុខងារ Trust Proxies របស់ Laravel 11 សម្រាប់ Vercel
        $middleware->trustProxies(at: '*');

        // បើកសិទ្ធិឱ្យ Livewire អាចដំណើរការដោយមិនគាំង CSRF Token លើ Vercel
        $middleware->validateCsrfTokens(except: [
            'livewire/update',
            'api/livewire/update',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
