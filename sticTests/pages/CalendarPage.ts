import { type Page } from "@playwright/test";
import { BasePage } from "#pages/BasePage";
import { expectNoPhpErrors } from "#helpers/errors";
import { expect } from "@playwright/test";
import { type ModuleKey } from "#helpers/generic/ModuleRegistry";

export class CalendarPage extends BasePage {
  readonly moduleKey: ModuleKey;

  constructor(page: Page, moduleKey: ModuleKey) {
    super(page);
    this.moduleKey = moduleKey;
  }

  async navigateTo(action = "index"): Promise<void> {
    await super.navigateTo(this.moduleKey, action);
  }

  async waitForContentLoad(): Promise<void> {
    // WARN: Both SuiteCRM Calendar and stic_Bookings_Calendar use FullCalendar
    // (`.fc` container) to render their calendar grids. This is the common
    // content indicator across otherwise very different DOM structures.
    // If a calendar module switches to a different library, this locator breaks.
    await expect(this.page.locator(".fc").first()).toBeVisible({ timeout: 10000 });
    await expectNoPhpErrors(this.page);
  }
}
