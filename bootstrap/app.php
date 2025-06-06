<?php

use App\Http\Middleware\UnbanUser;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Socialite\Two\InvalidStateException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )->withMiddleware(function (Middleware $middleware) {
        $middleware->append(UnbanUser::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
