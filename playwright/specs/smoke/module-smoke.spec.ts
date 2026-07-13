import { test, type Page } from "@playwright/test";
import { ListViewPage } from "#pages/ListViewPage";
import { CalendarPage } from "#pages/CalendarPage";
import { ALL_MODULES, type ModuleEntry } from "#helpers/generic/ModuleRegistry";
import { t } from "#helpers/i18n";

const listViewModules: ModuleEntry[] = ALL_MODULES.filter(
  (mod) => mod.type === "listView",
);
const calendarModules: ModuleEntry[] = ALL_MODULES.filter(
  (mod) => mod.type === "calendar",
);
const emptyModules: ModuleEntry[] = ALL_MODULES.filter(
  (mod) => mod.type === "empty",
);

test.describe("Module smoke tests", () => {
  test.describe("ListView", () => {
    for (const mod of listViewModules) {
      test(`${t(mod.key)} — view loads`, async ({ page }: { page: Page }) => {
        const listView: ListViewPage = new ListViewPage(page, mod.key);
        await listView.navigateTo();
        await listView.waitForContentLoad();
      });
    }
  });

  test.describe("Calendar", () => {
    for (const mod of calendarModules) {
      test(`${t(mod.key)} — view loads`, async ({ page }: { page: Page }) => {
        const calendar: CalendarPage = new CalendarPage(page, mod.key);
        await calendar.navigateTo();
        await calendar.waitForContentLoad();
      });
    }
  });

  test.describe("Empty", () => {
    for (const mod of emptyModules) {
      test.fixme(`${t(mod.key)} — view loads`, async ({
        page,
      }: {
        page: Page;
      }) => {
        const listView: ListViewPage = new ListViewPage(page, mod.key);
        await listView.navigateTo();
        await listView.waitForContentLoad();
      });
    }
  });
});
