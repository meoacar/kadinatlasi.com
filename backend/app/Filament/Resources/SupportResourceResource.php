<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportResourceResource\Pages;
use App\Models\SupportResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupportResourceResource extends Resource
{
    protected static ?string $model = SupportResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationLabel = 'Destek Kaynakları';

    protected static ?string $modelLabel = 'Destek Kaynağı';

    protected static ?string $pluralModelLabel = 'Destek Kaynakları';

    protected static ?string $navigationGroup = 'İçerik Yönetimi';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Temel Bilgiler')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->required()
                            ->rows(3),

                        Forms\Components\Select::make('category')
                            ->label('Kategori')
                            ->required()
                            ->options([
                                'psychological' => 'Psikolojik Destek',
                                'medical' => 'Tıbbi Destek',
                                'legal' => 'Hukuki Destek',
                                'social' => 'Sosyal Destek',
                                'emergency' => 'Acil Durum',
                            ]),

                        Forms\Components\Select::make('type')
                            ->label('Tür')
                            ->required()
                            ->options([
                                'hotline' => 'Yardım Hattı',
                                'website' => 'Web Sitesi',
                                'organization' => 'Kuruluş',
                                'app' => 'Mobil Uygulama',
                            ]),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('İletişim Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefon')
                            ->tel(),

                        Forms\Components\TextInput::make('email')
                            ->label('E-posta')
                            ->email(),

                        Forms\Components\TextInput::make('url')
                            ->label('Web Sitesi')
                            ->url(),

                        Forms\Components\Textarea::make('address')
                            ->label('Adres')
                            ->rows(2),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Çalışma Saatleri')
                    ->schema([
                        Forms\Components\Repeater::make('working_hours')
                            ->label('Çalışma Saatleri')
                            ->simple(
                                Forms\Components\TextInput::make('hours')
                                    ->label('Saat')
                                    ->placeholder('Örn: Pazartesi-Cuma: 09:00-17:00')
                            )
                            ->defaultItems(1),
                    ]),

                Forms\Components\Section::make('Ayarlar')
                    ->schema([
                        Forms\Components\Toggle::make('is_emergency')
                            ->label('Acil Durum')
                            ->helperText('Bu kaynak acil durumlarda kullanılabilir mi?'),

                        Forms\Components\Toggle::make('is_free')
                            ->label('Ücretsiz')
                            ->helperText('Bu hizmet ücretsiz mi?')
                            ->default(true),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Bu kaynak aktif olarak gösterilsin mi?')
                            ->default(true),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0)
                            ->helperText('Düşük sayılar önce gösterilir'),
                    ])
                    ->columns(2),
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

                Tables\Columns\BadgeColumn::make('category')
                    ->label('Kategori')
                    ->colors([
                        'danger' => 'emergency',
                        'warning' => 'psychological',
                        'success' => 'medical',
                        'primary' => 'legal',
                        'secondary' => 'social',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'psychological' => 'Psikolojik',
                        'medical' => 'Tıbbi',
                        'legal' => 'Hukuki',
                        'social' => 'Sosyal',
                        'emergency' => 'Acil Durum',
                        default => $state,
                    }),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tür')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'hotline' => 'Yardım Hattı',
                        'website' => 'Web Sitesi',
                        'organization' => 'Kuruluş',
                        'app' => 'Mobil Uygulama',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_emergency')
                    ->label('Acil')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_free')
                    ->label('Ücretsiz')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sıra')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'psychological' => 'Psikolojik Destek',
                        'medical' => 'Tıbbi Destek',
                        'legal' => 'Hukuki Destek',
                        'social' => 'Sosyal Destek',
                        'emergency' => 'Acil Durum',
                    ]),

                Tables\Filters\SelectFilter::make('type')
                    ->label('Tür')
                    ->options([
                        'hotline' => 'Yardım Hattı',
                        'website' => 'Web Sitesi',
                        'organization' => 'Kuruluş',
                        'app' => 'Mobil Uygulama',
                    ]),

                Tables\Filters\TernaryFilter::make('is_emergency')
                    ->label('Acil Durum'),

                Tables\Filters\TernaryFilter::make('is_free')
                    ->label('Ücretsiz'),

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
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportResources::route('/'),
            'create' => Pages\CreateSupportResource::route('/create'),
            'edit' => Pages\EditSupportResource::route('/{record}/edit'),
        ];
    }
}