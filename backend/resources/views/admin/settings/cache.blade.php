@extends('admin.layouts.app')

@section('title', 'Cache Yönetimi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Cache Yönetimi</h1>
            <p class="mt-1 text-sm text-gray-600">Sistem cache'ini yönetin ve performansı optimize edin</p>
        </div>
        <a href="{{ route('admin.settings.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Geri Dön
        </a>
    </div>

    <!-- Cache Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Cache Boyutu</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $cacheInfo['size'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Cache Anahtarları</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $cacheInfo['keys_count'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Cache Sürücüsü</p>
                    <p class="text-2xl font-bold text-gray-900">{{ strtoupper($cacheInfo['driver']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Son Temizleme</p>
                    <p class="text-lg font-bold text-gray-900">
                        @if($cacheInfo['last_cleared'])
                            {{ \Carbon\Carbon::parse($cacheInfo['last_cleared'])->diffForHumans() }}
                        @else
                            Hiç
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cache Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Cache Clear Options -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Cache Temizleme</h3>
            
            <div class="space-y-4">
                <!-- Clear All Cache -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Tüm Cache</h4>
                        <p class="text-sm text-gray-500">Tüm cache türlerini temizler</p>
                    </div>
                    <button onclick="clearCache('all')" 
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        Temizle
                    </button>
                </div>

                <!-- Clear Application Cache -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Uygulama Cache</h4>
                        <p class="text-sm text-gray-500">Uygulama verilerinin cache'ini temizler</p>
                    </div>
                    <button onclick="clearCache('application')" 
                            class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                        Temizle
                    </button>
                </div>

                <!-- Clear Config Cache -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Konfigürasyon Cache</h4>
                        <p class="text-sm text-gray-500">Ayar dosyalarının cache'ini temizler</p>
                    </div>
                    <button onclick="clearCache('config')" 
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Temizle
                    </button>
                </div>

                <!-- Clear Route Cache -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Route Cache</h4>
                        <p class="text-sm text-gray-500">URL rotalarının cache'ini temizler</p>
                    </div>
                    <button onclick="clearCache('route')" 
                            class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                        Temizle
                    </button>
                </div>

                <!-- Clear View Cache -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">View Cache</h4>
                        <p class="text-sm text-gray-500">Blade şablonlarının cache'ini temizler</p>
                    </div>
                    <button onclick="clearCache('view')" 
                            class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                        Temizle
                    </button>
                </div>
            </div>
        </div>

        <!-- Cache Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Cache Bilgileri</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-700">Cache Sürücüsü:</span>
                    <span class="text-sm text-gray-600">{{ strtoupper($cacheInfo['driver']) }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-700">Toplam Boyut:</span>
                    <span class="text-sm text-gray-600">{{ $cacheInfo['size'] }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-700">Anahtar Sayısı:</span>
                    <span class="text-sm text-gray-600">{{ $cacheInfo['keys_count'] }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-700">Son Temizleme:</span>
                    <span class="text-sm text-gray-600">
                        @if($cacheInfo['last_cleared'])
                            {{ \Carbon\Carbon::parse($cacheInfo['last_cleared'])->format('d.m.Y H:i') }}
                        @else
                            Hiç temizlenmemiş
                        @endif
                    </span>
                </div>

                <!-- Cache Status -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Cache Durumu</h4>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Cache sistemi aktif ve çalışıyor</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cache Optimization Tips -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Cache Optimizasyon İpuçları</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-0.5">
                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Düzenli Temizlik</h4>
                        <p class="text-sm text-gray-600">Cache'i düzenli olarak temizleyerek performansı artırın.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mt-0.5">
                            <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Seçici Temizlik</h4>
                        <p class="text-sm text-gray-600">Sadece gerekli cache türlerini temizleyerek zaman kazanın.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center mt-0.5">
                            <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Performans İzleme</h4>
                        <p class="text-sm text-gray-600">Cache boyutunu ve kullanımını düzenli olarak kontrol edin.</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center mt-0.5">
                            <svg class="w-3 h-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Güncelleme Sonrası</h4>
                        <p class="text-sm text-gray-600">Sistem güncellemelerinden sonra cache'i temizleyin.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center mt-0.5">
                            <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Sorun Giderme</h4>
                        <p class="text-sm text-gray-600">Beklenmeyen davranışlarda cache temizliği deneyin.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center mt-0.5">
                            <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Otomatik Yönetim</h4>
                        <p class="text-sm text-gray-600">Cache otomatik temizleme zamanlaması kurmayı düşünün.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Warning -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Dikkat!</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>Cache temizleme işlemi site performansını geçici olarak etkileyebilir. Yoğun saatlerde cache temizlemekten kaçının.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function clearCache(type) {
    const typeNames = {
        'all': 'tüm cache',
        'application': 'uygulama cache',
        'config': 'konfigürasyon cache',
        'route': 'route cache',
        'view': 'view cache'
    };
    
    if (!confirm(`${typeNames[type]} temizlensin mi?`)) {
        return;
    }
    
    try {
        const response = await fetch('{{ route("admin.settings.clear-cache") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                cache_type: type
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(`${typeNames[type]} başarıyla temizlendi.`);
            location.reload();
        } else {
            alert(result.message || 'Cache temizlenirken bir hata oluştu.');
        }
    } catch (error) {
        alert('Bir hata oluştu. Lütfen tekrar deneyin.');
    }
}
</script>
@endpush