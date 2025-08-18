<?php

namespace App\Filament\Resources\SecondHandCategoryResource\Pages;

use App\Filament\Resources\SecondHandCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSecondHandCategories extends ListRecords
{
    protected static string $resource = SecondHandCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
