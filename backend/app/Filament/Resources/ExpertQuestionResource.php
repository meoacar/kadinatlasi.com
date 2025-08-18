<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpertQuestionResource\Pages;
use App\Models\ExpertQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExpertQuestionResource extends Resource
{
    protected static ?string $model = ExpertQuestion::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Uzman Soruları';
    protected static ?string $modelLabel = 'Uzman Sorusu';
    protected static ?string $pluralModelLabel = 'Uzman Soruları';
    protected static ?string $navigationGroup = 'İçerik Yönetimi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Soru Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('question')
                            ->label('Soru')
                            ->required()
                            ->rows(4),
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required(),
                        Forms\Components\Select::make('user_id')
                            ->label('Soru Soran')
                            ->relationship('user', 'name')
                            ->required(),
                        Forms\Components\Toggle::make('is_public')
                            ->label('Herkese Açık'),
                    ])->columns(2),

                Forms\Components\Section::make('Cevap Bilgileri')
                    ->schema([
                        Forms\Components\Textarea::make('answer')
                            ->label('Cevap')
                            ->rows(4),
                        Forms\Components\Select::make('expert_id')
                            ->label('Cevaplayan Uzman')
                            ->relationship('expert', 'name'),
                        Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->options([
                                'pending' => 'Bekliyor',
                                'answered' => 'Cevaplandı',
                                'closed' => 'Kapatıldı',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Moderasyon')
                    ->schema([
                        Forms\Components\Toggle::make('is_approved')
                            ->label('Onaylandı')
                            ->default(true),
                        Forms\Components\Textarea::make('moderation_notes')
                            ->label('Moderasyon Notları')
                            ->rows(3),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Soru Soran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('expert.name')
                    ->label('Uzman')
                    ->searchable()
                    ->placeholder('Henüz atanmadı'),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Durum')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'answered',
                        'danger' => 'closed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Bekliyor',
                        'answered' => 'Cevaplandı',
                        'closed' => 'Kapatıldı',
                    }),
                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Onaylandı')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_public')
                    ->label('Herkese Açık')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Bekliyor',
                        'answered' => 'Cevaplandı',
                        'closed' => 'Kapatıldı',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Onay Durumu'),
                Tables\Filters\TernaryFilter::make('is_public')
                    ->label('Herkese Açık'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('moderate')
                    ->label('Modera')
                    ->icon('heroicon-o-shield-check')
                    ->color('warning')
                    ->form([
                        Forms\Components\Toggle::make('is_approved')
                            ->label('Onaylandı')
                            ->required(),
                        Forms\Components\Textarea::make('moderation_notes')
                            ->label('Moderasyon Notları')
                            ->rows(3),
                    ])
                    ->action(function (ExpertQuestion $record, array $data): void {
                        $record->update([
                            'is_approved' => $data['is_approved'],
                            'moderation_notes' => $data['moderation_notes'],
                            'moderated_at' => now(),
                            'moderated_by' => auth()->id(),
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Onayla')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update([
                                    'is_approved' => true,
                                    'moderated_at' => now(),
                                    'moderated_by' => auth()->id(),
                                ]);
                            });
                        }),
                    Tables\Actions\BulkAction::make('reject')
                        ->label('Reddet')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update([
                                    'is_approved' => false,
                                    'moderated_at' => now(),
                                    'moderated_by' => auth()->id(),
                                ]);
                            });
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpertQuestions::route('/'),
            'create' => Pages\CreateExpertQuestion::route('/create'),
            'view' => Pages\ViewExpertQuestion::route('/{record}'),
            'edit' => Pages\EditExpertQuestion::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_approved', false)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getNavigationBadge() > 0 ? 'warning' : 'primary';
    }
}