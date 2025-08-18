<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BeautyArticle;
use App\Models\BeautyProduct;
use App\Models\BeautyTip;
use App\Models\BeautyVideo;

class BeautyContentSeeder extends Seeder
{
    public function run()
    {
        // Beauty Articles
        $articles = [
            [
                'title' => '2024 Yılının En Trend Makyaj Teknikleri',
                'excerpt' => 'Bu yıl makyaj dünyasında doğal görünüm ön planda.',
                'content' => 'Bu yıl makyaj dünyasında doğal görünüm ön planda. Dewy skin, bold eyeliner ve nude dudaklar en popüler trendler arasında. Minimalist yaklaşım benimsenirken, cilt sağlığı ön plana çıkıyor. Doğal makyaj teknikleri ile günlük hayatta pratik ve şık görünümler elde edebilirsiniz.',
                'category_id' => 1,
                'featured_image' => '/images/beauty/makeup-trends-2024.jpg',
                'read_time' => 5,
                'is_active' => true,
                'tags' => json_encode(['makyaj', 'trend', '2024'])
            ],
            [
                'title' => 'Kış Aylarında Cilt Bakımı: Uzman Önerileri',
                'excerpt' => 'Kış aylarında cildinizi korumak için özel bakım rutinleri.',
                'content' => 'Kış aylarında cildinizi korumak için özel bakım rutinleri gerekiyor. Soğuk hava ve düşük nem oranı cildi kurutur. Yoğun nemlendirici kullanımı, gentle temizleyiciler ve SPF koruması şarttır. Cildinizi nemli tutmak için günde en az 2 litre su için ve nemlendiricileri düzenli kullanın.',
                'category_id' => 1,
                'featured_image' => '/images/beauty/winter-skincare.jpg',
                'read_time' => 7,
                'is_active' => true,
                'tags' => json_encode(['cilt bakımı', 'kış', 'nemlendirici'])
            ],
            [
                'title' => 'Evde Yapabileceğiniz Doğal Saç Maskeleri',
                'excerpt' => 'Mutfağınızda bulunan malzemelerle etkili saç maskeleri.',
                'content' => 'Mutfağınızda bulunan malzemelerle hazırlayabileceğiniz etkili saç maskeleri. Avokado, yumurta ve zeytinyağı ile besleyici maskeler yapabilirsiniz. Haftada 2 kez uygulayarak saçlarınızın sağlığını artırabilir, parlaklık ve yumuşaklık kazandırabilirsiniz.',
                'category_id' => 1,
                'featured_image' => '/images/beauty/hair-masks.jpg',
                'read_time' => 4,
                'is_active' => true,
                'tags' => json_encode(['saç bakımı', 'doğal', 'maske'])
            ],
            [
                'title' => 'Parfüm Seçimi: Kişiliğinize Uygun Koku Bulma Rehberi',
                'excerpt' => 'Kişiliğinize uygun parfüm seçimi için rehber.',
                'content' => 'Parfüm seçimi kişisel bir tercih olsa da, bazı ipuçları size en uygun kokuyu bulmanızda yardımcı olabilir. Koku aileleri, mevsimsel tercihler ve kişilik analizi önemlidir. Çiçeksi, odunsu, taze veya oriental koku ailelerinden hangisinin size uygun olduğunu keşfedin.',
                'category_id' => 1,
                'featured_image' => '/images/beauty/perfume-guide.jpg',
                'read_time' => 6,
                'is_active' => true,
                'tags' => json_encode(['parfüm', 'koku', 'seçim'])
            ],
            [
                'title' => 'Minimalist Makyaj: Az Ürünle Maksimum Etki',
                'excerpt' => 'Az ürünle maksimum etki için minimalist makyaj.',
                'content' => 'Günlük hayatta pratik ve doğal görünüm için minimalist makyaj teknikleri. Sadece 5 ürünle mükemmel görünüm elde edebilirsiniz. BB krem, maskara, ruj, kaş kalemi ve allık ile doğal ve taze bir görünüm yaratabilirsiniz. Zaman kazandıran bu teknikler her gün kullanılabilir.',
                'category_id' => 1,
                'featured_image' => '/images/beauty/minimalist-makeup.jpg',
                'read_time' => 3,
                'is_active' => true,
                'tags' => json_encode(['minimalist', 'makyaj', 'pratik'])
            ]
        ];

        foreach ($articles as $article) {
            BeautyArticle::create($article);
        }

        // Beauty Tips
        $tips = [
            [
                'title' => 'Göz Altı Morlukları İçin Hızlı Çözüm',
                'content' => 'Soğuk kaşık uygulaması ve kafein içeren göz kremi ile göz altı morlukları 10 dakikada azalır. Kaşığı buzdolabında bekletin ve göz altına nazikçe uygulayın.',
                'category' => 'skincare',
                'difficulty_level' => 'beginner',
                'time_required' => '10 dakika',
                'featured' => true,
                'is_active' => true
            ],
            [
                'title' => 'Ruj Kalıcılığını Artırma Yöntemi',
                'content' => 'Rujdan önce dudak kalemini tüm dudağa sürün, sonra ruj uygulayın. Mendil ile bastırıp tekrar ruj sürün. Bu yöntem rujun 8 saate kadar kalmasını sağlar.',
                'category' => 'makeup',
                'difficulty_level' => 'beginner',
                'time_required' => '5 dakika',
                'featured' => true,
                'is_active' => true
            ],
            [
                'title' => 'Saç Hacmi Artırma Tekniği',
                'content' => 'Saçınızı ters çevirin ve köklere kuru şampuan sıkın. Masaj yaparak dağıtın ve normal pozisyona getirin. Anında hacim kazanırsınız.',
                'category' => 'haircare',
                'difficulty_level' => 'beginner',
                'time_required' => '3 dakika',
                'featured' => false,
                'is_active' => true
            ],
            [
                'title' => 'Doğal Highlighter Yapımı',
                'content' => 'Vazelin ile göz farınızı karıştırarak doğal highlighter elde edebilirsiniz. Elmacık kemiklerine uygulayın. Doğal parlaklık verir.',
                'category' => 'makeup',
                'difficulty_level' => 'intermediate',
                'time_required' => '15 dakika',
                'featured' => false,
                'is_active' => true
            ],
            [
                'title' => 'Tırnak Güçlendirme Formülü',
                'content' => 'Zeytinyağı ve limon suyu karışımını tırnaklarınıza masaj yaparak uygulayın. Haftada 3 kez tekrarlayın. Tırnaklar güçlenir ve parlar.',
                'category' => 'nails',
                'difficulty_level' => 'beginner',
                'time_required' => '20 dakika',
                'featured' => true,
                'is_active' => true
            ],
            [
                'title' => 'Göz Makyajı Temizleme İpucu',
                'content' => 'Waterproof makyaj için çift fazlı temizleyici kullanın. Pamuk pedde 30 saniye bekletin, sonra nazikçe silin. Göz çevresini incitmez.',
                'category' => 'makeup',
                'difficulty_level' => 'beginner',
                'time_required' => '5 dakika',
                'featured' => false,
                'is_active' => true
            ],
            [
                'title' => 'Cilt Tonunu Eşitleme Maskesi',
                'content' => 'Yoğurt, bal ve zerdeçal karışımı ile hazırlanan maske cilt tonunu eşitler. 20 dakika bekletin ve ılık suyla durulayın.',
                'category' => 'skincare',
                'difficulty_level' => 'intermediate',
                'time_required' => '30 dakika',
                'featured' => true,
                'is_active' => true
            ],
            [
                'title' => 'Parfüm Kalıcılığını Artırma',
                'content' => 'Parfümü nabız noktalarına sıkmadan önce o bölgelere vazelin sürün. Koku daha uzun süre kalacaktır. 12 saate kadar etkili.',
                'category' => 'lifestyle',
                'difficulty_level' => 'beginner',
                'time_required' => '2 dakika',
                'featured' => false,
                'is_active' => true
            ]
        ];

        foreach ($tips as $tip) {
            BeautyTip::create($tip);
        }

        // Beauty Products
        $products = [
            [
                'name' => 'Hyaluronic Acid Serum',
                'brand' => 'The Ordinary',
                'category_id' => 1,
                'price' => 45.00,
                'description' => 'Cildi nemlendirir ve dolgunluk verir. Tüm cilt tiplerine uygun. Hyaluronic acid ile cildin nem seviyesini artırır.',
                'ingredients' => 'Hyaluronic Acid, Aqua, Sodium Hyaluronate',
                'rating' => 4.5,
                'pros' => json_encode(['Yoğun nemlendirme', 'Tüm cilt tiplerine uygun', 'Uygun fiyat']),
                'cons' => json_encode(['Yapışkan his', 'Tek başına yeterli değil']),
                'featured_image' => '/images/products/hyaluronic-serum.jpg',
                'is_active' => true
            ],
            [
                'name' => 'Matte Liquid Lipstick',
                'brand' => 'Maybelline',
                'category_id' => 1,
                'price' => 35.00,
                'description' => 'Uzun süre kalıcı mat ruj. 16 saate kadar solmaz. Yoğun pigmentasyon ile tek sürüşte tam kapatıcılık.',
                'ingredients' => 'Dimethicone, Cyclopentasiloxane, Trimethylsiloxysilicate',
                'rating' => 4.2,
                'pros' => json_encode(['Uzun kalıcılık', 'Yoğun renk', 'Mat finish']),
                'cons' => json_encode(['Kurutucu etki', 'Çıkarması zor']),
                'featured_image' => '/images/products/matte-lipstick.jpg',
                'is_active' => true
            ],
            [
                'name' => 'Vitamin C Brightening Cream',
                'brand' => 'Olay',
                'category_id' => 1,
                'price' => 85.00,
                'description' => 'Vitamin C ile cildi aydınlatır ve leke karşıtı etki sağlar. Düzenli kullanımda cilt tonu eşitlenir.',
                'ingredients' => 'Vitamin C, Niacinamide, Hyaluronic Acid',
                'rating' => 4.3,
                'pros' => json_encode(['Leke giderici', 'Aydınlatıcı', 'Anti-aging']),
                'cons' => json_encode(['Pahalı', 'Hassas ciltlerde tahriş']),
                'featured_image' => '/images/products/vitamin-c-cream.jpg',
                'is_active' => true
            ]
        ];

        foreach ($products as $product) {
            BeautyProduct::create($product);
        }

        // Beauty Videos
        $videos = [
            [
                'title' => '10 Dakikada Günlük Makyaj',
                'description' => 'Hızlı ve pratik günlük makyaj rutini. Sabah telaşında bile mükemmel görünüm.',
                'video_url' => 'https://youtube.com/watch?v=example1',
                'thumbnail' => '/images/videos/daily-makeup.jpg',
                'duration' => '10:30',
                'views_count' => 15000,
                'category_id' => 1,
                'featured' => true,
                'is_active' => true
            ],
            [
                'title' => 'Cilt Bakım Rutini: Sabah ve Akşam',
                'description' => 'Uzmanlardan cilt bakım rutini önerileri. Doğru ürün sıralaması ve uygulama teknikleri.',
                'video_url' => 'https://youtube.com/watch?v=example2',
                'thumbnail' => '/images/videos/skincare-routine.jpg',
                'duration' => '15:45',
                'views_count' => 22000,
                'category_id' => 1,
                'featured' => true,
                'is_active' => true
            ]
        ];

        foreach ($videos as $video) {
            BeautyVideo::create($video);
        }
    }
}