<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\BlogPost;
use App\Models\ForumTopic;
use App\Models\Notification;
use Filament\Widgets\ChartWidget;

class PerformanceWidget extends ChartWidget
{
    protected static ?string $heading = 'Aylık Performans';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $months = collect(range(1, 12))->map(function ($month) {
            return now()->month($month)->format('M');
        });

        $userData = collect(range(1, 12))->map(function ($month) {
            return User::whereMonth('created_at', $month)
                      ->whereYear('created_at', now()->year)
                      ->count();
        });

        $blogData = collect(range(1, 12))->map(function ($month) {
            return BlogPost::whereMonth('created_at', $month)
                          ->whereYear('created_at', now()->year)
                          ->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Yeni Kullanıcılar',
                    'data' => $userData->toArray(),
                    'borderColor' => '#E57399',
                    'backgroundColor' => 'rgba(229, 115, 153, 0.1)',
                ],
                [
                    'label' => 'Blog Yazıları',
                    'data' => $blogData->toArray(),
                    'borderColor' => '#F5A9BC',
                    'backgroundColor' => 'rgba(245, 169, 188, 0.1)',
                ],
            ],
            'labels' => $months->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}