<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SitemapController;
use App\Filament\Pages\SiteSettingsPage;

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/robots.txt', [SitemapController::class, 'robots']);

// Admin Routes
Route::post('/admin/site-settings/save', [SiteSettingsPage::class, 'save'])
    ->middleware(['web', 'auth:web'])
    ->name('filament.admin.pages.site-settings.save');

// Ana sayfa ve SPA routing
Route::get('/', function () {
    return file_get_contents(public_path('index.html'));
});

// SPA route'ları için fallback (API route'ları hariç)
Route::fallback(function () {
    // API route'ları için 404 döndür
    if (request()->is('api/*')) {
        return response()->json(['error' => 'Route not found'], 404);
    }
    
    // Diğer tüm route'lar için Vue.js uygulamasını döndür
    return file_get_contents(public_path('index.html'));
});
