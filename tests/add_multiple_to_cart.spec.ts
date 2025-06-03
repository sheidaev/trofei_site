import { test, expect } from '@playwright/test';

test('Додавання декількох товарів в корзину', async ({ page }) => {
  // Відкриваємо сторінку трофею
  await page.goto('http://localhost:8080/product.php?id=2&added=1');

  // Встановлюємо кількість 2
  await page.fill('#qty', '2');

  // Знаходимо кнопку "Купити" і клікаємо на неї
  await page.click('#buy-button');

  // Перевіряємо, що біля іконки корзини з'явилась двійка
  const cartCounter = page.locator('#cart-counter');
  await expect(cartCounter).toHaveText('2');
}); 
