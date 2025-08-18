<?php

namespace App\Filament\Resources\SupportResourceResource\Pages;

use App\Filament\Resources\SupportResourceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupportResource extends EditRecord
{
    protected static string $resource = SupportResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}