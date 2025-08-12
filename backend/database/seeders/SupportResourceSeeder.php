<?php

namespace Database\Seeders;

use App\Models\SupportResource;
use Illuminate\Database\Seeder;

class SupportResourceSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            // Acil Durum
            [
                'title' => 'Kadın Acil Yardım Hattı',
                'description' => '7/24 kadına yönelik şiddet ve acil durumlar için yardım hattı',
                'category' => 'emergency',
                'type' => 'hotline',
                'phone' => '183',
                'is_emergency' => true,
                'is_free' => true,
                'working_hours' => ['24/7'],
                'sort_order' => 1
            ],
            [
                'title' => 'Alo 155 Polis İmdat',
                'description' => 'Acil durumlarda polis yardımı',
                'category' => 'emergency',
                'type' => 'hotline',
                'phone' => '155',
                'is_emergency' => true,
                'is_free' => true,
                'working_hours' => ['24/7'],
                'sort_order' => 2
            ],
            [
                'title' => 'Alo 112 Acil Sağlık',
                'description' => 'Tıbbi acil durumlar için ambulans hizmeti',
                'category' => 'emergency',
                'type' => 'hotline',
                'phone' => '112',
                'is_emergency' => true,
                'is_free' => true,
                'working_hours' => ['24/7'],
                'sort_order' => 3
            ],

            // Psikolojik Destek
            [
                'title' => 'Türk Psikologlar Derneği Danışma Hattı',
                'description' => 'Ücretsiz psikolojik danışmanlık hizmeti',
                'category' => 'psychological',
                'type' => 'hotline',
                'phone' => '0312 466 39 69',
                'url' => 'https://www.psikolog.org.tr',
                'is_emergency' => false,
                'is_free' => true,
                'working_hours' => ['Pazartesi-Cuma: 09:00-17:00'],
                'sort_order' => 1
            ],
            [
                'title' => 'Ruh Sağlığı Derneği',
                'description' => 'Ruh sağlığı konularında bilgilendirme ve destek',
                'category' => 'psychological',
                'type' => 'organization',
                'phone' => '0212 246 64 80',
                'url' => 'https://ruhsagligidernegi.org',
                'email' => 'info@ruhsagligidernegi.org',
                'is_emergency' => false,
                'is_free' => true,
                'working_hours' => ['Pazartesi-Cuma: 09:00-18:00'],
                'sort_order' => 2
            ],
            [
                'title' => 'Online Terapi Platformu',
                'description' => 'Uzaktan psikolojik danışmanlık hizmeti',
                'category' => 'psychological',
                'type' => 'website',
                'url' => 'https://online-terapi.com',
                'is_emergency' => false,
                'is_free' => false,
                'working_hours' => ['7/24 Online'],
                'sort_order' => 3
            ],

            // Tıbbi Destek
            [
                'title' => 'Sağlık Bakanlığı ALO 184',
                'description' => 'Sağlık danışma hattı',
                'category' => 'medical',
                'type' => 'hotline',
                'phone' => '184',
                'is_emergency' => false,
                'is_free' => true,
                'working_hours' => ['24/7'],
                'sort_order' => 1
            ],
            [
                'title' => 'Kadın Doğum Uzmanları Derneği',
                'description' => 'Kadın sağlığı konularında bilgilendirme',
                'category' => 'medical',
                'type' => 'organization',
                'url' => 'https://www.tjod.org',
                'phone' => '0312 446 72 70',
                'is_emergency' => false,
                'is_free' => true,
                'working_hours' => ['Pazartesi-Cuma: 08:30-17:30'],
                'sort_order' => 2
            ],

            // Hukuki Destek
            [
                'title' => 'Türkiye Barolar Birliği Hukuki Yardım',
                'description' => 'Ücretsiz hukuki danışmanlık hizmeti',
                'category' => 'legal',
                'type' => 'organization',
                'phone' => '0312 425 30 00',
                'url' => 'https://www.barobirlik.org.tr',
                'is_emergency' => false,
                'is_free' => true,
                'working_hours' => ['Pazartesi-Cuma: 09:00-17:00'],
                'sort_order' => 1
            ],
            [
                'title' => 'Kadın Hakları Derneği',
                'description' => 'Kadın hakları konularında hukuki destek',
                'category' => 'legal',
                'type' => 'organization',
                'phone' => '0212 252 33 02',
                'url' => 'https://www.kadinhaklaridernegi.org',
                'email' => 'info@kadinhaklaridernegi.org',
                'is_emergency' => false,
                'is_free' => true,
                'working_hours' => ['Pazartesi-Cuma: 09:00-18:00'],
                'sort_order' => 2
            ],

            // Sosyal Destek
            [
                'title' => 'Aile ve Sosyal Hizmetler Bakanlığı',
                'description' => 'Sosyal yardım ve destek hizmetleri',
                'category' => 'social',
                'type' => 'organization',
                'phone' => '144',
                'url' => 'https://www.aile.gov.tr',
                'is_emergency' => false,
                'is_free' => true,
                'working_hours' => ['24/7'],
                'sort_order' => 1
            ],
            [
                'title' => 'Mor Çatı Kadın Sığınağı Vakfı',
                'description' => 'Kadına yönelik şiddetle mücadele ve sığınak hizmetleri',
                'category' => 'social',
                'type' => 'organization',
                'phone' => '0212 292 52 31',
                'url' => 'https://www.morcati.org.tr',
                'email' => 'bilgi@morcati.org.tr',
                'is_emergency' => false,
                'is_free' => true,
                'working_hours' => ['Pazartesi-Cuma: 09:00-17:00'],
                'sort_order' => 2
            ]
        ];

        foreach ($resources as $resource) {
            SupportResource::create($resource);
        }
    }
}