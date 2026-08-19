import { readFileSync, existsSync } from "fs";

let labels: Record<string, string> | undefined;

export function t(key: string): string {
  if (!labels) {
    if (!existsSync(".labels-cache.json")) {
      return key;
    }
    labels = JSON.parse(readFileSync(".labels-cache.json", "utf-8"));
  }
  if (!(key in labels!)) {
    return key;
  }
  return labels![key];
}
