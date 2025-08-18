<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'İletişim Mesajları';
    protected static ?string $modelLabel = 'İletişim Mesajı';
    protected static ?string $pluralModelLabel = 'İletişim Mesajları';
    protected static ?string $navigationGroup = 'İletişim';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::unread()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::unread()->count() > 0 ? 'danger' : 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Mesaj Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Ad Soyad')
                            ->required()
                            ->disabled(),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('E-posta')
                            ->email()
                            ->required()
                            ->disabled(),
                        
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefon')
                            ->disabled(),
                        
                        Forms\Components\TextInput::make('subject')
                            ->label('Konu')
                            ->required()
                            ->disabled(),
                        
                        Forms\Components\Textarea::make('message')
                            ->label('Mesaj')
                            ->required()
                            ->rows(6)
                            ->disabled(),
                        
                        Forms\Components\Toggle::make('is_read')
                            ->label('Okundu')
                            ->helperText('Mesajı okundu olarak işaretle'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Tarih Bilgileri')
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Gönderilme Tarihi')
                            ->content(fn ($record) => $record?->created_at?->format('d.m.Y H:i')),
                        
                        Forms\Components\Placeholder::make('read_at')
                            ->label('Okunma Tarihi')
                            ->content(fn ($record) => $record?->read_at?->format('d.m.Y H:i') ?? 'Henüz okunmadı'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_read')
                    ->label('')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip(fn ($record) => $record->is_read ? 'Okundu' : 'Okunmadı'),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable()
                    ->weight(fn ($record) => $record->is_read ? 'normal' : 'bold'),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('E-posta kopyalandı'),
                
                Tables\Columns\TextColumn::make('subject')
                    ->label('Konu')
                    ->searchable()
                    ->limit(50)
                    ->weight(fn ($record) => $record->is_read ? 'normal' : 'bold'),
                
                Tables\Columns\TextColumn::make('message')
                    ->label('Mesaj')
                    ->limit(100)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 100) {
                            return null;
                        }
                        return $state;
                    }),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label('Okunma Durumu')
                    ->trueLabel('Okundu')
                    ->falseLabel('Okunmadı')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_as_read')
                    ->label('Okundu İşaretle')
                    ->icon('heroicon-o-envelope-open')
                    ->color('success')
                    ->visible(fn ($record) => !$record->is_read)
                    ->action(fn ($record) => $record->markAsRead())
                    ->requiresConfirmation()
                    ->modalHeading('Mesajı Okundu İşaretle')
                    ->modalDescription('Bu mesajı okundu olarak işaretlemek istediğinizden emin misiniz?'),
                
                Tables\Actions\EditAction::make()
                    ->label('Görüntüle')
                    ->icon('heroicon-o-eye')
                    ->modalWidth('4xl')
                    ->action(fn ($record) => $record->markAsRead()),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_as_read')
                        ->label('Okundu İşaretle')
                        ->icon('heroicon-o-envelope-open')
                        ->color('success')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->markAsRead();
                            }
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
        ];
    }
}