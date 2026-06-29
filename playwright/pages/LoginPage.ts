import { type Locator, type Page } from "@playwright/test";
import { BasePage } from "./BasePage.js";

export class LoginPage extends BasePage {
  readonly usernameInput: Locator;
  readonly passwordInput: Locator;
  readonly loginButton: Locator;
  readonly loginForm: Locator;
  readonly forgotPasswordLink: Locator;

  constructor(page: Page) {
    super(page);
    // WARN: login inputs use placeholder instead of <label> — no accessible name.
    // CSS IDs are stable across SuiteP theme versions.
    this.usernameInput = page.locator("#user_name");
    this.passwordInput = page.locator("#username_password");
    // WARN: button value is always "Login" (English) — no session at render time.
    this.loginButton = page.locator("#bigbutton");
    this.loginForm = page.locator("#loginform");
    // WARN: SuiteP uses a <div id="forgotpasslink">, not an <a> tag.
    this.forgotPasswordLink = page.locator("#forgotpasslink");
  }
}
