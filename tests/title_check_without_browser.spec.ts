import { test, expect } from '@playwright/test';

test('Перевірка тайтла головної сторінки без браузеру', async () => {
  // Робимо запит на головну сторінку без браузера, простим HTTP запитом
  const response = await fetch('http://localhost:8080/index.php');
  const html = await response.text();
  
  // Перевіряємо наявність тайтлу в HTML
  expect(html).toContain('<title>trofei.ua</title>');
});