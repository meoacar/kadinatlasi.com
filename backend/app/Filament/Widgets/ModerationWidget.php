<?php

namespace App\Filament\Widgets;

use App\Models\ForumTopic;
use App\Models\BlogPost;
use App\Models\BlogComment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ModerationWidget extends BaseWidget
{
    protected static ?string $heading = 'Moderasyon Bekleyen İçerikler';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ForumTopic::query()
                    ->where('is_approved', false)
                    ->with(['user', 'category'])
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Yazar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Onayla')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (ForumTopic $record) {
                        $record->update([
                            'is_approved' => true,
                            'moderator_id' => auth()->id(),
                            'moderated_at' => now(),
                        ]);
                    }),
                Tables\Actions\Action::make('reject')
                    ->label('Reddet')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(function (ForumTopic $record) {
                        $record->delete();
                    })
                    ->requiresConfirmation(),
            ]);
    }
}