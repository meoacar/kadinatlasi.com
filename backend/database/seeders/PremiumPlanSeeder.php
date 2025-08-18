<?php

namespace Database\Seeders;

use App\Models\PremiumPlan;
use Illuminate\Database\Seeder;

class PremiumPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'plan_id' => 'basic',
                'name' => 'Temel',
                'price' => 29.00,
                'duration' => 'aylık',
                'is_popular' => false,
                'features' => [
                    'Reklamsız deneyim',
                    'Temel hesaplama araçları',
                    'Forum erişimi'
                ],
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'plan_id' => 'premium',
                'name' => 'Premium',
                'price' => 59.00,
                'duration' => 'aylık',
                'is_popular' => true,
                'features' => [
                    'Tüm Temel özellikler',
                    'Premium astroloji analizleri',
                    'Kişiye özel diyet listeleri',
                    'Uzman danışmanlık',
                    'Öncelikli destek'
                ],
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'plan_id' => 'vip',
                'name' => 'VIP',
                'price' => 99.00,
                'duration' => 'aylık',
                'is_popular' => false,
                'features' => [
                    'Tüm Premium özellikler',
                    'Birebir koçluk seansları',
                    'Özel grup etkinlikleri',
                    'İleri düzey raporlar',
                    '7/24 destek'
                ],
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($plans as $plan) {
            PremiumPlan::updateOrCreate(
                ['plan_id' => $plan['plan_id']],
                $plan
            );
        }
    }
}