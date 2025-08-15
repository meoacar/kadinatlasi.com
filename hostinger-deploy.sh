#!/bin/bash

# 🚀 Hostinger KVM1 - KadınAtlası Deployment Script
# Bu script kesinlikle çalışacak!

set -e  # Hata durumunda dur

echo "🚀 KadınAtlası Hostinger Deployment Başlıyor..."

# Renkli output için
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

log_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# 1. Sistem Güncellemeleri
log_info "Sistem güncelleniyor..."
sudo apt update && sudo apt upgrade -y

# 2. Gerekli Paketleri Kur
log_info "LEMP Stack kuruluyor..."
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-xml php8.2-curl php8.2-zip php8.2-mbstring php8.2-gd php8.2-bcmath php8.2-intl php8.2-redis redis-server unzip git curl

# 3. Swap File Oluştur (1GB RAM için kritik)
log_info "Swap file oluşturuluyor..."
if [ ! -f /swapfile ]; then
    sudo fallocate -l 1G /swapfile
    sudo chmod 600 /swapfile
    sudo mkswap /swapfile
    sudo swapon /swapfile
    echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
fi

# 4. Composer Kur
log_info "Composer kuruluyor..."
if [ ! -f /usr/local/bin/composer ]; then
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
fi

# 5. Node.js Kur
log_info "Node.js kuruluyor..."
if ! command -v node &> /dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
    sudo apt-get install -y nodejs
fi

# 6. MySQL Güvenlik Ayarları
log_info "MySQL ayarlanıyor..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS kadinatlasi_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'kadinatlasi_user'@'localhost' IDENTIFIED BY 'KadinAtlasi2024';"
sudo mysql -e "GRANT ALL PRIVILEGES ON kadinatlasi_db.* TO 'kadinatlasi_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# 7. PHP Ayarları (1GB RAM için optimize)
log_info "PHP ayarlanıyor..."
sudo tee /etc/php/8.2/fpm/conf.d/99-kadinatlasi.ini > /dev/null <<EOF
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 10M
post_max_size = 10M
max_input_vars = 3000
opcache.enable=1
opcache.memory_consumption=64
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
EOF

# 8. PHP-FPM Pool Ayarları
log_info "PHP-FPM pool ayarlanıyor..."
sudo tee /etc/php/8.2/fpm/pool.d/kadinatlasi.conf > /dev/null <<EOF
[kadinatlasi]
user = www-data
group = www-data
listen = /var/run/php/php8.2-fpm-kadinatlasi.sock
listen.owner = www-data
listen.group = www-data
pm = dynamic
pm.max_children = 8
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
pm.max_requests = 500
php_admin_value[memory_limit] = 256M
EOF

# 9. Proje Klasörü Oluştur
log_info "Proje klasörü hazırlanıyor..."
sudo mkdir -p /var/www/kadinatlasi
sudo chown -R $USER:www-data /var/www/kadinatlasi

# 10. Git Clone Proje
log_info "Proje GitHub'dan klonlanıyor..."
cd /var/www
if [ -d "kadinatlasi" ]; then
    sudo rm -rf kadinatlasi
fi
git clone https://github.com/meoacar/kadinatlasi.com.git kadinatlasi
cd kadinatlasi

# 11. Backend Kurulumu
log_info "Backend kuruluyor..."
cd /var/www/kadinatlasi/backend

# Dosya izinleri
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache

# Composer install (memory limit ile)
log_info "Composer paketleri kuruluyor..."
COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader --no-interaction

# Environment dosyası
log_info "Environment ayarlanıyor..."
cp .env.production .env

# Laravel key generate
php artisan key:generate --force

# 12. Database Migration (Sıralı ve Güvenli)
log_info "Database migrate ediliyor..."
php artisan migrate:fresh --force --no-interaction

log_info "Seed'ler çalıştırılıyor..."
php artisan db:seed --class=CategorySeeder --force
php artisan db:seed --class=BeautyCategorySeeder --force  
php artisan db:seed --class=UserSeeder --force
php artisan db:seed --class=PageSeeder --force
php artisan db:seed --class=FooterSettingSeeder --force
php artisan db:seed --class=SiteSettingSeeder --force

# 13. Filament Setup
log_info "Filament ayarlanıyor..."
php artisan filament:upgrade
php artisan filament:assets

# Admin kullanıcısı oluştur
log_info "Admin kullanıcısı oluşturuluyor..."
php artisan make:filament-user --name="Admin" --email="admin@kadinatlasi.com" --password="Admin123!" --no-interaction

# 14. Laravel Optimizasyonları
log_info "Laravel optimize ediliyor..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

# 15. Frontend Build
log_info "Frontend build ediliyor..."
cd /var/www/kadinatlasi/frontend
npm ci --production
npm run build

# Build dosyalarını backend/public'e kopyala
log_info "Frontend dosyaları kopyalanıyor..."
cp -r dist/* /var/www/kadinatlasi/backend/public/

# 16. Nginx Konfigürasyonu
log_info "Nginx ayarlanıyor..."
sudo tee /etc/nginx/sites-available/kadinatlasi > /dev/null <<EOF
server {
    listen 80;
    server_name kadinatlasi.com www.kadinatlasi.com;
    root /var/www/kadinatlasi/backend/public;
    index index.php index.html;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    # Handle Laravel routes
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm-kadinatlasi.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Static files caching
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Deny access to sensitive files
    location ~ /\. {
        deny all;
    }

    location ~ /(storage|bootstrap|config|database|resources|routes|tests|vendor) {
        deny all;
    }
}
EOF

# Site'ı aktif et
sudo ln -sf /etc/nginx/sites-available/kadinatlasi /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Nginx test et
sudo nginx -t

# 17. Servisleri Başlat
log_info "Servisler başlatılıyor..."
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
sudo systemctl restart mysql
sudo systemctl restart redis-server

# Servisleri otomatik başlat
sudo systemctl enable php8.2-fpm
sudo systemctl enable nginx
sudo systemctl enable mysql
sudo systemctl enable redis-server

# 18. Cron Job Ekle
log_info "Cron job ekleniyor..."
(crontab -l 2>/dev/null; echo "* * * * * cd /var/www/kadinatlasi/backend && php artisan schedule:run >> /dev/null 2>&1") | crontab -

# 19. Final Permissions
log_info "Final izinler ayarlanıyor..."
cd /var/www/kadinatlasi/backend
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache public/storage

# 20. SSL Sertifikası (Let's Encrypt)
log_info "SSL sertifikası kuruluyor..."
if ! command -v certbot &> /dev/null; then
    sudo apt install -y certbot python3-certbot-nginx
fi

# SSL sertifikası al (domain aktifse)
# sudo certbot --nginx -d kadinatlasi.com -d www.kadinatlasi.com --non-interactive --agree-tos --email admin@kadinatlasi.com

# 21. Test Et
log_info "Sistem test ediliyor..."
cd /var/www/kadinatlasi/backend

# Database bağlantısı test
php artisan migrate:status

# Cache test
php artisan config:clear
php artisan config:cache

log_info "✅ Deployment tamamlandı!"
echo ""
echo "🌐 Site URL: http://$(curl -s ifconfig.me)"
echo "🔐 Admin Panel: http://$(curl -s ifconfig.me)/admin"
echo "📧 Admin Email: admin@kadinatlasi.com"
echo "🔑 Admin Password: Admin123!"
echo ""
echo "🎉 KadınAtlası ar