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
            ],
            [
                'title' => 'Girişimcilikte İlk Adımlar: İş Fikrinden Başarıya',
                'excerpt' => 'Girişimcilik yolculuğuna başlamak isteyenler için pratik rehber ve temel adımlar.',
                'content' => '<p>Girişimcilik, finansal özgürlük ve kendi işinizin patronu olma hayalini gerçekleştirmenin en etkili yoludur. İşte başarılı bir girişimcilik yolculuğu için atmanız gereken temel adımlar:</p>

<h2>1. İş Fikri Geliştirme</h2>
<p>Her başarılı iş, güçlü bir fikirle başlar:</p>
<ul>
<li>Pazar araştırması yapın ve ihtiyaçları analiz edin</li>
<li>Kendi yeteneklerinizi ve tutkularınızı değerlendirin</li>
<li>Rekabet analizi yapın</li>
<li>Fikrinizi potansiyel müşterilerle test edin</li>
</ul>

<h2>2. İş Planı Hazırlama</h2>
<p>Detaylı bir iş planı, başarınızın yol haritasıdır. İş planınızda şunlar yer almalı:</p>
<ul>
<li>Executive summary</li>
<li>Pazar analizi</li>
<li>Pazarlama stratejisi</li>
<li>Finansal projeksiyonlar</li>
<li>Risk analizi</li>
</ul>

<h2>3. Finansman Seçenekleri</h2>
<p>İş kurma sürecinde finansman kritik öneme sahiptir:</p>
<ul>
<li>Kişisel tasarruflar</li>
<li>Aile ve arkadaş desteği</li>
<li>Banka kredileri</li>
<li>KOSGEB destekleri</li>
<li>Melek yatırımcılar</li>
<li>Girişim sermayesi fonları</li>
</ul>

<h2>4. Yasal Süreçler</h2>
<p>İşinizi resmi olarak kurmak için gerekli adımlar:</p>
<ul>
<li>Şirket türü seçimi (Ltd, A.Ş., Şahıs şirketi)</li>
<li>Ticaret sicil kaydı</li>
<li>Vergi levhası alımı</li>
<li>SGK işlemleri</li>
<li>Gerekli lisans ve izinler</li>
</ul>',
                'category_id' => $categories->where('slug', 'kariyer-girisimcilik')->first()->id,
                'tags' => ['girişimcilik', 'iş kurma', 'startup', 'kariyer'],
            ],
            [
                'title' => 'CV Hazırlama Teknikleri: İşverenin Gözünden Kaçmayın',
                'excerpt' => 'Profesyonel CV hazırlama teknikleri ve işverenlerin dikkat ettiği noktalar.',
                'content' => '<p>Doğru hazırlanmış bir CV, iş arama sürecinizin en kritik bileşenidir. İşverenlerin dikkatini çeken ve mülakata çağırma şansınızı artıran CV hazırlama teknikleri:</p>

<h2>CV\'nin Temel Bölümleri</h2>

<h3>1. Kişisel Bilgiler</h3>
<ul>
<li>Ad, soyad</li>
<li>İletişim bilgileri (telefon, e-posta, LinkedIn profili)</li>
<li>Profesyonel fotoğraf (opsiyonel)</li>
</ul>

<h3>2. Profesyonel Özet</h3>
<p>2-3 cümlelik özet bölümünde kendinizi ve hedeflerinizi net bir şekilde ifade edin.</p>

<h3>3. İş Deneyimi</h3>
<ul>
<li>En son işinizden başlayarak ters kronolojik sırada</li>
<li>Şirket adı, pozisyon, çalışma tarihleri</li>
<li>Başarılarınızı sayısal verilerle destekleyin</li>
<li>İş tanımından çok başarılarınıza odaklanın</li>
</ul>

<h3>4. Eğitim Bilgileri</h3>
<ul>
<li>Üniversite, bölüm, mezuniyet yılı</li>
<li>GPA (3.0 üzerindeyse)</li>
<li>İlgili sertifikalar ve kurslar</li>
</ul>

<h2>CV Hazırlama İpuçları</h2>
<ul>
<li>Maksimum 2 sayfa olmalı</li>
<li>Temiz ve okunabilir font kullanın</li>
<li>Anahtar kelimeleri stratejik olarak yerleştirin</li>
<li>Her pozisyon için CV\'yi özelleştirin</li>
<li>Yazım hatalarına dikkat edin</li>
<li>PDF formatında gönderin</li>
</ul>

<h2>Kaçınılması Gereken Hatalar</h2>
<ul>
<li>Çok uzun veya çok kısa CV</li>
<li>İlgisiz kişisel bilgiler</li>
<li>Standart CV şablonları</li>
<li>Açıklanamayan boşluklar</li>
<li>Abartılı ifadeler</li>
</ul>',
                'category_id' => $categories->where('slug', 'kariyer-girisimcilik')->first()->id,
                'tags' => ['cv', 'iş arama', 'kariyer', 'mülakat'],
            ],
            [
                'title' => 'Kadın Girişimciler İçin Networking Stratejileri',
                'excerpt' => 'Iş dünyasında güçlü ağlar kurmanın ve sürdürmenin etkili yolları.',
                'content' => '<p>Networking, özellikle kadın girişimciler için kritik öneme sahip bir beceridir. Doğru ağ ilişkileri kurmak, iş fırsatlarını artırır ve kariyerinizi hızlandırır.</p>

<h2>Networking\'in Önemi</h2>
<p>İş dünyasında başarı sadece ne bildiğinizle değil, kimi tanıdığınızla da yakından ilgilidir:</p>
<ul>
<li>Yeni iş fırsatları</li>
<li>Mentorluk ilişkileri</li>
<li>İş ortaklıkları</li>
<li>Sektörel bilgi paylaşımı</li>
<li>Marka bilinirliği</li>
</ul>

<h2>Etkili Networking Stratejileri</h2>

<h3>1. Hazırlık Aşaması</h3>
<ul>
<li>Kısa ve etkileyici bir tanıtım hazırlayın</li>
<li>Profesyonel kartvizit yaptırın</li>
<li>LinkedIn profilinizi güncelleyin</li>
<li>Hedef kitleyi belirleyin</li>
</ul>

<h3>2. Etkinlik Seçimi</h3>
<ul>
<li>Sektörel konferanslar</li>
<li>Girişimcilik seminerleri</li>
<li>Kadın girişimci toplulukları</li>
<li>Coworking space etkinlikleri</li>
<li>Online networking platformları</li>
</ul>

<h3>3. İletişim Teknikleri</h3>
<ul>
<li>Aktif dinleyin</li>
<li>Açık uçlu sorular sorun</li>
<li>Samimi ve doğal olun</li>
<li>Değer yaratmaya odaklanın</li>
<li>Takip sürecini ihmal etmeyin</li>
</ul>

<h2>Dijital Networking</h2>
<p>Çağımızda dijital platformlar networking için büyük fırsatlar sunuyor:</p>
<ul>
<li>LinkedIn gruplarına katılın</li>
<li>Twitter\'da sektör liderlerini takip edin</li>
<li>Instagram\'da hikayenizi paylaşın</li>
<li>Podcast\'lere konuk olun</li>
<li>Blog yazın ve uzman olarak görünün</li>
</ul>

<h2>Kadın Girişimci Ağları</h2>
<ul>
<li>KAGİDER (Kadın Girişimcileri Derneği)</li>
<li>Women in Business</li>
<li>Endeavor Kadın Girişimci Ağı</li>
<li>TUSIAD Kadın Girişimciler Kurulu</li>
<li>Yerel kadın girişimci toplulukları</li>
</ul>',
                'category_id' => $categories->where('slug', 'kariyer-girisimcilik')->first()->id,
                'tags' => ['networking', 'kadın girişimci', 'iş ağları', 'kariyer'],
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