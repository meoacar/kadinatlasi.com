<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BackupLogResource\Pages;
use App\Models\BackupLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class BackupLogResource extends Resource
{
    protected static ?string $model = BackupLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Yedekleme';
    protected static ?string $modelLabel = 'Yedek';
    protected static ?string $pluralModelLabel = 'Yedekler';
    protected static ?string $navigationGroup = 'Sistem Yönetimi';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Yedek Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Yedek Adı')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('type')
                            ->label('Yedek Tipi')
                            ->options([
                                'database' => 'Veritabanı',
                                'files' => 'Dosyalar',
                                'full' => 'Tam Yedek'
                            ])
                            ->required(),
                        
                        Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->options([
                                'pending' => 'Bekliyor',
                                'running' => 'Çalışıyor',
                                'completed' => 'Tamamlandı',
                                'failed' => 'Başarısız'
                            ])
                            ->required(),
                        
                        Forms\Components\TextInput::make('file_path')
                            ->label('Dosya Yolu'),
                        
                        Forms\Components\TextInput::make('file_size')
                            ->label('Dosya Boyutu (Byte)')
                            ->numeric(),
                        
                        Forms\Components\DateTimePicker::make('started_at')
                            ->label('Başlangıç Zamanı'),
                        
                        Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Bitiş Zamanı'),
                        
                        Forms\Components\Textarea::make('error_message')
                            ->label('Hata Mesajı')
                            ->rows(3),
                        
                        Forms\Components\Select::make('created_by')
                            ->label('Oluşturan')
                            ->relationship('creator', 'name')
                            ->searchable(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Yedek Adı')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('type')
                    ->label('Tip')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'database' => 'info',
                        'files' => 'warning',
                        'full' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'database' => 'Veritabanı',
                        'files' => 'Dosyalar',
                        'full' => 'Tam Yedek',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'running' => 'info',
                        'completed' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Bekliyor',
                        'running' => 'Çalışıyor',
                        'completed' => 'Tamamlandı',
                        'failed' => 'Başarısız',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('file_size_human')
                    ->label('Boyut')
                    ->getStateUsing(fn (BackupLog $record): string => $record->file_size_human),
                
                Tables\Columns\TextColumn::make('duration')
                    ->label('Süre')
                    ->getStateUsing(fn (BackupLog $record): ?string => $record->duration),
                
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Oluşturan')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tip')
                    ->options([
                        'database' => 'Veritabanı',
                        'files' => 'Dosyalar',
                        'full' => 'Tam Yedek'
                    ]),
                
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Bekliyor',
                        'running' => 'Çalışıyor',
                        'completed' => 'Tamamlandı',
                        'failed' => 'Başarısız'
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('İndir')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->visible(fn (BackupLog $record): bool => $record->status === 'completed' && $record->file_path)
                    ->action(function (BackupLog $record) {
                        if (file_exists($record->file_path)) {
                            return response()->download($record->file_path);
                        }
                        
                        Notification::make()
                            ->title('Dosya bulunamadı')
                            ->danger()
                            ->send();
                    }),
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('create_backup')
                    ->label('Yeni Yedek Oluştur')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label('Yedek Adı')
                            ->required()
                            ->default('Yedek_' . now()->format('Y_m_d_H_i_s')),
                        
                        Forms\Components\Select::make('type')
                            ->label('Yedek Tipi')
                            ->options([
                                'database' => 'Veritabanı',
                                'files' => 'Dosyalar',
                                'full' => 'Tam Yedek'
                            ])
                            ->required()
                            ->default('database'),
                    ])
                    ->action(function (array $data) {
                        BackupLog::create([
                            'name' => $data['name'],
                            'type' => $data['type'],
                            'status' => 'pending',
                            'created_by' => auth()->id(),
                        ]);
                        
                        Notification::make()
                            ->title('Yedekleme işlemi başlatıldı')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBackupLogs::route('/'),
            'create' => Pages\CreateBackupLog::route('/create'),
            'edit' => Pages\EditBackupLog::route('/{record}/edit'),
        ];
    }
}