<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $tasks = [
            // Daily Tasks - Health Category
            [
                'name' => 'Günlük Su İhtiyacı Hesapla',
                'slug' => 'daily-water-calculator',
                'description' => 'Su ihtiyacı hesaplayıcısını kullanarak günlük su ihtiyacını hesapla',
                'icon' => '💧',
                'type' => 'daily',
                'category' => 'health',
                'points' => 10,
                'xp_reward' => 5,
                'action_type' => 'calculator_water_use',
                'target_count' => 1,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'VKİ Hesapla',
                'slug' => 'daily-bmi-calculator',
                'description' => 'Vücut kitle indeksini hesapla ve sağlık durumunu kontrol et',
                'icon' => '⚖️',
                'type' => 'daily',
                'category' => 'health',
                'points' => 15,
                'xp_reward' => 8,
                'action_type' => 'calculator_bmi_use',
                'target_count' => 1,
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Günlük Giriş Yap',
                'slug' => 'daily-login',
                'description' => 'Platforma günlük giriş yaparak streak\'ini devam ettir',
                'icon' => '🔑',
                'type' => 'daily',
                'category' => 'engagement',
                'points' => 5,
                'xp_reward' => 3,
                'action_type' => 'login',
                'target_count' => 1,
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Profil Güncelle',
                'slug' => 'daily-profile-update',
                'description' => 'Profilini güncelleyerek bilgilerini taze tut',
                'icon' => '👤',
                'type' => 'daily',
                'category' => 'engagement',
                'points' => 20,
                'xp_reward' => 10,
                'action_type' => 'profile_update',
                'target_count' => 1,
                'is_active' => true,
                'sort_order' => 4
            ],

            // Daily Tasks - Social Category
            [
                'name' => 'Forum\'da Yorum Yap',
                'slug' => 'daily-forum-comment',
                'description' => 'Forum konularına yorum yaparak toplulukla etkileşim kur',
                'icon' => '💬',
                'type' => 'daily',
                'category' => 'social',
                'points' => 15,
                'xp_reward' => 8,
                'action_type' => 'forum_reply_create',
                'target_count' => 3,
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'Blog Yazısı Oku',
                'slug' => 'daily-blog-read',
                'description' => 'En az 2 blog yazısı okuyarak bilgini artır',
                'icon' => '📖',
                'type' => 'daily',
                'category' => 'learning',
                'points' => 10,
                'xp_reward' => 5,
                'action_type' => 'blog_post_view',
                'target_count' => 2,
                'is_active' => true,
                'sort_order' => 6
            ],

            // Weekly Tasks
            [
                'name' => 'Haftalık Blog Yazısı Yaz',
                'slug' => 'weekly-blog-post',
                'description' => 'Haftada en az 1 blog yazısı yazarak deneyimlerini paylaş',
                'icon' => '✍️',
                'type' => 'weekly',
                'category' => 'social',
                'points' => 50,
                'xp_reward' => 25,
                'action_type' => 'blog_post_create',
                'target_count' => 1,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Forum Konusu Aç',
                'slug' => 'weekly-forum-topic',
                'description' => 'Haftada en az 2 forum konusu açarak tartışma başlat',
                'icon' => '🗣️',
                'type' => 'weekly',
                'category' => 'social',
                'points' => 40,
                'xp_reward' => 20,
                'action_type' => 'forum_topic_create',
                'target_count' => 2,
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Hesaplayıcı Uzmanı',
                'slug' => 'weekly-calculator-expert',
                'description' => 'Haftada 5 farklı hesaplayıcı kullanarak sağlık takibini yap',
                'icon' => '🧮',
                'type' => 'weekly',
                'category' => 'health',
                'points' => 60,
                'xp_reward' => 30,
                'action_type' => 'calculator_use',
                'target_count' => 5,
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Sosyal Kelebek',
                'slug' => 'weekly-social-butterfly',
                'description' => 'Haftada 10 yorum yaparak aktif topluluk üyesi ol',
                'icon' => '🦋',
                'type' => 'weekly',
                'category' => 'social',
                'points' => 45,
                'xp_reward' => 22,
                'action_type' => 'forum_reply_create',
                'target_count' => 10,
                'is_active' => true,
                'sort_order' => 4
            ],

            // Monthly Tasks
            [
                'name' => 'Aylık İçerik Üreticisi',
                'slug' => 'monthly-content-creator',
                'description' => 'Ayda en az 4 blog yazısı yazarak içerik üreticisi ol',
                'icon' => '🏆',
                'type' => 'monthly',
                'category' => 'social',
                'points' => 200,
                'xp_reward' => 100,
                'action_type' => 'blog_post_create',
                'target_count' => 4,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Sağlık Takip Uzmanı',
                'slug' => 'monthly-health-tracker',
                'description' => 'Ayda 20 kez hesaplayıcı kullanarak sağlık takibinde uzmanlaş',
                'icon' => '🏥',
                'type' => 'monthly',
                'category' => 'health',
                'points' => 150,
                'xp_reward' => 75,
                'action_type' => 'calculator_use',
                'target_count' => 20,
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Topluluk Lideri',
                'slug' => 'monthly-community-leader',
                'description' => 'Ayda 8 forum konusu açarak topluluk lideri ol',
                'icon' => '👑',
                'type' => 'monthly',
                'category' => 'social',
                'points' => 300,
                'xp_reward' => 150,
                'action_type' => 'forum_topic_create',
                'target_count' => 8,
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Aktif Üye',
                'slug' => 'monthly-active-member',
                'description' => 'Ayda 25 gün giriş yaparak aktif üye ol',
                'icon' => '🌟',
                'type' => 'monthly',
                'category' => 'engagement',
                'points' => 250,
                'xp_reward' => 125,
                'action_type' => 'login',
                'target_count' => 25,
                'is_active' => true,
                'sort_order' => 4
            ],

            // Special Tasks
            [
                'name' => 'İlk Alışveriş',
                'slug' => 'special-first-purchase',
                'description' => 'E-ticaret bölümünden ilk alışverişini yap',
                'icon' => '🛍️',
                'type' => 'special',
                'category' => 'engagement',
                'points' => 100,
                'xp_reward' => 50,
                'action_type' => 'order_create',
                'target_count' => 1,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Etkinlik Katılımcısı',
                'slug' => 'special-event-participant',
                'description' => 'Bir etkinliğe katılım sağla',
                'icon' => '🎉',
                'type' => 'special',
                'category' => 'social',
                'points' => 75,
                'xp_reward' => 35,
                'action_type' => 'event_join',
                'target_count' => 1,
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Gebelik Takip Başlat',
                'slug' => 'special-pregnancy-tracker',
                'description' => 'Gebelik takip sistemini kullanmaya başla',
                'icon' => '🤱',
                'type' => 'special',
                'category' => 'health',
                'points' => 80,
                'xp_reward' => 40,
                'action_type' => 'pregnancy_tracker_create',
                'target_count' => 1,
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Astroloji Meraklısı',
                'slug' => 'special-astrology-enthusiast',
                'description' => 'Astroloji bölümünü 5 kez ziyaret et',
                'icon' => '🔮',
                'type' => 'special',
                'category' => 'wellness',
                'points' => 60,
                'xp_reward' => 30,
                'action_type' => 'astrology_view',
                'target_count' => 5,
                'is_active' => true,
                'sort_order' => 4
            ]
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}