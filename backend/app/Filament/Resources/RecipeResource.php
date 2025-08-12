<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecipeResource\Pages;
use App\Models\Recipe;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RecipeResource extends Resource
{
    protected static ?string $model = Recipe::class;
    protected static ?string $navigationIcon = 'heroicon-o-cake';
    protected static ?string $navigationLabel = 'Tarifler';
    protected static ?string $modelLabel = 'Tarif';
    protected static ?string $pluralModelLabel = 'Tarifler';
    protected static ?string $navigationGroup = 'Fitness & Diyet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Tarif Adı')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('description')
                    ->label('Açıklama')
                    ->required()
                    ->rows(3),
                    
                Forms\Components\Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'Kahvaltı' => 'Kahvaltı',
                        'Öğle Yemeği' => 'Öğle Yemeği',
                        'Akşam Yemeği' => 'Akşam Yemeği',
                        'Ara Öğün' => 'Ara Öğün',
                        'Tatlı' => 'Tatlı',
                    ])
                    ->required(),
                    
                Forms\Components\TextInput::make('prep_time')
                    ->label('Hazırlık Süresi (dk)')
                    ->numeric()
                    ->required(),
                    
                Forms\Components\TextInput::make('cook_time')
                    ->label('Pişirme Süresi (dk)')
                    ->numeric()
                    ->required(),
                    
                Forms\Components\TextInput::make('servings')
                    ->label('Porsiyon Sayısı')
                    ->numeric()
                    ->required(),
                    
                Forms\Components\TextInput::make('calories_per_serving')
                    ->label('Porsiyon Başı Kalori')
                    ->numeric()
                    ->required(),
                    
                Forms\Components\Repeater::make('ingredients')
                    ->label('Malzemeler')
                    ->schema([
                        Forms\Components\TextInput::make('item')
                            ->label('Malzeme')
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Miktar')
                            ->required(),
                    ])
                    ->columns(2)
                    ->required(),
                    
                Forms\Components\Repeater::make('instructions')
                    ->label('Yapılış')
                    ->schema([
                        Forms\Components\Textarea::make('step')
                            ->label('Adım')
                            ->required()
                            ->rows(2),
                    ])
                    ->required(),
                    
                Forms\Components\KeyValue::make('nutrition_info')
                    ->label('Besin Değerleri')
                    ->keyLabel('Besin')
                    ->valueLabel('Miktar'),
                    
                Forms\Components\CheckboxList::make('diet_type')
                    ->label('Diyet Tipi')
                    ->options([
                        'vegan' => 'Vegan',
                        'vegetarian' => 'Vejetaryen',
                        'gluten-free' => 'Glutensiz',
                        'dairy-free' => 'Sütsüz',
                        'low-carb' => 'Düşük Karbonhidrat',
                        'keto' => 'Ketojenik',
                        'paleo' => 'Paleo',
                    ])
                    ->columns(3),
                    
                Forms\Components\FileUpload::make('image_url')
                    ->label('Görsel')
                    ->image()
                    ->directory('recipes'),
                    
                Forms\Components\Select::make('difficulty')
                    ->label('Zorluk Seviyesi')
                    ->options([
                        'kolay' => 'Kolay',
                        'orta' => 'Orta',
                        'zor' => 'Zor',
                    ])
                    ->required(),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Görsel')
                    ->circular(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Tarif Adı')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('category')
                    ->label('Kategori')
                    ->colors([
                        'warning' => 'Kahvaltı',
                        'success' => 'Öğle Yemeği',
                        'primary' => 'Akşam Yemeği',
                        'info' => 'Ara Öğün',
                        'danger' => 'Tatlı',
                    ]),
                    
                Tables\Columns\TextColumn::make('prep_time')
                    ->label('Hazırlık')
                    ->suffix(' dk')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('servings')
                    ->label('Porsiyon')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('calories_per_serving')
                    ->label('Kalori')
                    ->suffix(' kcal')
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('difficulty')
                    ->label('Zorluk')
                    ->colors([
                        'success' => 'kolay',
                        'warning' => 'orta',
                        'danger' => 'zor',
                    ]),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Durum')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'Kahvaltı' => 'Kahvaltı',
                        'Öğle Yemeği' => 'Öğle Yemeği',
                        'Akşam Yemeği' => 'Akşam Yemeği',
                        'Ara Öğün' => 'Ara Öğün',
                        'Tatlı' => 'Tatlı',
                    ]),
                    
                Tables\Filters\SelectFilter::make('difficulty')
                    ->label('Zorluk')
                    ->options([
                        'kolay' => 'Kolay',
                        'orta' => 'Orta',
                        'zor' => 'Zor',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Durum')
                    ->trueLabel('Aktif')
                    ->falseLabel('Pasif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecipes::route('/'),
            'create' => Pages\CreateRecipe::route('/create'),
            'edit' => Pages\EditRecipe::route('/{record}/edit'),
        ];
    }
}