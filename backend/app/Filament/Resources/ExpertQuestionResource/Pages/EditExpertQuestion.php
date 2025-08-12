<?php

namespace App\Filament\Resources\ExpertQuestionResource\Pages;

use App\Filament\Resources\ExpertQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpertQuestion extends EditRecord
{
    protected static string $resource = ExpertQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}