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

  testDir: '.',
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
  timeout: 5000,
  /* Загальні налаштування для всіх проєктів нижче. Дивись https://playwright.dev/docs/api/class-testoptions. */
  use: {
    baseURL: 'http://nginx',
    /* Збирати trace при повторній спробі невдалого тесту. Дивись https://playwright.dev/docs/trace-viewer */
    trace: 'on-first-retry',
  },

  projects: [
  {
    name: 'e2e',
    testMatch: /.*\.e2e\.spec\.ts/,
    use: { 
      ...devices['Desktop Chrome'],
      baseURL: 'http://nginx',
    },
  },
  {
    name: 'integration',
    testMatch: /.*\.integration\.spec\.ts/,
    use: { 
      ...devices['Desktop Chrome'],
      baseURL: 'http://nginx',
    },
  },
],
});



