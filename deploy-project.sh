#!/bin/bash

# KadınAtlası.com Proje Deployment Script'i
# VPS kurulumu tamamlandıktan sonra bu script'i çalıştırın

set -e

echo "🚀 KadınAtlası.com Proje Deployment Başlıyor..."

# Root kontrolü
if [ "$EUID" -ne 0 ]; then
    echo "❌ Bu script'i root olarak çalıştırın: sudo ./deploy-project.sh"
    exit 1
fi

# Proje dosyalarının varlığını kontrol et
if [ ! -d "./backend" ] || [ ! -d "./frontend" ]; then
    echo "❌ Backend ve frontend klasörleri bulunamadı!"
    echo "Bu script'i proje ana dizininde çalıştırın."
    exit 1
fi

# 1. Backend dosyalarını kopyala
echo "📁 Backend dosyaları kopyalanıyor..."
cp -r ./backend/* /var/www/kadinatlasi.com/
chown -R www-data:www-data /var/www/kadinatlasi.com

# 2. Environment dosyasını ayarla
echo "⚙️ Environment dosyası ayarlanıyor..."
cd /var/www/kadinatlasi.com
cp .env.production .env
chown www-data:www-data .env

# 3. Composer dependencies yükle
echo "🎼 Composer dependencies yükleniyor..."
sudo -u www-data composer install --optimize-autoloader --no-dev --no-interaction

# 4. Laravel key generate
echo "🔑 Laravel key generate..."
sudo -u www-data php artisan key:generate --force

# 5. Frontend build
echo "🔨 Frontend build ediliyor..."
cd /tmp
cp -r /var/www/kadinatlasi.com/../frontend ./
cd frontend
npm install --production=false
npm run build

# 6. Frontend dosyalarını kopyala
echo "📦 Frontend dosyaları kopyalanıyor..."
cp -r dist/* /var/www/kadinatlasi.com/public/
chown -R www-data:www-data /var/www/kadinatlasi.com/public

# 7. Database migration ve seed
echo "📊 Database migration ve seed..."
cd /var/www/kadinatlasi.com
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan db:seed --force

# 8. Laravel cache
echo "⚡ Laravel cache oluşturuluyor..."
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# 9. Storage permissions
echo "🔒 Storage permissions ayarlanıyor..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 10. Nginx konfigürasyonu
echo "🌐 Nginx konfigürasyonu..."
if [ -f "/var/www/kadinatlasi.com/../nginx.conf" ]; then
    cp /var/www/kadinatlasi.com/../nginx.conf /etc/nginx/sites-available/kadinatlasi.com
    ln -sf /etc/nginx/sites-available/kadinatlasi.com /etc/nginx/sites-enabled/
    rm -f /etc/nginx/sites-enabled/default
    
    # Nginx test
    nginx -t
    systemctl reload nginx
else
    echo "⚠️ nginx.conf dosyası bulunamadı, manuel konfigürasyon gerekli"
fi

# 11. Cleanup
echo "🧹 Cleanup..."
rm -rf /tmp/frontend

# 12. Final checks
echo "✅ Final kontroller..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost | grep -q "200\|301\|302"; then
    echo "✅ Site erişilebilir"
else
    echo "⚠️ Site erişim kontrolü başarısız"
fi

# Test database connection
cd /var/www/kadinatlasi.com
if sudo -u www-data php artisan migrate:status > /dev/null 2>&1; then
    echo "✅ Database bağlantısı OK"
else
    echo "⚠️ Database bağlantı sorunu"
fi

echo ""
echo "🎉 Proje deployment tamamlandı!"
echo "🌐 Site: http://$(curl -s ifconfig.me)"
echo "🔧 Admin Panel: http://$(curl -s ifconfig.me)/admin"
echo "👤 Admin Login: admin@kadinatlasi.com / password"
echo ""
echo "📝 SSL için:"
echo "certbot --nginx -d kadinatlasi.com -d www.kadinatlasi.com"
echo ""
echo "🔍 Log dosyaları:"
echo "- Nginx: /var/log/nginx/error.log"
echo "- Laravel: /var/www/kadinatlasi.com/storage/logs/laravel.log"