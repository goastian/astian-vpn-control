#!/bin/sh 

cd /var/www


echo "Running migrations"
php artisan migrate --force
echo "Migration ran successfully"

echo "Running nodejs"
npm install
npm run production
echo "Nodejs ran successfully"


php-fpm82 -D

nginx -g "daemon off;"
echo "Server ran successfully" 