#!/bin/sh

cd /var/www

php artisan key:generate

echo "Running migrations"
php artisan migrate --force
echo "Migration ran successfully"

php artisan settings:upload

echo "Running nodejs"
npm install
npm run production
echo "Nodejs ran successfully"


php-fpm83 -D

nginx -g "daemon off;"
echo "Server ran successfully"
