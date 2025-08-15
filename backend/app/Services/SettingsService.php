<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SettingsService
{
    const CACHE_KEY = 'app_settings';
    const CACHE_TTL = 3600; // 1 hour

    /**
     * Tüm ayarları al
     */
    public function getAllSettings()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Belirli bir grubun ayarlarını al
     */
    public function getSettingsByGroup($group)
    {
        $allSettings = $this->getAllSettings();
        $groupSettings = [];

        foreach ($allSettings as $key => $value) {
            if (strpos($key, $group . '_') === 0) {
                $groupSettings[$key] = $value;
            }
        }

        return $groupSettings;
    }

    /**
     * Tek bir ayarı al
     */
    public function getSetting($key, $default = null)
    {
        $settings = $this->getAllSettings();
        return $settings[$key] ?? $default;
    }

    /**
     * Ayarları güncelle
     */
    public function updateSettings(array $settings, $group = null)
    {
        foreach ($settings as $key => $value) {
            // Grup prefix'i ekle
            if ($group && strpos($key, $group . '_') !== 0) {
                $key = $group . '_' . $key;
            }

            $this->updateSetting($key, $value);
        }

        $this->clearCache();
    }

    /**
     * Tek bir ayarı güncelle
     */
    public function updateSetting($key, $value)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $this->getValueType($value),
            ]
        );
    }

    /**
     * Ayar sil
     */
    public function deleteSetting($key)
    {
        Setting::where('key', $key)->delete();
        $this->clearCache();
    }

    /**
     * Cache'i temizle
     */
    public function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Test e-postası gönder
     */
    public function sendTestEmail($email)
    {
        try {
            Mail::raw('Bu bir test e-postasıdır. E-posta ayarlarınız doğru şekilde yapılandırılmış.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test E-postası - ' . config('app.name'));
            });

            Log::info('Test email sent successfully', ['email' => $email]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send test email', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Ayarları dışa aktar
     */
    public function exportSettings()
    {
        $settings = Setting::all()->map(function ($setting) {
            return [
                'key' => $setting->key,
                'value' => $setting->value,
                'type' => $setting->type,
                'group' => $this->getSettingGroup($setting->key),
            ];
        });

        return [
            'exported_at' => now()->toISOString(),
            'version' => '1.0',
            'settings' => $settings,
        ];
    }

    /**
     * Ayarları içe aktar
     */
    public function importSettings(array $data)
    {
        if (!isset($data['settings']) || !is_array($data['settings'])) {
            throw new \InvalidArgumentException('Invalid settings format');
        }

        $imported = 0;
        foreach ($data['settings'] as $setting) {
            if (!isset($setting['key']) || !isset($setting['value'])) {
                continue;
            }

            $this->updateSetting($setting['key'], $setting['value']);
            $imported++;
        }

        $this->clearCache();
        return $imported;
    }

    /**
     * Değer tipini belirle
     */
    private function getValueType($value)
    {
        if (is_bool($value)) {
            return 'boolean';
        } elseif (is_int($value)) {
            return 'integer';
        } elseif (is_float($value)) {
            return 'float';
        } elseif (is_array($value)) {
            return 'array';
        } else {
            return 'string';
        }
    }

    /**
     * Ayar grubunu belirle
     */
    private function getSettingGroup($key)
    {
        $groups = ['site', 'payment', 'email', 'social', 'seo'];
        
        foreach ($groups as $group) {
            if (strpos($key, $group . '_') === 0) {
                return $group;
            }
        }

        return 'general';
    }

    /**
     * Site bakım modunu kontrol et
     */
    public function isMaintenanceMode()
    {
        return (bool) $this->getSetting('site_maintenance_mode', false);
    }

    /**
     * Site bakım mesajını al
     */
    public function getMaintenanceMessage()
    {
        return $this->getSetting('site_maintenance_message', 'Site bakımda. Lütfen daha sonra tekrar deneyin.');
    }

    /**
     * Para birimi formatla
     */
    public function formatCurrency($amount)
    {
        $symbol = $this->getSetting('payment_currency_symbol', '₺');
        $currency = $this->getSetting('payment_currency', 'TRY');
        
        return $symbol . number_format($amount, 2);
    }

    /**
     * Vergi hesapla
     */
    public function calculateTax($amount)
    {
        $taxRate = (float) $this->getSetting('payment_tax_rate', 0);
        return $amount * ($taxRate / 100);
    }

    /**
     * Kargo ücreti hesapla
     */
    public function calculateShipping($amount)
    {
        $shippingCost = (float) $this->getSetting('payment_shipping_cost', 0);
        $freeShippingThreshold = (float) $this->getSetting('payment_free_shipping_threshold', 0);
        
        if ($freeShippingThreshold > 0 && $amount >= $freeShippingThreshold) {
            return 0;
        }
        
        return $shippingCost;
    }

    /**
     * SEO meta verilerini al
     */
    public function getSeoMeta($page = null)
    {
        $meta = [
            'title' => $this->getSetting('seo_meta_title', $this->getSetting('site_name')),
            'description' => $this->getSetting('seo_meta_description', $this->getSetting('site_description')),
            'keywords' => $this->getSetting('seo_meta_keywords', ''),
            'og_image' => $this->getSetting('seo_og_image', ''),
        ];

        // Sayfa özel meta verileri varsa onları kullan
        if ($page) {
            $pageTitle = $this->getSetting("seo_{$page}_title");
            $pageDescription = $this->getSetting("seo_{$page}_description");
            
            if ($pageTitle) $meta['title'] = $pageTitle;
            if ($pageDescription) $meta['description'] = $pageDescription;
        }

        return $meta;
    }

    /**
     * Sosyal medya linklerini al
     */
    public function getSocialLinks()
    {
        return [
            'facebook' => $this->getSetting('social_facebook_url'),
            'twitter' => $this->getSetting('social_twitter_url'),
            'instagram' => $this->getSetting('social_instagram_url'),
            'linkedin' => $this->getSetting('social_linkedin_url'),
            'youtube' => $this->getSetting('social_youtube_url'),
            'tiktok' => $this->getSetting('social_tiktok_url'),
            'whatsapp' => $this->getSetting('social_whatsapp_number'),
            'telegram' => $this->getSetting('social_telegram_url'),
            'discord' => $this->getSetting('social_discord_url'),
            'github' => $this->getSetting('social_github_url'),
        ];
    }

    /**
     * İletişim bilgilerini al
     */
    public function getContactInfo()
    {
        return [
            'email' => $this->getSetting('site_contact_email'),
            'phone' => $this->getSetting('site_contact_phone'),
            'address' => $this->getSetting('site_contact_address'),
            'admin_email' => $this->getSetting('site_admin_email'),
        ];
    }

    /**
     * Site logosunu al
     */
    public function getSiteLogo()
    {
        $logo = $this->getSetting('site_logo');
        return $logo ? asset('storage/' . $logo) : null;
    }

    /**
     * Site favicon'unu al
     */
    public function getSiteFavicon()
    {
        $favicon = $this->getSetting('site_favicon');
        return $favicon ? asset('storage/' . $favicon) : null;
    }

    /**
     * Google Analytics ID'sini al
     */
    public function getGoogleAnalyticsId()
    {
        return $this->getSetting('site_google_analytics_id');
    }

    /**
     * Facebook Pixel ID'sini al
     */
    public function getFacebookPixelId()
    {
        return $this->getSetting('site_facebook_pixel_id');
    }
}