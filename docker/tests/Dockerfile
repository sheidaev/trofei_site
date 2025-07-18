FROM mcr.microsoft.com/playwright:v1.52.0-jammy

WORKDIR /app/tests

# Копіюємо всю папку tests
COPY ../tests/ ./

# Встановлюємо залежності
RUN npm install

# Встановлюємо браузери для Playwright
#RUN npx playwright install