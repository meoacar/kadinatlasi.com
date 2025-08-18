<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembershipPlanResource\Pages;
use App\Models\MembershipPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MembershipPlanResource extends Resource
{
    protected static ?string $model = MembershipPlan::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Üyelik Planları';
    protected static ?string $modelLabel = 'Üyelik Planı';
    protected static ?string $pluralModelLabel = 'Üyelik Planları';
    protected static ?string $navigationGroup = 'Premium Üyelik';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Plan Adı')
                    ->required(),
                    
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required(),
                    
                Forms\Components\Textarea::make('description')
                    ->label('Açıklama')
                    ->required(),
                    
                Forms\Components\TextInput::make('price')
                    ->label('Fiyat')
                    ->numeric()
                    ->required(),
                    
                Forms\Components\TextInput::make('duration_days')
                    ->label('Süre (Gün)')
                    ->numeric()
                    ->required(),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Plan Adı')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('price')
                    ->label('Fiyat')
                    ->money('TRY'),
                    
                Tables\Columns\TextColumn::make('duration_days')
                    ->label('Süre')
                    ->suffix(' gün'),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembershipPlans::route('/'),
            'create' => Pages\CreateMembershipPlan::route('/create'),
            'edit' => Pages\EditMembershipPlan::route('/{record}/edit'),
        ];
    }
}