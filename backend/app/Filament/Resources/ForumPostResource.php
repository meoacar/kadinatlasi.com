<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ForumPostResource\Pages;
use App\Models\ForumPost;
use App\Models\ForumTopic;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ForumPostResource extends Resource
{
    protected static ?string $model = ForumPost::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationLabel = 'Forum Mesajları';
    protected static ?string $modelLabel = 'Forum Mesajı';
    protected static ?string $pluralModelLabel = 'Forum Mesajları';
    protected static ?string $navigationGroup = 'Forum & Topluluk';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Mesaj Bilgileri')
                    ->schema([
                        Forms\Components\Select::make('forum_topic_id')
                            ->label('Konu')
                            ->options(ForumTopic::with('forumCategory')->get()->pluck('title', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('user_id')
                            ->label('Kullanıcı')
                            ->options(User::pluck('name', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('parent_id')
                            ->label('Yanıtlanan Mesaj')
                            ->options(ForumPost::with('user')->get()->pluck('user.name', 'id'))
                            ->searchable(),

                        Forms\Components\RichEditor::make('content')
                            ->label('İçerik')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Ayarlar')
                    ->schema([
                        Forms\Components\Toggle::make('is_expert_answer')
                            ->label('Uzman Yanıtı'),

                        Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Pasif',
                                'pending' => 'Beklemede'
                            ])
                            ->default('active')
                            ->required(),

                        Forms\Components\TextInput::make('likes_count')
                            ->label('Beğeni Sayısı')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('topic.title')
                    ->label('Konu')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('content')
                    ->label('İçerik')
                    ->html()
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('likes_count')
                    ->label('Beğeni')
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                Tables\Columns\IconColumn::make('is_expert_answer')
                    ->label('Uzman')
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
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('forum_topic_id')
                    ->label('Konu')
                    ->options(ForumTopic::pluck('title', 'id')),

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

                Tables\Filters\Filter::make('is_expert_answer')
                    ->label('Uzman Yanıtları')
                    ->query(fn (Builder $query): Builder => $query->where('is_expert_answer', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListForumPosts::route('/'),
            'create' => Pages\CreateForumPost::route('/create'),
            'edit' => Pages\EditForumPost::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}