<?php

namespace App\Http\Middleware;

use App\Services\MonitoringService;
use Closure;
use Illuminate\Support\Facades\Auth;

class MonitoringMiddleware
{
    protected $monitoringService;

    public function __construct(MonitoringService $monitoringService)
    {
        $this->monitoringService = $monitoringService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        // Process the request
        $response = $next($request);

        // Calculate metrics
        $responseTime = (microtime(true) - $startTime) * 1000; // Convert to milliseconds
        $memoryUsage = (memory_get_usage(true) - $startMemory) / 1024 / 1024; // Convert to MB

        // Log performance (only for authenticated users and non-admin routes)
        if (Auth::check() && !$request->is('admin/monitoring/*')) {
            $this->monitoringService->logPerformance(
                $request->route()?->getName() ?? $request->path(),
                $responseTime,
                $memoryUsage,
                $response->getStatusCode()
            );
        }

        // Log user activity
        if (Auth::check() && $this->shouldLogActivity($request)) {
            $this->monitoringService->logUserActivity(
                Auth::id(),
                $this->getActionName($request),
                [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'response_time' => $responseTime
                ]
            );
        }

        return $response;
    }

    /**
     * Determine if activity should be logged
     */
    private function shouldLogActivity($request)
    {
        // Don't log monitoring, health checks, or asset requests
        $dontLog = [
            'admin/monitoring/*',
            'health/*',
            'api/health',
            'telescope/*',
            'horizon/*',
            '*.css',
            '*.js',
            '*.png',
            '*.jpg',
            '*.jpeg',
            '*.gif',
            '*.svg',
            '*.ico'
        ];

        foreach ($dontLog as $pattern) {
            if ($request->is($pattern)) {
                return false;
            }
        }

        // Only log important HTTP methods
        return in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH']);
    }

    /**
     * Get action name for logging
     */
    private function getActionName($request)
    {
        $method = $request->method();
        $route = $request->route()?->getName();

        if ($route) {
            return strtoupper($method) . ' - ' . $route;
        }

        return strtoupper($method) . ' - ' . $request->path();
    }
}