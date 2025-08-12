<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Site Ayarları</h2>
            <p class="text-gray-600 dark:text-gray-400">Sitenizin tüm global ayarlarını buradan yönetebilirsiniz</p>
        </div>

        <!-- Settings Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <form method="POST" action="{{ route('filament.admin.pages.site-settings.save') }}">
                @csrf
                
                <!-- Tabs -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button type="button" class="tab-button active border-pink-500 text-pink-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="general">
                            Genel Ayarlar
                        </button>
                        <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="seo">
                            SEO & Meta
                        </button>
                        <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="email">
                            E-posta Ayarları
                        </button>
                        <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="social">
                            Sosyal Medya
                        </button>
                        <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="security">
                            Güvenlik
                        </button>
                        <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="features">
                            Özellikler
                        </button>
                    </nav>
                </div>

                <!-- General Settings Tab -->
                <div id="general-tab" class="tab-content p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Genel Site Ayarları</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Site Adı</label>
                            <input type="text" name="site_name" value="{{ \App\Models\SiteSetting::get('site_name', 'KadınAtlası.com') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Admin E-posta</label>
                            <input type="email" name="admin_email" value="{{ \App\Models\SiteSetting::get('admin_email', 'admin@kadinatlasi.com') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Site Açıklaması</label>
                            <textarea name="site_description" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">{{ \App\Models\SiteSetting::get('site_description', 'Kadınların günlük hayatını kolaylaştıran dijital platform') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Site Durumu & Özellikler</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Bakım Modu -->
                                <div class="{{ \App\Models\SiteSetting::get('maintenance_mode', '0') === '1' ? 'bg-gradient-to-br from-orange-50 to-red-50 border-orange-200' : 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-200' }} dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 border-2 transition-all duration-300 hover:shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 {{ \App\Models\SiteSetting::get('maintenance_mode', '0') === '1' ? 'bg-orange-100 text-orange-600' : 'bg-green-100 text-green-600' }} rounded-xl flex items-center justify-center">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="text-lg font-bold {{ \App\Models\SiteSetting::get('maintenance_mode', '0') === '1' ? 'text-orange-800' : 'text-green-800' }} dark:text-gray-100">Bakım Modu</h5>
                                                <p class="text-sm {{ \App\Models\SiteSetting::get('maintenance_mode', '0') === '1' ? 'text-orange-600 font-semibold' : 'text-green-600 font-semibold' }} dark:text-gray-400">
                                                    {{ \App\Models\SiteSetting::get('maintenance_mode', '0') === '1' ? 'AKTİF - Site Kapalı' : 'PASİF - Site Açık' }}
                                                </p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="maintenance_mode" value="1" 
                                                   {{ \App\Models\SiteSetting::get('maintenance_mode', '0') === '1' ? 'checked' : '' }}
                                                   class="sr-only peer toggle-switch">
                                            <div class="w-16 h-8 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-8 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-orange-400 peer-checked:to-red-500 shadow-lg"></div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Kullanıcı Kaydı -->
                                <div class="{{ \App\Models\SiteSetting::get('user_registration', '1') === '1' ? 'bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-200' : 'bg-gradient-to-br from-gray-50 to-slate-50 border-gray-300' }} dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 border-2 transition-all duration-300 hover:shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 {{ \App\Models\SiteSetting::get('user_registration', '1') === '1' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500' }} rounded-xl flex items-center justify-center">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="text-lg font-bold {{ \App\Models\SiteSetting::get('user_registration', '1') === '1' ? 'text-blue-800' : 'text-gray-600' }} dark:text-gray-100">Kullanıcı Kaydı</h5>
                                                <p class="text-sm {{ \App\Models\SiteSetting::get('user_registration', '1') === '1' ? 'text-blue-600 font-semibold' : 'text-gray-500 font-semibold' }} dark:text-gray-400">
                                                    {{ \App\Models\SiteSetting::get('user_registration', '1') === '1' ? 'AKTİF - Kayıtlar Açık' : 'PASİF - Kayıtlar Kapalı' }}
                                                </p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="user_registration" value="1" 
                                                   {{ \App\Models\SiteSetting::get('user_registration', '1') === '1' ? 'checked' : '' }}
                                                   class="sr-only peer toggle-switch">
                                            <div class="w-16 h-8 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-8 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-blue-400 peer-checked:to-indigo-500 shadow-lg"></div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Yorumlar -->
                                <div class="{{ \App\Models\SiteSetting::get('enable_comments', '1') === '1' ? 'bg-gradient-to-br from-purple-50 to-pink-50 border-purple-200' : 'bg-gradient-to-br from-gray-50 to-slate-50 border-gray-300' }} dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 border-2 transition-all duration-300 hover:shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 {{ \App\Models\SiteSetting::get('enable_comments', '1') === '1' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-500' }} rounded-xl flex items-center justify-center">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="text-lg font-bold {{ \App\Models\SiteSetting::get('enable_comments', '1') === '1' ? 'text-purple-800' : 'text-gray-600' }} dark:text-gray-100">Yorumlar</h5>
                                                <p class="text-sm {{ \App\Models\SiteSetting::get('enable_comments', '1') === '1' ? 'text-purple-600 font-semibold' : 'text-gray-500 font-semibold' }} dark:text-gray-400">
                                                    {{ \App\Models\SiteSetting::get('enable_comments', '1') === '1' ? 'AKTİF - Yorumlar Açık' : 'PASİF - Yorumlar Kapalı' }}
                                                </p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="enable_comments" value="1" 
                                                   {{ \App\Models\SiteSetting::get('enable_comments', '1') === '1' ? 'checked' : '' }}
                                                   class="sr-only peer toggle-switch">
                                            <div class="w-16 h-8 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-8 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-purple-400 peer-checked:to-pink-500 shadow-lg"></div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Forum -->
                                <div class="{{ \App\Models\SiteSetting::get('enable_forum', '1') === '1' ? 'bg-gradient-to-br from-emerald-50 to-teal-50 border-emerald-200' : 'bg-gradient-to-br from-gray-50 to-slate-50 border-gray-300' }} dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 border-2 transition-all duration-300 hover:shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 {{ \App\Models\SiteSetting::get('enable_forum', '1') === '1' ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-100 text-gray-500' }} rounded-xl flex items-center justify-center">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="text-lg font-bold {{ \App\Models\SiteSetting::get('enable_forum', '1') === '1' ? 'text-emerald-800' : 'text-gray-600' }} dark:text-gray-100">Forum</h5>
                                                <p class="text-sm {{ \App\Models\SiteSetting::get('enable_forum', '1') === '1' ? 'text-emerald-600 font-semibold' : 'text-gray-500 font-semibold' }} dark:text-gray-400">
                                                    {{ \App\Models\SiteSetting::get('enable_forum', '1') === '1' ? 'AKTİF - Forum Açık' : 'PASİF - Forum Kapalı' }}
                                                </p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="enable_forum" value="1" 
                                                   {{ \App\Models\SiteSetting::get('enable_forum', '1') === '1' ? 'checked' : '' }}
                                                   class="sr-only peer toggle-switch">
                                            <div class="w-16 h-8 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-8 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-emerald-400 peer-checked:to-teal-500 shadow-lg"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings Tab -->
                <div id="seo-tab" class="tab-content p-6 hidden">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">SEO & Meta Ayarları</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SEO Başlık (Title)</label>
                            <input type="text" name="seo_title" value="{{ \App\Models\SiteSetting::get('seo_title', 'KadınAtlası - Kadınlar İçin Dijital Platform') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                            <p class="text-xs text-gray-500 mt-1">Arama motorlarında görünecek başlık (60 karakter önerilir)</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meta Açıklama</label>
                            <textarea name="seo_description" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">{{ \App\Models\SiteSetting::get('seo_description', 'Kadınların günlük hayatını kolaylaştıran, sağlık, kariyer, gebelik ve yaşam konularında rehberlik eden dijital platform.') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Arama sonuçlarında görünecek açıklama (160 karakter önerilir)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Anahtar Kelimeler</label>
                            <input type="text" name="seo_keywords" value="{{ \App\Models\SiteSetting::get('seo_keywords', 'kadın, sağlık, gebelik, kariyer, yaşam, hesaplayıcı, forum') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                            <p class="text-xs text-gray-500 mt-1">Virgülle ayırarak yazın</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Canonical URL</label>
                            <input type="url" name="seo_canonical" value="{{ \App\Models\SiteSetting::get('seo_canonical', 'https://kadinatlasi.com') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Google Analytics ID</label>
                            <input type="text" name="google_analytics" value="{{ \App\Models\SiteSetting::get('google_analytics', '') }}" 
                                   placeholder="G-XXXXXXXXXX"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Google Search Console</label>
                            <input type="text" name="google_search_console" value="{{ \App\Models\SiteSetting::get('google_search_console', '') }}" 
                                   placeholder="google-site-verification=..."
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Open Graph Görseli URL</label>
                            <input type="url" name="og_image" value="{{ \App\Models\SiteSetting::get('og_image', '') }}" 
                                   placeholder="https://kadinatlasi.com/images/og-image.jpg"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                            <p class="text-xs text-gray-500 mt-1">Sosyal medyada paylaşıldığında görünecek görsel (1200x630px önerilir)</p>
                        </div>
                        <div class="md:col-span-2">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">SEO Özellikleri</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="seo_sitemap_enabled" value="1" 
                                           {{ \App\Models\SiteSetting::get('seo_sitemap_enabled', '1') === '1' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">XML Sitemap Aktif</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="seo_robots_enabled" value="1" 
                                           {{ \App\Models\SiteSetting::get('seo_robots_enabled', '1') === '1' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Robots.txt Aktif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email Settings Tab -->
                <div id="email-tab" class="tab-content p-6 hidden">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">E-posta Ayarları</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SMTP Sunucu</label>
                            <input type="text" name="mail_host" value="{{ \App\Models\SiteSetting::get('mail_host', 'smtp.gmail.com') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SMTP Port</label>
                            <input type="number" name="mail_port" value="{{ \App\Models\SiteSetting::get('mail_port', '587') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">E-posta Adresi</label>
                            <input type="email" name="mail_username" value="{{ \App\Models\SiteSetting::get('mail_username', '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">E-posta Şifresi</label>
                            <input type="password" name="mail_password" value="{{ \App\Models\SiteSetting::get('mail_password', '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gönderen Adı</label>
                            <input type="text" name="mail_from_name" value="{{ \App\Models\SiteSetting::get('mail_from_name', 'KadınAtlası') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Şifreleme</label>
                            <select name="mail_encryption" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                                <option value="tls" {{ \App\Models\SiteSetting::get('mail_encryption', 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ \App\Models\SiteSetting::get('mail_encryption', 'tls') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="" {{ \App\Models\SiteSetting::get('mail_encryption', 'tls') === '' ? 'selected' : '' }}>Yok</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">E-posta Şablonları</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hoş Geldin E-postası Konusu</label>
                                    <input type="text" name="welcome_email_subject" value="{{ \App\Models\SiteSetting::get('welcome_email_subject', 'KadınAtlası\'na Hoş Geldiniz!') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Şifre Sıfırlama Konusu</label>
                                    <input type="text" name="reset_password_subject" value="{{ \App\Models\SiteSetting::get('reset_password_subject', 'Şifre Sıfırlama Talebi') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">E-posta Özellikleri</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="email_notifications" value="1" 
                                           {{ \App\Models\SiteSetting::get('email_notifications', '1') === '1' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">E-posta Bildirimleri Aktif</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="newsletter_enabled" value="1" 
                                           {{ \App\Models\SiteSetting::get('newsletter_enabled', '1') === '1' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Bülten Sistemi Aktif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media Tab -->
                <div id="social-tab" class="tab-content p-6 hidden">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Sosyal Medya Ayarları</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Facebook URL</label>
                            <input type="url" name="facebook_url" value="{{ \App\Models\SiteSetting::get('facebook_url', '') }}" 
                                   placeholder="https://facebook.com/kadinatlasi"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Instagram URL</label>
                            <input type="url" name="instagram_url" value="{{ \App\Models\SiteSetting::get('instagram_url', '') }}" 
                                   placeholder="https://instagram.com/kadinatlasi"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Twitter URL</label>
                            <input type="url" name="twitter_url" value="{{ \App\Models\SiteSetting::get('twitter_url', '') }}" 
                                   placeholder="https://twitter.com/kadinatlasi"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">YouTube URL</label>
                            <input type="url" name="youtube_url" value="{{ \App\Models\SiteSetting::get('youtube_url', '') }}" 
                                   placeholder="https://youtube.com/@kadinatlasi"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">LinkedIn URL</label>
                            <input type="url" name="linkedin_url" value="{{ \App\Models\SiteSetting::get('linkedin_url', '') }}" 
                                   placeholder="https://linkedin.com/company/kadinatlasi"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">TikTok URL</label>
                            <input type="url" name="tiktok_url" value="{{ \App\Models\SiteSetting::get('tiktok_url', '') }}" 
                                   placeholder="https://tiktok.com/@kadinatlasi"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div class="md:col-span-2">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Sosyal Medya Entegrasyonu</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Facebook App ID</label>
                                    <input type="text" name="facebook_app_id" value="{{ \App\Models\SiteSetting::get('facebook_app_id', '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Twitter Site Handle</label>
                                    <input type="text" name="twitter_site" value="{{ \App\Models\SiteSetting::get('twitter_site', '@kadinatlasi') }}" 
                                           placeholder="@kadinatlasi"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Sosyal Medya Özellikleri</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="social_login_facebook" value="1" 
                                           {{ \App\Models\SiteSetting::get('social_login_facebook', '0') === '1' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Facebook Girişi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="social_login_google" value="1" 
                                           {{ \App\Models\SiteSetting::get('social_login_google', '0') === '1' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Google Girişi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="social_sharing" value="1" 
                                           {{ \App\Models\SiteSetting::get('social_sharing', '1') === '1' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Sosyal Paylaşım</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div id="security-tab" class="tab-content p-6 hidden">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Güvenlik Ayarları</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Minimum Şifre Uzunluğu</label>
                            <input type="number" name="min_password_length" value="{{ \App\Models\SiteSetting::get('min_password_length', '8') }}" 
                                   min="6" max="20"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Oturum Süresi (dakika)</label>
                            <input type="number" name="session_lifetime" value="{{ \App\Models\SiteSetting::get('session_lifetime', '120') }}" 
                                   min="30" max="1440"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Maksimum Giriş Denemesi</label>
                            <input type="number" name="max_login_attempts" value="{{ \App\Models\SiteSetting::get('max_login_attempts', '5') }}" 
                                   min="3" max="10"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hesap Kilitleme Süresi (dakika)</label>
                            <input type="number" name="lockout_duration" value="{{ \App\Models\SiteSetting::get('lockout_duration', '15') }}" 
                                   min="5" max="60"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">İzin Verilen Dosya Türleri</label>
                            <input type="text" name="allowed_file_types" value="{{ \App\Models\SiteSetting::get('allowed_file_types', 'jpg,jpeg,png,gif,pdf,doc,docx') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                            <p class="text-xs text-gray-500 mt-1">Virgülle ayırarak yazın (örn: jpg,png,pdf)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Maksimum Dosya Boyutu (MB)</label>
                            <input type="number" name="max_file_size" value="{{ \App\Models\SiteSetting::get('max_file_size', '10') }}" 
                                   min="1" max="100"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rate Limit (dakikada istek)</label>
                            <input type="number" name="rate_limit" value="{{ \App\Models\SiteSetting::get('rate_limit', '60') }}" 
                                   min="10" max="1000"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div class="md:col-span-2">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Güvenlik Özellikleri</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="two_factor_auth" value="1" 
                                           {{ \App\Models\SiteSetting::get('two_factor_auth', '0') === '1' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">İki Faktörlü Kimlik Doğrulama</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="email_verification" value="1" 
                                           {{ \App\Models\SiteSetting::get('email_verification', '1') === '1' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">E-posta Doğrulaması Zorunlu</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="captcha_enabled" value="1" 
                                           {{ \App\Models\SiteSetting::get('captcha_enabled', '1') === '1' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">CAPTCHA Aktif</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="force_https" value="1" 
                                           {{ \App\Models\SiteSetting::get('force_https', '1') === '1' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">HTTPS Zorunlu</span>
                                </label>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">reCAPTCHA Ayarları</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">reCAPTCHA Site Key</label>
                                    <input type="text" name="recaptcha_site_key" value="{{ \App\Models\SiteSetting::get('recaptcha_site_key', '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">reCAPTCHA Secret Key</label>
                                    <input type="password" name="recaptcha_secret_key" value="{{ \App\Models\SiteSetting::get('recaptcha_secret_key', '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Tab -->
                <div id="features-tab" class="tab-content p-6 hidden">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Site Özellikleri</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">Ana Özellikler</h4>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 rounded-full transition-colors duration-300 {{ \App\Models\SiteSetting::get('enable_blog', '1') === '1' ? 'bg-green-500' : 'bg-red-500' }} mr-3"></div>
                                                <div>
                                                    <h5 class="font-medium text-gray-900 dark:text-gray-100">Blog Sistemi</h5>
                                                    <p class="text-xs {{ \App\Models\SiteSetting::get('enable_blog', '1') === '1' ? 'text-green-600 font-medium' : 'text-red-600 font-medium' }} dark:text-gray-400">
                                                        {{ \App\Models\SiteSetting::get('enable_blog', '1') === '1' ? 'AKTİF' : 'PASİF' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="enable_blog" value="1" 
                                                       {{ \App\Models\SiteSetting::get('enable_blog', '1') === '1' ? 'checked' : '' }}
                                                       class="sr-only peer toggle-switch">
                                                <div class="w-11 h-6 {{ \App\Models\SiteSetting::get('enable_blog', '1') === '1' ? 'bg-green-500' : 'bg-red-500' }} rounded-full peer peer-focus:outline-none peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 rounded-full transition-colors duration-300 {{ \App\Models\SiteSetting::get('enable_calculators', '1') === '1' ? 'bg-green-500' : 'bg-red-500' }} mr-3"></div>
                                                <div>
                                                    <h5 class="font-medium text-gray-900 dark:text-gray-100">Hesaplayıcılar</h5>
                                                    <p class="text-xs {{ \App\Models\SiteSetting::get('enable_calculators', '1') === '1' ? 'text-green-600 font-medium' : 'text-red-600 font-medium' }} dark:text-gray-400">
                                                        {{ \App\Models\SiteSetting::get('enable_calculators', '1') === '1' ? 'AKTİF' : 'PASİF' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="enable_calculators" value="1" 
                                                       {{ \App\Models\SiteSetting::get('enable_calculators', '1') === '1' ? 'checked' : '' }}
                                                       class="sr-only peer toggle-switch">
                                                <div class="w-11 h-6 {{ \App\Models\SiteSetting::get('enable_calculators', '1') === '1' ? 'bg-green-500' : 'bg-red-500' }} rounded-full peer peer-focus:outline-none peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 rounded-full transition-colors duration-300 {{ \App\Models\SiteSetting::get('enable_astrology', '1') === '1' ? 'bg-green-500' : 'bg-red-500' }} mr-3"></div>
                                                <div>
                                                    <h5 class="font-medium text-gray-900 dark:text-gray-100">Astroloji Modülü</h5>
                                                    <p class="text-xs {{ \App\Models\SiteSetting::get('enable_astrology', '1') === '1' ? 'text-green-600 font-medium' : 'text-red-600 font-medium' }} dark:text-gray-400">
                                                        {{ \App\Models\SiteSetting::get('enable_astrology', '1') === '1' ? 'AKTİF' : 'PASİF' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="enable_astrology" value="1" 
                                                       {{ \App\Models\SiteSetting::get('enable_astrology', '1') === '1' ? 'checked' : '' }}
                                                       class="sr-only peer toggle-switch">
                                                <div class="w-11 h-6 {{ \App\Models\SiteSetting::get('enable_astrology', '1') === '1' ? 'bg-green-500' : 'bg-red-500' }} rounded-full peer peer-focus:outline-none peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 rounded-full transition-colors duration-300 {{ \App\Models\SiteSetting::get('enable_notifications', '1') === '1' ? 'bg-green-500' : 'bg-red-500' }} mr-3"></div>
                                                <div>
                                                    <h5 class="font-medium text-gray-900 dark:text-gray-100">Bildirimler</h5>
                                                    <p class="text-xs {{ \App\Models\SiteSetting::get('enable_notifications', '1') === '1' ? 'text-green-600 font-medium' : 'text-red-600 font-medium' }} dark:text-gray-400">
                                                        {{ \App\Models\SiteSetting::get('enable_notifications', '1') === '1' ? 'AKTİF' : 'PASİF' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="enable_notifications" value="1" 
                                                       {{ \App\Models\SiteSetting::get('enable_notifications', '1') === '1' ? 'checked' : '' }}
                                                       class="sr-only peer toggle-switch">
                                                <div class="w-11 h-6 {{ \App\Models\SiteSetting::get('enable_notifications', '1') === '1' ? 'bg-green-500' : 'bg-red-500' }} rounded-full peer peer-focus:outline-none peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">Sayfa Ayarları</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sayfa Başına Blog Yazısı</label>
                                    <input type="number" name="posts_per_page" value="{{ \App\Models\SiteSetting::get('posts_per_page', '10') }}" 
                                           min="5" max="50"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sayfa Başına Forum Konusu</label>
                                    <input type="number" name="topics_per_page" value="{{ \App\Models\SiteSetting::get('topics_per_page', '15') }}" 
                                           min="5" max="50"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">Cache Ayarları</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cache Süresi (dakika)</label>
                                    <input type="number" name="cache_duration" value="{{ \App\Models\SiteSetting::get('cache_duration', '60') }}" 
                                           min="5" max="1440"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-gray-100">
                                </div>
                                <div class="flex items-center">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="enable_cache" value="1" 
                                               {{ \App\Models\SiteSetting::get('enable_cache', '1') === '1' ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Cache Sistemi Aktif</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="px-6 py-4 bg-pink-50 dark:bg-pink-900/20 border-t-2 border-pink-200 dark:border-pink-700 rounded-b-lg">
                    <div class="flex justify-center">
                        <button type="submit" 
                                class="px-8 py-3 bg-pink-600 text-white text-lg font-semibold rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-4 focus:ring-pink-300 shadow-lg transform hover:scale-105 transition-all duration-200">
                            🔄 Tüm Ayarları Kaydet
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tabButtons = document.querySelectorAll('.tab-button');
                const tabContents = document.querySelectorAll('.tab-content');

                tabButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const targetTab = this.getAttribute('data-tab');
                        
                        tabButtons.forEach(btn => {
                            btn.classList.remove('active', 'border-pink-500', 'text-pink-600');
                            btn.classList.add('border-transparent', 'text-gray-500');
                        });
                        
                        this.classList.add('active', 'border-pink-500', 'text-pink-600');
                        this.classList.remove('border-transparent', 'text-gray-500');
                        
                        tabContents.forEach(content => {
                            content.classList.add('hidden');
                        });
                        
                        document.getElementById(targetTab + '-tab').classList.remove('hidden');
                    });
                });

                const toggleSwitches = document.querySelectorAll('.toggle-switch');
                toggleSwitches.forEach(toggle => {
                    toggle.addEventListener('change', function() {
                        const toggleBg = this.nextElementSibling;
                        const statusDot = this.closest('.bg-white').querySelector('.w-3.h-3');
                        const statusText = this.closest('.bg-white').querySelector('p');
                        
                        if (this.checked) {
                            this.nextElementSibling.classList.remove('bg-red-500');
                            this.nextElementSibling.classList.add('bg-green-500');
                        } else {
                            this.nextElementSibling.classList.remove('bg-green-500');
                            this.nextElementSibling.classList.add('bg-red-500');
                        }
                        
                        if (this.name === 'maintenance_mode') {
                            if (this.checked) {
                                statusDot.classList.remove('bg-green-500');
                                statusDot.classList.add('bg-orange-500');
                                statusText.textContent = 'AKTİF - Site kapalı';
                                statusText.className = 'text-xs text-orange-600 font-medium dark:text-gray-400';
                            } else {
                                statusDot.classList.remove('bg-orange-500');
                                statusDot.classList.add('bg-green-500');
                                statusText.textContent = 'PASİF - Site açık';
                                statusText.className = 'text-xs text-gray-500 dark:text-gray-400';
                            }
                        } else {
                            if (this.checked) {
                                statusDot.classList.remove('bg-red-500');
                                statusDot.classList.add('bg-green-500');
                                statusText.className = 'text-xs text-green-600 font-medium dark:text-gray-400';
                                
                                if (this.name === 'user_registration') statusText.textContent = 'AKTİF - Kayıtlar açık';
                                else if (this.name === 'enable_comments') statusText.textContent = 'AKTİF - Yorumlar açık';
                                else if (this.name === 'enable_forum') statusText.textContent = 'AKTİF - Forum açık';
                            } else {
                                statusDot.classList.remove('bg-green-500');
                                statusDot.classList.add('bg-red-500');
                                statusText.className = 'text-xs text-red-600 font-medium dark:text-gray-400';
                                
                                if (this.name === 'user_registration') statusText.textContent = 'PASİF - Kayıtlar kapalı';
                                else if (this.name === 'enable_comments') statusText.textContent = 'PASİF - Yorumlar kapalı';
                                else if (this.name === 'enable_forum') statusText.textContent = 'PASİF - Forum kapalı';
                            }
                        }
                    });
                });
            });
        </script>
    </div>
</x-filament-panels::page>