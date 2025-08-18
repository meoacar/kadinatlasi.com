<?php

namespace App\Filament\Resources\UserOutfitResource\Pages;

use App\Filament\Resources\UserOutfitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserOutfit extends EditRecord
{
    protected static string $resource = UserOutfitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}