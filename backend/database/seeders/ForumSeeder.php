<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        // Forum kategorilerini oluştur
        $categories = [
            [
                'name' => 'Anneler Kulübü',
                'slug' => 'anneler-kulubu',
                'description' => 'Hamilelik, doğum, bebek bakımı ve çocuk gelişimi konularında deneyim paylaşımı',
                'icon' => '🤱',
                'color' => '#e57399',
                'sort_order' => 1,
                'topics_count' => 3245,
                'posts_count' => 18420
            ],
            [
                'name' => 'Kariyer & İş Hayatı',
                'slug' => 'kariyer-is-hayati',
                'description' => 'İş arama, kariyer gelişimi, girişimcilik ve iş-yaşam dengesi',
                'icon' => '💼',
                'color' => '#3b82f6',
                'sort_order' => 2,
                'topics_count' => 1892,
                'posts_count' => 12650
            ],
            [
                'name' => 'Sağlık & Wellness',
                'slug' => 'saglik-wellness',
                'description' => 'Kadın sağlığı, beslenme, egzersiz ve mental sağlık',
                'icon' => '🏥',
                'color' => '#10b981',
                'sort_order' => 3,
                'topics_count' => 2156,
                'posts_count' => 15780
            ],
            [
                'name' => 'Güzellik & Bakım',
                'slug' => 'guzellik-bakim',
                'description' => 'Cilt bakımı, makyaj, saç bakımı ve güzellik ipuçları',
                'icon' => '💄',
                'color' => '#f59e0b',
                'sort_order' => 4,
                'topics_count' => 1567,
                'posts_count' => 9340
            ],
            [
                'name' => 'İlişkiler & Evlilik',
                'slug' => 'iliskiler-evlilik',
                'description' => 'Romantik ilişkiler, evlilik, aile hayatı ve sosyal ilişkiler',
                'icon' => '💕',
                'color' => '#ec4899',
                'sort_order' => 5,
                'topics_count' => 2890,
                'posts_count' => 16720
            ],
            [
                'name' => 'Hobi & Yaşam',
                'slug' => 'hobi-yasam',
                'description' => 'El sanatları, yemek, dekorasyon ve yaşam tarzı',
                'icon' => '🎨',
                'color' => '#8b5cf6',
                'sort_order' => 6,
                'topics_count' => 1234,
                'posts_count' => 8560
            ]
        ];

        foreach ($categories as $categoryData) {
            ForumCategory::create($categoryData);
        }

        // Test kullanıcısı oluştur (eğer yoksa)
        $testUser = User::firstOrCreate(
            ['email' => 'test@kadinatlasi.com'],
            [
                'name' => 'Test Kullanıcı',
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]
        );

        // Örnek forum konuları oluştur
        $topics = [
            [
                'forum_category_id' => 1, // Anneler Kulübü
                'title' => 'Bebeğim 6 aylık oldu, ek gıdaya nasıl başlamalıyım?',
                'content' => 'Merhaba anneler, bebeğim 6 aylık oldu ve doktor ek gıdaya başlamamızı söyledi. Hangi yiyeceklerle başlamalıyım? Deneyimlerinizi paylaşır mısınız?',
                'is_pinned' => false,
                'is_featured' => true,
                'views_count' => 1247,
                'replies_count' => 23,
                'likes_count' => 18,
                'tags' => ['bebek', 'beslenme', 'ek-gıda']
            ],
            [
                'forum_category_id' => 2, // Kariyer
                'title' => 'İş görüşmesinde hamile olduğumu söylemeli miyim?',
                'content' => '3 aylık hamileyim ve yeni bir işe başvurdum. İş görüşmesinde hamile olduğumu belirtmeli miyim? Hukuki durumu bilen var mı?',
                'is_pinned' => true,
                'is_featured' => true,
                'views_count' => 2156,
                'replies_count' => 31,
                'likes_count' => 42,
                'tags' => ['hamilelik', 'iş-görüşmesi', 'hukuk']
            ],
            [
                'forum_category_id' => 4, // Güzellik
                'title' => 'Cilt bakım rutinimi ne değiştirmeliyim?',
                'content' => 'Yaşım 35, karma cilt tipim var. Son zamanlarda cildimde değişiklikler fark ettim. Hangi ürünleri kullanmalıyım?',
                'is_pinned' => false,
                'is_featured' => false,
                'views_count' => 892,
                'replies_count' => 15,
                'likes_count' => 12,
                'tags' => ['cilt-bakımı', '35-yaş', 'karma-cilt']
            ],
            [
                'forum_category_id' => 3, // Sağlık
                'title' => 'Doğum sonrası kilo verme deneyimleriniz',
                'content' => 'Doğum yaptım, 15 kilo aldım. Nasıl sağlıklı şekilde kilo verebilirim? Emzirirken diyet yapılır mı?',
                'is_pinned' => false,
                'is_featured' => true,
                'views_count' => 1834,
                'replies_count' => 28,
                'likes_count' => 35,
                'tags' => ['doğum-sonrası', 'kilo-verme', 'emzirme']
            ],
            [
                'forum_category_id' => 5, // İlişkiler
                'title' => 'Evlilik terapisi deneyimi olan var mı?',
                'content' => 'Eşimle iletişim problemleri yaşıyoruz. Evlilik terapisi almayı düşünüyoruz. Deneyimi olan var mı?',
                'is_pinned' => false,
                'is_featured' => false,
                'views_count' => 967,
                'replies_count' => 19,
                'likes_count' => 24,
                'tags' => ['evlilik', 'terapi', 'iletişim']
            ]
        ];

        foreach ($topics as $topicData) {
            $topic = ForumTopic::create([
                'user_id' => $testUser->id,
                'forum_category_id' => $topicData['forum_category_id'],
                'title' => $topicData['title'],
                'slug' => \Illuminate\Support\Str::slug($topicData['title']),
                'content' => $topicData['content'],
                'is_pinned' => $topicData['is_pinned'],
                'is_featured' => $topicData['is_featured'],
                'views_count' => $topicData['views_count'],
                'replies_count' => $topicData['replies_count'],
                'likes_count' => $topicData['likes_count'],
                'tags' => $topicData['tags'],
                'last_post_at' => now(),
                'last_post_user_id' => $testUser->id
            ]);

            // Her konu için birkaç örnek mesaj oluştur
            for ($i = 1; $i <= min(3, $topicData['replies_count']); $i++) {
                ForumPost::create([
                    'forum_topic_id' => $topic->id,
                    'user_id' => $testUser->id,
                    'content' => "Bu konuyla ilgili örnek yanıt #{$i}. Çok faydalı bir konu açmışsınız, teşekkürler!",
                    'is_expert_answer' => $i === 1, // İlk yanıt uzman yanıtı olsun
                    'likes_count' => rand(1, 10)
                ]);
            }
        }
    }
}