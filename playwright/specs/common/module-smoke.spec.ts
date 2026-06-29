import { test, type Page } from "@playwright/test";
import { ListViewPage } from "../../pages/ListViewPage.js";
import { ALL_MODULES, type ModuleEntry } from "../../helpers/generic/ModuleRegistry.js";
import { t } from "../../helpers/i18n.js";

test.describe("Module list view smoke tests", () => {
  for (const mod of ALL_MODULES) {
    test(`${t(mod.key)} — list view loads`, async ({ page }: { page: Page }) => {
      const list: ListViewPage = new ListViewPage(page, mod.key);
      await list.navigateTo();
      await list.waitForLoad();
    });
  }
});
