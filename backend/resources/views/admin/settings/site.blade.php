@extends('admin.layouts.app')

@section('title', 'Site Ayarları')

@section('content')
<div class="space-y-6" x-data="siteSettings()">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Site Ayarları</h1>
            <p class="mt-1 text-sm text-gray-600">Site temel bilgilerini ve görünümünü yapılandırın</p>
        </div>
        <a href="{{ route('admin.settings.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Geri Dön
        </a>
    </div>

    <form method="POST" action="{{ route('admin.settings.update-site') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Temel Bilgiler</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Site Name -->
                        <div class="md:col-span-2">
                            <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Site Adı <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="site_name" name="site_name" 
                                   value="{{ old('site_name', $settings['site_name'] ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('site_name') border-red-300 @enderror"
                                   placeholder="KadınAtlası">
                            @error('site_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Site URL -->
                        <div>
                            <label for="site_url" class="block text-sm font-medium text-gray-700 mb-1">
                                Site URL <span class="text-red-500">*</span>
                            </label>
                            <input type="url" id="site_url" name="site_url" 
                                   value="{{ old('site_url', $settings['site_url'] ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('site_url') border-red-300 @enderror"
                                   placeholder="https://kadinatlasi.com">
                            @error('site_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Language -->
                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700 mb-1">
                                Dil <span class="text-red-500">*</span>
                            </label>
                            <select id="language" name="language" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('language') border-red-300 @enderror">
                                <option value="tr" {{ old('language', $settings['site_language'] ?? 'tr') === 'tr' ? 'selected' : '' }}>Türkçe</option>
                                <option value="en" {{ old('language', $settings['site_language'] ?? 'tr') === 'en' ? 'selected' : '' }}>English</option>
                            </select>
                            @error('language')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Site Description -->
                    <div class="mt-4">
                        <label for="site_description" class="block text-sm font-medium text-gray-700 mb-1">
                            Site Açıklaması
                        </label>
                        <textarea id="site_description" name="site_description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('site_description') border-red-300 @enderror"
                                  placeholder="Kadınlar için kapsamlı bir platform...">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                        @error('site_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Site Keywords -->
                    <div class="mt-4">
                        <label for="site_keywords" class="block text-sm font-medium text-gray-700 mb-1">
                            Site Anahtar Kelimeleri
                        </label>
                        <input type="text" id="site_keywords" name="site_keywords" 
                               value="{{ old('site_keywords', $settings['site_keywords'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('site_keywords') border-red-300 @enderror"
                               placeholder="kadın, forum, blog, ürün">
                        <p class="mt-1 text-xs text-gray-500">Anahtar kelimeleri virgülle ayırın</p>
                        @error('site_keywords')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">İletişim Bilgileri</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Admin Email -->
                        <div>
                            <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-1">
                                Admin E-posta <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="admin_email" name="admin_email" 
                                   value="{{ old('admin_email', $settings['site_admin_email'] ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('admin_email') border-red-300 @enderror"
                                   placeholder="admin@kadinatlasi.com">
                            @error('admin_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Email -->
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">
                                İletişim E-posta
                            </label>
                            <input type="email" id="contact_email" name="contact_email" 
                                   value="{{ old('contact_email', $settings['site_contact_email'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('contact_email') border-red-300 @enderror"
                                   placeholder="iletisim@kadinatlasi.com">
                            @error('contact_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Phone -->
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                İletişim Telefonu
                            </label>
                            <input type="tel" id="contact_phone" name="contact_phone" 
                                   value="{{ old('contact_phone', $settings['site_contact_phone'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('contact_phone') border-red-300 @enderror"
                                   placeholder="+90 555 123 45 67">
                            @error('contact_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Timezone -->
                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">
                                Saat Dilimi <span class="text-red-500">*</span>
                            </label>
                            <select id="timezone" name="timezone" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('timezone') border-red-300 @enderror">
                                <option value="Europe/Istanbul" {{ old('timezone', $settings['site_timezone'] ?? 'Europe/Istanbul') === 'Europe/Istanbul' ? 'selected' : '' }}>Europe/Istanbul</option>
                                <option value="UTC" {{ old('timezone', $settings['site_timezone'] ?? 'Europe/Istanbul') === 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="Europe/London" {{ old('timezone', $settings['site_timezone'] ?? 'Europe/Istanbul') === 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                <option value="America/New_York" {{ old('timezone', $settings['site_timezone'] ?? 'Europe/Istanbul') === 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                            </select>
                            @error('timezone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Address -->
                    <div class="mt-4">
                        <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-1">
                            İletişim Adresi
                        </label>
                        <textarea id="contact_address" name="contact_address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('contact_address') border-red-300 @enderror"
                                  placeholder="Tam adres bilgisi...">{{ old('contact_address', $settings['site_contact_address'] ?? '') }}</textarea>
                        @error('contact_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Analytics & Tracking -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Analitik ve İzleme</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Google Analytics -->
                        <div>
                            <label for="google_analytics_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Google Analytics ID
                            </label>
                            <input type="text" id="google_analytics_id" name="google_analytics_id" 
                                   value="{{ old('google_analytics_id', $settings['site_google_analytics_id'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('google_analytics_id') border-red-300 @enderror"
                                   placeholder="G-XXXXXXXXXX">
                            @error('google_analytics_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Facebook Pixel -->
                        <div>
                            <label for="facebook_pixel_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Facebook Pixel ID
                            </label>
                            <input type="text" id="facebook_pixel_id" name="facebook_pixel_id" 
                                   value="{{ old('facebook_pixel_id', $settings['site_facebook_pixel_id'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('facebook_pixel_id') border-red-300 @enderror"
                                   placeholder="123456789012345">
                            @error('facebook_pixel_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Site Logo -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Site Logosu</h3>
                    
                    <div class="space-y-4">
                        @if(isset($settings['site_logo']) && $settings['site_logo'])
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $settings['site_logo']) }}" 
                                     alt="Mevcut Logo" 
                                     class="max-w-full h-20 mx-auto object-contain">
                                <p class="mt-2 text-sm text-gray-500">Mevcut Logo</p>
                            </div>
                        @endif

                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors"
                             x-data="logoUpload()" @drop.prevent="handleDrop" @dragover.prevent @dragenter.prevent>
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4">
                                <label for="site_logo" class="cursor-pointer">
                                    <span class="mt-2 block text-sm font-medium text-gray-900">
                                        Logo yüklemek için tıklayın
                                    </span>
                                    <input id="site_logo" name="site_logo" type="file" accept="image/*" class="sr-only" @change="handleFileSelect">
                                </label>
                                <p class="mt-2 text-xs text-gray-500">
                                    PNG, JPG, GIF, SVG. Maksimum 2MB.
                                </p>
                            </div>
                        </div>

                        <!-- Logo Preview -->
                        <div x-show="logoPreview" class="text-center">
                            <img :src="logoPreview" alt="Logo Önizleme" class="max-w-full h-20 mx-auto object-contain">
                            <p class="mt-2 text-sm text-gray-500">Yeni Logo Önizlemesi</p>
                        </div>
                    </div>
                </div>

                <!-- Site Favicon -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Site Favicon</h3>
                    
                    <div class="space-y-4">
                        @if(isset($settings['site_favicon']) && $settings['site_favicon'])
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $settings['site_favicon']) }}" 
                                     alt="Mevcut Favicon" 
                                     class="w-8 h-8 mx-auto">
                                <p class="mt-2 text-sm text-gray-500">Mevcut Favicon</p>
                            </div>
                        @endif

                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors"
                             x-data="faviconUpload()" @drop.prevent="handleDrop" @dragover.prevent @dragenter.prevent>
                            <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-2">
                                <label for="site_favicon" class="cursor-pointer">
                                    <span class="block text-sm font-medium text-gray-900">
                                        Favicon yükle
                                    </span>
                                    <input id="site_favicon" name="site_favicon" type="file" accept=".ico,.png" class="sr-only" @change="handleFileSelect">
                                </label>
                                <p class="mt-1 text-xs text-gray-500">
                                    ICO, PNG. Maksimum 512KB.
                                </p>
                            </div>
                        </div>

                        <!-- Favicon Preview -->
                        <div x-show="faviconPreview" class="text-center">
                            <img :src="faviconPreview" alt="Favicon Önizleme" class="w-8 h-8 mx-auto">
                            <p class="mt-2 text-sm text-gray-500">Yeni Favicon Önizlemesi</p>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Mode -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Bakım Modu</h3>
                    
                    <div class="space-y-4">
                        <!-- Maintenance Toggle -->
                        <div class="flex items-center">
                            <input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" 
                                   {{ old('maintenance_mode', $settings['site_maintenance_mode'] ?? false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="maintenance_mode" class="ml-2 text-sm text-gray-700">
                                Bakım modunu etkinleştir
                            </label>
                        </div>

                        <!-- Maintenance Message -->
                        <div>
                            <label for="maintenance_message" class="block text-sm font-medium text-gray-700 mb-1">
                                Bakım Mesajı
                            </label>
                            <textarea id="maintenance_message" name="maintenance_message" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Site bakımda. Lütfen daha sonra tekrar deneyin.">{{ old('maintenance_message', $settings['site_maintenance_message'] ?? '') }}</textarea>
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
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function siteSettings() {
    return {
        // Component data here
    }
}

function logoUpload() {
    return {
        logoPreview: null,
        
        handleFileSelect(event) {
            this.processFile(event.target.files[0]);
        },
        
        handleDrop(event) {
            this.processFile(event.dataTransfer.files[0]);
        },
        
        processFile(file) {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.logoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }
}

function faviconUpload() {
    return {
        faviconPreview: null,
        
        handleFileSelect(event) {
            this.processFile(event.target.files[0]);
        },
        
        handleDrop(event) {
            this.processFile(event.dataTransfer.files[0]);
        },
        
        processFile(file) {
            if (file && (file.type.startsWith('image/') || file.type === 'image/x-icon')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.faviconPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }
}
</script>
@endpush