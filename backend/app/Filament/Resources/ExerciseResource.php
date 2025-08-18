<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExerciseResource\Pages;
use App\Models\Exercise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExerciseResource extends Resource
{
    protected static ?string $model = Exercise::class;
    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?string $navigationLabel = 'Egzersizler';
    protected static ?string $modelLabel = 'Egzersiz';
    protected static ?string $pluralModelLabel = 'Egzersizler';
    protected static ?string $navigationGroup = 'Fitness & Diyet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Egzersiz Adı')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('description')
                    ->label('Açıklama')
                    ->required()
                    ->rows(3),
                    
                Forms\Components\Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'Kardiyovasküler' => 'Kardiyovasküler',
                        'Güç Antrenmanı' => 'Güç Antrenmanı',
                        'Esneklik' => 'Esneklik',
                        'Yoga' => 'Yoga',
                        'Pilates' => 'Pilates',
                        'HIIT' => 'HIIT',
                    ])
                    ->required(),
                    
                Forms\Components\Select::make('difficulty')
                    ->label('Zorluk Seviyesi')
                    ->options([
                        'beginner' => 'Başlangıç',
                        'intermediate' => 'Orta',
                        'advanced' => 'İleri',
                    ])
                    ->required(),
                    
                Forms\Components\TextInput::make('duration_minutes')
                    ->label('Süre (Dakika)')
                    ->numeric()
                    ->required(),
                    
                Forms\Components\TextInput::make('calories_burned')
                    ->label('Yakılan Kalori')
                    ->numeric(),
                    
                Forms\Components\TextInput::make('equipment_needed')
                    ->label('Gerekli Ekipman')
                    ->maxLength(255),
                    
                Forms\Components\RichEditor::make('instructions')
                    ->label('Talimatlar')
                    ->required(),
                    
                Forms\Components\TextInput::make('video_url')
                    ->label('Video URL')
                    ->url(),
                    
                Forms\Components\FileUpload::make('image_url')
                    ->label('Görsel')
                    ->image()
                    ->directory('exercises'),
                    
                Forms\Components\TagsInput::make('muscle_groups')
                    ->label('Kas Grupları')
                    ->suggestions([
                        'Göğüs', 'Sırt', 'Omuz', 'Kol', 'Karın', 'Bacak', 'Kalça'
                    ]),
                    
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
                    ->label('Egzersiz Adı')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('category')
                    ->label('Kategori')
                    ->colors([
                        'primary' => 'Kardiyovasküler',
                        'success' => 'Güç Antrenmanı',
                        'warning' => 'Esneklik',
                        'info' => 'Yoga',
                        'secondary' => 'Pilates',
                        'danger' => 'HIIT',
                    ]),
                    
                Tables\Columns\BadgeColumn::make('difficulty')
                    ->label('Zorluk')
                    ->colors([
                        'success' => 'beginner',
                        'warning' => 'intermediate',
                        'danger' => 'advanced',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'beginner' => 'Başlangıç',
                        'intermediate' => 'Orta',
                        'advanced' => 'İleri',
                    }),
                    
                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Süre')
                    ->suffix(' dk')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('calories_burned')
                    ->label('Kalori')
                    ->suffix(' kcal')
                    ->sortable(),
                    
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
                        'Kardiyovasküler' => 'Kardiyovasküler',
                        'Güç Antrenmanı' => 'Güç Antrenmanı',
                        'Esneklik' => 'Esneklik',
                        'Yoga' => 'Yoga',
                        'Pilates' => 'Pilates',
                        'HIIT' => 'HIIT',
                    ]),
                    
                Tables\Filters\SelectFilter::make('difficulty')
                    ->label('Zorluk')
                    ->options([
                        'beginner' => 'Başlangıç',
                        'intermediate' => 'Orta',
                        'advanced' => 'İleri',
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
            'index' => Pages\ListExercises::route('/'),
            'create' => Pages\CreateExercise::route('/create'),
            'edit' => Pages\EditExercise::route('/{record}/edit'),
        ];
    }
}