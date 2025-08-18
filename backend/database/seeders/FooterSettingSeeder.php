<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FooterSetting;

class FooterSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'footer_description',
                'value' => 'KadınAtlası.com, kadınların günlük hayatını kolaylaştıran, bilgi alabilecekleri, hesaplamalar yapabilecekleri ve topluluk oluşturabilecekleri kapsamlı bir dijital platformdur.',
                'type' => 'textarea',
                'group' => 'general',
                'label' => 'Footer Açıklaması',
                'description' => 'Footer alanında gösterilecek ana açıklama metni',
                'sort_order' => 1
            ],
            [
                'key' => 'footer_copyright',
                'value' => '© 2024 KadınAtlası.com. Tüm hakları saklıdır.',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Telif Hakkı Metni',
                'description' => 'Footer alt kısmında gösterilecek telif hakkı metni',
                'sort_order' => 2
            ],
            
            // Contact Information
            [
                'key' => 'contact_email',
                'value' => 'info@kadinatlasi.com',
                'type' => 'email',
                'group' => 'contact',
                'label' => 'İletişim E-postası',
                'description' => 'Ana iletişim e-posta adresi',
                'sort_order' => 1
            ],
            [
                'key' => 'contact_phone',
                'value' => '+90 (212) 555 0123',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'İletişim Telefonu',
                'description' => 'Ana iletişim telefon numarası',
                'sort_order' => 2
            ],
            [
                'key' => 'contact_address',
                'value' => 'İstanbul, Türkiye',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Adres',
                'description' => 'Şirket adresi',
                'sort_order' => 3
            ],
            
            // Social Media Links
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/kadinatlasi',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Facebook URL',
                'description' => 'Facebook sayfa linki',
                'sort_order' => 1
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/kadinatlasi',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Instagram URL',
                'description' => 'Instagram profil linki',
                'sort_order' => 2
            ],
            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com/kadinatlasi',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Twitter URL',
                'description' => 'Twitter profil linki',
                'sort_order' => 3
            ],
            [
                'key' => 'social_youtube',
                'value' => 'https://youtube.com/@kadinatlasi',
                'type' => 'url',
                'group' => 'social',
                'label' => 'YouTube URL',
                'description' => 'YouTube kanal linki',
                'sort_order' => 4
            ],
            [
                'key' => 'social_linkedin',
                'value' => 'https://linkedin.com/company/kadinatlasi',
                'type' => 'url',
                'group' => 'social',
                'label' => 'LinkedIn URL',
                'description' => 'LinkedIn şirket sayfası',
                'sort_order' => 5
            ],
            
            // Quick Links
            [
                'key' => 'quick_links',
                'value' => json_encode([
                    ['name' => 'Hakkımızda', 'url' => '/hakkimizda'],
                    ['name' => 'İletişim', 'url' => '/iletisim'],
                    ['name' => 'Gizlilik Politikası', 'url' => '/gizlilik-politikasi'],
                    ['name' => 'Kullanım Şartları', 'url' => '/kullanim-sartlari'],
                    ['name' => 'SSS', 'url' => '/sss'],
                    ['name' => 'Destek', 'url' => '/destek']
                ]),
                'type' => 'json',
                'group' => 'links',
                'label' => 'Hızlı Linkler',
                'description' => 'Footer\'da gösterilecek hızlı erişim linkleri',
                'sort_order' => 1
            ],
            
            // Categories Links
            [
                'key' => 'category_links',
                'value' => json_encode([
                    ['name' => 'Sağlık', 'url' => '/saglik'],
                    ['name' => 'Gebelik & Anne', 'url' => '/gebelik'],
                    ['name' => 'Güzellik', 'url' => '/guzellik'],
                    ['name' => 'Fitness', 'url' => '/fitness'],
                    ['name' => 'Psikoloji', 'url' => '/psikoloji'],
                    ['name' => 'Astroloji', 'url' => '/astroloji']
                ]),
                'type' => 'json',
                'group' => 'links',
                'label' => 'Kategori Linkleri',
                'description' => 'Footer\'da gösterilecek kategori linkleri',
                'sort_order' => 2
            ],
            
            // Newsletter
            [
                'key' => 'newsletter_title',
                'value' => 'Bültenimize Abone Olun',
                'type' => 'text',
                'group' => 'newsletter',
                'label' => 'Bülten Başlığı',
                'description' => 'Newsletter bölümü başlığı',
                'sort_order' => 1
            ],
            [
                'key' => 'newsletter_description',
                'value' => 'En son haberler, ipuçları ve özel içerikler için e-posta listemize katılın.',
                'type' => 'textarea',
                'group' => 'newsletter',
                'label' => 'Bülten Açıklaması',
                'description' => 'Newsletter bölümü açıklama metni',
                'sort_order' => 2
            ]
        ];

        foreach ($settings as $setting) {
            FooterSetting::create($setting);
        }
    }
}