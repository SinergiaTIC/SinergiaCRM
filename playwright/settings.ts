import { config } from "dotenv";
config();

import { dirname, resolve } from "path";
import { fileURLToPath } from "url";

const PLAYWRIGHT_DIR: string = dirname(fileURLToPath(import.meta.url));
const DEFAULT_CRM_ROOT: string = resolve(PLAYWRIGHT_DIR, "..");

export const BASE_URL: string =
  process.env.BASE_URL || "http://localhost:8000/sinergiacrm/";
export const TEST_LANG: string = process.env.TEST_LANG || "ca_ES";
export const INSTANCE_USER: string = process.env.INSTANCE_USER || "sinergiacrm";
export const INSTANCE_PASSWORD: string = process.env.INSTANCE_PASSWORD || "sinergiacrm";
export const CRM_ROOT: string = process.env.CRM_ROOT || DEFAULT_CRM_ROOT;
