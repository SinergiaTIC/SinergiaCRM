import { test, expect } from "@playwright/test";
import { SuiteCRMApi } from "#helpers/api";
import { expectNoPhpErrors } from "#helpers/errors";
import { t } from "#helpers/i18n";

test.describe("Test group", () => {
  test("seed", async ({ page }) => {
    // Seed: navigates to the CRM home page (already authenticated via global setup)
    await page.goto("index.php?module=Home&action=index");
    await page.waitForURL(/module=Home&action=index/);
  });
});
