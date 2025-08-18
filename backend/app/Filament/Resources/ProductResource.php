<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\ProductCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Ürünler';
    protected static ?string $modelLabel = 'Ürün';
    protected static ?string $pluralModelLabel = 'Ürünler';
    protected static ?string $navigationGroup = 'E-Ticaret';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Ürün Bilgileri')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Genel Bilgiler')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Ürün Adı')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')
                                    ->options(ProductCategory::active()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable(),

                                Forms\Components\TextInput::make('sku')
                                    ->label('Ürün Kodu (SKU)')
                                    ->maxLength(100)
                                    ->unique(Product::class, 'sku', ignoreRecord: true),

                                Forms\Components\TextInput::make('brand')
                                    ->label('Marka')
                                    ->maxLength(100),

                                Forms\Components\Select::make('gender')
                                    ->label('Cinsiyet')
                                    ->options([
                                        'kadın' => 'Kadın',
                                        'erkek' => 'Erkek', 
                                        'unisex' => 'Unisex',
                                        'çocuk-kız' => 'Çocuk (Kız)',
                                        'çocuk-erkek' => 'Çocuk (Erkek)',
                                        'bebek-kız' => 'Bebek (Kız)',
                                        'bebek-erkek' => 'Bebek (Erkek)',
                                        'bebek-unisex' => 'Bebek (Unisex)'
                                    ])
                                    ->searchable(),

                                Forms\Components\Select::make('status')
                                    ->label('Durum')
                                    ->options([
                                        'active' => 'Aktif',
                                        'inactive' => 'Pasif',
                                        'draft' => 'Taslak'
                                    ])
                                    ->default('active')
                                    ->required(),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Öne Çıkan Ürün'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Açıklama')
                            ->schema([
                                Forms\Components\Textarea::make('short_description')
                                    ->label('Kısa Açıklama')
                                    ->maxLength(500)
                                    ->rows(3),

                                Forms\Components\RichEditor::make('description')
                                    ->label('Detaylı Açıklama')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Fiyat & Stok')
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label('Normal Fiyat')
                                    ->numeric()
                                    ->prefix('₺')
                                    ->required(),

                                Forms\Components\TextInput::make('sale_price')
                                    ->label('İndirimli Fiyat')
                                    ->numeric()
                                    ->prefix('₺')
                                    ->lt('price'),

                                Forms\Components\Toggle::make('manage_stock')
                                    ->label('Stok Takibi Yap')
                                    ->live(),

                                Forms\Components\TextInput::make('stock_quantity')
                                    ->label('Stok Miktarı')
                                    ->numeric()
                                    ->default(0)
                                    ->visible(fn (callable $get) => $get('manage_stock')),

                                Forms\Components\Toggle::make('in_stock')
                                    ->label('Stokta Var')
                                    ->default(true),
                            ]),

                        Forms\Components\Tabs\Tab::make('Resimler')
                            ->schema([
                                Forms\Components\FileUpload::make('images')
                                    ->label('Ürün Resimleri')
                                    ->multiple()
                                    ->image()
                                    ->maxFiles(10)
                                    ->directory('products')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->reorderable()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '1:1',
                                        '4:3',
                                        '16:9',
                                    ])
                                    ->maxSize(5120)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->helperText('Maksimum 10 resim, her biri en fazla 5MB')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Fiziksel Özellikler')
                            ->schema([
                                Forms\Components\Section::make('Temel Özellikler')
                                    ->schema([
                                        Forms\Components\TextInput::make('weight')
                                            ->label('Ağırlık (kg)')
                                            ->numeric()
                                            ->step(0.01),

                                        Forms\Components\TextInput::make('dimensions_length')
                                            ->label('Uzunluk (cm)')
                                            ->numeric(),
                                        Forms\Components\TextInput::make('dimensions_width')
                                            ->label('Genişlik (cm)')
                                            ->numeric(),
                                        Forms\Components\TextInput::make('dimensions_height')
                                            ->label('Yükseklik (cm)')
                                            ->numeric(),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Giyim Özellikleri')
                                    ->schema([
                                        Forms\Components\Repeater::make('variants')
                                            ->label('Beden ve Renk Varyantları')
                                            ->relationship()
                                            ->schema([
                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\Select::make('size')
                                                            ->label('Beden')
                                                            ->options([
                                                                'XS' => 'XS',
                                                                'S' => 'S', 
                                                                'M' => 'M',
                                                                'L' => 'L',
                                                                'XL' => 'XL',
                                                                'XXL' => 'XXL',
                                                                '34' => '34',
                                                                '36' => '36',
                                                                '38' => '38',
                                                                '40' => '40',
                                                                '42' => '42',
                                                                '44' => '44',
                                                                '46' => '46',
                                                            ])
                                                            ->required()
                                                            ->searchable(),
                                                            
                                                        Forms\Components\TextInput::make('color')
                                                            ->label('Renk')
                                                            ->placeholder('Örn: Siyah, Beyaz, Kırmızı'),
                                                    ]),
                                                    
                                                Forms\Components\Grid::make(3)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('stock_quantity')
                                                            ->label('Stok')
                                                            ->numeric()
                                                            ->default(0)
                                                            ->required()
                                                            ->minValue(0),
                                                            
                                                        Forms\Components\TextInput::make('price_adjustment')
                                                            ->label('Fiyat Farkı')
                                                            ->numeric()
                                                            ->default(0)
                                                            ->prefix('₺')
                                                            ->helperText('+/- fiyat farkı'),
                                                            
                                                        Forms\Components\Toggle::make('is_active')
                                                            ->label('Aktif')
                                                            ->default(true)
                                                            ->inline(false),
                                                    ]),
                                                    
                                                Forms\Components\TextInput::make('sku')
                                                    ->label('Varyant SKU')
                                                    ->placeholder('Otomatik oluşturulacak')
                                                    ->helperText('Boş bırakılırsa otomatik oluşturulur'),
                                            ])
                                            ->collapsed()
                                            ->cloneable()
                                            ->reorderable()
                                            ->defaultItems(0)
                                            ->addActionLabel('Yeni Varyant Ekle'),

                                        Forms\Components\TextInput::make('color')
                                            ->label('Renk'),

                                        Forms\Components\TextInput::make('material')
                                            ->label('Malzeme/Kumaş'),

                                        Forms\Components\Select::make('pattern')
                                            ->label('Desen')
                                            ->options([
                                                'düz' => 'Düz',
                                                'çizgili' => 'Çizgili',
                                                'kareli' => 'Kareli',
                                                'çiçekli' => 'Çiçekli',
                                                'desenli' => 'Desenli',
                                                'noktalı' => 'Noktalı'
                                            ]),

                                        Forms\Components\Select::make('fit_type')
                                            ->label('Kesim')
                                            ->options([
                                                'slim' => 'Slim Fit',
                                                'regular' => 'Regular Fit',
                                                'oversized' => 'Oversized',
                                                'loose' => 'Bol Kesim'
                                            ]),

                                        Forms\Components\Select::make('sleeve_type')
                                            ->label('Kol Tipi')
                                            ->options([
                                                'uzun' => 'Uzun Kol',
                                                'kısa' => 'Kısa Kol',
                                                'kolsuz' => 'Kolsuz',
                                                '3/4' => '3/4 Kol'
                                            ]),

                                        Forms\Components\Select::make('neckline')
                                            ->label('Yaka Tipi')
                                            ->options([
                                                'bisiklet' => 'Bisiklet Yaka',
                                                'v-yaka' => 'V Yaka',
                                                'polo' => 'Polo Yaka',
                                                'gömlek' => 'Gömlek Yaka',
                                                'boğazlı' => 'Boğazlı'
                                            ]),
                                    ])
                                    ->columns(3),

                                Forms\Components\Section::make('Ayakkabı Özellikleri')
                                    ->schema([
                                        Forms\Components\Select::make('shoe_size')
                                            ->label('Ayakkabı Numarası')
                                            ->options(array_combine(
                                                range(35, 45),
                                                range(35, 45)
                                            )),

                                        Forms\Components\Select::make('heel_height')
                                            ->label('Topuk Yüksekliği')
                                            ->options([
                                                'düz' => 'Düz (0-1cm)',
                                                'alçak' => 'Alçak (2-4cm)',
                                                'orta' => 'Orta (5-7cm)',
                                                'yüksek' => 'Yüksek (8cm+)'
                                            ]),

                                        Forms\Components\Select::make('shoe_type')
                                            ->label('Ayakkabı Tipi')
                                            ->options([
                                                'spor' => 'Spor Ayakkabı',
                                                'klasik' => 'Klasik Ayakkabı',
                                                'bot' => 'Bot',
                                                'sandalet' => 'Sandalet',
                                                'terlik' => 'Terlik'
                                            ]),
                                    ])
                                    ->columns(3),

                                Forms\Components\Section::make('Aksesuar Özellikleri')
                                    ->schema([
                                        Forms\Components\Select::make('accessory_type')
                                            ->label('Aksesuar Tipi')
                                            ->options([
                                                'çanta' => 'Çanta',
                                                'takı' => 'Takı',
                                                'şapka' => 'Şapka',
                                                'kemer' => 'Kemer',
                                                'gözlük' => 'Gözlük'
                                            ]),

                                        Forms\Components\Select::make('closure_type')
                                            ->label('Kapama Tipi')
                                            ->options([
                                                'fermuar' => 'Fermuar',
                                                'düğme' => 'Düğme',
                                                'cırt' => 'Cırt Cırt',
                                                'mıknatıs' => 'Mıknatıs'
                                            ]),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Kozmetik Özellikleri')
                                    ->schema([
                                        Forms\Components\Select::make('skin_type')
                                            ->label('Cilt Tipi')
                                            ->options([
                                                'yağlı' => 'Yağlı Cilt',
                                                'kuru' => 'Kuru Cilt',
                                                'karma' => 'Karma Cilt',
                                                'hassas' => 'Hassas Cilt',
                                                'normal' => 'Normal Cilt'
                                            ]),

                                        Forms\Components\TextInput::make('shade')
                                            ->label('Ton/Renk Numarası'),

                                        Forms\Components\TextInput::make('volume')
                                            ->label('Hacim (ml/gr)'),

                                        Forms\Components\DatePicker::make('expiry_date')
                                            ->label('Son Kullanma Tarihi'),

                                        Forms\Components\Toggle::make('is_organic')
                                            ->label('Organik'),

                                        Forms\Components\Toggle::make('is_vegan')
                                            ->label('Vegan'),

                                        Forms\Components\Toggle::make('is_cruelty_free')
                                            ->label('Hayvan Deneyi Yapılmamış'),
                                    ])
                                    ->columns(3),

                                Forms\Components\Section::make('Genel Özellikler')
                                    ->schema([
                                        Forms\Components\Select::make('age_group')
                                            ->label('Yaş Grubu')
                                            ->options([
                                                'bebek' => 'Bebek (0-2 yaş)',
                                                'çocuk' => 'Çocuk (3-12 yaş)',
                                                'genç' => 'Genç (13-17 yaş)',
                                                'yetişkin' => 'Yetişkin (18+ yaş)'
                                            ]),

                                        Forms\Components\Select::make('season')
                                            ->label('Mevsim')
                                            ->options([
                                                'yaz' => 'Yaz',
                                                'kış' => 'Kış',
                                                'sonbahar' => 'Sonbahar',
                                                'ilkbahar' => 'İlkbahar',
                                                'dört-mevsim' => 'Dört Mevsim'
                                            ]),

                                        Forms\Components\Select::make('occasion')
                                            ->label('Kullanım Alanı')
                                            ->options([
                                                'günlük' => 'Günlük',
                                                'özel' => 'Özel Günler',
                                                'spor' => 'Spor',
                                                'iş' => 'İş/Ofis',
                                                'gece' => 'Gece'
                                            ]),

                                        Forms\Components\Textarea::make('care_instructions_text')
                                            ->label('Bakım Talimatları')
                                            ->placeholder('30°C yıkama, ütü yasak, vb.')
                                            ->rows(3),

                                        Forms\Components\Textarea::make('ingredients_text')
                                            ->label('İçerik/Malzeme Listesi')
                                            ->placeholder('Pamuk, polyester, vb.')
                                            ->rows(3),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Meta Başlık')
                                    ->maxLength(60),

                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Açıklama')
                                    ->maxLength(160)
                                    ->rows(3),

                                Forms\Components\TextInput::make('seo_keywords_text')
                                    ->label('SEO Anahtar Kelimeler')
                                    ->placeholder('anahtar, kelime, listesi'),

                                Forms\Components\TextInput::make('tags_text')
                                    ->label('Ürün Etiketleri')
                                    ->placeholder('etiket1, etiket2, etiket3'),
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Resim')
                    ->circular()
                    ->stacked()
                    ->limit(1)
                    ->limitedRemainingText(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Ürün Adı')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('gender')
                    ->label('Cinsiyet')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'kadın' => 'pink',
                        'erkek' => 'blue',
                        'unisex' => 'gray',
                        default => 'warning',
                    }),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Fiyat')
                    ->money('TRY')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sale_price')
                    ->label('İndirimli Fiyat')
                    ->money('TRY')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stok')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state > 10 => 'success',
                        $state > 0 => 'warning',
                        default => 'danger',
                    }),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Öne Çıkan')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Durum')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Pasif',
                        'draft' => 'Taslak'
                    ])
                    ->selectablePlaceholder(false),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->options(ProductCategory::active()->pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Pasif',
                        'draft' => 'Taslak'
                    ]),

                Tables\Filters\Filter::make('is_featured')
                    ->label('Öne Çıkan')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),

                Tables\Filters\Filter::make('low_stock')
                    ->label('Düşük Stok')
                    ->query(fn (Builder $query): Builder => $query->where('stock_quantity', '<=', 5)),

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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
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