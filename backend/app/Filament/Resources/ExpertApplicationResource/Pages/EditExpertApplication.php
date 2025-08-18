<?php

namespace App\Filament\Resources\ExpertApplicationResource\Pages;

use App\Filament\Resources\ExpertApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpertApplication extends EditRecord
{
    protected static string $resource = ExpertApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}