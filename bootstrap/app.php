<?php

use App\Exceptions\PetstoreException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (PetstoreException $e, Request $request) {
            // JSON (np. /api/*) – zostawiamy na przyszłość:
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 502);
            }

            // Dla wszystkich ścieżek zaczynających się od /pets
            if ($request->is('pets*')) {
                return redirect()
                    ->route('pets.index', ['status' => 'available'])
                    ->with('error', $e->getMessage());
            }

            // Fallback: przekieruj na stronę główną z błędem
            return redirect('/')
                ->with('error', $e->getMessage());
        });
    })->create();
