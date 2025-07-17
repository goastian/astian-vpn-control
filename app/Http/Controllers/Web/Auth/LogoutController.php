<?php
namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\ApiController;
use Elyerr\Passport\Connect\Http\Request;
use Elyerr\Passport\Connect\Services\Passport;

class LogoutController extends ApiController
{

    /**
     * LogoutController constructor.
     * 
     */
    public function __construct()
    {
        $this->middleware('server');
    }

    /**
     * Summary of logout
     * @param \Elyerr\Passport\Connect\Http\Request $request
     * @return \stdClass|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function logout(Request $request): mixed
    {
        $passport = app(Passport::class);

        return $passport->logout($request);
    }

}
