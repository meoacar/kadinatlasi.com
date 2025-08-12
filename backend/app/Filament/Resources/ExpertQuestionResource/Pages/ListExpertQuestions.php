<?php

namespace App\Filament\Resources\ExpertQuestionResource\Pages;

use App\Filament\Resources\ExpertQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExpertQuestions extends ListRecords
{
    protected static string $resource = ExpertQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}