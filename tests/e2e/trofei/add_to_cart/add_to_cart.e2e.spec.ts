import { test, expect } from '@playwright/test';
import { TrofeiPage } from '../../PageObjects';

test('Додавання товару в корзину', async ({ page }) => {
  const Trofei = new TrofeiPage(page);
  
  // Відкриваємо сторінку трофею
  await Trofei.goto(2);

  // Додаємо товар в корзину
  await Trofei.addToCart();

  // Перевіряємо кількість в корзині
  await Trofei.expectCartCount(1);
}); 


