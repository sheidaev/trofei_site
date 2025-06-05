import { test, expect } from '@playwright/test';
import { TrofeiPage } from '../../../PageObjects';
import fixtures from './fixture.json';
import { DatabaseHelper } from '../../../../helpers/db';

const db = new DatabaseHelper();
const testCases = fixtures.testCases;

test.beforeEach(async () => {
  await db.connect();
});

test.afterEach(async () => {
  await db.rollback();
  await db.disconnect();
});

for (const testCase of testCases) {
  test(testCase.testcase_data.name, async ({ page }) => {
    const trofei = new TrofeiPage(page);
    
    // Створюємо тестові дані та отримуємо їх ID
    const insertedIds = await db.insertFixtureData({ db: testCase.db });
    
    // Відкриваємо сторінку трофею з ID першого створеного продукту
    await trofei.goto(insertedIds.products[0]);

    // Встановлюємо кількість
    await trofei.setQuantity(testCase.testcase_data.quantity);

    // Додаємо товар в корзину
    await trofei.addToCart();

    // Перевіряємо очікувану кількість в корзині
    await trofei.expectCartCount(testCase.testcase_data.expectedCartCount);
  });
} 


