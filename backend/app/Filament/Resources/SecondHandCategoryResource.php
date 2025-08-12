<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SecondHandCategoryResource\Pages;
use App\Models\SecondHandCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SecondHandCategoryResource extends Resource
{
    protected static ?string $model = SecondHandCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Pazar Kategorileri';
    protected static ?string $modelLabel = 'Pazar Kategorisi';
    protected static ?string $pluralModelLabel = 'Pazar Kategorileri';
    protected static ?string $navigationGroup = 'İkinci El Pazar';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Kategori Adı')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $context, $state, Forms\Set $set) => 
                    $context === 'create' ? $set('slug', Str::slug($state)) : null),
            
            Forms\Components\TextInput::make('slug')
                ->label('URL Slug')
                ->required()
                ->maxLength(255)
                ->unique(SecondHandCategory::class, 'slug', ignoreRecord: true),
            
            Forms\Components\Textarea::make('description')
                ->label('Açıklama')
                ->rows(3),
            
            Forms\Components\TextInput::make('icon')
                ->label('İkon (Emoji)')
                ->maxLength(10)
                ->placeholder('🛍️'),
            
            Forms\Components\TextInput::make('sort_order')
                ->label('Sıralama')
                ->numeric()
                ->default(0),
            
            Forms\Components\Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Kategori Adı')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('icon')
                    ->label('İkon'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sıra')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif Durum'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSecondHandCategories::route('/'),
            'create' => Pages\CreateSecondHandCategory::route('/create'),
            'edit' => Pages\EditSecondHandCategory::route('/{record}/edit'),
        ];
    }
}