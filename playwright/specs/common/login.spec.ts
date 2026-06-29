import { test, expect, type Page } from "@playwright/test";
import { LoginPage } from "../../pages/LoginPage.js";
import { expectNoPhpErrors } from "../../helpers/errors.js";
import { INSTANCE_USER, INSTANCE_PASSWORD } from "../../settings.js";

test.use({ storageState: { cookies: [], origins: [] } as any });

test.describe("Login page", () => {
  test.beforeEach(async ({ page }: { page: Page }) => {
    await page.goto("index.php?action=Login&module=Users");
  });

  test("displays login form with all elements", async ({ page }: { page: Page }) => {
    const login: LoginPage = new LoginPage(page);
    await expect(login.usernameInput).toBeVisible();
    await expect(login.passwordInput).toBeVisible();
    await expect(login.loginButton).toBeVisible();
    await expect(login.forgotPasswordLink).toBeVisible();
  });

  test("logs in successfully with valid credentials", async ({ page }: { page: Page }) => {
    const login: LoginPage = new LoginPage(page);
    await login.usernameInput.fill(INSTANCE_USER);
    await login.passwordInput.fill(INSTANCE_PASSWORD);
    await login.loginButton.click();
    await expect(page).toHaveURL(/module=Home&action=index/);
    await expectNoPhpErrors(page);
  });
});
