FROM php:8.2-fpm

COPY php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www/html