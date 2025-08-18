<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeautyVideoResource\Pages;
use App\Models\BeautyVideo;
use App\Models\BeautyCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BeautyVideoResource extends Resource
{
    protected static ?string $model = BeautyVideo::class;
    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationLabel = 'Videolar';
    protected static ?string $navigationGroup = 'Güzellik & Moda';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Video Başlığı')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('description')
                    ->label('Açıklama')
                    ->required()
                    ->rows(3),
                    
                Forms\Components\TextInput::make('video_url')
                    ->label('Video URL')
                    ->url()
                    ->placeholder('https://youtube.com/watch?v=...'),
                    
                Forms\Components\FileUpload::make('thumbnail')
                    ->label('Video Kapak Görseli')
                    ->image()
                    ->directory('beauty-videos'),
                    
                Forms\Components\TextInput::make('duration')
                    ->label('Süre')
                    ->placeholder('8:45')
                    ->maxLength(10),
                    
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->options(BeautyCategory::where('is_active', true)->pluck('name', 'id'))
                    ->searchable(),
                    
                Forms\Components\TagsInput::make('tags')
                    ->label('Etiketler'),
                    
                Forms\Components\TextInput::make('views_count')
                    ->label('Görüntülenme')
                    ->numeric()
                    ->default(0),
                    
                Forms\Components\Toggle::make('featured')
                    ->label('Öne Çıkan'),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Kapak'),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),
                    
                Tables\Columns\TextColumn::make('duration')
                    ->label('Süre'),
                    
                Tables\Columns\TextColumn::make('views_count')
                    ->label('Görüntülenme')
                    ->numeric(),
                    
                Tables\Columns\IconColumn::make('featured')
                    ->label('Öne Çıkan')
                    ->boolean(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBeautyVideos::route('/'),
            'create' => Pages\CreateBeautyVideo::route('/create'),
            'edit' => Pages\EditBeautyVideo::route('/{record}/edit'),
        ];
    }
}