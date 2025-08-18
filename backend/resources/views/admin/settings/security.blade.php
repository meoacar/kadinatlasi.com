@extends('admin.layouts.app')

@section('title', 'Güvenlik Ayarları')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Güvenlik Ayarları</h1>
            <p class="mt-1 text-sm text-gray-600">Sistem güvenliği ve erişim kontrolü ayarları</p>
        </div>
        <a href="{{ route('admin.settings.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Geri Dön
        </a>
    </div>

    <form method="POST" action="{{ route('admin.settings.update-security') }}" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Authentication Settings -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Kimlik Doğrulama</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Two Factor Authentication -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">İki Faktörlü Kimlik Doğrulama</h4>
                                <p class="text-sm text-gray-600">Hesap güvenliği için ek doğrulama katmanı</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="two_factor_enabled" value="1" 
                                       {{ old('two_factor_enabled', $settings['security_two_factor_enabled'] ?? false) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- Session Timeout -->
                        <div>
                            <label for="session_timeout" class="block text-sm font-medium text-gray-700 mb-1">
                                Oturum Zaman Aşımı (dakika)
                            </label>
                            <select id="session_timeout" name="session_timeout"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="15" {{ old('session_timeout', $settings['security_session_timeout'] ?? '60') == '15' ? 'selected' : '' }}>15 dakika</option>
                                <option value="30" {{ old('session_timeout', $settings['security_session_timeout'] ?? '60') == '30' ? 'selected' : '' }}>30 dakika</option>
                                <option value="60" {{ old('session_timeout', $settings['security_session_timeout'] ?? '60') == '60' ? 'selected' : '' }}>1 saat</option>
                                <option value="120" {{ old('session_timeout', $settings['security_session_timeout'] ?? '60') == '120' ? 'selected' : '' }}>2 saat</option>
                                <option value="480" {{ old('session_timeout', $settings['security_session_timeout'] ?? '60') == '480' ? 'selected' : '' }}>8 saat</option>
                                <option value="1440" {{ old('session_timeout', $settings['security_session_timeout'] ?? '60') == '1440' ? 'selected' : '' }}>24 saat</option>
                            </select>
                        </div>

                        <!-- Max Login Attempts -->
                        <div>
                            <label for="max_login_attempts" class="block text-sm font-medium text-gray-700 mb-1">
                                Maksimum Giriş Denemesi
                            </label>
                            <select id="max_login_attempts" name="max_login_attempts"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="3" {{ old('max_login_attempts', $settings['security_max_login_attempts'] ?? '5') == '3' ? 'selected' : '' }}>3 deneme</option>
                                <option value="5" {{ old('max_login_attempts', $settings['security_max_login_attempts'] ?? '5') == '5' ? 'selected' : '' }}>5 deneme</option>
                                <option value="10" {{ old('max_login_attempts', $settings['security_max_login_attempts'] ?? '5') == '10' ? 'selected' : '' }}>10 deneme</option>
                            </select>
                        </div>

                        <!-- Lockout Duration -->
                        <div>
                            <label for="lockout_duration" class="block text-sm font-medium text-gray-700 mb-1">
                                Hesap Kilitleme Süresi (dakika)
                            </label>
                            <select id="lockout_duration" name="lockout_duration"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="5" {{ old('lockout_duration', $settings['security_lockout_duration'] ?? '15') == '5' ? 'selected' : '' }}>5 dakika</option>
                                <option value="15" {{ old('lockout_duration', $settings['security_lockout_duration'] ?? '15') == '15' ? 'selected' : '' }}>15 dakika</option>
                                <option value="30" {{ old('lockout_duration', $settings['security_lockout_duration'] ?? '15') == '30' ? 'selected' : '' }}>30 dakika</option>
                                <option value="60" {{ old('lockout_duration', $settings['security_lockout_duration'] ?? '15') == '60' ? 'selected' : '' }}>1 saat</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Password Policy -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Şifre Politikası</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Minimum Password Length -->
                        <div>
                            <label for="min_password_length" class="block text-sm font-medium text-gray-700 mb-1">
                                Minimum Şifre Uzunluğu
                            </label>
                            <select id="min_password_length" name="min_password_length"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="6" {{ old('min_password_length', $settings['security_min_password_length'] ?? '8') == '6' ? 'selected' : '' }}>6 karakter</option>
                                <option value="8" {{ old('min_password_length', $settings['security_min_password_length'] ?? '8') == '8' ? 'selected' : '' }}>8 karakter</option>
                                <option value="10" {{ old('min_password_length', $settings['security_min_password_length'] ?? '8') == '10' ? 'selected' : '' }}>10 karakter</option>
                                <option value="12" {{ old('min_password_length', $settings['security_min_password_length'] ?? '8') == '12' ? 'selected' : '' }}>12 karakter</option>
                            </select>
                        </div>

                        <!-- Password Requirements -->
                        <div class="space-y-3">
                            <h4 class="text-sm font-medium text-gray-900">Şifre Gereksinimleri</h4>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-700">Büyük harf zorunlu</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="require_uppercase" value="1" 
                                           {{ old('require_uppercase', $settings['security_require_uppercase'] ?? true) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-700">Küçük harf zorunlu</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="require_lowercase" value="1" 
                                           {{ old('require_lowercase', $settings['security_require_lowercase'] ?? true) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-700">Rakam zorunlu</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="require_numbers" value="1" 
                                           {{ old('require_numbers', $settings['security_require_numbers'] ?? true) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-700">Özel karakter zorunlu</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="require_symbols" value="1" 
                                           {{ old('require_symbols', $settings['security_require_symbols'] ?? false) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Password Expiry -->
                        <div>
                            <label for="password_expiry_days" class="block text-sm font-medium text-gray-700 mb-1">
                                Şifre Geçerlilik Süresi (gün)
                            </label>
                            <select id="password_expiry_days" name="password_expiry_days"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="0" {{ old('password_expiry_days', $settings['security_password_expiry_days'] ?? '0') == '0' ? 'selected' : '' }}>Süresiz</option>
                                <option value="30" {{ old('password_expiry_days', $settings['security_password_expiry_days'] ?? '0') == '30' ? 'selected' : '' }}>30 gün</option>
                                <option value="60" {{ old('password_expiry_days', $settings['security_password_expiry_days'] ?? '0') == '60' ? 'selected' : '' }}>60 gün</option>
                                <option value="90" {{ old('password_expiry_days', $settings['security_password_expiry_days'] ?? '0') == '90' ? 'selected' : '' }}>90 gün</option>
                                <option value="180" {{ old('password_expiry_days', $settings['security_password_expiry_days'] ?? '0') == '180' ? 'selected' : '' }}>180 gün</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Security Features -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Güvenlik Özellikleri</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- CAPTCHA -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">CAPTCHA Koruması</h4>
                                <p class="text-sm text-gray-600">Giriş formlarında bot koruması</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="captcha_enabled" value="1" 
                                       {{ old('captcha_enabled', $settings['security_captcha_enabled'] ?? true) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- IP Whitelist -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">IP Beyaz Liste</h4>
                                <p class="text-sm text-gray-600">Sadece belirli IP'lerden admin erişimi</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="ip_whitelist_enabled" value="1" 
                                       {{ old('ip_whitelist_enabled', $settings['security_ip_whitelist_enabled'] ?? false) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- SSL Force -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">SSL Zorunluluğu</h4>
                                <p class="text-sm text-gray-600">HTTPS bağlantısı zorunlu kıl</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="force_ssl" value="1" 
                                       {{ old('force_ssl', $settings['security_force_ssl'] ?? true) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- Security Headers -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Güvenlik Başlıkları</h4>
                                <p class="text-sm text-gray-600">HTTP güvenlik başlıklarını etkinleştir</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="security_headers" value="1" 
                                       {{ old('security_headers', $settings['security_security_headers'] ?? true) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Security Score -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Güvenlik Skoru</h3>
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-3">
                            <span class="text-2xl font-bold text-green-600">85</span>
                        </div>
                        <p class="text-sm text-gray-600">İyi seviyede güvenlik</p>
                        <div class="mt-3 space-y-2">
                            <div class="flex items-center text-xs">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-gray-600">İki faktörlü kimlik doğrulama</span>
                            </div>
                            <div class="flex items-center text-xs">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-gray-600">Güçlü şifre politikası</span>
                            </div>
                            <div class="flex items-center text-xs">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                <span class="text-gray-600">IP kısıtlaması eksik</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Security Events -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Son Güvenlik Olayları</h3>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm text-gray-900">Başarısız giriş denemesi</p>
                                <p class="text-xs text-gray-500">2 saat önce</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm text-gray-900">Başarılı admin girişi</p>
                                <p class="text-xs text-gray-500">5 saat önce</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm text-gray-900">Şifre değişikliği</p>
                                <p class="text-xs text-gray-500">1 gün önce</p>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="block mt-4 text-sm text-blue-600 hover:text-blue-700">
                        Tüm güvenlik loglarını görüntüle →
                    </a>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex flex-col space-y-3">
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Güvenlik Ayarlarını Kaydet
                        </button>
                        <a href="{{ route('admin.settings.index') }}" 
                           class="w-full px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors text-center">
                            İptal
                        </a>
                    </div>
                </div>

                <!-- Security Tips -->
                <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">🛡️ Güvenlik İpuçları</h4>
                    <div class="text-sm text-blue-700 space-y-2">
                        <p>• Düzenli olarak şifrenizi değiştirin</p>
                        <p>• İki faktörlü kimlik doğrulamayı aktif edin</p>
                        <p>• Güvenlik loglarını kontrol edin</p>
                        <p>• Güncel yazılım kullanın</p>
                        <p>• Şüpheli aktiviteleri bildirin</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Password strength indicator
function checkPasswordStrength() {
    const requirements = {
        uppercase: document.querySelector('input[name="require_uppercase"]').checked,
        lowercase: document.querySelector('input[name="require_lowercase"]').checked,
        numbers: document.querySelector('input[name="require_numbers"]').checked,
        symbols: document.querySelector('input[name="require_symbols"]').checked,
        minLength: parseInt(document.getElementById('min_password_length').value)
    };
    
    // Update security score based on requirements
    let score = 60; // Base score
    
    if (requirements.uppercase) score += 5;
    if (requirements.lowercase) score += 5;
    if (requirements.numbers) score += 10;
    if (requirements.symbols) score += 15;
    if (requirements.minLength >= 10) score += 5;
    
    // Update score display (if exists)
    const scoreElement = document.querySelector('.text-2xl.font-bold.text-green-600');
    if (scoreElement) {
        scoreElement.textContent = Math.min(score, 100);
    }
}

// Add event listeners to password policy inputs
document.addEventListener('DOMContentLoaded', function() {
    const policyInputs = document.querySelectorAll('input[name^="require_"], #min_password_length');
    policyInputs.forEach(input => {
        input.addEventListener('change', checkPasswordStrength);
    });
    
    // Initial check
    checkPasswordStrength();
});

// Toggle switch animation
document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        // Add visual feedback
        this.parentElement.classList.add('transition-all', 'duration-200');
    });
});
</script>
@endpush