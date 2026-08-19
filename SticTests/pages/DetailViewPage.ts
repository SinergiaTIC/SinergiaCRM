import { type Page } from "@playwright/test";
import { BasePage } from "#pages/BasePage";
import { expectNoPhpErrors } from "#helpers/errors";
import { expect } from "@playwright/test";
import { t } from "#helpers/i18n";

export class DetailViewPage extends BasePage {
  readonly moduleName: string;

  constructor(page: Page, moduleName: string) {
    super(page);
    this.moduleName = moduleName;
  }

  async waitForLoad(): Promise<void> {
    await expect(this.page).toHaveURL(/action=DetailView/);
    await expectNoPhpErrors(this.page);
  }

  async clickAction(name: string): Promise<void> {
    await this.page.getByRole("link", { name }).click();
  }

  async edit(): Promise<void> {
    await this.clickAction(t("LBL_EDIT_BUTTON"));
  }
}
