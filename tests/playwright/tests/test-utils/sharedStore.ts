declare const process: any;
// Use require to avoid TS Node type issues in test environment
const fs = require('fs').promises;
const path = require('path');
const STORE_PATH = path.join((typeof __dirname !== 'undefined' ? __dirname : '.'), '..', '_created_records.json');

async function readStore(): Promise<Record<string, string>> {
  try {
    const raw = await fs.readFile(STORE_PATH, 'utf8');
    return JSON.parse(raw || '{}');
  } catch (e) {
    return {};
  }
}

async function writeStore(s: Record<string, string>) {
  await fs.writeFile(STORE_PATH, JSON.stringify(s, null, 2), 'utf8');
}

export async function saveRecord(moduleName: string, id: string) {
  const s = await readStore();
  s[moduleName] = id;
  await writeStore(s);
}

export async function getRecord(moduleName: string) {
  const s = await readStore();
  return s[moduleName] || null;
}

export async function waitForRecord(moduleName: string, timeout = 10000) {
  const start = Date.now();
  while (Date.now() - start < timeout) {
    const r = await getRecord(moduleName);
    if (r) return r;
    await new Promise((res) => setTimeout(res, 250));
  }
  return null;
}
