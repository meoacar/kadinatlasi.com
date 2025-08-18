<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeautyArticleResource\Pages;
use App\Models\BeautyArticle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BeautyArticleResource extends Resource
{
    protected static ?string $model = BeautyArticle::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = 'Güzellik Makaleleri';
    protected static ?string $navigationGroup = 'Güzellik & Moda';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Başlık')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('excerpt')
                    ->label('Özet')
                    ->required()
                    ->maxLength(500),
                    
                Forms\Components\RichEditor::make('content')
                    ->label('İçerik')
                    ->required(),
                    
                Forms\Components\TextInput::make('icon')
                    ->label('İkon (Emoji)')
                    ->maxLength(10)
                    ->default('✨'),
                    
                Forms\Components\ColorPicker::make('color')
                    ->label('Renk')
                    ->default('#fce7f3'),
                    
                Forms\Components\TextInput::make('read_time')
                    ->label('Okuma Süresi (dk)')
                    ->numeric()
                    ->default(5),
                    
                Forms\Components\Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'skincare' => 'Cilt Bakımı',
                        'makeup' => 'Makyaj',
                        'haircare' => 'Saç Bakımı',
                        'nails' => 'Tırnak Bakımı',
                        'fragrance' => 'Parfüm'
                    ])
                    ->required(),
                    
                Forms\Components\FileUpload::make('featured_image')
                    ->label('Öne Çıkan Görsel')
                    ->image()
                    ->directory('beauty-articles'),
                    
                Forms\Components\TagsInput::make('tags')
                    ->label('Etiketler'),
                    
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
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'skincare' => 'success',
                        'makeup' => 'warning',
                        'haircare' => 'info',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('read_time')
                    ->label('Okuma Süresi')
                    ->suffix(' dk'),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'skincare' => 'Cilt Bakımı',
                        'makeup' => 'Makyaj',
                        'haircare' => 'Saç Bakımı',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif'),
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
            'index' => Pages\ListBeautyArticles::route('/'),
            'create' => Pages\CreateBeautyArticle::route('/create'),
            'edit' => Pages\EditBeautyArticle::route('/{record}/edit'),
        ];
    }
}