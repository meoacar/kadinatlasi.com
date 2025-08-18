#!/bin/bash

# KadınAtlası.com VPS Deployment Script
# Ubuntu 22.04 LTS için hazırlanmıştır

echo "🚀 KadınAtlası.com VPS Deployment Başlıyor..."

# Sistem güncellemesi
sudo apt update && sudo apt upgrade -y

# Gerekli paketleri yükle
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-xml php8.2-gd php8.2-curl php8.2-mbstring php8.2-zip php8.2-intl php8.2-bcmath redis-server unzip git curl

# Composer yükle
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Node.js 20+ ve npm yükle
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs

# MySQL güvenlik ayarları
sudo mysql_secure_installation

# Nginx konfigürasyonu (nginx.conf dosyasından kopyalanacak)
echo "⚙️ Nginx konfigürasyonu nginx.conf dosyasından kopyalanacak..."

# MySQL veritabanı kurulumu
echo "📊 MySQL veritabanı kuruluyor..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS kadinatlasi_db;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'kadinatlasi_user'@'localhost' IDENTIFIED BY 'KadinAtlasi2024!';"
sudo mysql -e "GRANT ALL PRIVILEGES ON kadinatlasi_db.* TO 'kadinatlasi_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Proje dizini oluştur
sudo mkdir -p /var/www/kadinatlasi.com
sudo chown -R www-data:www-data /var/www/kadinatlasi.com

# Proje dosyalarını kopyala (eğer mevcut dizinde isek)
if [ -d "./backend" ] && [ -d "./frontend" ]; then
    echo "📁 Proje dosyaları kopyalanıyor..."
    sudo cp -r ./backend/* /var/www/kadinatlasi.com/
    
    # Frontend build
    echo "🔨 Frontend build ediliyor..."
    cd ./frontend
    npm install
    npm run build
    sudo cp -r dist/* /var/www/kadinatlasi.com/public/
    cd ..
    
    # Backend kurulumu
    echo "⚙️ Backend kuruluyor..."
    cd /var/www/kadinatlasi.com
    sudo -u www-data composer install --optimize-autoloader --no-dev
    
    # Environment dosyası
    sudo cp .env.production .env
    sudo chown www-data:www-data .env
    
    # Laravel kurulumu
    sudo -u www-data php artisan key:generate
    sudo -u www-data php artisan migrate --force
    sudo -u www-data php artisan db:seed --force
    sudo -u www-data php artisan config:cache
    sudo -u www-data php artisan route:cache
    sudo -u www-data php artisan view:cache
    
    # Permissions
    sudo chown -R www-data:www-data storage bootstrap/cache
    sudo chmod -R 775 storage bootstrap/cache
fi

# Nginx konfigürasyonu kopyala
if [ -f "./nginx.conf" ]; then
    sudo cp ./nginx.conf /etc/nginx/sites-available/kadinatlasi.com
    sudo ln -sf /etc/nginx/sites-available/kadinatlasi.com /etc/nginx/sites-enabled/
    sudo rm -f /etc/nginx/sites-enabled/default
fi

# SSL sertifikası (Let's Encrypt)
sudo apt install -y certbot python3-certbot-nginx

# PHP-FPM konfigürasyonu
sudo sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' /etc/php/8.2/fpm/php.ini

# Servisleri başlat
sudo systemctl enable nginx php8.2-fpm mysql redis-server
sudo systemctl restart nginx php8.2-fpm mysql redis-server

# Nginx test
sudo nginx -t

echo "✅ VPS Deployment tamamlandı!"
echo "🌐 Site: http://$(curl -s ifconfig.me)"
echo "🔐 SSL için: sudo certbot --nginx -d kadinatlasi.com -d www.kadinatlasi.com"
echo "👤 Admin: admin@kadinatlasi.com / password"