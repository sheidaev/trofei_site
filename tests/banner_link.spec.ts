import { test, expect } from '@playwright/test';

test('Перехід на сторінку каталогу з банеру', async ({ page }) => {
  // Відкриваємо головну сторінку
  await page.goto('http://localhost:8080/index.php');

  // Знаходимо і клікаємо на кнопку в банері
  await page.click('.banner-btn');

  // Перевіряємо, що перейшли на сторінку каталогу
  await expect(page).toHaveURL('http://localhost:8080/catalog.php');
}); 