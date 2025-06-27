# Налаштування SSL сертифікатів Let's Encrypt

Цей документ описує як налаштувати SSL сертифікати Let's Encrypt для вашого проекту.

## Передумови

1. Доменне ім'я, яке вказує на ваш сервер
2. Відкритий порт 80 та 443 на сервері
3. Docker та Docker Compose встановлені

## Кроки налаштування

### 1. Налаштування змінних середовища

Скопіюйте файл `env.example` в `.env` та налаштуйте змінні:

```bash
cp env.example .env
```

Відредагуйте `.env` файл:

```env
# Database configuration
DB_NAME=trofei
DB_USER=trofei_user
DB_PASSWORD=your_secure_password

# Nginx configuration
NGINX_PORT=80
NGINX_SSL_PORT=443

# SSL Configuration
DOMAIN_NAME=your-domain.com
CERTBOT_EMAIL=your-email@example.com
```

### 2. Запуск налаштування SSL

Запустіть скрипт налаштування:

```bash
DOMAIN_NAME=your-domain.com CERTBOT_EMAIL=your-email@example.com ./ssl-setup.sh
```

Або встановіть змінні середовища та запустіть:

```bash
export DOMAIN_NAME=your-domain.com
export CERTBOT_EMAIL=your-email@example.com
./ssl-setup.sh
```

### 3. Запуск продакшн сервісів

Після успішного отримання сертифіката запустіть всі сервіси:

```bash
docker-compose -f docker-compose.prod.yml up -d
```

## Автоматичне оновлення сертифікатів

Let's Encrypt сертифікати дійсні 90 днів. Для автоматичного оновлення:

### Налаштування cron job

Додайте до crontab:

```bash
# Оновлення сертифікатів кожні 2 місяці
0 0 1 */2 * cd /path/to/your/project && ./ssl-renew.sh >> /var/log/ssl-renew.log 2>&1
```

### Ручне оновлення

Для ручного оновлення:

```bash
./ssl-renew.sh
```

## Перевірка статусу сертифіката

Перевірити термін дії сертифіката:

```bash
docker-compose -f docker-compose.prod.yml run --rm certbot certificates
```

## Структура файлів

```
trofei_site/
├── docker-compose.prod.yml    # Продакшн конфігурація з SSL
├── docker/nginx/
│   ├── default.conf          # Nginx конфігурація з HTTPS
│   └── nginx.conf            # Основна конфігурація Nginx
├── ssl-setup.sh              # Скрипт налаштування SSL
├── ssl-renew.sh              # Скрипт оновлення сертифікатів
└── .env                      # Змінні середовища
```

## Безпека

- Сертифікати зберігаються в Docker volumes
- Використовується HSTS заголовок
- Налаштовані безпечні SSL протоколи та шифри
- Автоматичне перенаправлення HTTP на HTTPS

## Вирішення проблем

### Помилка "Domain validation failed"

1. Перевірте, чи домен вказує на правильний IP адресу
2. Переконайтеся, що порт 80 відкритий
3. Перевірте, чи Nginx запущений

### Помилка "Certificate not found"

1. Перевірте правильність доменного імені
2. Запустіть скрипт налаштування ще раз
3. Перевірте логи certbot

### Помилка "Permission denied"

1. Переконайтеся, що скрипти мають права на виконання
2. Запустіть з sudo якщо потрібно

## Логи

Логи certbot можна переглянути:

```bash
docker-compose -f docker-compose.prod.yml logs certbot
```

Логи Nginx:

```bash
docker-compose -f docker-compose.prod.yml logs nginx
``` 