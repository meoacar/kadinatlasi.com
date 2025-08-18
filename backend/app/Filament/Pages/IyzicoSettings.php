<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class IyzicoSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'İyzico Ayarları';
    protected static ?string $title = 'İyzico Ödeme Ayarları';
    protected static ?string $navigationGroup = 'Premium Üyelik';
    protected static ?int $navigationSort = 7;

    protected static string $view = 'filament.pages.iyzico-settings';
}