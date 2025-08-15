@extends('admin.layouts.app')

@section('title', 'SEO Ayarları')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">SEO Ayarları</h1>
            <p class="mt-1 text-sm text-gray-600">Arama motoru optimizasyonu ayarlarını yapılandırın</p>
        </div>
        <a href="{{ route('admin.settings.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Geri Dön
        </a>
    </div>

    <form method="POST" action="{{ route('admin.settings.update-seo') }}" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Meta Tags -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Meta Etiketleri</h3>
                    
                    <div class="space-y-4">
                        <!-- Site Title -->
                        <div>
                            <label for="seo_site_title" class="block text-sm font-medium text-gray-700 mb-1">
                                Site Başlığı <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="seo_site_title" name="seo_site_title" 
                                   value="{{ old('seo_site_title', $settings['seo_site_title'] ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('seo_site_title') border-red-300 @enderror"
                                   placeholder="KadınAtlası - Kadın Girişimciler Platformu">
                            <p class="mt-1 text-xs text-gray-500">Tarayıcı sekmesinde görünecek başlık (50-60 karakter önerilir)</p>
                            @error('seo_site_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="seo_meta_description" class="block text-sm font-medium text-gray-700 mb-1">
                                Meta Açıklama <span class="text-red-500">*</span>
                            </label>
                            <textarea id="seo_meta_description" name="seo_meta_description" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('seo_meta_description') border-red-300 @enderror"
                                      placeholder="Kadın girişimciler için özel olarak tasarlanmış platform. Blog yazıları, ürün satışı ve forum ile kadınları destekliyoruz.">{{ old('seo_meta_description', $settings['seo_meta_description'] ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Arama sonuçlarında görünecek açıklama (150-160 karakter önerilir)</p>
                            @error('seo_meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Keywords -->
                        <div>
                            <label for="seo_meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">
                                Meta Anahtar Kelimeler
                            </label>
                            <input type="text" id="seo_meta_keywords" name="seo_meta_keywords" 
                                   value="{{ old('seo_meta_keywords', $settings['seo_meta_keywords'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="kadın girişimci, kadın, blog, ürün, forum, platform">
                            <p class="mt-1 text-xs text-gray-500">Virgülle ayrılmış anahtar kelimeler</p>
                        </div>

                        <!-- Canonical URL -->
                        <div>
                            <label for="seo_canonical_url" class="block text-sm font-medium text-gray-700 mb-1">
                                Canonical URL
                            </label>
                            <input type="url" id="seo_canonical_url" name="seo_canonical_url" 
                                   value="{{ old('seo_canonical_url', $settings['seo_canonical_url'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="https://kadinatlasi.com">
                            <p class="mt-1 text-xs text-gray-500">Sitenizin ana URL'si</p>
                        </div>
                    </div>
                </div>

                <!-- Open Graph -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Open Graph (Sosyal Medya)</h3>
                    
                    <div class="space-y-4">
                        <!-- OG Title -->
                        <div>
                            <label for="seo_og_title" class="block text-sm font-medium text-gray-700 mb-1">
                                OG Başlık
                            </label>
                            <input type="text" id="seo_og_title" name="seo_og_title" 
                                   value="{{ old('seo_og_title', $settings['seo_og_title'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="KadınAtlası - Kadın Girişimciler Platformu">
                            <p class="mt-1 text-xs text-gray-500">Sosyal medyada paylaşıldığında görünecek başlık</p>
                        </div>

                        <!-- OG Description -->
                        <div>
                            <label for="seo_og_description" class="block text-sm font-medium text-gray-700 mb-1">
                                OG Açıklama
                            </label>
                            <textarea id="seo_og_description" name="seo_og_description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Kadın girişimciler için özel platform. Blog, ürün satışı ve forum ile kadınları destekliyoruz.">{{ old('seo_og_description', $settings['seo_og_description'] ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Sosyal medyada paylaşıldığında görünecek açıklama</p>
                        </div>

                        <!-- OG Image -->
                        <div>
                            <label for="seo_og_image" class="block text-sm font-medium text-gray-700 mb-1">
                                OG Resim URL
                            </label>
                            <input type="url" id="seo_og_image" name="seo_og_image" 
                                   value="{{ old('seo_og_image', $settings['seo_og_image'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="https://kadinatlasi.com/images/og-image.jpg">
                            <p class="mt-1 text-xs text-gray-500">Sosyal medyada paylaşıldığında görünecek resim (1200x630 önerilir)</p>
                        </div>

                        <!-- OG Type -->
                        <div>
                            <label for="seo_og_type" class="block text-sm font-medium text-gray-700 mb-1">
                                OG Tip
                            </label>
                            <select id="seo_og_type" name="seo_og_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="website" {{ old('seo_og_type', $settings['seo_og_type'] ?? 'website') === 'website' ? 'selected' : '' }}>Website</option>
                                <option value="article" {{ old('seo_og_type', $settings['seo_og_type'] ?? 'website') === 'article' ? 'selected' : '' }}>Article</option>
                                <option value="blog" {{ old('seo_og_type', $settings['seo_og_type'] ?? 'website') === 'blog' ? 'selected' : '' }}>Blog</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Twitter Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Twitter Card</h3>
                    
                    <div class="space-y-4">
                        <!-- Twitter Card Type -->
                        <div>
                            <label for="seo_twitter_card" class="block text-sm font-medium text-gray-700 mb-1">
                                Twitter Card Tipi
                            </label>
                            <select id="seo_twitter_card" name="seo_twitter_card"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="summary" {{ old('seo_twitter_card', $settings['seo_twitter_card'] ?? 'summary') === 'summary' ? 'selected' : '' }}>Summary</option>
                                <option value="summary_large_image" {{ old('seo_twitter_card', $settings['seo_twitter_card'] ?? 'summary') === 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
                            </select>
                        </div>

                        <!-- Twitter Site -->
                        <div>
                            <label for="seo_twitter_site" class="block text-sm font-medium text-gray-700 mb-1">
                                Twitter Hesabı
                            </label>
                            <input type="text" id="seo_twitter_site" name="seo_twitter_site" 
                                   value="{{ old('seo_twitter_site', $settings['seo_twitter_site'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="@kadinatlasi">
                            <p class="mt-1 text-xs text-gray-500">@ işareti ile birlikte Twitter kullanıcı adı</p>
                        </div>
                    </div>
                </div>

                <!-- Analytics -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Analytics ve Tracking</h3>
                    
                    <div class="space-y-4">
                        <!-- Google Analytics -->
                        <div>
                            <label for="seo_google_analytics" class="block text-sm font-medium text-gray-700 mb-1">
                                Google Analytics ID
                            </label>
                            <input type="text" id="seo_google_analytics" name="seo_google_analytics" 
                                   value="{{ old('seo_google_analytics', $settings['seo_google_analytics'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="G-XXXXXXXXXX">
                            <p class="mt-1 text-xs text-gray-500">Google Analytics 4 Measurement ID</p>
                        </div>

                        <!-- Google Tag Manager -->
                        <div>
                            <label for="seo_google_tag_manager" class="block text-sm font-medium text-gray-700 mb-1">
                                Google Tag Manager ID
                            </label>
                            <input type="text" id="seo_google_tag_manager" name="seo_google_tag_manager" 
                                   value="{{ old('seo_google_tag_manager', $settings['seo_google_tag_manager'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="GTM-XXXXXXX">
                            <p class="mt-1 text-xs text-gray-500">Google Tag Manager Container ID</p>
                        </div>

                        <!-- Facebook Pixel -->
                        <div>
                            <label for="seo_facebook_pixel" class="block text-sm font-medium text-gray-700 mb-1">
                                Facebook Pixel ID
                            </label>
                            <input type="text" id="seo_facebook_pixel" name="seo_facebook_pixel" 
                                   value="{{ old('seo_facebook_pixel', $settings['seo_facebook_pixel'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="123456789012345">
                            <p class="mt-1 text-xs text-gray-500">Facebook Pixel ID numarası</p>
                        </div>
                    </div>
                </div>

                <!-- Robots.txt -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Robots.txt</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="seo_robots_txt" class="block text-sm font-medium text-gray-700 mb-1">
                                Robots.txt İçeriği
                            </label>
                            <textarea id="seo_robots_txt" name="seo_robots_txt" rows="8"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm"
                                      placeholder="User-agent: *&#10;Disallow: /admin/&#10;Disallow: /api/&#10;&#10;Sitemap: https://kadinatlasi.com/sitemap.xml">{{ old('seo_robots_txt', $settings['seo_robots_txt'] ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Arama motorları için robots.txt dosya içeriği</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- SEO Status -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Durumu</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">Site Başlığı:</span>
                            <span class="text-sm text-gray-600">
                                @if($settings['seo_site_title'] ?? false)
                                    <span class="text-green-600">✓</span>
                                @else
                                    <span class="text-red-600">✗</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">Meta Açıklama:</span>
                            <span class="text-sm text-gray-600">
                                @if($settings['seo_meta_description'] ?? false)
                                    <span class="text-green-600">✓</span>
                                @else
                                    <span class="text-red-600">✗</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">OG Etiketleri:</span>
                            <span class="text-sm text-gray-600">
                                @if(($settings['seo_og_title'] ?? false) && ($settings['seo_og_description'] ?? false))
                                    <span class="text-green-600">✓</span>
                                @else
                                    <span class="text-red-600">✗</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">Analytics:</span>
                            <span class="text-sm text-gray-600">
                                @if($settings['seo_google_analytics'] ?? false)
                                    <span class="text-green-600">✓</span>
                                @else
                                    <span class="text-red-600">✗</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex flex-col space-y-3">
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Ayarları Kaydet
                        </button>
                        <a href="{{ route('admin.settings.index') }}" 
                           class="w-full px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors text-center">
                            İptal
                        </a>
                    </div>
                </div>

                <!-- SEO Tools -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Araçları</h3>
                    
                    <div class="space-y-3">
                        <a href="/sitemap.xml" target="_blank" 
                           class="w-full px-4 py-2 bg-green-100 text-green-700 text-sm font-medium rounded-lg hover:bg-green-200 transition-colors text-center block">
                            Sitemap'i Görüntüle
                        </a>
                        <a href="/robots.txt" target="_blank" 
                           class="w-full px-4 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 transition-colors text-center block">
                            Robots.txt'yi Görüntüle
                        </a>
                    </div>
                </div>

                <!-- Help -->
                <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">💡 SEO İpuçları</h4>
                    <div class="text-sm text-blue-700 space-y-2">
                        <p>• Başlık 50-60 karakter olmalı</p>
                        <p>• Meta açıklama 150-160 karakter</p>
                        <p>• OG resmi 1200x630 boyutunda</p>
                        <p>• Analytics ile performansı takip edin</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection