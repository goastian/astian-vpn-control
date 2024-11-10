<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Elyerr\Passport\Connect\Middleware\Authorization;
use Elyerr\Passport\Connect\Traits\Passport;

class GlobalController extends Controller
{
    use Passport;

    public function __construct()
    {
        $this->middleware(Authorization::class);
    }
}
