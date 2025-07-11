FROM nginx:1.25-alpine

# Встановлюємо додаткові пакети
RUN apk add --no-cache \
    openssl \
    apache2-utils

# Копіюємо конфігураційні файли (виправлені шляхи)
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Копіюємо файл з паролями (якщо потрібно)
COPY docker/nginx/htpasswd /etc/nginx/htpasswd

# Створюємо каталоги для логів
RUN mkdir -p /var/log/nginx

# Копіюємо код додатку
COPY app/ /var/www/html/

# Встановлюємо права доступу
RUN chown -R nginx:nginx /var/www/html \
    && chmod -R 755 /var/www/html

# Відкриваємо порти
EXPOSE 80 443

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD wget --quiet --tries=1 --spider http://localhost/ || exit 1

# Команда за замовчуванням
CMD ["nginx", "-g", "daemon off;"]