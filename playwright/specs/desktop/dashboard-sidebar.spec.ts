import { test, expect, type Page } from "@playwright/test";
import { DashboardPage } from "#pages/DashboardPage";
import { expectNoPhpErrors } from "#helpers/errors";

test.describe("Dashboard sidebar", () => {
  let dashboard: DashboardPage;

  test.beforeEach(async ({ page }: { page: Page }) => {
    await page.goto("index.php?module=Home&action=index");
    dashboard = new DashboardPage(page);
  });

  test.afterEach(async ({ page }: { page: Page }) => {
    await expectNoPhpErrors(page);
  });

  // TC08 — Right sidebar shows Recently Viewed section
  test("Right sidebar shows Recently Viewed section", async ({ page }: { page: Page }) => {
    // 1. Navigate to index.php?module=Home&action=index (done in beforeEach)
    // 2. Verify DashboardPage.sidebar is visible
    // WARN: sidebar uses .sidebar CSS class which is fragile if the layout changes
    await expect(dashboard.sidebar).toBeVisible();

    // 3. Verify DashboardPage.recentlyViewedHeading is visible
    // WARN: heading is matched by h2 text content ("LBL_LAST_VIEWED" i18n key) which
    // may differ across languages; uppercase rendering via CSS text-transform may also
    // cause exact-match failures
    await expect(dashboard.recentlyViewedHeading).toBeVisible();

    // 4. Assert at least one record link is present in the sidebar
    const sidebarLinks = dashboard.sidebar.locator("a");
    await expect(sidebarLinks.first()).toBeAttached();
    const linkCount = await sidebarLinks.count();
    expect(linkCount).toBeGreaterThan(0);
  });

  // TC09 — Right sidebar shows Admin actions section
  test("Right sidebar shows Admin actions section", async ({ page }: { page: Page }) => {
    // 1. Navigate to index.php?module=Home&action=index (done in beforeEach)
    // 2. Verify DashboardPage.adminActionsHeading is visible
    // WARN: heading is matched by h2 text content "Admin actions" — this is hardcoded
    // text and may change if the CRM is translated or the sidebar is restructured
    await expect(dashboard.adminActionsHeading).toBeVisible();

    // 3. Verify the "Administration" link exists in the sidebar
    // WARN: relies on sidebar being a .sidebar CSS class; Administration link text
    // may vary by language (i18n). #admin_link is NOT in the sidebar (it's in top nav),
    // so we check the actual sidebar links directly.
    await expect(
      dashboard.sidebar.getByRole("link", { name: "Administration" }),
    ).toBeVisible();
    await expect(
      dashboard.sidebar.getByRole("link", { name: "Studio" }),
    ).toBeVisible();
  });
});
