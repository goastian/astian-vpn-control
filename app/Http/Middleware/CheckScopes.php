<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Elyerr\Passport\Connect\Support\Response;

class CheckScopes extends \Elyerr\Passport\Connect\Middleware\CheckScopes
{
    public function handle($request, Closure $next, ...$scopes)
    {
        $headers['X-SCOPES'] = implode(',', $scopes);
        $this->client->addHeaders($headers);

        try {
            $response = $this->client->get($this->uri);

            if ($response->status === 200) {
                return $next($request);
            }

            if ($response->status === 401) {

                if ($request->wantsJson()) {
                    return Response::json([
                        "message" => "Authentication is failed."
                    ], $response->status);
                }

                return redirect()->route('home');
            }

            if ($response->status === 403) {

                if ($request->wantsJson()) {
                    return Response::json([
                        "message" => "Forbidden"
                    ], $response->status);
                }

                abort(403, "Forbidden");
            }

            if ($request->wantsJson()) {
                return Response::json([
                    "message" => "something went wrong"
                ], $response->status);
            }

            abort($response->status, "Something went wrong");

        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : null;

            throw new Exception($e->getMessage(), $statusCode);
        }
    }
}
