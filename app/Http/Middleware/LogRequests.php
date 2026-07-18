<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    /**
     * Log all requests for security audit
     */
    public function handle(Request $request, Closure $next): mixed
    {
        Log::info('API Request', [
            'method' => $request->method(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'user_id' => auth()->id(),
            'timestamp' => now(),
        ]);

        return $next($request);
    }
}
