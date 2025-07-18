import { test, expect } from '@playwright/test';

test('Підписка на розсилку', async ({ page }) => {
  // Відкриваємо головну сторінку
  await page.goto('/index.php');

  // Заповнюємо поле email
  await page.fill('#subscribe-email', 'test@example.com');

  // Натискаємо кнопку підписки
  await page.click('#subscribe-button');

  // Перевіряємо, що з'явилось повідомлення про успішну підписку
  const popup = page.locator('#center-popup');
  await expect(popup).toBeVisible();
  await expect(popup).toHaveText('Дякуємо! Ви будете першим, хто отримає інформацію про новий трофей!');

  // Перевіряємо, що поле email очистилось
  await expect(page.locator('#subscribe-email')).toHaveValue('');
}); 

