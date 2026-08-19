import { test, expect } from "@playwright/test";
import { EditViewPage } from "#pages/EditViewPage";
import { DetailViewPage } from "#pages/DetailViewPage";
import { SuiteCRMApi } from "#helpers/api";
import { expectNoPhpErrors } from "#helpers/errors";
import { t } from "#helpers/i18n";

const MODULE = "Accounts";

test.describe("Account creation", () => {
  test("Create account with required name only", async ({ page }) => {
    const name = `Test-Account-${Date.now()}`;

    const edit = new EditViewPage(page, MODULE);
    await edit.navigateToCreate();
    await edit.fillField("name", name);
    await edit.save();

    const detail = new DetailViewPage(page, MODULE);
    await detail.waitForLoad();
    await expect(page.getByRole("heading", { name })).toBeVisible();

    const url = new URL(page.url());
    const recordId = url.searchParams.get("record");
    if (recordId) {
      const api = new SuiteCRMApi();
      await api.login();
      await api.deleteEntry(MODULE, recordId);
    }
  });

  test("Create account with all fields", async ({ page }) => {
    const name = `Test-Account-All-${Date.now()}`;
    const phone = "555-3000";
    const website = "https://example.com";

    const edit = new EditViewPage(page, MODULE);
    await edit.navigateToCreate();
    await edit.fillField("name", name);
    await edit.fillField("phone_office", phone);
    await edit.fillField("website", website);
    await edit.fillField("description", "Test account created by e2e");
    await edit.save();

    const detail = new DetailViewPage(page, MODULE);
    await detail.waitForLoad();
    await expect(page.getByRole("heading", { name })).toBeVisible();
    // WARN: `getByText` may match unintended elements if the text is not unique on screen.
    await expect(page.getByText(phone)).toBeVisible();
    await expect(page.getByText(website)).toBeVisible();

    const url = new URL(page.url());
    const recordId = url.searchParams.get("record");
    if (recordId) {
      const api = new SuiteCRMApi();
      await api.login();
      await api.deleteEntry(MODULE, recordId);
    }
  });

  test("Cancel creation returns to list view", async ({ page }) => {
    const edit = new EditViewPage(page, MODULE);
    await edit.navigateToCreate();

    // WARN: .first() needed because #CANCEL_HEADER and #CANCEL_FOOTER coexist on the page.
    await page
      .getByRole("button", { name: t("LBL_CANCEL_BUTTON_LABEL") })
      .first()
      .click();
    await page.waitForURL(/action=index/);
    await expectNoPhpErrors(page);
  });
});
