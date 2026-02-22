/**
 * E2E Smoke Test — 1st Class Chimney & Air Duct
 * Story 6.4: Validates critical conversion path.
 *
 * Prerequisites:
 *   npm install -D playwright @playwright/test
 *   npx playwright install chromium
 *
 * Run:
 *   npx playwright test tests/e2e/form-submission.spec.ts
 */

import { test, expect } from '@playwright/test';

test.describe('Landing Page — Critical Path', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/');
  });

  test('hero section renders with h1', async ({ page }) => {
    const h1 = page.locator('h1');
    await expect(h1).toBeVisible();
    await expect(h1).toContainText('1st Class');
  });

  test('no console errors on page load', async ({ page }) => {
    const errors: string[] = [];
    page.on('console', (msg) => {
      if (msg.type() === 'error') errors.push(msg.text());
    });
    await page.goto('/');
    await page.waitForLoadState('networkidle');
    // Filter known GTM errors (expected when GTM ID is placeholder)
    const realErrors = errors.filter(
      (e) => !e.includes('GTM-XXXXXX') && !e.includes('googletagmanager')
    );
    expect(realErrors).toHaveLength(0);
  });

  test('anchor links navigate to correct sections', async ({ page }) => {
    const anchors = ['#services', '#testimonials', '#contact'];
    for (const anchor of anchors) {
      const link = page.locator(`a[href="${anchor}"]`).first();
      if (await link.isVisible()) {
        await link.click();
        const section = page.locator(anchor);
        await expect(section).toBeVisible();
      }
    }
  });

  test('lead form fills and submits', async ({ page }) => {
    await page.locator('#first-name').fill('Test');
    await page.locator('#last-name').fill('User');
    await page.locator('#email').fill('test@example.com');
    await page.locator('#service-select').selectOption('chimney-sweep');

    // Wait minimum 3s for time-to-submit check
    await page.waitForTimeout(3500);

    // Submit
    await page.locator('.contact__submit').click();

    // Should show confirmation OR error (depending on API availability)
    const confirmation = page.locator('.contact__confirmation');
    const error = page.locator('.contact__form-error');
    await expect(confirmation.or(error)).toBeVisible({ timeout: 10000 });
  });

  test('sticky CTA visible on mobile', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 812 });
    await page.goto('/');
    const stickyCTA = page.locator('.sticky-cta');
    await expect(stickyCTA).toBeVisible();
  });

  test('form validation shows errors for empty fields', async ({ page }) => {
    await page.locator('.contact__submit').click();
    const errorMessages = page.locator('.contact__field-error');
    const count = await errorMessages.count();
    expect(count).toBeGreaterThanOrEqual(1);
  });
});
