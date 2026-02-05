<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RateLimitProtection
{
    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Cache\RateLimiter  $limiter
     * @return void
     */
    public function __construct(protected RateLimiter $limiter)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  int|null  $maxAttempts
     * @param  int|null  $decayMinutes
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ?int $maxAttempts = 60, ?int $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return $this->buildResponse($key, $maxAttempts);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        $response = $next($request);

        // Add rate limit headers
        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', 
            max(0, $maxAttempts - $this->limiter->attempts($key)));

        return $response;
    }

    /**
     * Resolve request signature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function resolveRequestSignature(Request $request): string
    {
        return sha1(
            $request->ip() . 
            '|' . $request->route()->getName() . 
            '|' . $request->method()
        );
    }

    /**
     * Create a 'too many attempts' response.
     *
     * @param  string  $key
     * @param  int  $maxAttempts
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildResponse(string $key, int $maxAttempts): Response
    {
        $seconds = $this->limiter->availableIn($key);

        Log::warning('Rate limit exceeded', [
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
            'attempts' => $this->limiter->attempts($key),
            'max_attempts' => $maxAttempts
        ]);

        return response()->json([
            'message' => 'Too many attempts. Please try again later.',
            'retry_after' => $seconds
        ], 429);
    }
}