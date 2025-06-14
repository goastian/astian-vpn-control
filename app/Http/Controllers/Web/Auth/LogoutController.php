<?php
namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\ApiController;
use Elyerr\Passport\Connect\Traits\Passport;

class LogoutController extends ApiController
{

    use Passport;


    public function __construct()
    {
        $this->middleware('server');
    }
}
