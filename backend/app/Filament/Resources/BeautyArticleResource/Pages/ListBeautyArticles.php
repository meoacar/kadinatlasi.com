<?php

namespace App\Filament\Resources\BeautyArticleResource\Pages;

use App\Filament\Resources\BeautyArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBeautyArticles extends ListRecords
{
    protected static string $resource = BeautyArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}