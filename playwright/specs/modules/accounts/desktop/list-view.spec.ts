import { test, expect } from "@playwright/test";
import { ListViewPage } from "#pages/ListViewPage";
import { t } from "#helpers/i18n";

test("Sidebar displays module actions", async ({ page }) => {
  const list = new ListViewPage(page, "Accounts");
  await list.navigateTo();
  await list.waitForLoad();

  // WARN: link role may match sidebar + other duplicates; .first() targets the first occurrence.
  await expect(
    page.getByRole("link", { name: t("LNK_NEW_ACCOUNT") }).first(),
  ).toBeVisible();
  await expect(
    page.getByRole("link", { name: t("LNK_ACCOUNT_LIST") }).first(),
  ).toBeVisible();
});
