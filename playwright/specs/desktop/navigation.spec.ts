import { test, expect, type Page } from "@playwright/test";
import { DashboardPage } from "../../pages/DashboardPage.js";
import { t } from "../../helpers/i18n.js";

test.describe("Desktop navigation", () => {
  test.beforeEach(async ({ page }: { page: Page }) => {
    await page.goto("index.php?module=Home&action=index");
  });

  test("sidebar is always visible", async ({ page }: { page: Page }) => {
    await expect(page.getByRole("navigation")).toBeVisible();
  });

  test("all module group links are visible in sidebar", async ({ page }: { page: Page }) => {
    const groups: string[] = [
      "LBL_GROUPTAB_MAIN",
      "LBL_GROUPTAB_ACTIVITIES",
      "LBL_GROUPTAB_CAMPAIGNS",
      "LBL_GROUPTAB_ECONOMY",
      "LBL_GROUPTAB_DIRECTCARE",
      "LBL_GROUPTAB_EVENTS",
      "LBL_GROUPTAB_BOOKINGS",
    ];
    for (const key of groups) {
      await expect(page.getByRole("link", { name: t(key) })).toBeVisible();
    }
  });

  test("shows admin action link in sidebar", async ({ page }: { page: Page }) => {
    await expect(
      page.getByRole("link", { name: t("Administration") }),
    ).toBeVisible();
  });

  test("user menu contains logout option", async ({ page }: { page: Page }) => {
    const dashboard: DashboardPage = new DashboardPage(page);
    await dashboard.userMenuButton.click();
    await expect(dashboard.logoutItem).toBeVisible();
  });
});
