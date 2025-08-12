<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SystemLogResource\Pages;
use App\Models\SystemLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SystemLogResource extends Resource
{
    protected static ?string $model = SystemLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Sistem Logları';
    protected static ?string $modelLabel = 'Sistem Logu';
    protected static ?string $pluralModelLabel = 'Sistem Logları';
    protected static ?string $navigationGroup = 'Sistem Yönetimi';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Log Detayları')
                    ->schema([
                        Forms\Components\Select::make('level')
                            ->label('Seviye')
                            ->options([
                                'info' => 'Bilgi',
                                'warning' => 'Uyarı',
                                'error' => 'Hata',
                                'critical' => 'Kritik'
                            ])
                            ->required(),
                        
                        Forms\Components\TextInput::make('action')
                            ->label('Eylem')
                            ->required(),
                        
                        Forms\Components\TextInput::make('model')
                            ->label('Model'),
                        
                        Forms\Components\TextInput::make('model_id')
                            ->label('Model ID')
                            ->numeric(),
                        
                        Forms\Components\Select::make('user_id')
                            ->label('Kullanıcı')
                            ->relationship('user', 'name')
                            ->searchable(),
                        
                        Forms\Components\TextInput::make('ip_address')
                            ->label('IP Adresi'),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->required()
                            ->rows(3),
                        
                        Forms\Components\Textarea::make('old_values')
                            ->label('Eski Değerler (JSON)')
                            ->rows(4),
                        
                        Forms\Components\Textarea::make('new_values')
                            ->label('Yeni Değerler (JSON)')
                            ->rows(4),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('level')
                    ->label('Seviye')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'info' => 'success',
                        'warning' => 'warning',
                        'error' => 'danger',
                        'critical' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'info' => 'Bilgi',
                        'warning' => 'Uyarı',
                        'error' => 'Hata',
                        'critical' => 'Kritik',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('action')
                    ->label('Eylem')
                    ->badge()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('model')
                    ->label('Model')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('description')
                    ->label('Açıklama')
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->label('Seviye')
                    ->options([
                        'info' => 'Bilgi',
                        'warning' => 'Uyarı',
                        'error' => 'Hata',
                        'critical' => 'Kritik'
                    ]),
                
                Tables\Filters\SelectFilter::make('action')
                    ->label('Eylem')
                    ->options([
                        'login' => 'Giriş',
                        'logout' => 'Çıkış',
                        'create' => 'Oluştur',
                        'update' => 'Güncelle',
                        'delete' => 'Sil'
                    ]),
                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Başlangıç Tarihi'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Bitiş Tarihi'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn ($query, $date) => $query->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSystemLogs::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }
}