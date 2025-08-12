<?php

namespace App\Filament\Resources\ExpertQuestionResource\Pages;

use App\Filament\Resources\ExpertQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewExpertQuestion extends ViewRecord
{
    protected static string $resource = ExpertQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}