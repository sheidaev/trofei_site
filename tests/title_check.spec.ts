import { test, expect } from '@playwright/test';

test('Перевірка тайтла головної сторінки', async ({ page }) => {

  // Відкриваємо головну сторінку
  await page.goto('http://localhost:8080/index.php');

  // Перевіряємо тайтл сторінки
  await expect(page).toHaveTitle("trofei.ua");
  
});


