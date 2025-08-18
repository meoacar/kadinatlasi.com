<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- PHP Bilgileri -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">PHP Bilgileri</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">PHP Sürümü:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['php_version'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Bellek Limiti:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['memory_limit'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Maksimum Çalışma Süresi:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['max_execution_time'] }}s</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Upload Limit:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['upload_max_filesize'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">POST Limit:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['post_max_size'] }}</span>
                </div>
            </div>
        </div>

        <!-- Laravel Bilgileri -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Laravel Bilgileri</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Laravel Sürümü:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['laravel_version'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Ortam:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['environment'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Debug Modu:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['debug_mode'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Zaman Dilimi:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['timezone'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Dil:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['locale'] }}</span>
                </div>
            </div>
        </div>

        <!-- Sunucu Bilgileri -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Sunucu Bilgileri</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Sunucu Yazılımı:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['server_software'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Veritabanı:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['database_version'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Boş Disk Alanı:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['disk_free_space'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Toplam Disk Alanı:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['disk_total_space'] }}</span>
                </div>
            </div>
        </div>

        <!-- Performans Bilgileri -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Performans Bilgileri</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Mevcut Bellek Kullanımı:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['memory_usage'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Pik Bellek Kullanımı:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['memory_peak_usage'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Cache Driver:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['cache_driver'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Session Driver:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['session_driver'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Queue Driver:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['queue_driver'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Mail Driver:</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->getSystemInfo()['mail_driver'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sistem Durumu -->
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Sistem Durumu</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ \App\Models\User::count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Toplam Kullanıcı</div>
            </div>
            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ \App\Models\BlogPost::count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Blog Yazıları</div>
            </div>
            <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ \App\Models\SystemLog::whereDate('created_at', today())->count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Bugünkü Loglar</div>
            </div>
            <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ \App\Models\SystemLog::where('level', 'error')->whereDate('created_at', today())->count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Bugünkü Hatalar</div>
            </div>
        </div>
    </div>
</x-filament-panels::page>