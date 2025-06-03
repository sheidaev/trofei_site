import { defineConfig, devices } from '@playwright/test';

/**
 * Налаштуання для змінних середовища з файлу
 * https://github.com/motdotla/dotenv
 */
// import dotenv from 'dotenv';
// import path from 'path';
// dotenv.config({ path: path.resolve(__dirname, '.env') });

/**
 * подробніше https://playwright.dev/docs/test-configuration.
 */
export default defineConfig({

  testDir: './tests',
  /* Запускати тести у файлах паралельно */
  fullyParallel: false,
  /* Завалити збірку на CI, якщо випадково залишився test.only у вихідному коді. */
  forbidOnly: !!process.env.CI,
  /* Повторити спробу тільки на CI */
  retries: process.env.CI ? 2 : 0,
  /* Відмовитись від паралельних тестів на CI. */
  workers: process.env.CI ? 1 : undefined,
  /* Репортер, який використовувати. Дивись https://playwright.dev/docs/test-reporters */
  reporter: 'html',
  /* Загальні налаштування для всіх проєктів нижче. Дивись https://playwright.dev/docs/api/class-testoptions. */
  use: {
    /* Базова URL-адреса для дій, таких як `await page.goto('/')`. */
    // baseURL: 'http://localhost:3000',

    /* Збирати trace при повторній спробі невдалого тесту. Дивись https://playwright.dev/docs/trace-viewer */
    trace: 'on-first-retry',
  },

  /* Налаштування проєктів для основних браузерів */
  projects: [
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },

    // {
    //   name: 'firefox',
    //   use: { ...devices['Desktop Firefox'] },
    // },

    // {
    //   name: 'webkit',
    //   use: { ...devices['Desktop Safari'] },
    // },

    /* Тестування на мобільних екранах. */
    // {
    //   name: 'Mobile Chrome',
    //   use: { ...devices['Pixel 5'] },
    // },
    // {
    //   name: 'Mobile Safari',
    //   use: { ...devices['iPhone 12'] },
    // },

    /* Тестування у фірмових браузерах. */
    // {
    //   name: 'Microsoft Edge',
    //   use: { ...devices['Desktop Edge'], channel: 'msedge' },
    // },
    // {
    //   name: 'Google Chrome',
    //   use: { ...devices['Desktop Chrome'], channel: 'chrome' },
    // },
  ],

  /* Запустити локальний dev-сервер перед стартом тестів */
  // webServer: {
  //   command: 'npm run start',
  //   url: 'http://localhost:3000',
  //   reuseExistingServer: !process.env.CI,
  // },
});
