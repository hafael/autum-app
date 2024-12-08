<?php

use Hafael\HttpClient\Exceptions\ClientException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->statefulApi();
        
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'auth.mesh' => \Hafael\Mesh\Auth\Middlewares\MeshTokenMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/signin',
            '/webhook',
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->render(function (ClientException $e) {
        //     return response()->json([
        //         'error' => $e->getMessage(),
        //     ], $e->getCode());
        // });
    })->create();
