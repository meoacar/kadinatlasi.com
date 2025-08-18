<?php

namespace App\Services;

use App\Models\User;
use App\Models\BlogPost;
use App\Models\ForumTopic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    public function getDashboardStats()
    {
        return Cache::remember('dashboard_stats', 3600, function () {
            return [
                'total_users' => User::count(),
                'active_users_today' => User::whereDate('last_login_at', today())->count(),
                'total_blog_posts' => BlogPost::count(),
                'total_forum_topics' => ForumTopic::count(),
                'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
                'popular_categories' => $this->getPopularCategories(),
                'user_growth' => $this->getUserGrowthData(),
                'engagement_metrics' => $this->getEngagementMetrics()
            ];
        });
    }

    private function getPopularCategories()
    {
        return DB::table('categories')
            ->leftJoin('blog_posts', 'categories.id', '=', 'blog_posts.category_id')
            ->select('categories.name', DB::raw('COUNT(blog_posts.id) as post_count'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('post_count')
            ->limit(5)
            ->get();
    }

    private function getUserGrowthData()
    {
        return User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getEngagementMetrics()
    {
        return [
            'avg_session_duration' => '5:30', // Mock data
            'bounce_rate' => '35%',
            'page_views_per_session' => 4.2,
            'most_visited_pages' => [
                ['page' => '/hesaplama', 'views' => 1250],
                ['page' => '/blog', 'views' => 980],
                ['page' => '/forum', 'views' => 750],
                ['page' => '/astroloji', 'views' => 650]
            ]
        ];
    }
}