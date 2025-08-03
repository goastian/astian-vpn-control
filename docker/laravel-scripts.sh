#!/bin/sh

set -e

cd /var/www

echo "  Running system configuration..."

cp /root/.env /var/www/.env

php artisan settings:system-start
chown -R www-data:www-data .

find . -type d -exec chmod 750 {} \;
find . -type f -exec chmod 640 {} \;

chmod 600 secrets/*.pem
#chmod -R 770 storage
#chmod -R 770 bootstrap/cache
chmod 400 .env

echo "Starting PHP-FPM..."
php-fpm83 -D || { echo "Failed to start PHP-FPM"; exit 1; }

echo "Starting Nginx..."
exec nginx -g "daemon off;"
