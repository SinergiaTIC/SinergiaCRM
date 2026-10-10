import { type Page } from "@playwright/test";
import { expect } from "@playwright/test";
import { t } from "#helpers/i18n";
import { expectNoPhpErrors } from "#helpers/errors";

export class BasePage {
  constructor(protected readonly page: Page) {}

  async logout(): Promise<void> {
    // WARN: SuiteP renders 3 duplicate user-menu bars — one per responsive
    // breakpoint.  Their CSS visibility rules (from SuiteP theme):
    //   .desktop-bar: visible at >= 1201px viewport width
    //   .tablet-bar:  visible at 751px – 1200px
    //   .mobile-bar:  visible at <= 750px
    // Using viewport size is deterministic; isVisible() on bar containers is
    // unreliable because the containers may have zero bounding box.
    const vp = this.page.viewportSize();
    const w = vp?.width ?? 1280;
    const barClass =
      w >= 1201
        ? ".desktop-bar"
        : w >= 751
          ? ".tablet-bar"
          : ".mobile-bar";

    // WARN: the toggle button differs per bar — `.user-menu-button` (desktop)
    // vs `.usermenucollapsed` (tablet/mobile). Using `button[data-toggle="dropdown"]`
    // picks the single user-menu toggle within the scoped bar, and `#globalLinks`
    // avoids matching other dropdowns (e.g. CREATE uses <a> not <button>).
    await this.page
      .locator(`${barClass} #globalLinks button[data-toggle="dropdown"]`)
      .click();

    // WARN: `#logout_link` is duplicated 3× (one per device bar). Scoping
    // to the breakpoint-based bar class avoids strict-mode collisions.
    await this.page.locator(`${barClass} a#logout_link`).click();

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
