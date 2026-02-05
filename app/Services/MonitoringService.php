<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class MonitoringService
{
    /**
     * Log user activity
     */
    public function logUserActivity($userId, $action, $details = [])
    {
        try {
            DB::table('user_activities')->insert([
                'user_id' => $userId,
                'action' => $action,
                'details' => json_encode($details),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Clean old activities (keep last 90 days)
            $this->cleanOldActivities();
        } catch (\Exception $e) {
            Log::error('Failed to log user activity', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'action' => $action
            ]);
        }
    }

    /**
     * Log system errors
     */
    public function logError($exception, $context = [])
    {
        try {
            DB::table('system_errors')->insert([
                'exception_type' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'stack_trace' => $exception->getTraceAsString(),
                'context' => json_encode($context),
                'user_id' => auth()->id(),
                'ip_address' => request()->ip(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Clean old errors (keep last 30 days)
            $this->cleanOldErrors();
        } catch (\Exception $e) {
            Log::error('Failed to log system error', [
                'error' => $e->getMessage(),
                'original_error' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Log performance metrics
     */
    public function logPerformance($route, $responseTime, $memoryUsage, $statusCode)
    {
        try {
            // Only log if response time is slow (> 1 second)
            if ($responseTime > 1000) {
                DB::table('performance_logs')->insert([
                    'route' => $route,
                    'response_time_ms' => $responseTime,
                    'memory_usage_mb' => $memoryUsage,
                    'status_code' => $statusCode,
                    'user_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Clean old performance logs (keep last 7 days)
                $this->cleanOldPerformanceLogs();
            }
        } catch (\Exception $e) {
            Log::error('Failed to log performance', [
                'error' => $e->getMessage(),
                'route' => $route
            ]);
        }
    }

    /**
     * Get system health status
     */
    public function getSystemHealth()
    {
        return Cache::remember('system-health', 300, function () {
            return [
                'database' => $this->checkDatabase(),
                'cache' => $this->checkCache(),
                'storage' => $this->checkStorage(),
                'memory' => $this->checkMemoryUsage(),
                'disk' => $this->checkDiskSpace(),
                'timestamp' => now()->toIso8601String()
            ];
        });
    }

    /**
     * Check database connection
     */
    private function checkDatabase()
    {
        try {
            DB::select('SELECT 1');
            return [
                'status' => 'healthy',
                'response_time_ms' => $this->measureDatabaseResponseTime()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check cache functionality
     */
    private function checkCache()
    {
        try {
            $key = 'health-check-' . time();
            Cache::put($key, 'test', 60);
            $value = Cache::get($key);
            Cache::forget($key);

            if ($value === 'test') {
                return [
                    'status' => 'healthy',
                    'driver' => config('cache.default')
                ];
            }

            return ['status' => 'unhealthy', 'error' => 'Cache read/write failed'];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check storage availability
     */
    private function checkStorage()
    {
        try {
            $testFile = 'health-check-' . time() . '.txt';
            $content = 'Storage test';
            
            if (file_put_contents(storage_path('app/' . $testFile), $content)) {
                unlink(storage_path('app/' . $testFile));
                return [
                    'status' => 'healthy',
                    'path' => storage_path('app')
                ];
            }

            return ['status' => 'unhealthy', 'error' => 'Cannot write to storage'];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check memory usage
     */
    private function checkMemoryUsage()
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));
        $usagePercent = ($memoryUsage / $memoryLimit) * 100;

        return [
            'status' => $usagePercent < 80 ? 'healthy' : 'warning',
            'usage_mb' => round($memoryUsage / 1024 / 1024, 2),
            'limit_mb' => round($memoryLimit / 1024 / 1024, 2),
            'usage_percent' => round($usagePercent, 2)
        ];
    }

    /**
     * Check disk space
     */
    private function checkDiskSpace()
    {
        $freeSpace = disk_free_space('/');
        $totalSpace = disk_total_space('/');
        $usedPercent = (($totalSpace - $freeSpace) / $totalSpace) * 100;

        return [
            'status' => $usedPercent < 80 ? 'healthy' : 'warning',
            'free_gb' => round($freeSpace / 1024 / 1024 / 1024, 2),
            'total_gb' => round($totalSpace / 1024 / 1024 / 1024, 2),
            'used_percent' => round($usedPercent, 2)
        ];
    }

    /**
     * Get recent system errors
     */
    public function getRecentErrors($limit = 50)
    {
        return DB::table('system_errors')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($error) {
                return [
                    'id' => $error->id,
                    'type' => $error->exception_type,
                    'message' => $error->message,
                    'file' => $error->file,
                    'line' => $error->line,
                    'created_at' => $error->created_at,
                    'user_id' => $error->user_id
                ];
            });
    }

    /**
     * Get error statistics
     */
    public function getErrorStats($days = 7)
    {
        $startDate = Carbon::now()->subDays($days);

        return [
            'total_errors' => DB::table('system_errors')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'errors_by_type' => DB::table('system_errors')
                ->select('exception_type', DB::raw('count(*) as count'))
                ->where('created_at', '>=', $startDate)
                ->groupBy('exception_type')
                ->orderBy('count', 'desc')
                ->get(),
            'errors_last_24h' => DB::table('system_errors')
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->count()
        ];
    }

    /**
     * Get recent user activities
     */
    public function getRecentActivities($limit = 100)
    {
        return DB::table('user_activities')
            ->join('users', 'user_activities.user_id', '=', 'users.id')
            ->select('user_activities.*', 'users.name', 'users.email')
            ->orderBy('user_activities.created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics($hours = 24)
    {
        $startDate = Carbon::now()->subHours($hours);

        return [
            'avg_response_time' => DB::table('performance_logs')
                ->where('created_at', '>=', $startDate)
                ->avg('response_time_ms'),
            'slow_requests' => DB::table('performance_logs')
                ->where('created_at', '>=', $startDate)
                ->where('response_time_ms', '>', 2000)
                ->count(),
            'total_requests' => DB::table('performance_logs')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'status_codes' => DB::table('performance_logs')
                ->select('status_code', DB::raw('count(*) as count'))
                ->where('created_at', '>=', $startDate)
                ->groupBy('status_code')
                ->get()
        ];
    }

    /**
     * Measure database response time
     */
    private function measureDatabaseResponseTime()
    {
        $startTime = microtime(true);
        DB::select('SELECT 1');
        return round((microtime(true) - $startTime) * 1000, 2);
    }

    /**
     * Parse memory limit string to bytes
     */
    private function parseMemoryLimit($memoryLimit)
    {
        if ($memoryLimit == '-1') {
            return 4 * 1024 * 1024 * 1024; // 4GB default
        }

        $value = (int) $memoryLimit;
        $unit = strtoupper(substr($memoryLimit, -1));

        switch ($unit) {
            case 'G':
                return $value * 1024 * 1024 * 1024;
            case 'M':
                return $value * 1024 * 1024;
            case 'K':
                return $value * 1024;
            default:
                return $value;
        }
    }

    /**
     * Clean old activity logs
     */
    private function cleanOldActivities()
    {
        if (rand(1, 100) === 1) { // Run 1% of the time
            DB::table('user_activities')
                ->where('created_at', '<', Carbon::now()->subDays(90))
                ->delete();
        }
    }

    /**
     * Clean old error logs
     */
    private function cleanOldErrors()
    {
        if (rand(1, 100) === 1) { // Run 1% of the time
            DB::table('system_errors')
                ->where('created_at', '<', Carbon::now()->subDays(30))
                ->delete();
        }
    }

    /**
     * Clean old performance logs
     */
    private function cleanOldPerformanceLogs()
    {
        if (rand(1, 100) === 1) { // Run 1% of the time
            DB::table('performance_logs')
                ->where('created_at', '<', Carbon::now()->subDays(7))
                ->delete();
        }
    }

    /**
     * Send alert if system health is critical
     */
    public function checkAndSendAlerts()
    {
        $health = $this->getSystemHealth();

        foreach ($health as $component => $status) {
            if (is_array($status) && isset($status['status']) && $status['status'] === 'unhealthy') {
                $this->sendHealthAlert($component, $status);
            }
        }
    }

    /**
     * Send health alert notification
     */
    private function sendHealthAlert($component, $status)
    {
        try {
            // Log critical alert
            Log::critical('System health alert', [
                'component' => $component,
                'status' => $status
            ]);

            // You can integrate with email, Slack, or other notification services here
            // For now, we'll just log it
        } catch (\Exception $e) {
            Log::error('Failed to send health alert', [
                'error' => $e->getMessage(),
                'component' => $component
            ]);
        }
    }
}