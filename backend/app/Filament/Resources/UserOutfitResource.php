<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserOutfitResource\Pages;
use App\Models\UserOutfit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class UserOutfitResource extends Resource
{
    protected static ?string $model = UserOutfit::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = 'Kombin Paylaşımları';
    protected static ?string $modelLabel = 'Kombin';
    protected static ?string $pluralModelLabel = 'Kombinler';
    protected static ?string $navigationGroup = 'Güzellik & Moda';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Başlık')
                    ->required()
                    ->maxLength(255),
                
                Textarea::make('description')
                    ->label('Açıklama')
                    ->rows(3),
                
                Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'casual' => 'Günlük',
                        'formal' => 'Resmi',
                        'party' => 'Parti',
                        'sport' => 'Spor',
                        'business' => 'İş',
                        'evening' => 'Gece',
                        'wedding' => 'Düğün',
                        'beach' => 'Plaj'
                    ])
                    ->required(),
                
                Select::make('season')
                    ->label('Mevsim')
                    ->options([
                        'spring' => 'İlkbahar',
                        'summer' => 'Yaz',
                        'autumn' => 'Sonbahar',
                        'winter' => 'Kış',
                        'all' => 'Tüm Mevsimler'
                    ])
                    ->required(),
                
                FileUpload::make('image1')
                    ->label('Resim 1 (Zorunlu)')
                    ->image()
                    ->directory('outfits')
                    ->required(),
                
                FileUpload::make('image2')
                    ->label('Resim 2')
                    ->image()
                    ->directory('outfits'),
                
                FileUpload::make('image3')
                    ->label('Resim 3')
                    ->image()
                    ->directory('outfits'),
                
                FileUpload::make('image4')
                    ->label('Resim 4')
                    ->image()
                    ->directory('outfits'),
                
                TagsInput::make('tags')
                    ->label('Etiketler'),
                
                Toggle::make('is_approved')
                    ->label('Onaylandı')
                    ->default(false),
                
                Select::make('user_id')
                    ->label('Kullanıcı')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image1')
                    ->label('Resim')
                    ->circular()
                    ->size(60),
                
                TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'casual' => 'gray',
                        'formal' => 'blue',
                        'party' => 'pink',
                        'sport' => 'green',
                        'business' => 'indigo',
                        'evening' => 'purple',
                        'wedding' => 'rose',
                        'beach' => 'cyan',
                        default => 'gray',
                    }),
                
                TextColumn::make('season')
                    ->label('Mevsim')
                    ->badge(),
                
                BooleanColumn::make('is_approved')
                    ->label('Onaylandı')
                    ->sortable(),
                
                TextColumn::make('likes_count')
                    ->label('Beğeni')
                    ->sortable()
                    ->default(0),
                
                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'casual' => 'Günlük',
                        'formal' => 'Resmi',
                        'party' => 'Parti',
                        'sport' => 'Spor',
                        'business' => 'İş',
                        'evening' => 'Gece',
                        'wedding' => 'Düğün',
                        'beach' => 'Plaj'
                    ]),
                
                SelectFilter::make('season')
                    ->label('Mevsim')
                    ->options([
                        'spring' => 'İlkbahar',
                        'summer' => 'Yaz',
                        'autumn' => 'Sonbahar',
                        'winter' => 'Kış',
                        'all' => 'Tüm Mevsimler'
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Onay Durumu'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Onayla')
                        ->icon('heroicon-o-check')
                        ->action(fn ($records) => $records->each->update(['is_approved' => true]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('reject')
                        ->label('Reddet')
                        ->icon('heroicon-o-x-mark')
                        ->action(fn ($records) => $records->each->update(['is_approved' => false]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserOutfits::route('/'),
            'create' => Pages\CreateUserOutfit::route('/create'),
            'edit' => Pages\EditUserOutfit::route('/{record}/edit'),
        ];
    }
}