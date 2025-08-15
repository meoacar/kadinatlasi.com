@extends('admin.layouts.app')

@section('title', 'Özel Export')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Özel Export</h1>
            <p class="text-gray-600">İhtiyacınıza göre özelleştirilmiş veri dışa aktarma</p>
        </div>
        <a href="{{ route('admin.exports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            Geri Dön
        </a>
    </div>

    <!-- Custom Export Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.exports.custom.process') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Export Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Veri Türü</label>
                        <div class="space-y-2">
                            @foreach($exportOptions as $key => $option)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="export_type" value="{{ $key }}" class="mr-3" 
                                       {{ $loop->first ? 'checked' : '' }}
                                       onchange="updateColumnOptions('{{ $key }}')">
                                <div class="flex items-center">
                                    <div class="p-2 rounded bg-blue-100 text-blue-600 mr-3">
                                        @switch($key)
                                            @case('users')
                                                <i class="fas fa-users"></i>
                                                @break
                                            @case('blog')
                                                <i class="fas fa-blog"></i>
                                                @break
                                            @case('products')
                                                <i class="fas fa-box"></i>
                                                @break
                                            @case('forum')
                                                <i class="fas fa-comments"></i>
                                                @break
                                            @case('activities')
                                                <i class="fas fa-clipboard-list"></i>
                                                @break
                                        @endswitch
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $option['name'] }}</div>
                                        <div class="text-sm text-gray-600">{{ $option['description'] }}</div>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Format Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Export Formatı</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="flex items-center justify-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="format" value="csv" class="mr-2" checked>
                                <span class="font-medium">CSV</span>
                            </label>
                            <label class="flex items-center justify-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="format" value="excel" class="mr-2">
                                <span class="font-medium">Excel</span>
                            </label>
                            <label class="flex items-center justify-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="format" value="pdf" class="mr-2">
                                <span class="font-medium">PDF</span>
                            </label>
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Tarih Aralığı</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Başlangıç Tarihi</label>
                                <input type="date" name="date_from" class="form-input">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Bitiş Tarihi</label>
                                <input type="date" name="date_to" class="form-input">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Column Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Dışa Aktarılacak Sütunlar
                            <span class="text-xs text-gray-500 ml-2">(En az bir sütun seçmelisiniz)</span>
                        </label>
                        
                        <!-- Users Columns -->
                        <div id="users-columns" class="column-group">
                            <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="id" class="mr-2" checked>
                                    <span class="text-sm">ID</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="name" class="mr-2" checked>
                                    <span class="text-sm">Ad Soyad</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="email" class="mr-2" checked>
                                    <span class="text-sm">E-posta</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="membership_type" class="mr-2">
                                    <span class="text-sm">Üyelik Türü</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="is_active" class="mr-2">
                                    <span class="text-sm">Durum</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="birth_date" class="mr-2">
                                    <span class="text-sm">Doğum Tarihi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="zodiac_sign" class="mr-2">
                                    <span class="text-sm">Burç</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="points" class="mr-2">
                                    <span class="text-sm">Puan</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="created_at" class="mr-2" checked>
                                    <span class="text-sm">Kayıt Tarihi</span>
                                </label>
                            </div>
                        </div>

                        <!-- Blog Columns -->
                        <div id="blog-columns" class="column-group hidden">
                            <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="id" class="mr-2" checked>
                                    <span class="text-sm">ID</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="title" class="mr-2" checked>
                                    <span class="text-sm">Başlık</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="author" class="mr-2" checked>
                                    <span class="text-sm">Yazar</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="category" class="mr-2">
                                    <span class="text-sm">Kategori</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="status" class="mr-2">
                                    <span class="text-sm">Durum</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="views_count" class="mr-2">
                                    <span class="text-sm">Görüntülenme</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="published_at" class="mr-2">
                                    <span class="text-sm">Yayın Tarihi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="created_at" class="mr-2" checked>
                                    <span class="text-sm">Oluşturulma Tarihi</span>
                                </label>
                            </div>
                        </div>

                        <!-- Products Columns -->
                        <div id="products-columns" class="column-group hidden">
                            <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="id" class="mr-2" checked>
                                    <span class="text-sm">ID</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="name" class="mr-2" checked>
                                    <span class="text-sm">Ürün Adı</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="sku" class="mr-2">
                                    <span class="text-sm">SKU</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="category" class="mr-2">
                                    <span class="text-sm">Kategori</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="price" class="mr-2" checked>
                                    <span class="text-sm">Fiyat</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="sale_price" class="mr-2">
                                    <span class="text-sm">İndirimli Fiyat</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="brand" class="mr-2">
                                    <span class="text-sm">Marka</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="is_active" class="mr-2">
                                    <span class="text-sm">Durum</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="created_at" class="mr-2" checked>
                                    <span class="text-sm">Oluşturulma Tarihi</span>
                                </label>
                            </div>
                        </div>

                        <!-- Forum Columns -->
                        <div id="forum-columns" class="column-group hidden">
                            <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="id" class="mr-2" checked>
                                    <span class="text-sm">ID</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="title" class="mr-2" checked>
                                    <span class="text-sm">Başlık</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="author" class="mr-2" checked>
                                    <span class="text-sm">Yazar</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="group" class="mr-2">
                                    <span class="text-sm">Grup</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="is_active" class="mr-2">
                                    <span class="text-sm">Durum</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="views_count" class="mr-2">
                                    <span class="text-sm">Görüntülenme</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="replies_count" class="mr-2">
                                    <span class="text-sm">Yanıt Sayısı</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="created_at" class="mr-2" checked>
                                    <span class="text-sm">Oluşturulma Tarihi</span>
                                </label>
                            </div>
                        </div>

                        <!-- Activities Columns -->
                        <div id="activities-columns" class="column-group hidden">
                            <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="id" class="mr-2" checked>
                                    <span class="text-sm">ID</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="admin" class="mr-2" checked>
                                    <span class="text-sm">Admin</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="action" class="mr-2" checked>
                                    <span class="text-sm">Aksiyon</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="model_type" class="mr-2">
                                    <span class="text-sm">Model</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="description" class="mr-2" checked>
                                    <span class="text-sm">Açıklama</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="ip_address" class="mr-2">
                                    <span class="text-sm">IP Adresi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="severity" class="mr-2">
                                    <span class="text-sm">Önem Derecesi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="created_at" class="mr-2" checked>
                                    <span class="text-sm">Tarih</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex space-x-2">
                        <button type="button" onclick="selectAllColumns()" class="text-sm text-blue-600 hover:text-blue-800">
                            Tümünü Seç
                        </button>
                        <span class="text-gray-300">|</span>
                        <button type="button" onclick="deselectAllColumns()" class="text-sm text-blue-600 hover:text-blue-800">
                            Tümünü Kaldır
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-6 border-t border-gray-200">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-download mr-2"></i>
                    Özel Export Oluştur
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function updateColumnOptions(type) {
    // Hide all column groups
    document.querySelectorAll('.column-group').forEach(group => {
        group.classList.add('hidden');
    });
    
    // Show selected type's columns
    document.getElementById(type + '-columns').classList.remove('hidden');
    
    // Clear all checkboxes first
    document.querySelectorAll('input[name="columns[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Check default columns for the selected type
    const defaultColumns = {
        'users': ['id', 'name', 'email', 'created_at'],
        'blog': ['id', 'title', 'author', 'created_at'],
        'products': ['id', 'name', 'price', 'created_at'],
        'forum': ['id', 'title', 'author', 'created_at'],
        'activities': ['id', 'admin', 'action', 'description', 'created_at']
    };
    
    if (defaultColumns[type]) {
        defaultColumns[type].forEach(column => {
            const checkbox = document.querySelector(`#${type}-columns input[value="${column}"]`);
            if (checkbox) checkbox.checked = true;
        });
    }
}

function selectAllColumns() {
    const activeGroup = document.querySelector('.column-group:not(.hidden)');
    if (activeGroup) {
        activeGroup.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = true;
        });
    }
}

function deselectAllColumns() {
    const activeGroup = document.querySelector('.column-group:not(.hidden)');
    if (activeGroup) {
        activeGroup.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });
    }
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const checkedColumns = document.querySelectorAll('input[name="columns[]"]:checked');
    if (checkedColumns.length === 0) {
        e.preventDefault();
        alert('En az bir sütun seçmelisiniz.');
        return false;
    }
});
</script>
@endpush
@endsection