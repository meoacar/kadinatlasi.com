<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;
use App\Models\AchievementCategory;

class AchievementSeeder extends Seeder
{
    public function run()
    {
        // Create Achievement Categories
        $categories = [
            [
                'name' => 'Sosyal Etkileşim',
                'slug' => 'social-interaction',
                'description' => 'Forum ve topluluk etkileşimi başarımları',
                'icon' => '👥',
                'color' => '#E57399'
            ],
            [
                'name' => 'Sağlık ve Wellness',
                'slug' => 'health-wellness',
                'description' => 'Sağlık takibi ve wellness başarımları',
                'icon' => '🏥',
                'color' => '#4CAF50'
            ],
            [
                'name' => 'İçerik Üretimi',
                'slug' => 'content-creation',
                'description' => 'Blog yazısı ve içerik üretimi başarımları',
                'icon' => '✍️',
                'color' => '#FF9800'
            ],
            [
                'name' => 'Platform Kullanımı',
                'slug' => 'platform-usage',
                'description' => 'Platform kullanımı ve aktivite başarımları',
                'icon' => '🌟',
                'color' => '#9C27B0'
            ],
            [
                'name' => 'Özel Başarımlar',
                'slug' => 'special-achievements',
                'description' => 'Özel ve nadir başarımlar',
                'icon' => '🏆',
                'color' => '#FFD700'
            ]
        ];

        foreach ($categories as $category) {
            AchievementCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        // Get category IDs
        $socialCategory = AchievementCategory::where('slug', 'social-interaction')->first()->id;
        $healthCategory = AchievementCategory::where('slug', 'health-wellness')->first()->id;
        $contentCategory = AchievementCategory::where('slug', 'content-creation')->first()->id;
        $platformCategory = AchievementCategory::where('slug', 'platform-usage')->first()->id;
        $specialCategory = AchievementCategory::where('slug', 'special-achievements')->first()->id;

        $achievements = [
            // Social Interaction Achievements
            [
                'category_id' => $socialCategory,
                'name' => 'İlk Adım',
                'slug' => 'first-step',
                'description' => 'İlk forum yorumunu yap',
                'icon' => '👋',
                'badge_color' => '#8BC34A',
                'type' => 'one_time',
                'difficulty' => 'bronze',
                'points' => 10,
                'target_value' => 1,
                'target_metric' => 'forum_reply_create',
                'sort_order' => 1
            ],
            [
                'category_id' => $socialCategory,
                'name' => 'Konuşkan',
                'slug' => 'talkative',
                'description' => '10 forum yorumu yap',
                'icon' => '💬',
                'badge_color' => '#2196F3',
                'type' => 'one_time',
                'difficulty' => 'silver',
                'points' => 50,
                'target_value' => 10,
                'target_metric' => 'forum_reply_create',
                'sort_order' => 2
            ],
            [
                'category_id' => $socialCategory,
                'name' => 'Sosyal Kelebek',
                'slug' => 'social-butterfly',
                'description' => '50 forum yorumu yap',
                'icon' => '🦋',
                'badge_color' => '#FF9800',
                'type' => 'one_time',
                'difficulty' => 'gold',
                'points' => 200,
                'target_value' => 50,
                'target_metric' => 'forum_reply_create',
                'sort_order' => 3
            ],
            [
                'category_id' => $socialCategory,
                'name' => 'Topluluk Lideri',
                'slug' => 'community-leader',
                'description' => '100 forum yorumu yap',
                'icon' => '👑',
                'badge_color' => '#9C27B0',
                'type' => 'one_time',
                'difficulty' => 'platinum',
                'points' => 500,
                'target_value' => 100,
                'target_metric' => 'forum_reply_create',
                'sort_order' => 4
            ],
            [
                'category_id' => $socialCategory,
                'name' => 'Efsane',
                'slug' => 'legend',
                'description' => '500 forum yorumu yap',
                'icon' => '💎',
                'badge_color' => '#00BCD4',
                'type' => 'one_time',
                'difficulty' => 'diamond',
                'points' => 1000,
                'target_value' => 500,
                'target_metric' => 'forum_reply_create',
                'sort_order' => 5
            ],

            // Health & Wellness Achievements
            [
                'category_id' => $healthCategory,
                'name' => 'Sağlık Bilinci',
                'slug' => 'health-awareness',
                'description' => 'İlk hesaplayıcıyı kullan',
                'icon' => '🏥',
                'badge_color' => '#4CAF50',
                'type' => 'one_time',
                'difficulty' => 'bronze',
                'points' => 15,
                'target_value' => 1,
                'target_metric' => 'calculator_use',
                'sort_order' => 1
            ],
            [
                'category_id' => $healthCategory,
                'name' => 'Sağlık Takipçisi',
                'slug' => 'health-tracker',
                'description' => '10 kez hesaplayıcı kullan',
                'icon' => '📊',
                'badge_color' => '#2196F3',
                'type' => 'one_time',
                'difficulty' => 'silver',
                'points' => 75,
                'target_value' => 10,
                'target_metric' => 'calculator_use',
                'sort_order' => 2
            ],
            [
                'category_id' => $healthCategory,
                'name' => 'Wellness Uzmanı',
                'slug' => 'wellness-expert',
                'description' => '50 kez hesaplayıcı kullan',
                'icon' => '🌿',
                'badge_color' => '#FF9800',
                'type' => 'one_time',
                'difficulty' => 'gold',
                'points' => 300,
                'target_value' => 50,
                'target_metric' => 'calculator_use',
                'sort_order' => 3
            ],
            [
                'category_id' => $healthCategory,
                'name' => 'Su İçme Uzmanı',
                'slug' => 'water-expert',
                'description' => 'Su hesaplayıcısını 30 kez kullan',
                'icon' => '💧',
                'badge_color' => '#00BCD4',
                'type' => 'one_time',
                'difficulty' => 'gold',
                'points' => 200,
                'target_value' => 30,
                'target_metric' => 'calculator_water_use',
                'sort_order' => 4
            ],

            // Content Creation Achievements
            [
                'category_id' => $contentCategory,
                'name' => 'İlk Yazı',
                'slug' => 'first-post',
                'description' => 'İlk blog yazını yaz',
                'icon' => '📝',
                'badge_color' => '#8BC34A',
                'type' => 'one_time',
                'difficulty' => 'bronze',
                'points' => 25,
                'target_value' => 1,
                'target_metric' => 'blog_post_create',
                'sort_order' => 1
            ],
            [
                'category_id' => $contentCategory,
                'name' => 'Yazar',
                'slug' => 'writer',
                'description' => '5 blog yazısı yaz',
                'icon' => '✍️',
                'badge_color' => '#2196F3',
                'type' => 'one_time',
                'difficulty' => 'silver',
                'points' => 100,
                'target_value' => 5,
                'target_metric' => 'blog_post_create',
                'sort_order' => 2
            ],
            [
                'category_id' => $contentCategory,
                'name' => 'İçerik Üreticisi',
                'slug' => 'content-creator',
                'description' => '20 blog yazısı yaz',
                'icon' => '🎨',
                'badge_color' => '#FF9800',
                'type' => 'one_time',
                'difficulty' => 'gold',
                'points' => 400,
                'target_value' => 20,
                'target_metric' => 'blog_post_create',
                'sort_order' => 3
            ],
            [
                'category_id' => $contentCategory,
                'name' => 'Editör',
                'slug' => 'editor',
                'description' => '50 blog yazısı yaz',
                'icon' => '📚',
                'badge_color' => '#9C27B0',
                'type' => 'one_time',
                'difficulty' => 'platinum',
                'points' => 800,
                'target_value' => 50,
                'target_metric' => 'blog_post_create',
                'sort_order' => 4
            ],

            // Platform Usage Achievements
            [
                'category_id' => $platformCategory,
                'name' => 'Hoş Geldin',
                'slug' => 'welcome',
                'description' => 'Platforma ilk giriş yap',
                'icon' => '🎉',
                'badge_color' => '#E91E63',
                'type' => 'one_time',
                'difficulty' => 'bronze',
                'points' => 5,
                'target_value' => 1,
                'target_metric' => 'login',
                'sort_order' => 1
            ],
            [
                'category_id' => $platformCategory,
                'name' => 'Düzenli Kullanıcı',
                'slug' => 'regular-user',
                'description' => '7 gün üst üste giriş yap',
                'icon' => '📅',
                'badge_color' => '#2196F3',
                'type' => 'one_time',
                'difficulty' => 'silver',
                'points' => 50,
                'target_value' => 7,
                'target_metric' => 'daily_streak',
                'sort_order' => 2
            ],
            [
                'category_id' => $platformCategory,
                'name' => 'Sadık Kullanıcı',
                'slug' => 'loyal-user',
                'description' => '30 gün üst üste giriş yap',
                'icon' => '🔥',
                'badge_color' => '#FF9800',
                'type' => 'one_time',
                'difficulty' => 'gold',
                'points' => 200,
                'target_value' => 30,
                'target_metric' => 'daily_streak',
                'sort_order' => 3
            ],
            [
                'category_id' => $platformCategory,
                'name' => 'Bağımlı',
                'slug' => 'addicted',
                'description' => '100 gün üst üste giriş yap',
                'icon' => '💯',
                'badge_color' => '#9C27B0',
                'type' => 'one_time',
                'difficulty' => 'platinum',
                'points' => 500,
                'target_value' => 100,
                'target_metric' => 'daily_streak',
                'sort_order' => 4
            ],

            // Special Achievements
            [
                'category_id' => $specialCategory,
                'name' => 'Erken Kuş',
                'slug' => 'early-bird',
                'description' => 'Sabah 6:00-8:00 arası giriş yap',
                'icon' => '🐦',
                'badge_color' => '#FFD700',
                'type' => 'repeatable',
                'difficulty' => 'gold',
                'points' => 30,
                'target_value' => 1,
                'target_metric' => 'early_login',
                'is_hidden' => true,
                'sort_order' => 1
            ],
            [
                'category_id' => $specialCategory,
                'name' => 'Gece Kuşu',
                'slug' => 'night-owl',
                'description' => 'Gece 22:00-02:00 arası giriş yap',
                'icon' => '🦉',
                'badge_color' => '#673AB7',
                'type' => 'repeatable',
                'difficulty' => 'gold',
                'points' => 30,
                'target_value' => 1,
                'target_metric' => 'late_login',
                'is_hidden' => true,
                'sort_order' => 2
            ],
            [
                'category_id' => $specialCategory,
                'name' => 'Alışveriş Kraliçesi',
                'slug' => 'shopping-queen',
                'description' => 'İlk alışverişini tamamla',
                'icon' => '👑',
                'badge_color' => '#E91E63',
                'type' => 'one_time',
                'difficulty' => 'gold',
                'points' => 100,
                'target_value' => 1,
                'target_metric' => 'order_create',
                'sort_order' => 3
            ],
            [
                'category_id' => $specialCategory,
                'name' => 'Astroloji Meraklısı',
                'slug' => 'astrology-enthusiast',
                'description' => 'Astroloji bölümünü 10 kez ziyaret et',
                'icon' => '🔮',
                'badge_color' => '#9C27B0',
                'type' => 'one_time',
                'difficulty' => 'silver',
                'points' => 75,
                'target_value' => 10,
                'target_metric' => 'astrology_view',
                'sort_order' => 4
            ],
            [
                'category_id' => $specialCategory,
                'name' => 'Anne Adayı',
                'slug' => 'expectant-mother',
                'description' => 'Gebelik takibini başlat',
                'icon' => '🤱',
                'badge_color' => '#FF9800',
                'type' => 'one_time',
                'difficulty' => 'gold',
                'points' => 150,
                'target_value' => 1,
                'target_metric' => 'pregnancy_tracker_create',
                'sort_order' => 5
            ]
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}