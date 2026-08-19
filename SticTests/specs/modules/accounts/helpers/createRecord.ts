import { type Page } from "@playwright/test";
import { EditViewPage } from "#pages/EditViewPage";

/**
 * Creates an Account record via the UI EditView.
 *
 * Navigates to the Accounts EditView, fills the "name" field, then
 * submits via keyboard Enter to avoid tab-bar overlap on tablet/mobile.
 *
 * @param page Playwright page
 * @param name Value for the "name" field
 * @returns The record ID extracted from the DetailView URL
 */
export async function createRecord(
  page: Page,
  name: string,
): Promise<string> {
  const editView = new EditViewPage(page, "Accounts");
  await editView.navigateToCreate();
  await editView.fillField("name", name);

  // WARN: EditViewPage.save() uses POM click which fails on tablet/mobile
  // because the tab bar overlaps the Save button. Use keyboard Enter instead.
  await page.keyboard.press("Enter");
  await page.waitForURL(/action=(DetailView|index)/);

  return new URL(page.url()).searchParams.get("record") ?? "";
}
