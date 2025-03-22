#!/bin/sh

set -e  

cd /var/www || exit 1 

echo "Generating application key..."
php artisan key:generate

echo "Generating priv and pub key"
php artisan settings:generate-keys

echo "Running migrations..."
php artisan migrate --force
echo "Migrations completed successfully."

echo "Uploading settings..."
php artisan settings:upload

echo "Installing Node.js dependencies..."
npm install --no-progress
echo "Building assets..."
npm run production
echo "Node.js build completed successfully."

echo "Starting PHP-FPM..."
php-fpm8.3 -D || { echo "Failed to start PHP-FPM"; exit 1; }

echo "Starting Nginx..."
exec nginx -g "daemon off;"