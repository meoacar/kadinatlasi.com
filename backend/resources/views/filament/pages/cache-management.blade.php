<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Cache Temizleme -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Cache Temizleme</h3>
            <div class="space-y-3">
                <button 
                    wire:click="clearApplicationCache"
                    class="w-full bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                >
                    Uygulama Cache Temizle
                </button>
                <button 
                    wire:click="clearConfigCache"
                    class="w-full bg-orange-500 hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                >
                    Konfigürasyon Cache Temizle
                </button>
                <button 
                    wire:click="clearRouteCache"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                >
                    Route Cache Temizle
                </button>
                <button 
                    wire:click="clearViewCache"
                    class="w-full bg-purple-500 hover:bg-purple-600 dark:bg-purple-600 dark:hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                >
                    View Cache Temizle
                </button>
            </div>
        </div>

        <!-- Cache Oluşturma -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Cache Oluşturma</h3>
            <div class="space-y-3">
                <button 
                    wire:click="cacheConfig"
                    class="w-full bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                >
                    Konfigürasyon Cache Oluştur
                </button>
                <button 
                    wire:click="cacheRoutes"
                    class="w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                >
                    Route Cache Oluştur
                </button>
                <button 
                    wire:click="cacheViews"
                    class="w-full bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                >
                    View Cache Oluştur
                </button>
            </div>
        </div>
    </div>

    <!-- Cache Bilgileri -->
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Cache Bilgileri</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div class="text-sm text-gray-600 dark:text-gray-400">Cache Driver</div>
                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getCacheInfo()['cache_driver'] }}</div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div class="text-sm text-gray-600 dark:text-gray-400">Session Driver</div>
                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getCacheInfo()['session_driver'] }}</div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div class="text-sm text-gray-600 dark:text-gray-400">Queue Driver</div>
                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getCacheInfo()['queue_driver'] }}</div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div class="text-sm text-gray-600 dark:text-gray-400">Cache Prefix</div>
                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getCacheInfo()['cache_prefix'] ?: 'Yok' }}</div>
            </div>
        </div>
    </div>

    <!-- Uyarılar -->
    <div class="mt-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Dikkat</h3>
                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Cache temizleme işlemleri site performansını geçici olarak etkileyebilir.</li>
                        <li>Production ortamında cache oluşturma işlemlerini dikkatli yapın.</li>
                        <li>Route ve config cache oluşturduktan sonra değişiklikler için cache temizlemeniz gerekebilir.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>