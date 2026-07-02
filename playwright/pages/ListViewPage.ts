import { type Page, type Locator } from "@playwright/test";
import { BasePage } from "#pages/BasePage";
import { expectNoPhpErrors } from "#helpers/errors";
import { expect } from "@playwright/test";
import { type ModuleKey } from "#helpers/generic/ModuleRegistry";

export class ListViewPage extends BasePage {
  readonly moduleKey: ModuleKey;
  readonly pageContent: Locator;

  constructor(page: Page, moduleKey: ModuleKey) {
    super(page);
    this.moduleKey = moduleKey;
    this.pageContent = page.locator(`#pagecontent[data-module="${moduleKey}"]`);
  }

  async navigateTo(action = "index", recordId: string | null = null): Promise<void> {
    await super.navigateTo(this.moduleKey, action, recordId);
  }

  async waitForLoad(): Promise<void> {
    await expect(this.pageContent).toBeVisible({ timeout: 10000 });

    // Check for visible content in priority order: data table, empty-state text, calendar grid.
    // Sequential checks avoid strict-mode violations when both a hidden table and
    // .listViewEmpty coexist in the DOM.
    //
    // First try: standard SuiteP data table wrapped in .list-view-rounded-corners.
    // WARN: scoped because multiple hidden `list view table-responsive` tables exist
    // at the root of #pagecontent for empty modules.
    const dataTable = this.pageContent.locator('.list-view-rounded-corners table[class*="list"]').first();
    // Second try: any list table (some modules like Releases use `list view` without wrapper).
    const anyTable = this.pageContent.locator('table[class*="list"]').first();
    // Fallback: empty-state text or Calendar container.
    // WARN: empty-state text is hardcoded in English. Tests run with TEST_LANG=en_us.
    const emptyMsg = this.page.getByText('You currently have no records saved.').first();
    const calendar = this.pageContent.locator('#calendarContainer');

    try {
      // Fast path: modules with data use the standard .list-view-rounded-corners layout
      await expect(dataTable).toBeVisible({ timeout: 5000 });
    } catch {
      // Second path: non-standard layouts (e.g. Releases) have a visible list table
      try {
        await expect(anyTable).toBeVisible({ timeout: 5000 });
      } catch {
        // Last resort: empty-state text or calendar for truly empty modules
        await expect(emptyMsg.or(calendar)).toBeVisible({ timeout: 5000 });
      }
    }

    await expectNoPhpErrors(this.page);
  }

  async selectRow(index: number): Promise<void> {
    // WARN: index-based checkbox selection is fragile — adding a row shifts indices.
    // Prefer selectRowByName() when the record name is known.
    await this.page.locator("input[name='mass[]']").nth(index).check();
  }

  async selectRowByName(name: string): Promise<void> {
    // WARN: .filter({ hasText }) does substring matching — a name like "Test" could
    // match multiple rows. Use unique names (e.g. timestamped) to avoid ambiguity.
    // WARN: Scoped to the row's name link (<a>) to be more precise than matching
    // arbitrary row text. Fragile if the row structure changes significantly.
    await this.page
      .locator("tr", { has: this.page.locator("a", { hasText: name }) })
      .locator("input[name='mass[]']")
      .check();
  }

  async clickAction(name: string): Promise<void> {
    // WARN: The entire action is performed via page.evaluate() because:
    // 1. SuiteP action link containers have class "hide" on load
    // 2. Subnav dropdowns have inline display:none set by JS
    // 3. Action link text uses &amp;nbsp; (non-breaking space, \u00a0) in
    //    elements like "Mass&nbsp;Update", which breaks getByRole text matching
    // 4. Even with force:true, Playwright cannot click elements inside a parent
    //    with display:none because they have no bounding box
    // If CSS class names or inline styles change, this silently breaks.
    await this.page.evaluate((actionName) => {
      document.querySelectorAll<HTMLElement>("#actionLinkTop, #actionLinkBottom").forEach((el) => {
        el.classList.remove("hide");
        el.querySelectorAll<HTMLElement>(".subnav").forEach((subnav) => {
          subnav.style.display = "block";
        });
      });
      document.querySelectorAll<HTMLElement>(".selectActionsDisabled").forEach((el) => {
        el.style.display = "none";
      });
      // Find the action link by normalized text content and click it directly
      const links = document.querySelectorAll<HTMLAnchorElement>("#actionLinkTop a, #actionLinkBottom a");
      for (const link of links) {
        if (link.textContent?.trim().replace(/\s+/g, " ") === actionName) {
          link.click();
          return;
        }
      }
    }, name);
  }
}
