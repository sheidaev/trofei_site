FROM php:8.2-fpm

# Встановлюємо системні залежності
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Встановлюємо PHP розширення
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    zip \
    opcache

# Налаштовуємо PHP-FPM
COPY php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Встановлюємо робочий каталог
WORKDIR /var/www/html

# Копіюємо код додатку
COPY ../../app/ /var/www/html/

# Встановлюємо права доступу
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Відкриваємо порт
EXPOSE 9000

# Команда за замовчуванням
CMD ["php-fpm"]