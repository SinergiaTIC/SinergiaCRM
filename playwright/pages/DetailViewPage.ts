import { type Page } from "@playwright/test";
import { BasePage } from "./BasePage.js";
import { expectNoPhpErrors } from "../helpers/errors.js";
import { expect } from "@playwright/test";
import { t } from "../helpers/i18n.js";

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
