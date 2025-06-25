#!/bin/bash

# Скрипт управління Trofei Site
COMPOSE_FILE="docker-compose.prod.yml"

case "$1" in
    start)
        echo "▶️  Запуск сервісів..."
        docker-compose -f $COMPOSE_FILE up -d
        ;;
    stop)
        echo "🛑 Зупинка сервісів..."
        docker-compose -f $COMPOSE_FILE down
        ;;
    restart)
        echo "🔄 Перезапуск сервісів..."
        docker-compose -f $COMPOSE_FILE restart
        ;;
    logs)
        echo "📝 Показ логів..."
        docker-compose -f $COMPOSE_FILE logs -f
        ;;
    status)
        echo "📊 Статус сервісів:"
        docker-compose -f $COMPOSE_FILE ps
        ;;
    update)
        echo "🔄 Оновлення сервісів..."
        docker-compose -f $COMPOSE_FILE pull
        docker-compose -f $COMPOSE_FILE up -d
        ;;
    backup)
        echo "💾 Створення резервної копії бази даних..."
        docker-compose -f $COMPOSE_FILE exec db pg_dump -U trofei_user trofei > backup_$(date +%Y%m%d_%H%M%S).sql
        echo "✅ Резервна копія створена!"
        ;;
    shell)
        echo "🐚 Вхід в контейнер PHP..."
        docker-compose -f $COMPOSE_FILE exec php bash
        ;;
    *)
        echo "Використання: $0 {start|stop|restart|logs|status|update|backup|shell}"
        echo ""
        echo "Команди:"
        echo "  start   - Запустити сервіси"
        echo "  stop    - Зупинити сервіси"
        echo "  restart - Перезапустити сервіси"
        echo "  logs    - Показати логи"
        echo "  status  - Показати статус"
        echo "  update  - Оновити сервіси"
        echo "  backup  - Створити резервну копію БД"
        echo "  shell   - Війти в PHP контейнер"
        exit 1
        ;;
esac 