import { test, expect } from '@playwright/test';
import { TrofeiPage } from '../../../PageObjects';
import fixtures from './fixture.json';

// Отримуємо тестові дані з фікстур
const testCases = fixtures.addToCart;

// Створюємо тести на основі фікстур
for (const testCase of testCases) {
  test(testCase.name, async ({ page }) => {
    const trofei = new TrofeiPage(page);
    
    // Відкриваємо сторінку трофею з вказаним ID
    await trofei.goto(testCase.productId);

    // Встановлюємо кількість
    await trofei.setQuantity(testCase.quantity);

    // Додаємо товар в корзину
    await trofei.addToCart();

    // Перевіряємо очікувану кількість в корзині
    await trofei.expectCartCount(testCase.expectedCartCount);
  });
} 
