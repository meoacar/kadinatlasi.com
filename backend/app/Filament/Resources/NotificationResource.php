<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Models\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationLabel = 'Bildirimler';
    protected static ?string $modelLabel = 'Bildirim';
    protected static ?string $pluralModelLabel = 'Bildirimler';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('type')
                    ->options([
                        'forum_reply' => 'Forum Yanıtı',
                        'blog_comment' => 'Blog Yorumu',
                        'menstrual_reminder' => 'Regl Hatırlatması',
                        'water_reminder' => 'Su Hatırlatması',
                        'exercise_reminder' => 'Egzersiz Hatırlatması',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->rows(3),
                Forms\Components\KeyValue::make('data')
                    ->label('Ek Veriler'),
                Forms\Components\Toggle::make('is_push')
                    ->label('Push Bildirimi Gönderildi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tür')
                    ->colors([
                        'primary' => 'forum_reply',
                        'success' => 'blog_comment',
                        'warning' => 'menstrual_reminder',
                        'info' => 'water_reminder',
                        'danger' => 'exercise_reminder',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'forum_reply' => 'Forum Yanıtı',
                        'blog_comment' => 'Blog Yorumu',
                        'menstrual_reminder' => 'Regl Hatırlatması',
                        'water_reminder' => 'Su Hatırlatması',
                        'exercise_reminder' => 'Egzersiz Hatırlatması',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\IconColumn::make('read_at')
                    ->label('Okundu')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn ($record) => !is_null($record->read_at)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tür')
                    ->options([
                        'forum_reply' => 'Forum Yanıtı',
                        'blog_comment' => 'Blog Yorumu',
                        'menstrual_reminder' => 'Regl Hatırlatması',
                        'water_reminder' => 'Su Hatırlatması',
                        'exercise_reminder' => 'Egzersiz Hatırlatması',
                    ]),
                Tables\Filters\Filter::make('unread')
                    ->label('Okunmamış')
                    ->query(fn ($query) => $query->whereNull('read_at')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'view' => Pages\ViewNotification::route('/{record}'),
            'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}