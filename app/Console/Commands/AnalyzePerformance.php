<?php

namespace App\Console\Commands;

use App\Services\DatabaseOptimizer;
use Illuminate\Console\Command;

class AnalyzePerformance extends Command
{
    protected $signature = 'performance:analyze {--detailed : Show detailed performance analysis}';
    protected $description = 'Analyze system performance and provide optimization recommendations';

    protected DatabaseOptimizer $dbOptimizer;

    public function __construct(DatabaseOptimizer $dbOptimizer)
    {
        parent::__construct();
        $this->dbOptimizer = $dbOptimizer;
    }

    public function handle()
    {
        $this->info('📊 Analyzing System Performance...');

        // Database performance
        $this->info("\n🗄️ Database Performance:");
        $this->analyzeDatabasePerformance();

        // Cache performance
        $this->info("\n📦 Cache Performance:");
        $this->analyzeCachePerformance();

        // Query performance
        $this->info("\n⚡ Query Performance:");
        $this->analyzeQueryPerformance();

        // Recommendations
        $this->info("\n💡 Optimization Recommendations:");
        $this->showRecommendations();

        return 0;
    }

    protected function analyzeDatabasePerformance()
    {
        try {
            $performance = $this->dbOptimizer->analyzeQueryPerformance();

            // Table sizes
            $this->table(
                ['Table', 'Size (MB)', 'Rows'],
                collect($performance['table_sizes'] ?? [])->take(5)->map(function ($size, $table) {
                    return [$table, $size['size_mb'], number_format($size['rows'])];
                })->toArray()
            );

            if ($this->option('detailed')) {
                $this->info("\nIndex Usage:");
                foreach ($performance['index_usage'] ?? [] as $table => $indexes) {
                    $this->line("<info>{$table}:</info>");
                    foreach ($indexes as $index) {
                        $this->line("  - {$index['name']} (Cardinality: {$index['cardinality']})");
                    }
                }
            }

        } catch (\Exception $e) {
            $this->error("Database analysis failed: " . $e->getMessage());
        }
    }

    protected function analyzeCachePerformance()
    {
        try {
            $cacheInfo = app(\App\Services\CacheService::class)->getCacheInfo();

            $this->table(
                ['Metric', 'Value'],
                [
                    ['Driver', $cacheInfo['driver']],
                    ['Enabled', $cacheInfo['enabled'] ? 'Yes' : 'No'],
                    ['Keys Count', $cacheInfo['keys_count']],
                    ['Default TTL', $cacheInfo['default_ttl'] . ' seconds'],
                ]
            );

            // Show cache efficiency recommendations
            if ($cacheInfo['driver'] === 'file') {
                $this->warn("Consider using Redis for better cache performance");
            } elseif ($cacheInfo['driver'] === 'redis') {
                $this->info("✓ Using Redis cache driver (optimal)");
            }

        } catch (\Exception $e) {
            $this->error("Cache analysis failed: " . $e->getMessage());
        }
    }

    protected function analyzeQueryPerformance()
    {
        try {
            $metrics = $this->dbOptimizer->getDatabaseMetrics();

            $this->table(
                ['Metric', 'Value'],
                [
                    ['Active Connections', $metrics['connection_count'] ?? 'Unknown'],
                    ['Database Uptime', $metrics['uptime'] ?? 'Unknown'],
                    ['Buffer Pool Usage', ($metrics['buffer_pool_size']['usage_percentage'] ?? 0) . '%'],
                    ['Cache Hit Ratio', ($metrics['cache_hit_ratio']['hit_ratio_percentage'] ?? 0) . '%'],
                ]
            );

            // Performance recommendations based on metrics
            $hitRatio = $metrics['cache_hit_ratio']['hit_ratio_percentage'] ?? 0;
            if ($hitRatio < 90) {
                $this->warn("Low cache hit ratio ({$hitRatio}%). Consider increasing buffer pool size.");
            }

            $usagePercentage = $metrics['buffer_pool_size']['usage_percentage'] ?? 0;
            if ($usagePercentage > 80) {
                $this->warn("High buffer pool usage ({$usagePercentage}%). Consider increasing buffer pool size.");
            }

        } catch (\Exception $e) {
            $this->error("Query analysis failed: " . $e->getMessage());
        }
    }

    protected function showRecommendations()
    {
        $recommendations = [];

        // Cache recommendations
        if (config('cache.default') === 'file') {
            $recommendations[] = "Switch to Redis cache driver for better performance";
        }

        // Database recommendations
        try {
            $metrics = $this->dbOptimizer->getDatabaseMetrics();
            $hitRatio = $metrics['cache_hit_ratio']['hit_ratio_percentage'] ?? 0;
            
            if ($hitRatio < 90) {
                $recommendations[] = "Increase InnoDB buffer pool size to improve cache hit ratio";
            }

            $usagePercentage = $metrics['buffer_pool_size']['usage_percentage'] ?? 0;
            if ($usagePercentage > 80) {
                $recommendations[] = "Increase InnoDB buffer pool size (current usage: {$usagePercentage}%)";
            }

        } catch (\Exception $e) {
            // Skip database recommendations if analysis fails
        }

        // General recommendations
        $recommendations[] = "Run 'php artisan system:optimize --full' for comprehensive optimization";
        $recommendations[] = "Enable query logging to identify slow queries";
        $recommendations[] = "Implement database query caching for frequently accessed data";

        if (empty($recommendations)) {
            $this->info("✓ System is well optimized!");
        } else {
            foreach ($recommendations as $recommendation) {
                $this->line("• <info>{$recommendation}</info>");
            }
        }
    }
}