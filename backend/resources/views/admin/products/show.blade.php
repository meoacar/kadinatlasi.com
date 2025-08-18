@extends('admin.layouts.app')

@section('title', 'Ürün Detayı: ' . $product->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Ürün detayları ve yönetim</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.products.edit', $product) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Düzenle
            </a>
            <a href="{{ route('admin.products.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Geri Dön
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Product Images -->
            @if($product->images && count($product->images) > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ürün Resimleri</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($product->images as $image)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $image) }}" alt="Product Image" 
                             class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity"
                             onclick="openImageModal('{{ asset('storage/' . $image) }}')">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                            </svg>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Product Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ürün Bilgileri</h3>
                
                <div class="space-y-4">
                    <!-- Description -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Açıklama</h4>
                        <div class="text-sm text-gray-600 whitespace-pre-wrap">{{ $product->description }}</div>
                    </div>

                    @if($product->short_description)
                    <!-- Short Description -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Kısa Açıklama</h4>
                        <div class="text-sm text-gray-600">{{ $product->short_description }}</div>
                    </div>
                    @endif

                    @if($product->tags && count($product->tags) > 0)
                    <!-- Tags -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Etiketler</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->tags as $tag)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $tag }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Product Variants -->
            @if($product->variants && $product->variants->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Ürün Varyantları</h3>
                    <a href="{{ route('admin.products.variants', $product) }}" 
                       class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        Tümünü Görüntüle
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Varyant</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fiyat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($product->variants->take(5) as $variant)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $variant->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $variant->sku }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($variant->price, 2) }} TL</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $variant->stock_quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $variant->stock_quantity }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- SEO Information -->
            @if($product->meta_title || $product->meta_description)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Bilgileri</h3>
                
                <div class="space-y-4">
                    @if($product->meta_title)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Meta Başlık</h4>
                        <div class="text-sm text-gray-600">{{ $product->meta_title }}</div>
                    </div>
                    @endif

                    @if($product->meta_description)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Meta Açıklama</h4>
                        <div class="text-sm text-gray-600">{{ $product->meta_description }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Hızlı İşlemler</h3>
                
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.products.toggle-status', $product) }}">
                        @csrf
                        <button type="submit" 
                                class="w-full px-4 py-2 {{ $product->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white text-sm font-medium rounded-lg transition-colors">
                            {{ $product->is_active ? 'Pasif Yap' : 'Aktif Yap' }}
                        </button>
                    </form>

                    <a href="{{ route('admin.products.manage-images', $product) }}" 
                       class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors text-center block">
                        Resimleri Yönet
                    </a>

                    <button onclick="openStockModal()" 
                            class="w-full px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        Stok Güncelle
                    </button>

                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" 
                          onsubmit="return confirm('Bu ürünü silmek istediğinizden emin misiniz?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                            Ürünü Sil
                        </button>
                    </form>
                </div>
            </div>

            <!-- Product Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ürün Detayları</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">SKU:</span>
                        <span class="text-sm text-gray-600">{{ $product->sku ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Kategori:</span>
                        <span class="text-sm text-gray-600">{{ $product->category->name ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Fiyat:</span>
                        <span class="text-sm text-gray-600">{{ number_format($product->price, 2) }} TL</span>
                    </div>

                    @if($product->sale_price)
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">İndirimli Fiyat:</span>
                        <span class="text-sm text-red-600 font-medium">{{ number_format($product->sale_price, 2) }} TL</span>
                    </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Stok:</span>
                        <span class="text-sm {{ $product->stock_quantity <= 0 ? 'text-red-600' : ($product->stock_quantity <= 10 ? 'text-yellow-600' : 'text-green-600') }}">
                            {{ $product->stock_quantity }}
                        </span>
                    </div>

                    @if($product->weight)
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Ağırlık:</span>
                        <span class="text-sm text-gray-600">{{ $product->weight }} kg</span>
                    </div>
                    @endif

                    @if($product->dimensions)
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Boyutlar:</span>
                        <span class="text-sm text-gray-600">{{ $product->dimensions }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Durum:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                    </div>

                    @if($product->is_featured)
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Öne Çıkan:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            ⭐ Evet
                        </span>
                    </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Oluşturulma:</span>
                        <span class="text-sm text-gray-600">{{ $product->created_at->format('d.m.Y H:i') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Güncellenme:</span>
                        <span class="text-sm text-gray-600">{{ $product->updated_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Product Statistics -->
            @if(isset($stats))
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">İstatistikler</h3>
                
                <div class="space-y-3">
                    @foreach($stats as $key => $value)
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                        <span class="text-sm text-gray-600">{{ is_numeric($value) ? number_format($value) : $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <img id="modalImage" src="" alt="Product Image" class="max-w-full max-h-full object-contain">
        <button onclick="closeImageModal()" 
                class="absolute top-4 right-4 text-white hover:text-gray-300 text-2xl font-bold">
            ×
        </button>
    </div>
</div>

<!-- Stock Update Modal -->
<div id="stockModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Stok Güncelle</h3>
        
        <form method="POST" action="{{ route('admin.products.update-stock', $product) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">
                        Yeni Stok Miktarı
                    </label>
                    <input type="number" id="stock_quantity" name="stock_quantity" 
                           value="{{ $product->stock_quantity }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="note" class="block text-sm font-medium text-gray-700 mb-1">
                        Not (Opsiyonel)
                    </label>
                    <input type="text" id="note" name="note" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Stok güncelleme notu">
                </div>

                <div class="flex space-x-3">
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Güncelle
                    </button>
                    <button type="button" onclick="closeStockModal()" 
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        İptal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

function openStockModal() {
    document.getElementById('stockModal').classList.remove('hidden');
}

function closeStockModal() {
    document.getElementById('stockModal').classList.add('hidden');
}

// Close modals when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

document.getElementById('stockModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStockModal();
    }
});
</script>
@endpush