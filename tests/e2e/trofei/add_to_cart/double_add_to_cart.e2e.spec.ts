import { test, expect } from '@playwright/test';
import { TrofeiPage } from '../../PageObjects';

test('Додавання товару в корзину якщо там вже є один', async ({ page }) => {
  const Trofei = new TrofeiPage(page);
  
  // Відкриваємо сторінку трофею
  await Trofei.goto(2);

  // Додаємо товар в корзину двічі
  await Trofei.addToCart();

  // Закриваємо попап
  await page.click('#close-cart-popup');
  await expect(page.locator('#cart-popup')).not.toBeVisible();

  await Trofei.addToCart();

  // Перевіряємо, що попап корзини відкрився
  await expect(page.locator('#cart-popup')).toBeVisible();

  // Перевіряємо, що в попапі відображається правильна сума (наприклад, якщо ціна товару 340 грн, то 2*340=680)
  await expect(page.locator('#cart-popup-content')).toContainText('Всього: 700.00 грн');
});