#!/bin/bash

# Скрипт для автоматичного оновлення SSL сертифікатів Let's Encrypt

set -e

# Перевірка змінних середовища
if [ -z "$DOMAIN_NAME" ]; then
    echo "Помилка: DOMAIN_NAME не встановлено"
    exit 1
fi

echo "Оновлення SSL сертифіката для домену: $DOMAIN_NAME"

# Оновлення сертифіката
docker-compose -f docker-compose.prod.yml run --rm certbot renew

# Перезапуск Nginx для застосування нових сертифікатів
echo "Перезапуск Nginx..."
docker-compose -f docker-compose.prod.yml restart nginx

echo "Оновлення SSL сертифіката завершено!" 