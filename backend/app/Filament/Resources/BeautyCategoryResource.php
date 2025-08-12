<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeautyCategoryResource\Pages;
use App\Models\BeautyCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BeautyCategoryResource extends Resource
{
    protected static ?string $model = BeautyCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Kategoriler';
    protected static ?string $navigationGroup = 'Güzellik & Moda';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Kategori Adı')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $context, $state, Forms\Set $set) => 
                        $context === 'create' ? $set('slug', Str::slug($state)) : null),
                    
                Forms\Components\TextInput::make('slug')
                    ->label('URL Slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                    
                Forms\Components\Textarea::make('description')
                    ->label('Açıklama')
                    ->rows(3),
                    
                Forms\Components\TextInput::make('icon')
                    ->label('İkon (Emoji)')
                    ->maxLength(10)
                    ->default('✨'),
                    
                Forms\Components\ColorPicker::make('color')
                    ->label('Ana Renk')
                    ->default('#ec4899'),
                    
                Forms\Components\TextInput::make('background_gradient')
                    ->label('Arka Plan Gradient')
                    ->placeholder('linear-gradient(135deg, #fce7f3, #fdf2f8)')
                    ->default('linear-gradient(135deg, #fce7f3, #fdf2f8)'),
                    
                Forms\Components\TextInput::make('order')
                    ->label('Sıralama')
                    ->numeric()
                    ->default(0),
                    
                Forms\Components\TextInput::make('meta_title')
                    ->label('SEO Başlık')
                    ->maxLength(60),
                    
                Forms\Components\Textarea::make('meta_description')
                    ->label('SEO Açıklama')
                    ->maxLength(160),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('#')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('articles_count')
                    ->label('Makale')
                    ->counts('articles'),
                    
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Ürün')
                    ->counts('products'),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBeautyCategories::route('/'),
            'create' => Pages\CreateBeautyCategory::route('/create'),
            'edit' => Pages\EditBeautyCategory::route('/{record}/edit'),
        ];
    }
}