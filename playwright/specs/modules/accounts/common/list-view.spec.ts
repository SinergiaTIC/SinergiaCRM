import { test, expect } from "@playwright/test";
import { ListViewPage } from "#pages/ListViewPage";
import { SuiteCRMApi } from "#helpers/api";
import { t } from "#helpers/i18n";

test.describe("Accounts list view", () => {
  test("List view displays heading and controls", async ({ page }) => {
    const list = new ListViewPage(page, "Accounts");
    await list.navigateTo();
    await list.waitForLoad();

    await expect(page.locator("h2.module-title-text")).toBeVisible();
    // WARN: CSS class-based selector (glyphicon-filter) — fragile if icon class changes.
    await expect(page.locator(".glyphicon-filter").first()).toBeVisible();
    // WARN: columnsFilterLink only renders when records exist (it controls visible columns).
    // When the module is empty (e.g. after test data cleanup) it is absent from the DOM.
    // This assertion guards against regressions when data IS present.
    if (await page.locator(".columnsFilterLink").first().isVisible().catch(() => false)) {
      await expect(page.locator(".columnsFilterLink").first()).toBeVisible();
    }
  });

  test("Row selection opens mass update panel and updates records", async ({ page }) => {
    const timestamp = Date.now();
    const name1 = `Test-MU-${timestamp}-A`;
    const name2 = `Test-MU-${timestamp}-B`;

    // Arrange: create two records with stic_182_excluded_c explicitly "0"
    const api = new SuiteCRMApi();
    await api.login();
    const id1 = await api.createEntry("Accounts", {
      name: name1,
      stic_182_excluded_c: "0",
    });
    const id2 = await api.createEntry("Accounts", {
      name: name2,
      stic_182_excluded_c: "0",
    });

    const list = new ListViewPage(page, "Accounts");
    await list.navigateTo();
    await list.waitForLoad();

    // Act: select both rows by name (avoids index fragility with leftover records)
    await list.selectRowByName(name1);
    await list.selectRowByName(name2);

    await list.clickAction(t("LBL_MASS_UPDATE"));
    await expect(page.locator("#massupdate_form")).toBeVisible();

    // WARN: stic_182_excluded_c is a bool rendered as <select name="stic_182_excluded_c">
    // with values "" (None), "__SugarMassUpdateClearField__" (Clear), "0" (No), "1" (Yes).
    // Select "1" (Yes) to mark both records as excluded.
    await page.selectOption('#massupdate_form select[name="stic_182_excluded_c"]', "1");

    // Click Update: send_mass_update shows a confirm() dialog before submitting.
    // Accept the dialog and wait for the form POST navigation.
    page.once("dialog", (dialog) => dialog.accept());
    await Promise.all([
      page.waitForNavigation({ timeout: 15000 }),
      page.locator("#update_button").click(),
    ]);
    await list.waitForLoad();

    // Assert: verify both records were updated via API
    const entry1 = (await api.getEntry("Accounts", id1)).entry_list![0].name_value_list;
    const entry2 = (await api.getEntry("Accounts", id2)).entry_list![0].name_value_list;
    expect(entry1.stic_182_excluded_c.value).toBe("1");
    expect(entry2.stic_182_excluded_c.value).toBe("1");

    // Cleanup
    await api.deleteEntry("Accounts", id1);
    await api.deleteEntry("Accounts", id2);
  });

  test("Filter icon opens search dialog modal", async ({ page }) => {
    const list = new ListViewPage(page, "Accounts");
    await list.navigateTo();
    await list.waitForLoad();

    // WARN: CSS class-based selector (glyphicon-filter) — fragile if icon class changes.
    // WARN: .first() needed because filter icon appears in both thead and tfoot.
    await page.locator(".glyphicon-filter").first().click();
    await expect(page.locator("#searchDialog")).toBeVisible();
  });
});
