<?php

use App\Http\Middleware\CsrfToken;
use App\Http\Middleware\SecureHeaders;
use Illuminate\Foundation\Application;
use App\Http\Middleware\EncryptCookies;
use Elyerr\Passport\Connect\Middleware\CheckScopes;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Elyerr\Passport\Connect\Middleware\Authorization;
use Laravel\Passport\Http\Middleware\CheckCredentials;
use Elyerr\Passport\Connect\Middleware\CheckForAnyScope;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'scope' => CheckForAnyScope::class,
            'scopes' => CheckScopes::class,
            'server' => Authorization::class,
            'client-credentials' => CheckCredentials::class
        ]);

        $middleware->web(
            remove: [
                \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
                Illuminate\Cookie\Middleware\EncryptCookies::class,
            ],
            append: [
                SecureHeaders::class,
                CsrfToken::class,
                EncryptCookies::class
            ]
        );

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
