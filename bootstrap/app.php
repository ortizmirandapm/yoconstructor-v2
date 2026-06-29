<?php

use App\Http\Middleware\EsAdmin;
use App\Http\Middleware\EsEmpresa;
use App\Http\Middleware\EsTrabajador;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'es.empresa' => EsEmpresa::class,
            'es.trabajador' => EsTrabajador::class,
            'es.admin' => EsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
