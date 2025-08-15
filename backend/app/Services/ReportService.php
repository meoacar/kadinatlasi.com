<?php

namespace App\Services;

use App\Models\User;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\AdminActivity;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Kullanıcı raporları
     */
    public function getUserReports(array $filters = []): array
    {
        $dateFrom = $filters['date_from'] ?? now()->subMonth();
        $dateTo = $filters['date_to'] ?? now();

        return [
            'overview' => $this->getUserOverview($dateFrom, $dateTo),
            'registrations' => $this->getUserRegistrations($dateFrom, $dateTo),
            'activity' => $this->getUserActivity($dateFrom, $dateTo),
            'demographics' => $this->getUserDemographics(),
            'engagement' => $this->getUserEngagement($dateFrom, $dateTo),
            'top_users' => $this->getTopUsers($dateFrom, $dateTo)
        ];
    }

    /**
     * İçerik istatistikleri
     */
    public function getContentReports(array $filters = []): array
    {
        $dateFrom = $filters['date_from'] ?? now()->subMonth();
        $dateTo = $filters['date_to'] ?? now();

        return [
            'overview' => $this->getContentOverview($dateFrom, $dateTo),
            'blog_stats' => $this->getBlogStats($dateFrom, $dateTo),
            'product_stats' => $this->getProductStats($dateFrom, $dateTo),
            'forum_stats' => $this->getForumStats($dateFrom, $dateTo),
            'popular_content' => $this->getPopularContent($dateFrom, $dateTo),
            'content_trends' => $this->getContentTrends($dateFrom, $dateTo)
        ];
    }

    /**
     * Satış raporları
     */
    public function getSalesReports(array $filters = []): array
    {
        $dateFrom = $filters['date_from'] ?? now()->subMonth();
        $dateTo = $filters['date_to'] ?? now();

        return [
            'overview' => $this->getSalesOverview($dateFrom, $dateTo),
            'products' => $this->getProductSales($dateFrom, $dateTo),
            'categories' => $this->getCategorySales($dateFrom, $dateTo),
            'trends' => $this->getSalesTrends($dateFrom, $dateTo)
        ];
    }

    /**
     * Sistem raporları
     */
    public function getSystemReports(array $filters = []): array
    {
        $dateFrom = $filters['date_from'] ?? now()->subWeek();
        $dateTo = $filters['date_to'] ?? now();

        return [
            'performance' => $this->getPerformanceStats($dateFrom, $dateTo),
            'security' => $this->getSecurityStats($dateFrom, $dateTo),
            'admin_activity' => $this->getAdminActivityStats($dateFrom, $dateTo),
            'errors' => $this->getErrorStats($dateFrom, $dateTo)
        ];
    }

    /**
     * Kullanıcı genel bakış
     */
    private function getUserOverview($dateFrom, $dateTo): array
    {
        $totalUsers = User::count();
        $newUsers = User::whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $activeUsers = User::where('is_active', true)->count();
        $premiumUsers = User::where('membership_type', 'premium')->count();

        $previousPeriod = $dateFrom->copy()->sub($dateTo->diffInDays($dateFrom), 'days');
        $previousNewUsers = User::whereBetween('created_at', [$previousPeriod, $dateFrom])->count();

        return [
            'total_users' => $totalUsers,
            'new_users' => $newUsers,
            'active_users' => $activeUsers,
            'premium_users' => $premiumUsers,
            'growth_rate' => $previousNewUsers > 0 ? (($newUsers - $previousNewUsers) / $previousNewUsers) * 100 : 0,
            'active_percentage' => $totalUsers > 0 ? ($activeUsers / $totalUsers) * 100 : 0,
            'premium_percentage' => $totalUsers > 0 ? ($premiumUsers / $totalUsers) * 100 : 0
        ];
    }

    /**
     * Kullanıcı kayıtları
     */
    private function getUserRegistrations($dateFrom, $dateTo): Collection
    {
        return User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Kullanıcı aktivitesi
     */
    private function getUserActivity($dateFrom, $dateTo): array
    {
        $blogPosts = BlogPost::whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $forumTopics = ForumTopic::whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $forumPosts = ForumPost::whereBetween('created_at', [$dateFrom, $dateTo])->count();

        return [
            'blog_posts' => $blogPosts,
            'forum_topics' => $forumTopics,
            'forum_posts' => $forumPosts,
            'total_activity' => $blogPosts + $forumTopics + $forumPosts
        ];
    }

    /**
     * Kullanıcı demografikleri
     */
    private function getUserDemographics(): array
    {
        $membershipTypes = User::selectRaw('membership_type, COUNT(*) as count')
            ->groupBy('membership_type')
            ->pluck('count', 'membership_type')
            ->toArray();

        $zodiacSigns = User::selectRaw('zodiac_sign, COUNT(*) as count')
            ->whereNotNull('zodiac_sign')
            ->groupBy('zodiac_sign')
            ->pluck('count', 'zodiac_sign')
            ->toArray();

        $ageGroups = User::selectRaw('
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 18 THEN "18 altı"
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 24 THEN "18-24"
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 25 AND 34 THEN "25-34"
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 35 AND 44 THEN "35-44"
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 45 AND 54 THEN "45-54"
                ELSE "55+"
            END as age_group,
            COUNT(*) as count
        ')
            ->whereNotNull('birth_date')
            ->groupBy('age_group')
            ->pluck('count', 'age_group')
            ->toArray();

        return [
            'membership_types' => $membershipTypes,
            'zodiac_signs' => $zodiacSigns,
            'age_groups' => $ageGroups
        ];
    }

    /**
     * Kullanıcı etkileşimi
     */
    private function getUserEngagement($dateFrom, $dateTo): array
    {
        $activeUsers = User::whereHas('blogPosts', function($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('created_at', [$dateFrom, $dateTo]);
        })->orWhereHas('forumTopics', function($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('created_at', [$dateFrom, $dateTo]);
        })->count();

        $totalUsers = User::where('is_active', true)->count();

        return [
            'active_users' => $activeUsers,
            'engagement_rate' => $totalUsers > 0 ? ($activeUsers / $totalUsers) * 100 : 0,
            'avg_posts_per_user' => $activeUsers > 0 ? BlogPost::whereBetween('created_at', [$dateFrom, $dateTo])->count() / $activeUsers : 0
        ];
    }

    /**
     * En aktif kullanıcılar
     */
    private function getTopUsers($dateFrom, $dateTo): Collection
    {
        return User::withCount([
            'blogPosts' => function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('created_at', [$dateFrom, $dateTo]);
            },
            'forumTopics' => function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('created_at', [$dateFrom, $dateTo]);
            }
        ])
        ->having('blog_posts_count', '>', 0)
        ->orHaving('forum_topics_count', '>', 0)
        ->orderByDesc('blog_posts_count')
        ->orderByDesc('forum_topics_count')
        ->limit(10)
        ->get();
    }

    /**
     * İçerik genel bakış
     */
    private function getContentOverview($dateFrom, $dateTo): array
    {
        $totalBlogPosts = BlogPost::count();
        $newBlogPosts = BlogPost::whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $publishedPosts = BlogPost::where('status', 'published')->count();

        $totalProducts = Product::count();
        $newProducts = Product::whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $activeProducts = Product::where('is_active', true)->count();

        $totalForumTopics = ForumTopic::count();
        $newForumTopics = ForumTopic::whereBetween('created_at', [$dateFrom, $dateTo])->count();

        return [
            'blog_posts' => [
                'total' => $totalBlogPosts,
                'new' => $newBlogPosts,
                'published' => $publishedPosts,
                'published_rate' => $totalBlogPosts > 0 ? ($publishedPosts / $totalBlogPosts) * 100 : 0
            ],
            'products' => [
                'total' => $totalProducts,
                'new' => $newProducts,
                'active' => $activeProducts,
                'active_rate' => $totalProducts > 0 ? ($activeProducts / $totalProducts) * 100 : 0
            ],
            'forum' => [
                'total_topics' => $totalForumTopics,
                'new_topics' => $newForumTopics
            ]
        ];
    }

    /**
     * Blog istatistikleri
     */
    private function getBlogStats($dateFrom, $dateTo): array
    {
        $posts = BlogPost::whereBetween('created_at', [$dateFrom, $dateTo])->get();
        
        $categoryStats = BlogPost::join('categories', 'blog_posts.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, COUNT(*) as count')
            ->whereBetween('blog_posts.created_at', [$dateFrom, $dateTo])
            ->groupBy('categories.name')
            ->orderByDesc('count')
            ->get();

        $authorStats = BlogPost::join('users', 'blog_posts.user_id', '=', 'users.id')
            ->selectRaw('users.name, COUNT(*) as count')
            ->whereBetween('blog_posts.created_at', [$dateFrom, $dateTo])
            ->groupBy('users.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return [
            'total_posts' => $posts->count(),
            'avg_views' => $posts->avg('views_count') ?? 0,
            'avg_likes' => $posts->avg('likes_count') ?? 0,
            'category_distribution' => $categoryStats,
            'top_authors' => $authorStats
        ];
    }

    /**
     * Ürün istatistikleri
     */
    private function getProductStats($dateFrom, $dateTo): array
    {
        $products = Product::whereBetween('created_at', [$dateFrom, $dateTo])->get();
        
        $categoryStats = Product::join('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->selectRaw('product_categories.name, COUNT(*) as count, AVG(products.price) as avg_price')
            ->whereBetween('products.created_at', [$dateFrom, $dateTo])
            ->groupBy('product_categories.name')
            ->orderByDesc('count')
            ->get();

        return [
            'total_products' => $products->count(),
            'avg_price' => $products->avg('price') ?? 0,
            'price_range' => [
                'min' => $products->min('price') ?? 0,
                'max' => $products->max('price') ?? 0
            ],
            'category_distribution' => $categoryStats,
            'featured_products' => $products->where('is_featured', true)->count()
        ];
    }

    /**
     * Forum istatistikleri
     */
    private function getForumStats($dateFrom, $dateTo): array
    {
        $topics = ForumTopic::whereBetween('created_at', [$dateFrom, $dateTo])->get();
        $posts = ForumPost::whereBetween('created_at', [$dateFrom, $dateTo])->get();

        return [
            'total_topics' => $topics->count(),
            'total_posts' => $posts->count(),
            'avg_posts_per_topic' => $topics->count() > 0 ? $posts->count() / $topics->count() : 0,
            'active_topics' => $topics->where('is_active', true)->count()
        ];
    }

    /**
     * Popüler içerik
     */
    private function getPopularContent($dateFrom, $dateTo): array
    {
        $popularPosts = BlogPost::whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderByDesc('views_count')
            ->limit(10)
            ->get(['title', 'views_count', 'likes_count']);

        $popularProducts = Product::whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderByDesc('views_count')
            ->limit(10)
            ->get(['name', 'views_count', 'price']);

        return [
            'blog_posts' => $popularPosts,
            'products' => $popularProducts
        ];
    }

    /**
     * İçerik trendleri
     */
    private function getContentTrends($dateFrom, $dateTo): array
    {
        $blogTrends = BlogPost::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $productTrends = Product::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'blog_posts' => $blogTrends,
            'products' => $productTrends
        ];
    }

    /**
     * Satış genel bakış
     */
    private function getSalesOverview($dateFrom, $dateTo): array
    {
        // Bu kısım e-ticaret sistemi olduğunda genişletilebilir
        $totalProducts = Product::count();
        $featuredProducts = Product::where('is_featured', true)->count();
        $avgPrice = Product::avg('price') ?? 0;

        return [
            'total_products' => $totalProducts,
            'featured_products' => $featuredProducts,
            'avg_price' => $avgPrice,
            'price_ranges' => $this->getPriceRanges()
        ];
    }

    /**
     * Fiyat aralıkları
     */
    private function getPriceRanges(): array
    {
        return Product::selectRaw('
            CASE 
                WHEN price < 50 THEN "0-50 TL"
                WHEN price BETWEEN 50 AND 100 THEN "50-100 TL"
                WHEN price BETWEEN 100 AND 250 THEN "100-250 TL"
                WHEN price BETWEEN 250 AND 500 THEN "250-500 TL"
                ELSE "500+ TL"
            END as price_range,
            COUNT(*) as count
        ')
            ->groupBy('price_range')
            ->pluck('count', 'price_range')
            ->toArray();
    }

    /**
     * Ürün satışları (placeholder)
     */
    private function getProductSales($dateFrom, $dateTo): array
    {
        // E-ticaret sistemi eklendiğinde genişletilebilir
        return [
            'top_products' => Product::orderByDesc('views_count')->limit(10)->get(),
            'category_performance' => ProductCategory::withCount('products')->get()
        ];
    }

    /**
     * Kategori satışları (placeholder)
     */
    private function getCategorySales($dateFrom, $dateTo): array
    {
        return ProductCategory::withCount('products')
            ->with(['products' => function($q) {
                $q->selectRaw('category_id, AVG(price) as avg_price, COUNT(*) as product_count')
                    ->groupBy('category_id');
            }])
            ->get()
            ->toArray();
    }

    /**
     * Satış trendleri (placeholder)
     */
    private function getSalesTrends($dateFrom, $dateTo): array
    {
        return [
            'daily_views' => Product::selectRaw('DATE(created_at) as date, SUM(views_count) as total_views')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        ];
    }

    /**
     * Performans istatistikleri
     */
    private function getPerformanceStats($dateFrom, $dateTo): array
    {
        return [
            'total_requests' => AdminActivity::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'unique_visitors' => AdminActivity::whereBetween('created_at', [$dateFrom, $dateTo])
                ->distinct('ip_address')->count('ip_address'),
            'avg_response_time' => 0.25, // Placeholder
            'error_rate' => 0.02 // Placeholder
        ];
    }

    /**
     * Güvenlik istatistikleri
     */
    private function getSecurityStats($dateFrom, $dateTo): array
    {
        $failedLogins = AdminActivity::where('action', 'failed_login')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        $successfulLogins = AdminActivity::where('action', 'login')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        $highSeverityEvents = AdminActivity::where('severity', 'high')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        return [
            'failed_logins' => $failedLogins,
            'successful_logins' => $successfulLogins,
            'high_severity_events' => $highSeverityEvents,
            'security_score' => $this->calculateSecurityScore($failedLogins, $successfulLogins, $highSeverityEvents)
        ];
    }

    /**
     * Admin aktivite istatistikleri
     */
    private function getAdminActivityStats($dateFrom, $dateTo): array
    {
        $activities = AdminActivity::whereBetween('created_at', [$dateFrom, $dateTo])->get();

        $actionDistribution = $activities->groupBy('action')
            ->map(function($group) {
                return $group->count();
            })
            ->toArray();

        $adminDistribution = $activities->groupBy('admin_id')
            ->map(function($group) {
                return $group->count();
            })
            ->toArray();

        return [
            'total_activities' => $activities->count(),
            'action_distribution' => $actionDistribution,
            'admin_distribution' => $adminDistribution,
            'most_active_hours' => $this->getMostActiveHours($activities)
        ];
    }

    /**
     * En aktif saatler
     */
    private function getMostActiveHours($activities): array
    {
        return $activities->groupBy(function($activity) {
            return $activity->created_at->format('H');
        })
        ->map(function($group) {
            return $group->count();
        })
        ->sortDesc()
        ->take(5)
        ->toArray();
    }

    /**
     * Hata istatistikleri
     */
    private function getErrorStats($dateFrom, $dateTo): array
    {
        $errorActivities = AdminActivity::where('action', 'failed_request')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        return [
            'total_errors' => $errorActivities->count(),
            'error_types' => $errorActivities->groupBy('new_values.status_code')
                ->map(function($group) {
                    return $group->count();
                })
                ->toArray(),
            'error_rate' => 0.02 // Placeholder
        ];
    }

    /**
     * Güvenlik skoru hesapla
     */
    private function calculateSecurityScore($failedLogins, $successfulLogins, $highSeverityEvents): int
    {
        $baseScore = 100;
        
        // Başarısız giriş oranı
        $totalLogins = $failedLogins + $successfulLogins;
        if ($totalLogins > 0) {
            $failureRate = ($failedLogins / $totalLogins) * 100;
            $baseScore -= min($failureRate * 2, 30);
        }
        
        // Yüksek önem olayları
        $baseScore -= min($highSeverityEvents * 5, 40);
        
        return max(0, min(100, (int)$baseScore));
    }
}