import { type Page } from "@playwright/test";
import { BasePage } from "#pages/BasePage";
import { expectNoPhpErrors } from "#helpers/errors";
import { fillRelateField } from "#helpers/generic/FieldHelpers";
import { expect } from "@playwright/test";

export class EditViewPage extends BasePage {
  readonly moduleName: string;

  constructor(page: Page, moduleName: string) {
    super(page);
    this.moduleName = moduleName;
  }

  async navigateToCreate(recordId: string | null = null): Promise<void> {
    const url = recordId
      ? `index.php?module=${this.moduleName}&action=EditView&record=${recordId}`
      : `index.php?module=${this.moduleName}&action=EditView`;
    await this.page.goto(url);
    await expectNoPhpErrors(this.page);
  }

  async fillField(label: string, value: string): Promise<void> {
    const field = this.page
      .getByLabel(label)
      .or(this.page.locator(`input[name^="${label}"]`));
    await field.fill(value);
  }

  async fillRelateField(
    fieldLabel: string,
    searchValue: string,
    recordId: string,
  ): Promise<void> {
    await fillRelateField(this.page, fieldLabel, searchValue, recordId);
  }

  async save(): Promise<void> {
    // WARN: flaky test, locator should not depend on the CSS ID.
    const btn = this.page
      .locator("#SAVE, #SAVE_HEADER, button:has-text('SAVE')")
      .first();
    await btn.click();
    if (this.page.url().includes("ShowDuplicates")) {
      // WARN: flaky test, locator should not depend on the CSS ID.
      await this.page
        .locator("button:has-text('Save'), input[value='Save']")
        .first()
        .click();
    }
    await this.page.waitForURL(/action=(DetailView|index)/);
    await expectNoPhpErrors(this.page);
  }
}
