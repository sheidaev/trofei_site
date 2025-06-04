import { test, expect } from '@playwright/test';

test('Підписка на розсилку', async ({ page }) => {
  // Відкриваємо головну сторінку
  await page.goto('http://localhost:8080/index.php');

  // Заповнюємо поле email
  await page.fill('#subscribe-email', 'test@example.com');

  // Натискаємо кнопку підписки
  await page.click('#subscribe-button');

  // Перевіряємо, що з'явилось повідомлення про успішну підписку
  const popup = page.locator('#subscribe-popup');
  await expect(popup).toBeVisible();
  await expect(popup).toHaveText('Дякуємо за підписку!');

  // Перевіряємо, що поле email очистилось
  await expect(page.locator('#subscribe-email')).toHaveValue('');
}); 

