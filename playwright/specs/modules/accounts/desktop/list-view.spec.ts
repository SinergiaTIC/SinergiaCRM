import { test, expect } from "@playwright/test";
import { ListViewPage } from "#pages/ListViewPage";

test("Sidebar displays module actions", async ({ page }) => {
  const list = new ListViewPage(page, "Accounts");
  await list.navigateTo();
  await list.waitForContentLoad();

  const createAccountLabel = await page.evaluate(() =>
    (window as any).SUGAR?.language?.get("Accounts", "LNK_NEW_ACCOUNT"),
  );
  const viewAccountsLabel = await page.evaluate(() =>
    (window as any).SUGAR?.language?.get("Accounts", "LNK_ACCOUNT_LIST"),
  );

  const sidebar = page.locator("#sidebar_container");
  await expect(sidebar.getByText(createAccountLabel)).toBeVisible();
  await expect(sidebar.getByText(viewAccountsLabel)).toBeVisible();
});
