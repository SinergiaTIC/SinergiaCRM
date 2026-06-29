import { test, expect, type Page } from "@playwright/test";
import { BasePage } from "../../pages/BasePage.js";
import { t } from "../../helpers/i18n.js";

test.describe("Mass actions", () => {
  test.beforeEach(async ({ page }: { page: Page }) => {
    const base: BasePage = new BasePage(page);
    await base.navigateTo("Accounts");
  });

  test.fixme("mass update button is visible", async ({ page }: { page: Page }) => {
    await expect(
      page.getByRole("button", { name: t("LBL_MASS_UPDATE") }),
    ).toBeVisible();
  });
});
