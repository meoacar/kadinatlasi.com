# KadınAtlası.com

KadınAtlası.com; kadınların günlük hayatını kolaylaştıracak, bilgi alabilecekleri, hesaplamalar yapabilecekleri, topluluk oluşturabilecekleri ve kadın sağlığı, yaşam, kariyer gibi tüm ilgi alanlarına hitap eden kapsayıcı bir dijital platformdur.

## 🚀 Özellikler

### ✅ Faz 1 - Tamamlanan Özellikler

- **Kullanıcı Yönetimi**: Kayıt, giriş, profil yönetimi
- **Blog Sistemi**: İçerik yönetimi, kategoriler ve blog sayfası
- **Hesaplama Araçları**: 5 tam fonksiyonel hesaplayıcı (VKİ, Regl, Gebelik, Kalori, Su)
- **Temel UI/UX**: Modern, responsive tasarım (TailwindCSS)
- **Admin Panel**: Laravel Filament ile yönetim paneli
- **API**: RESTful API yapısı (25+ endpoint)
- **Authentication**: Laravel Sanctum ile güvenli kimlik doğrulama
- **Database**: 8 kategori, user profile sistemi, blog modelleri

### 🔄 Geliştirilmekte Olan Özellikler

- Forum ve topluluk özellikleri
- Astroloji modülü
- Admin panel özelleştirme
- VPS deployment
- Blog yorum sistemi

## 🛠 Teknoloji Stack

### Backend
- **Framework**: Laravel 10.x
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum
- **Admin Panel**: Laravel Filament
- **Permissions**: Spatie Laravel Permission
- **Image Processing**: Intervention Image

### Frontend
- **Framework**: Vue.js 3 + TypeScript
- **State Management**: Pinia
- **Routing**: Vue Router
- **Styling**: TailwindCSS
- **Icons**: Heroicons
- **HTTP Client**: Axios

### Hosting & Infrastructure
- **Server**: Hostinger VPS 2 (2 CPU, 4GB RAM, 80GB NVMe SSD)
- **OS**: Ubuntu 22.04 LTS
- **Web Server**: Nginx + PHP-FPM 8.2
- **SSL**: Let's Encrypt
- **CDN**: Cloudflare

## 📁 Proje Yapısı

```
kadinatlasi.com/
├── backend/                 # Laravel API Backend
│   ├── app/
│   │   ├── Http/Controllers/Api/
│   │   ├── Models/
│   │   └── ...
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   └── routes/api.php
├── frontend/               # Vue.js Frontend
│   ├── src/
│   │   ├── components/
│   │   ├── views/
│   │   ├── stores/
│   │   ├── services/
│   │   └── router/
│   └── ...
└── PRD.MD                 # Product Requirements Document
```

## 🚀 Kurulum

### Backend Kurulumu

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve --port=8000
```

### Frontend Kurulumu

```bash
cd frontend
npm install
npm run dev
```

### Test Etme

```bash
# Backend API test
php artisan route:list --path=api

# Frontend build test
npm run build
```

## 👥 Varsayılan Kullanıcılar

### Admin Kullanıcı
- **E-posta**: admin@kadinatlasi.com
- **Şifre**: password

### Test Kullanıcı
- **E-posta**: test@kadinatlasi.com
- **Şifre**: password

## 📊 API Endpoints (25+ Endpoint)

### Authentication
- `POST /api/register` - Kullanıcı kaydı
- `POST /api/login` - Kullanıcı girişi
- `POST /api/logout` - Kullanıcı çıkışı
- `GET /api/me` - Kullanıcı bilgileri

### Content
- `GET /api/categories` - Kategoriler
- `GET /api/blog-posts` - Blog yazıları (filtreleme, arama)
- `GET /api/blog-posts/{id}` - Blog yazısı detayı
- `POST /api/blog-posts` - Blog yazısı oluşturma
- `PUT /api/blog-posts/{id}` - Blog yazısı güncelleme
- `DELETE /api/blog-posts/{id}` - Blog yazısı silme

### Calculator Tools (✅ 5 Hesaplayıcı Aktif)
- `POST /api/calculator/bmi` - VKİ hesaplama
- `POST /api/calculator/menstrual-cycle` - Regl döngüsü
- `POST /api/calculator/pregnancy` - Gebelik hesaplama
- `POST /api/calculator/calorie` - Kalori hesaplama
- `POST /api/calculator/water-intake` - Su ihtiyacı

### Protected Routes
- `GET /api/profile` - Kullanıcı profili
- `PUT /api/profile` - Profil güncelleme
- Admin routes (categories, users management)

## 🎨 Tasarım Sistemi

### Renk Paleti
- **Primary**: #E57399 (Soft Pembe)
- **Accent**: #F5A9BC (Pastel Mercan)
- **Neutral**: Gri tonları

### Typography
- **Başlıklar**: Playfair Display (Serif)
- **Metin**: Roboto (Sans-serif)

## 📈 Performans Hedefleri

- **Sayfa Yükleme**: <2 saniye
- **API Response**: <500ms
- **Eşzamanlı Kullanıcı**: 5,000-8,000
- **Günlük Ziyaretçi**: 50,000-100,000

## 🔒 Güvenlik

- HTTPS zorunlu
- CSRF koruması
- XSS koruması
- SQL Injection koruması
- Rate limiting
- Input validation

## 📝 Lisans

Bu proje özel mülkiyettir. Tüm hakları saklıdır.

## 📞 İletişim

- **Website**: https://kadinatlasi.com
- **E-posta**: info@kadinatlasi.com

---

**Not**: Bu proje aktif geliştirme aşamasındadır. Özellikler ve dokümantasyon sürekli güncellenmektedir.