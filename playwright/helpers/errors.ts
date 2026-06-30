import { expect, type Page } from "@playwright/test";

const PHP_ERROR_PATTERN: RegExp =
  /(?:PHP\s+)?(?:Notice|Fatal error|Deprecated|Strict Standards):\s|PHP\s+Warning:\s|Error:.*on line|Undefined\s+(?:variable|index|offset|property)/;

export async function expectNoPhpErrors(page: Page): Promise<void> {
  const body = await page.locator("body").textContent();
  if (body && PHP_ERROR_PATTERN.test(body)) {
    const matches = body.match(
      /.{0,300}(?:Warning|Notice|Fatal error|Deprecated|Strict Standards|Undefined).{0,300}/gis,
    );
    console.warn("[PHP Errors detected on page]:", matches?.slice(0, 3));
  }
  await expect.soft(page.locator("body")).not.toContainText(PHP_ERROR_PATTERN);
}
