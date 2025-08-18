<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Siparişler';
    protected static ?string $modelLabel = 'Sipariş';
    protected static ?string $pluralModelLabel = 'Siparişler';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Sipariş Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Sipariş No')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->options([
                                'pending' => 'Beklemede',
                                'processing' => 'Hazırlanıyor',
                                'shipped' => 'Kargoda',
                                'delivered' => 'Teslim Edildi',
                                'cancelled' => 'İptal Edildi',
                            ])
                            ->required(),
                        Forms\Components\Select::make('payment_status')
                            ->label('Ödeme Durumu')
                            ->options([
                                'pending' => 'Beklemede',
                                'paid' => 'Ödendi',
                                'failed' => 'Başarısız',
                                'refunded' => 'İade Edildi',
                            ])
                            ->required(),
                    ])->columns(3),
                
                Forms\Components\Section::make('Müşteri Bilgileri')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Müşteri')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->disabled(),
                    ]),
                
                Forms\Components\Section::make('Fiyat Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->label('Ara Toplam')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('shipping_cost')
                            ->label('Kargo Ücreti')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('total')
                            ->label('Toplam')
                            ->numeric()
                            ->disabled(),
                    ])->columns(3),
                
                Forms\Components\Textarea::make('notes')
                    ->label('Notlar')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Sipariş No')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Müşteri')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Durum')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'processing',
                        'info' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Beklemede',
                        'processing' => 'Hazırlanıyor',
                        'shipped' => 'Kargoda',
                        'delivered' => 'Teslim Edildi',
                        'cancelled' => 'İptal Edildi',
                        default => $state,
                    }),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Ödeme')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'info' => 'refunded',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Beklemede',
                        'paid' => 'Ödendi',
                        'failed' => 'Başarısız',
                        'refunded' => 'İade Edildi',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->label('Toplam')
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Beklemede',
                        'processing' => 'Hazırlanıyor',
                        'shipped' => 'Kargoda',
                        'delivered' => 'Teslim Edildi',
                        'cancelled' => 'İptal Edildi',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Ödeme Durumu')
                    ->options([
                        'pending' => 'Beklemede',
                        'paid' => 'Ödendi',
                        'failed' => 'Başarısız',
                        'refunded' => 'İade Edildi',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Sipariş Detayları')
                    ->schema([
                        Infolists\Components\TextEntry::make('order_number')
                            ->label('Sipariş No'),
                        Infolists\Components\TextEntry::make('status_label')
                            ->label('Durum'),
                        Infolists\Components\TextEntry::make('payment_status_label')
                            ->label('Ödeme Durumu'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Sipariş Tarihi')
                            ->dateTime('d.m.Y H:i'),
                    ])->columns(2),
                
                Infolists\Components\Section::make('Müşteri Bilgileri')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Müşteri Adı'),
                        Infolists\Components\TextEntry::make('user.email')
                            ->label('E-posta'),
                        Infolists\Components\KeyValueEntry::make('billing_info')
                            ->label('Fatura Bilgileri')
                            ->keyLabel('Alan')
                            ->valueLabel('Değer'),
                    ]),
                
                Infolists\Components\Section::make('Sipariş Kalemleri')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('product_name')
                                    ->label('Ürün'),
                                Infolists\Components\TextEntry::make('quantity')
                                    ->label('Adet'),
                                Infolists\Components\TextEntry::make('product_price')
                                    ->label('Birim Fiyat')
                                    ->money('TRY'),
                                Infolists\Components\TextEntry::make('total')
                                    ->label('Toplam')
                                    ->money('TRY'),
                            ])->columns(4),
                    ]),
                
                Infolists\Components\Section::make('Fiyat Özeti')
                    ->schema([
                        Infolists\Components\TextEntry::make('subtotal')
                            ->label('Ara Toplam')
                            ->money('TRY'),
                        Infolists\Components\TextEntry::make('shipping_cost')
                            ->label('Kargo Ücreti')
                            ->money('TRY'),
                        Infolists\Components\TextEntry::make('total')
                            ->label('Genel Toplam')
                            ->money('TRY')
                            ->weight('bold'),
                    ])->columns(3),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}