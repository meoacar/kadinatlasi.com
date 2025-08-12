<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkoutProgramResource\Pages;
use App\Models\WorkoutProgram;
use App\Models\Exercise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorkoutProgramResource extends Resource
{
    protected static ?string $model = WorkoutProgram::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Antrenman Programları';
    protected static ?string $modelLabel = 'Antrenman Programı';
    protected static ?string $pluralModelLabel = 'Antrenman Programları';
    protected static ?string $navigationGroup = 'Fitness & Diyet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Program Adı')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('description')
                    ->label('Açıklama')
                    ->required()
                    ->rows(3),
                    
                Forms\Components\Select::make('goal')
                    ->label('Hedef')
                    ->options([
                        'weight_loss' => 'Kilo Verme',
                        'muscle_gain' => 'Kas Kazanma',
                        'endurance' => 'Dayanıklılık',
                        'flexibility' => 'Esneklik',
                        'general_fitness' => 'Genel Fitness',
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
                    
                Forms\Components\TextInput::make('duration_weeks')
                    ->label('Süre (Hafta)')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(52),
                    
                Forms\Components\TextInput::make('days_per_week')
                    ->label('Haftalık Gün Sayısı')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(7),
                    
                Forms\Components\Repeater::make('exercises')
                    ->label('Egzersizler')
                    ->schema([
                        Forms\Components\Select::make('exercise_id')
                            ->label('Egzersiz')
                            ->options(Exercise::active()->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        Forms\Components\TextInput::make('sets')
                            ->label('Set Sayısı')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Forms\Components\TextInput::make('reps')
                            ->label('Tekrar Sayısı')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Forms\Components\TextInput::make('rest_seconds')
                            ->label('Dinlenme (Saniye)')
                            ->numeric()
                            ->default(60),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notlar')
                            ->rows(2),
                    ])
                    ->columns(2)
                    ->required(),
                    
                Forms\Components\CheckboxList::make('schedule')
                    ->label('Haftalık Program')
                    ->options([
                        'monday' => 'Pazartesi',
                        'tuesday' => 'Salı',
                        'wednesday' => 'Çarşamba',
                        'thursday' => 'Perşembe',
                        'friday' => 'Cuma',
                        'saturday' => 'Cumartesi',
                        'sunday' => 'Pazar',
                    ])
                    ->columns(3)
                    ->required(),
                    
                Forms\Components\FileUpload::make('image_url')
                    ->label('Program Görseli')
                    ->image()
                    ->directory('workout-programs'),
                    
                Forms\Components\TextInput::make('trainer_name')
                    ->label('Antrenör Adı')
                    ->maxLength(255),
                    
                Forms\Components\Toggle::make('is_premium')
                    ->label('Premium Program')
                    ->default(false),
                    
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
                    ->label('Program Adı')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('goal')
                    ->label('Hedef')
                    ->colors([
                        'danger' => 'weight_loss',
                        'success' => 'muscle_gain',
                        'warning' => 'endurance',
                        'info' => 'flexibility',
                        'primary' => 'general_fitness',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'weight_loss' => 'Kilo Verme',
                        'muscle_gain' => 'Kas Kazanma',
                        'endurance' => 'Dayanıklılık',
                        'flexibility' => 'Esneklik',
                        'general_fitness' => 'Genel Fitness',
                    }),
                    
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
                    
                Tables\Columns\TextColumn::make('duration_weeks')
                    ->label('Süre')
                    ->suffix(' hafta')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('days_per_week')
                    ->label('Haftalık')
                    ->suffix(' gün')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('trainer_name')
                    ->label('Antrenör')
                    ->searchable(),
                    
                Tables\Columns\IconColumn::make('is_premium')
                    ->label('Premium')
                    ->boolean(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Durum')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('goal')
                    ->label('Hedef')
                    ->options([
                        'weight_loss' => 'Kilo Verme',
                        'muscle_gain' => 'Kas Kazanma',
                        'endurance' => 'Dayanıklılık',
                        'flexibility' => 'Esneklik',
                        'general_fitness' => 'Genel Fitness',
                    ]),
                    
                Tables\Filters\SelectFilter::make('difficulty')
                    ->label('Zorluk')
                    ->options([
                        'beginner' => 'Başlangıç',
                        'intermediate' => 'Orta',
                        'advanced' => 'İleri',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_premium')
                    ->label('Premium')
                    ->trueLabel('Premium')
                    ->falseLabel('Ücretsiz'),
                    
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
            'index' => Pages\ListWorkoutPrograms::route('/'),
            'create' => Pages\CreateWorkoutProgram::route('/create'),
            'edit' => Pages\EditWorkoutProgram::route('/{record}/edit'),
        ];
    }
}