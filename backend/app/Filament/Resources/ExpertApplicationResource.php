<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpertApplicationResource\Pages;
use App\Models\ExpertApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExpertApplicationResource extends Resource
{
    protected static ?string $model = ExpertApplication::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Uzman Başvuruları';
    protected static ?string $modelLabel = 'Uzman Başvurusu';
    protected static ?string $pluralModelLabel = 'Uzman Başvuruları';
    protected static ?string $navigationGroup = 'Forum & Topluluk';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Başvuru Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Ad Soyad')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('E-posta')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('profession')
                            ->label('Meslek')
                            ->options([
                                'doktor' => 'Doktor',
                                'psikolog' => 'Psikolog',
                                'diyetisyen' => 'Diyetisyen',
                                'ebe' => 'Ebe',
                                'fizyoterapist' => 'Fizyoterapist',
                                'avukat' => 'Avukat',
                                'egitmen' => 'Eğitmen/Koç',
                                'diger' => 'Diğer'
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('experience')
                            ->label('Deneyim Süresi')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detaylar')
                    ->schema([
                        Forms\Components\Textarea::make('specialization')
                            ->label('Uzmanlık Alanı')
                            ->required()
                            ->rows(3),

                        Forms\Components\Textarea::make('motivation')
                            ->label('Motivasyon')
                            ->required()
                            ->rows(3),

                        Forms\Components\FileUpload::make('certificate_path')
                            ->label('Sertifika/Diploma')
                            ->disk('public')
                            ->directory('expert-certificates')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->maxSize(5120),

                        Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->options([
                                'pending' => 'Beklemede',
                                'approved' => 'Onaylandı',
                                'rejected' => 'Reddedildi'
                            ])
                            ->default('pending')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('profession')
                    ->label('Meslek')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'doktor' => 'success',
                        'psikolog' => 'info',
                        'diyetisyen' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('experience')
                    ->label('Deneyim')
                    ->sortable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Beklemede',
                        'approved' => 'Onaylandı',
                        'rejected' => 'Reddedildi'
                    ])
                    ->selectablePlaceholder(false),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Başvuru Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Beklemede',
                        'approved' => 'Onaylandı',
                        'rejected' => 'Reddedildi'
                    ]),

                Tables\Filters\SelectFilter::make('profession')
                    ->label('Meslek')
                    ->options([
                        'doktor' => 'Doktor',
                        'psikolog' => 'Psikolog',
                        'diyetisyen' => 'Diyetisyen',
                        'ebe' => 'Ebe',
                        'fizyoterapist' => 'Fizyoterapist',
                        'avukat' => 'Avukat',
                        'egitmen' => 'Eğitmen/Koç',
                        'diger' => 'Diğer'
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpertApplications::route('/'),
            'view' => Pages\ViewExpertApplication::route('/{record}'),
            'edit' => Pages\EditExpertApplication::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }
}