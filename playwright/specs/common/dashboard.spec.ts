import { test, expect, type Page } from "@playwright/test";
import { DashboardPage } from "#pages/DashboardPage";
import { expectNoPhpErrors } from "#helpers/errors";

test.describe("Dashboard", () => {
  let dashboardPage: DashboardPage;

  test.beforeEach(async ({ page }: { page: Page }) => {
    await page.goto("index.php?module=Home&action=index");
    dashboardPage = new DashboardPage(page);
  });

  test.afterEach(async ({ page }: { page: Page }) => {
    await expectNoPhpErrors(page);
  });

  test("All four dashlets are visible on the dashboard", async ({
    page,
  }: {
    page: Page;
  }) => {
    await expect(page).toHaveURL(/module=Home&action=index/);
    await expect(dashboardPage.myCallsHeading).toBeVisible();
    await expect(dashboardPage.myMeetingsHeading).toBeVisible();
    await expect(dashboardPage.myOpenTasksHeading).toBeVisible();
    await expect(dashboardPage.sinergiaNewsHeading).toBeVisible();
  });

  test("Each dashlet has action buttons", async ({ page }: { page: Page }) => {
    await expect(page).toHaveURL(/module=Home&action=index/);
    // WARN: FLAKY — All dashlet action buttons share identical aria-labels, so these counts
    // match every dashlet's buttons globally. If dashlets are added or removed, the expected
    // count changes. Cannot scope to a single dashlet because dashlet IDs are UUIDs (dynamic).
    await expect(dashboardPage.dashletEditButtons).toHaveCount(4);
    await expect(dashboardPage.dashletRefreshButtons).toHaveCount(4);
    await expect(dashboardPage.dashletDeleteButtons).toHaveCount(4);
  });

  test("My Calls dashlet contains a data table with expected columns", async ({
    page,
  }: {
    page: Page;
  }) => {
    await expect(page).toHaveURL(/module=Home&action=index/);
    // WARN: FLAKY — Uses .first() which assumes dashlets are in default order.
    // If dashlets are reordered, this may select the wrong table.
    const myCallsTable = page
      .locator("table.list.view.default.dashletPanel")
      .first();
    // Verify column headers
    await expect(
      myCallsTable.getByRole("columnheader", { name: "Subject" }),
    ).toBeVisible();
    await expect(
      myCallsTable.getByRole("columnheader", { name: "Start Date" }),
    ).toBeVisible();
    await expect(
      myCallsTable.getByRole("columnheader", { name: "Status" }),
    ).toBeVisible();
    // Verify data section exists (second tbody, first is pagination + header row)
    // WARN: each dashlet table has 2 tbodys — pagination/header in first, data in second.
    // WARN: data rows and "No Data" text are both in the same <tr>, so bare text match
    // triggers a strict-mode collision with the row locator. Just check the row exists.
    const dataBody = myCallsTable.locator("tbody").last();
    await expect(dataBody.locator("tr").first()).toBeAttached();
  });

  test("My Meetings dashlet contains expected column headers", async ({
    page,
  }: {
    page: Page;
  }) => {
    await expect(page).toHaveURL(/module=Home&action=index/);
    // WARN: FLAKY — Uses .nth(1) which assumes dashlets are in default order.
    // If dashlets are reordered, this may select the wrong table.
    const myMeetingsTable = page
      .locator("table.list.view.default.dashletPanel")
      .nth(1);
    // Verify column headers — My Meetings has NO "Status" column
    await expect(
      myMeetingsTable.getByRole("columnheader", { name: "Subject" }),
    ).toBeVisible();
    await expect(
      myMeetingsTable.getByRole("columnheader", { name: "Start Date" }),
    ).toBeVisible();
    // WARN: My Meetings columns are: Close, Subject, Related to, Start Date, Accept? (no Status)
    const dataBody = myMeetingsTable.locator("tbody").last();
    await expect(dataBody.locator("tr").first()).toBeAttached();
  });

  test("My Open Tasks dashlet contains expected column headers", async ({
    page,
  }: {
    page: Page;
  }) => {
    await expect(page).toHaveURL(/module=Home&action=index/);
    // WARN: FLAKY — Uses .nth(2) which assumes dashlets are in default order.
    // If dashlets are reordered, this may select the wrong table.
    const myTasksTable = page
      .locator("table.list.view.default.dashletPanel")
      .nth(2);
    // Verify column headers
    await expect(
      myTasksTable.getByRole("columnheader", { name: "Subject" }),
    ).toBeVisible();
    await expect(
      myTasksTable.getByRole("columnheader", { name: "Priority" }),
    ).toBeVisible();
    await expect(
      myTasksTable.getByRole("columnheader", { name: "Status" }),
    ).toBeVisible();
    await expect(
      myTasksTable.getByRole("columnheader", { name: "Start Date" }),
    ).toBeVisible();
    await expect(
      myTasksTable.getByRole("columnheader", { name: "Due Date" }),
    ).toBeVisible();
    const dataBody = myTasksTable.locator("tbody").last();
    await expect(dataBody.locator("tr").first()).toBeAttached();
  });

  test("SinergiaCRM News dashlet loads an iframe", async ({
    page,
  }: {
    page: Page;
  }) => {
    await expect(page).toHaveURL(/module=Home&action=index/);
    // WARN: Relies on the iframe title attribute which may change if the news source is updated
    const newsIframe = page.locator('iframe[title="SinergiaCRM News"]');
    await expect(newsIframe).toBeVisible();
  });
});
