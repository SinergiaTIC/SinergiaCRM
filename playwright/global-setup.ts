import { chromium, type Browser, type BrowserContext, type Page } from "@playwright/test";
import { writeFileSync, mkdirSync, existsSync, readFileSync } from "fs";
import {
  BASE_URL,
  TEST_LANG,
  INSTANCE_USER,
  INSTANCE_PASSWORD,
  CRM_ROOT,
} from "./settings.js";
import { ALL_MODULES } from "./helpers/generic/ModuleRegistry.js";
import type { LabelSource } from "./models/types.js";

interface ApiResponse {
  id?: string;
  error?: { number: string; name: string; description: string };
}

function parsePhpLabels(filePath: string): Record<string, string> {
  const content: string = readFileSync(filePath, "utf-8");
  const labels: Record<string, string> = {};
  // Handles both:
  //   'KEY' => 'VALUE'          (PHP array construction, core lang files)
  //   'KEY'] = 'VALUE'          (nested assignment, extension files)
  const regex: RegExp =
    /'([A-Za-z_][A-Za-z_0-9]+)'\s*(?:=>|])\s*=?\s*(?:'((?:[^'\\]|\\.)*)'|"((?:[^"\\]|\\.)*)")/g;
  let match: RegExpExecArray | null;
  while ((match = regex.exec(content)) !== null) {
    const value: string | undefined = match[2] || match[3];
    if (value !== undefined) {
      labels[match[1]] = value.replace(/\\(['"])/g, "$1");
    }
  }
  return labels;
}

const MODULE_KEYS: string[] = [
  "Users",
  ...ALL_MODULES.map((m) => m.key),
];

const LABEL_SOURCES: LabelSource[] = [
  { file: `include/language/${TEST_LANG}.lang.php` },
  ...MODULE_KEYS.map((m) => ({
    file: `modules/${m}/language/${TEST_LANG}.lang.php`,
  })),
  { file: `custom/Extension/application/Ext/Language/${TEST_LANG}.SticLang.php` },
];

export default async function (): Promise<void> {
  // ── Step 1: Parse labels from PHP files ──
  const labels: Record<string, string> = {};

  for (const source of LABEL_SOURCES) {
    const path: string = `${CRM_ROOT}/${source.file}`;
    try {
      Object.assign(labels, parsePhpLabels(path));
    } catch (err) {
      console.warn(
        `[global-setup] Could not read ${path}: ${(err as Error).message}`,
      );
    }
  }

  writeFileSync(".labels-cache.json", JSON.stringify(labels, null, 2));

  // ── Step 2: Login via REST API and save auth state ──
  const browser: Browser = await chromium.launch({ channel: "chrome" });
  const context: BrowserContext = await browser.newContext({
    baseURL: BASE_URL,
  });

  const page: Page = await context.newPage();

  // Establish a PHP session (sets PHPSESSID cookie in the browser context)
  await page.request.get("index.php?action=Login&module=Users");

  // Login via REST API — shares PHPSESSID cookie with the page context
  const restData = {
    user_auth: {
      user_name: INSTANCE_USER,
      password: INSTANCE_PASSWORD,
      encryption: "PLAIN",
    },
    application_name: "Playwright Global Setup",
    name_value_list: [{ name: "language", value: TEST_LANG }],
  };

  const loginResponse = await page.request.post(
    "service/v4_1/rest.php",
    {
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      form: {
        method: "login",
        input_type: "JSON",
        response_type: "JSON",
        rest_data: JSON.stringify(restData),
      },
    },
  );

  const loginData: ApiResponse = await loginResponse.json();
  if (loginData.error) {
    throw new Error(
      `API login failed: ${loginData.error.name} — ${loginData.error.description}`,
    );
  }

  // Navigate to the homepage — session is now authenticated with the right language
  await page.goto("index.php?module=Home&action=index");
  await page.waitForURL(/module=Home&action=index/);

  if (!existsSync(".auth")) mkdirSync(".auth", { recursive: true });
  await context.storageState({ path: ".auth/user.json" });
  await browser.close();
}
