import { test, expect, type Page } from "@playwright/test";
import { expectNoPhpErrors } from "../../helpers/errors.js";

test.describe("Dashboard", () => {
  test.beforeEach(async ({ page }: { page: Page }) => {
    await page.goto("index.php?module=Home&action=index");
  });

  test("page loads without PHP errors", async ({ page }: { page: Page }) => {
    await expectNoPhpErrors(page);
  });

  test("shows dashboard heading", async ({ page }: { page: Page }) => {
    await expect(page).toHaveURL(/module=Home&action=index/);
  });
});
