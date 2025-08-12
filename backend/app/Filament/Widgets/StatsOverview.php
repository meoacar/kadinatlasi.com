<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\BlogPost;
use App\Models\ForumTopic;
use App\Models\BlogComment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Toplam Kullanıcı', User::count())
                ->description('Kayıtlı kullanıcı sayısı')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            
            Stat::make('Blog Yazıları', BlogPost::count())
                ->description('Yayınlanan yazı sayısı')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
            
            Stat::make('Forum Konuları', ForumTopic::count())
                ->description('Açılan konu sayısı')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('warning'),
            
            Stat::make('Yorumlar', BlogComment::count())
                ->description('Toplam yorum sayısı')
                ->descriptionIcon('heroicon-m-chat-bubble-oval-left')
                ->color('primary'),
        ];
    }
}