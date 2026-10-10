import { test, expect, type Page } from "@playwright/test";
import { DashboardPage } from "#pages/DashboardPage";
import { expectNoPhpErrors } from "#helpers/errors";
import { t } from "#helpers/i18n";

test.describe("Dashboard sidebar", () => {
  let dashboard: DashboardPage;

  test.beforeEach(async ({ page }: { page: Page }) => {
    await page.goto("index.php?module=Home&action=index");
    dashboard = new DashboardPage(page);
  });

  test.afterEach(async ({ page }: { page: Page }) => {
    await expectNoPhpErrors(page);
  });

  test("Right sidebar shows Recently Viewed section", async ({
    page,
  }: {
    page: Page;
  }) => {
    // WARN: sidebar uses .sidebar CSS class which is fragile if the layout changes
    await expect(dashboard.sidebar).toBeVisible();

    // WARN: heading is matched by h2 text content ("LBL_LAST_VIEWED" i18n key) which
    // may differ across languages; uppercase rendering via CSS text-transform may also
    // cause exact-match failures
    await expect(dashboard.recentlyViewedHeading).toBeVisible();

    // 4. Assert at least one record link is present in the sidebar
    // WARN: using a generic <a> selector because the sidebar links do not have unique IDs or classes
    const sidebarLinks = dashboard.sidebar.locator("a");
    await expect(sidebarLinks.first()).toBeAttached();
    const linkCount = await sidebarLinks.count();
    expect(linkCount).toBeGreaterThan(0);
  });

  test("Right sidebar shows Admin actions section", async ({
    page,
  }: {
    page: Page;
  }) => {
    // WARN: sidebar uses .sidebar CSS class — fragile if the layout changes.
    await expect(dashboard.adminActionsHeading).toBeVisible();

    // WARN: relies on sidebar being a .sidebar CSS class. #admin_link is NOT
    // in the sidebar (it's in top nav), so we check the actual sidebar links.
    await expect(
      dashboard.sidebar.getByRole("link", { name: t("Administration") }),
    ).toBeVisible();
    await expect(
      dashboard.sidebar.getByRole("link", { name: t("Studio") }),
    ).toBeVisible();
  });
});
