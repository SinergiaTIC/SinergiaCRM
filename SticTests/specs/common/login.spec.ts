import { test, expect, type Page } from "@playwright/test";
import { BasePage } from "#pages/BasePage";
import { LoginPage } from "#pages/LoginPage";
import { expectNoPhpErrors } from "#helpers/errors";
import { INSTANCE_USER, INSTANCE_PASSWORD } from "#settings";

const login = async (user: string, password: string, page: Page) => {
  const login: LoginPage = new LoginPage(page);
  await login.usernameInput.fill(user);
  await login.passwordInput.fill(password);
  await login.loginButton.click();
};

test.describe("Login page", () => {
  test.use({ storageState: { cookies: [], origins: [] } as any });

  test.beforeEach(async ({ page }: { page: Page }) => {
    await page.goto("index.php?action=Login&module=Users");
  });

  test("displays login form with all elements", async ({
    page,
  }: {
    page: Page;
  }) => {
    const login: LoginPage = new LoginPage(page);
    await expect(login.usernameInput).toBeVisible();
    await expect(login.passwordInput).toBeVisible();
    await expect(login.loginButton).toBeVisible();
    await expect(login.forgotPasswordLink).toBeVisible();
  });

  test("shows error with invalid credentials", async ({
    page,
  }: {
    page: Page;
  }) => {
    const loginPage: LoginPage = new LoginPage(page);
    await test.step("fill and submit invalid credentials", async () => {
      await login("fakeuser", "fakepass", page);
    });
    await test.step("stays on login page with error message", async () => {
      await expect(page).toHaveURL(/action=Login/);
      await expect(
        page.getByText("You must specify a valid username and password."),
      ).toBeVisible();
      await expect(loginPage.usernameInput).toBeVisible();
      await expectNoPhpErrors(page);
    });
  });

  test("logout redirects to login page", async ({ page }: { page: Page }) => {
    const loginPage: LoginPage = new LoginPage(page);
    await test.step("log in with valid credentials", async () => {
      await login(INSTANCE_USER, INSTANCE_PASSWORD, page);
      await expect(page).toHaveURL(/module=Home&action=index/);
    });
    await test.step("perform logout via UI and verify redirect", async () => {
      // WARN: logout uses BasePage.logout() which is device-aware —
      // it selects the correct responsive bar class based on viewport
      // width (desktop / tablet / mobile).  The detailed breakpoint
      // logic is documented in BasePage.ts.
      const basePage = new BasePage(page);
      await basePage.logout();
      await expect(loginPage.loginButton).toBeVisible();
    });
  });
});
