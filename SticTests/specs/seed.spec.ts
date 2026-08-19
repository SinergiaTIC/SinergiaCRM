import { test, expect } from "@playwright/test";
import { SuiteCRMApi } from "#helpers/api";
import { expectNoPhpErrors } from "#helpers/errors";
import { t } from "#helpers/i18n";

test.describe("Test group", () => {
  test("seed", async ({ page }) => {
    await page.goto("index.php?module=Home&action=index");
  });
});
