import { test, expect } from "@playwright/test";

test.use({ storageState: { cookies: [], origins: [] } as any });

test.describe("Visual: Login page", () => {
  test.beforeEach(async ({ page }) => {
    await page.goto("index.php?action=Login&module=Users");
  });

  test("login page matches baseline", async ({ page }) => {
    await expect(page).toHaveScreenshot("login-page.png", {
      fullPage: true,
    });
  });
});
