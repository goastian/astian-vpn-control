<?php

use Illuminate\Support\Str;

return [

    /**
     * OAuth2 Server URL
     */
    'server' => env('PASSPORT_SERVER'),

    /**
     * Client ID generated on the OAUTH 2 Server
     */
    'server_id' => env('PASSPORT_SERVER_ID') ?: null,

    /**
     * Host
     */
    'host' => env('APP_URL'),

    /**
     * Redirect page after login
     */
    'redirect_after_login' => env('PASSPORT_REDIRECT_TO', '/'),

    /**
     * Login route
     */
    'login' => env('PASSPORT_LOGIN_TO', '/login'),

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
    'scopes' => env('PASSPORT_CLIENT_SCOPES', ''),

    /**
     * Name of cookies to save token jwt and refresh token
     */
    'jwt_token' => trim(env('PASSPORT_TOKEN_NAME', "passport_server")),

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
