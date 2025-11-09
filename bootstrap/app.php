<?php


use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register custom middleware alias
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class, // Tambahkan ini
            'membership' => \App\Http\Middleware\CheckMembershipStatus::class, // Tambahkan ini
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('admin/*')) {
                return redirect()->guest(route('admin.login'));
            }

            return redirect()->guest(route('login'));
        });
    })->create();
