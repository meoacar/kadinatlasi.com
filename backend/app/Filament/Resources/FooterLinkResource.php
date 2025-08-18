<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FooterLinkResource\Pages;
use App\Models\FooterLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FooterLinkResource extends Resource
{
    protected static ?string $model = FooterLink::class;
    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationLabel = 'Footer Linkleri';
    protected static ?string $modelLabel = 'Footer Link';
    protected static ?string $pluralModelLabel = 'Footer Linkleri';
    protected static ?string $navigationGroup = 'Sistem Yönetimi';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Link Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Link Adı')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('url')
                            ->label('URL')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Örn: /hakkimizda veya https://example.com'),
                        
                        Forms\Components\Select::make('section')
                            ->label('Bölüm')
                            ->options([
                                'quick_links' => 'Hızlı Linkler',
                                'category_links' => 'Kategori Linkleri'
                            ])
                            ->required(),
                        
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0)
                            ->helperText('Küçük sayılar önce görünür'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Link Adı')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('URL kopyalandı'),
                
                Tables\Columns\BadgeColumn::make('section')
                    ->label('Bölüm')
                    ->colors([
                        'primary' => 'quick_links',
                        'success' => 'category_links',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'quick_links' => 'Hızlı Linkler',
                        'category_links' => 'Kategori Linkleri',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sıra')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Güncelleme')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('section')
                    ->label('Bölüm')
                    ->options([
                        'quick_links' => 'Hızlı Linkler',
                        'category_links' => 'Kategori Linkleri'
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
            ])
            ->defaultSort('section')
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFooterLinks::route('/'),
            'create' => Pages\CreateFooterLink::route('/create'),
            'edit' => Pages\EditFooterLink::route('/{record}/edit'),
        ];
    }
}