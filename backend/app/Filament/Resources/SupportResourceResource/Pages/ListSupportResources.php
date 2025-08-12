<?php

namespace App\Filament\Resources\SupportResourceResource\Pages;

use App\Filament\Resources\SupportResourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupportResources extends ListRecords
{
    protected static string $resource = SupportResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}