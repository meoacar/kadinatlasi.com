# 🚀 KadınAtlası Production Deployment Checklist

## ✅ Hostinger KVM1 Hazırlık

### 1. Hostinger Panel'den Yapılacaklar:
- [ ] Domain kadinatlasi.com'u VPS IP'sine yönlendir
- [ ] DNS A record ekle: @ -> VPS_IP
- [ ] DNS A record ekle: www -> VPS_IP
- [ ] SSH erişimi aktif

### 2. Local'den VPS'e Bağlan:
```bash
ssh root@YOUR_VPS_IP
```

### 3. Deployment Script'i Çalıştır:
```bash
# Script'i VPS'e kopyala
scp hostinger-deploy.sh root@YOUR_VPS_IP:/root/

# VPS'te çalıştır
ssh root@YOUR_VPS_IP
chmod +x /root/hostinger-deploy.sh
./hostinger-deploy.sh
```

## 🎯 Deployment Sonrası Kontroller

### 1. Site Erişimi:
- [ ] http://YOUR_VPS_IP açılıyor
- [ ] http://kadinatlasi.com açılıyor (DNS propagation sonrası)
- [ ] Ana sayfa yükleniyor
- [ ] Navbar menüleri çalışıyor

### 2. Admin Panel:
- [ ] http://YOUR_VPS_IP/admin/login açılıyor
- [ ] admin@kadinatlasi.com / Admin123! ile giriş yapılıyor
- [ ] Dashboard yükleniyor
- [ ] Resources listesi görünüyor
- [ ] Kategoriler var
- [ ] Users listesi var

### 3. API Testleri:
```bash
# Kategoriler
curl http://YOUR_VPS_IP/api/categories

# Blog posts
curl http://YOUR_VPS_IP/api/blog

# Health check
curl http://YOUR_VPS_IP/up
```

### 4. Database Kontrolleri:
```bash
# VPS'te
mysql -u kadinatlasi_user -p kadinatlasi_db
SHOW TABLES;
SELECT COUNT(*) FROM categories;
SELECT COUNT(*) FROM users;
```

## 🔧 Sorun Giderme

### Nginx Hatası:
```bash
sudo nginx -t
sudo systemctl status nginx
sudo tail -f /var/log/nginx/error.log
```

### PHP Hatası:
```bash
sudo systemctl status php8.2-fpm
sudo tail -f /var/log/php8.2-fpm.log
```

### Laravel Hatası:
```bash
cd /var/www/kadinatlasi/backend
tail -f storage/logs/laravel.log
php artisan config:clear
```

### Database Hatası:
```bash
sudo systemctl status mysql
mysql -u root -p
SHOW PROCESSLIST;
```

## 🚀 SSL Sertifikası (Domain aktif olduktan sonra):
```bash
sudo certbot --nginx -d kadinatlasi.com -d www.kadinatlasi.com
```

## 📊 Performance Monitoring:
```bash
# Disk kullanımı
df -h

# RAM kullanımı  
free -h

# CPU kullanımı
top

# Nginx status
sudo systemctl status nginx

# MySQL status
sudo systemctl status mysql
```

## 🎉 Başarı Kriterleri:
- ✅ Site açılıyor
- ✅ Admin panel çalışıyor  
- ✅ Database bağlantısı var
- ✅ Kategoriler yüklü
- ✅ Filament resources çalışıyor
- ✅ Frontend build edilmiş
- ✅ SSL sertifikası kurulu

**Bu checklist'i takip edersen kesinlikle çalışır!** 🚀