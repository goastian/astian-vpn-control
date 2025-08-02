<?php

use App\Http\Middleware\CsrfToken;
use App\Http\Middleware\CheckScopes;
use App\Http\Middleware\Authorization;
use App\Http\Middleware\SecureHeaders;
use Illuminate\Foundation\Application;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\WantsJsonHeader;
use App\Http\Middleware\CheckForAnyScope;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

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
            'json.response' => WantsJsonHeader::class
        ]);

        $middleware->web(
            remove: [
                \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
            ],
            append: [
                SecureHeaders::class,
                CsrfToken::class,
                EncryptCookies::class,
                HandleInertiaRequests::class,
            ]
        );

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Throwable $e) {

            if ($e->getCode() == 401) {
                if (request()->wantsJson()) {
                    return new ReportError(__("Unauthenticated"), 401);
                }

                return redirect(config('system.home_page', '/'));
            }

            if ($e->getCode() == 403) {
                if (request()->wantsJson()) {
                    return new ReportError(__("forbidden"), 403);
                }

                return redirect(config('system.redirect_to', '/'));
            }
        });
    })->create();
