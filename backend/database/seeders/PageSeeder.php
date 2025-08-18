<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Hakkımızda',
                'slug' => 'hakkimizda',
                'content' => '<h2>KadınAtlası.com Hakkında</h2><p>KadınAtlası.com, kadınların günlük hayatını kolaylaştıracak, bilgi alabilecekleri, hesaplamalar yapabilecekleri, topluluk oluşturabilecekleri ve kadın sağlığı, yaşam, kariyer gibi tüm ilgi alanlarına hitap eden kapsayıcı bir dijital platformdur.</p><h3>Misyonumuz</h3><p>Kadınların hayatlarının her alanında onlara destek olmak, güvenilir bilgi kaynağı olmak ve güçlü bir topluluk oluşturmak.</p><h3>Vizyonumuz</h3><p>Türkiye\'nin en kapsamlı kadın platformu olmak ve kadınların hayatlarını kolaylaştırmak.</p>',
                'excerpt' => 'KadınAtlası.com hakkında bilgi edinin.',
                'meta_title' => 'Hakkımızda - KadınAtlası.com',
                'meta_description' => 'KadınAtlası.com, kadınların günlük hayatını kolaylaştıran kapsamlı dijital platform. Misyonumuz ve vizyonumuz hakkında bilgi edinin.',
                'sort_order' => 1
            ],
            [
                'title' => 'İletişim',
                'slug' => 'iletisim',
                'content' => '<h2>İletişim</h2><p>Bizimle iletişime geçmek için aşağıdaki bilgileri kullanabilirsiniz:</p><h3>E-posta</h3><p>Genel sorularınız için: <strong>info@kadinatlasi.com</strong></p><p>Destek için: <strong>destek@kadinatlasi.com</strong></p><h3>Sosyal Medya</h3><p>Bizi sosyal medya hesaplarımızdan takip edebilirsiniz.</p><h3>İş Birliği</h3><p>İş birliği teklifleriniz için: <strong>isbirligi@kadinatlasi.com</strong></p>',
                'excerpt' => 'Bizimle iletişime geçin.',
                'meta_title' => 'İletişim - KadınAtlası.com',
                'meta_description' => 'KadınAtlası.com ile iletişime geçin. E-posta adresleri ve sosyal medya hesaplarımız.',
                'sort_order' => 2
            ],
            [
                'title' => 'Gizlilik Politikası',
                'slug' => 'gizlilik-politikasi',
                'content' => '<h2>Gizlilik Politikası</h2><p>Bu gizlilik politikası, KadınAtlası.com\'un kişisel verilerinizi nasıl topladığını, kullandığını ve koruduğunu açıklar.</p><h3>Toplanan Bilgiler</h3><p>Sitemizi kullanırken aşağıdaki bilgileri toplayabiliriz:</p><ul><li>Ad, soyad ve e-posta adresi</li><li>Profil bilgileri</li><li>Site kullanım verileri</li></ul><h3>Bilgilerin Kullanımı</h3><p>Topladığımız bilgileri şu amaçlarla kullanırız:</p><ul><li>Hizmet kalitesini artırmak</li><li>Kişiselleştirilmiş içerik sunmak</li><li>İletişim kurmak</li></ul>',
                'excerpt' => 'Gizlilik politikamızı okuyun.',
                'meta_title' => 'Gizlilik Politikası - KadınAtlası.com',
                'meta_description' => 'KadınAtlası.com gizlilik politikası. Kişisel verilerinizin nasıl korunduğunu öğrenin.',
                'sort_order' => 3
            ],
            [
                'title' => 'Kullanım Şartları',
                'slug' => 'kullanim-sartlari',
                'content' => '<h2>Kullanım Şartları</h2><p>KadınAtlası.com\'u kullanarak aşağıdaki şartları kabul etmiş olursunuz:</p><h3>Genel Kurallar</h3><ul><li>Siteyi yasal amaçlar için kullanacaksınız</li><li>Başkalarının haklarına saygı göstereceksiniz</li><li>Zararlı içerik paylaşmayacaksınız</li></ul><h3>Hesap Sorumluluğu</h3><p>Hesabınızın güvenliğinden siz sorumlusunuz. Şifrenizi güvenli tutun ve başkalarıyla paylaşmayın.</p><h3>İçerik Politikası</h3><p>Paylaştığınız içerikler toplum kurallarına uygun olmalıdır.</p>',
                'excerpt' => 'Site kullanım şartlarımızı okuyun.',
                'meta_title' => 'Kullanım Şartları - KadınAtlası.com',
                'meta_description' => 'KadınAtlası.com kullanım şartları. Siteyi kullanırken uymanız gereken kurallar.',
                'sort_order' => 4
            ]
        ];

        foreach ($pages as $pageData) {
            Page::create($pageData);
        }
    }
}