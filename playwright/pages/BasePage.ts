import { type Page } from "@playwright/test";
import { expect } from "@playwright/test";
import { t } from "#helpers/i18n";
import { expectNoPhpErrors } from "#helpers/errors";

export class BasePage {
  constructor(protected readonly page: Page) {}

  async logout(): Promise<void> {
    // WARN: flaky test, locator should not depende on the CSS class.
    await this.page.locator("button.user-menu-button").click();
    await this.page.getByRole("menuitem", { name: t("LBL_LOGOUT") }).click();
    await expect(this.page).toHaveURL(/action=Login/);
  }

  async navigateTo(
    module: string,
    action = "index",
    recordId: string | null = null,
  ): Promise<void> {
    const url = recordId
      ? `index.php?module=${module}&action=${action}&record=${recordId}`
      : `index.php?module=${module}&action=${action}`;
    await this.page.goto(url);
    await expectNoPhpErrors(this.page);
  }

  async waitForListView(headingLabelKey: string): Promise<void> {
    await expect(
      this.page.locator("h2").filter({ hasText: t(headingLabelKey) }),
    ).toBeVisible({ timeout: 10000 });
  }

  async waitForDetailView(recordName: string): Promise<void> {
    await expect(this.page).toHaveURL(/action=DetailView/);
    await expect(
      this.page.getByRole("heading").filter({ hasText: recordName }),
    ).toBeVisible({ timeout: 10000 });
  }
}
