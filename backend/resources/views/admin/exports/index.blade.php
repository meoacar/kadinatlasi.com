@extends('admin.layouts.app')

@section('title', 'Veri Dışa Aktarma')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Veri Dışa Aktarma</h1>
            <p class="text-gray-600">Sistem verilerini farklı formatlarda dışa aktarın</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.exports.history') }}" class="btn btn-secondary">
                <i class="fas fa-history mr-2"></i>
                Export Geçmişi
            </a>
            <a href="{{ route('admin.exports.custom') }}" class="btn btn-primary">
                <i class="fas fa-cog mr-2"></i>
                Özel Export
            </a>
        </div>
    </div>

    <!-- Quick Export Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($exportOptions as $key => $option)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4">
                    @switch($key)
                        @case('users')
                            <i class="fas fa-users text-xl"></i>
                            @break
                        @case('blog')
                            <i class="fas fa-blog text-xl"></i>
                            @break
                        @case('products')
                            <i class="fas fa-box text-xl"></i>
                            @break
                        @case('forum')
                            <i class="fas fa-comments text-xl"></i>
                            @break
                        @case('activities')
                            <i class="fas fa-clipboard-list text-xl"></i>
                            @break
                    @endswitch
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $option['name'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $option['description'] }}</p>
                </div>
            </div>

            <form action="{{ route('admin.exports.' . $key) }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Format Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                    <div class="flex space-x-2">
                        @foreach($option['formats'] as $format)
                        <label class="flex items-center">
                            <input type="radio" name="format" value="{{ $format }}" 
                                   class="mr-2" {{ $loop->first ? 'checked' : '' }}>
                            <span class="text-sm">{{ strtoupper($format) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Date Range -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Başlangıç</label>
                        <input type="date" name="date_from" class="form-input text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bitiş</label>
                        <input type="date" name="date_to" class="form-input text-sm">
                    </div>
                </div>

                <!-- Additional Filters -->
                @if($key === 'users')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Durum</label>
                    <select name="status" class="form-select text-sm">
                        <option value="">Tümü</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Pasif</option>
                    </select>
                </div>
                @elseif($key === 'blog')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Durum</label>
                    <select name="status" class="form-select text-sm">
                        <option value="">Tümü</option>
                        <option value="published">Yayında</option>
                        <option value="draft">Taslak</option>
                    </select>
                </div>
                @elseif($key === 'products')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Min Fiyat</label>
                        <input type="number" name="price_min" class="form-input text-sm" min="0" step="0.01">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Fiyat</label>
                        <input type="number" name="price_max" class="form-input text-sm" min="0" step="0.01">
                    </div>
                </div>
                @endif

                <button type="submit" class="w-full btn btn-primary btn-sm">
                    <i class="fas fa-download mr-2"></i>
                    Dışa Aktar
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <!-- Bulk Export Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Toplu Dışa Aktarma</h2>
        <p class="text-gray-600 mb-6">Birden fazla veri türünü tek seferde dışa aktarın</p>

        <form action="{{ route('admin.exports.bulk') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Export Types -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Dışa Aktarılacak Veriler</label>
                    <div class="space-y-2">
                        @foreach($exportOptions as $key => $option)
                        <label class="flex items-center">
                            <input type="checkbox" name="export_types[]" value="{{ $key }}" class="mr-3">
                            <span class="text-sm">{{ $option['name'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Options -->
                <div class="space-y-4">
                    <!-- Format -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="format" value="csv" class="mr-2" checked>
                                <span class="text-sm">CSV</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="format" value="excel" class="mr-2">
                                <span class="text-sm">Excel</span>
                            </label>
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Başlangıç</label>
                            <input type="date" name="date_from" class="form-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bitiş</label>
                            <input type="date" name="date_to" class="form-input">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-download mr-2"></i>
                    Toplu Dışa Aktar
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submission handling
    const forms = document.querySelectorAll('form[action*="exports"]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>İşleniyor...';
            
            // Re-enable after 5 seconds (in case of issues)
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 5000);
        });
    });
});
</script>
@endpush
@endsection