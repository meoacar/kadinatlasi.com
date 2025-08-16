#!/bin/bash

# KadınAtlası.com VPS Kolay Kurulum Script'i
# Bu script'i VPS'te çalıştırarak tüm kurulumu otomatik yapabilirsiniz

set -e  # Hata durumunda dur

echo "🚀 KadınAtlası.com VPS Kurulumu Başlıyor..."
echo "⚠️  Bu script root yetkisi gerektirir!"

# Root kontrolü
if [ "$EUID" -ne 0 ]; then
    echo "❌ Bu script'i root olarak çalıştırın: sudo ./setup-vps.sh"
    exit 1
fi

# Sistem bilgileri
echo "📋 Sistem Bilgileri:"
echo "OS: $(lsb_release -d | cut -f2)"
echo "IP: $(curl -s ifconfig.me)"
echo "RAM: $(free -h | awk '/^Mem:/ {print $2}')"
echo "Disk: $(df -h / | awk 'NR==2 {print $4}')"
echo ""

# Onay al
read -p "🤔 Kuruluma devam etmek istiyor musunuz? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Kurulum iptal edildi."
    exit 1
fi

# 1. Sistem güncellemesi
echo "📦 Sistem güncelleniyor..."
apt update && apt upgrade -y

# 2. Gerekli paketleri yükle
echo "🔧 Gerekli paketler yükleniyor..."
apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-xml php8.2-gd \
    php8.2-curl php8.2-mbstring php8.2-zip php8.2-intl php8.2-bcmath \
    redis-server unzip git curl software-properties-common

# 3. Composer yükle
echo "🎼 Composer yükleniyor..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# 4. Node.js 20+ yükle
echo "🟢 Node.js yükleniyor..."
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt-get install -y nodejs

# 5. MySQL güvenlik ayarları
echo "🔒 MySQL güvenlik ayarları..."
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root123!';"
mysql -e "DELETE FROM mysql.user WHERE User='';"
mysql -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');"
mysql -e "DROP DATABASE IF EXISTS test;"
mysql -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';"
mysql -e "FLUSH PRIVILEGES;"

# 6. Veritabanı oluştur
echo "📊 Veritabanı oluşturuluyor..."
mysql -u root -proot123! -e "CREATE DATABASE IF NOT EXISTS kadinatlasi_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -proot123! -e "CREATE USER IF NOT EXISTS 'kadinatlasi_user'@'localhost' IDENTIFIED BY 'KadinAtlasi2024!';"
mysql -u root -proot123! -e "GRANT ALL PRIVILEGES ON kadinatlasi_db.* TO 'kadinatlasi_user'@'localhost';"
mysql -u root -proot123! -e "FLUSH PRIVILEGES;"

# 7. Proje dizini hazırla
echo "📁 Proje dizini hazırlanıyor..."
mkdir -p /var/www/kadinatlasi.com
chown -R www-data:www-data /var/www/kadinatlasi.com

# 8. PHP-FPM ayarları
echo "🐘 PHP-FPM ayarları..."
sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' /etc/php/8.2/fpm/php.ini
sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 20M/' /etc/php/8.2/fpm/php.ini
sed -i 's/post_max_size = 8M/post_max_size = 20M/' /etc/php/8.2/fpm/php.ini

# 9. Redis ayarları
echo "🔴 Redis ayarları..."
sed -i 's/# maxmemory <bytes>/maxmemory 256mb/' /etc/redis/redis.conf
sed -i 's/# maxmemory-policy noeviction/maxmemory-policy allkeys-lru/' /etc/redis/redis.conf

# 10. Firewall ayarları
echo "🔥 Firewall ayarları..."
ufw --force enable
ufw allow ssh
ufw allow 'Nginx Full'

# 11. SSL sertifikası için Certbot yükle
echo "🔐 Certbot yükleniyor..."
apt install -y certbot python3-certbot-nginx

# 12. Servisleri başlat
echo "🚀 Servisler başlatılıyor..."
systemctl enable nginx php8.2-fpm mysql redis-server
systemctl restart nginx php8.2-fpm mysql redis-server

# 13. Durum kontrolü
echo "✅ Servis durumları:"
systemctl is-active nginx && echo "✅ Nginx: Çalışıyor" || echo "❌ Nginx: Çalışmıyor"
systemctl is-active php8.2-fpm && echo "✅ PHP-FPM: Çalışıyor" || echo "❌ PHP-FPM: Çalışmıyor"
systemctl is-active mysql && echo "✅ MySQL: Çalışıyor" || echo "❌ MySQL: Çalışmıyor"
systemctl is-active redis-server && echo "✅ Redis: Çalışıyor" || echo "✅ Redis: Çalışıyor"

echo ""
echo "🎉 VPS kurulumu tamamlandı!"
echo "📝 Sonraki adımlar:"
echo "1. Proje dosyalarını /var/www/kadinatlasi.com/ dizinine yükleyin"
echo "2. ./deploy-project.sh script'ini çalıştırın"
echo "3. Domain DNS ayarlarını yapın"
echo "4. SSL sertifikası alın: certbot --nginx -d kadinatlasi.com -d www.kadinatlasi.com"
echo ""
echo "🔑 Veritabanı bilgileri:"
echo "Database: kadinatlasi_db"
echo "Username: kadinatlasi_user"
echo "Password: KadinAtlasi2024!"
echo "MySQL Root Password: root123!"