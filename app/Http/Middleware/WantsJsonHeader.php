<?php

namespace App\Http\Middleware;

use Closure;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WantsJsonHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->wantsJson()) {
            return $next($request);
        }

        return redirect()->route('user.dashboard');
    }
}
