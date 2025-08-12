<?php

namespace App\Filament\Widgets;

use App\Models\Advertisement;
use App\Models\PremiumSubscription;
use App\Models\Course;
use App\Models\Order;
use App\Models\Partnership;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $adsRevenue = Advertisement::where('is_active', true)->sum('price');
        $premiumRevenue = PremiumSubscription::where('status', 'active')->sum('price');
        $courseRevenue = Course::sum('price');
        $ecommerceRevenue = Order::where('status', 'completed')->sum('total_amount');
        $partnershipRevenue = Partnership::where('status', 'active')->sum('total_revenue');
        
        $totalRevenue = $adsRevenue + $premiumRevenue + $courseRevenue + $ecommerceRevenue + $partnershipRevenue;

        return [
            Stat::make('Toplam Gelir', '₺' . number_format($totalRevenue, 2))
                ->description('Tüm gelir kaynakları')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            
            Stat::make('Reklam Gelirleri', '₺' . number_format($adsRevenue, 2))
                ->description('Aktif reklamlar')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('info'),
            
            Stat::make('Premium Üyelikler', '₺' . number_format($premiumRevenue, 2))
                ->description('Aktif üyelikler')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
            
            Stat::make('Kurs Satışları', '₺' . number_format($courseRevenue, 2))
                ->description('Toplam kurs gelirleri')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),
            
            Stat::make('E-Ticaret', '₺' . number_format($ecommerceRevenue, 2))
                ->description('Tamamlanan siparişler')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success'),
            
            Stat::make('İşbirlikleri', '₺' . number_format($partnershipRevenue, 2))
                ->description('Aktif ortaklıklar')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray'),
        ];
    }
}