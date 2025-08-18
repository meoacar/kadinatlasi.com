<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Filament\Resources\SubscriptionResource\RelationManagers;
use App\Models\Subscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Abonelikler';
    protected static ?string $modelLabel = 'Abonelik';
    protected static ?string $pluralModelLabel = 'Abonelikler';
    protected static ?string $navigationGroup = 'Premium Üyelik';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Kullanıcı')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                    
                Forms\Components\Select::make('membership_plan_id')
                    ->label('Üyelik Planı')
                    ->relationship('membershipPlan', 'name')
                    ->required(),
                    
                Forms\Components\Select::make('status')
                    ->label('Durum')
                    ->options([
                        'active' => 'Aktif',
                        'expired' => 'Süresi Dolmuş',
                        'cancelled' => 'İptal Edilmiş',
                        'pending' => 'Beklemede',
                    ])
                    ->required(),
                    
                Forms\Components\DateTimePicker::make('starts_at')
                    ->label('Başlangıç Tarihi')
                    ->required(),
                    
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('Bitiş Tarihi')
                    ->required(),
                    
                Forms\Components\DateTimePicker::make('cancelled_at')
                    ->label('İptal Tarihi'),
                    
                Forms\Components\KeyValue::make('metadata')
                    ->label('Ek Bilgiler'),
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
                    
                Tables\Columns\TextColumn::make('membershipPlan.name')
                    ->label('Plan')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'expired' => 'danger',
                        'cancelled' => 'warning',
                        'pending' => 'secondary',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'expired' => 'Süresi Dolmuş',
                        'cancelled' => 'İptal Edilmiş',
                        'pending' => 'Beklemede',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Başlangıç')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Bitiş')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('remaining_days')
                    ->label('Kalan Gün')
                    ->getStateUsing(function ($record) {
                        if ($record->status !== 'active') return '-';
                        return $record->remaining_days . ' gün';
                    }),
                    
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
                        'active' => 'Aktif',
                        'expired' => 'Süresi Dolmuş',
                        'cancelled' => 'İptal Edilmiş',
                        'pending' => 'Beklemede',
                    ]),
                    
                Tables\Filters\SelectFilter::make('membership_plan_id')
                    ->label('Plan')
                    ->relationship('membershipPlan', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('cancel')
                    ->label('İptal Et')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'active')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'cancelled',
                            'cancelled_at' => now(),
                        ]);
                        $record->user->update([
                            'membership_type' => 'normal',
                            'membership_expires_at' => null,
                        ]);
                    }),
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
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
