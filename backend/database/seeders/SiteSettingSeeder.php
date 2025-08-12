<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Genel Ayarlar
            [
                'key' => 'site_name',
                'value' => 'KadınAtlası.com',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Adı',
                'description' => 'Sitenin ana başlığı',
                'sort_order' => 1
            ],
            [
                'key' => 'site_description',
                'value' => 'Kadınların günlük hayatını kolaylaştıran dijital platform',
                'type' => 'textarea',
                'group' => 'general',
                'label' => 'Site Açıklaması',
                'description' => 'Sitenin kısa açıklaması',
                'sort_order' => 2
            ],
            [
                'key' => 'admin_email',
                'value' => 'admin@kadinatlasi.com',
                'type' => 'email',
                'group' => 'general',
                'label' => 'Admin E-posta',
                'description' => 'Sistem yöneticisi e-posta adresi',
                'sort_order' => 3
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'general',
                'label' => 'Bakım Modu',
                'description' => 'Site bakım modunda mı?',
                'sort_order' => 4
            ],
            [
                'key' => 'user_registration',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'general',
                'label' => 'Kullanıcı Kaydı',
                'description' => 'Yeni kullanıcı kaydına izin ver',
                'sort_order' => 5
            ],

            // SEO Ayarları
            [
                'key' => 'seo_title',
                'value' => 'KadınAtlası - Kadınlar İçin Dijital Platform',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'SEO Başlık',
                'description' => 'Ana sayfa SEO başlığı',
                'sort_order' => 1
            ],
            [
                'key' => 'seo_description',
                'value' => 'Hesaplama araçları, blog, forum ve daha fazlası ile kadınların günlük hayatını kolaylaştıran platform.',
                'type' => 'textarea',
                'group' => 'seo',
                'label' => 'SEO Açıklama',
                'description' => 'Ana sayfa meta açıklaması',
                'sort_order' => 2
            ],
            [
                'key' => 'seo_keywords',
                'value' => 'kadın, sağlık, gebelik, diyet, fitness, hesaplama araçları',
                'type' => 'textarea',
                'group' => 'seo',
                'label' => 'SEO Anahtar Kelimeler',
                'description' => 'Ana sayfa anahtar kelimeleri',
                'sort_order' => 3
            ],
            [
                'key' => 'google_analytics_id',
                'value' => '',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Google Analytics ID',
                'description' => 'Google Analytics takip kodu',
                'sort_order' => 4
            ],
            [
                'key' => 'google_search_console',
                'value' => '',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Google Search Console',
                'description' => 'Google Search Console doğrulama kodu',
                'sort_order' => 5
            ],

            // SMTP Ayarları
            [
                'key' => 'smtp_host',
                'value' => 'smtp.gmail.com',
                'type' => 'text',
                'group' => 'smtp',
                'label' => 'SMTP Host',
                'description' => 'E-posta sunucu adresi',
                'sort_order' => 1
            ],
            [
                'key' => 'smtp_port',
                'value' => '587',
                'type' => 'number',
                'group' => 'smtp',
                'label' => 'SMTP Port',
                'description' => 'E-posta sunucu portu',
                'sort_order' => 2
            ],
            [
                'key' => 'smtp_username',
                'value' => '',
                'type' => 'text',
                'group' => 'smtp',
                'label' => 'SMTP Kullanıcı Adı',
                'description' => 'E-posta hesabı kullanıcı adı',
                'sort_order' => 3
            ],
            [
                'key' => 'smtp_password',
                'value' => '',
                'type' => 'text',
                'group' => 'smtp',
                'label' => 'SMTP Şifre',
                'description' => 'E-posta hesabı şifresi',
                'sort_order' => 4
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@kadinatlasi.com',
                'type' => 'email',
                'group' => 'smtp',
                'label' => 'Gönderen E-posta',
                'description' => 'Sistem e-postalarının gönderen adresi',
                'sort_order' => 5
            ],

            // Sosyal Medya
            [
                'key' => 'facebook_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Facebook URL',
                'description' => 'Facebook sayfa linki',
                'sort_order' => 1
            ],
            [
                'key' => 'instagram_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Instagram URL',
                'description' => 'Instagram profil linki',
                'sort_order' => 2
            ],
            [
                'key' => 'twitter_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Twitter URL',
                'description' => 'Twitter profil linki',
                'sort_order' => 3
            ],
            [
                'key' => 'youtube_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'YouTube URL',
                'description' => 'YouTube kanal linki',
                'sort_order' => 4
            ],

            // Güvenlik
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'type' => 'number',
                'group' => 'security',
                'label' => 'Maksimum Giriş Denemesi',
                'description' => 'Hesap kilitlenmeden önceki deneme sayısı',
                'sort_order' => 1
            ],
            [
                'key' => 'session_timeout',
                'value' => '120',
                'type' => 'number',
                'group' => 'security',
                'label' => 'Oturum Zaman Aşımı (dakika)',
                'description' => 'Kullanıcı oturumu zaman aşımı süresi',
                'sort_order' => 2
            ],
            [
                'key' => 'password_min_length',
                'value' => '8',
                'type' => 'number',
                'group' => 'security',
                'label' => 'Minimum Şifre Uzunluğu',
                'description' => 'Kullanıcı şifresi minimum karakter sayısı',
                'sort_order' => 3
            ],

            // Görünüm
            [
                'key' => 'theme_color',
                'value' => '#E57399',
                'type' => 'text',
                'group' => 'appearance',
                'label' => 'Tema Rengi',
                'description' => 'Site ana tema rengi',
                'sort_order' => 1
            ],
            [
                'key' => 'posts_per_page',
                'value' => '12',
                'type' => 'number',
                'group' => 'appearance',
                'label' => 'Sayfa Başına Yazı',
                'description' => 'Blog sayfasında gösterilecek yazı sayısı',
                'sort_order' => 2
            ],
            [
                'key' => 'enable_comments',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'appearance',
                'label' => 'Yorumları Etkinleştir',
                'description' => 'Blog yazılarında yorum özelliği',
                'sort_order' => 3
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}