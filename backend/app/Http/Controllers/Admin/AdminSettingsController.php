<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Models\FooterLink;
use App\Models\PaymentMethod;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminSettingsController extends AdminController
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        parent::__construct();
        $this->settingsService = $settingsService;
    }

    /**
     * Ayarlar ana sayfası
     */
    public function index()
    {
        try {
            $settings = $this->settingsService->getAllSettings();
            $stats = [
                'total_settings' => Setting::count(),
                'footer_links' => FooterLink::count(),
                'payment_methods' => PaymentMethod::where('is_active', true)->count(),
                'cache_size' => $this->getCacheSize(),
            ];

            return view('admin.settings.index', compact('settings', 'stats'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load settings');
        }
    }

    /**
     * Site ayarları
     */
    public function siteSettings()
    {
        try {
            $settings = $this->settingsService->getSettingsByGroup('site');
            return view('admin.settings.site', compact('settings'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load site settings');
        }
    }

    /**
     * Site ayarlarını güncelle
     */
    public function updateSiteSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_keywords' => 'nullable|string|max:500',
            'site_url' => 'required|url',
            'admin_email' => 'required|email',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string|max:500',
            'timezone' => 'required|string|max:50',
            'language' => 'required|string|max:10',
            'maintenance_mode' => 'boolean',
            'maintenance_message' => 'nullable|string|max:1000',
            'google_analytics_id' => 'nullable|string|max:50',
            'facebook_pixel_id' => 'nullable|string|max:50',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png|max:512',
        ], [
            'site_name.required' => 'Site adı gereklidir.',
            'site_url.required' => 'Site URL\'si gereklidir.',
            'site_url.url' => 'Geçerli bir URL girin.',
            'admin_email.required' => 'Admin e-posta adresi gereklidir.',
            'admin_email.email' => 'Geçerli bir e-posta adresi girin.',
            'contact_email.email' => 'Geçerli bir e-posta adresi girin.',
            'timezone.required' => 'Saat dilimi gereklidir.',
            'language.required' => 'Dil seçimi gereklidir.',
            'site_logo.image' => 'Logo bir resim dosyası olmalıdır.',
            'site_logo.max' => 'Logo boyutu en fazla 2MB olabilir.',
            'site_favicon.image' => 'Favicon bir resim dosyası olmalıdır.',
            'site_favicon.max' => 'Favicon boyutu en fazla 512KB olabilir.',
        ]);

        try {
            $settingsData = $request->except(['site_logo', 'site_favicon']);

            // Logo yükleme
            if ($request->hasFile('site_logo')) {
                $logoPath = $request->file('site_logo')->store('settings', 'public');
                $settingsData['site_logo'] = $logoPath;
                
                // Eski logoyu sil
                $oldLogo = $this->settingsService->getSetting('site_logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
            }

            // Favicon yükleme
            if ($request->hasFile('site_favicon')) {
                $faviconPath = $request->file('site_favicon')->store('settings', 'public');
                $settingsData['site_favicon'] = $faviconPath;
                
                // Eski favicon'u sil
                $oldFavicon = $this->settingsService->getSetting('site_favicon');
                if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                    Storage::disk('public')->delete($oldFavicon);
                }
            }

            $this->settingsService->updateSettings($settingsData, 'site');

            return $this->successResponse('Site ayarları başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update site settings');
        }
    }

    /**
     * Ödeme ayarları
     */
    public function paymentSettings()
    {
        try {
            $settings = $this->settingsService->getSettingsByGroup('payment');
            $paymentMethods = PaymentMethod::orderBy('sort_order')->get();
            
            return view('admin.settings.payment', compact('settings', 'paymentMethods'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load payment settings');
        }
    }

    /**
     * Ödeme ayarlarını güncelle
     */
    public function updatePaymentSettings(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|max:3',
            'currency_symbol' => 'required|string|max:5',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'shipping_cost' => 'nullable|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|string|max:1000',
            'refund_policy' => 'nullable|string|max:1000',
        ], [
            'currency.required' => 'Para birimi gereklidir.',
            'currency_symbol.required' => 'Para birimi sembolü gereklidir.',
            'tax_rate.numeric' => 'Vergi oranı sayısal olmalıdır.',
            'tax_rate.max' => 'Vergi oranı en fazla 100 olabilir.',
            'shipping_cost.numeric' => 'Kargo ücreti sayısal olmalıdır.',
            'free_shipping_threshold.numeric' => 'Ücretsiz kargo limiti sayısal olmalıdır.',
        ]);

        try {
            $settingsData = $request->all();
            $this->settingsService->updateSettings($settingsData, 'payment');

            return $this->successResponse('Ödeme ayarları başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update payment settings');
        }
    }

    /**
     * Ödeme yöntemi oluştur
     */
    public function storePaymentMethod(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:payment_methods,slug',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'config' => 'nullable|array',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'Ödeme yöntemi adı gereklidir.',
            'slug.unique' => 'Bu slug zaten kullanılıyor.',
        ]);

        try {
            $paymentData = $request->only([
                'name', 'slug', 'description', 'icon', 'config', 'is_active', 'sort_order'
            ]);

            if (empty($paymentData['slug'])) {
                $paymentData['slug'] = \Str::slug($paymentData['name']);
            }

            PaymentMethod::create($paymentData);

            return $this->successResponse('Ödeme yöntemi başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'create payment method');
        }
    }

    /**
     * Ödeme yöntemi güncelle
     */
    public function updatePaymentMethod(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('payment_methods')->ignore($paymentMethod->id)],
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'config' => 'nullable|array',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $paymentData = $request->only([
                'name', 'slug', 'description', 'icon', 'config', 'is_active', 'sort_order'
            ]);

            if (empty($paymentData['slug'])) {
                $paymentData['slug'] = \Str::slug($paymentData['name']);
            }

            $paymentMethod->update($paymentData);

            return $this->successResponse('Ödeme yöntemi başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update payment method');
        }
    }

    /**
     * Ödeme yöntemi sil
     */
    public function destroyPaymentMethod(PaymentMethod $paymentMethod)
    {
        try {
            $paymentMethod->delete();
            return $this->successResponse('Ödeme yöntemi başarıyla silindi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'delete payment method');
        }
    }

    /**
     * Footer link yönetimi
     */
    public function footerLinks()
    {
        try {
            $footerLinks = FooterLink::orderBy('group')->orderBy('sort_order')->get();
            $groups = FooterLink::select('group')->distinct()->pluck('group');
            
            return view('admin.settings.footer-links', compact('footerLinks', 'groups'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load footer links');
        }
    }

    /**
     * Footer link oluştur
     */
    public function storeFooterLink(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'group' => 'required|string|max:100',
            'target' => 'required|in:_self,_blank',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'title.required' => 'Link başlığı gereklidir.',
            'url.required' => 'Link URL\'si gereklidir.',
            'group.required' => 'Link grubu gereklidir.',
            'target.required' => 'Link hedefi gereklidir.',
        ]);

        try {
            $linkData = $request->only([
                'title', 'url', 'group', 'target', 'is_active', 'sort_order'
            ]);

            FooterLink::create($linkData);

            return $this->successResponse('Footer linki başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'create footer link');
        }
    }

    /**
     * Footer link güncelle
     */
    public function updateFooterLink(Request $request, FooterLink $footerLink)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'group' => 'required|string|max:100',
            'target' => 'required|in:_self,_blank',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $linkData = $request->only([
                'title', 'url', 'group', 'target', 'is_active', 'sort_order'
            ]);

            $footerLink->update($linkData);

            return $this->successResponse('Footer linki başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update footer link');
        }
    }

    /**
     * Footer link sil
     */
    public function destroyFooterLink(FooterLink $footerLink)
    {
        try {
            $footerLink->delete();
            return $this->successResponse('Footer linki başarıyla silindi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'delete footer link');
        }
    }

    /**
     * E-posta ayarları
     */
    public function emailSettings()
    {
        try {
            $settings = $this->settingsService->getSettingsByGroup('email');
            return view('admin.settings.email', compact('settings'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load email settings');
        }
    }

    /**
     * E-posta ayarlarını güncelle
     */
    public function updateEmailSettings(Request $request)
    {
        $request->validate([
            'mail_driver' => 'required|in:smtp,sendmail,mailgun,ses,postmark',
            'mail_host' => 'required_if:mail_driver,smtp|nullable|string|max:255',
            'mail_port' => 'required_if:mail_driver,smtp|nullable|integer|min:1|max:65535',
            'mail_username' => 'required_if:mail_driver,smtp|nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:tls,ssl',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string|max:255',
            'mailgun_domain' => 'required_if:mail_driver,mailgun|nullable|string|max:255',
            'mailgun_secret' => 'required_if:mail_driver,mailgun|nullable|string|max:255',
        ], [
            'mail_driver.required' => 'E-posta sürücüsü gereklidir.',
            'mail_host.required_if' => 'SMTP kullanırken host gereklidir.',
            'mail_port.required_if' => 'SMTP kullanırken port gereklidir.',
            'mail_username.required_if' => 'SMTP kullanırken kullanıcı adı gereklidir.',
            'mail_from_address.required' => 'Gönderen e-posta adresi gereklidir.',
            'mail_from_address.email' => 'Geçerli bir e-posta adresi girin.',
            'mail_from_name.required' => 'Gönderen adı gereklidir.',
        ]);

        try {
            $settingsData = $request->all();
            $this->settingsService->updateSettings($settingsData, 'email');

            return $this->successResponse('E-posta ayarları başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update email settings');
        }
    }

    /**
     * Test e-postası gönder
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ], [
            'test_email.required' => 'Test e-posta adresi gereklidir.',
            'test_email.email' => 'Geçerli bir e-posta adresi girin.',
        ]);

        try {
            $this->settingsService->sendTestEmail($request->test_email);
            return $this->successResponse('Test e-postası başarıyla gönderildi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'send test email');
        }
    }

    /**
     * Sosyal medya ayarları
     */
    public function socialSettings()
    {
        try {
            $settings = $this->settingsService->getSettingsByGroup('social');
            return view('admin.settings.social', compact('settings'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load social settings');
        }
    }

    /**
     * Sosyal medya ayarlarını güncelle
     */
    public function updateSocialSettings(Request $request)
    {
        $request->validate([
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string|max:20',
            'telegram_url' => 'nullable|url',
            'discord_url' => 'nullable|url',
            'github_url' => 'nullable|url',
        ], [
            'facebook_url.url' => 'Geçerli bir Facebook URL\'si girin.',
            'twitter_url.url' => 'Geçerli bir Twitter URL\'si girin.',
            'instagram_url.url' => 'Geçerli bir Instagram URL\'si girin.',
            'linkedin_url.url' => 'Geçerli bir LinkedIn URL\'si girin.',
            'youtube_url.url' => 'Geçerli bir YouTube URL\'si girin.',
            'tiktok_url.url' => 'Geçerli bir TikTok URL\'si girin.',
            'telegram_url.url' => 'Geçerli bir Telegram URL\'si girin.',
            'discord_url.url' => 'Geçerli bir Discord URL\'si girin.',
            'github_url.url' => 'Geçerli bir GitHub URL\'si girin.',
        ]);

        try {
            $settingsData = $request->all();
            $this->settingsService->updateSettings($settingsData, 'social');

            return $this->successResponse('Sosyal medya ayarları başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update social settings');
        }
    }

    /**
     * SEO ayarları
     */
    public function seoSettings()
    {
        try {
            $settings = $this->settingsService->getSettingsByGroup('seo');
            return view('admin.settings.seo', compact('settings'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load SEO settings');
        }
    }

    /**
     * SEO ayarlarını güncelle
     */
    public function updateSeoSettings(Request $request)
    {
        $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'robots_txt' => 'nullable|string|max:2000',
            'sitemap_enabled' => 'boolean',
            'google_site_verification' => 'nullable|string|max:100',
            'bing_site_verification' => 'nullable|string|max:100',
            'yandex_site_verification' => 'nullable|string|max:100',
            'structured_data_enabled' => 'boolean',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'og_image.image' => 'OG resmi bir resim dosyası olmalıdır.',
            'og_image.max' => 'OG resmi boyutu en fazla 2MB olabilir.',
        ]);

        try {
            $settingsData = $request->except(['og_image']);

            // OG image yükleme
            if ($request->hasFile('og_image')) {
                $ogImagePath = $request->file('og_image')->store('settings', 'public');
                $settingsData['og_image'] = $ogImagePath;
                
                // Eski resmi sil
                $oldImage = $this->settingsService->getSetting('og_image');
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $this->settingsService->updateSettings($settingsData, 'seo');

            return $this->successResponse('SEO ayarları başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update SEO settings');
        }
    }

    /**
     * Cache yönetimi
     */
    public function cacheManagement()
    {
        try {
            $cacheInfo = [
                'size' => $this->getCacheSize(),
                'keys_count' => $this->getCacheKeysCount(),
                'driver' => config('cache.default'),
                'last_cleared' => $this->settingsService->getSetting('cache_last_cleared'),
            ];

            return view('admin.settings.cache', compact('cacheInfo'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load cache management');
        }
    }

    /**
     * Cache temizle
     */
    public function clearCache(Request $request)
    {
        $request->validate([
            'cache_type' => 'required|in:all,config,route,view,application',
        ]);

        try {
            $cacheType = $request->cache_type;
            $cleared = false;

            switch ($cacheType) {
                case 'all':
                    \Artisan::call('cache:clear');
                    \Artisan::call('config:clear');
                    \Artisan::call('route:clear');
                    \Artisan::call('view:clear');
                    $cleared = true;
                    break;
                case 'config':
                    \Artisan::call('config:clear');
                    $cleared = true;
                    break;
                case 'route':
                    \Artisan::call('route:clear');
                    $cleared = true;
                    break;
                case 'view':
                    \Artisan::call('view:clear');
                    $cleared = true;
                    break;
                case 'application':
                    Cache::flush();
                    $cleared = true;
                    break;
            }

            if ($cleared) {
                $this->settingsService->updateSetting('cache_last_cleared', now()->toDateTimeString());
                return $this->successResponse('Cache başarıyla temizlendi.');
            }

            return $this->errorResponse('Cache temizlenirken bir hata oluştu.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'clear cache');
        }
    }

    /**
     * Sistem bilgileri
     */
    public function systemInfo()
    {
        try {
            $systemInfo = [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'database_version' => $this->getDatabaseVersion(),
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'disk_space' => $this->getDiskSpace(),
                'extensions' => $this->getRequiredExtensions(),
            ];

            return view('admin.settings.system-info', compact('systemInfo'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load system info');
        }
    }

    /**
     * Ayarları dışa aktar
     */
    public function exportSettings()
    {
        try {
            $settings = $this->settingsService->exportSettings();
            $filename = 'settings_backup_' . now()->format('Y_m_d_H_i_s') . '.json';

            return response()->json($settings)
                ->header('Content-Type', 'application/json')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
        } catch (\Exception $e) {
            return $this->handleException($e, 'export settings');
        }
    }

    /**
     * Ayarları içe aktar
     */
    public function importSettings(Request $request)
    {
        $request->validate([
            'settings_file' => 'required|file|mimes:json|max:1024',
        ], [
            'settings_file.required' => 'Ayar dosyası gereklidir.',
            'settings_file.mimes' => 'Sadece JSON dosyaları kabul edilir.',
            'settings_file.max' => 'Dosya boyutu en fazla 1MB olabilir.',
        ]);

        try {
            $file = $request->file('settings_file');
            $content = file_get_contents($file->getRealPath());
            $settings = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->errorResponse('Geçersiz JSON dosyası.');
            }

            $imported = $this->settingsService->importSettings($settings);

            return $this->successResponse("{$imported} ayar başarıyla içe aktarıldı.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'import settings');
        }
    }

    /**
     * API ayarları
     */
    public function apiSettings()
    {
        try {
            $settings = $this->settingsService->getSettingsByGroup('api');
            return view('admin.settings.api', compact('settings'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load API settings');
        }
    }

    /**
     * API ayarlarını güncelle
     */
    public function updateApiSettings(Request $request)
    {
        $request->validate([
            'google_analytics_id' => 'nullable|string|max:50',
            'google_maps_api_key' => 'nullable|string|max:255',
            'recaptcha_site_key' => 'nullable|string|max:255',
            'recaptcha_secret_key' => 'nullable|string|max:255',
            'facebook_app_id' => 'nullable|string|max:255',
            'facebook_app_secret' => 'nullable|string|max:255',
            'google_client_id' => 'nullable|string|max:255',
            'google_client_secret' => 'nullable|string|max:255',
            'twitter_api_key' => 'nullable|string|max:255',
            'twitter_api_secret' => 'nullable|string|max:255',
            'openai_api_key' => 'nullable|string|max:255',
            'aws_access_key' => 'nullable|string|max:255',
            'aws_secret_key' => 'nullable|string|max:255',
            'aws_bucket' => 'nullable|string|max:255',
            'pusher_app_id' => 'nullable|string|max:255',
            'pusher_key' => 'nullable|string|max:255',
        ]);

        try {
            $settingsData = $request->all();
            $this->settingsService->updateSettings($settingsData, 'api');

            return $this->successResponse('API ayarları başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update API settings');
        }
    }

    /**
     * Güvenlik ayarları
     */
    public function securitySettings()
    {
        try {
            $settings = $this->settingsService->getSettingsByGroup('security');
            return view('admin.settings.security', compact('settings'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load security settings');
        }
    }

    /**
     * Güvenlik ayarlarını güncelle
     */
    public function updateSecuritySettings(Request $request)
    {
        $request->validate([
            'two_factor_enabled' => 'boolean',
            'session_timeout' => 'required|integer|min:5|max:1440',
            'max_login_attempts' => 'required|integer|min:1|max:20',
            'lockout_duration' => 'required|integer|min:1|max:1440',
            'min_password_length' => 'required|integer|min:6|max:20',
            'require_uppercase' => 'boolean',
            'require_lowercase' => 'boolean',
            'require_numbers' => 'boolean',
            'require_symbols' => 'boolean',
            'password_expiry_days' => 'required|integer|min:0|max:365',
            'captcha_enabled' => 'boolean',
            'ip_whitelist_enabled' => 'boolean',
            'force_ssl' => 'boolean',
            'security_headers' => 'boolean',
        ], [
            'session_timeout.required' => 'Oturum zaman aşımı gereklidir.',
            'session_timeout.min' => 'Oturum zaman aşımı en az 5 dakika olmalıdır.',
            'session_timeout.max' => 'Oturum zaman aşımı en fazla 1440 dakika olabilir.',
            'max_login_attempts.required' => 'Maksimum giriş denemesi gereklidir.',
            'max_login_attempts.min' => 'En az 1 giriş denemesi olmalıdır.',
            'max_login_attempts.max' => 'En fazla 20 giriş denemesi olabilir.',
            'lockout_duration.required' => 'Kilitleme süresi gereklidir.',
            'min_password_length.required' => 'Minimum şifre uzunluğu gereklidir.',
            'min_password_length.min' => 'Minimum şifre uzunluğu en az 6 karakter olmalıdır.',
            'password_expiry_days.max' => 'Şifre geçerlilik süresi en fazla 365 gün olabilir.',
        ]);

        try {
            $settingsData = $request->all();
            $this->settingsService->updateSettings($settingsData, 'security');

            return $this->successResponse('Güvenlik ayarları başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update security settings');
        }
    }

    /**
     * Cache boyutunu al
     */
    private function getCacheSize()
    {
        try {
            $cacheDir = storage_path('framework/cache');
            if (!is_dir($cacheDir)) {
                return '0 B';
            }

            $size = 0;
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($cacheDir)
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }

            return $this->formatBytes($size);
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Cache key sayısını al
     */
    private function getCacheKeysCount()
    {
        try {
            // Redis için
            if (config('cache.default') === 'redis') {
                $redis = \Redis::connection();
                return $redis->dbSize();
            }

            // Diğer cache driver'ları için yaklaşık hesaplama
            return 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Veritabanı versiyonunu al
     */
    private function getDatabaseVersion()
    {
        try {
            return \DB::select('SELECT VERSION() as version')[0]->version;
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Disk alanı bilgisini al
     */
    private function getDiskSpace()
    {
        try {
            $bytes = disk_free_space('/');
            $total = disk_total_space('/');
            
            return [
                'free' => $this->formatBytes($bytes),
                'total' => $this->formatBytes($total),
                'used_percentage' => round((($total - $bytes) / $total) * 100, 2),
            ];
        } catch (\Exception $e) {
            return [
                'free' => 'Unknown',
                'total' => 'Unknown',
                'used_percentage' => 0,
            ];
        }
    }

    /**
     * Gerekli PHP uzantılarını kontrol et
     */
    private function getRequiredExtensions()
    {
        $required = [
            'openssl', 'pdo', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json',
            'bcmath', 'curl', 'fileinfo', 'gd', 'zip'
        ];

        $extensions = [];
        foreach ($required as $ext) {
            $extensions[$ext] = extension_loaded($ext);
        }

        return $extensions;
    }

    /**
     * Byte'ları okunabilir formata çevir
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}    /**

     * SEO ayarları sayfası
     */
    public function seo()
    {
        try {
            $settings = $this->settingsService->getAllSettings();
            return view('admin.settings.seo', compact('settings'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load SEO settings');
        }
    }

    /**
     * SEO ayarlarını güncelle
     */
    public function updateSeo(Request $request)
    {
        $request->validate([
            'seo_site_title' => 'required|string|max:255',
            'seo_meta_description' => 'required|string|max:500',
            'seo_meta_keywords' => 'nullable|string|max:255',
            'seo_canonical_url' => 'nullable|url',
            'seo_og_title' => 'nullable|string|max:255',
            'seo_og_description' => 'nullable|string|max:500',
            'seo_og_image' => 'nullable|url',
            'seo_og_type' => 'nullable|in:website,article,blog',
            'seo_twitter_card' => 'nullable|in:summary,summary_large_image',
            'seo_twitter_site' => 'nullable|string|max:50',
            'seo_google_analytics' => 'nullable|string|max:50',
            'seo_google_tag_manager' => 'nullable|string|max:50',
            'seo_facebook_pixel' => 'nullable|string|max:50',
            'seo_robots_txt' => 'nullable|string',
        ]);

        try {
            $settingsData = $request->all();
            foreach ($settingsData as $key => $value) {
                $this->settingsService->updateSetting($key, $value);
            }

            return $this->successResponse('SEO ayarları başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update SEO settings');
        }
    }

    /**
     * Footer links sayfası
     */
    public function footerLinksPage()
    {
        try {
            $footerLinks = FooterLink::orderBy('category')->orderBy('sort_order')->get();
            return view('admin.settings.footer-links', compact('footerLinks'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load footer links');
        }
    }

    /**
     * Footer link oluştur
     */
    public function storeFooterLinkNew(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'category' => 'required|in:company,support,legal',
            'target' => 'nullable|in:_self,_blank',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            FooterLink::create([
                'title' => $request->title,
                'url' => $request->url,
                'category' => $request->category,
                'target' => $request->target ?? '_self',
                'is_active' => $request->boolean('is_active', true),
                'sort_order' => $request->sort_order ?? 0,
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Footer link düzenle
     */
    public function editFooterLink(FooterLink $footerLink)
    {
        return response()->json($footerLink);
    }

    /**
     * Footer link güncelle
     */
    public function updateFooterLinkNew(Request $request, FooterLink $footerLink)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'category' => 'required|in:company,support,legal',
            'target' => 'nullable|in:_self,_blank',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $footerLink->update([
                'title' => $request->title,
                'url' => $request->url,
                'category' => $request->category,
                'target' => $request->target ?? '_self',
                'is_active' => $request->boolean('is_active', true),
                'sort_order' => $request->sort_order ?? 0,
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Footer link sil
     */
    public function destroyFooterLinkNew(FooterLink $footerLink)
    {
        try {
            $footerLink->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Sistem bilgileri sayfası
     */
    public function systemInfoPage()
    {
        try {
            $settings = $this->settingsService->getAllSettings();
            return view('admin.settings.system-info', compact('settings'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load system info');
        }
    }

    /**
     * Test e-postası gönder (JSON response)
     */
    public function sendTestEmailJson(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email'
        ]);

        try {
            \Mail::raw('Bu bir test e-postasıdır. E-posta ayarlarınız doğru şekilde yapılandırılmış.', function ($message) use ($request) {
                $message->to($request->test_email)
                        ->subject('Test E-postası - ' . config('app.name'));
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Veritabanı bağlantısını kontrol et
     */
    public function checkDatabase()
    {
        try {
            \DB::connection()->getPdo();
            return response()->json(['connected' => true]);
        } catch (\Exception $e) {
            return response()->json(['connected' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Disk kullanımını al
     */
    public function diskUsage()
    {
        try {
            $bytes = disk_total_space('/');
            $free = disk_free_space('/');
            $used = $bytes - $free;

            return response()->json([
                'used' => $this->formatBytes($used),
                'free' => $this->formatBytes($free),
                'total' => $this->formatBytes($bytes)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'used' => 'Hesaplanamadı',
                'free' => 'Hesaplanamadı',
                'total' => 'Hesaplanamadı'
            ]);
        }
    }

    /**
     * Cache temizle (JSON response)
     */
    public function clearCacheJson()
    {
        try {
            \Artisan::call('cache:clear');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Config cache temizle
     */
    public function clearConfigJson()
    {
        try {
            \Artisan::call('config:clear');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Route cache temizle
     */
    public function clearRouteJson()
    {
        try {
            \Artisan::call('route:clear');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * View cache temizle
     */
    public function clearViewJson()
    {
        try {
            \Artisan::call('view:clear');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}