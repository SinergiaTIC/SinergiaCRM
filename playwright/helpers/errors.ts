import { expect, type Page } from "@playwright/test";

const PHP_ERROR_PATTERN: RegExp =
  /(?:PHP\s+)?(?:Warning|Notice|Fatal error|Deprecated|Strict Standards):\s|Error:.*on line|Undefined\s+(?:variable|index|offset|property)/;

export async function expectNoPhpErrors(page: Page): Promise<void> {
  await expect.soft(page.locator("body")).not.toContainText(PHP_ERROR_PATTERN);
}
