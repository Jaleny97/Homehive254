<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    /**
     * Add security headers to response
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        // Prevent clickjacking
        $response->header('X-Frame-Options', 'DENY');
        
        // Prevent MIME type sniffing
        $response->header('X-Content-Type-Options', 'nosniff');
        
        // Enable XSS protection
        $response->header('X-XSS-Protection', '1; mode=block');
        
        // Content Security Policy
        $response->header('Content-Security-Policy', "default-src 'self'");
        
        // Referrer Policy
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Feature Policy
        $response->header('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}
