<?php

namespace App\Filament\Resources\PremiumPlanResource\Pages;

use App\Filament\Resources\PremiumPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPremiumPlan extends EditRecord
{
    protected static string $resource = PremiumPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}