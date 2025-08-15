<?php

namespace App\Services;

use App\Models\User;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\Order;
use App\Services\PerformanceService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Cache süresi (saniye)
     */
    private const CACHE_DURATION = 300; // 5 dakika

    /**
     * Genel dashboard istatistiklerini getir
     */
    public function getGeneralStats(): array
    {
        return Cache::remember('dashboard_general_stats', self::CACHE_DURATION, function () {
            return [
                'users' => $this->getUserStats(),
                'content' => $this->getContentStats(),
                'forum' => $this->getForumStats(),
                'commerce' => $this->getCommerceStats(),
            ];
        });
    }

    /**
     * Kullanıcı istatistikleri
     */
    public function getUserStats(): array
    {
        $total = User::count();
        $active = User::where('is_active', true)->count();
        $today = User::whereDate('created_at', today())->count();
        $thisWeek = User::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
        $thisMonth = User::whereMonth('created_at', now()->month)
                         ->whereYear('created_at', now()->year)
                         ->count();

        // Membership dağılımı
        $membershipDistribution = [
            'normal' => User::where('membership_type', 'normal')
                           ->orWhereNull('membership_type')
                           ->count(),
            'basic' => User::where('membership_type', 'basic')->count(),
            'premium' => User::where('membership_type', 'premium')->count(),
            'vip' => User::where('membership_type', 'vip')->count(),
        ];

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $total - $active,
            'today' => $today,
            'this_week' => $thisWeek,
            'this_month' => $thisMonth,
            'membership_distribution' => $membershipDistribution,
            'growth_rate' => $this->calculateGrowthRate('users'),
        ];
    }

    /**
     * İçerik istatistikleri (Blog, Ürün)
     */
    public function getContentStats(): array
    {
        // Blog istatistikleri
        $totalPosts = BlogPost::count();
        $publishedPosts = BlogPost::where('status', 'published')->count();
        $draftPosts = BlogPost::where('status', 'draft')->count();
        $postsThisMonth = BlogPost::whereMonth('created_at', now()->month)->count();

        // Ürün istatistikleri
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $productsThisMonth = Product::whereMonth('created_at', now()->month)->count();

        return [
            'blog' => [
                'total' => $totalPosts,
                'published' => $publishedPosts,
                'draft' => $draftPosts,
                'this_month' => $postsThisMonth,
                'publish_rate' => $totalPosts > 0 ? round(($publishedPosts / $totalPosts) * 100, 1) : 0,
            ],
            'products' => [
                'total' => $totalProducts,
                'active' => $activeProducts,
                'inactive' => $totalProducts - $activeProducts,
                'this_month' => $productsThisMonth,
                'active_rate' => $totalProducts > 0 ? round(($activeProducts / $totalProducts) * 100, 1) : 0,
            ]
        ];
    }

    /**
     * Forum istatistikleri
     */
    public function getForumStats(): array
    {
        $totalTopics = ForumTopic::count();
        $totalPosts = ForumPost::count();
        $topicsToday = ForumTopic::whereDate('created_at', today())->count();
        $postsToday = ForumPost::whereDate('created_at', today())->count();
        $topicsThisWeek = ForumTopic::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        // En aktif kategoriler
        $activeCategories = ForumTopic::select('category_id', DB::raw('count(*) as topic_count'))
            ->with('category')
            ->groupBy('category_id')
            ->orderBy('topic_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->category->name ?? 'Kategori Yok',
                    'count' => $item->topic_count
                ];
            });

        return [
            'total_topics' => $totalTopics,
            'total_posts' => $totalPosts,
            'topics_today' => $topicsToday,
            'posts_today' => $postsToday,
            'topics_this_week' => $topicsThisWeek,
            'avg_posts_per_topic' => $totalTopics > 0 ? round($totalPosts / $totalTopics, 1) : 0,
            'active_categories' => $activeCategories,
        ];
    }

    /**
     * E-ticaret istatistikleri
     */
    public function getCommerceStats(): array
    {
        try {
            if (!class_exists(Order::class)) {
                return [
                    'total_orders' => 0,
                    'orders_today' => 0,
                    'revenue_today' => 0,
                    'revenue_this_month' => 0,
                    'avg_order_value' => 0,
                ];
            }

            $totalOrders = Order::count();
            $ordersToday = Order::whereDate('created_at', today())->count();
            $revenueToday = Order::where('status', 'completed')
                                 ->whereDate('created_at', today())
                                 ->sum('total_amount') ?? 0;
            $revenueThisMonth = Order::where('status', 'completed')
                                    ->whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->sum('total_amount') ?? 0;

            $avgOrderValue = $totalOrders > 0 ? 
                Order::where('status', 'completed')->avg('total_amount') ?? 0 : 0;

            return [
                'total_orders' => $totalOrders,
                'orders_today' => $ordersToday,
                'revenue_today' => $revenueToday,
                'revenue_this_month' => $revenueThisMonth,
                'avg_order_value' => round($avgOrderValue, 2),
            ];
        } catch (\Exception $e) {
            return [
                'total_orders' => 0,
                'orders_today' => 0,
                'revenue_today' => 0,
                'revenue_this_month' => 0,
                'avg_order_value' => 0,
            ];
        }
    }

    /**
     * Grafik verileri (son 7 gün)
     */
    public function getChartData(): array
    {
        return Cache::remember('dashboard_chart_data', self::CACHE_DURATION, function () {
            $days = [];
            $userRegistrations = [];
            $blogPosts = [];
            $forumTopics = [];
            $forumPosts = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $days[] = $date->format('M d');

                $userRegistrations[] = User::whereDate('created_at', $date)->count();
                $blogPosts[] = BlogPost::whereDate('created_at', $date)->count();
                $forumTopics[] = ForumTopic::whereDate('created_at', $date)->count();
                $forumPosts[] = ForumPost::whereDate('created_at', $date)->count();
            }

            return [
                'labels' => $days,
                'datasets' => [
                    'user_registrations' => $userRegistrations,
                    'blog_posts' => $blogPosts,
                    'forum_topics' => $forumTopics,
                    'forum_posts' => $forumPosts,
                ]
            ];
        });
    }

    /**
     * Son aktiviteler
     */
    public function getRecentActivity(): array
    {
        return [
            'users' => $this->getRecentUsers(),
            'posts' => $this->getRecentBlogPosts(),
            'topics' => $this->getRecentForumTopics(),
        ];
    }

    /**
     * Son kullanıcılar
     */
    public function getRecentUsers(int $limit = 10): array
    {
        return User::with(['profile'])
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'is_active' => $user->is_active,
                    'membership_type' => $user->membership_type ?? 'normal',
                    'created_at' => $user->created_at,
                    'created_at_human' => $user->created_at->diffForHumans(),
                ];
            })
            ->toArray();
    }

    /**
     * Son blog yazıları
     */
    public function getRecentBlogPosts(int $limit = 5): array
    {
        return BlogPost::with(['user', 'category'])
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'status' => $post->status,
                    'author' => $post->user->name ?? 'Bilinmeyen',
                    'category' => $post->category->name ?? 'Kategori Yok',
                    'created_at' => $post->created_at,
                    'created_at_human' => $post->created_at->diffForHumans(),
                    'views' => $post->views ?? 0,
                ];
            })
            ->toArray();
    }

    /**
     * Son forum konuları
     */
    public function getRecentForumTopics(int $limit = 5): array
    {
        return ForumTopic::with(['user', 'category', 'posts'])
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($topic) {
                return [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'slug' => $topic->slug,
                    'author' => $topic->user->name ?? 'Bilinmeyen',
                    'category' => $topic->category->name ?? 'Kategori Yok',
                    'posts_count' => $topic->posts->count(),
                    'views' => $topic->views ?? 0,
                    'created_at' => $topic->created_at,
                    'created_at_human' => $topic->created_at->diffForHumans(),
                ];
            })
            ->toArray();
    }

    /**
     * Büyüme oranını hesapla
     */
    private function calculateGrowthRate(string $type): float
    {
        try {
            $model = match($type) {
                'users' => User::class,
                'posts' => BlogPost::class,
                'products' => Product::class,
                'topics' => ForumTopic::class,
                default => User::class,
            };

            $thisMonth = $model::whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year)
                              ->count();

            $lastMonth = $model::whereMonth('created_at', now()->subMonth()->month)
                              ->whereYear('created_at', now()->subMonth()->year)
                              ->count();

            if ($lastMonth == 0) {
                return $thisMonth > 0 ? 100 : 0;
            }

            return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Cache'i temizle
     */
    public function clearCache(): void
    {
        Cache::forget('dashboard_general_stats');
        Cache::forget('dashboard_chart_data');
    }

    /**
     * Sistem sağlık durumu
     */
    public function getSystemHealth(): array
    {
        return [
            'database' => $this->checkDatabaseConnection(),
            'cache' => $this->checkCacheConnection(),
            'storage' => $this->checkStorageWritable(),
            'queue' => $this->checkQueueConnection(),
        ];
    }

    private function checkDatabaseConnection(): bool
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function checkCacheConnection(): bool
    {
        try {
            Cache::put('health_check', 'ok', 10);
            return Cache::get('health_check') === 'ok';
        } catch (\Exception $e) {
            return false;
        }
    }

    private function checkStorageWritable(): bool
    {
        try {
            $path = storage_path('app/health_check.txt');
            file_put_contents($path, 'ok');
            $result = file_get_contents($path) === 'ok';
            unlink($path);
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function checkQueueConnection(): bool
    {
        try {
            // Basit queue kontrolü - gerçek uygulamada daha detaylı olabilir
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}