<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Görevler';
    protected static ?string $modelLabel = 'Görev';
    protected static ?string $pluralModelLabel = 'Görevler';
    protected static ?string $navigationGroup = 'Gamification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Görev Adı')
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
                    
                Forms\Components\Select::make('type')
                    ->label('Görev Tipi')
                    ->options([
                        'daily' => 'Günlük',
                        'weekly' => 'Haftalık',
                        'monthly' => 'Aylık',
                        'special' => 'Özel'
                    ])
                    ->required(),
                    
                Forms\Components\Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'health' => 'Sağlık',
                        'social' => 'Sosyal',
                        'learning' => 'Öğrenme',
                        'wellness' => 'Wellness',
                        'engagement' => 'Etkileşim'
                    ])
                    ->required(),
                    
                Forms\Components\TextInput::make('points')
                    ->label('Puan')
                    ->numeric()
                    ->default(10)
                    ->required(),
                    
                Forms\Components\TextInput::make('xp_reward')
                    ->label('XP Ödülü')
                    ->numeric()
                    ->default(5)
                    ->required(),
                    
                Forms\Components\TextInput::make('action_type')
                    ->label('Aksiyon Tipi')
                    ->required()
                    ->helperText('Örnek: login, blog_post_create, calculator_use'),
                    
                Forms\Components\TextInput::make('target_count')
                    ->label('Hedef Sayı')
                    ->numeric()
                    ->default(1)
                    ->required(),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
                    
                Forms\Components\DatePicker::make('start_date')
                    ->label('Başlangıç Tarihi'),
                    
                Forms\Components\DatePicker::make('end_date')
                    ->label('Bitiş Tarihi'),
                    
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Görev Adı')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('icon')
                    ->label('İkon'),
                    
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tip')
                    ->colors([
                        'primary' => 'daily',
                        'success' => 'weekly',
                        'warning' => 'monthly',
                        'danger' => 'special'
                    ]),
                    
                Tables\Columns\BadgeColumn::make('category')
                    ->label('Kategori')
                    ->colors([
                        'success' => 'health',
                        'primary' => 'social',
                        'warning' => 'learning',
                        'info' => 'wellness',
                        'secondary' => 'engagement'
                    ]),
                    
                Tables\Columns\TextColumn::make('points')
                    ->label('Puan')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('xp_reward')
                    ->label('XP')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('target_count')
                    ->label('Hedef')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('userTasks_count')
                    ->label('Tamamlanan')
                    ->counts('userTasks')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tip')
                    ->options([
                        'daily' => 'Günlük',
                        'weekly' => 'Haftalık',
                        'monthly' => 'Aylık',
                        'special' => 'Özel'
                    ]),
                    
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'health' => 'Sağlık',
                        'social' => 'Sosyal',
                        'learning' => 'Öğrenme',
                        'wellness' => 'Wellness',
                        'engagement' => 'Etkileşim'
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif')
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}