<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AchievementResource\Pages;
use App\Models\Achievement;
use App\Models\AchievementCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AchievementResource extends Resource
{
    protected static ?string $model = Achievement::class;
    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'Başarımlar';
    protected static ?string $modelLabel = 'Başarım';
    protected static ?string $pluralModelLabel = 'Başarımlar';
    protected static ?string $navigationGroup = 'Gamification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->options(AchievementCategory::pluck('name', 'id'))
                    ->required(),
                    
                Forms\Components\TextInput::make('name')
                    ->label('Başarım Adı')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('description')
                    ->label('Açıklama')
                    ->required()
                    ->rows(3),
                    
                Forms\Components\TextInput::make('icon')
                    ->label('İkon (Emoji)')
                    ->maxLength(10),
                    
                Forms\Components\ColorPicker::make('badge_color')
                    ->label('Rozet Rengi')
                    ->default('#E57399'),
                    
                Forms\Components\Select::make('type')
                    ->label('Tip')
                    ->options([
                        'one_time' => 'Tek Seferlik',
                        'repeatable' => 'Tekrarlanabilir',
                        'progressive' => 'Aşamalı'
                    ])
                    ->required(),
                    
                Forms\Components\Select::make('difficulty')
                    ->label('Zorluk')
                    ->options([
                        'bronze' => 'Bronz',
                        'silver' => 'Gümüş',
                        'gold' => 'Altın',
                        'platinum' => 'Platin',
                        'diamond' => 'Elmas'
                    ])
                    ->required(),
                    
                Forms\Components\TextInput::make('points')
                    ->label('Puan')
                    ->numeric()
                    ->default(0)
                    ->required(),
                    
                Forms\Components\TextInput::make('target_value')
                    ->label('Hedef Değer')
                    ->numeric(),
                    
                Forms\Components\TextInput::make('target_metric')
                    ->label('Hedef Metrik')
                    ->helperText('Örnek: blog_post_create, forum_reply_create'),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
                    
                Forms\Components\Toggle::make('is_hidden')
                    ->label('Gizli (Sürpriz Başarım)')
                    ->default(false),
                    
                Forms\Components\TextInput::make('sort_order')
                    ->label('Sıralama')
                    ->numeric()
                    ->default(0),
                    
                Forms\Components\KeyValue::make('requirements')
                    ->label('Gereksinimler (JSON)')
                    ->helperText('Ek gereksinimler için key-value çiftleri')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Başarım Adı')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('icon')
                    ->label('İkon'),
                    
                Tables\Columns\BadgeColumn::make('difficulty')
                    ->label('Zorluk')
                    ->colors([
                        'warning' => 'bronze',
                        'secondary' => 'silver',
                        'success' => 'gold',
                        'primary' => 'platinum',
                        'info' => 'diamond'
                    ]),
                    
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tip')
                    ->colors([
                        'primary' => 'one_time',
                        'success' => 'repeatable',
                        'warning' => 'progressive'
                    ]),
                    
                Tables\Columns\TextColumn::make('points')
                    ->label('Puan')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('target_value')
                    ->label('Hedef')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                    
                Tables\Columns\IconColumn::make('is_hidden')
                    ->label('Gizli')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('userAchievements_count')
                    ->label('Kazanan Sayısı')
                    ->counts('userAchievements')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->options(AchievementCategory::pluck('name', 'id')),
                    
                Tables\Filters\SelectFilter::make('difficulty')
                    ->label('Zorluk')
                    ->options([
                        'bronze' => 'Bronz',
                        'silver' => 'Gümüş',
                        'gold' => 'Altın',
                        'platinum' => 'Platin',
                        'diamond' => 'Elmas'
                    ]),
                    
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tip')
                    ->options([
                        'one_time' => 'Tek Seferlik',
                        'repeatable' => 'Tekrarlanabilir',
                        'progressive' => 'Aşamalı'
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif'),
                    
                Tables\Filters\TernaryFilter::make('is_hidden')
                    ->label('Gizli')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAchievements::route('/'),
            'create' => Pages\CreateAchievement::route('/create'),
            'edit' => Pages\EditAchievement::route('/{record}/edit'),
        ];
    }
}