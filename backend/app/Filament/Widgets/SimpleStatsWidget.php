<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\BlogPost;
use App\Models\Category;

class SimpleStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        try {
            $userCount = User::count();
            $blogCount = BlogPost::count();
            $categoryCount = Category::count();
        } catch (\Exception $e) {
            $userCount = 0;
            $blogCount = 0;
            $categoryCount = 0;
        }

        return [
            Stat::make('Toplam Kullanıcı', $userCount)
                ->description('Kayıtlı kullanıcı sayısı')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            
            Stat::make('Blog Yazıları', $blogCount)
                ->description('Yayınlanan blog yazısı')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
            
            Stat::make('Kategoriler', $categoryCount)
                ->description('Aktif kategori sayısı')
                ->descriptionIcon('heroicon-m-tag')
                ->color('warning'),
        ];
    }
}