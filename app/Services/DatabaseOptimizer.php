<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseOptimizer
{
    protected bool $logSlowQueries = true;
    protected int $slowQueryThreshold = 100; // milliseconds

    public function optimize()
    {
        return [
            'indexes_created' => $this->createOptimalIndexes(),
            'tables_optimized' => $this->optimizeTables(),
            'query_cache_enabled' => $this->enableQueryCache(),
        ];
    }

    public function createOptimalIndexes(): int
    {
        $indexes = [
            'problems' => [
                'idx_problems_status' => 'status',
                'idx_problems_user_id' => 'user_id',
                'idx_problems_technician_id' => 'user_technician_id',
                'idx_problems_created_at' => 'created_at',
                'idx_problems_updated_at' => 'updated_at',
                'idx_problems_code' => 'code',
            ],
            'goods' => [
                'idx_goods_status' => 'status',
                'idx_goods_location_id' => 'location_id',
                'idx_goods_code' => 'code',
            ],
            'problem_items' => [
                'idx_problem_items_problem_id' => 'problem_id',
                'idx_problem_items_good_id' => 'good_id',
            ],
            'users' => [
                'idx_users_email' => 'email',
            ],
            'notifications' => [
                'idx_notifications_notifiable_type_notifiable_id' => 'notifiable_type(50), notifiable_id',
                'idx_notifications_read_at' => 'read_at',
            ],
            'jobs' => [
                'idx_jobs_queue' => 'queue',
                'idx_jobs_reserved' => 'reserved',
                'idx_jobs_available_at' => 'available_at',
            ],
        ];

        $createdCount = 0;

        foreach ($indexes as $table => $tableIndexes) {
            foreach ($tableIndexes as $indexName => $columns) {
                try {
                    $exists = DB::select("
                        SELECT COUNT(*) as count 
                        FROM information_schema.statistics 
                        WHERE table_schema = DATABASE() 
                        AND table_name = '{$table}' 
                        AND index_name = '{$indexName}'
                    ");

                    if ($exists[0]->count == 0) {
                        DB::statement("ALTER TABLE `{$table}` ADD INDEX `{$indexName}` ({$columns})");
                        $createdCount++;
                        Log::info("Created index: {$indexName} on table {$table}");
                    }
                } catch (\Exception $e) {
                    Log::warning("Failed to create index {$indexName} on {$table}: " . $e->getMessage());
                }
            }
        }

        return $createdCount;
    }

    public function optimizeTables(): int
    {
        $tables = ['problems', 'goods', 'problem_items', 'users', 'locations', 'notifications', 'jobs'];
        $optimizedCount = 0;

        foreach ($tables as $table) {
            try {
                DB::statement("OPTIMIZE TABLE `{$table}`");
                $optimizedCount++;
                Log::info("Optimized table: {$table}");
            } catch (\Exception $e) {
                Log::warning("Failed to optimize table {$table}: " . $e->getMessage());
            }
        }

        return $optimizedCount;
    }

    public function enableQueryCache(): bool
    {
        try {
            // Enable query cache in MySQL/MariaDB
            DB::statement("SET GLOBAL query_cache_type = ON");
            DB::statement("SET GLOBAL query_cache_size = 268435456"); // 256MB
            return true;
        } catch (\Exception $e) {
            Log::warning("Failed to enable query cache: " . $e->getMessage());
            return false;
        }
    }

    public function analyzeQueryPerformance(): array
    {
        $performance = [];

        // Check table sizes
        $performance['table_sizes'] = $this->getTableSizes();

        // Check index usage
        $performance['index_usage'] = $this->getIndexUsage();

        // Check slow queries
        $performance['slow_queries'] = $this->getSlowQueries();

        return $performance;
    }

    protected function getTableSizes(): array
    {
        $sizes = DB::select("
            SELECT 
                table_name,
                ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb,
                table_rows
            FROM information_schema.tables
            WHERE table_schema = DATABASE()
            ORDER BY (data_length + index_length) DESC
            LIMIT 10
        ");

        return collect($sizes)->mapWithKeys(function ($table) {
            return [
                $table->table_name => [
                    'size_mb' => $table->size_mb,
                    'rows' => $table->table_rows,
                ]
            ];
        })->toArray();
    }

    protected function getIndexUsage(): array
    {
        try {
            $usage = DB::select("
                SELECT 
                    table_name,
                    index_name,
                    cardinality,
                    column_name
                FROM information_schema.statistics
                WHERE table_schema = DATABASE()
                AND index_name != 'PRIMARY'
                ORDER BY table_name, index_name
            ");

            return collect($usage)->groupBy('table_name')->map(function ($indexes) {
                return $indexes->map(function ($index) {
                    return [
                        'name' => $index->index_name,
                        'cardinality' => $index->cardinality,
                        'column' => $index->column_name,
                    ];
                })->toArray();
            })->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getSlowQueries(): array
    {
        try {
            $slowQueries = DB::select("
                SHOW VARIABLES LIKE 'slow_query_log%'
            ");

            $logEnabled = collect($slowQueries)->firstWhere('Variable_name', 'slow_query_log')->Value ?? 'OFF';
            $logFile = collect($slowQueries)->firstWhere('Variable_name', 'slow_query_log_file')->Value ?? '';

            return [
                'enabled' => $logEnabled === 'ON',
                'log_file' => $logFile,
                'threshold' => $this->slowQueryThreshold . 'ms',
            ];
        } catch (\Exception $e) {
            return [
                'enabled' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function monitorQueryExecution($callback)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        DB::enableQueryLog();

        try {
            $result = $callback();

            $queries = DB::getQueryLog();
            $executionTime = (microtime(true) - $startTime) * 1000;
            $memoryUsage = (memory_get_usage() - $startMemory) / 1024 / 1024;

            $queryInfo = [
                'execution_time_ms' => round($executionTime, 2),
                'memory_usage_mb' => round($memoryUsage, 2),
                'query_count' => count($queries),
                'queries' => $queries,
            ];

            if ($executionTime > $this->slowQueryThreshold) {
                Log::warning('Slow query detected', $queryInfo);
                $queryInfo['slow_query'] = true;
            }

            return [
                'result' => $result,
                'performance' => $queryInfo,
            ];
        } finally {
            DB::disableQueryLog();
        }
    }

    public function createMaterializedViews(): array
    {
        $views = [];

        try {
            // Create summary statistics view
            DB::statement("
                CREATE OR REPLACE VIEW problem_statistics AS
                SELECT 
                    status,
                    COUNT(*) as count,
                    DATE(created_at) as date,
                    DATE_FORMAT(created_at, '%Y-%m') as month
                FROM problems
                GROUP BY status, date, month
            ");
            $views[] = 'problem_statistics';

            // Create user problem summary view
            DB::statement("
                CREATE OR REPLACE VIEW user_problem_summary AS
                SELECT 
                    user_id,
                    COUNT(*) as total_problems,
                    SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as completed_problems,
                    MIN(created_at) as first_problem_date,
                    MAX(created_at) as last_problem_date
                FROM problems
                GROUP BY user_id
            ");
            $views[] = 'user_problem_summary';

            // Create goods usage summary view
            DB::statement("
                CREATE OR REPLACE VIEW goods_damage_summary AS
                SELECT 
                    g.id,
                    g.name,
                    g.code,
                    COUNT(pi.id) as damage_count,
                    COALESCE(SUM(pi.price), 0) as total_repair_cost
                FROM goods g
                LEFT JOIN problem_items pi ON g.id = pi.good_id
                GROUP BY g.id, g.name, g.code
            ");
            $views[] = 'goods_damage_summary';

            Log::info('Created materialized views', ['views' => $views]);

        } catch (\Exception $e) {
            Log::error('Failed to create materialized views: ' . $e->getMessage());
        }

        return $views;
    }

    public function setupReplicationSafety(): bool
    {
        try {
            // Enable safe replication practices
            DB::statement("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO'");
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to setup replication safety: ' . $e->getMessage());
            return false;
        }
    }

    public function getDatabaseMetrics(): array
    {
        return [
            'connection_count' => $this->getConnectionCount(),
            'uptime' => $this->getDatabaseUptime(),
            'buffer_pool_size' => $this->getBufferPoolSize(),
            'cache_hit_ratio' => $this->getCacheHitRatio(),
        ];
    }

    protected function getConnectionCount(): int
    {
        try {
            $result = DB::select("SHOW STATUS LIKE 'Threads_connected'");
            return (int) ($result[0]->Value ?? 0);
        } catch (\Exception $e) {
            return 0;
        }
    }

    protected function getDatabaseUptime(): string
    {
        try {
            $result = DB::select("SHOW STATUS LIKE 'Uptime'");
            $seconds = (int) ($result[0]->Value ?? 0);
            return gmdate('H:i:s', $seconds);
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    protected function getBufferPoolSize(): array
    {
        try {
            $result = DB::select("SHOW STATUS LIKE 'Innodb_buffer_pool_pages_%'");
            
            $data = collect($result)->mapWithKeys(function ($item) {
                return [str_replace('Innodb_buffer_pool_pages_', '', $item->Variable_name) => (int) $item->Value];
            });

            return [
                'total' => $data->get('total', 0),
                'free' => $data->get('free', 0),
                'dirty' => $data->get('dirty', 0),
                'usage_percentage' => $data->get('total', 0) > 0 
                    ? round((($data->get('total', 0) - $data->get('free', 0)) / $data->get('total', 0)) * 100, 2)
                    : 0,
            ];
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getCacheHitRatio(): array
    {
        try {
            $result = DB::select("SHOW STATUS LIKE 'Innodb_buffer_pool_read%'");
            
            $data = collect($result)->mapWithKeys(function ($item) {
                return [str_replace('Innodb_buffer_pool_read_', '', $item->Variable_name) => (int) $item->Value];
            });

            $reads = $data->get('s', 0) + $data->get('a', 0);
            $hitRatio = $reads > 0 ? ($data->get('s', 0) / $reads) * 100 : 0;

            return [
                'hit_ratio_percentage' => round($hitRatio, 2),
                'reads_from_cache' => $data->get('s', 0),
                'reads_from_disk' => $data->get('a', 0),
                'total_reads' => $reads,
            ];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function cleanupOldData(int $daysToKeep = 90): int
    {
        $deletedCount = 0;

        try {
            // Clean old failed jobs
            $deletedCount += DB::table('failed_jobs')
                ->where('failed_at', '<', now()->subDays($daysToKeep))
                ->delete();

            // Clean old notifications (read and old)
            $deletedCount += DB::table('notifications')
                ->whereNotNull('read_at')
                ->where('updated_at', '<', now()->subDays($daysToKeep))
                ->delete();

            // Clean cache entries older than retention period
            if (config('cache.default') === 'database') {
                $deletedCount += DB::table('cache')
                    ->where('expiration', '<', now()->timestamp)
                    ->delete();
            }

            Log::info("Cleaned up old data", [
                'days_kept' => $daysToKeep,
                'deleted_count' => $deletedCount
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to cleanup old data: ' . $e->getMessage());
        }

        return $deletedCount;
    }
}