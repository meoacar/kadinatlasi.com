<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProductService
{
    /**
     * Filtrelere göre ürünleri getir
     */
    public function getProductsWithFilters(array $filters): LengthAwarePaginator
    {
        $query = Product::with(['category']);

        // Arama filtresi
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Durum filtresi
        if (!empty($filters['status'])) {
            $isActive = $filters['status'] === 'active';
            $query->where('is_active', $isActive);
        }

        // Kategori filtresi
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Fiyat filtreleri
        if (!empty($filters['price_min'])) {
            $query->where('price', '>=', $filters['price_min']);
        }

        if (!empty($filters['price_max'])) {
            $query->where('price', '<=', $filters['price_max']);
        }

        // Stok durumu filtresi
        if (!empty($filters['stock_status'])) {
            switch ($filters['stock_status']) {
                case 'in_stock':
                    $query->where('stock_quantity', '>', 10);
                    break;
                case 'low_stock':
                    $query->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10);
                    break;
                case 'out_of_stock':
                    $query->where('stock_quantity', '<=', 0);
                    break;
            }
        }

        // Tarih filtreleri
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Sıralama
        $sortField = $filters['sort'] ?? 'created_at';
        $sortDirection = $filters['direction'] ?? 'desc';
        
        $allowedSortFields = ['name', 'price', 'stock_quantity', 'created_at', 'is_active'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Sayfalama
        $perPage = min($filters['per_page'] ?? 15, 100);
        
        return $query->paginate($perPage);
    }

    /**
     * Yeni ürün oluştur
     */
    public function createProduct(array $data): Product
    {
        DB::beginTransaction();
        
        try {
            // Slug benzersizliğini kontrol et
            $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['name']);
            
            // SKU benzersizliğini kontrol et
            if (empty($data['sku'])) {
                $data['sku'] = $this->generateUniqueSku();
            }
            
            $product = Product::create($data);
            
            // SEO verilerini otomatik oluştur
            $this->generateSeoData($product);
            
            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Ürün güncelle
     */
    public function updateProduct(Product $product, array $data): Product
    {
        DB::beginTransaction();
        
        try {
            // Slug benzersizliğini kontrol et (mevcut ürün hariç)
            if (isset($data['slug'])) {
                $data['slug'] = $this->generateUniqueSlug($data['slug'], $product->id);
            }
            
            $product->update($data);
            
            // SEO verilerini güncelle
            if (empty($product->meta_title) || empty($product->meta_description)) {
                $this->generateSeoData($product);
            }
            
            DB::commit();
            return $product->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Çoklu resim yükleme işlemi
     */
    public function handleImageUploads(Product $product, array $files): array
    {
        $uploadedImages = [];
        $existingImages = $product->images ?? [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $imagePath = $this->uploadProductImage($file);
                $uploadedImages[] = $imagePath;
            }
        }
        
        // Mevcut resimlerle birleştir
        $allImages = array_merge($existingImages, $uploadedImages);
        
        // Maksimum 10 resim sınırı
        $allImages = array_slice($allImages, 0, 10);
        
        $product->update(['images' => $allImages]);
        
        return $uploadedImages;
    }

    /**
     * Ürün resimlerini sil
     */
    public function deleteProductImages(Product $product): void
    {
        if ($product->images) {
            foreach ($product->images as $imagePath) {
                Storage::disk('public')->delete($imagePath);
                // Thumbnail'i de sil
                $thumbnailPath = str_replace('products/', 'products/thumbnails/', $imagePath);
                Storage::disk('public')->delete($thumbnailPath);
            }
        }
    }

    /**
     * Ürün istatistikleri
     */
    public function getProductStats(Product $product = null): array
    {
        if ($product) {
            // Belirli bir ürün için istatistikler
            return [
                'views' => $product->views ?? 0,
                'orders_count' => $this->getProductOrdersCount($product),
                'revenue' => $this->getProductRevenue($product),
                'stock_value' => $product->price * $product->stock_quantity,
                'last_ordered' => $this->getLastOrderDate($product),
                'conversion_rate' => $this->calculateConversionRate($product),
            ];
        } else {
            // Genel ürün istatistikleri
            return [
                'overview' => [
                    'total_products' => Product::count(),
                    'active_products' => Product::where('is_active', true)->count(),
                    'featured_products' => Product::where('is_featured', true)->count(),
                    'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
                    'low_stock' => Product::where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10)->count(),
                    'total_stock_value' => Product::selectRaw('SUM(price * stock_quantity)')->value('SUM(price * stock_quantity)') ?? 0,
                    'avg_price' => Product::avg('price') ?? 0,
                ],
                'categories' => $this->getCategoryStats(),
                'price_ranges' => $this->getPriceRangeStats(),
                'stock_alerts' => $this->getStockAlerts(),
                'top_products' => $this->getTopProducts(),
            ];
        }
    }

    /**
     * Ürünleri dışa aktar
     */
    public function exportProducts(array $filters = []): array
    {
        $products = $this->getProductsWithFilters(array_merge($filters, ['per_page' => 10000]));
        
        return $products->map(function ($product) {
            return [
                'ID' => $product->id,
                'Ürün Adı' => $product->name,
                'SKU' => $product->sku ?? 'N/A',
                'Kategori' => $product->category->name ?? 'Kategori Yok',
                'Fiyat' => number_format($product->price, 2),
                'İndirimli Fiyat' => $product->sale_price ? number_format($product->sale_price, 2) : '-',
                'Stok' => $product->stock_quantity,
                'Durum' => $product->is_active ? 'Aktif' : 'Pasif',
                'Öne Çıkan' => $product->is_featured ? 'Evet' : 'Hayır',
                'Oluşturma Tarihi' => $product->created_at->format('d.m.Y H:i'),
            ];
        })->toArray();
    }

    /**
     * Stok değişikliğini logla
     */
    public function logStockChange(Product $product, int $oldStock, int $newStock, ?string $note = null): void
    {
        \Log::info('Product stock changed', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'difference' => $newStock - $oldStock,
            'note' => $note,
            'changed_by' => auth()->id(),
            'timestamp' => now(),
        ]);
    }

    // Private helper methods

    /**
     * Benzersiz slug oluştur
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        
        while (true) {
            $query = Product::where('slug', $slug);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Benzersiz SKU oluştur
     */
    private function generateUniqueSku(): string
    {
        do {
            $sku = 'PRD-' . strtoupper(Str::random(8));
        } while (Product::where('sku', $sku)->exists());
        
        return $sku;
    }

    /**
     * SEO verilerini otomatik oluştur
     */
    private function generateSeoData(Product $product): void
    {
        $updates = [];
        
        // Meta title oluştur
        if (empty($product->meta_title)) {
            $updates['meta_title'] = Str::limit($product->name, 55);
        }
        
        // Meta description oluştur
        if (empty($product->meta_description)) {
            $description = $product->short_description ?? strip_tags($product->description ?? '');
            $updates['meta_description'] = Str::limit($description, 155);
        }
        
        if (!empty($updates)) {
            $product->update($updates);
        }
    }

    /**
     * Ürün resmi yükle
     */
    private function uploadProductImage(UploadedFile $file): string
    {
        // Dosya adını oluştur
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = 'products/' . $filename;
        
        // Görseli yeniden boyutlandır ve optimize et
        $image = Image::make($file);
        
        // Ana görsel (max 1200px genişlik)
        $image->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        // Kaliteyi optimize et
        $image->encode('jpg', 85);
        
        // Storage'a kaydet
        Storage::disk('public')->put($path, $image->stream());
        
        // Thumbnail oluştur (300x300)
        $thumbnailPath = 'products/thumbnails/' . $filename;
        $thumbnail = Image::make($file);
        $thumbnail->fit(300, 300);
        $thumbnail->encode('jpg', 80);
        Storage::disk('public')->put($thumbnailPath, $thumbnail->stream());
        
        return $path;
    }

    /**
     * Kategori istatistikleri
     */
    private function getCategoryStats(): array
    {
        return ProductCategory::withCount(['products'])
            ->orderBy('products_count', 'desc')
            ->take(10)
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'products_count' => $category->products_count,
                    'percentage' => Product::count() > 0 ? 
                        round(($category->products_count / Product::count()) * 100, 1) : 0
                ];
            })
            ->toArray();
    }

    /**
     * Fiyat aralığı istatistikleri
     */
    private function getPriceRangeStats(): array
    {
        $ranges = [
            '0-50' => Product::where('price', '>=', 0)->where('price', '<', 50)->count(),
            '50-100' => Product::where('price', '>=', 50)->where('price', '<', 100)->count(),
            '100-250' => Product::where('price', '>=', 100)->where('price', '<', 250)->count(),
            '250-500' => Product::where('price', '>=', 250)->where('price', '<', 500)->count(),
            '500+' => Product::where('price', '>=', 500)->count(),
        ];

        return $ranges;
    }

    /**
     * Stok uyarıları
     */
    private function getStockAlerts(): array
    {
        return [
            'out_of_stock' => Product::where('is_active', true)->where('stock_quantity', '<=', 0)->count(),
            'low_stock' => Product::where('is_active', true)->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10)->count(),
            'critical_stock' => Product::where('is_active', true)->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 5)->count(),
        ];
    }

    /**
     * En çok satan ürünler
     */
    private function getTopProducts(int $limit = 10): array
    {
        // Bu bilgi Order tablosundan alınacak, şimdilik views'a göre sıralıyoruz
        return Product::with('category')
            ->where('is_active', true)
            ->orderBy('views', 'desc')
            ->take($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category->name ?? 'Kategori Yok',
                    'price' => $product->price,
                    'stock' => $product->stock_quantity,
                    'views' => $product->views ?? 0,
                ];
            })
            ->toArray();
    }

    /**
     * Ürün sipariş sayısı (gelecekte implement edilecek)
     */
    private function getProductOrdersCount(Product $product): int
    {
        // Order sistemi implement edildiğinde burası doldurulacak
        return 0;
    }

    /**
     * Ürün geliri (gelecekte implement edilecek)
     */
    private function getProductRevenue(Product $product): float
    {
        // Order sistemi implement edildiğinde burası doldurulacak
        return 0.0;
    }

    /**
     * Son sipariş tarihi (gelecekte implement edilecek)
     */
    private function getLastOrderDate(Product $product): ?\Carbon\Carbon
    {
        // Order sistemi implement edildiğinde burası doldurulacak
        return null;
    }

    /**
     * Dönüşüm oranı hesapla (gelecekte implement edilecek)
     */
    private function calculateConversionRate(Product $product): float
    {
        // Analytics sistemi implement edildiğinde burası doldurulacak
        return 0.0;
    }
}