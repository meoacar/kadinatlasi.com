<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\BlogPost;
use App\Models\ForumTopic;
use App\Models\ForumGroup;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class UserStatsWidget extends ChartWidget
{
    protected static ?string $heading = 'Kullanıcı İstatistikleri (Son 7 Gün)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(function ($day) {
            return Carbon::now()->subDays($day)->format('Y-m-d');
        });

        $userCounts = $days->map(function ($date) {
            return User::whereDate('created_at', $date)->count();
        });

        $postCounts = $days->map(function ($date) {
            return BlogPost::whereDate('created_at', $date)->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Yeni Kullanıcılar',
                    'data' => $userCounts->toArray(),
                    'borderColor' => '#E57399',
                    'backgroundColor' => 'rgba(229, 115, 153, 0.1)',
                ],
                [
                    'label' => 'Yeni Blog Yazıları',
                    'data' => $postCounts->toArray(),
                    'borderColor' => '#F5A9BC',
                    'backgroundColor' => 'rgba(245, 169, 188, 0.1)',
                ],
            ],
            'labels' => $days->map(fn($date) => Carbon::parse($date)->format('d.m'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}