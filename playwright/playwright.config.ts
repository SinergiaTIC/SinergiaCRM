import { defineConfig } from "@playwright/test";
import { BASE_URL, TEST_LANG } from "./settings.js";
import type { DeviceConfig } from "./models/types.js";

const deviceConfigs: DeviceConfig[] = [
  { name: "desktop", width: 1920, height: 1080 },
  { name: "tablet", width: 768, height: 1024 },
  { name: "mobile", width: 375, height: 812 },
];

const functionalProjects = deviceConfigs.map((device) => ({
  name: `functional-${TEST_LANG}-${device.name}`,
  testMatch: [
    `specs/common/**/*.spec.ts`,
    `specs/${device.name}/**/*.spec.ts`,
    `specs/modules/*/common/**/*.spec.ts`,
    `specs/modules/*/${device.name}/**/*.spec.ts`,
  ],
  use: {
    channel: "chrome",
    viewport: { width: device.width, height: device.height },
  },
}));

const visualProjects = deviceConfigs.map((device) => ({
  name: `visual-${TEST_LANG}-${device.name}`,
  testMatch: [
    `specs/visual/common/**/*.spec.ts`,
    `specs/visual/${device.name}/**/*.spec.ts`,
    `specs/modules/*/visual/common/**/*.spec.ts`,
    `specs/modules/*/visual/${device.name}/**/*.spec.ts`,
  ],
  use: {
    channel: "chrome",
    viewport: { width: device.width, height: device.height },
  },
}));

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
    trace: "on-first-retry",
    screenshot: "only-on-failure",
    video: "on-first-retry",
  },
  projects: [...functionalProjects, ...visualProjects],
});
