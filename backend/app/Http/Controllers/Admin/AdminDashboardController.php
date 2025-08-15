<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends AdminController
{
    /**
     * Dashboard ana sayfası
     */
    public function index()
    {
        try {
            $data = [
                'stats' => $this->getDashboardStats(),
                'recentUsers' => $this->getRecentUsers(),
                'recentPosts' => $this->getRecentPosts(),
                'recentTopics' => $this->getRecentTopics(),
                'chartData' => $this->getChartData(),
            ];

            return view('admin.dashboard.index', $data);
        } catch (\Exception $e) {
            return $this->handleException($e, 'load dashboard');
        }
    }

    /**
     * Dashboard istatistiklerini getir
     */
    private function getDashboardStats(): array
    {
        return Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_users' => User::count(),
                'active_users' => User::where('is_active', true)->count(),
                'new_users_today' => User::whereDate('created_at', today())->count(),
                'new_users_this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                
                'total_posts' => BlogPost::count(),
                'published_posts' => BlogPost::where('status', 'published')->count(),
                'draft_posts' => BlogPost::where('status', 'draft')->count(),
                'posts_this_month' => BlogPost::whereMonth('created_at', now()->month)->count(),
                
                'total_products' => Product::count(),
                'active_products' => Product::where('is_active', true)->count(),
                'products_this_month' => Product::whereMonth('created_at', now()->month)->count(),
                
                'total_forum_topics' => ForumTopic::count(),
                'forum_posts' => ForumPost::count(),
                'forum_topics_today' => ForumTopic::whereDate('created_at', today())->count(),
                
                'total_orders' => $this->getOrderCount(),
                'orders_today' => $this->getOrderCount(today()),
                'revenue_this_month' => $this->getRevenue('month'),
                'revenue_today' => $this->getRevenue('today'),
            ];
        });
    }

    /**
     * Son kullanıcıları getir
     */
    private function getRecentUsers()
    {
        return User::with(['profile'])
            ->latest()
            ->take(10)
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
            });
    }

    /**
     * Son blog yazılarını getir
     */
    private function getRecentPosts()
    {
        return BlogPost::with(['user', 'category'])
            ->latest()
            ->take(5)
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
            });
    }

    /**
     * Son forum konularını getir
     */
    private function getRecentTopics()
    {
        return ForumTopic::with(['user', 'category', 'posts'])
            ->latest()
            ->take(5)
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
            });
    }

    /**
     * Grafik verileri
     */
    private function getChartData(): array
    {
        return Cache::remember('admin_chart_data', 300, function () {
            // Son 7 günün kullanıcı kayıt verileri
            $userRegistrations = [];
            $blogPosts = [];
            $forumActivity = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dateStr = $date->format('Y-m-d');
                $dayName = $date->format('D');
                
                $userRegistrations[] = [
                    'date' => $dateStr,
                    'day' => $dayName,
                    'count' => User::whereDate('created_at', $date)->count()
                ];
                
                $blogPosts[] = [
                    'date' => $dateStr,
                    'day' => $dayName,
                    'count' => BlogPost::whereDate('created_at', $date)->count()
                ];
                
                $forumActivity[] = [
                    'date' => $dateStr,
                    'day' => $dayName,
                    'topics' => ForumTopic::whereDate('created_at', $date)->count(),
                    'posts' => ForumPost::whereDate('created_at', $date)->count()
                ];
            }

            // Membership dağılımı
            $membershipDistribution = [
                'normal' => User::where('membership_type', 'normal')->orWhereNull('membership_type')->count(),
                'basic' => User::where('membership_type', 'basic')->count(),
                'premium' => User::where('membership_type', 'premium')->count(),
                'vip' => User::where('membership_type', 'vip')->count(),
            ];

            return [
                'user_registrations' => $userRegistrations,
                'blog_posts' => $blogPosts,
                'forum_activity' => $forumActivity,
                'membership_distribution' => $membershipDistribution,
            ];
        });
    }

    /**
     * Sipariş sayısını getir (Order tablosu varsa)
     */
    private function getOrderCount($date = null): int
    {
        try {
            if (!class_exists(Order::class)) {
                return 0;
            }

            $query = Order::query();
            
            if ($date) {
                $query->whereDate('created_at', $date);
            }
            
            return $query->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Gelir hesapla (Order tablosu varsa)
     */
    private function getRevenue(string $period = 'month'): float
    {
        try {
            if (!class_exists(Order::class)) {
                return 0.0;
            }

            $query = Order::where('status', 'completed');
            
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
            }
            
            return $query->sum('total_amount') ?? 0.0;
        } catch (\Exception $e) {
            return 0.0;
        }
    }

    /**
     * Dashboard verilerini JSON olarak döndür (AJAX için)
     */
    public function getData(Request $request)
    {
        try {
            $type = $request->get('type', 'stats');
            
            switch ($type) {
                case 'stats':
                    return response()->json($this->getDashboardStats());
                    
                case 'chart':
                    return response()->json($this->getChartData());
                    
                case 'recent':
                    return response()->json([
                        'users' => $this->getRecentUsers(),
                        'posts' => $this->getRecentPosts(),
                        'topics' => $this->getRecentTopics(),
                    ]);
                    
                default:
                    return response()->json(['error' => 'Invalid type'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }
}