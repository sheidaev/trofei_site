#!/bin/bash

# Скрипт для генерації нового пароля для базової HTTP авторизації

echo "🔐 Генерація нового пароля для базової авторизації"
echo ""

# Запит нового пароля
read -s -p "Введіть новий пароль: " password
echo ""
read -s -p "Підтвердіть пароль: " password_confirm
echo ""

# Перевірка співпадіння паролів
if [ "$password" != "$password_confirm" ]; then
    echo "❌ Паролі не співпадають!"
    exit 1
fi

# Генерація хешу пароля
hashed_password=$(openssl passwd -apr1 "$password")

# Створення нового файлу htpasswd
echo "admin:$hashed_password" > docker/nginx/htpasswd

echo "✅ Пароль успішно оновлено!"
echo "👤 Логін: admin"
echo "🔑 Пароль: (введений вами)"
echo ""
echo "📝 Файл htpasswd оновлено: docker/nginx/htpasswd"
echo ""
echo "🔄 Перезапустіть сервіси для застосування змін:"
echo "   ./manage.sh restart" 