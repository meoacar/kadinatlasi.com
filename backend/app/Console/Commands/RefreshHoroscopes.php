<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AstrologyApiService;

class RefreshHoroscopes extends Command
{
    protected $signature = 'horoscope:refresh {--type=daily} {--category=general}';
    protected $description = 'Refresh horoscope data from API';

    public function handle()
    {
        $type = $this->option('type');
        $category = $this->option('category');
        
        $this->info("Refreshing {$type} horoscopes for {$category} category...");
        
        $astrologyService = app(AstrologyApiService::class);
        $horoscopes = $astrologyService->fetchAllSigns($type, $category);
        
        $this->info('Refreshed ' . count($horoscopes) . ' horoscopes successfully!');
        
        return 0;
    }
}