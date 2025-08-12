<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Etkinlikler';
    protected static ?string $modelLabel = 'Etkinlik';
    protected static ?string $pluralModelLabel = 'Etkinlikler';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Başlık')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Açıklama')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('type')
                    ->label('Tür')
                    ->options([
                        'workshop' => 'Atölye',
                        'seminar' => 'Seminer',
                        'webinar' => 'Webinar',
                        'meetup' => 'Buluşma'
                    ])
                    ->required()
                    ->default('workshop'),
                Forms\Components\DateTimePicker::make('start_date')
                    ->label('Başlangıç Tarihi')
                    ->required(),
                Forms\Components\DateTimePicker::make('end_date')
                    ->label('Bitiş Tarihi')
                    ->required(),
                Forms\Components\TextInput::make('location')
                    ->label('Konum')
                    ->maxLength(255),
                Forms\Components\TextInput::make('online_link')
                    ->label('Online Link')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('max_participants')
                    ->label('Maksimum Katılımcı')
                    ->numeric()
                    ->minValue(1),
                Forms\Components\TextInput::make('price')
                    ->label('Fiyat (₺)')
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Forms\Components\Toggle::make('is_free')
                    ->label('Ücretsiz')
                    ->default(true),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
                Forms\Components\TagsInput::make('tags')
                    ->label('Etiketler')
                    ->placeholder('Etiket ekleyin...')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tür')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'workshop' => 'Atölye',
                        'seminar' => 'Seminer', 
                        'webinar' => 'Webinar',
                        'meetup' => 'Buluşma',
                        default => $state
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'workshop' => 'primary',
                        'seminar' => 'success',
                        'webinar' => 'warning',
                        'meetup' => 'info',
                        default => 'gray'
                    }),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Başlangıç')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Konum')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('participants_count')
                    ->label('Katılımcı')
                    ->counts('attendees')
                    ->badge(),
                Tables\Columns\TextColumn::make('max_participants')
                    ->label('Kapasite')
                    ->numeric(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Fiyat')
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_free')
                    ->label('Ücretsiz')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tür')
                    ->options([
                        'workshop' => 'Atölye',
                        'seminar' => 'Seminer',
                        'webinar' => 'Webinar', 
                        'meetup' => 'Buluşma'
                    ]),
                Tables\Filters\TernaryFilter::make('is_free')
                    ->label('Ücretsiz'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}