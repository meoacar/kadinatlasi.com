<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Ödemeler';
    protected static ?string $modelLabel = 'Ödeme';
    protected static ?string $pluralModelLabel = 'Ödemeler';
    protected static ?string $navigationGroup = 'Premium Üyelik';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Kullanıcı')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                    
                Forms\Components\Select::make('subscription_id')
                    ->label('Abonelik')
                    ->relationship('subscription', 'id')
                    ->searchable(),
                    
                Forms\Components\TextInput::make('payment_id')
                    ->label('Ödeme ID')
                    ->required(),
                    
                Forms\Components\TextInput::make('amount')
                    ->label('Tutar')
                    ->numeric()
                    ->prefix('₺')
                    ->required(),
                    
                Forms\Components\Select::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Beklemede',
                        'success' => 'Başarılı',
                        'failed' => 'Başarısız',
                        'cancelled' => 'İptal Edildi',
                        'refunded' => 'İade Edildi',
                    ])
                    ->required(),
                    
                Forms\Components\Select::make('payment_method')
                    ->label('Ödeme Yöntemi')
                    ->options([
                        'credit_card' => 'Kredi Kartı',
                        'debit_card' => 'Banka Kartı',
                        'bank_transfer' => 'Banka Havalesi',
                    ])
                    ->required(),
                    
                Forms\Components\DateTimePicker::make('paid_at')
                    ->label('Ödeme Tarihi'),
                    
                Forms\Components\Textarea::make('failure_reason')
                    ->label('Hata Sebebi')
                    ->rows(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('payment_id')
                    ->label('Ödeme ID')
                    ->searchable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('amount')
                    ->label('Tutar')
                    ->money('TRY')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'failed' => 'danger',
                        'pending' => 'warning',
                        'cancelled' => 'secondary',
                        'refunded' => 'info',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Beklemede',
                        'success' => 'Başarılı',
                        'failed' => 'Başarısız',
                        'cancelled' => 'İptal Edildi',
                        'refunded' => 'İade Edildi',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Ödeme Yöntemi')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'credit_card' => 'Kredi Kartı',
                        'debit_card' => 'Banka Kartı',
                        'bank_transfer' => 'Banka Havalesi',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('subscription.membershipPlan.name')
                    ->label('Plan')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Ödeme Tarihi')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Beklemede',
                        'success' => 'Başarılı',
                        'failed' => 'Başarısız',
                        'cancelled' => 'İptal Edildi',
                        'refunded' => 'İade Edildi',
                    ]),
                    
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Ödeme Yöntemi')
                    ->options([
                        'credit_card' => 'Kredi Kartı',
                        'debit_card' => 'Banka Kartı',
                        'bank_transfer' => 'Banka Havalesi',
                    ]),
                    
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Başlangıç Tarihi'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Bitiş Tarihi'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
