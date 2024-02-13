<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitDownload
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $key = $request->ip(); // You can customize the key as needed
        $maxAttempts = 1; // One download attempt allowed
        $decayMinutes = 0.5; // 30 seconds

        app(RateLimiter::class)->hit($key, $maxAttempts, $decayMinutes);

        return $next($request);
    }
}
