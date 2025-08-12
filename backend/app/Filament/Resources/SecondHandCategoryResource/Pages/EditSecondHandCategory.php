<?php

namespace App\Filament\Resources\SecondHandCategoryResource\Pages;

use App\Filament\Resources\SecondHandCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSecondHandCategory extends EditRecord
{
    protected static string $resource = SecondHandCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
