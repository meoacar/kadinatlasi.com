<?php

namespace App\Filament\Resources\PremiumSubscriptionResource\Pages;

use App\Filament\Resources\PremiumSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPremiumSubscriptions extends ListRecords
{
    protected static string $resource = PremiumSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}