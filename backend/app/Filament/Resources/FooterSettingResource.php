<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FooterSettingResource\Pages;
use App\Models\FooterSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FooterSettingResource extends Resource
{
    protected static ?string $model = FooterSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Footer Ayarları';
    protected static ?string $modelLabel = 'Footer Ayarı';
    protected static ?string $pluralModelLabel = 'Footer Ayarları';
    protected static ?string $navigationGroup = 'Sistem Yönetimi';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Genel Bilgiler')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Anahtar')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('label')
                            ->label('Etiket')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('type')
                            ->label('Tip')
                            ->options([
                                'text' => 'Metin',
                                'textarea' => 'Uzun Metin',
                                'url' => 'URL',
                                'email' => 'E-posta',
                                'json' => 'JSON'
                            ])
                            ->required()
                            ->reactive(),
                        
                        Forms\Components\Select::make('group')
                            ->label('Grup')
                            ->options([
                                'general' => 'Genel',
                                'contact' => 'İletişim',
                                'social' => 'Sosyal Medya',
                                'links' => 'Linkler'
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('İçerik')
                    ->schema([
                        Forms\Components\TextInput::make('value')
                            ->label('Değer')
                            ->visible(fn ($get) => in_array($get('type'), ['text', 'url', 'email']))
                            ->maxLength(1000),
                        
                        Forms\Components\Textarea::make('value')
                            ->label('Değer')
                            ->visible(fn ($get) => $get('type') === 'textarea')
                            ->rows(4),
                        
                        Forms\Components\Textarea::make('value')
                            ->label('JSON Değer')
                            ->visible(fn ($get) => $get('type') === 'json')
                            ->rows(6)
                            ->helperText('Geçerli JSON formatında giriniz'),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(2),
                    ]),

                Forms\Components\Section::make('Ayarlar')
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0),
                        
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
                Tables\Columns\TextColumn::make('label')
                    ->label('Etiket')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('key')
                    ->label('Anahtar')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('group')
                    ->label('Grup')
                    ->colors([
                        'primary' => 'general',
                        'success' => 'contact',
                        'warning' => 'social',
                        'danger' => 'links',
                    ]),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tip')
                    ->colors([
                        'primary' => 'text',
                        'success' => 'textarea',
                        'warning' => 'url',
                        'danger' => 'email',
                        'secondary' => 'json',
                    ]),
                
                Tables\Columns\TextColumn::make('value')
                    ->label('Değer')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
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
                Tables\Filters\SelectFilter::make('group')
                    ->label('Grup')
                    ->options([
                        'general' => 'Genel',
                        'contact' => 'İletişim',
                        'social' => 'Sosyal Medya',
                        'links' => 'Linkler'
                    ]),
                
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tip')
                    ->options([
                        'text' => 'Metin',
                        'textarea' => 'Uzun Metin',
                        'url' => 'URL',
                        'email' => 'E-posta',
                        'json' => 'JSON'
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
            ->defaultSort('group')
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFooterSettings::route('/'),
            'create' => Pages\CreateFooterSetting::route('/create'),
            'edit' => Pages\EditFooterSetting::route('/{record}/edit'),
        ];
    }
}