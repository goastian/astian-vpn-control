<?php

namespace App\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\ClientException;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Elyerr\Passport\Connect\Controllers\CodeController;


class AuthController extends CodeController
{
    /**
     * Make a requests to the oauth 2 server using the code to generate valid credentials
     * @param \Illuminate\Http\Request $request
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
        $state = $request->session()->pull('state');

        $codeVerifier = $request->session()->pull('code_verifier');

        throw_unless(
            strlen($state) > 0 && $state === $request->state,
            new ReportError("Can't find the session", 404)
        );

        try {
            $response = $this->client()
                ->post(config('passport_connect.server') . '/api/oauth/token', [
                    'form_params' => [
                        'grant_type' => 'authorization_code',
                        'client_id' => config("passport_connect.server_id"),
                        'redirect_uri' => config('app.url') . '/callback',
                        'code_verifier' => $codeVerifier,
                        'code' => $request->code,
                    ],
                ]);
        } catch (ClientException $e) {
            throw new ReportError(__('Unauthenticated'), 401);
        }

        $jwtToken = $this->generateCredentials($response);

        $redirect_to = route('user.dashboard');

        return redirect($redirect_to)->withCookie($jwtToken);
    }
}
