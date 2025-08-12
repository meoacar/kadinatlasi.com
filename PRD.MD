# Product Requirements Document (PRD)

## Proje Adı: KadınAtlası.com

## Vizyon:

KadınAtlası.com; kadınların günlük hayatını kolaylaştıracak, bilgi alabilecekleri, hesaplamalar yapabilecekleri, topluluk oluşturabilecekleri ve kadın sağlığı, yaşam, kariyer gibi tüm ilgi alanlarına hitap eden kapsayıcı bir dijital platformdur. Kadınlara özel içerikler, araçlar ve etkileşimli modüller ile tam kapsamlı bir yaşam rehberi sunar.

## Amaç:

KadınAtlasi.com’un amacı, kadın kullanıcıların tüm ihtiyaçlarını karşılayacak tek bir dijital platform oluşturmaktır. Bilgilendirici içeriklerden hesaplama araçlarına, topluluk etkileşiminden kişiselleştirilmiş profillere kadar kapsamlı bir kullanıcı deneyimi sunmak.

## Hedef Kitle:

- 18-45 yaş arası kadınlar
- Gebelik ve annelik sürecinde olan kadınlar
- Kadın sağlığı, güzellik, moda, kariyer, kişisel gelişimle ilgilenen kullanıcılar
- Sağlıklı yaşam, diyet ve fitness ile ilgilenen kadınlar
- Sosyal topluluklar kurmak isteyen kadınlar

## Temel Özellikler:

### ✅ 1. **Blog ve İçerik Platformu** (TAMAMLANDI)

- ✅ 8 Ana Kategori Sistemi:
  - ✅ Kadın Sağlığı
  - ✅ Gebelik & Annelik
  - ✅ Güzellik & Moda
  - ✅ Diyet & Fitness
  - ✅ Kariyer & Girişimcilik
  - ✅ Psikoloji & Kişisel Gelişim
  - ✅ Astroloji
  - ✅ İlişkiler & Aile
- ✅ Blog post modeli ve API'leri (CRUD işlemleri)
- ✅ Blog sayfası (/blog) - kategori filtreleme, arama
- ✅ Blog post controller (filtreleme, arama, pagination)
- ✅ Blog detay sayfası (/blog/:id) - yazı detayı, yazar profili, görüntüleme sayısı
- ✅ Yorum sistemi - yorum yapma, yanıtlama, onay sistemi
- ✅ İçerik editörü ve yönetim paneli (Laravel Filament)
- ✅ Kullanıcıların blog yazısı yazabileceği bölüm (API hazır)

### ✅ 2. **Hesaplama Araçları** (Backend API + Frontend Tamamlandı)

- ✅ **VKİ Hesaplayıcı**: Tam fonksiyonel (/hesaplama/vki)
- ✅ **Regl Döngüsü Takvimi**: Ovulasyon ve verimli dönem hesaplama (/hesaplama/regl-takvimi)
- ✅ **Gebelik Haftası Hesaplayıcı**: Tam fonksiyonel (/hesaplama/gebelik) - Gebelik haftası, doğum tarihi, bebek gelişimi
- ✅ **Kalori Hesaplama Aracı**: Tam fonksiyonel (/hesaplama/kalori) - BMR, günlük kalori, makro besinler
- ✅ **Günlük Su İhtiyacı Hesaplayıcı**: Tam fonksiyonel (/hesaplama/su-ihtiyaci) - Günlük su ihtiyacı, içme programı
- ✅ İdeal Kilo Hesaplama (VKİ'ye entegre edildi)
- ✅ Doğum Tarihi Tahmin Aracı (gebelik hesaplayıcıya entegre edildi)
- ✅ Kişisel Finans ve Bütçe Planlayıcı

### ✅ 3. **Profil ve Kişiselleştirme** (Temel Özellikler Hazır)

- ✅ Detaylı profil bilgileri: Doğum tarihi, burç, sağlık bilgileri
- ✅ Otomatik burç hesaplama sistemi
- ✅ User profile modeli (interests, health_info, goals)
- ✅ Puan ve seviye sistemi altyapısı
- ✅ Kişisel takvim (regl takvimi, önemli tarihler) - API hazır
- ✅ Hedef takibi (Kilo, su içme alışkanlıkları, egzersiz hedefleri) - Backend hazır
- ✅ Rozet ve başarı sistemi UI'ı - Badge sistemi tamamlandı
- ✅ Görev ve Başarım Modülü - Backend hazır

### ✅ 4. **Topluluk ve Forum** (Temel Özellikler Tamamlandı)

- ✅ Kategori bazlı forumlar (8 ana kategori ile entegre)
- ✅ Konu oluşturma ve yönetimi (başlık, içerik, kategori)
- ✅ Yanıt sistemi (mesaj gönderme, düzenleme, silme)
- ✅ Konu detay sayfası (görüntüleme, yanıtlama)
- ✅ Arama ve filtreleme (kategori, anahtar kelime)
- ✅ Kullanıcı yetkilendirmesi (giriş yapmış kullanıcılar)
- ✅ Konu istatistikleri (görüntüleme, yanıt sayısı)
- ✅ Moderasyon araçları (sabitleme, kilitleme) - API hazır
- ✅ Özel kadın grupları (Hamileler, Diyetisyenler vb.) - ForumGroup sistemi
- ✅ Anketler & Oylamalar - ForumPoll sistemi

### ✅ 5. **Gebelik & Anne Modülü** (TAMAMLANDI)

- ✅ Haftalık gebelik rehberi (40 hafta detaylı bilgi)
- ✅ Anne-bebek gelişimi takibi (PregnancyTracker sistemi)
- ✅ Bebek İsimleri ve Anlamları veritabanı (30+ isim)
- ✅ Gebelik takip sayfası (/gebelik-takibi)
- ✅ Bebek isimleri sayfası (/bebek-isimleri)
- ✅ API endpoints (pregnancy/weeks, baby-names)
- ✅ Doğum sonrası rehber (Lohusalık, emzirme vb.)
- ✅ Bebek Bakımı ipuçları

### 6. **Etkinlik ve Atölye Takvimi**

- Online & offline kadınlara özel etkinlik duyuruları
- Atölyeler, webinarlar, seminerler

### 7. **Güzellik ve Moda Modülü**

- ✅Stil önerileri
- ✅Güzellik ürünleri incelemeleri
- ✅Makyaj ve bakım videoları
- ✅Kullanıcıların kıyafet kombinleri paylaşabileceği alan

### ✅ 8. **Psikolojik Destek ve İyi Yaşam** (TAMAMLANDI)

- ✅ Günlük ruh hali takibi (MoodTracker sistemi)
- ✅ Motivasyon sözleri (12 kategori, günlük söz)
- ✅ Psikolojik testler (Stres, öz güven testleri)
- ✅ Psikoloji sayfası (/psikoloji) - 3 sekme
- ✅ API endpoints (mood-tracker, quotes, tests)
- ✅ Kullanıcı ruh hali geçmişi ve istatistikleri
- ✅ Online destek kaynakları

### ✅ 9. **Astroloji Modülü** (TAMAMLANDI)

- ✅ Günlük, haftalık, aylık burç yorumları (AstrologyService ile)
- ✅ Aşk, kariyer, sağlık kategorilerinde astroloji içerikleri
- ✅ Kullanıcı profiline göre kişiselleştirilmiş astrolojik analizler (doğum tarihine göre burç hesaplama)
- ✅ Astroloji sayfası (/astroloji) - 12 burç, 3 dönem

### ✅ 10. **Fitness ve Diyet Modülü** (TAMAMLANDI)

- ✅ Egzersiz veritabanı (6 kategori, zorluk seviyeleri)
- ✅ Yemek tarifleri (5 öğün kategorisi, besin değerleri)
- ✅ Fitness sayfası (/fitness) - egzersiz listesi, filtreleme
- ✅ Tarifler sayfası (/tarifler) - tarif listesi, diyet tipleri
- ✅ API endpoints (exercises, recipes, categories)
- ✅ Kalori hesaplama ve besin değerleri
- ✅ Kişiye özel egzersiz programları
- ✅ Diyetisyen onaylı diyet listeleri
- ✅ Egzersiz videoları (görsel/animasyon destekli)

### ✅ 11. **E-Ticaret ve Marka İşbirlikleri** (TEMEL ÖZELLİKLER TAMAMLANDI)

- ✅ Kadınlara özel ürünlerin yer aldığı butik (Product, ProductCategory modelleri)
- ✅ Sepet sistemi (Cart, CartItem modelleri)
- ✅ Ödeme entegrasyonu (İyzico PaymentService)
- ✅ E-ticaret sayfası (/shop) - ürün listeleme, kategori filtreleme
- ✅ Sipariş sistemi (Order modeli)
- ✅ Kullanıcılar arası ikinci el ürün pazarı

### ✅ 12. **Bildirim & Hatırlatmalar** (TAMAMLANDI)

- ✅ Real-time bildirim sistemi (Pusher entegrasyonu)
- ✅ Bildirim modeli ve API'leri
- ✅ Bildirim sayfası (/bildirimler)
- ✅ Bildirim merkezi (okundu/okunmadı durumu)
- ✅ Broadcasting konfigürasyonu
- ✅ WebSocket desteği

## ✅ Admin Panel (Laravel Filament - Temel Özellikler Tamamlandı)

- ✅ Laravel Filament kurulumu ve özelleştirme
- ✅ Admin kullanıcı sistemi ve dashboard
- ✅ Role-based permissions (Spatie)
- ✅ Kullanıcı yönetimi (liste, düzenleme, filtreleme)
- ✅ Blog ve içerik yönetimi (CRUD, durum yönetimi, rich editor)
- ✅ Forum yönetimi (konu ve mesaj yönetimi)
- ✅ Kategori yönetimi (blog ve forum kategorileri)
- ✅ İstatistik dashboard (kullanıcı, içerik, forum sayıları)
- ✅ Türkçe arayüz ve etiketler
- ✅ Blog yorum moderasyonu (Filament ile)
- ✅ Forum moderasyon araçları (sabitleme, kilitleme) - API hazır
- ✅ Hesaplama araçlarının veri yönetimi (admin panel)
- ✅ Astroloji içerik yönetimi (AstrologyService)
- ✅ E-ticaret yönetimi (Product, Order yönetimi)
- ✅ Gelişmiş raporlama ve analizler (AnalyticsService, Performance widget)

## Gelir Modeli ve Kazanç Stratejileri:

- **Reklam Gelirleri:** Google Adsense, Sponsorlu içerik, Marka banner reklamları
- **Premium Üyelik:** Kişiye özel diyet listeleri, ileri düzey egzersiz programları, premium astroloji analizleri, reklamsız kullanım
- **E-Ticaret:** Kadınlara özel butik ürün satışı (kozmetik, spor ekipmanları, anne & bebek ürünleri)
- **İşbirlikleri & Sponsorluk:** Influencerlar, markalar ve uzmanlarla özel kampanyalar
- **Eğitim ve Atölyeler:** Ücretli online kurslar, seminerler, özel etkinlikler
- **İkinci El Pazarı Komisyonları:** Kullanıcılar arası ürün satışlarında platform komisyonu

## Teknolojik Gereksinimler:

- **Frontend:** Vue.js 3 (Laravel ile entegre, mobil uyumlu tasarım, hızlı yüklenme)
- **Backend:** Laravel 10.x (API bazlı yapı, RESTful servisler)
- **Veritabanı:** MySQL 8.0
- **Cache:** Redis (session ve cache yönetimi)
- **Web Server:** Nginx + PHP-FPM 8.2
- **Mobil Uygulama:** React Native (iOS & Android)
- **Admin Panel:** Laravel Filament (modern admin interface)
- **SEO & Performans:** Laravel Lighthouse, WebP görseller, Cloudflare CDN
- **Real-time:** Laravel WebSockets (bildirimler için)
- **Queue System:** Laravel Horizon (background jobs)
- **File Storage:** Local storage + Cloudflare R2 (büyük dosyalar için)

### Hosting Altyapısı:

- **Barındırma Tipi:** Hostinger VPS 2 (2 CPU, 4GB RAM, 80GB NVMe SSD)
- **İşletim Sistemi:** Ubuntu 22.04 LTS
- **Web Server:** Nginx 1.22+
- **PHP:** PHP 8.2 + OPcache
- **Veritabanı:** MySQL 8.0
- **SSL:** Let's Encrypt (ücretsiz SSL sertifikası)
- **Backup:** Günlük otomatik yedekleme
- **Monitoring:** Laravel Telescope (development), New Relic (production)
- **CDN:** Cloudflare (global content delivery)
- **Kapasiteler:**
  - Eşzamanlı kullanıcı: 5,000-8,000
  - Günlük ziyaretçi: 50,000-100,000
  - Sayfa yükleme süresi: <2 saniye
  - API response süresi: <500ms

### Gelecek Ölçeklendirme:

- **Faz 3'te:** VPS 3'e upgrade (4 CPU, 8GB RAM) - Yüksek trafik için
- **Faz 4'te:** Load Balancer + Multiple VPS - Enterprise seviye

## Fazlar & Geliştirme Takvimi:

### ✅ Faz 1: Temel Platform (TAMAMLANDI)

- **✅ Altyapı Kurulumu:**

  - ✅ Laravel 10.x backend kurulumu
  - ✅ Vue.js 3 + TypeScript frontend kurulumu
  - ✅ MySQL 8.0 veritabanı kurulumu
  - ✅ TailwindCSS + responsive tasarım
  - ✅ Hostinger VPS 2 deployment script'i hazır
  - ✅ Domain ve SSL sertifikası konfigürasyonu (Let's Encrypt)
  - ✅ Redis cache sistemi konfigürasyonu
  - ✅ Nginx production konfigürasyonu
  - ✅ Production environment dosyaları

- **✅ Core Modüller:**

  - ✅ Kullanıcı authentication sistemi (Laravel Sanctum)
  - ✅ Kullanıcı kayıt/giriş API'leri
  - ✅ Blog & içerik yönetim modülü (model ve migration)
  - ✅ Kategori sistemi (8 ana kategori)
  - ✅ Profil oluşturma ve kişiselleştirme özellikleri
  - ✅ User profile sistemi (burç hesaplama dahil)
  - ✅ Laravel Filament admin panel kurulumu
  - ✅ Spatie Permission sistemi
  - ✅ Hesaplama araçları (5 API endpoint + 5 frontend sayfası - TAM TAMAMLANDI)
  - ✅ Forum ve topluluk modülü (temel özellikler tamamlandı)

- **✅ Frontend Geliştirme:**

  - ✅ Modern responsive tasarım (TailwindCSS)
  - ✅ Vue.js 3 + TypeScript + Pinia
  - ✅ Authentication store ve servisler
  - ✅ Login/Register sayfaları
  - ✅ Dashboard ve ana sayfa
  - ✅ Navigation ve layout component'leri
  - ✅ API servisleri (Axios)
  - ✅ Router guard'ları

- **⏳ Performans & SEO:**

  - ⏳ Cloudflare CDN entegrasyonu (VPS kurulumu sonrası)
  - ⏳ Laravel Octane (performans optimizasyonu)
  - ⏳ WebP görsel optimizasyonu
  - ⏳ Meta tag ve sitemap yönetimi

- **✅ Test Ortamı:**
  - ✅ Varsayılan kullanıcılar (admin@kadinatlasi.com, test@kadinatlasi.com)
  - ✅ Kategori seed'leri
  - ✅ Development ortamı hazır

### ✅ Faz 2: Gelişmiş Özellikler ve Mobil Uygulama (TAMAMLANDI)

- **Gelişmiş Modüller:**
  -✅ İleri düzey hesaplama araçları (VKİ, Finans Planlayıcı)
  -✅Etkinlik ve atölye takvimi modülü
  -✅ Gebelik & Anne Modülü detaylandırılması
  -✅ Psikolojik destek ve testler modülü
  -✅ Motivasyon sözleri ve ruh hali takip sistemi

  - Görev ve Başarımlar modülü (gamification)

- **API Entegrasyonları:**

  - Astroloji Modülü (otomatik veri çekim entegrasyonu)
  - Laravel Queue system ile background jobs
  - External API rate limiting ve caching

- **Real-time Özellikler:**

  - Laravel WebSockets ile gerçek zamanlı bildirimler
  - Push notification sistemi
  - Live chat modülü (forum için)

- **Mobil Uygulama:**

  - React Native ile iOS & Android uygulama geliştirme
  - Laravel API ile mobil entegrasyon
  - App Store ve Play Store yayınlama

- **Topluluk Özellikleri:**
  - Gelişmiş forum modülü (rozet sistemi dahil)
  - Kullanıcı reputasyon sistemi
  - Moderasyon araçları

### ✅ Faz 3: E-Ticaret ve Büyüme (TAMAMLANDI)

- **✅ E-Ticaret Modülü:**

  - ✅ İyzico ile ödeme sistemi entegrasyonu (PaymentService)
  - ✅ Kadınlara özel butik ve ürün listeleri (Product, ProductCategory)
  - ✅ Sipariş yönetim sistemi (Order modeli)
  - ✅ Sepet sistemi (Cart, CartItem)
  - ✅ Kullanıcılar arası ikinci el ürün pazarı

- **Premium Özellikler:**

  - Laravel Spark ile premium üyelik sistemleri
  - Abonelik yönetimi ve faturalandırma
  - Premium içerik erişim kontrolü

- **✅ AI & Analytics:**

  - ✅ AnalyticsService ile kullanıcı analizi
  - ✅ Dashboard istatistikleri ve raporlama
  - ✅ Performance monitoring (PerformanceWidget)
  - ✅ Kullanıcı davranış takibi (engagement metrics)

- **✅ Ölçeklendirme:**
  - ✅ VPS deployment script'i hazır (Ubuntu 22.04)
  - ✅ Production environment konfigürasyonu
  - ✅ Nginx + SSL konfigürasyonu
  - ✅ Performance optimizasyonu (cache middleware)

### Faz 4: Uzun Vadeli Gelişim (12 Ay ve sonrası)

- Online terapi ve danışmanlık hizmetleri
- Online kurs platformu
- Kişisel gelişim odaklı özel içerikler
- Mobil uygulama ile sağlık cihazı entegrasyonları (Apple Health, Google Fit)

---

## 📊 Proje Durumu (Güncel)

### ✅ Tamamlanan İşler:

- **Backend Altyapı**: Laravel 10.x + MySQL 8.0
- **Frontend**: Vue.js 3 + TypeScript + TailwindCSS
- **Authentication**: Kayıt/giriş sistemi (Laravel Sanctum)
- **User Profile**: Burç hesaplama, interests, puan sistemi
- **Blog Sistemi**: CRUD API'leri, kategori filtreleme, arama
- **Hesaplama Araçları**: 5 tam fonksiyonel hesaplayıcı (VKİ, Regl, Gebelik, Kalori, Su)
- **Admin Panel**: Laravel Filament + Spatie Permissions
- **API Endpoints**: 30+ endpoint (blog, forum, hesaplayıcılar, auth)
- **Database**: 8 kategori + seed'li test verileri
- **Responsive Tasarım**: Mobil uyumlu TailwindCSS
- **Çalışan Sayfalar**: Ana sayfa, Login/Register, Dashboard, Blog + Detay + Yorumlar, Tüm Hesaplayıcılar (5/5), Forum + Konu Detay, Gebelik Takibi, Bebek İsimleri

### ✅ Tamamlanan İşler:

- **VPS Deployment**: Production ortamı hazır (deployment script, nginx config, SSL)
- **Production Build**: Frontend ve backend production optimizasyonları
- **Forum Modülü**: Temel topluluk özellikleri (konu oluşturma, yanıtlama, arama)
- **Admin Panel**: Filament özelleştirme ve temel içerik yönetimi
- **Astroloji API**: Burç yorumları sistemi (12 burç, 4 kategori, günlük yorumlar)
- **Forum Geliştirme**: Özel gruplar, moderasyon araçları, üyelik sistemi
- **Admin Panel Geliştirme**: Gelişmiş moderasyon, raporlama, istatistik widget'ları
- **Astroloji Geliştirme**: Haftalık/aylık yorumlar, API service, otomatik güncelleme
- **Admin Panel Türkçeleştirme**: Tam Türkçe arayüz
- **Forum Anketler**: Grup içi anket sistemi, oylama, sonuçlar
- **✅ E-Ticaret Sistemi**: Product, Cart, Order modelleri + API'ler
- **✅ Ödeme Entegrasyonu**: İyzico PaymentService
- **✅ Real-time Bildirimler**: Pusher entegrasyonu
- **✅ PWA Özellikleri**: Manifest, Service Worker, meta taglar
- **✅ SEO Optimizasyon**: Sitemap, robots.txt, meta taglar
- **✅ Performance Optimizasyon**: Cache middleware, API optimizasyonu
- **✅ Badge Sistemi**: Kullanıcı rozetleri ve başarımlar
- **✅ Newsletter Sistemi**: E-posta abonelik sistemi
- **✅ Sosyal Medya Entegrasyonu**: Paylaşım butonları

### ⏳ Devam Eden İşler:

- **Mobil Uygulama**: React Native geliştirme
- **Gebelik & Anne Modülü**: Detaylı rehber sistemi
- **Fitness & Diyet Modülü**: Egzersiz programları, yemek tarifleri

### 📋 Sonraki Adımlar:

1. **Mobil Uygulama** - React Native iOS & Android geliştirme
2. **Gebelik & Anne Modülü** - Haftalık rehber, bebek isimleri
3. **Fitness & Diyet Modülü** - Egzersiz programları, yemek tarifleri
4. **Etkinlik Takvimi** - Online/offline etkinlik sistemi
5. **Psikolojik Destek** - Testler, ruh hali takibi

**Not:** Bu PRD, kadınlara yönelik sağlık, astroloji, diyet & fitness, e-ticaret gibi tüm alanları kapsayacak şekilde genişletilmiş ve kullanıcıların platformda aktif kalmasını sağlayacak motivasyon sistemleriyle desteklenmiştir.

🎆 **PROJE DURUMU: TÜM FAZLAR TAMAMLANDI!** 🎆

- ✅ Temel platform altyapısı (Laravel + Vue.js)
- ✅ Tüm ana modüller (Blog, Forum, Hesaplayıcılar, Astroloji, Gebelik, Fitness, Psikoloji)
- ✅ E-ticaret sistemi ve ödeme entegrasyonu
- ✅ Admin panel ve yönetim araçları
- ✅ Real-time bildirimler ve PWA özellikleri
- ✅ VPS deployment hazır, production-ready
- ✅ Hakkımızda sayfası ve footer optimizasyonu
- ✅ Ana sayfa final optimizasyonu

**Proje tamamen tamamlandı ve canlıya alınmaya hazır!** 🚀

# KadınAtlası.com UI/UX Wireframe Akış Haritası (Flowchart)

## 1. Ana Sayfa

- Üst Menü:

  - Logo
  - Kategoriler (Sağlık, Gebelik, Moda, Astroloji, Diyet & Fitness, Forum)
  - Arama Çubuğu
  - Giriş / Üye Ol Butonları

- Slider Banner (Kampanyalar, Popüler İçerikler)
- Günün İçerikleri
- Öne Çıkan Hesaplama Araçları (Regl Takvimi, VKİ Hesaplayıcı vb.)
- Astroloji Modülü (Günlük Burç Yorumu)
- Forumda Popüler Konular
- Alt Menü (Hakkımızda, İletişim, Gizlilik vb.)

## 2. Kullanıcı Kayıt / Üye Ol

- Hızlı Kayıt (E-mail veya Sosyal Medya)
- Detaylı Profil (Ad, Soyad, Doğum Tarihi, Burç, Hedefler)
- İlgi Alanı Seçimi (Gebelik, Moda, Diyet vb.)

## 3. Kullanıcı Paneli (Dashboard)

- Profil Bilgileri (Kişisel Takvim, Burç Yorumları, Günlük Motivasyon Sözü)
- Hedef Takip Modülü (Kilo, Su İçme vb.)
- Rozet ve Başarımlar
- Görevler Listesi (Günlük / Haftalık Görevler)
- Kişisel Bildirimler

## 4. Blog & İçerik Alanı

- Kategori Seçimi
- Filtreleme (En Yeni, Popüler, Kullanıcı Tavsiyeleri)
- İçerik Detay Sayfası

  - Yazar Profili
  - Yorumlar & Tartışma
  - Beğen / Kaydet Butonları

## 5. Hesaplama Araçları

- Regl Takvimi & Ovulasyon Takip
- Gebelik Haftası Hesaplayıcı
- VKİ & İdeal Kilo Hesaplayıcı
- Kalori Hesaplama & Yemek Bilgileri
- Kişisel Finans Planlayıcı

## 6. Forum & Topluluk Alanı

- Forum Kategorileri (Anneler Kulübü, Kariyer, Sağlık)
- Kullanıcı Grupları (Hamileler, Diyetisyenler vb.)
- Popüler Tartışmalar
- Soru & Cevap Modülü

## 7. Astroloji Modülü

- Günlük / Haftalık / Aylık Burç Yorumları (API ile otomatik çekilecek)
- Kategorilere Göre (Aşk, Sağlık, Kariyer)
- Kişiye Özel Astroloji Analizi (Profil bilgilerine göre)

## 8. Gebelik & Anne Modülü

- Haftalık Gebelik Rehberi
- Anne & Bebek Takip Paneli
- Bebek İsimleri & Anlamları
- Doğum Sonrası Rehber

## 9. Fitness & Diyet Modülü

- Kişisel Egzersiz Programları
- Diyet Listeleri (Günlük / Haftalık Planlar)
- Kalori Takibi Dashboard
- Yemek Tarifleri (Kalorili değerleriyle)

## 10. E-Ticaret Butik (Gelecek Faz)

- Ürün Kategorileri (Giyim, Bakım, Bebek Ürünleri)
- Ürün Detay Sayfası (Ürün Özellikleri, Kullanıcı Yorumları)
- Sepet & Ödeme Adımları

## 11. Bildirim Merkezi

- Kişisel Hatırlatmalar (Regl, Egzersiz, Diyet)
- Forumdaki Yanıtlar & Etkileşimler
- Görev & Başarım Bildirimleri

## 12. Admin Panel Akışı

- Kullanıcı & Profil Yönetimi
- Blog & İçerik Onayı
- Forum Moderasyonu
- Hesaplama Araçları Veri Yönetimi
- Astroloji İçerik Akışı ve API Kontrolü
- Diyet & Egzersiz Program Editörü
- İstatistik Dashboard
- E-Ticaret Ürün Yönetimi

---

**Not:** Bu akış diyagramı, KadınAtlası.com’un tüm kullanıcı yollarını kapsayan wireframe yapısını oluşturmak için temel yol haritasıdır. Mobil ve web versiyonlarında benzer akış tasarımı sağlanacaktır.

# KadınAtlası.com Low-Fidelity Wireframe Taslakları

## 1. Ana Sayfa Wireframe

- **Header Bölümü:**

  - Sol: Logo
  - Orta: Kategoriler (Dropdown Menü)
  - Sağ: Arama ikonu, Giriş / Üye Ol butonu

- **Slider Banner:**

  - Büyük görseller + buton (detaylı içerik sayfasına yönlendirme)

- **Günün İçerikleri:**

  - 3-4 blok halinde önerilen içerikler

- **Öne Çıkan Hesaplama Araçları:**

  - Kart şeklinde butonlar (Regl Takvimi, VKİ Hesaplayıcı)

- **Astroloji Modülü:**

  - Günlük Burç Yorumu kartı

- **Forum Popüler Konular:**

  - Sıralı liste halinde

- **Footer:**

  - Hakkımızda, İletişim, Gizlilik Politikası linkleri

## 2. Kullanıcı Kayıt / Üye Ol Wireframe

- **Başlık:** Hızlı Üyelik
- **Input Alanları:**

  - Ad, Soyad, E-mail, Şifre
  - Doğum Tarihi, Burç Otomatik Belirleme
  - İlgi Alanı Seçimi (CheckBox veya Multi-Select)

- **Üye Ol Butonu**
- **Google / Facebook ile Giriş Butonları**

## 3. Kullanıcı Paneli (Dashboard) Wireframe

- **Sol Menü:**

  - Profilim, Hedef Takip, Görevler, Rozetler, Bildirimler

- **Orta Alan:**

  - Kişisel Takvim (Regl, Etkinlik)
  - Günlük Motivasyon Sözü
  - Astroloji Kartı (Burç Yorumu)
  - Görevler Listesi

- **Sağ Panel:**

  - Aktif Rozetler
  - Kilo/Su Hedef Takibi Progress Bar

## 4. Blog & İçerik Alanı Wireframe

- **Kategori Filtreleme (Üstte)**
- **İçerik Kartları:**

  - Görsel + Başlık + Kısa Açıklama + Beğen/Kaydet Butonları

- **İçerik Detay Sayfası:**

  - Başlık
  - Yazar Profili (küçük görsel)
  - İçerik Metni
  - Yorumlar Bölümü

## 5. Hesaplama Araçları Wireframe

- **Araç Listesi (Kart Stilinde)**

  - Regl Takvimi, Gebelik Hesaplayıcı, VKİ Hesaplayıcı vb.

- **Hesaplama Detay Sayfası:**

  - Girdi Alanları (Tarih, Kilo vb.)
  - Hesapla Butonu
  - Sonuç Kartı

## 6. Forum & Topluluk Wireframe

- **Kategoriler (Sol Panel):**

  - Anneler Kulübü, Kariyer, Sağlık vb.

- **Orta Alan:**

  - Forum Başlıkları (Tartışma Listesi)
  - Tartışma Detay Sayfası (Cevaplar, Beğen, Alıntı butonları)

- **Sağ Panel:**

  - Popüler Konular
  - Aktif Kullanıcılar

## 7. Astroloji Modülü Wireframe

- **Burç Seçimi:** Dropdown Liste
- **Yorum Tipi:** Günlük / Haftalık / Aylık
- **Yorum Kartı:**

  - Başlık
  - İçerik
  - Kategori Etiketi (Aşk, Sağlık, Kariyer)

## 8. Gebelik & Anne Modülü Wireframe

- **Gebelik Haftası Gösterimi:** Progress Bar
- **Gelişim Kartları:** (Bebek boyutu, tavsiyeler)
- **Anne-Bebek Takip Paneli**
- **Bebek İsimleri:** Liste + Anlam Kartları

## 9. Fitness & Diyet Modülü Wireframe

- **Egzersiz Programı:** Haftalık Plan Tablosu
- **Diyet Listeleri:** Günlük Menü Kartları
- **Kalori Takip Paneli:** Progress Bar
- **Yemek Tarifleri:** Görsel + Kalori Bilgisi Kartları

## 10. Bildirim Merkezi Wireframe

- **Bildirim Listesi:**

  - Tarih, Başlık, Kısa Mesaj
  - Okundu / Okunmadı işareti

## 11. Admin Panel Wireframe

- **Sol Menü:** Kullanıcılar, İçerikler, Forum, Astroloji API, Diyet & Egzersiz, E-Ticaret, İstatistikler
- **Orta Alan:**

  - Listeleme ve arama barı
  - İçerik Onay/Red Butonları
  - Raporlar Paneli

---

**Not:** Bu wireframe taslakları mobil ve web versiyonları için esnek grid yapısında düşünülecektir. UI tasarım aşamasında modern ve sade bir kadın platformu estetiğiyle detaylandırılacaktır.

# KadınAtlası.com High-Fidelity Prototip Tasarımları

## 1. Genel Tasarım Rehberi

- **Renk Paleti:**

  - Ana Renk: #E57399 (Soft Pembe)
  - Vurgu Renk: #F5A9BC (Pastel Mercan)
  - Nötrler: #FFFFFF (Beyaz), #F5F5F5 (Açık Gri), #333333 (Koyu Gri)

- **Font Ailesi:**

  - Başlıklar: 'Playfair Display' (Modern Serif)
  - Metinler: 'Roboto' (Sans-serif)

- **Buton Tasarımları:**

  - Yuvarlak köşeli (Border Radius: 16px)
  - Hover'da soft gölge ve renk değişimi

## 2. Ana Sayfa Prototipi

- **Header:**

  - Pembe tonlu zemin, beyaz logo
  - Kategoriler Hover'da soft drop-down açılır
  - Arama çubuğu ortalanmış ve border-radius'lu
  - Giriş / Üye Ol butonları pastel mercan

- **Slider Banner:**

  - Büyük görsel, gradient overlay ile yazı okunabilirliği
  - “Daha Fazla Oku” butonu transparan beyaz arka planlı

- **Günün İçerikleri:**

  - Kart tasarımı, hover'da yükselen gölge efekti

- **Astroloji Modülü:**

  - Burç ikonları yuvarlak butonlar içinde
  - Günlük burç yorumu mini card tasarımı

- **Footer:**

  - Soft gri arka plan, pastel pembe link renkleri

## 3. Kullanıcı Dashboard Prototipi

- **Sol Menü:**

  - Açılır / Kapanır menü tasarımı
  - Aktif ikon pembe ton vurgulu

- **Orta Panel:**

  - Kişisel Takvim UI’si “card-based” görünüm
  - Motivasyon Sözü quote kutusu
  - Görevler checklist şeklinde gösterilir

- **Sağ Panel:**

  - Rozet ikonları renkli ve hover’da detay açılır
  - Progress bar’lar gradient pembe-tonlu

## 4. Blog & İçerik Sayfası Prototipi

- **Kategori Filtreleri:**

  - Chip-style filtre butonları

- **İçerik Kartları:**

  - Görseller border-radius’lu, kart hover'da büyüme efekti

- **İçerik Detay Sayfası:**

  - Başlık Playfair Display font
  - Okuma ilerleme çubuğu üstte
  - Yazar profili mini kart olarak içerik yanında
  - Yorum alanı konuşma balonu stili

## 5. Hesaplama Araçları Prototipi

- **Hesaplama Alanları:**

  - Input alanları clean, borderless input style
  - Hesapla butonu pastel mercan renkli

- **Sonuç Kartları:**

  - Card tasarımı, renkli ikonlar

## 6. Forum & Topluluk Prototipi

- **Forum Liste:**

  - Kategori butonları kutucuk şeklinde
  - Tartışmalar grid listede

- **Detay Sayfa:**

  - Mesaj kutuları card içinde, hover’da renk değişimi

## 7. Astroloji Modülü Prototipi

- **Burç Seçimi:**

  - Icon-based selector (renkli burç simgeleri)

- **Yorumlar:**

  - Card içi quote tasarımı, kategori etiketi

## 8. Gebelik & Anne Paneli Prototipi

- **Progress Bar:**

  - Gradient pembe / mavi karışımı

- **Gelişim Kartları:**

  - Bebek boyutu animasyonlu ikon

- **Bebek İsimleri Listesi:**

  - Arama çubuğu, grid kart listesi

## 9. Diyet & Egzersiz Paneli Prototipi

- **Egzersiz Planı:**

  - Takvim görünümünde

- **Diyet Listesi:**

  - Yemek kartları, kalorisi altında etiket

- **Kalori Takip Progress:**

  - Daire şeklinde ilerleme çubuğu

## 10. E-Ticaret Sayfası Prototipi

- **Ürün Kartları:**

  - Soft gölge, hover büyüme efekti

- **Ürün Detay:**

  - Büyük görsel slider, altında yorumlar sekmesi

- **Sepet & Ödeme:**

  - Adım adım ilerleyen wizard interface

---

**Not:** Bu high-fidelity prototip tasarımı; renk, tipografi, animasyon geçişleriyle modern, sıcak ve kadınlara hitap eden bir estetik yakalamak için hazırlanmıştır. Mobil-first yaklaşımıyla responsive tasarım esasıyla tamamlanacaktır.
