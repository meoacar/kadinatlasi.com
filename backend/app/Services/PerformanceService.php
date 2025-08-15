<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class PerformanceService
{
    /**
     * Cache süreleri (dakika)
     */
    const CACHE_DURATIONS = [
        'dashboard_stats' => 15,
        'user_stats' => 30,
        'content_stats' => 60,
        'system_stats' => 120,
    ];

    /**
     * Dashboard istatistiklerini cache'le
     */
    public function getCachedDashboardStats(): array
    {
        return Cache::remember('admin_dashboard_stats', self::CACHE_DURATIONS['dashboard_stats'], function () {
            return [
                'users_count' => $this->optimizedCount('users'),
                'active_users_count' => $this->optimizedCount('users', ['is_active' => true]),
                'blog_posts_count' => $this->optimizedCount('blog_posts'),
                'published_posts_count' => $this->optimizedCount('blog_posts', ['status' => 'published']),
                'products_count' => $this->optimizedCount('products'),
                'active_products_count' => $this->optimizedCount('products', ['is_active' => true]),
                'forum_topics_count' => $this->optimizedCount('forum_topics'),
                'active_topics_count' => $this->optimizedCount('forum_topics', ['is_active' => true]),
                'total_views' => $this->getTotalViews(),
                'monthly_registrations' => $this->getMonthlyRegistrations(),
            ];
        });
    }

    /**
     * Optimized count query
     */
    private function optimizedCount(string $table, array $conditions = []): int
    {
        $query = DB::table($table);
        
        foreach ($conditions as $column => $value) {
            $query->where($column, $value);
        }
        
        return $query->count();
    }

    /**
     * Toplam görüntülenme sayısı
     */
    private function getTotalViews(): int
    {
        return Cache::remember('total_views', 60, function () {
            $blogViews = DB::table('blog_posts')->sum('views_count') ?? 0;
            $productViews = DB::table('products')->sum('views_count') ?? 0;
            $forumViews = DB::table('forum_topics')->sum('views_count') ?? 0;
            
            return $blogViews + $productViews + $forumViews;
        });
    }

    /**
     * Aylık kayıt sayıları
     */
    private function getMonthlyRegistrations(): array
    {
        return Cache::remember('monthly_registrations', 60, function () {
            return DB::table('users')
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('count', 'month')
                ->toArray();
        });
    }

    /**
     * Query optimizasyonu için eager loading helper
     */
    public function optimizeUserQuery(Builder $query): Builder
    {
        return $query->with([
            'profile' => function ($q) {
                $q->select('user_id', 'birth_date', 'zodiac_sign', 'points');
            }
        ])->select([
            'id', 'name', 'email', 'membership_type', 'is_active', 
            'created_at', 'updated_at'
        ]);
    }

    /**
     * Blog query optimizasyonu
     */
    public function optimizeBlogQuery(Builder $query): Builder
    {
        return $query->with([
            'author:id,name',
            'category:id,name'
        ])->select([
            'id', 'title', 'slug', 'status', 'user_id', 'category_id',
            'views_count', 'likes_count', 'published_at', 'created_at'
        ]);
    }

    /**
     * Product query optimizasyonu
     */
    public function optimizeProductQuery(Builder $query): Builder
    {
        return $query->with([
            'category:id,name'
        ])->select([
            'id', 'name', 'slug', 'sku', 'price', 'sale_price', 'brand',
            'category_id', 'is_active', 'in_stock', 'views_count', 'created_at'
        ]);
    }

    /**
     * Forum query optimizasyonu
     */
    public function optimizeForumQuery(Builder $query): Builder
    {
        return $query->with([
            'author:id,name',
            'group:id,name'
        ])->select([
            'id', 'title', 'slug', 'user_id', 'group_id', 'is_active',
            'views_count', 'replies_count', 'created_at'
        ]);
    }

    /**
     * Database indexleri kontrol et
     */
    public function checkDatabaseIndexes(): array
    {
        $recommendations = [];

        // Users tablosu indexleri
        if (!$this->hasIndex('users', 'email')) {
            $recommendations[] = 'CREATE INDEX idx_users_email ON users(email)';
        }
        if (!$this->hasIndex('users', 'is_active')) {
            $recommendations[] = 'CREATE INDEX idx_users_is_active ON users(is_active)';
        }
        if (!$this->hasIndex('users', 'created_at')) {
            $recommendations[] = 'CREATE INDEX idx_users_created_at ON users(created_at)';
        }

        // Blog posts indexleri
        if (!$this->hasIndex('blog_posts', 'status')) {
            $recommendations[] = 'CREATE INDEX idx_blog_posts_status ON blog_posts(status)';
        }
        if (!$this->hasIndex('blog_posts', 'user_id')) {
            $recommendations[] = 'CREATE INDEX idx_blog_posts_user_id ON blog_posts(user_id)';
        }
        if (!$this->hasIndex('blog_posts', 'category_id')) {
            $recommendations[] = 'CREATE INDEX idx_blog_posts_category_id ON blog_posts(category_id)';
        }

        // Products indexleri
        if (!$this->hasIndex('products', 'is_active')) {
            $recommendations[] = 'CREATE INDEX idx_products_is_active ON products(is_active)';
        }
        if (!$this->hasIndex('products', 'category_id')) {
            $recommendations[] = 'CREATE INDEX idx_products_category_id ON products(category_id)';
        }

        return $recommendations;
    }

    /**
     * Index varlığını kontrol et
     */
    private function hasIndex(string $table, string $column): bool
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Column_name = ?", [$column]);
            return !empty($indexes);
        } catch (\Exception $e) {
            Log::warning("Could not check index for {$table}.{$column}: " . $e->getMessage());
            return true; // Hata durumunda varsayalım ki var
        }
    }

    /**
     * Slow query'leri logla
     */
    public function enableQueryLogging(): void
    {
        DB::listen(function ($query) {
            if ($query->time > 1000) { // 1 saniyeden uzun sürerse
                Log::warning('Slow query detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . 'ms'
                ]);
            }
        });
    }

    /**
     * Cache'i temizle
     */
    public function clearCache(array $keys = []): array
    {
        $clearedKeys = [];
        
        if (empty($keys)) {
            // Tüm admin cache'lerini temizle
            $keys = [
                'admin_dashboard_stats',
                'total_views',
                'monthly_registrations',
                'user_stats_*',
                'content_stats_*',
                'system_stats_*'
            ];
        }

        foreach ($keys as $key) {
            if (str_contains($key, '*')) {
                // Wildcard pattern için
                $pattern = str_replace('*', '', $key);
                $this->clearCacheByPattern($pattern);
                $clearedKeys[] = $key;
            } else {
                if (Cache::forget($key)) {
                    $clearedKeys[] = $key;
                }
            }
        }

        return $clearedKeys;
    }

    /**
     * Pattern ile cache temizle
     */
    private function clearCacheByPattern(string $pattern): void
    {
        try {
            // Redis kullanıyorsak
            if (config('cache.default') === 'redis') {
                $redis = Cache::getRedis();
                $keys = $redis->keys($pattern . '*');
                if (!empty($keys)) {
                    $redis->del($keys);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Could not clear cache by pattern: ' . $e->getMessage());
        }
    }

    /**
     * Memory usage'ı kontrol et
     */
    public function getMemoryUsage(): array
    {
        return [
            'current' => memory_get_usage(true),
            'current_formatted' => $this->formatBytes(memory_get_usage(true)),
            'peak' => memory_get_peak_usage(true),
            'peak_formatted' => $this->formatBytes(memory_get_peak_usage(true)),
            'limit' => ini_get('memory_limit'),
        ];
    }

    /**
     * Database connection pool durumu
     */
    public function getDatabaseStats(): array
    {
        try {
            $stats = DB::select('SHOW STATUS LIKE "Threads_connected"');
            $maxConnections = DB::select('SHOW VARIABLES LIKE "max_connections"');
            
            return [
                'active_connections' => $stats[0]->Value ?? 0,
                'max_connections' => $maxConnections[0]->Value ?? 0,
                'connection_usage' => $stats[0]->Value / $maxConnections[0]->Value * 100,
            ];
        } catch (\Exception $e) {
            return ['error' => 'Could not retrieve database stats'];
        }
    }

    /**
     * Byte formatı
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Performans raporu oluştur
     */
    public function generatePerformanceReport(): array
    {
        return [
            'memory_usage' => $this->getMemoryUsage(),
            'database_stats' => $this->getDatabaseStats(),
            'cache_stats' => $this->getCacheStats(),
            'index_recommendations' => $this->checkDatabaseIndexes(),
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Cache istatistikleri
     */
    private function getCacheStats(): array
    {
        try {
            if (config('cache.default') === 'redis') {
                $redis = Cache::getRedis();
                $info = $redis->info();
                
                return [
                    'used_memory' => $info['used_memory_human'] ?? 'N/A',
                    'connected_clients' => $info['connected_clients'] ?? 0,
                    'total_commands_processed' => $info['total_commands_processed'] ?? 0,
                    'keyspace_hits' => $info['keyspace_hits'] ?? 0,
                    'keyspace_misses' => $info['keyspace_misses'] ?? 0,
                    'hit_rate' => $this->calculateHitRate($info),
                ];
            }
            
            return ['driver' => config('cache.default'), 'stats' => 'Not available'];
        } catch (\Exception $e) {
            return ['error' => 'Could not retrieve cache stats'];
        }
    }

    /**
     * Cache hit rate hesapla
     */
    private function calculateHitRate(array $info): string
    {
        $hits = $info['keyspace_hits'] ?? 0;
        $misses = $info['keyspace_misses'] ?? 0;
        $total = $hits + $misses;
        
        if ($total === 0) {
            return '0%';
        }
        
        return round(($hits / $total) * 100, 2) . '%';
    }
}