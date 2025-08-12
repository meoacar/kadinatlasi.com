<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Kurslar';
    protected static ?string $modelLabel = 'Kurs';
    protected static ?string $pluralModelLabel = 'Kurslar';
    protected static ?string $navigationGroup = 'Premium Üyelik';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Kurs Başlığı')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Açıklama')
                    ->required(),
                Forms\Components\TextInput::make('instructor_name')
                    ->label('Eğitmen Adı')
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->label('Fiyat (₺)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('duration')
                    ->label('Süre (dakika)')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('level')
                    ->label('Seviye')
                    ->options([
                        'beginner' => 'Başlangıç',
                        'intermediate' => 'Orta',
                        'advanced' => 'İleri'
                    ])
                    ->required(),
                Forms\Components\TextInput::make('category')
                    ->label('Kategori')
                    ->required(),
                Forms\Components\TextInput::make('image_url')
                    ->label('Görsel URL'),
                Forms\Components\TextInput::make('video_url')
                    ->label('Video URL'),
                Forms\Components\RichEditor::make('content')
                    ->label('İçerik'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable(),
                Tables\Columns\TextColumn::make('instructor_name')
                    ->label('Eğitmen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Fiyat')
                    ->money('TRY'),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Süre')
                    ->suffix(' dk'),
                Tables\Columns\TextColumn::make('level')
                    ->label('Seviye')
                    ->badge(),
                Tables\Columns\TextColumn::make('enrollment_count')
                    ->label('Kayıt')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Puan')
                    ->badge()
                    ->color('warning'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}