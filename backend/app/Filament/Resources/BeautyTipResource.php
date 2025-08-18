<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeautyTipResource\Pages;
use App\Models\BeautyTip;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BeautyTipResource extends Resource
{
    protected static ?string $model = BeautyTip::class;
    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';
    protected static ?string $navigationLabel = 'Güzellik İpuçları';
    protected static ?string $navigationGroup = 'Güzellik & Moda';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('İpucu Başlığı')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\RichEditor::make('content')
                    ->label('İçerik')
                    ->required(),
                    
                Forms\Components\TextInput::make('icon')
                    ->label('İkon (Emoji)')
                    ->maxLength(10)
                    ->default('💡'),
                    
                Forms\Components\ColorPicker::make('color')
                    ->label('Renk')
                    ->default('#fbbf24'),
                    
                Forms\Components\Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'skincare' => 'Cilt Bakımı',
                        'makeup' => 'Makyaj',
                        'haircare' => 'Saç Bakımı',
                        'nails' => 'Tırnak Bakımı',
                        'lifestyle' => 'Yaşam Tarzı'
                    ])
                    ->required(),
                    
                Forms\Components\Select::make('difficulty_level')
                    ->label('Zorluk Seviyesi')
                    ->options([
                        'beginner' => 'Başlangıç',
                        'intermediate' => 'Orta',
                        'advanced' => 'İleri'
                    ])
                    ->default('beginner'),
                    
                Forms\Components\TextInput::make('time_required')
                    ->label('Gerekli Süre')
                    ->placeholder('5 dakika'),
                    
                Forms\Components\TagsInput::make('ingredients')
                    ->label('Malzemeler'),
                    
                Forms\Components\Repeater::make('steps')
                    ->label('Adımlar')
                    ->schema([
                        Forms\Components\Textarea::make('step')
                            ->label('Adım')
                            ->required()
                    ])
                    ->collapsible(),
                    
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
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->limit(40),
                    
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'skincare' => 'success',
                        'makeup' => 'warning',
                        'haircare' => 'info',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('difficulty_level')
                    ->label('Zorluk')
                    ->badge(),
                    
                Tables\Columns\TextColumn::make('time_required')
                    ->label('Süre'),
                    
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
            'index' => Pages\ListBeautyTips::route('/'),
            'create' => Pages\CreateBeautyTip::route('/create'),
            'edit' => Pages\EditBeautyTip::route('/{record}/edit'),
        ];
    }
}