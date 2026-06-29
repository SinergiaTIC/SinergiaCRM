import { type Page, type Locator } from "@playwright/test";
import { BasePage } from "./BasePage.js";
import { expectNoPhpErrors } from "../helpers/errors.js";
import { expect } from "@playwright/test";
import { type ModuleKey } from "../helpers/generic/ModuleRegistry.js";

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
    await expectNoPhpErrors(this.page);
  }

  async selectRow(index: number): Promise<void> {
    // WARN: index-based checkbox selection is fragile — adding a row shifts indices.
    // No stable selector available for list view checkboxes.
    await this.page.locator("input[type='checkbox']").nth(index).check();
  }

  async clickAction(name: string): Promise<void> {
    // WARN: page.evaluate() removes the 'hide' CSS class because Playwright cannot
    // click hidden elements. If the class name changes, the action silently stops working.
    await this.page.evaluate(() => {
      document
        .querySelectorAll("#actionLinkTop, #actionLinkBottom")
        .forEach((el) => (el as HTMLElement).classList.remove("hide"));
    });
    await this.page.getByRole("link", { name }).click();
  }
}
