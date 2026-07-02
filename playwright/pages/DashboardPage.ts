import { type Locator, type Page } from "@playwright/test";
import { BasePage } from "#pages/BasePage";
import { t } from "#helpers/i18n";

export class DashboardPage extends BasePage {
  readonly userMenuButton: Locator;
  readonly logoutItem: Locator;
  readonly searchButton: Locator;
  readonly recentItems: Locator;
  readonly adminActions: Locator;

  constructor(page: Page) {
    super(page);
    // WARN: flaky test, locator should not depende on the CSS class.
    this.userMenuButton = this.page.locator("button.user-menu-button");
    this.logoutItem = this.page.getByRole("menuitem", {
      name: t("LBL_LOGOUT"),
    });
    // WARN: search button matched by regex /^[^a-zA-Z]+$/ on aria-label (Unicode icon).
    // If the icon character changes or another button gets such a label, the match breaks.
    // ACCESSIBILITY: screen readers cannot pronounce a Unicode icon aria-label.
    this.searchButton = this.page.getByRole("button", { name: /^[^a-zA-Z]+$/ });
    this.recentItems = this.page
      .locator("h2")
      .filter({ hasText: t("LBL_LAST_VIEWED") })
      .locator("..");
    this.adminActions = this.page.locator("#admin_link");
  }
}
