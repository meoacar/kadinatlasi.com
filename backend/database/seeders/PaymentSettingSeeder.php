<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentSetting;

class PaymentSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // İyzico Ayarları
            [
                'key' => 'iyzico_api_key',
                'value' => 'sandbox-api-key',
                'type' => 'password',
                'group' => 'iyzico',
                'label' => 'İyzico API Key',
                'description' => 'İyzico panelinden alacağınız API anahtarı',
                'is_encrypted' => true,
            ],
            [
                'key' => 'iyzico_secret_key',
                'value' => 'sandbox-secret-key',
                'type' => 'password',
                'group' => 'iyzico',
                'label' => 'İyzico Secret Key',
                'description' => 'İyzico panelinden alacağınız gizli anahtar',
                'is_encrypted' => true,
            ],
            [
                'key' => 'iyzico_base_url',
                'value' => 'https://sandbox-api.iyzipay.com',
                'type' => 'url',
                'group' => 'iyzico',
                'label' => 'İyzico Base URL',
                'description' => 'Test için sandbox, canlı için api.iyzipay.com kullanın',
                'is_encrypted' => false,
            ],
            [
                'key' => 'iyzico_test_mode',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'iyzico',
                'label' => 'Test Modu',
                'description' => 'Test modunda çalışsın mı?',
                'is_encrypted' => false,
            ],
            
            // Genel Ödeme Ayarları
            [
                'key' => 'payment_currency',
                'value' => 'TRY',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Para Birimi',
                'description' => 'Varsayılan para birimi (TRY, USD, EUR)',
                'is_encrypted' => false,
            ],
            [
                'key' => 'payment_success_url',
                'value' => '/premium/success',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Başarı Sayfası',
                'description' => 'Ödeme başarılı olduğunda yönlendirilecek sayfa',
                'is_encrypted' => false,
            ],
            [
                'key' => 'payment_failed_url',
                'value' => '/premium/failed',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Hata Sayfası',
                'description' => 'Ödeme başarısız olduğunda yönlendirilecek sayfa',
                'is_encrypted' => false,
            ],
        ];

        foreach ($settings as $setting) {
            PaymentSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}