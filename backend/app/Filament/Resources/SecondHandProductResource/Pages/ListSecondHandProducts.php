<?php

namespace App\Filament\Resources\SecondHandProductResource\Pages;

use App\Filament\Resources\SecondHandProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSecondHandProducts extends ListRecords
{
    protected static string $resource = SecondHandProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}