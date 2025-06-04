import { Page, expect } from '@playwright/test';

export class TrofeiPage {
  readonly page: Page;

  // Селектори
  private readonly buyButton = '#buy-button';
  private readonly quantityInput = '#qty';
  private readonly cartCounter = '#cart-counter';

  constructor(page: Page) {
    this.page = page;
  }

  // Методи навігації
  async goto(productId: number) {
    await this.page.goto(`http://localhost:8080/product.php?id=${productId}&added=1`);
  }

  // Методи взаємодії
  async addToCart() {
    await this.page.click(this.buyButton);
  }

  async setQuantity(quantity: number) {
    await this.page.fill(this.quantityInput, quantity.toString());
  }

  // Методи перевірки
  async getCartCount() {
    const counter = this.page.locator(this.cartCounter);
    return await counter.textContent();
  }

  async expectCartCount(expectedCount: number) {
    const counter = this.page.locator(this.cartCounter);
    await counter.waitFor({ state: 'visible' });
    await expect(counter).toHaveText(expectedCount.toString());
  }
}



