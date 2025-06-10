FROM mcr.microsoft.com/playwright:v1.42.1-jammy

WORKDIR /app/tests

# Копіюємо всю папку tests
COPY ../tests/ ./

# Встановлюємо залежності
RUN npm install

# Встановлюємо браузери для Playwright
RUN npx playwright install