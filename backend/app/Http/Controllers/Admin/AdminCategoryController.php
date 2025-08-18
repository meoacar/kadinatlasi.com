<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminCategoryController extends AdminController
{
    /**
     * Kategori listesi
     */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search');
            $sort = $request->get('sort', 'name');
            $direction = $request->get('direction', 'asc');

            $query = ProductCategory::withCount('products');

            // Arama filtresi
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Sıralama
            $allowedSortFields = ['name', 'products_count', 'created_at', 'is_active'];
            if (in_array($sort, $allowedSortFields)) {
                $query->orderBy($sort, $direction);
            }

            $categories = $query->paginate(20);
            
            $stats = [
                'total' => ProductCategory::count(),
                'active' => ProductCategory::where('is_active', true)->count(),
                'inactive' => ProductCategory::where('is_active', false)->count(),
                'with_products' => ProductCategory::has('products')->count(),
            ];

            return view('admin.categories.index', compact('categories', 'stats', 'search', 'sort', 'direction'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load categories');
        }
    }

    /**
     * Kategori oluşturma formu
     */
    public function create()
    {
        $parentCategories = ProductCategory::where('parent_id', null)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Yeni kategori oluştur
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:product_categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:product_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Kategori adı gereklidir.',
            'name.max' => 'Kategori adı en fazla 255 karakter olabilir.',
            'slug.unique' => 'Bu slug zaten kullanılıyor.',
            'parent_id.exists' => 'Seçilen üst kategori geçersiz.',
            'image.image' => 'Yüklenen dosya bir resim olmalıdır.',
            'image.max' => 'Resim boyutu en fazla 2MB olabilir.',
            'color.max' => 'Renk kodu en fazla 7 karakter olabilir.',
        ]);

        try {
            $categoryData = $request->only([
                'name', 'slug', 'description', 'parent_id', 'icon', 'color',
                'sort_order', 'is_active', 'is_featured', 'meta_title', 'meta_description'
            ]);

            // Slug oluştur
            if (empty($categoryData['slug'])) {
                $categoryData['slug'] = $this->generateUniqueSlug($categoryData['name']);
            }

            // Varsayılan değerler
            $categoryData['is_active'] = $request->boolean('is_active', true);
            $categoryData['is_featured'] = $request->boolean('is_featured', false);
            $categoryData['sort_order'] = $categoryData['sort_order'] ?? 0;

            $category = ProductCategory::create($categoryData);

            // Resim yükle
            if ($request->hasFile('image')) {
                $imagePath = $this->handleImageUpload($request->file('image'));
                $category->update(['image' => $imagePath]);
            }

            return $this->successResponse('Kategori başarıyla oluşturuldu.')
                ->with('redirect', route('admin.categories.show', $category));
        } catch (\Exception $e) {
            return $this->handleException($e, 'create category');
        }
    }

    /**
     * Kategori detayları
     */
    public function show(ProductCategory $category)
    {
        try {
            $category->load(['parent', 'children', 'products']);
            
            $stats = [
                'products_count' => $category->products()->count(),
                'active_products' => $category->products()->where('is_active', true)->count(),
                'total_value' => $category->products()->sum('price') ?? 0,
                'avg_price' => $category->products()->avg('price') ?? 0,
            ];
            
            return view('admin.categories.show', compact('category', 'stats'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load category details');
        }
    }

    /**
     * Kategori düzenleme formu
     */
    public function edit(ProductCategory $category)
    {
        $parentCategories = ProductCategory::where('parent_id', null)
            ->where('id', '!=', $category->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Kategori güncelle
     */
    public function update(Request $request, ProductCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('product_categories')->ignore($category->id)],
            'description' => 'nullable|string',
            'parent_id' => [
                'nullable',
                'exists:product_categories,id',
                function ($attribute, $value, $fail) use ($category) {
                    if ($value == $category->id) {
                        $fail('Kategori kendi alt kategorisi olamaz.');
                    }
                    // Alt kategorilerden birini üst kategori yapmayı engelle
                    if ($value && $category->children()->where('id', $value)->exists()) {
                        $fail('Alt kategori, üst kategori olarak seçilemez.');
                    }
                },
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Kategori adı gereklidir.',
            'slug.unique' => 'Bu slug zaten kullanılıyor.',
            'parent_id.exists' => 'Seçilen üst kategori geçersiz.',
            'image.image' => 'Yüklenen dosya bir resim olmalıdır.',
            'image.max' => 'Resim boyutu en fazla 2MB olabilir.',
        ]);

        try {
            $categoryData = $request->only([
                'name', 'slug', 'description', 'parent_id', 'icon', 'color',
                'sort_order', 'is_active', 'is_featured', 'meta_title', 'meta_description'
            ]);

            // Slug güncelle
            if (empty($categoryData['slug'])) {
                $categoryData['slug'] = $this->generateUniqueSlug($categoryData['name'], $category->id);
            }

            $category->update($categoryData);

            // Yeni resim yükle
            if ($request->hasFile('image')) {
                // Eski resmi sil
                if ($category->image) {
                    \Storage::disk('public')->delete($category->image);
                }
                
                $imagePath = $this->handleImageUpload($request->file('image'));
                $category->update(['image' => $imagePath]);
            }

            return $this->successResponse('Kategori başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update category');
        }
    }

    /**
     * Kategori sil
     */
    public function destroy(ProductCategory $category)
    {
        try {
            // Alt kategorileri kontrol et
            if ($category->children()->count() > 0) {
                return $this->errorResponse('Bu kategorinin alt kategorileri var. Önce alt kategorileri silin.');
            }

            // Ürünleri kontrol et
            if ($category->products()->count() > 0) {
                return $this->errorResponse('Bu kategoriye ait ürünler var. Önce ürünleri başka kategoriye taşıyın.');
            }

            // Resmi sil
            if ($category->image) {
                \Storage::disk('public')->delete($category->image);
            }

            $category->delete();

            return $this->successResponse('Kategori başarıyla silindi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'delete category');
        }
    }

    /**
     * Kategori durumunu değiştir
     */
    public function toggleStatus(ProductCategory $category)
    {
        try {
            $newStatus = !$category->is_active;
            $category->update(['is_active' => $newStatus]);
            
            $statusText = $newStatus ? 'aktif' : 'pasif';
            return $this->successResponse("Kategori başarıyla {$statusText} yapıldı.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'toggle category status');
        }
    }

    /**
     * Kategori sırasını güncelle
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:product_categories,id',
            'categories.*.sort_order' => 'required|integer|min:0',
        ]);

        try {
            foreach ($request->categories as $categoryData) {
                ProductCategory::where('id', $categoryData['id'])
                    ->update(['sort_order' => $categoryData['sort_order']]);
            }

            return $this->successResponse('Kategori sırası başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update category order');
        }
    }

    /**
     * Kategori resmi sil
     */
    public function deleteImage(ProductCategory $category)
    {
        try {
            if ($category->image) {
                \Storage::disk('public')->delete($category->image);
                $category->update(['image' => null]);
                
                return $this->successResponse('Kategori resmi başarıyla silindi.');
            }
            
            return $this->errorResponse('Silinecek resim bulunamadı.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'delete category image');
        }
    }

    /**
     * Kategorileri toplu işlem
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:product_categories,id',
        ]);

        try {
            $categoryIds = $request->categories;
            $action = $request->action;
            
            $categories = ProductCategory::whereIn('id', $categoryIds)->get();
            
            if ($categories->isEmpty()) {
                return $this->errorResponse('İşlem yapılacak kategori bulunamadı.');
            }

            $count = 0;
            switch ($action) {
                case 'activate':
                    $count = $categories->where('is_active', false)->count();
                    ProductCategory::whereIn('id', $categoryIds)->update(['is_active' => true]);
                    break;
                    
                case 'deactivate':
                    $count = $categories->where('is_active', true)->count();
                    ProductCategory::whereIn('id', $categoryIds)->update(['is_active' => false]);
                    break;
                    
                case 'feature':
                    $count = $categories->where('is_featured', false)->count();
                    ProductCategory::whereIn('id', $categoryIds)->update(['is_featured' => true]);
                    break;
                    
                case 'unfeature':
                    $count = $categories->where('is_featured', true)->count();
                    ProductCategory::whereIn('id', $categoryIds)->update(['is_featured' => false]);
                    break;
                    
                case 'delete':
                    // Silme işlemi için kontroller
                    foreach ($categories as $category) {
                        if ($category->children()->count() > 0 || $category->products()->count() > 0) {
                            return $this->errorResponse('Bazı kategorilerin alt kategorileri veya ürünleri var. Silme işlemi iptal edildi.');
                        }
                    }
                    
                    $count = $categories->count();
                    // Resimleri sil
                    foreach ($categories as $category) {
                        if ($category->image) {
                            \Storage::disk('public')->delete($category->image);
                        }
                    }
                    ProductCategory::whereIn('id', $categoryIds)->delete();
                    break;
            }

            $actionText = match($action) {
                'activate' => 'aktif yapıldı',
                'deactivate' => 'pasif yapıldı',
                'feature' => 'öne çıkarıldı',
                'unfeature' => 'öne çıkarmadan kaldırıldı',
                'delete' => 'silindi',
            };

            return $this->successResponse("{$count} kategori başarıyla {$actionText}.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'perform bulk action');
        }
    }

    /**
     * Kategori hiyerarşisi (JSON)
     */
    public function hierarchy()
    {
        try {
            $categories = ProductCategory::with(['children' => function ($query) {
                $query->orderBy('sort_order')->orderBy('name');
            }])
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load category hierarchy'], 500);
        }
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
            $query = ProductCategory::where('slug', $slug);
            
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
     * Resim yükleme işlemi
     */
    private function handleImageUpload($file): string
    {
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = 'categories/' . $filename;
        
        // Resmi yeniden boyutlandır (max 800x600)
        $image = \Image::make($file);
        $image->resize(800, 600, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        // Kaliteyi optimize et
        $image->encode('jpg', 85);
        
        // Storage'a kaydet
        \Storage::disk('public')->put($path, $image->stream());
        
        return $path;
    }
}