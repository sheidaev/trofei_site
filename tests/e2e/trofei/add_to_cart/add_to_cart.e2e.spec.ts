import { test, expect } from '@playwright/test';
import { TrofeiPage } from '../../PageObjects';

test('Додавання товару в корзину', async ({ page }) => {
  const Trofei = new TrofeiPage(page);
  
  // Відкриваємо сторінку трофею
  await Trofei.goto(2);

  // Додаємо товар в корзину
  await Trofei.addToCart();

  // Перевіряємо, що попап корзини відкрився
  await expect(page.locator('#cart-popup')).toBeVisible();

  // Перевіряємо, що в попапі відображається правильна сума (наприклад, якщо ціна товару 340 грн)
  await expect(page.locator('#cart-popup-content')).toContainText('Всього: 350.00 грн');
}); 


