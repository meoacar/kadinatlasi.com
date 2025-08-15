<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminProductController extends AdminController
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;
    }

    /**
     * Ürün listesi
     */
    public function index(Request $request)
    {
        try {
            $filters = $this->getPaginationData($request);
            $filters['status'] = $request->get('status');
            $filters['category_id'] = $request->get('category_id');
            $filters['price_min'] = $request->get('price_min');
            $filters['price_max'] = $request->get('price_max');
            $filters['stock_status'] = $request->get('stock_status');
            $filters['date_from'] = $request->get('date_from');
            $filters['date_to'] = $request->get('date_to');

            $products = $this->productService->getProductsWithFilters($filters);
            $categories = ProductCategory::orderBy('name')->get();
            
            $stats = [
                'total' => Product::count(),
                'active' => Product::where('is_active', true)->count(),
                'inactive' => Product::where('is_active', false)->count(),
                'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
                'low_stock' => Product::where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10)->count(),
                'today' => Product::whereDate('created_at', today())->count(),
            ];

            return view('admin.products.index', compact('products', 'categories', 'stats', 'filters'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load products');
        }
    }

    /**
     * Ürün oluşturma formu
     */
    public function create()
    {
        $categories = ProductCategory::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Yeni ürün oluştur
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'tags' => 'nullable|string',
        ], [
            'name.required' => 'Ürün adı gereklidir.',
            'name.max' => 'Ürün adı en fazla 255 karakter olabilir.',
            'slug.unique' => 'Bu slug zaten kullanılıyor.',
            'price.required' => 'Fiyat gereklidir.',
            'price.numeric' => 'Fiyat sayısal bir değer olmalıdır.',
            'price.min' => 'Fiyat 0\'dan küçük olamaz.',
            'sale_price.lt' => 'İndirimli fiyat normal fiyattan küçük olmalıdır.',
            'sku.unique' => 'Bu SKU zaten kullanılıyor.',
            'stock_quantity.required' => 'Stok miktarı gereklidir.',
            'stock_quantity.integer' => 'Stok miktarı tam sayı olmalıdır.',
            'stock_quantity.min' => 'Stok miktarı 0\'dan küçük olamaz.',
            'images.*.image' => 'Yüklenen dosyalar resim formatında olmalıdır.',
            'images.*.max' => 'Resim boyutu en fazla 2MB olabilir.',
        ]);

        try {
            $productData = $request->only([
                'name', 'slug', 'description', 'short_description', 'category_id',
                'price', 'sale_price', 'sku', 'stock_quantity', 'weight', 'dimensions',
                'is_active', 'is_featured', 'meta_title', 'meta_description'
            ]);

            // Slug oluştur
            if (empty($productData['slug'])) {
                $productData['slug'] = Str::slug($productData['name']);
            }

            // SKU oluştur
            if (empty($productData['sku'])) {
                $productData['sku'] = 'PRD-' . strtoupper(Str::random(8));
            }

            // Tags işleme
            if ($request->filled('tags')) {
                $productData['tags'] = array_map('trim', explode(',', $request->tags));
            }

            $product = $this->productService->createProduct($productData);

            // Resimleri yükle
            if ($request->hasFile('images')) {
                $this->productService->handleImageUploads($product, $request->file('images'));
            }

            return $this->successResponse('Ürün başarıyla oluşturuldu.')
                ->with('redirect', route('admin.products.show', $product));
        } catch (\Exception $e) {
            return $this->handleException($e, 'create product');
        }
    }

    /**
     * Ürün detayları
     */
    public function show(Product $product)
    {
        try {
            $product->load(['category', 'variants', 'reviews']);
            
            $stats = $this->productService->getProductStats($product);
            
            return view('admin.products.show', compact('product', 'stats'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load product details');
        }
    }

    /**
     * Ürün düzenleme formu
     */
    public function edit(Product $product)
    {
        $categories = ProductCategory::orderBy('name')->get();
        $product->load('category');
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Ürün güncelle
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('products')->ignore($product->id)],
            'stock_quantity' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'tags' => 'nullable|string',
        ], [
            'name.required' => 'Ürün adı gereklidir.',
            'slug.unique' => 'Bu slug zaten kullanılıyor.',
            'price.required' => 'Fiyat gereklidir.',
            'sale_price.lt' => 'İndirimli fiyat normal fiyattan küçük olmalıdır.',
            'sku.unique' => 'Bu SKU zaten kullanılıyor.',
            'stock_quantity.required' => 'Stok miktarı gereklidir.',
        ]);

        try {
            $productData = $request->only([
                'name', 'slug', 'description', 'short_description', 'category_id',
                'price', 'sale_price', 'sku', 'stock_quantity', 'weight', 'dimensions',
                'is_active', 'is_featured', 'meta_title', 'meta_description'
            ]);

            // Slug güncelle
            if (empty($productData['slug'])) {
                $productData['slug'] = Str::slug($productData['name']);
            }

            // Tags işleme
            if ($request->filled('tags')) {
                $productData['tags'] = array_map('trim', explode(',', $request->tags));
            }

            $product = $this->productService->updateProduct($product, $productData);

            // Yeni resimleri yükle
            if ($request->hasFile('images')) {
                $this->productService->handleImageUploads($product, $request->file('images'));
            }

            return $this->successResponse('Ürün başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update product');
        }
    }

    /**
     * Ürün sil
     */
    public function destroy(Product $product)
    {
        try {
            // Ürün resimlerini sil
            $this->productService->deleteProductImages($product);

            $product->delete();

            return $this->successResponse('Ürün başarıyla silindi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'delete product');
        }
    }

    /**
     * Ürün durumunu değiştir (aktif/pasif)
     */
    public function toggleStatus(Product $product)
    {
        try {
            $newStatus = !$product->is_active;
            $product->update(['is_active' => $newStatus]);
            
            $statusText = $newStatus ? 'aktif' : 'pasif';
            return $this->successResponse("Ürün başarıyla {$statusText} yapıldı.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'toggle product status');
        }
    }

    /**
     * Ürünleri toplu işlem
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature,update_category',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
            'category_id' => 'required_if:action,update_category|nullable|exists:product_categories,id',
        ]);

        try {
            $productIds = $request->products;
            $action = $request->action;
            
            $products = Product::whereIn('id', $productIds)->get();
            
            if ($products->isEmpty()) {
                return $this->errorResponse('İşlem yapılacak ürün bulunamadı.');
            }

            $count = 0;
            switch ($action) {
                case 'activate':
                    $count = $products->where('is_active', false)->count();
                    Product::whereIn('id', $productIds)->update(['is_active' => true]);
                    break;
                    
                case 'deactivate':
                    $count = $products->where('is_active', true)->count();
                    Product::whereIn('id', $productIds)->update(['is_active' => false]);
                    break;
                    
                case 'feature':
                    $count = $products->where('is_featured', false)->count();
                    Product::whereIn('id', $productIds)->update(['is_featured' => true]);
                    break;
                    
                case 'unfeature':
                    $count = $products->where('is_featured', true)->count();
                    Product::whereIn('id', $productIds)->update(['is_featured' => false]);
                    break;
                    
                case 'update_category':
                    $count = $products->count();
                    Product::whereIn('id', $productIds)->update(['category_id' => $request->category_id]);
                    break;
                    
                case 'delete':
                    $count = $products->count();
                    // Resimleri sil
                    foreach ($products as $product) {
                        $this->productService->deleteProductImages($product);
                    }
                    Product::whereIn('id', $productIds)->delete();
                    break;
            }

            $actionText = match($action) {
                'activate' => 'aktif yapıldı',
                'deactivate' => 'pasif yapıldı',
                'feature' => 'öne çıkarıldı',
                'unfeature' => 'öne çıkarmadan kaldırıldı',
                'update_category' => 'kategorisi güncellendi',
                'delete' => 'silindi',
            };

            return $this->successResponse("{$count} ürün başarıyla {$actionText}.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'perform bulk action');
        }
    }

    /**
     * Ürün resimlerini yönet
     */
    public function manageImages(Product $product)
    {
        $product->load('images');
        return view('admin.products.images', compact('product'));
    }

    /**
     * Ürün resmi sil
     */
    public function deleteImage(Request $request, Product $product)
    {
        $request->validate([
            'image_path' => 'required|string',
        ]);

        try {
            $imagePath = $request->image_path;
            
            // Resmi storage'dan sil
            \Storage::disk('public')->delete($imagePath);
            
            // Ürün resimlerinden kaldır
            $images = $product->images ?? [];
            $images = array_filter($images, function($image) use ($imagePath) {
                return $image !== $imagePath;
            });
            
            $product->update(['images' => array_values($images)]);
            
            return $this->successResponse('Resim başarıyla silindi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'delete image');
        }
    }

    /**
     * Stok güncelle
     */
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'stock_note' => 'nullable|string|max:255',
        ], [
            'stock_quantity.required' => 'Stok miktarı gereklidir.',
            'stock_quantity.integer' => 'Stok miktarı tam sayı olmalıdır.',
            'stock_quantity.min' => 'Stok miktarı 0\'dan küçük olamaz.',
        ]);

        try {
            $oldStock = $product->stock_quantity;
            $newStock = $request->stock_quantity;
            
            $product->update([
                'stock_quantity' => $newStock,
            ]);
            
            // Stok değişikliğini logla
            $this->productService->logStockChange($product, $oldStock, $newStock, $request->stock_note);
            
            return $this->successResponse('Stok miktarı başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update stock');
        }
    }

    /**
     * Ürün istatistikleri
     */
    public function stats()
    {
        try {
            $stats = $this->productService->getProductStats();
            
            return view('admin.products.stats', compact('stats'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load product stats');
        }
    }

    /**
     * Ürünleri dışa aktar
     */
    public function export(Request $request)
    {
        try {
            $filters = $request->only(['status', 'category_id', 'price_min', 'price_max', 'stock_status']);
            
            $products = $this->productService->exportProducts($filters);
            
            $filename = 'products_' . now()->format('Y_m_d_H_i_s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];
            
            $callback = function() use ($products) {
                $file = fopen('php://output', 'w');
                
                // CSV başlıkları
                fputcsv($file, [
                    'ID', 'Ürün Adı', 'SKU', 'Kategori', 'Fiyat', 'İndirimli Fiyat', 
                    'Stok', 'Durum', 'Öne Çıkan', 'Oluşturma Tarihi'
                ]);
                
                // Veri satırları
                foreach ($products as $product) {
                    fputcsv($file, $product);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return $this->handleException($e, 'export products');
        }
    }

    /**
     * Düşük stok uyarıları
     */
    public function lowStockAlert()
    {
        try {
            $lowStockProducts = Product::where('is_active', true)
                ->where('stock_quantity', '>', 0)
                ->where('stock_quantity', '<=', 10)
                ->with('category')
                ->orderBy('stock_quantity', 'asc')
                ->get();
                
            $outOfStockProducts = Product::where('is_active', true)
                ->where('stock_quantity', '<=', 0)
                ->with('category')
                ->orderBy('updated_at', 'desc')
                ->get();
            
            return view('admin.products.low-stock', compact('lowStockProducts', 'outOfStockProducts'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load low stock alerts');
        }
    }
}