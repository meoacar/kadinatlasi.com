<?php

namespace App\Filament\Resources\UserOutfitResource\Pages;

use App\Filament\Resources\UserOutfitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserOutfits extends ListRecords
{
    protected static string $resource = UserOutfitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}