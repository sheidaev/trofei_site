#!/bin/bash

# Кольори для виводу
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}🚀 Починаємо процес тестування...${NC}\n"

echo -e "${BLUE}📦 Крок 1: Збірка контейнерів застосунку${NC}"
docker-compose --profile app build
if [ $? -ne 0 ]; then
    echo "❌ Помилка при збірці контейнерів застосунку"
    exit 1
fi
echo -e "${GREEN}✅ Контейнери застосунку успішно зібрані${NC}\n"

echo -e "${BLUE}📦 Крок 2: Збірка контейнера з тестами${NC}"
docker-compose --profile test build
if [ $? -ne 0 ]; then
    echo "❌ Помилка при збірці контейнера з тестами"
    exit 1
fi
echo -e "${GREEN}✅ Контейнер з тестами успішно зібраний${NC}\n"

echo -e "${BLUE}🚀 Крок 3: Запуск застосунку${NC}"
docker-compose --profile app up -d
if [ $? -ne 0 ]; then
    echo "❌ Помилка при запуску застосунку"
    exit 1
fi
echo -e "${GREEN}✅ Застосунок успішно запущений${NC}\n"

echo -e "${BLUE}🧪 Крок 4: Запуск тестів${NC}"
docker-compose --profile test up
TEST_RESULT=$?
if [ $TEST_RESULT -ne 0 ]; then
    echo "❌ Тести завершились з помилками"
else
    echo -e "${GREEN}✅ Тести успішно завершені${NC}"
fi

echo -e "\n${BLUE}🔄 Крок 5: Зупинка застосунку${NC}"
docker-compose down
if [ $? -ne 0 ]; then
    echo "❌ Помилка при зупинці застосунку"
    exit 1
fi
echo -e "${GREEN}✅ Застосунок успішно зупинено${NC}\n"

# Повертаємо результат тестів
exit $TEST_RESULT