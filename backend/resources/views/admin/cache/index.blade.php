@extends('admin.layouts.app')

@section('title', 'Cache ve Performans Yönetimi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Cache ve Performans Yönetimi</h1>
            <p class="text-gray-600">Sistem performansını optimize edin ve cache'leri yönetin</p>
        </div>
        <button onclick="refreshReport()" class="btn btn-secondary">
            <i class="fas fa-sync-alt mr-2"></i>
            Raporu Yenile
        </button>
    </div>

    <!-- Performance Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Memory Usage -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-memory text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Memory Kullanımı</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $performanceReport['memory_usage']['current_formatted'] ?? 'N/A' }}
                    </p>
                    <p class="text-xs text-gray-500">
                        Peak: {{ $performanceReport['memory_usage']['peak_formatted'] ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Database Connections -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-database text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">DB Bağlantıları</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $performanceReport['database_stats']['active_connections'] ?? 'N/A' }}
                    </p>
                    <p class="text-xs text-gray-500">
                        Max: {{ $performanceReport['database_stats']['max_connections'] ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Cache Hit Rate -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-tachometer-alt text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Cache Hit Rate</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $performanceReport['cache_stats']['hit_rate'] ?? 'N/A' }}
                    </p>
                    <p class="text-xs text-gray-500">
                        Driver: {{ $performanceReport['cache_stats']['driver'] ?? config('cache.default') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Index Recommendations -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Index Önerileri</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ count($performanceReport['index_recommendations'] ?? []) }}
                    </p>
                    <p class="text-xs text-gray-500">Optimizasyon</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cache Management -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Cache Yönetimi</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Dashboard Cache -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-medium text-gray-900 mb-2">Dashboard Cache</h3>
                    <p class="text-sm text-gray-600 mb-4">Dashboard istatistikleri ve veriler</p>
                    <div class="flex space-x-2">
                        <button onclick="clearCache('dashboard')" class="btn btn-sm btn-outline">
                            <i class="fas fa-trash mr-1"></i>
                            Temizle
                        </button>
                        <button onclick="rebuildCache('dashboard')" class="btn btn-sm btn-primary">
                            <i class="fas fa-sync mr-1"></i>
                            Yenile
                        </button>
                    </div>
                </div>

                <!-- Application Cache -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-medium text-gray-900 mb-2">Application Cache</h3>
                    <p class="text-sm text-gray-600 mb-4">Uygulama genel cache'i</p>
                    <div class="flex space-x-2">
                        <button onclick="clearCache('application')" class="btn btn-sm btn-outline">
                            <i class="fas fa-trash mr-1"></i>
                            Temizle
                        </button>
                    </div>
                </div>

                <!-- Config Cache -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-medium text-gray-900 mb-2">Config Cache</h3>
                    <p class="text-sm text-gray-600 mb-4">Konfigürasyon cache'i</p>
                    <div class="flex space-x-2">
                        <button onclick="clearCache('config')" class="btn btn-sm btn-outline">
                            <i class="fas fa-trash mr-1"></i>
                            Temizle
                        </button>
                        <button onclick="rebuildCache('config')" class="btn btn-sm btn-primary">
                            <i class="fas fa-sync mr-1"></i>
                            Yenile
                        </button>
                    </div>
                </div>

                <!-- Route Cache -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-medium text-gray-900 mb-2">Route Cache</h3>
                    <p class="text-sm text-gray-600 mb-4">Route tanımları cache'i</p>
                    <div class="flex space-x-2">
                        <button onclick="clearCache('route')" class="btn btn-sm btn-outline">
                            <i class="fas fa-trash mr-1"></i>
                            Temizle
                        </button>
                        <button onclick="rebuildCache('route')" class="btn btn-sm btn-primary">
                            <i class="fas fa-sync mr-1"></i>
                            Yenile
                        </button>
                    </div>
                </div>

                <!-- View Cache -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-medium text-gray-900 mb-2">View Cache</h3>
                    <p class="text-sm text-gray-600 mb-4">Blade template cache'i</p>
                    <div class="flex space-x-2">
                        <button onclick="clearCache('view')" class="btn btn-sm btn-outline">
                            <i class="fas fa-trash mr-1"></i>
                            Temizle
                        </button>
                        <button onclick="rebuildCache('view')" class="btn btn-sm btn-primary">
                            <i class="fas fa-sync mr-1"></i>
                            Yenile
                        </button>
                    </div>
                </div>

                <!-- All Cache -->
                <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                    <h3 class="font-medium text-red-900 mb-2">Tüm Cache</h3>
                    <p class="text-sm text-red-600 mb-4">Tüm cache türlerini temizle</p>
                    <button onclick="clearCache('all')" class="btn btn-sm btn-danger">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Tümünü Temizle
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Database Optimization -->
    @if(count($performanceReport['index_recommendations'] ?? []) > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Database Optimizasyon Önerileri</h2>
        </div>
        <div class="p-6">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                    <div>
                        <h3 class="text-sm font-medium text-yellow-800">Performans İyileştirme Önerileri</h3>
                        <p class="text-sm text-yellow-700 mt-1">
                            Aşağıdaki indexler database performansını artırabilir:
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                @foreach($performanceReport['index_recommendations'] as $recommendation)
                <div class="bg-gray-50 rounded-lg p-3">
                    <code class="text-sm text-gray-800">{{ $recommendation }}</code>
                </div>
                @endforeach
            </div>

            @if(!app()->environment('production'))
            <div class="mt-4">
                <button onclick="optimizeDatabase()" class="btn btn-warning">
                    <i class="fas fa-tools mr-2"></i>
                    Database'i Optimize Et
                </button>
                <p class="text-xs text-gray-500 mt-2">
                    * Bu işlem sadece development ortamında çalışır
                </p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Performance Tools -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Performans Araçları</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Query Logging -->
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">Query Logging</h3>
                    <p class="text-sm text-gray-600 mb-4">Yavaş sorguları tespit etmek için logging'i etkinleştirin</p>
                    <div class="flex items-center space-x-4">
                        <button onclick="toggleQueryLogging(true)" class="btn btn-sm btn-success">
                            <i class="fas fa-play mr-1"></i>
                            Etkinleştir
                        </button>
                        <button onclick="toggleQueryLogging(false)" class="btn btn-sm btn-secondary">
                            <i class="fas fa-stop mr-1"></i>
                            Devre Dışı
                        </button>
                    </div>
                </div>

                <!-- Performance Report -->
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">Performans Raporu</h3>
                    <p class="text-sm text-gray-600 mb-4">Detaylı performans analizi raporu oluşturun</p>
                    <button onclick="generateReport()" class="btn btn-sm btn-primary">
                        <i class="fas fa-chart-line mr-1"></i>
                        Rapor Oluştur
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Cache işlemleri
async function clearCache(type) {
    if (type === 'all' && !confirm('Tüm cache\'leri temizlemek istediğinizden emin misiniz?')) {
        return;
    }

    try {
        showLoading(`${type} cache temizleniyor...`);
        
        const response = await fetch('{{ route("admin.cache.clear") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ type: type })
        });

        const result = await response.json();
        
        if (result.success) {
            showSuccess(`${result.message} (${result.count} öğe)`);
        } else {
            showError(result.message || 'Cache temizlenirken hata oluştu');
        }
    } catch (error) {
        showError('İşlem sırasında hata oluştu');
    } finally {
        hideLoading();
    }
}

async function rebuildCache(type) {
    try {
        showLoading(`${type} cache yeniden oluşturuluyor...`);
        
        const response = await fetch('{{ route("admin.cache.rebuild") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ type: type })
        });

        const result = await response.json();
        
        if (result.success) {
            showSuccess(result.message);
        } else {
            showError(result.message || 'Cache yeniden oluşturulurken hata oluştu');
        }
    } catch (error) {
        showError('İşlem sırasında hata oluştu');
    } finally {
        hideLoading();
    }
}

async function optimizeDatabase() {
    if (!confirm('Database optimizasyonu yapmak istediğinizden emin misiniz?')) {
        return;
    }

    try {
        showLoading('Database optimize ediliyor...');
        
        const response = await fetch('{{ route("admin.cache.optimize-database") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ action: 'optimize' })
        });

        const result = await response.json();
        
        if (result.success) {
            showSuccess('Database optimizasyonu tamamlandı');
        } else {
            showError(result.message || 'Optimizasyon sırasında hata oluştu');
        }
    } catch (error) {
        showError('İşlem sırasında hata oluştu');
    } finally {
        hideLoading();
    }
}

async function toggleQueryLogging(enable) {
    try {
        showLoading('Query logging ayarlanıyor...');
        
        const response = await fetch('{{ route("admin.cache.toggle-query-logging") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ enable: enable })
        });

        const result = await response.json();
        
        if (result.success) {
            showSuccess(result.message);
        } else {
            showError(result.message || 'Ayar değiştirilirken hata oluştu');
        }
    } catch (error) {
        showError('İşlem sırasında hata oluştu');
    } finally {
        hideLoading();
    }
}

async function generateReport() {
    try {
        showLoading('Performans raporu oluşturuluyor...');
        
        const response = await fetch('{{ route("admin.cache.performance-report") }}');
        const result = await response.json();
        
        if (result.success) {
            // Raporu modal veya yeni sayfada göster
            showPerformanceReport(result.report);
        } else {
            showError('Rapor oluşturulurken hata oluştu');
        }
    } catch (error) {
        showError('İşlem sırasında hata oluştu');
    } finally {
        hideLoading();
    }
}

function refreshReport() {
    window.location.reload();
}

function showPerformanceReport(report) {
    // Basit bir alert ile göster (daha sonra modal yapılabilir)
    const reportText = JSON.stringify(report, null, 2);
    alert('Performans Raporu:\n\n' + reportText);
}

// Utility functions
function showLoading(message) {
    // Loading indicator göster
    console.log('Loading:', message);
}

function hideLoading() {
    // Loading indicator gizle
    console.log('Loading hidden');
}

function showSuccess(message) {
    // Success notification göster
    alert('Başarılı: ' + message);
}

function showError(message) {
    // Error notification göster
    alert('Hata: ' + message);
}
</script>
@endpush
@endsection