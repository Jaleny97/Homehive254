<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RateLimitMiddleware
{
    /**
     * Rate limiting
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $key = 'rate-limit:' . $request->ip();
        $maxAttempts = 60;
        $decayMinutes = 1;

        if (cache()->has($key)) {
            if (cache()->get($key) >= $maxAttempts) {
                return response()->json([
                    'message' => 'Too many requests. Please try again later.',
                ], 429);
            }
            cache()->increment($key);
        } else {
            cache()->put($key, 1, now()->addMinutes($decayMinutes));
        }

        return $next($request);
    }
}
