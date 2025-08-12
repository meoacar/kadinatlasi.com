<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SecondHandProductResource\Pages;
use App\Models\SecondHandProduct;
use App\Models\ProductCategory;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class SecondHandProductResource extends Resource
{
    protected static ?string $model = SecondHandProduct::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationLabel = 'İkinci El Ürünler';
    protected static ?string $modelLabel = 'İkinci El Ürün';
    protected static ?string $pluralModelLabel = 'İkinci El Ürünler';
    protected static ?string $navigationGroup = 'İkinci El Pazar';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ürün Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Ürün Başlığı')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $context, $state, callable $set) => [
                                $context === 'create' ? $set('name', $state) : null,
                                $context === 'create' ? $set('slug', Str::slug($state)) : null
                            ]),

                        Forms\Components\TextInput::make('name')
                            ->label('Ürün Adı')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->label('URL Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(SecondHandProduct::class, 'slug', ignoreRecord: true),

                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->options(ProductCategory::active()->pluck('name', 'id'))
                            ->searchable(),

                        Forms\Components\Select::make('seller_id')
                            ->label('Satıcı')
                            ->options(User::pluck('name', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->required()
                            ->rows(4),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Fiyat & Durum')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('Satış Fiyatı')
                            ->numeric()
                            ->prefix('₺')
                            ->required(),

                        Forms\Components\TextInput::make('original_price')
                            ->label('Orijinal Fiyat')
                            ->numeric()
                            ->prefix('₺'),

                        Forms\Components\Select::make('condition')
                            ->label('Ürün Durumu')
                            ->options([
                                'excellent' => 'Mükemmel',
                                'good' => 'İyi',
                                'fair' => 'Orta',
                                'poor' => 'Kötü'
                            ])
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->options([
                                'pending' => 'Onay Bekliyor',
                                'active' => 'Aktif',
                                'sold' => 'Satıldı',
                                'rejected' => 'Reddedildi'
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Öne Çıkan'),

                        Forms\Components\TextInput::make('location')
                            ->label('Konum')
                            ->maxLength(255),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Resimler')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->label('Ürün Resimleri')
                            ->multiple()
                            ->image()
                            ->maxFiles(8)
                            ->directory('second-hand')
                            ->visibility('public')
                            ->reorderable(),
                    ]),

                Forms\Components\Section::make('İletişim Bilgileri')
                    ->schema([
                        Forms\Components\KeyValue::make('contact_info')
                            ->label('İletişim Bilgileri')
                            ->keyLabel('Bilgi Türü')
                            ->valueLabel('Değer')
                            ->addActionLabel('Bilgi Ekle'),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('images')
                    ->label('Resimler')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state) && count($state) > 0) {
                            return count($state) . ' resim';
                        }
                        return 'Resim yok';
                    })
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Ürün Başlığı')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Satıcı')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Fiyat')
                    ->money('TRY')
                    ->sortable(),

                Tables\Columns\TextColumn::make('original_price')
                    ->label('Orijinal Fiyat')
                    ->money('TRY')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('condition')
                    ->label('Durum')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'excellent' => 'success',
                        'good' => 'info',
                        'fair' => 'warning',
                        'poor' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'excellent' => 'Mükemmel',
                        'good' => 'İyi',
                        'fair' => 'Orta',
                        'poor' => 'Kötü',
                        default => $state,
                    }),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Onay Bekliyor',
                        'active' => 'Aktif',
                        'sold' => 'Satıldı',
                        'rejected' => 'Reddedildi'
                    ])
                    ->selectablePlaceholder(false),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Öne Çıkan')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('location')
                    ->label('Konum')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->options(ProductCategory::active()->pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('seller_id')
                    ->label('Satıcı')
                    ->options(User::pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('condition')
                    ->label('Ürün Durumu')
                    ->options([
                        'excellent' => 'Mükemmel',
                        'good' => 'İyi',
                        'fair' => 'Orta',
                        'poor' => 'Kötü'
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'active' => 'Aktif',
                        'sold' => 'Satıldı',
                        'inactive' => 'Pasif'
                    ]),

                Tables\Filters\Filter::make('is_featured')
                    ->label('Öne Çıkan')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSecondHandProducts::route('/'),
            'create' => Pages\CreateSecondHandProduct::route('/create'),
            'view' => Pages\ViewSecondHandProduct::route('/{record}'),
            'edit' => Pages\EditSecondHandProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}