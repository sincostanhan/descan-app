<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Daftarkan Tenancy Middleware di grup web
        $middleware->web(append: [
            \App\Http\Middleware\InitializeTenancy::class,
        ]);
        // Alias middleware Spatie dan Custom
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'role.bps' => \App\Http\Middleware\EnsureUserIsAdminBps::class,
            'role.kelurahan' => \App\Http\Middleware\EnsureUserIsAdminKelurahan::class,
            'setup.check' => \App\Http\Middleware\CheckInitialSetup::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
