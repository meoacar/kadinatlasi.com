<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\BlogPost;
use App\Models\SystemLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemPerformanceWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        return [
            Stat::make('Toplam Kullanıcı', User::count())
                ->description('Kayıtlı kullanıcı sayısı')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Aktif Kullanıcı', User::where('last_login_at', '>=', now()->subDays(7))->count())
                ->description('Son 7 gündeki aktif kullanıcı')
                ->descriptionIcon('heroicon-m-user-circle')
                ->color('info'),

            Stat::make('Blog Yazıları', BlogPost::where('status', 'published')->count())
                ->description('Yayınlanan blog yazıları')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),

            Stat::make('Sistem Hataları', SystemLog::where('level', 'error')->whereDate('created_at', today())->count())
                ->description('Bugünkü hata sayısı')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Disk Kullanımı', $this->getDiskUsage())
                ->description('Toplam disk kullanımı')
                ->descriptionIcon('heroicon-m-server')
                ->color('primary'),

            Stat::make('Bellek Kullanımı', $this->getMemoryUsage())
                ->description('Mevcut bellek kullanımı')
                ->descriptionIcon('heroicon-m-cpu-chip')
                ->color('secondary'),
        ];
    }

    private function getDiskUsage(): string
    {
        $bytes = disk_total_space('/') - disk_free_space('/');
        $total = disk_total_space('/');
        
        $percentage = round(($bytes / $total) * 100, 1);
        
        return $this->formatBytes($bytes) . ' (' . $percentage . '%)';
    }

    private function getMemoryUsage(): string
    {
        $memory = memory_get_usage(true);
        $peak = memory_get_peak_usage(true);
        
        return $this->formatBytes($memory) . ' / ' . $this->formatBytes($peak);
    }

    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}