<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ForumGroupResource\Pages;
use App\Filament\Resources\ForumGroupResource\RelationManagers;
use App\Models\ForumGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ForumGroupResource extends Resource
{
    protected static ?string $model = ForumGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Forum Grupları';
    protected static ?string $modelLabel = 'Forum Grubu';
    protected static ?string $pluralModelLabel = 'Forum Grupları';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Forum & Topluluk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Grup Adı')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Açıklama')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('icon')
                    ->label('İkon'),
                Forms\Components\TextInput::make('color')
                    ->label('Renk')
                    ->required(),
                Forms\Components\Toggle::make('is_private')
                    ->label('Özel Grup')
                    ->required(),
                Forms\Components\Toggle::make('requires_approval')
                    ->label('Onay Gerektirir')
                    ->required(),
                Forms\Components\Select::make('creator_id')
                    ->label('Oluşturan')
                    ->relationship('creator', 'name')
                    ->required(),
                Forms\Components\TextInput::make('member_count')
                    ->label('Üye Sayısı')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Grup Adı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('icon')
                    ->label('İkon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->label('Renk')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_private')
                    ->label('Özel')
                    ->boolean(),
                Tables\Columns\IconColumn::make('requires_approval')
                    ->label('Onay Gerekli')
                    ->boolean(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Oluşturan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('member_count')
                    ->label('Üye Sayısı')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Güncellenme')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListForumGroups::route('/'),
            'create' => Pages\CreateForumGroup::route('/create'),
            'edit' => Pages\EditForumGroup::route('/{record}/edit'),
        ];
    }
}
