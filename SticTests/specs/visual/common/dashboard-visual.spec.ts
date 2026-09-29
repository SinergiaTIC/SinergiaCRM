import { test, expect, type Page } from "@playwright/test";

test.describe("Visual: Dashboard", () => {
  test.beforeEach(async ({ page }: { page: Page }) => {
    await page.goto("index.php?module=Home&action=index");
  });

  test("dashboard home matches baseline", async ({ page }: { page: Page }) => {
    await expect(page).toHaveScreenshot("dashboard-home.png", {
      fullPage: true,
      mask: [
        page.getByText(
          /Temps de resposta|Tiempo de respuesta|Server response time/i,
        ),
        // WARN: locator("..") parent traversal breaks if the heading's container
        // element changes after a theme update. The mask silently stops matching.
        page
          .locator("h2")
          .filter({ hasText: /Recents|Recientes|Recent/ })
          .locator(".."),
        // WARN: masking parent iframe element instead of frame content — iframe
        // may fail to load or its external content (sinergiatic) may change.
        page.locator('iframe[src*="sinergiatic"]'),
      ],
    });
  });
});
