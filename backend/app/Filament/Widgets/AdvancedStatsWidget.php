<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\BlogPost;
use App\Models\ForumTopic;
use App\Models\Notification;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdvancedStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Toplam Kullanıcı', User::count())
                ->description('Kayıtlı kullanıcı sayısı')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Aktif Kullanıcılar', User::where('created_at', '>=', now()->subDays(30))->count())
                ->description('Son 30 günde kayıt olan')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('primary'),
                
            Stat::make('Blog Yazıları', BlogPost::where('is_published', true)->count())
                ->description('Yayınlanan yazı sayısı')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
                
            Stat::make('Forum Konuları', ForumTopic::where('is_approved', true)->count())
                ->description('Onaylanmış konu sayısı')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('warning'),
                
            Stat::make('Okunmamış Bildirimler', Notification::whereNull('read_at')->count())
                ->description('Sistem geneli okunmamış')
                ->descriptionIcon('heroicon-m-bell')
                ->color('danger'),
                
            Stat::make('Günlük Bildirimler', Notification::whereDate('created_at', today())->count())
                ->description('Bugün gönderilen bildirimler')
                ->descriptionIcon('heroicon-m-bell-alert')
                ->color('gray'),
        ];
    }
}