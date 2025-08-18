<?php

namespace App\Filament\Resources\SecondHandProductResource\Pages;

use App\Filament\Resources\SecondHandProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSecondHandProduct extends EditRecord
{
    protected static string $resource = SecondHandProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}