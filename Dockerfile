FROM alpine:3.18
RUN apk add --no-cache \
    php82 \
    php82-fpm \
    php82-opcache \
    php82-cli \
    php82-mbstring \
    php82-xml \
    php82-curl \
    php82-mysqli \
    php82-pdo \
    php82-pdo_mysql \
    php82-pdo_pgsql \
    php82-zip \
    php82-tokenizer \
    php82-json \
    php82-phar \
    php82-fileinfo \
    php82-dom \
    php82-session \
    php82-simplexml \
    php82-xmlwriter \
    vim \
    nginx \
    nodejs \
    npm \
    git \
    curl \
    unzip

RUN getent passwd www-data || adduser -S -G www-data -s /usr/sbin/nologin www-data

RUN ln -sf /usr/bin/php82 /usr/bin/php
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/

COPY . /var/www/ 

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www 

COPY docker/www.conf /etc/php82/php-fpm.d/www.conf
COPY docker/nginx.conf /etc/nginx/nginx.conf 
COPY docker/default.conf /etc/nginx/http.d/default.conf  
COPY docker/laravel-scripts.sh /usr/local/bin/laravel-scripts.sh

RUN chmod 755 /etc/nginx/http.d/default.conf 
RUN chmod 755 /usr/local/bin/laravel-scripts.sh

EXPOSE 80