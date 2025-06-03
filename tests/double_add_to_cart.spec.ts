import { test, expect } from '@playwright/test';

test('Додавання товару в корзину якщо там вже є один', async ({ page }) => {
  // Відкриваємо сторінку трофею
  await page.goto('http://localhost:8080/product.php?id=2&added=1');

  // Знаходимо кнопку "Купити" і клікаємо на неї двічі
  await page.click('#buy-button');
  await page.click('#buy-button');

  // Перевіряємо, що біля іконки корзини з'явилась двійка
  const cartCounter = page.locator('#cart-counter');
  await expect(cartCounter).toHaveText('2');
});



