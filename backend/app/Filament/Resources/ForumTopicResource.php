<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ForumTopicResource\Pages;
use App\Models\ForumTopic;
use App\Models\Category;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ForumTopicResource extends Resource
{
    protected static ?string $model = ForumTopic::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Forum Konuları';
    protected static ?string $modelLabel = 'Forum Konusu';
    protected static ?string $pluralModelLabel = 'Forum Konuları';
    protected static ?string $navigationGroup = 'Forum & Topluluk';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Konu Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $context, $state, callable $set) => 
                                $context === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->label('URL Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ForumTopic::class, 'slug', ignoreRecord: true),

                        Forms\Components\Select::make('forum_category_id')
                            ->label('Forum Kategorisi')
                            ->options(\App\Models\ForumCategory::pluck('name', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('category_id')
                            ->label('Genel Kategori')
                            ->options(Category::pluck('name', 'id'))
                            ->searchable(),

                        Forms\Components\Select::make('user_id')
                            ->label('Kullanıcı')
                            ->options(User::pluck('name', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\RichEditor::make('content')
                            ->label('İçerik')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Ayarlar')
                    ->schema([
                        Forms\Components\Toggle::make('is_pinned')
                            ->label('Sabitlenmiş'),

                        Forms\Components\Toggle::make('is_locked')
                            ->label('Kilitli'),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Öne Çıkan'),

                        Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Pasif',
                                'pending' => 'Beklemede'
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('İstatistikler')
                    ->schema([
                        Forms\Components\TextInput::make('views_count')
                            ->label('Görüntüleme Sayısı')
                            ->numeric()
                            ->default(0),

                        Forms\Components\TextInput::make('replies_count')
                            ->label('Yanıt Sayısı')
                            ->numeric()
                            ->default(0),

                        Forms\Components\TextInput::make('likes_count')
                            ->label('Beğeni Sayısı')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(3)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('forumCategory.name')
                    ->label('Forum Kategorisi')
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Genel Kategori')
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('replies_count')
                    ->label('Yanıt')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Görüntüleme')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format($state)),

                Tables\Columns\TextColumn::make('likes_count')
                    ->label('Beğeni')
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                Tables\Columns\IconColumn::make('is_pinned')
                    ->label('Sabit')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_locked')
                    ->label('Kilitli')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Öne Çıkan')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Durum')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Pasif',
                        'pending' => 'Beklemede'
                    ])
                    ->selectablePlaceholder(false),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('forum_category_id')
                    ->label('Forum Kategorisi')
                    ->options(\App\Models\ForumCategory::pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Genel Kategori')
                    ->options(Category::pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Kullanıcı')
                    ->options(User::pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Pasif',
                        'pending' => 'Beklemede'
                    ]),

                Tables\Filters\Filter::make('is_pinned')
                    ->label('Sabitlenmiş')
                    ->query(fn (Builder $query): Builder => $query->where('is_pinned', true)),

                Tables\Filters\Filter::make('is_featured')
                    ->label('Öne Çıkan')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
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
            'index' => Pages\ListForumTopics::route('/'),
            'create' => Pages\CreateForumTopic::route('/create'),
            'view' => Pages\ViewForumTopic::route('/{record}'),
            'edit' => Pages\EditForumTopic::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}