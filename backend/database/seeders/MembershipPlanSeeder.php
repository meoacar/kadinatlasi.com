<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MembershipPlan;

class MembershipPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic Üyelik',
                'slug' => 'basic',
                'description' => 'Temel premium özellikler ile KadınAtlası deneyimini yaşayın.',
                'price' => 29.99,
                'duration_days' => 30,
                'features' => [
                    'Aylık 5 uzman sorusu',
                    'Aylık 20 forum gönderisi', 
                    'Aylık 5 ikinci el ilan',
                    'Premium içeriklere erişim',
                    'Reklamsız deneyim',
                    'Öncelikli destek'
                ],
                'limits' => [
                    'expert_questions' => 5,
                    'forum_posts' => 20,
                    'second_hand_listings' => 5,
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Premium Üyelik',
                'slug' => 'premium',
                'description' => 'Gelişmiş özellikler ve daha fazla limit ile tam KadınAtlası deneyimi.',
                'price' => 49.99,
                'duration_days' => 30,
                'features' => [
                    'Aylık 15 uzman sorusu',
                    'Aylık 50 forum gönderisi',
                    'Aylık 10 ikinci el ilan', 
                    'Tüm premium içeriklere erişim',
                    'Reklamsız deneyim',
                    'Öncelikli destek',
                    'Özel webinarlar',
                    'İndirimli kurs fiyatları'
                ],
                'limits' => [
                    'expert_questions' => 15,
                    'forum_posts' => 50,
                    'second_hand_listings' => 10,
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'VIP Üyelik',
                'slug' => 'vip',
                'description' => 'Sınırsız erişim ve özel ayrıcalıklar ile en üst düzey deneyim.',
                'price' => 99.99,
                'duration_days' => 30,
                'features' => [
                    'Sınırsız uzman sorusu',
                    'Sınırsız forum gönderisi',
                    'Aylık 20 ikinci el ilan',
                    'Tüm premium içeriklere erişim', 
                    'Reklamsız deneyim',
                    'VIP destek hattı',
                    'Özel webinarlar',
                    'Ücretsiz kurslar',
                    'Kişisel danışmanlık',
                    'Özel etkinlik davetleri'
                ],
                'limits' => [
                    'expert_questions' => -1, // unlimited
                    'forum_posts' => -1, // unlimited
                    'second_hand_listings' => 20,
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
            // 3 aylık planlar
            [
                'name' => 'Basic Üyelik (3 Ay)',
                'slug' => 'basic-3m',
                'description' => '3 aylık Basic üyelik ile %20 tasarruf edin.',
                'price' => 71.99, // 29.99 * 3 * 0.8
                'duration_days' => 90,
                'features' => [
                    'Aylık 5 uzman sorusu',
                    'Aylık 20 forum gönderisi',
                    'Aylık 5 ikinci el ilan',
                    'Premium içeriklere erişim',
                    'Reklamsız deneyim', 
                    'Öncelikli destek',
                    '%20 indirim avantajı'
                ],
                'limits' => [
                    'expert_questions' => 5,
                    'forum_posts' => 20,
                    'second_hand_listings' => 5,
                ],
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Premium Üyelik (3 Ay)',
                'slug' => 'premium-3m',
                'description' => '3 aylık Premium üyelik ile %20 tasarruf edin.',
                'price' => 119.99, // 49.99 * 3 * 0.8
                'duration_days' => 90,
                'features' => [
                    'Aylık 15 uzman sorusu',
                    'Aylık 50 forum gönderisi',
                    'Aylık 10 ikinci el ilan',
                    'Tüm premium içeriklere erişim',
                    'Reklamsız deneyim',
                    'Öncelikli destek', 
                    'Özel webinarlar',
                    'İndirimli kurs fiyatları',
                    '%20 indirim avantajı'
                ],
                'limits' => [
                    'expert_questions' => 15,
                    'forum_posts' => 50,
                    'second_hand_listings' => 10,
                ],
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'VIP Üyelik (3 Ay)',
                'slug' => 'vip-3m',
                'description' => '3 aylık VIP üyelik ile %20 tasarruf edin.',
                'price' => 239.99, // 99.99 * 3 * 0.8
                'duration_days' => 90,
                'features' => [
                    'Sınırsız uzman sorusu',
                    'Sınırsız forum gönderisi',
                    'Aylık 20 ikinci el ilan',
                    'Tüm premium içeriklere erişim',
                    'Reklamsız deneyim',
                    'VIP destek hattı',
                    'Özel webinarlar', 
                    'Ücretsiz kurslar',
                    'Kişisel danışmanlık',
                    'Özel etkinlik davetleri',
                    '%20 indirim avantajı'
                ],
                'limits' => [
                    'expert_questions' => -1,
                    'forum_posts' => -1,
                    'second_hand_listings' => 20,
                ],
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::create($plan);
        }
    }
}