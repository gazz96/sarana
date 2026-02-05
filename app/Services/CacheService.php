<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CacheService
{
    protected int $defaultTTL = 3600; // 1 hour
    protected int $longTTL = 86400; // 24 hours
    protected int $shortTTL = 300; // 5 minutes

    public function remember(string $key, $callback, int $ttl = null)
    {
        $ttl = $ttl ?? $this->defaultTTL;
        
        return Cache::remember($key, $ttl, function () use ($callback) {
            return $callback();
        });
    }

    public function rememberForever(string $key, $callback)
    {
        return Cache::rememberForever($key, function () use ($callback) {
            return $callback();
        });
    }

    public function forget(string $key): bool
    {
        return Cache::forget($key);
    }

    public function forgetByPattern(string $pattern): int
    {
        $count = 0;
        
        if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
            $redis = Cache::getStore()->connection();
            $keys = $redis->keys($pattern);
            
            foreach ($keys as $key) {
                if (Cache::forget($key)) {
                    $count++;
                }
            }
        } else {
            // For file cache, iterate through cache
            $count = Cache::forget($pattern);
        }
        
        return $count;
    }

    public function flush(): bool
    {
        return Cache::flush();
    }

    public function get(string $key, $default = null)
    {
        return Cache::get($key, $default);
    }

    public function put(string $key, $value, int $ttl = null): bool
    {
        $ttl = $ttl ?? $this->defaultTTL;
        return Cache::put($key, $value, $ttl);
    }

    public function has(string $key): bool
    {
        return Cache::has($key);
    }

    public function getDashboardStats(string $role, $callback)
    {
        $key = "dashboard_stats_{$role}";
        return $this->remember($key, $callback, $this->shortTTL);
    }

    public function getProblemCounts($callback)
    {
        $key = 'problem_counts';
        return $this->remember($key, $callback, $this->shortTTL);
    }

    public function getGoodsList($callback)
    {
        $key = 'goods_list';
        return $this->remember($key, $callback, $this->longTTL);
    }

    public function getLocationsList($callback)
    {
        $key = 'locations_list';
        return $this->remember($key, $callback, $this->longTTL);
    }

    public function getUsersByRole(string $role, $callback)
    {
        $key = "users_role_{$role}";
        return $this->remember($key, $callback, $this->defaultTTL);
    }

    public function getChartData(string $chartType, $callback, int $ttl = null)
    {
        $key = "chart_data_{$chartType}";
        return $this->remember($key, $callback, $ttl ?? $this->defaultTTL);
    }

    public function cacheQueryResult(string $key, $query, int $ttl = null)
    {
        $ttl = $ttl ?? $this->defaultTTL;
        
        return Cache::remember($key, $ttl, function () use ($query) {
            return $query->get();
        });
    }

    public function rememberUserPreferences(int $userId, $callback)
    {
        $key = "user_preferences_{$userId}";
        return $this->remember($key, $callback, $this->longTTL);
    }

    public function getCachedCount(string $table, $callback)
    {
        $key = "count_{$table}";
        return $this->remember($key, $callback, $this->shortTTL);
    }

    public function increment(string $key, int $value = 1): int
    {
        return Cache::increment($key, $value);
    }

    public function decrement(string $key, int $value = 1): int
    {
        return Cache::decrement($key, $value);
    }

    public function getMultiple(array $keys): array
    {
        return Cache::many($keys);
    }

    public function putMultiple(array $values, int $ttl = null): bool
    {
        $ttl = $ttl ?? $this->defaultTTL;
        return Cache::putMany($values, $ttl);
    }

    public function rememberWithLock(string $key, $callback, int $ttl = null, int $lockTimeout = 5)
    {
        $ttl = $ttl ?? $this->defaultTTL;
        
        return Cache::remember($key, $ttl, function () use ($callback, $key, $lockTimeout) {
            return Cache::lock($key)->block($lockTimeout, function () use ($callback) {
                return $callback();
            });
        });
    }

    public function getCacheInfo(): array
    {
        return [
            'driver' => config('cache.default'),
            'enabled' => config('cache.enabled', true),
            'default_ttl' => $this->defaultTTL,
            'keys_count' => $this->getKeysCount(),
        ];
    }

    protected function getKeysCount(): int
    {
        try {
            if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
                $redis = Cache::getStore()->connection();
                $info = $redis->info('keyspace');
                return (int) ($info['db0']['keys'] ?? 0);
            }
        } catch (\Exception $e) {
            return 0;
        }
        
        return 0;
    }

    public function warmUpCache(array $keys): void
    {
        foreach ($keys as $key => $callback) {
            if (!$this->has($key)) {
                try {
                    $this->put($key, $callback(), $this->defaultTTL);
                } catch (\Exception $e) {
                    // Skip failed cache warmup
                    continue;
                }
            }
        }
    }

    public function invalidateRelatedCache(string $entity, int $entityId): void
    {
        $patterns = [
            "dashboard_stats_*",
            "chart_data_*",
            "problem_counts",
            "count_*",
        ];

        foreach ($patterns as $pattern) {
            $this->forgetByPattern($pattern);
        }
    }
}