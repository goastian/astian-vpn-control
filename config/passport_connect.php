<?php

use Illuminate\Support\Str;

return [

    /**
     * Master domain 
     * Domain host where the server_cookie_names are created.
     * Used for authentication with module is true.
     */
    'domain' => env('PASSPORT_MASTER_DOMAIN', null),

    /**
     * Host
     */
    'host' => env('APP_URL'),

    /**
     * Determines whether the application behaves as an internal module or a third-party app.
     */
    'module' => env('PASSPORT_MODULE', false),

    /**
     * OAuth2 Server URL
     */
    'server' => env('PASSPORT_SERVER'),

    /**
     * These cookie names are required if the application is on the same domain.
     * When you add these names, the app will behave as a module rather than a 
     * third-party application.
     */
    'server_cookie_names' => explode(',', env('PASSPORT_MODULE_COOKIES_NAMES')),

    /**
     * Redirect page after login
     */
    'redirect_after_login' => env('PASSPORT_REDIRECT_TO', '/'),

    /**
     * Login route
     */
    'login' => env('PASSPORT_LOGIN_TO', '/login'),

    //------------------------------THIRD APPLICATION ---------------------------
    // These credentials are required only if the app is on a different domain.

    /**
     * Client ID generated on the OAUTH 2 Server
     */
    'server_id' => env('PASSPORT_SERVER_ID') ?: null,

    /**
     * Prompt to make request for authorization
     * Accept values
     * none : No ask to the user,
     * consent: Asking to the user
     * login: Generate a new login
     */
    'prompt_mode' => env('PASSPORT_PROMPT_MODE', 'consent') ?: 'login',

    /**
     * Scopes for this clients to provide to the user
     */
    'scopes' => env('PASSPORT_CLIENT_SCOPES', '*'),

    /**
     * Name of cookies to save token jwt and refresh token
     */
    'jwt_token' => env('PASSPORT_TOKEN', Str::slug(env('APP_NAME', 'passport'), '_') . '_oauth_server'),

    /**
     * Set config for cookies to OAUTH2 Server
     */
    'cookie' => [
        'path' => '/',
        'domain' => env('PASSPORT_DOMAIN_SERVER', null),
        'time_expires' => env('PASSPORT_TIME_EXPIRES'),
        'secure' => env('PASSPORT_SECURE_COOKIE', true),
        'http_only' => env('PASSPORT_HTTP_ONLY_COOKIE', true),
        'same_site' => env('PASSPORT_SAME_SITE_COOKIE', 'lax'),
        'partitioned' => env('PASSPORT_PARTITIONED_COOKIE', false),
    ],
];
