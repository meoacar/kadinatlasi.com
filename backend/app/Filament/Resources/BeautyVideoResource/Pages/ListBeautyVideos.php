<?php

namespace App\Filament\Resources\BeautyVideoResource\Pages;

use App\Filament\Resources\BeautyVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBeautyVideos extends ListRecords
{
    protected static string $resource = BeautyVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}