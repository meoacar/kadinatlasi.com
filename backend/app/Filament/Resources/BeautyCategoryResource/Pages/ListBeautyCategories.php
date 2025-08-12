<?php

namespace App\Filament\Resources\BeautyCategoryResource\Pages;

use App\Filament\Resources\BeautyCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBeautyCategories extends ListRecords
{
    protected static string $resource = BeautyCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}