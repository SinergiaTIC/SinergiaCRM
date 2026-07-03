import { type Locator, type Page } from "@playwright/test";
import { BasePage } from "#pages/BasePage";
import { t } from "#helpers/i18n";

export class DashboardPage extends BasePage {
  readonly userMenuButton: Locator;
  readonly logoutItem: Locator;
  readonly searchButton: Locator;
  readonly recentItems: Locator;
  readonly adminActions: Locator;

  // Dashboard heading
  readonly dashboardHeading: Locator;

  // Dashlet title locators
  readonly myCallsHeading: Locator;
  readonly myMeetingsHeading: Locator;
  readonly myOpenTasksHeading: Locator;
  readonly sinergiaNewsHeading: Locator;

  // Dashlet action buttons (match all dashlets since aria-label is the same)
  readonly dashletEditButtons: Locator;
  readonly dashletRefreshButtons: Locator;
  readonly dashletDeleteButtons: Locator;

  // Sidebar
  readonly sidebar: Locator;
  readonly recentlyViewedHeading: Locator;
  readonly adminActionsHeading: Locator;

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

    // WARN: sidebar uses .sidebar class which is fragile if the layout changes.
    this.sidebar = this.page.locator(".sidebar");
    this.recentlyViewedHeading = this.page
      .locator("h2")
      .filter({ hasText: t("LBL_LAST_VIEWED") });
    this.adminActionsHeading = this.page
      .locator("h2")
      .filter({ hasText: "Admin actions" });
    // WARN: #admin_link is NOT in the sidebar — it's in the top navigation bar.
    // Use .first() to handle duplicate IDs (same id used in nav + user menu).
    this.adminActions = this.page.locator("#admin_link").first();

    // WARN: heading text is uppercased by CSS text-transform, so getByRole with exact match fails.
    // Using filter hasText on h3 to match the DOM textContent ("My Calls") case-insensitively.
    this.dashboardHeading = this.page.getByRole("link", {
      name: /SUITECRM DASHBOARD/,
    });

    this.myCallsHeading = this.page
      .locator("h3")
      .filter({ hasText: "My Calls" });
    this.myMeetingsHeading = this.page
      .locator("h3")
      .filter({ hasText: "My Meetings" });
    this.myOpenTasksHeading = this.page
      .locator("h3")
      .filter({ hasText: "My Open Tasks" });
    this.sinergiaNewsHeading = this.page
      .locator("h3")
      .filter({ hasText: "SinergiaCRM News" });

    // WARN: FLAKY — All dashlet action buttons share identical aria-labels ("Edit SuiteCRM Dashlet",
    // "Refresh SuiteCRM Dashlet", "Delete SuiteCRM Dashlet"). These match every dashlet's buttons.
    // Cannot scope to a single dashlet because dashlet IDs are UUIDs (dynamic).
    this.dashletEditButtons = this.page.getByRole("link", {
      name: "Edit SuiteCRM Dashlet",
    });
    this.dashletRefreshButtons = this.page.getByRole("link", {
      name: "Refresh SuiteCRM Dashlet",
    });
    this.dashletDeleteButtons = this.page.getByRole("link", {
      name: "Delete SuiteCRM Dashlet",
    });

  }
}
