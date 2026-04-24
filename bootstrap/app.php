<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Support\ApiResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo('/api/auth/login');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // All api/* requests always receive JSON, never an HTML error page.
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*')
        );

        $exceptions->render(function (AuthenticationException $e) {
            return ApiResponse::error([], 'Unauthenticated.', 401);
        });

        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                $model = class_basename($e->getModel());
                return ApiResponse::error([], "{$model} not found.", 404);
            }
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return ApiResponse::error([$e->errors()], 'The given data was invalid.', 422);
            }
        });

        $exceptions->render(function (HttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return ApiResponse::error([],$e->getMessage() ?: 'HTTP error.', $e->getStatusCode());
            }
        });
    })->create();
