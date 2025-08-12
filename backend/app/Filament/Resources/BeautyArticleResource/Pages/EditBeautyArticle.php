<?php

namespace App\Filament\Resources\BeautyArticleResource\Pages;

use App\Filament\Resources\BeautyArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBeautyArticle extends EditRecord
{
    protected static string $resource = BeautyArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}