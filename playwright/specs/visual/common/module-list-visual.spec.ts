import { test, expect, type Page } from "@playwright/test";
import { ListViewPage } from "#pages/ListViewPage";
import { ALL_MODULES } from "#helpers/generic/ModuleRegistry";
import { t } from "#helpers/i18n";

test.describe("Module list view visual baselines", () => {
  for (const mod of ALL_MODULES) {
    test(`${t(mod.key)} — list view matches baseline`, async ({ page }: { page: Page }) => {
      const list: ListViewPage = new ListViewPage(page, mod.key);
      await list.navigateTo();
      await expect(page).toHaveScreenshot(`${mod.key}-list.png`, {
        fullPage: true,
        mask: [
          page.getByText(
            /Temps de resposta|Tiempo de respuesta|Server response time/i,
          ),
        ],
      });
    });
  }
});
