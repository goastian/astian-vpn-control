<?php

use Illuminate\Support\Str;

return [

    /**
     * Host
     */
    'host' => env('APP_URL'),

    /**
     *  Server URL AOTUH 2
     */
    'server' => env('PASSPORT_SERVER'),


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
    'ids' => [
        'jwt_token' => env('PASSPORT_TOKEN', Str::slug(env('APP_NAME', 'passport'), '_') . '_outh2_server'),
        'jwt_refresh' => env('PASSPORT_REFRESH', Str::slug(env('APP_NAME', 'passport'), '_') . '_refresh_outh2_server'),
    ],

    /**
     * Login route
     */
    'login' => env('PASSPORT_LOGIN_TO', '/login'),

    /**
     * Redirect paga after login
     */
    'redirect_after_login' => env('PASSPORT_REDIRECT_TO', '/'),

    /**
     * Set config for cookies to OAUTH2 Server
     */
    'cookie' => [
        'path' => '/',
        'domain' => env('PASSPORT_DOMAIN_SERVER'),
        'time_expires' => env('PASSPORT_TIME_EXPIRES', 10),
        'secure' => env('PASSPORT_SECURE_COOKIE', true),
        'http_only' => env('PASSPORT_HTTP_ONLY_COOKIE', true),
        'same_site' => env('PASSPORT_SAME_SITE_COOKIE', 'lax'),
        'partitioned' => env('PASSPORT_PARTITIONED_COOKIE', false),
    ],
];
