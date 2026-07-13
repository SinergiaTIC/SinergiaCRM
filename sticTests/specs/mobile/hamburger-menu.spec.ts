import { test, expect, type Page } from "@playwright/test";

test.describe("Mobile navigation", () => {
  test.beforeEach(async ({ page }: { page: Page }) => {
    await page.goto("index.php?module=Home&action=index");
  });

  test("hamburger menu toggle exists and responds to click", async ({
    page,
  }: { page: Page }) => {
    // WARN: hamburger toggle has no id, aria-label, or data-testid. .first()
    // is fragile — if a button is added before it in <nav>, the toggle shifts.
    const toggle = page.getByRole("navigation").locator("button").first();
    await expect(toggle).toBeVisible();
    await toggle.click();
    await expect(toggle).toBeVisible();
  });
});
