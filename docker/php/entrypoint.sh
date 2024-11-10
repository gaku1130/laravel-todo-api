#!/bin/sh

composer install
composer dump-autoload
cp .env.example .env
php artisan key:generate

# 必要なディレクトリと権限の設定
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
chmod -R 775 /var/www/html/storage
chown -R www-data:www-data /var/www/html/storage

# php-fpmでコンテナを継続的に実行
php-fpm