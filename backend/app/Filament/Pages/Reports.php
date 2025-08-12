<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Models\BlogPost;
use App\Models\ForumTopic;
use App\Models\ForumGroup;
use App\Models\Horoscope;
use Filament\Pages\Page;
use Carbon\Carbon;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Raporlar';
    protected static ?string $title = 'Platform Raporları';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationGroup = 'Yönetim';

    protected static string $view = 'filament.pages.reports';

    public function getViewData(): array
    {
        $today = Carbon::today();
        $lastWeek = Carbon::today()->subWeek();
        $lastMonth = Carbon::today()->subMonth();

        return [
            'stats' => [
                'users' => [
                    'total' => User::count(),
                    'today' => User::whereDate('created_at', $today)->count(),
                    'week' => User::where('created_at', '>=', $lastWeek)->count(),
                    'month' => User::where('created_at', '>=', $lastMonth)->count(),
                ],
                'blog_posts' => [
                    'total' => BlogPost::count(),
                    'today' => BlogPost::whereDate('created_at', $today)->count(),
                    'week' => BlogPost::where('created_at', '>=', $lastWeek)->count(),
                    'month' => BlogPost::where('created_at', '>=', $lastMonth)->count(),
                ],
                'forum_topics' => [
                    'total' => ForumTopic::count(),
                    'today' => ForumTopic::whereDate('created_at', $today)->count(),
                    'week' => ForumTopic::where('created_at', '>=', $lastWeek)->count(),
                    'month' => ForumTopic::where('created_at', '>=', $lastMonth)->count(),
                    'pending' => ForumTopic::where('is_approved', false)->count(),
                ],
                'forum_groups' => [
                    'total' => ForumGroup::count(),
                    'active' => ForumGroup::where('member_count', '>', 1)->count(),
                ],
                'horoscopes' => [
                    'total' => Horoscope::count(),
                    'today' => Horoscope::whereDate('date', $today)->count(),
                ],
            ],
            'top_users' => User::withCount(['blogPosts', 'forumTopics'])
                ->orderByDesc('blog_posts_count')
                ->limit(10)
                ->get(),
            'popular_groups' => ForumGroup::orderByDesc('member_count')
                ->limit(5)
                ->get(),
        ];
    }
}