<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class CacheManagement extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationLabel = 'Cache Yönetimi';
    protected static ?string $title = 'Cache Yönetimi';
    protected static ?string $navigationGroup = 'Sistem Yönetimi';
    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.cache-management';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clearCache')
                ->label('Tüm Cache Temizle')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    Artisan::call('cache:clear');
                    Artisan::call('config:clear');
                    Artisan::call('route:clear');
                    Artisan::call('view:clear');
                    
                    Notification::make()
                        ->title('Tüm cache başarıyla temizlendi')
                        ->success()
                        ->send();
                }),

            Action::make('optimizeApp')
                ->label('Uygulamayı Optimize Et')
                ->icon('heroicon-o-rocket-launch')
                ->color('success')
                ->action(function () {
                    Artisan::call('optimize');
                    
                    Notification::make()
                        ->title('Uygulama başarıyla optimize edildi')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function clearApplicationCache()
    {
        Artisan::call('cache:clear');
        
        Notification::make()
            ->title('Uygulama cache temizlendi')
            ->success()
            ->send();
    }

    public function clearConfigCache()
    {
        Artisan::call('config:clear');
        
        Notification::make()
            ->title('Konfigürasyon cache temizlendi')
            ->success()
            ->send();
    }

    public function clearRouteCache()
    {
        Artisan::call('route:clear');
        
        Notification::make()
            ->title('Route cache temizlendi')
            ->success()
            ->send();
    }

    public function clearViewCache()
    {
        Artisan::call('view:clear');
        
        Notification::make()
            ->title('View cache temizlendi')
            ->success()
            ->send();
    }

    public function cacheConfig()
    {
        Artisan::call('config:cache');
        
        Notification::make()
            ->title('Konfigürasyon cache oluşturuldu')
            ->success()
            ->send();
    }

    public function cacheRoutes()
    {
        Artisan::call('route:cache');
        
        Notification::make()
            ->title('Route cache oluşturuldu')
            ->success()
            ->send();
    }

    public function cacheViews()
    {
        Artisan::call('view:cache');
        
        Notification::make()
            ->title('View cache oluşturuldu')
            ->success()
            ->send();
    }

    public function getCacheInfo(): array
    {
        return [
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            'cache_prefix' => config('cache.prefix'),
        ];
    }
}