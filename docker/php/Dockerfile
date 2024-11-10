# ベースイメージの指定
FROM php:8.1.16-fpm

# Viteのサーバーにローカルからアクセスするために開ける
EXPOSE 5173

# COPY php.ini
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

# Composer install
COPY --from=composer:2.5.4 /usr/bin/composer /usr/bin/composer

# install Node.js
COPY --from=node:18.14.2 /usr/local/bin /usr/local/bin
COPY --from=node:18.14.2 /usr/local/lib /usr/local/lib

# 必要なライブラリをインストール
RUN apt-get update && \
  apt-get -y install \
  git \
  zip \
  unzip \
  vim \
  libpq-dev \
  libonig-dev \
  libicu-dev \
  libzip-dev \
  libpng-dev \
  default-mysql-client \
  && docker-php-ext-install pdo_pgsql pdo_mysql bcmath intl mbstring gd && \
  pecl install xdebug && \
  docker-php-ext-enable xdebug

# Laravelプロジェクトのソースコードをコピー
COPY ./src /var/www/html

# 作業ディレクトリの設定
WORKDIR /var/www/html

# entrypoint.shのスクリプトをコピーし、実行権限を付与
COPY ./docker/php/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# コンテナ起動時にentrypoint.shを実行
ENTRYPOINT ["/entrypoint.sh"]