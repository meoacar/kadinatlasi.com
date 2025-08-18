@extends('admin.layouts.app')

@section('title', 'API Ayarları')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">API Ayarları</h1>
            <p class="mt-1 text-sm text-gray-600">API anahtarları ve üçüncü taraf entegrasyonları</p>
        </div>
        <a href="{{ route('admin.settings.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Geri Dön
        </a>
    </div>

    <form method="POST" action="{{ route('admin.settings.update-api') }}" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Google Services -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 mr-3 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Google Servisleri</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Google Analytics -->
                        <div>
                            <label for="google_analytics_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Google Analytics ID
                            </label>
                            <input type="text" id="google_analytics_id" name="google_analytics_id" 
                                   value="{{ old('google_analytics_id', $settings['api_google_analytics_id'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('google_analytics_id') border-red-300 @enderror"
                                   placeholder="G-XXXXXXXXXX">
                            @error('google_analytics_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Google Maps API -->
                        <div>
                            <label for="google_maps_api_key" class="block text-sm font-medium text-gray-700 mb-1">
                                Google Maps API Anahtarı
                            </label>
                            <input type="password" id="google_maps_api_key" name="google_maps_api_key" 
                                   value="{{ old('google_maps_api_key', $settings['api_google_maps_api_key'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('google_maps_api_key') border-red-300 @enderror"
                                   placeholder="AIzaSy...">
                            @error('google_maps_api_key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Google reCAPTCHA -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="recaptcha_site_key" class="block text-sm font-medium text-gray-700 mb-1">
                                    reCAPTCHA Site Key
                                </label>
                                <input type="text" id="recaptcha_site_key" name="recaptcha_site_key" 
                                       value="{{ old('recaptcha_site_key', $settings['api_recaptcha_site_key'] ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="6Lc...">
                            </div>
                            <div>
                                <label for="recaptcha_secret_key" class="block text-sm font-medium text-gray-700 mb-1">
                                    reCAPTCHA Secret Key
                                </label>
                                <input type="password" id="recaptcha_secret_key" name="recaptcha_secret_key" 
                                       value="{{ old('recaptcha_secret_key', $settings['api_recaptcha_secret_key'] ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="6Lc...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Login APIs -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Sosyal Medya Girişi</h3>
                    
                    <div class="space-y-6">
                        <!-- Facebook Login -->
                        <div class="border-b border-gray-200 pb-4">
                            <div class="flex items-center mb-3">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <h4 class="text-sm font-medium text-gray-900">Facebook Login</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="facebook_app_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        App ID
                                    </label>
                                    <input type="text" id="facebook_app_id" name="facebook_app_id" 
                                           value="{{ old('facebook_app_id', $settings['api_facebook_app_id'] ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="123456789012345">
                                </div>
                                <div>
                                    <label for="facebook_app_secret" class="block text-sm font-medium text-gray-700 mb-1">
                                        App Secret
                                    </label>
                                    <input type="password" id="facebook_app_secret" name="facebook_app_secret" 
                                           value="{{ old('facebook_app_secret', $settings['api_facebook_app_secret'] ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="••••••••••••••••">
                                </div>
                            </div>
                        </div>

                        <!-- Google Login -->
                        <div class="border-b border-gray-200 pb-4">
                            <div class="flex items-center mb-3">
                                <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                <h4 class="text-sm font-medium text-gray-900">Google Login</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="google_client_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Client ID
                                    </label>
                                    <input type="text" id="google_client_id" name="google_client_id" 
                                           value="{{ old('google_client_id', $settings['api_google_client_id'] ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="123456789-abc.apps.googleusercontent.com">
                                </div>
                                <div>
                                    <label for="google_client_secret" class="block text-sm font-medium text-gray-700 mb-1">
                                        Client Secret
                                    </label>
                                    <input type="password" id="google_client_secret" name="google_client_secret" 
                                           value="{{ old('google_client_secret', $settings['api_google_client_secret'] ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="••••••••••••••••">
                                </div>
                            </div>
                        </div>

                        <!-- Twitter Login -->
                        <div>
                            <div class="flex items-center mb-3">
                                <svg class="w-5 h-5 mr-2 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                                <h4 class="text-sm font-medium text-gray-900">Twitter Login</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="twitter_api_key" class="block text-sm font-medium text-gray-700 mb-1">
                                        API Key
                                    </label>
                                    <input type="text" id="twitter_api_key" name="twitter_api_key" 
                                           value="{{ old('twitter_api_key', $settings['api_twitter_api_key'] ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="abcdefghijklmnopqrstuvwxyz">
                                </div>
                                <div>
                                    <label for="twitter_api_secret" class="block text-sm font-medium text-gray-700 mb-1">
                                        API Secret
                                    </label>
                                    <input type="password" id="twitter_api_secret" name="twitter_api_secret" 
                                           value="{{ old('twitter_api_secret', $settings['api_twitter_api_secret'] ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="••••••••••••••••">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other APIs -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Diğer API'ler</h3>
                    
                    <div class="space-y-4">
                        <!-- OpenAI API -->
                        <div>
                            <label for="openai_api_key" class="block text-sm font-medium text-gray-700 mb-1">
                                OpenAI API Anahtarı
                            </label>
                            <input type="password" id="openai_api_key" name="openai_api_key" 
                                   value="{{ old('openai_api_key', $settings['api_openai_api_key'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="sk-...">
                            <p class="mt-1 text-xs text-gray-500">AI destekli özellikler için kullanılır</p>
                        </div>

                        <!-- AWS S3 -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="aws_access_key" class="block text-sm font-medium text-gray-700 mb-1">
                                    AWS Access Key
                                </label>
                                <input type="text" id="aws_access_key" name="aws_access_key" 
                                       value="{{ old('aws_access_key', $settings['api_aws_access_key'] ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="AKIA...">
                            </div>
                            <div>
                                <label for="aws_secret_key" class="block text-sm font-medium text-gray-700 mb-1">
                                    AWS Secret Key
                                </label>
                                <input type="password" id="aws_secret_key" name="aws_secret_key" 
                                       value="{{ old('aws_secret_key', $settings['api_aws_secret_key'] ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="••••••••••••••••">
                            </div>
                            <div>
                                <label for="aws_bucket" class="block text-sm font-medium text-gray-700 mb-1">
                                    S3 Bucket
                                </label>
                                <input type="text" id="aws_bucket" name="aws_bucket" 
                                       value="{{ old('aws_bucket', $settings['api_aws_bucket'] ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="my-bucket">
                            </div>
                        </div>

                        <!-- Pusher (Real-time) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="pusher_app_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pusher App ID
                                </label>
                                <input type="text" id="pusher_app_id" name="pusher_app_id" 
                                       value="{{ old('pusher_app_id', $settings['api_pusher_app_id'] ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="123456">
                            </div>
                            <div>
                                <label for="pusher_key" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pusher Key
                                </label>
                                <input type="text" id="pusher_key" name="pusher_key" 
                                       value="{{ old('pusher_key', $settings['api_pusher_key'] ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="abcdefghijklmnop">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- API Status -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">API Durumu</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Google Analytics</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Google Maps</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Yapılandırılmamış
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Facebook Login</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Hata
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Test API -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">API Testi</h3>
                    <div class="space-y-3">
                        <button type="button" onclick="testGoogleMaps()" 
                                class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Google Maps Test
                        </button>
                        <button type="button" onclick="testRecaptcha()" 
                                class="w-full px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            reCAPTCHA Test
                        </button>
                        <button type="button" onclick="testSocialLogin()" 
                                class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                            Sosyal Giriş Test
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex flex-col space-y-3">
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            API Ayarlarını Kaydet
                        </button>
                        <a href="{{ route('admin.settings.index') }}" 
                           class="w-full px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors text-center">
                            İptal
                        </a>
                    </div>
                </div>

                <!-- Security Warning -->
                <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-6">
                    <h4 class="text-sm font-medium text-yellow-900 mb-2">🔒 Güvenlik Uyarısı</h4>
                    <div class="text-sm text-yellow-700 space-y-2">
                        <p>• API anahtarlarını güvenli tutun</p>
                        <p>• Düzenli olarak yenileyin</p>
                        <p>• Sadece gerekli izinleri verin</p>
                        <p>• Test ortamında farklı anahtarlar kullanın</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
async function testGoogleMaps() {
    const apiKey = document.getElementById('google_maps_api_key').value;
    if (!apiKey) {
        alert('Lütfen Google Maps API anahtarını girin.');
        return;
    }
    
    try {
        const response = await fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=Istanbul&key=${apiKey}`);
        const result = await response.json();
        
        if (result.status === 'OK') {
            alert('Google Maps API başarıyla test edildi!');
        } else {
            alert(`Google Maps API hatası: ${result.error_message || result.status}`);
        }
    } catch (error) {
        alert('Google Maps API test edilemedi.');
    }
}

async function testRecaptcha() {
    const siteKey = document.getElementById('recaptcha_site_key').value;
    if (!siteKey) {
        alert('Lütfen reCAPTCHA site key\'ini girin.');
        return;
    }
    
    alert('reCAPTCHA test özelliği yakında eklenecek.');
}

async function testSocialLogin() {
    alert('Sosyal giriş test özelliği yakında eklenecek.');
}

// Show/hide password fields
document.querySelectorAll('input[type="password"]').forEach(input => {
    const wrapper = document.createElement('div');
    wrapper.className = 'relative';
    input.parentNode.insertBefore(wrapper, input);
    wrapper.appendChild(input);
    
    const toggleBtn = document.createElement('button');
    toggleBtn.type = 'button';
    toggleBtn.className = 'absolute inset-y-0 right-0 pr-3 flex items-center';
    toggleBtn.innerHTML = `
        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
    `;
    
    toggleBtn.addEventListener('click', () => {
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
    });
    
    wrapper.appendChild(toggleBtn);
});
</script>
@endpush