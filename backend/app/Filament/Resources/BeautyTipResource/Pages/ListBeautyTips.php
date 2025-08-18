<?php

namespace App\Filament\Resources\BeautyTipResource\Pages;

use App\Filament\Resources\BeautyTipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBeautyTips extends ListRecords
{
    protected static string $resource = BeautyTipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}