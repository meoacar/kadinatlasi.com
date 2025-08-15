@extends('admin.layouts.app')

@section('title', 'E-posta Ayarları')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">E-posta Ayarları</h1>
            <p class="mt-1 text-sm text-gray-600">E-posta sunucusu ve gönderim ayarlarını yapılandırın</p>
        </div>
        <a href="{{ route('admin.settings.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Geri Dön
        </a>
    </div>

    <form method="POST" action="{{ route('admin.settings.update-email') }}" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Mail Driver -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">E-posta Sürücüsü</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="mail_driver" class="block text-sm font-medium text-gray-700 mb-1">
                                Mail Sürücüsü <span class="text-red-500">*</span>
                            </label>
                            <select id="mail_driver" name="mail_driver" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mail_driver') border-red-300 @enderror">
                                <option value="smtp" {{ old('mail_driver', $settings['email_mail_driver'] ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                <option value="sendmail" {{ old('mail_driver', $settings['email_mail_driver'] ?? 'smtp') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                <option value="mailgun" {{ old('mail_driver', $settings['email_mail_driver'] ?? 'smtp') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                <option value="ses" {{ old('mail_driver', $settings['email_mail_driver'] ?? 'smtp') === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                <option value="postmark" {{ old('mail_driver', $settings['email_mail_driver'] ?? 'smtp') === 'postmark' ? 'selected' : '' }}>Postmark</option>
                            </select>
                            @error('mail_driver')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SMTP Settings -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">SMTP Ayarları</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- SMTP Host -->
                        <div>
                            <label for="mail_host" class="block text-sm font-medium text-gray-700 mb-1">
                                SMTP Sunucusu
                            </label>
                            <input type="text" id="mail_host" name="mail_host" 
                                   value="{{ old('mail_host', $settings['email_mail_host'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mail_host') border-red-300 @enderror"
                                   placeholder="smtp.gmail.com">
                            @error('mail_host')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SMTP Port -->
                        <div>
                            <label for="mail_port" class="block text-sm font-medium text-gray-700 mb-1">
                                SMTP Port
                            </label>
                            <input type="number" id="mail_port" name="mail_port" 
                                   value="{{ old('mail_port', $settings['email_mail_port'] ?? '587') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mail_port') border-red-300 @enderror"
                                   placeholder="587">
                            @error('mail_port')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SMTP Username -->
                        <div>
                            <label for="mail_username" class="block text-sm font-medium text-gray-700 mb-1">
                                SMTP Kullanıcı Adı
                            </label>
                            <input type="text" id="mail_username" name="mail_username" 
                                   value="{{ old('mail_username', $settings['email_mail_username'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mail_username') border-red-300 @enderror"
                                   placeholder="your-email@gmail.com">
                            @error('mail_username')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SMTP Password -->
                        <div>
                            <label for="mail_password" class="block text-sm font-medium text-gray-700 mb-1">
                                SMTP Şifre
                            </label>
                            <input type="password" id="mail_password" name="mail_password" 
                                   value="{{ old('mail_password', $settings['email_mail_password'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mail_password') border-red-300 @enderror"
                                   placeholder="••••••••">
                            @error('mail_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Encryption -->
                        <div class="md:col-span-2">
                            <label for="mail_encryption" class="block text-sm font-medium text-gray-700 mb-1">
                                Şifreleme
                            </label>
                            <select id="mail_encryption" name="mail_encryption"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Şifreleme Yok</option>
                                <option value="tls" {{ old('mail_encryption', $settings['email_mail_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ old('mail_encryption', $settings['email_mail_encryption'] ?? 'tls') === 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sender Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Gönderen Bilgileri</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- From Address -->
                        <div>
                            <label for="mail_from_address" class="block text-sm font-medium text-gray-700 mb-1">
                                Gönderen E-posta <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="mail_from_address" name="mail_from_address" 
                                   value="{{ old('mail_from_address', $settings['email_mail_from_address'] ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mail_from_address') border-red-300 @enderror"
                                   placeholder="noreply@kadinatlasi.com">
                            @error('mail_from_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- From Name -->
                        <div>
                            <label for="mail_from_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Gönderen Adı <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="mail_from_name" name="mail_from_name" 
                                   value="{{ old('mail_from_name', $settings['email_mail_from_name'] ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mail_from_name') border-red-300 @enderror"
                                   placeholder="KadınAtlası">
                            @error('mail_from_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Mailgun Settings -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Mailgun Ayarları</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Mailgun Domain -->
                        <div>
                            <label for="mailgun_domain" class="block text-sm font-medium text-gray-700 mb-1">
                                Mailgun Domain
                            </label>
                            <input type="text" id="mailgun_domain" name="mailgun_domain" 
                                   value="{{ old('mailgun_domain', $settings['email_mailgun_domain'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="mg.kadinatlasi.com">
                        </div>

                        <!-- Mailgun Secret -->
                        <div>
                            <label for="mailgun_secret" class="block text-sm font-medium text-gray-700 mb-1">
                                Mailgun Secret Key
                            </label>
                            <input type="password" id="mailgun_secret" name="mailgun_secret" 
                                   value="{{ old('mailgun_secret', $settings['email_mailgun_secret'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="key-••••••••••••••••••••••••••••••••">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Test Email -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Test E-postası</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="test_email" class="block text-sm font-medium text-gray-700 mb-1">
                                Test E-posta Adresi
                            </label>
                            <input type="email" id="test_email" name="test_email" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="test@example.com">
                        </div>
                        
                        <button type="button" onclick="sendTestEmail()" 
                                class="w-full px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            Test E-postası Gönder
                        </button>
                    </div>
                </div>

                <!-- Email Status -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">E-posta Durumu</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">Sürücü:</span>
                            <span class="text-sm text-gray-600">{{ strtoupper($settings['email_mail_driver'] ?? 'SMTP') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">Sunucu:</span>
                            <span class="text-sm text-gray-600">{{ $settings['email_mail_host'] ?? 'Yapılandırılmamış' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">Port:</span>
                            <span class="text-sm text-gray-600">{{ $settings['email_mail_port'] ?? 'Yapılandırılmamış' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">Şifreleme:</span>
                            <span class="text-sm text-gray-600">{{ strtoupper($settings['email_mail_encryption'] ?? 'Yok') }}</span>
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

                <!-- Help -->
                <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">💡 E-posta Ayarları</h4>
                    <div class="text-sm text-blue-700 space-y-2">
                        <p>• Gmail için App Password kullanın</p>
                        <p>• TLS şifrelemesi önerilir</p>
                        <p>• Test e-postası ile ayarları doğrulayın</p>
                        <p>• Mailgun production için idealdir</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
async function sendTestEmail() {
    const email = document.getElementById('test_email').value;
    
    if (!email) {
        alert('Lütfen test e-posta adresini girin.');
        return;
    }
    
    try {
        const response = await fetch('{{ route("admin.settings.send-test-email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                test_email: email
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Test e-postası başarıyla gönderildi!');
        } else {
            alert('Test e-postası gönderilemedi: ' + (result.message || 'Bilinmeyen hata'));
        }
    } catch (error) {
        alert('Bir hata oluştu: ' + error.message);
    }
}
</script>
@endpush