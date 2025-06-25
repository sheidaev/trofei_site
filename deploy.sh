#!/bin/bash

# Скрипт розгортання Trofei Site на сервері
set -e

echo "🚀 Початок розгортання Trofei Site..."

# Перевірка наявності Docker та Docker Compose
if ! command -v docker &> /dev/null; then
    echo "❌ Docker не встановлений. Встановіть Docker спочатку."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose не встановлений. Встановіть Docker Compose спочатку."
    exit 1
fi

# Створення .env файлу якщо не існує
if [ ! -f .env ]; then
    echo "📝 Створення .env файлу..."
    cp env.example .env
    echo "⚠️  Будь ласка, відредагуйте .env файл з вашими налаштуваннями!"
    echo "   Особливо змініть DB_PASSWORD на безпечний пароль!"
    read -p "Натисніть Enter після редагування .env файлу..."
fi

# Створення директорії для SSL сертифікатів
#mkdir -p ssl

# Надання прав на виконання скриптів
chmod +x manage.sh generate_password.sh

# Зупинка існуючих контейнерів
echo "🛑 Зупинка існуючих контейнерів..."
docker-compose -f docker-compose.prod.yml down

# Видалення старих образів
echo "🧹 Очищення старих образів..."
docker-compose -f docker-compose.prod.yml down --rmi all

# Побудова нових образів
echo "🔨 Побудова Docker образів..."
docker-compose -f docker-compose.prod.yml build --no-cache

# Запуск сервісів
echo "▶️  Запуск сервісів..."
docker-compose -f docker-compose.prod.yml up -d

# Очікування готовності бази даних
echo "⏳ Очікування готовності бази даних..."
sleep 10

# Перевірка статусу сервісів
echo "📊 Статус сервісів:"
docker-compose -f docker-compose.prod.yml ps

echo "✅ Розгортання завершено!"
echo "🌐 Сайт доступний за адресою: http://your-server-ip"
echo "🔐 Логін: admin, Пароль: password"
echo "📝 Для зміни пароля виконайте: ./generate_password.sh"
echo "📝 Логи: docker-compose -f docker-compose.prod.yml logs -f" 