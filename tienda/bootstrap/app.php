<?php

// Suprimir advertencias de deprecación de PDO::MYSQL_ATTR_SSL_CA en PHP 8.5+
// Esto es necesario porque Laravel 11.x aún usa la constante antigua en vendor/
error_reporting(E_ALL & ~E_DEPRECATED);

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
        // Registramos el middleware IsAdmin con el alias 'admin'
        // Esto nos permite usar middleware('admin') en las rutas
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
