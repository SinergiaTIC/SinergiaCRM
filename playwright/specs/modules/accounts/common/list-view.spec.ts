import { test, expect } from "@playwright/test";
import { ListViewPage } from "#pages/ListViewPage";
import { SuiteCRMApi } from "#helpers/api";
import { t } from "#helpers/i18n";
import { createRecord } from "../helpers/createRecord";

test.describe("Accounts list view", () => {
  test("List view displays heading, columns, and controls", async ({
    page,
  }) => {
    let recordId = "";

    await test.step("seed a record via UI so columnsFilterLink is present", async () => {
      const seedName = `Test-LV-${Date.now()}`;
      recordId = await createRecord(page, seedName);
    });

    await test.step("verify list view elements are present", async () => {
      const list = new ListViewPage(page, "Accounts");
      await list.navigateTo();
      await list.waitForContentLoad();

      await expect(
        page.getByRole("heading", { name: t("LBL_ACCOUNTS") }),
      ).toBeVisible();
      await expect(
        page.getByRole("columnheader", { name: t("LBL_LIST_NAME") }),
      ).toBeVisible();
      // WARN: CSS class-based selector (glyphicon-filter) — fragile if icon class changes.
      await expect(page.locator(".glyphicon-filter").first()).toBeVisible();
      await expect(page.locator(".columnsFilterLink").first()).toBeVisible();
    });

    await test.step("cleanup seed record", async () => {
      const api = new SuiteCRMApi();
      await api.login();
      await api.deleteEntry("Accounts", recordId);
    });
  });

  test("Row selection opens mass update panel and updates records", async ({
    page,
  }) => {
    let id1 = "";
    let id2 = "";
    const name1 = `Test-MU-${Date.now()}-A`;
    const name2 = `Test-MU-${Date.now()}-B`;

    await test.step("create two test records via UI", async () => {
      // WARN: stic_182_excluded_c ("Exclude from 182 Model") is a checkbox in
      // EditView (default unchecked = "0") but a <select> in the mass update
      // form. Unchecked is the correct starting state for this test.
      id1 = await createRecord(page, name1);
      id2 = await createRecord(page, name2);
    });

    await test.step("navigate to list view and select both rows", async () => {
      const list = new ListViewPage(page, "Accounts");
      await list.navigateTo();
      await list.waitForContentLoad();
      await list.selectRowByName(name1);
      await list.selectRowByName(name2);
    });

    await test.step("apply mass update: set stic_182_excluded_c to Yes", async () => {
      const list = new ListViewPage(page, "Accounts");
      await list.clickAction(t("LBL_MASS_UPDATE"));
      await expect(page.locator("#massupdate_form")).toBeVisible();
      // WARN: stic_182_excluded_c is a bool rendered as <select> with values
      // "" (None), "__SugarMassUpdateClearField__" (Clear), "0" (No), "1" (Yes).
      await page.selectOption(
        '#massupdate_form select[name="stic_182_excluded_c"]',
        "1",
      );
      // send_mass_update shows a confirm() dialog before submitting
      page.once("dialog", (dialog) => dialog.accept());
      await page.locator("#update_button").click();
      await page.waitForURL(/module=Accounts&action=index/, { timeout: 15000 });
      await list.waitForContentLoad();
    });

    await test.step("verify both records were updated via API", async () => {
      const api = new SuiteCRMApi();
      await api.login();
      const entry1 = (await api.getEntry("Accounts", id1)).entry_list![0]
        .name_value_list;
      const entry2 = (await api.getEntry("Accounts", id2)).entry_list![0]
        .name_value_list;
      expect(entry1.stic_182_excluded_c.value).toBe("1");
      expect(entry2.stic_182_excluded_c.value).toBe("1");
    });

    await test.step("cleanup test records", async () => {
      const api = new SuiteCRMApi();
      await api.login();
      await api.deleteEntry("Accounts", id1);
      await api.deleteEntry("Accounts", id2);
    });
  });

  test("Filter icon opens search dialog modal", async ({ page }) => {
    await test.step("navigate to Accounts list view", async () => {
      const list = new ListViewPage(page, "Accounts");
      await list.navigateTo();
      await list.waitForContentLoad();
    });

    await test.step("click filter icon and verify search dialog appears", async () => {
      // WARN: CSS class-based selector (glyphicon-filter) — fragile if icon class changes.
      // WARN: .first() needed because filter icon appears in both thead and tfoot.
      await page.locator(".glyphicon-filter").first().click();
      await expect(page.locator("#searchDialog")).toBeVisible();
    });
  });
});
