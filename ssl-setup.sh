#!/bin/bash

# Скрипт для налаштування SSL сертифікатів Let's Encrypt

set -e

# Перевірка змінних середовища
if [ -z "$DOMAIN_NAME" ]; then
    echo "Помилка: DOMAIN_NAME не встановлено"
    echo "Використання: DOMAIN_NAME=your-domain.com ./ssl-setup.sh"
    exit 1
fi

if [ -z "$CERTBOT_EMAIL" ]; then
    echo "Помилка: CERTBOT_EMAIL не встановлено"
    echo "Використання: CERTBOT_EMAIL=your-email@example.com ./ssl-setup.sh"
    exit 1
fi

echo "Налаштування SSL сертифіката для домену: $DOMAIN_NAME"
echo "Email: $CERTBOT_EMAIL"

# Створення директорії для SSL
mkdir -p ssl

# Запуск сервісів без SSL для отримання сертифіката
echo "Запуск сервісів..."
docker-compose -f docker-compose.prod.yml up -d nginx

# Очікування запуску Nginx
echo "Очікування запуску Nginx..."
sleep 10

# Отримання сертифіката (спочатку staging для тестування)
echo "Отримання тестового сертифіката..."
docker-compose -f docker-compose.prod.yml run --rm certbot certonly \
    --webroot \
    --webroot-path=/var/www/html \
    --email $CERTBOT_EMAIL \
    --agree-tos \
    --no-eff-email \
    --staging \
    -d $DOMAIN_NAME

echo "Тестовий сертифікат отримано успішно!"

# Питаємо користувача чи продовжити з реальним сертифікатом
read -p "Тестовий сертифікат отримано. Отримати реальний сертифікат? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "Отримання реального сертифіката..."
    docker-compose -f docker-compose.prod.yml run --rm certbot certonly \
        --webroot \
        --webroot-path=/var/www/html \
        --email $CERTBOT_EMAIL \
        --agree-tos \
        --no-eff-email \
        -d $DOMAIN_NAME
    
    echo "Реальний сертифікат отримано успішно!"
else
    echo "Використовується тестовий сертифікат"
fi

# Перезапуск Nginx з новою конфігурацією
echo "Перезапуск Nginx..."
docker-compose -f docker-compose.prod.yml restart nginx

echo "SSL налаштування завершено!"
echo "Ваш сайт тепер доступний за адресою: https://$DOMAIN_NAME" 