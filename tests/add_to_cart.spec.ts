import { test, expect } from '@playwright/test';

test('Додавання товару в корзину', async ({ page }) => {
  // Відкриваємо сторінку трофею
  await page.goto('http://localhost:8080/product.php?id=2&added=1');

  // Знаходимо кнопку "Купити" і клікаємо на неї
  await page.click('#buy-button');

  // Перевіряємо, що біля іконки корзини з'явилась одиниця
  const cartCounter = page.locator('#cart-counter');
  await expect(cartCounter).toHaveText('1');
}); 