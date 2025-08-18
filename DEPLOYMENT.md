# KadınAtlası.com Deployment Rehberi

## 🚀 VPS Deployment (Hostinger VPS 2)

### Sistem Gereksinimleri
- **VPS**: Hostinger VPS 2 (2 CPU, 4GB RAM, 80GB NVMe SSD)
- **OS**: Ubuntu 22.04 LTS
- **Domain**: kadinatlasi.com
- **SSL**: Let's Encrypt

### 1. VPS Hazırlığı

```bash
# VPS'e SSH ile bağlan
ssh root@YOUR_VPS_IP

# Sistem güncellemeleri
apt update && apt upgrade -y

# Gerekli paketleri yükle
apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-xml php8.2-gd php8.2-curl php8.2-mbstring php8.2-zip php8.2-intl php8.2-bcmath redis-server nodejs npm composer git unzip
```

### 2. Domain Ayarları

**DNS Kayıtları (Hostinger/Domain Panel):**
```
A Record: @ -> VPS_IP_ADDRESS
A Record: www -> VPS_IP_ADDRESS
```

### 3. Otomatik Deployment

```bash
# Proje dosyalarını VPS'e yükle
scp -r kadinatlasi.com root@YOUR_VPS_IP:/tmp/

# VPS'te deployment script'i çalıştır
ssh root@YOUR_VPS_IP
cd /tmp/kadinatlasi.com
chmod +x deploy.sh
./deploy.sh
```

### 4. Manuel Deployment Adımları

#### MySQL Kurulumu
```bash
mysql_secure_installation

# Veritabanı oluştur
mysql -u root -p
CREATE DATABASE kadinatlasi_db;
CREATE USER 'kadinatlasi_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD';
GRANT ALL PRIVILEGES ON kadinatlasi_db.* TO 'kadinatlasi_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Laravel Kurulumu
```bash
# Proje dizini
mkdir -p /var/www/kadinatlasi.com
cd /var/www/kadinatlasi.com

# Backend dosyalarını kopyala
cp -r /tmp/kadinatlasi.com/backend/* ./

# Composer dependencies
composer install --optimize-autoloader --no-dev

# Environment
cp .env.production .env
php artisan key:generate

# Cache ve optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Database
php artisan migrate --force
php artisan db:seed --force

# Permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

#### Frontend Build
```bash
cd /tmp/kadinatlasi.com/frontend
npm install
npm run build
cp -r dist/* /var/www/kadinatlasi.com/public/
```

#### Nginx Konfigürasyonu
```bash
# Site konfigürasyonu
cp /tmp/kadinatlasi.com/nginx.conf /etc/nginx/sites-available/kadinatlasi.com
ln -s /etc/nginx/sites-available/kadinatlasi.com /etc/nginx/sites-enabled/
rm /etc/nginx/sites-enabled/default

# Test ve restart
nginx -t
systemctl reload nginx
```

#### SSL Sertifikası
```bash
# Certbot yükle
apt install -y certbot python3-certbot-nginx

# SSL sertifikası al
certbot --nginx -d kadinatlasi.com -d www.kadinatlasi.com
```

### 5. Servis Yönetimi

```bash
# Servisleri başlat ve enable et
systemctl enable nginx php8.2-fpm mysql redis-server
systemctl start nginx php8.2-fpm mysql redis-server

# Durum kontrol
systemctl status nginx php8.2-fpm mysql redis-server
```

### 6. Güvenlik Ayarları

#### Firewall (UFW)
```bash
ufw enable
ufw allow ssh
ufw allow 'Nginx Full'
ufw status
```

#### Fail2Ban
```bash
apt install -y fail2ban
systemctl enable fail2ban
systemctl start fail2ban
```

### 7. Monitoring ve Backup

#### Log Monitoring
```bash
# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# Laravel logs
tail -f /var/www/kadinatlasi.com/storage/logs/laravel.log
```

#### Backup Script
```bash
#!/bin/bash
# /root/backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/root/backups"
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u kadinatlasi_user -p kadinatlasi_db > $BACKUP_DIR/db_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/kadinatlasi.com

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

#### Crontab
```bash
crontab -e
# Daily backup at 2 AM
0 2 * * * /root/backup.sh
```

### 8. Performance Optimizasyonu

#### PHP-FPM Tuning
```bash
# /etc/php/8.2/fpm/pool.d/www.conf
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500
```

#### Redis Konfigürasyonu
```bash
# /etc/redis/redis.conf
maxmemory 256mb
maxmemory-policy allkeys-lru
```

### 9. SSL Yenileme

```bash
# Otomatik yenileme testi
certbot renew --dry-run

# Crontab'a ekle
0 12 * * * /usr/bin/certbot renew --quiet
```

### 10. Troubleshooting

#### Common Issues
```bash
# Permission issues
chown -R www-data:www-data /var/www/kadinatlasi.com
chmod -R 755 /var/www/kadinatlasi.com
chmod -R 775 /var/www/kadinatlasi.com/storage
chmod -R 775 /var/www/kadinatlasi.com/bootstrap/cache

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Queue restart
php artisan queue:restart
```

### 11. Post-Deployment Checklist

- [ ] Site erişilebilir (https://kadinatlasi.com)
- [ ] SSL sertifikası çalışıyor
- [ ] Admin panel erişilebilir (/admin)
- [ ] API endpoints çalışıyor
- [ ] Database bağlantısı OK
- [ ] Redis çalışıyor
- [ ] Email gönderimi test edildi
- [ ] Backup script çalışıyor
- [ ] Monitoring kuruldu

### 12. Varsayılan Giriş Bilgileri

**Admin Panel:**
- Email: admin@kadinatlasi.com
- Password: password

**Test Kullanıcı:**
- Email: test@kadinatlasi.com  
- Password: password

---

## 📞 Destek

Deployment sırasında sorun yaşarsanız:
- Nginx error logs: `/var/log/nginx/error.log`
- Laravel logs: `/var/www/kadinatlasi.com/storage/logs/laravel.log`
- PHP-FPM logs: `/var/log/php8.2-fpm.log`