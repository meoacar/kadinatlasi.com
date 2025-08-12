<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnershipResource\Pages;
use App\Models\Partnership;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartnershipResource extends Resource
{
    protected static ?string $model = Partnership::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'İşbirlikleri';
    protected static ?string $modelLabel = 'İşbirliği';
    protected static ?string $pluralModelLabel = 'İşbirlikleri';
    protected static ?string $navigationGroup = 'Premium Üyelik';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->label('Şirket Adı')
                    ->required(),
                Forms\Components\TextInput::make('contact_person')
                    ->label('İletişim Kişisi')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('E-posta')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->label('Telefon'),
                Forms\Components\Select::make('partnership_type')
                    ->label('İşbirliği Tipi')
                    ->options([
                        'influencer' => 'Influencer',
                        'brand' => 'Marka',
                        'sponsor' => 'Sponsor',
                        'affiliate' => 'Affiliate'
                    ])
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Açıklama')
                    ->required(),
                Forms\Components\TextInput::make('budget')
                    ->label('Bütçe (₺)')
                    ->numeric(),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Başlangıç Tarihi')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Bitiş Tarihi'),
                Forms\Components\Select::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Beklemede',
                        'active' => 'Aktif',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal'
                    ])
                    ->required(),
                Forms\Components\TextInput::make('commission_rate')
                    ->label('Komisyon Oranı (%)')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Şirket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('partnership_type')
                    ->label('Tip')
                    ->badge(),
                Tables\Columns\TextColumn::make('budget')
                    ->label('Bütçe')
                    ->money('TRY'),
                Tables\Columns\TextColumn::make('total_revenue')
                    ->label('Toplam Gelir')
                    ->money('TRY'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'active' => 'success',
                        'completed' => 'info',
                        'cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Başlangıç')
                    ->date(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerships::route('/'),
            'create' => Pages\CreatePartnership::route('/create'),
            'edit' => Pages\EditPartnership::route('/{record}/edit'),
        ];
    }
}