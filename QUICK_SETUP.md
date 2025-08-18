# 🚀 KadınAtlası.com Hızlı VPS Kurulum Rehberi

Bu rehber ile VPS'inizi 15 dakikada kurabilirsiniz.

## 📋 Gereksinimler

- Ubuntu 22.04 LTS VPS
- Root erişimi
- Domain (kadinatlasi.com) DNS ayarları

## ⚡ Hızlı Kurulum (3 Adım)

### 1. VPS'e Bağlan ve Dosyaları Yükle

```bash
# VPS'e SSH ile bağlan
ssh root@YOUR_VPS_IP

# Proje dosyalarını yükle (scp ile)
scp -r kadinatlasi.com root@YOUR_VPS_IP:/root/
```

### 2. VPS Kurulumu (5 dakika)

```bash
# Proje dizinine git
cd /root/kadinatlasi.com

# VPS kurulum script'ini çalıştır
chmod +x setup-vps.sh
./setup-vps.sh
```

### 3. Proje Deployment (5 dakika)

```bash
# Proje deployment script'ini çalıştır
chmod +x deploy-project.sh
./deploy-project.sh
```

## 🌐 Domain ve SSL (5 dakika)

### DNS Ayarları
Domain panelinden (Hostinger/Godaddy vs.):
```
A Record: @ -> YOUR_VPS_IP
A Record: www -> YOUR_VPS_IP
```

### SSL Sertifikası
```bash
certbot --nginx -d kadinatlasi.com -d www.kadinatlasi.com
```

## ✅ Kurulum Tamamlandı!

- **Site**: https://kadinatlasi.com
- **Admin Panel**: https://kadinatlasi.com/admin
- **Admin Login**: admin@kadinatlasi.com / password

## 🔧 Önemli Bilgiler

### Veritabanı
- **Database**: kadinatlasi_db
- **Username**: kadinatlasi_user
- **Password**: KadinAtlasi2024!
- **MySQL Root**: root123!

### Dosya Konumları
- **Proje**: /var/www/kadinatlasi.com
- **Nginx Config**: /etc/nginx/sites-available/kadinatlasi.com
- **Logs**: /var/log/nginx/ ve /var/www/kadinatlasi.com/storage/logs/

### Servis Komutları
```bash
# Servisleri yeniden başlat
systemctl restart nginx php8.2-fpm mysql redis-server

# Durum kontrol
systemctl status nginx php8.2-fpm mysql redis-server

# Laravel cache temizle
cd /var/www/kadinatlasi.com
php artisan cache:clear
php artisan config:clear
```

## 🚨 Sorun Giderme

### Site açılmıyor
```bash
# Nginx durumu
systemctl status nginx
nginx -t

# PHP-FPM durumu
systemctl status php8.2-fpm

# Log kontrol
tail -f /var/log/nginx/error.log
```

### Database hatası
```bash
# MySQL durumu
systemctl status mysql

# Database test
mysql -u kadinatlasi_user -pKadinAtlasi2024! kadinatlasi_db -e "SHOW TABLES;"
```

### Laravel hatası
```bash
# Laravel logs
tail -f /var/www/kadinatlasi.com/storage/logs/laravel.log

# Permissions
chown -R www-data:www-data /var/www/kadinatlasi.com
chmod -R 775 /var/www/kadinatlasi.com/storage
```

## 📞 Destek

Sorun yaşarsanız log dosyalarını kontrol edin:
- `/var/log/nginx/error.log`
- `/var/www/kadinatlasi.com/storage/logs/laravel.log`