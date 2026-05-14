<?php

// use Illuminate\Auth\AuthenticationException;
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
        $middleware->redirectGuestsTo(function ($request) {
            return route('index');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            // logger('AUTH EXCEPTION HIT');
            // if ($request->ajax() || $request->expectsJson()) {
            //     return response()->json([
            //         'auth' => false
            //     ], 401);
            // }
            return redirect()
                ->route('index')
                ->with('show_login_modal', true);
        });
    })->create();
