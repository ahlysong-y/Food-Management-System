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
        // ហៅ Middleware ដែលទើបបង្កើតថ្មីមកប្រើនៅទីនេះសម្រាប់ដោះស្រាយ Proxy Vercel
        $middleware->append(\App\Http\Middleware\TrustProxies::class);

        // បើកសិទ្ធិឱ្យ Livewire អាចដំណើរការដោយមិនគាំង CSRF Token លើ Vercel
        $middleware->validateCsrfTokens(except: [
            'livewire/update',
            'api/livewire/update',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
