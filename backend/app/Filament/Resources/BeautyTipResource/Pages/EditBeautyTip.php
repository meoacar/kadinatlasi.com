<?php

namespace App\Filament\Resources\BeautyTipResource\Pages;

use App\Filament\Resources\BeautyTipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBeautyTip extends EditRecord
{
    protected static string $resource = BeautyTipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}