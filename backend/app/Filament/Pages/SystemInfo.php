<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class SystemInfo extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $navigationLabel = 'Sistem Bilgileri';
    protected static ?string $title = 'Sistem Bilgileri';
    protected static ?string $navigationGroup = 'Sistem Yönetimi';
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.system-info';

    public function getSystemInfo(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'database_version' => $this->getDatabaseVersion(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'disk_free_space' => $this->formatBytes(disk_free_space('/')),
            'disk_total_space' => $this->formatBytes(disk_total_space('/')),
            'memory_usage' => $this->formatBytes(memory_get_usage(true)),
            'memory_peak_usage' => $this->formatBytes(memory_get_peak_usage(true)),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug') ? 'Açık' : 'Kapalı',
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            'mail_driver' => config('mail.default'),
        ];
    }

    private function getDatabaseVersion(): string
    {
        try {
            return \DB::select('SELECT version() as version')[0]->version ?? 'N/A';
        } catch (\Exception $e) {
            return 'N/A';
        }
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