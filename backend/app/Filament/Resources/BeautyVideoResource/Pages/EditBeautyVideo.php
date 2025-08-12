<?php

namespace App\Filament\Resources\BeautyVideoResource\Pages;

use App\Filament\Resources\BeautyVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBeautyVideo extends EditRecord
{
    protected static string $resource = BeautyVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}