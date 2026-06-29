import { type Page } from "@playwright/test";
import { t } from "../helpers/i18n.js";

export class FilterModalPage {
  constructor(private readonly page: Page) {}

  async setQuickFilter(text: string): Promise<void> {
    await this.page.getByPlaceholder(/cerca|buscar|search/i).fill(text);
    await this.page
      .getByRole("button", { name: t("LBL_SEARCH_BUTTON_LABEL") })
      .click();
  }

  async clear(): Promise<void> {
    await this.page
      .getByRole("button", { name: t("LBL_CLEAR_BUTTON_LABEL") })
      .click();
  }
}
