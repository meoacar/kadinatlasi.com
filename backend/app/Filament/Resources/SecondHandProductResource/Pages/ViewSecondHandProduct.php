<?php

namespace App\Filament\Resources\SecondHandProductResource\Pages;

use App\Filament\Resources\SecondHandProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSecondHandProduct extends ViewRecord
{
    protected static string $resource = SecondHandProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}