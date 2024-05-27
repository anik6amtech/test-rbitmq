<?php

use App\Http\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(dirname(__DIR__))
->withRouting(
    using: function () {

        Route::prefix('api/v1')
            ->group(base_path('routes/rest_api/v1/api.php'));

        Route::fallback(function () {
            return response()->json(responseFormatter(DEFAULT_404), 404);
        });

    },
    commands: __DIR__.'/../routes/console.php',
    health: '/up'
)
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(function () {
            abort(response()->json(responseFormatter(AUTH_TOKEN_EXPIRED_401), 401));
        })->alias([
            'auth' => Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
