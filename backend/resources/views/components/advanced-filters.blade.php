@props([
    'action' => '',
    'showDateFilter' => true,
    'showStatusFilter' => true,
    'showCategoryFilter' => false,
    'showAuthorFilter' => false,
    'showPriceFilter' => false,
    'categories' => collect(),
    'authors' => collect(),
    'statusOptions' => [],
    'currentFilters' => []
])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6" x-data="advancedFilters()">
    <form method="GET" action="{{ $action }}" class="space-y-4">
        <!-- Preserve existing query parameters -->
        @foreach(request()->except(['date_from', 'date_to', 'status', 'category_id', 'author_id', 'price_min', 'price_max', 'sort_by', 'sort_order', 'quick_date']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Gelişmiş Filtreler</h3>
            <button type="button" @click="toggleFilters()" 
                    class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                <span x-text="showFilters ? 'Filtreleri Gizle' : 'Filtreleri Göster'"></span>
            </button>
        </div>

        <div x-show="showFilters" x-transition class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                
                @if($showDateFilter)
                    <!-- Quick Date Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hızlı Tarih</label>
                        <select name="quick_date" @change="handleQuickDateChange($event)" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tüm Zamanlar</option>
                            <option value="today" {{ request('quick_date') === 'today' ? 'selected' : '' }}>Bugün</option>
                            <option value="yesterday" {{ request('quick_date') === 'yesterday' ? 'selected' : '' }}>Dün</option>
                            <option value="this_week" {{ request('quick_date') === 'this_week' ? 'selected' : '' }}>Bu Hafta</option>
                            <option value="last_week" {{ request('quick_date') === 'last_week' ? 'selected' : '' }}>Geçen Hafta</option>
                            <option value="this_month" {{ request('quick_date') === 'this_month' ? 'selected' : '' }}>Bu Ay</option>
                            <option value="last_month" {{ request('quick_date') === 'last_month' ? 'selected' : '' }}>Geçen Ay</option>
                            <option value="this_year" {{ request('quick_date') === 'this_year' ? 'selected' : '' }}>Bu Yıl</option>
                            <option value="custom" {{ request('date_from') || request('date_to') ? 'selected' : '' }}>Özel Tarih</option>
                        </select>
                    </div>

                    <!-- Custom Date Range -->
                    <div x-show="showCustomDate" class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tarih Aralığı</label>
                        <div class="flex space-x-2">
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <span class="flex items-center text-gray-500">-</span>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                @endif

                @if($showStatusFilter && !empty($statusOptions))
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Durum</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tüm Durumlar</option>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if($showCategoryFilter && $categories->isNotEmpty())
                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tüm Kategoriler</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if($showAuthorFilter && $authors->isNotEmpty())
                    <!-- Author Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Yazar</label>
                        <select name="author_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tüm Yazarlar</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}" {{ request('author_id') == $author->id ? 'selected' : '' }}>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if($showPriceFilter)
                    <!-- Price Range Filter -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fiyat Aralığı (₺)</label>
                        <div class="flex space-x-2">
                            <input type="number" name="price_min" value="{{ request('price_min') }}" 
                                   placeholder="Min" step="0.01"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <span class="flex items-center text-gray-500">-</span>
                            <input type="number" name="price_max" value="{{ request('price_max') }}" 
                                   placeholder="Max" step="0.01"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                @endif

                <!-- Sort Options -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sıralama</label>
                    <select name="sort_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="created_at" {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>Oluşturulma Tarihi</option>
                        <option value="updated_at" {{ request('sort_by') === 'updated_at' ? 'selected' : '' }}>Güncellenme Tarihi</option>
                        <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Ad</option>
                        <option value="title" {{ request('sort_by') === 'title' ? 'selected' : '' }}>Başlık</option>
                        @if($showPriceFilter)
                            <option value="price" {{ request('sort_by') === 'price' ? 'selected' : '' }}>Fiyat</option>
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sıra</label>
                    <select name="sort_order" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="desc" {{ request('sort_order', 'desc') === 'desc' ? 'selected' : '' }}>Azalan</option>
                        <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Artan</option>
                    </select>
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div class="flex items-center space-x-4">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Filtrele
                    </button>
                    <a href="{{ $action }}" 
                       class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Temizle
                    </a>
                </div>

                <!-- Active Filters Count -->
                @php
                    $activeFilters = collect(request()->only(['date_from', 'date_to', 'status', 'category_id', 'author_id', 'price_min', 'price_max', 'quick_date']))
                        ->filter(fn($value) => !empty($value))
                        ->count();
                @endphp
                
                @if($activeFilters > 0)
                    <span class="text-sm text-gray-600">
                        {{ $activeFilters }} filtre aktif
                    </span>
                @endif
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function advancedFilters() {
    return {
        showFilters: {{ $activeFilters > 0 ? 'true' : 'false' }},
        showCustomDate: {{ request('date_from') || request('date_to') ? 'true' : 'false' }},
        
        toggleFilters() {
            this.showFilters = !this.showFilters;
        },
        
        handleQuickDateChange(event) {
            this.showCustomDate = event.target.value === 'custom';
            
            if (event.target.value !== 'custom' && event.target.value !== '') {
                // Clear custom date inputs when using quick date
                document.querySelector('input[name="date_from"]').value = '';
                document.querySelector('input[name="date_to"]').value = '';
            }
        }
    }
}
</script>
@endpush