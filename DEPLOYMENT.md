# Інструкції розгортання Trofei Site

## Підготовка сервера

### 1. Встановлення Docker та Docker Compose

```bash
# Оновлення системи
sudo apt update && sudo apt upgrade -y

# Встановлення Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Додавання користувача до групи docker
sudo usermod -aG docker $USER

# Встановлення Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Перезавантаження сесії
newgrp docker
```

### 2. Завантаження проекту

```bash
# Клонування репозиторію
git clone <your-repo-url>
cd trofei_site

# Надання прав на виконання скриптів
chmod +x deploy.sh manage.sh generate_password.sh
```

### 3. Налаштування змінних середовища

```bash
# Копіювання прикладу конфігурації
cp env.example .env

# Редагування конфігурації
nano .env
```

**Важливо:** Змініть `DB_PASSWORD` на безпечний пароль!

## Розгортання

### Автоматичне розгортання

```bash
# Запуск скрипта розгортання
./deploy.sh
```

### Ручне розгортання

```bash
# Побудова образів
docker-compose -f docker-compose.prod.yml build

# Запуск сервісів
docker-compose -f docker-compose.prod.yml up -d

# Перевірка статусу
docker-compose -f docker-compose.prod.yml ps
```

## Безпека

### Базова HTTP авторизація

Сайт захищений базовою HTTP авторизацією. За замовчуванням:
- **Логін:** admin
- **Пароль:** password

### Зміна пароля

```bash
# Генерація нового пароля
./generate_password.sh
```

### Відключення авторизації

Якщо потрібно відключити авторизацію, відредагуйте файл `docker/nginx/default.conf`:

```nginx
# Закоментуйте ці рядки:
# auth_basic "Restricted Access";
# auth_basic_user_file /etc/nginx/htpasswd;
```

Після зміни перезапустіть сервіси:
```bash
./manage.sh restart
```

## Управління

### Основні команди

```bash
# Запуск сервісів
./manage.sh start

# Зупинка сервісів
./manage.sh stop

# Перезапуск сервісів
./manage.sh restart

# Перегляд логів
./manage.sh logs

# Статус сервісів
./manage.sh status
```

### Docker Compose команди

```bash
# Перегляд логів конкретного сервісу
docker-compose -f docker-compose.prod.yml logs -f nginx
docker-compose -f docker-compose.prod.yml logs -f php
docker-compose -f docker-compose.prod.yml logs -f db

# Вхід в контейнер
docker-compose -f docker-compose.prod.yml exec php bash
docker-compose -f docker-compose.prod.yml exec db psql -U trofei_user -d trofei

# Оновлення сервісів
docker-compose -f docker-compose.prod.yml pull
docker-compose -f docker-compose.prod.yml up -d
```

## Налаштування Nginx

### SSL сертифікати

Для налаштування HTTPS:

1. Помістіть ваші SSL сертифікати в папку `ssl/`
2. Оновіть конфігурацію Nginx в `docker/nginx/default.conf`
3. Перезапустіть сервіси

### Домен

Для налаштування домену:

1. Вкажіть ваш домен в DNS
2. Оновіть конфігурацію Nginx
3. Перезапустіть сервіси

## Моніторинг

### Перевірка працездатності

```bash
# Перевірка статусу всіх сервісів
docker-compose -f docker-compose.prod.yml ps

# Перевірка логів
docker-compose -f docker-compose.prod.yml logs

# Перевірка використання ресурсів
docker stats
```

### Резервне копіювання

```bash
# Створення резервної копії бази даних
docker-compose -f docker-compose.prod.yml exec db pg_dump -U trofei_user trofei > backup.sql

# Відновлення з резервної копії
docker-compose -f docker-compose.prod.yml exec -T db psql -U trofei_user -d trofei < backup.sql
```

## Структура проекту

```
trofei_site/
├── app/                    # PHP код
├── docker/                 # Docker конфігурації
│   ├── nginx/             # Nginx конфігурації
│   │   ├── nginx.conf     # Основна конфігурація Nginx
│   │   ├── default.conf   # Конфігурація сервера
│   │   └── htpasswd       # Файл з паролями для авторизації
│   └── php/               # PHP Dockerfile
├── db/                    # SQL скрипти
├── ssl/                   # SSL сертифікати
├── docker-compose.yml     # Development конфігурація
├── docker-compose.prod.yml # Production конфігурація
├── deploy.sh              # Скрипт розгортання
├── manage.sh              # Скрипт управління
├── generate_password.sh   # Скрипт зміни пароля
└── .env                   # Змінні середовища
```

## Troubleshooting

### Проблеми з підключенням до бази даних

```bash
# Перевірка логів бази даних
docker-compose -f docker-compose.prod.yml logs db

# Перевірка підключення
docker-compose -f docker-compose.prod.yml exec db pg_isready -U trofei_user
```

### Проблеми з Nginx

```bash
# Перевірка конфігурації
docker-compose -f docker-compose.prod.yml exec nginx nginx -t

# Перегляд логів
docker-compose -f docker-compose.prod.yml logs nginx
```

### Проблеми з PHP

```bash
# Перегляд логів PHP
docker-compose -f docker-compose.prod.yml logs php

# Вхід в контейнер для діагностики
docker-compose -f docker-compose.prod.yml exec php bash
```

### Проблеми з авторизацією

```bash
# Перевірка файлу htpasswd
docker-compose -f docker-compose.prod.yml exec nginx cat /etc/nginx/htpasswd

# Генерація нового пароля
./generate_password.sh
``` 