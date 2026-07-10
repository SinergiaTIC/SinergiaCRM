import "dotenv/config";

export const BASE_URL: string =
  process.env.BASE_URL || "http://localhost:8000/sinergiacrm/";
export const INSTANCE_USER: string = process.env.INSTANCE_USER || "sinergiacrm";
export const INSTANCE_PASSWORD: string = process.env.INSTANCE_PASSWORD || "sinergiacrm";
