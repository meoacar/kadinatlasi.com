<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvertisementResource\Pages;
use App\Models\Advertisement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AdvertisementResource extends Resource
{
    protected static ?string $model = Advertisement::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'Reklamlar';
    protected static ?string $modelLabel = 'Reklam';
    protected static ?string $pluralModelLabel = 'Reklamlar';
    protected static ?string $navigationGroup = 'Premium Üyelik';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Reklam Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Başlık')
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->label('Reklam Tipi')
                            ->options([
                                'banner' => 'Banner',
                                'sidebar' => 'Kenar Çubuğu',
                                'popup' => 'Pop-up',
                                'sponsored_content' => 'Sponsorlu İçerik'
                            ])
                            ->required(),
                        Forms\Components\Select::make('position')
                            ->label('Konum')
                            ->options([
                                'header' => 'Üst Kısım',
                                'sidebar' => 'Kenar Çubuğu',
                                'footer' => 'Alt Kısım',
                                'content' => 'İçerik Arası',
                                'popup' => 'Pop-up'
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('content')
                            ->label('İçerik'),
                        Forms\Components\TextInput::make('image_url')
                            ->label('Görsel URL'),
                        Forms\Components\TextInput::make('link_url')
                            ->label('Link URL'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Tarih ve Fiyat')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Başlangıç Tarihi')
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Bitiş Tarihi')
                            ->required(),
                        Forms\Components\TextInput::make('price')
                            ->label('Fiyat (₺)')
                            ->numeric()
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),
                
                Forms\Components\Section::make('Müşteri Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('client_name')
                            ->label('Müşteri Adı')
                            ->required(),
                        Forms\Components\TextInput::make('client_email')
                            ->label('Müşteri E-posta')
                            ->email()
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tip')
                    ->badge(),
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Müşteri')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Fiyat')
                    ->money('TRY'),
                Tables\Columns\TextColumn::make('clicks')
                    ->label('Tıklama')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('impressions')
                    ->label('Gösterim')
                    ->badge()
                    ->color('info'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Başlangıç')
                    ->date(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Bitiş')
                    ->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Reklam Tipi')
                    ->options([
                        'banner' => 'Banner',
                        'sidebar' => 'Kenar Çubuğu',
                        'popup' => 'Pop-up',
                        'sponsored_content' => 'Sponsorlu İçerik'
                    ]),
                Tables\Filters\Filter::make('is_active')
                    ->label('Aktif Reklamlar')
                    ->query(fn ($query) => $query->where('is_active', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdvertisements::route('/'),
            'create' => Pages\CreateAdvertisement::route('/create'),
            'edit' => Pages\EditAdvertisement::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }
}