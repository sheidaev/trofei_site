FROM postgres:15

# Встановлюємо змінні середовища за замовчуванням
ENV POSTGRES_DB=trofei
ENV POSTGRES_USER=trofei_user
ENV POSTGRES_PASSWORD=trofei_pass

# Копіюємо скрипт ініціалізації бази даних
COPY ../../db/init.sql /docker-entrypoint-initdb.d/init.sql

# Створюємо volume для даних
VOLUME ["/var/lib/postgresql/data"]

# Відкриваємо порт
EXPOSE 5432

# Health check для перевірки стану бази даних
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD pg_isready -U $POSTGRES_USER -d $POSTGRES_DB 