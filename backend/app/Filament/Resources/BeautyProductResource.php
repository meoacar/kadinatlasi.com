<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeautyProductResource\Pages;
use App\Models\BeautyProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BeautyProductResource extends Resource
{
    protected static ?string $model = BeautyProduct::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Ürün İncelemeleri';
    protected static ?string $navigationGroup = 'Güzellik & Moda';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Ürün Adı')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('description')
                    ->label('Açıklama')
                    ->required()
                    ->maxLength(1000),
                    
                Forms\Components\TextInput::make('brand')
                    ->label('Marka')
                    ->maxLength(100),
                    
                Forms\Components\TextInput::make('icon')
                    ->label('İkon (Emoji)')
                    ->maxLength(10)
                    ->default('🧴'),
                    
                Forms\Components\ColorPicker::make('color')
                    ->label('Renk')
                    ->default('#fce7f3'),
                    
                Forms\Components\TextInput::make('rating')
                    ->label('Puan')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->step(0.1)
                    ->default(4.5),
                    
                Forms\Components\TextInput::make('price')
                    ->label('Fiyat (₺)')
                    ->numeric()
                    ->prefix('₺'),
                    
                Forms\Components\Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'cleanser' => 'Temizleyici',
                        'moisturizer' => 'Nemlendirici',
                        'sunscreen' => 'Güneş Kremi',
                        'serum' => 'Serum',
                        'makeup' => 'Makyaj',
                        'haircare' => 'Saç Bakımı'
                    ])
                    ->required(),
                    
                Forms\Components\TagsInput::make('ingredients')
                    ->label('İçerikler'),
                    
                Forms\Components\TagsInput::make('pros')
                    ->label('Artıları'),
                    
                Forms\Components\TagsInput::make('cons')
                    ->label('Eksileri'),
                    
                Forms\Components\FileUpload::make('featured_image')
                    ->label('Ürün Görseli')
                    ->image()
                    ->directory('beauty-products'),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Görsel')
                    ->circular(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Ürün Adı')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('brand')
                    ->label('Marka')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge(),
                    
                Tables\Columns\TextColumn::make('rating')
                    ->label('Puan')
                    ->suffix('/5')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('price')
                    ->label('Fiyat')
                    ->money('TRY')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'cleanser' => 'Temizleyici',
                        'moisturizer' => 'Nemlendirici',
                        'sunscreen' => 'Güneş Kremi',
                        'makeup' => 'Makyaj',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBeautyProducts::route('/'),
            'create' => Pages\CreateBeautyProduct::route('/create'),
            'edit' => Pages\EditBeautyProduct::route('/{record}/edit'),
        ];
    }
}