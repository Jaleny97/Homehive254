<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateCsrfToken
{
    /**
     * CSRF Token validation
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Skip CSRF for API endpoints using Sanctum tokens
        if ($request->expectsJson() && $request->bearerToken()) {
            return $next($request);
        }

        return $next($request);
    }
}
