<?php

namespace App\Filament\Resources\BeautyProductResource\Pages;

use App\Filament\Resources\BeautyProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBeautyProducts extends ListRecords
{
    protected static string $resource = BeautyProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}