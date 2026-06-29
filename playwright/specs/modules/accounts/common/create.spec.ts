import { test, expect } from "@playwright/test";
import { EditViewPage } from "../../../../pages/EditViewPage.js";
import { DetailViewPage } from "../../../../pages/DetailViewPage.js";
import { SuiteCRMApi } from "../../../../helpers/api.js";

const MODULE = "Accounts";

test("create a new account", async ({ page }) => {
  const name = `Test Account ${Date.now()}`;

  const edit = new EditViewPage(page, MODULE);
  await edit.navigateToCreate();
  await edit.fillField("name", name);
  await edit.save();

  const detail = new DetailViewPage(page, MODULE);
  await detail.waitForLoad();
  const heading = page.getByRole("heading", { name: "Test Account" });
  await expect(heading).toBeVisible();

  const url = new URL(page.url());
  const recordId = url.searchParams.get("record");
  if (recordId) {
    const api = new SuiteCRMApi();
    await api.login();
    await api.deleteEntry(MODULE, recordId);
  }
});
