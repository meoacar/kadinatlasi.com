@extends('admin.layouts.app')

@section('title', 'Sistem Bilgileri')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Sistem Bilgileri</h1>
            <p class="mt-1 text-sm text-gray-600">Sunucu ve uygulama bilgilerini görüntüleyin</p>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="refreshSystemInfo()" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Yenile
            </button>
            <a href="{{ route('admin.settings.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Geri Dön
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Application Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Uygulama Bilgileri</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Uygulama Adı:</span>
                    <span class="text-sm text-gray-600">{{ config('app.name') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Ortam:</span>
                    <span class="text-sm text-gray-600">
                        <span class="px-2 py-1 text-xs rounded-full {{ config('app.env') === 'production' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ strtoupper(config('app.env')) }}
                        </span>
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Debug Modu:</span>
                    <span class="text-sm text-gray-600">
                        <span class="px-2 py-1 text-xs rounded-full {{ config('app.debug') ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ config('app.debug') ? 'AÇIK' : 'KAPALI' }}
                        </span>
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">URL:</span>
                    <span class="text-sm text-gray-600">{{ config('app.url') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Zaman Dilimi:</span>
                    <span class="text-sm text-gray-600">{{ config('app.timezone') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Dil:</span>
                    <span class="text-sm text-gray-600">{{ config('app.locale') }}</span>
                </div>
            </div>
        </div>

        <!-- Laravel Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Laravel Bilgileri</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Laravel Sürümü:</span>
                    <span class="text-sm text-gray-600">{{ app()->version() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">PHP Sürümü:</span>
                    <span class="text-sm text-gray-600">{{ PHP_VERSION }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Composer Sürümü:</span>
                    <span class="text-sm text-gray-600" id="composer-version">Yükleniyor...</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Artisan Sürümü:</span>
                    <span class="text-sm text-gray-600">{{ app()->version() }}</span>
                </div>
            </div>
        </div>

        <!-- Server Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Sunucu Bilgileri</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">İşletim Sistemi:</span>
                    <span class="text-sm text-gray-600">{{ PHP_OS }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Web Sunucusu:</span>
                    <span class="text-sm text-gray-600">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Bilinmiyor' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Sunucu IP:</span>
                    <span class="text-sm text-gray-600">{{ $_SERVER['SERVER_ADDR'] ?? 'Bilinmiyor' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Sunucu Portu:</span>
                    <span class="text-sm text-gray-600">{{ $_SERVER['SERVER_PORT'] ?? 'Bilinmiyor' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">HTTPS:</span>
                    <span class="text-sm text-gray-600">
                        <span class="px-2 py-1 text-xs rounded-full {{ request()->secure() ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ request()->secure() ? 'AÇIK' : 'KAPALI' }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Database Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Veritabanı Bilgileri</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Sürücü:</span>
                    <span class="text-sm text-gray-600">{{ config('database.default') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Host:</span>
                    <span class="text-sm text-gray-600">{{ config('database.connections.'.config('database.default').'.host') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Port:</span>
                    <span class="text-sm text-gray-600">{{ config('database.connections.'.config('database.default').'.port') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Veritabanı:</span>
                    <span class="text-sm text-gray-600">{{ config('database.connections.'.config('database.default').'.database') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Bağlantı Durumu:</span>
                    <span class="text-sm text-gray-600" id="db-status">
                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                            Kontrol ediliyor...
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Cache Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Cache Bilgileri</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Cache Sürücüsü:</span>
                    <span class="text-sm text-gray-600">{{ config('cache.default') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Session Sürücüsü:</span>
                    <span class="text-sm text-gray-600">{{ config('session.driver') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Queue Sürücüsü:</span>
                    <span class="text-sm text-gray-600">{{ config('queue.default') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Mail Sürücüsü:</span>
                    <span class="text-sm text-gray-600">{{ config('mail.default') }}</span>
                </div>
            </div>
        </div>

        <!-- Storage Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Depolama Bilgileri</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Varsayılan Disk:</span>
                    <span class="text-sm text-gray-600">{{ config('filesystems.default') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Public Disk:</span>
                    <span class="text-sm text-gray-600">{{ config('filesystems.disks.public.driver') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Disk Kullanımı:</span>
                    <span class="text-sm text-gray-600" id="disk-usage">Hesaplanıyor...</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-700">Boş Alan:</span>
                    <span class="text-sm text-gray-600" id="free-space">Hesaplanıyor...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- PHP Extensions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">PHP Eklentileri</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
            @php
                $requiredExtensions = [
                    'bcmath', 'ctype', 'curl', 'dom', 'fileinfo', 'filter', 'hash', 'mbstring', 
                    'openssl', 'pcre', 'pdo', 'session', 'tokenizer', 'xml', 'zip', 'gd', 'json'
                ];
            @endphp
            
            @foreach($requiredExtensions as $extension)
            <div class="flex items-center space-x-2">
                @if(extension_loaded($extension))
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                @else
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                @endif
                <span class="text-sm text-gray-600">{{ $extension }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- System Health -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Sistem Sağlığı</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Memory Usage -->
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-900" id="memory-usage">
                    {{ round(memory_get_usage(true) / 1024 / 1024, 2) }} MB
                </div>
                <div class="text-sm text-gray-500">Bellek Kullanımı</div>
                <div class="mt-2">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, (memory_get_usage(true) / (ini_get('memory_limit') ? (int)ini_get('memory_limit') * 1024 * 1024 : 134217728)) * 100) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Peak Memory -->
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-900">
                    {{ round(memory_get_peak_usage(true) / 1024 / 1024, 2) }} MB
                </div>
                <div class="text-sm text-gray-500">Pik Bellek</div>
                <div class="mt-2">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(100, (memory_get_peak_usage(true) / (ini_get('memory_limit') ? (int)ini_get('memory_limit') * 1024 * 1024 : 134217728)) * 100) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Uptime -->
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-900" id="uptime">
                    Hesaplanıyor...
                </div>
                <div class="text-sm text-gray-500">Çalışma Süresi</div>
                <div class="mt-2">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Hızlı İşlemler</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button type="button" onclick="clearCache()" 
                    class="px-4 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 transition-colors">
                Cache Temizle
            </button>
            <button type="button" onclick="clearConfig()" 
                    class="px-4 py-2 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-lg hover:bg-yellow-200 transition-colors">
                Config Temizle
            </button>
            <button type="button" onclick="clearRoute()" 
                    class="px-4 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 transition-colors">
                Route Temizle
            </button>
            <button type="button" onclick="clearView()" 
                    class="px-4 py-2 bg-green-100 text-green-700 text-sm font-medium rounded-lg hover:bg-green-200 transition-colors">
                View Temizle
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function refreshSystemInfo() {
    location.reload();
}

function clearCache() {
    if (confirm('Cache temizlensin mi?')) {
        fetch('{{ route("admin.settings.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cache başarıyla temizlendi!');
            } else {
                alert('Cache temizlenirken hata oluştu.');
            }
        });
    }
}

function clearConfig() {
    if (confirm('Config cache temizlensin mi?')) {
        fetch('{{ route("admin.settings.clear-config") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Config cache başarıyla temizlendi!');
            } else {
                alert('Config cache temizlenirken hata oluştu.');
            }
        });
    }
}

function clearRoute() {
    if (confirm('Route cache temizlensin mi?')) {
        fetch('{{ route("admin.settings.clear-route") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Route cache başarıyla temizlendi!');
            } else {
                alert('Route cache temizlenirken hata oluştu.');
            }
        });
    }
}

function clearView() {
    if (confirm('View cache temizlensin mi?')) {
        fetch('{{ route("admin.settings.clear-view") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('View cache başarıyla temizlendi!');
            } else {
                alert('View cache temizlenirken hata oluştu.');
            }
        });
    }
}

// Check database connection
fetch('{{ route("admin.settings.check-database") }}')
    .then(response => response.json())
    .then(data => {
        const statusElement = document.getElementById('db-status');
        if (data.connected) {
            statusElement.innerHTML = '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">BAĞLI</span>';
        } else {
            statusElement.innerHTML = '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">BAĞLANTI YOK</span>';
        }
    })
    .catch(() => {
        document.getElementById('db-status').innerHTML = '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">HATA</span>';
    });

// Get disk usage
fetch('{{ route("admin.settings.disk-usage") }}')
    .then(response => response.json())
    .then(data => {
        document.getElementById('disk-usage').textContent = data.used;
        document.getElementById('free-space').textContent = data.free;
    })
    .catch(() => {
        document.getElementById('disk-usage').textContent = 'Hesaplanamadı';
        document.getElementById('free-space').textContent = 'Hesaplanamadı';
    });

// Calculate uptime (simple version)
const startTime = Date.now();
setInterval(() => {
    const uptime = Date.now() - startTime;
    const seconds = Math.floor(uptime / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    
    if (hours > 0) {
        document.getElementById('uptime').textContent = `${hours}s ${minutes % 60}d`;
    } else if (minutes > 0) {
        document.getElementById('uptime').textContent = `${minutes}d ${seconds % 60}s`;
    } else {
        document.getElementById('uptime').textContent = `${seconds}s`;
    }
}, 1000);
</script>
@endpush