<?php

namespace Vio\Pinball\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VerifyApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()) {
            $secret = config('api.key');

            if (is_null($secret)) {
                abort(403, "API Key not configured.");
            }

            $keysProvided = [
                $request->bearerToken(),
                $request->header('X-Api-Key'),
            ];

            if (in_array($secret, $keysProvided)) {
                abort(403, "Invalid API Key.");
            }
        }

        return $next($request);
    }
}
