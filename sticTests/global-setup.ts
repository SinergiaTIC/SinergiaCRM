import { chromium, type Browser, type BrowserContext, type Page } from "@playwright/test";
import { writeFileSync, mkdirSync, existsSync } from "fs";
import { BASE_URL, INSTANCE_USER, INSTANCE_PASSWORD } from "#settings";
import { ALL_MODULES } from "#helpers/generic/ModuleRegistry";

interface ApiResponse {
  id?: string;
  error?: { number: string; name: string; description: string };
}

export default async function (): Promise<void> {
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
    name_value_list: [],
  };

  const loginResponse = await page.request.post("service/v4_1/rest.php", {
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    form: {
      method: "login",
      input_type: "JSON",
      response_type: "JSON",
      rest_data: JSON.stringify(restData),
    },
  });

  const loginData: ApiResponse = await loginResponse.json();
  if (loginData.error) {
    throw new Error(
      `API login failed: ${loginData.error.name} — ${loginData.error.description}`,
    );
  }

  // Navigate to the homepage — session is now authenticated with the right language
  await page.goto("index.php?module=Home&action=index");
  await page.waitForURL(/module=Home&action=index/);

  // ── Detect CRM language from browser HTML lang attribute ──
  const detectedLang: string = await page.evaluate(
    () => document.documentElement.lang || "en_us",
  );
  console.log(`[global-setup] CRM language detected: ${detectedLang}`);

  // ── Extract labels from browser SUGAR.language ──
  const moduleKeys: string[] = ["Users", ...ALL_MODULES.map((m) => m.key)];
  const labels: Record<string, string> = await page.evaluate(
    async (keys: string[]) => {
      const all: Record<string, string> = {};
      const seen = new Set<string>();

      function flatten(obj: Record<string, unknown>) {
        if (!obj || typeof obj !== "object") return;
        for (const [k, v] of Object.entries(obj)) {
          if (typeof v === "string" && !seen.has(k)) {
            seen.add(k);
            all[k] = v;
          } else if (typeof v === "object" && v !== null && !Array.isArray(v)) {
            flatten(v as Record<string, unknown>);
          }
        }
      }

      // 1. Extract from SUGAR.language on the page (app_strings, app_list_strings, module_strings)
      // Structure: SUGAR.language.languages = { app_strings: {...}, app_list_strings: {...}, <ModuleName>: {...} }
      const sl = (window as any).SUGAR?.language;
      const data = sl?.languages ?? sl;
      if (data?.app_strings) flatten(data.app_strings);
      if (data?.app_list_strings) flatten(data.app_list_strings);
      if (data?.module_strings) flatten(data.module_strings);

      // 2. Fetch module-specific labels via getJSLanguage endpoint
      for (const mod of keys) {
        try {
          const res = await fetch(
            `index.php?entryPoint=getJSLanguage&module=${mod}`,
          );
          if (!res.ok) continue;
          const json = await res.json();
          if (json?.module_strings) flatten(json.module_strings);
        } catch (e) {
          console.warn(
            `[global-setup] Could not load labels for "${mod}": ${e instanceof Error ? e.message : String(e)}`,
          );
        }
      }

      return all;
    },
    moduleKeys,
  );

  writeFileSync(".labels-cache.json", JSON.stringify(labels, null, 2));

  if (!existsSync(".auth")) mkdirSync(".auth", { recursive: true });
  await context.storageState({ path: ".auth/user.json" });
  await browser.close();
}
