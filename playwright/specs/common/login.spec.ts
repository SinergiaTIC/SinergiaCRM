import { test, expect, type Page } from "@playwright/test";
import { LoginPage } from "#pages/LoginPage";
import { expectNoPhpErrors } from "#helpers/errors";
import { INSTANCE_USER, INSTANCE_PASSWORD } from "#settings";
import { t } from "#helpers/i18n";

test.describe("Login page", () => {
  test.use({ storageState: { cookies: [], origins: [] } as any });

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

  test("redirects to login when unauthenticated", async ({ page }: { page: Page }) => {
    await test.step("protected page redirects to login", async () => {
      await page.goto("index.php?module=Accounts&action=index");
      await expect(
        page.getByRole("button", { name: t("LBL_LOGIN_BUTTON_LABEL") }),
      ).toBeVisible();
      await expectNoPhpErrors(page);
    });
  });

  test("shows error with invalid credentials", async ({ page }: { page: Page }) => {
    const login: LoginPage = new LoginPage(page);
    await test.step("fill and submit invalid credentials", async () => {
      await login.usernameInput.fill("fakeuser");
      await login.passwordInput.fill("fakepass");
      await login.loginButton.click();
    });
    await test.step("stays on login page with error message", async () => {
      await expect(page).toHaveURL(/action=Login/);
      await expect(page.getByText(t("ERR_INVALID_PASSWORD"))).toBeVisible();
      await expect(login.usernameInput).toBeVisible();
      await expectNoPhpErrors(page);
    });
  });

  test("logs in successfully with valid credentials", async ({ page }: { page: Page }) => {
    const login: LoginPage = new LoginPage(page);
    await test.step("fill credentials and submit", async () => {
      await login.usernameInput.fill(INSTANCE_USER);
      await login.passwordInput.fill(INSTANCE_PASSWORD);
      await login.loginButton.click();
    });
    await test.step("redirected to Home dashboard", async () => {
      await expect(page).toHaveURL(/module=Home&action=index/);
      await expectNoPhpErrors(page);
    });
  });

  test("session persists across module navigation", async ({ page }: { page: Page }) => {
    const login: LoginPage = new LoginPage(page);
    await test.step("log in", async () => {
      await login.usernameInput.fill(INSTANCE_USER);
      await login.passwordInput.fill(INSTANCE_PASSWORD);
      await login.loginButton.click();
      await expect(page).toHaveURL(/module=Home&action=index/);
    });
    await test.step("navigate to Contacts — session active", async () => {
      await page.goto("index.php?module=Contacts&action=index");
      await expect(
        page.getByRole("heading", { name: t("LBL_CONTACTS") }),
      ).toBeVisible();
    });
    await test.step("navigate to Accounts — session active", async () => {
      await page.goto("index.php?module=Accounts&action=index");
      await expect(
        page.getByRole("heading", { name: t("LBL_ACCOUNTS") }),
      ).toBeVisible();
    });
  });

  test("logout redirects to login page", async ({ page }: { page: Page }) => {
    const login: LoginPage = new LoginPage(page);
    await test.step("log in", async () => {
      await login.usernameInput.fill(INSTANCE_USER);
      await login.passwordInput.fill(INSTANCE_PASSWORD);
      await login.loginButton.click();
      await expect(page).toHaveURL(/module=Home&action=index/);
    });
    await test.step("perform logout and verify redirect", async () => {
      await page.goto("index.php?module=Users&action=Logout");
      await expect(page).toHaveURL(/action=Login/);
      await expect(login.loginButton).toBeVisible();
    });
  });
});
