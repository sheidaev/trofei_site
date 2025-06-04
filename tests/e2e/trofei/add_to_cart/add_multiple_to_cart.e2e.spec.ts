import { test, expect } from '@playwright/test';
import { TrofeiPage } from '../../PageObjects';

test('Додавання декількох товарів в корзину', async ({ page }) => {
  const Trofei = new TrofeiPage(page);
  
  // Відкриваємо сторінку трофею
  await Trofei.goto(2);

  // Встановлюємо кількість 2
  await Trofei.setQuantity(2);

  // Додаємо товар в корзину
  await Trofei.addToCart();

  // Перевіряємо кількість в корзині
  await Trofei.expectCartCount(2);

}); 
