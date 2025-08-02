<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\CookieValuePrefix;
use Symfony\Component\HttpFoundation\Cookie; 
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class CsrfToken extends Middleware
{
    /**
     * Get the CSRF token from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function getTokenFromRequest($request)
    {
        $token = $request->input('_token') ?: $request->header(config('session.xcsrf-token'));

        if (empty($token)) {
            $token = $request->cookies->get(config('session.xcsrf-token'));
        }

        if (!$token && $header = $request->header('X-XSRF-TOKEN')) {
            try {
                $token = CookieValuePrefix::remove($this->encrypter->decrypt($header, static::serialized()));
            } catch (DecryptException) {
                $token = '';
            }
        }

        return $token;
    }

    /**
     * Create a new "XSRF-TOKEN" cookie that contains the CSRF token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $config
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    protected function newCookie($request, $config): Cookie
    {
        return new Cookie(
            config('session.xcsrf-token'),
            $request->session()->token(),
            $this->availableAt(60 * $config['lifetime']),
            $config['path'],
            $config['domain'],
            $config['secure'],
            false,
            false,
            $config['same_site'] ?? null,
            $config['partitioned'] ?? false
        );
    }


    /**
     * Determine if the cookie contents should be serialized.
     *
     * @return bool
     */
    public static function serialized()
    {
        return EncryptCookies::serialized(config('session.xcsrf-token'));
    }
}
