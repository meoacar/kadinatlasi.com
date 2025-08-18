<?php

namespace App\Filament\Resources\PremiumSubscriptionResource\Pages;

use App\Filament\Resources\PremiumSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPremiumSubscription extends EditRecord
{
    protected static string $resource = PremiumSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}