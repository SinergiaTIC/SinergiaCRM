import { defineConfig, devices } from "@playwright/test";
import { BASE_URL, TEST_LANG } from "./settings.js";

type ProjectType = "functional" | "visual";

function makeProjects(type: ProjectType) {
  const isVisual = type === "visual" ? "visual/" : "";
  const deviceEntries = [
    { name: "desktop", device: devices["Desktop Chrome"] },
    { name: "tablet", device: devices["Galaxy Tab S4"] },
    { name: "mobile", device: devices["Pixel 5"] },
  ] as const;
  return deviceEntries.map(({ name, device }) => ({
    name: `${type}-${TEST_LANG}-${name}`,
    testMatch: [
      `specs/${isVisual}common/**/*.spec.ts`,
      `specs/${isVisual}${name}/**/*.spec.ts`,
      `specs/modules/*/${isVisual}common/**/*.spec.ts`,
      `specs/modules/*/${isVisual}${name}/**/*.spec.ts`,
    ],
    use: { ...device },
  }));
}

export default defineConfig({
  globalSetup: "./global-setup.ts",
  workers: 1,
  timeout: 30000,
  expect: {
    timeout: 10000,
    toHaveScreenshot: { maxDiffPixelRatio: 0.02, threshold: 0.2 },
  },
  retries: process.env.CI ? 2 : 0,
  snapshotPathTemplate:
    "{testFileDir}/{testFileName}-snapshots/{projectName}-{arg}{ext}",
  use: {
    baseURL: BASE_URL,
    storageState: ".auth/user.json",
    channel: "chrome",
    trace: "on-first-retry",
    screenshot: "only-on-failure",
    video: "on-first-retry",
  },
  projects: [...makeProjects("functional"), ...makeProjects("visual")],
});
