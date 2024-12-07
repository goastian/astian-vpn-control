<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Encryption\Encrypter as EncrypterContract;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    public function __construct(EncrypterContract $encrypter)
    {
        parent::__construct($encrypter);

        $except = [
            config('passport_connect.ids.jwt_token'),
            config('passport_connect.ids.jwt_refresh'),
        ];

        $this->except = array_merge($this->except, $except);
    }
}
