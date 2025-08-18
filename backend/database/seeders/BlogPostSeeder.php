<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@kadinatlasi.com')->first();
        $categories = Category::all();

        $posts = [
            [
                'title' => 'Sağlıklı Beslenme İpuçları',
                'excerpt' => 'Günlük yaşamda sağlıklı beslenme alışkanlıkları kazanmak için pratik öneriler.',
                'content' => '<p>Sağlıklı beslenme, yaşam kalitenizi artıran en önemli faktörlerden biridir. İşte günlük hayatınızda uygulayabileceğiniz pratik öneriler:</p>

<h2>Temel Prensipler</h2>
<ul>
<li>Günde en az 5 porsiyon sebze ve meyve tüketin</li>
<li>Bol su için (günde 2-3 litre)</li>
<li>Tam tahıllı ürünleri tercih edin</li>
<li>Protein kaynaklarını çeşitlendirin</li>
</ul>

<h2>Öğün Planlaması</h2>
<p>Düzenli öğün saatleri metabolizmanızı hızlandırır ve kan şekerinizi dengede tutar. Sabah kahvaltısını asla atlamayın ve ara öğünlerde sağlıklı atıştırmalıklar tercih edin.</p>

<h2>Su Tüketimi</h2>
<p>Vücudunuzun %70\'i sudan oluşur. Yeterli su tüketimi sindirim sistemini destekler, cildi nemlendirir ve toksinlerin atılmasına yardımcı olur.</p>',
                'category_id' => $categories->where('slug', 'kadin-sagligi')->first()->id,
                'tags' => ['beslenme', 'sağlık', 'yaşam'],
            ],
            [
                'title' => 'Hamilelikte Dikkat Edilmesi Gerekenler',
                'excerpt' => 'Hamilelik döneminde anne ve bebek sağlığı için önemli noktalar.',
                'content' => '<p>Hamilelik, kadın yaşamının en özel dönemlerinden biridir. Bu süreçte hem anne hem de bebek sağlığı için dikkat edilmesi gereken önemli noktalar vardır.</p>

<h2>Beslenme</h2>
<p>Hamilelik döneminde beslenme alışkanlıklarınız bebeğinizin gelişimini doğrudan etkiler:</p>
<ul>
<li>Folik asit alımına dikkat edin</li>
<li>Kalsiyum ve demir açısından zengin besinler tüketin</li>
<li>Çiğ et, balık ve yumurta tüketiminden kaçının</li>
<li>Kafein alımını sınırlayın</li>
</ul>

<h2>Egzersiz</h2>
<p>Doktor kontrolünde yapılan hafif egzersizler hamilelik sürecini kolaylaştırır. Yürüyüş, yüzme ve hamile yogası güvenli seçeneklerdir.</p>

<h2>Düzenli Kontroller</h2>
<p>Düzenli doktor kontrollerinizi aksatmayın. Ultrason takipleri ve tahliller bebeğinizin sağlıklı gelişimi için kritik önem taşır.</p>',
                'category_id' => $categories->where('slug', 'gebelik-annelik')->first()->id,
                'tags' => ['hamilelik', 'anne', 'bebek', 'sağlık'],
            ],
            [
                'title' => 'Cilt Bakımında Doğal Yöntemler',
                'excerpt' => 'Evde hazırlayabileceğiniz doğal cilt bakım maskeleri ve önerileri.',
                'content' => '<p>Doğal cilt bakım yöntemleri, kimyasal içerikli ürünlere alternatif olarak cildinizi besler ve korur.</p>

<h2>Evde Hazırlayabileceğiniz Maskeler</h2>

<h3>Nemlendirici Bal Maskesi</h3>
<p>Malzemeler: 2 yemek kaşığı bal, 1 yemek kaşığı yoğurt</p>
<p>Karıştırın ve temiz cilde uygulayın. 15 dakika bekletin ve ılık suyla durulayın.</p>

<h3>Arındırıcı Yulaf Maskesi</h3>
<p>Malzemeler: 3 yemek kaşığı yulaf ezmesi, 2 yemek kaşığı süt</p>
<p>Karıştırarak kıvamlı bir karışım elde edin. Dairesel hareketlerle uygulayın ve 10 dakika bekletin.</p>

<h2>Günlük Bakım İpuçları</h2>
<ul>
<li>Cildinizi günde iki kez temizleyin</li>
<li>Güneş kremi kullanmayı ihmal etmeyin</li>
<li>Bol su için</li>
<li>Yeterli uyku alın</li>
</ul>',
                'category_id' => $categories->where('slug', 'guzellik-moda')->first()->id,
                'tags' => ['cilt bakımı', 'doğal', 'güzellik', 'maske'],
            ]
        ];

        foreach ($posts as $postData) {
            BlogPost::create([
                'user_id' => $admin->id,
                'category_id' => $postData['category_id'],
                'title' => $postData['title'],
                'slug' => Str::slug($postData['title']),
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'tags' => $postData['tags'],
                'status' => 'published',
                'views_count' => rand(50, 500),
                'likes_count' => rand(5, 50),
                'published_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}