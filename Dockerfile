FROM alpine:3.20
RUN apk add --no-cache \
    php83 \
    php83-fpm \
    php83-opcache \
    php83-cli \
    php83-mbstring \
    php83-xml \
    php83-curl \
    php83-pecl-redis \
    php83-pecl-memcached \
    php83-pcntl \
    php83-posix \
    php83-pdo \
    php83-pdo_pgsql \
    php83-zip \
    php83-tokenizer \
    php83-json \
    php83-phar \
    php83-fileinfo \
    php83-dom \
    php83-session \
    php83-simplexml \
    php83-xmlwriter \
    php83-soap \
    php83-openssl \
    php83-bcmath \
    php83-gd \
    php83-intl \
    vim \
    nginx \
    nodejs \
    npm \
    curl \
    wireguard-tools \
    unzip

RUN getent passwd www-data || adduser -S -G www-data -s /usr/sbin/nologin www-data

RUN ln -sf /usr/bin/php83 /usr/bin/php
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/

COPY . /var/www/

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www

COPY docker/www.conf /etc/php83/php-fpm.d/www.conf
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/http.d/default.conf
COPY docker/laravel-scripts.sh /usr/local/bin/laravel-scripts.sh

RUN chmod 755 /etc/nginx/http.d/default.conf
RUN chmod 755 /usr/local/bin/laravel-scripts.sh

RUN mkdir -p /var/log/php
RUN chmod -R 755 /var/log/php
RUN chown -R www-data:www-data /var/log/php

EXPOSE 80
EXPOSE 443
