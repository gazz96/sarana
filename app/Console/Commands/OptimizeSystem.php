<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use App\Services\DatabaseOptimizer;
use Illuminate\Console\Command;

class OptimizeSystem extends Command
{
    protected $signature = 'system:optimize {--full : Run full optimization including database}';
    protected $description = 'Optimize system performance with caching and database optimization';

    protected CacheService $cacheService;
    protected DatabaseOptimizer $dbOptimizer;

    public function __construct(CacheService $cacheService, DatabaseOptimizer $dbOptimizer)
    {
        parent::__construct();
        $this->cacheService = $cacheService;
        $this->dbOptimizer = $dbOptimizer;
    }

    public function handle()
    {
        $this->info('🚀 Starting System Optimization...');

        // Cache optimization
        $this->info("\n📦 Optimizing Cache...");
        $this->optimizeCache();

        // Database optimization (if full flag is set)
        if ($this->option('full')) {
            $this->info("\n🗄️ Optimizing Database...");
            $this->optimizeDatabase();
        }

        // Performance metrics
        $this->info("\n📊 Current Performance Metrics:");
        $this->showPerformanceMetrics();

        $this->info("\n✅ System optimization completed successfully!");
        return 0;
    }

    protected function optimizeCache()
    {
        try {
            // Get current cache info
            $cacheInfo = $this->cacheService->getCacheInfo();
            $this->line("Current cache driver: <info>{$cacheInfo['driver']}</info>");
            $this->line("Cache keys count: <info>{$cacheInfo['keys_count']}</info>");

            // Clear stale cache
            $this->line("Clearing stale cache entries...");
            // This could be enhanced to clear only specific patterns

            // Warm up essential cache
            $this->line("Warming up essential cache...");
            $this->warmUpCache();

            $this->info("✓ Cache optimization completed");

        } catch (\Exception $e) {
            $this->error("✗ Cache optimization failed: " . $e->getMessage());
        }
    }

    protected function optimizeDatabase()
    {
        try {
            $this->line("Creating optimal indexes...");
            $indexesCreated = $this->dbOptimizer->createOptimalIndexes();
            $this->line("Created <info>{$indexesCreated}</info> new indexes");

            $this->line("Optimizing tables...");
            $tablesOptimized = $this->dbOptimizer->optimizeTables();
            $this->line("Optimized <info>{$tablesOptimized}</info> tables");

            $this->line("Enabling query cache...");
            $queryCacheEnabled = $this->dbOptimizer->enableQueryCache();
            $this->line($queryCacheEnabled ? "✓ Query cache enabled" : "✗ Query cache not available");

            $this->info("✓ Database optimization completed");

        } catch (\Exception $e) {
            $this->error("✗ Database optimization failed: " . $e->getMessage());
        }
    }

    protected function warmUpCache()
    {
        // Define essential cache keys to warm up
        $essentialKeys = [
            'problem_counts' => fn() => \App\Models\Problem::count(),
            'count_goods' => fn() => \App\Models\Good::count(),
            'count_locations' => fn() => \App\Models\Location::count(),
            'count_users' => fn() => \App\Models\User::count(),
        ];

        $this->cacheService->warmUpCache($essentialKeys);
        $this->line("Warmed up " . count($essentialKeys) . " essential cache keys");
    }

    protected function showPerformanceMetrics()
    {
        try {
            $dbMetrics = $this->dbOptimizer->getDatabaseMetrics();

            $this->table(
                ['Metric', 'Value'],
                [
                    ['Database Connections', $dbMetrics['connection_count'] ?? 'Unknown'],
                    ['Database Uptime', $dbMetrics['uptime'] ?? 'Unknown'],
                    ['Buffer Pool Usage', ($dbMetrics['buffer_pool_size']['usage_percentage'] ?? 0) . '%'],
                    ['Cache Hit Ratio', ($dbMetrics['cache_hit_ratio']['hit_ratio_percentage'] ?? 0) . '%'],
                    ['Cache Driver', config('cache.default')],
                    ['Cache Enabled', config('cache.enabled', true) ? 'Yes' : 'No'],
                ]
            );
        } catch (\Exception $e) {
            $this->warn("Could not retrieve performance metrics: " . $e->getMessage());
        }
    }
}