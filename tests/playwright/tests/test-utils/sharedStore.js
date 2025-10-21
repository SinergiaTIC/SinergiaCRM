const fs = require('fs').promises;
const path = require('path');
const STORE_PATH = path.join(__dirname, '..', '_created_records.json');

async function readStore() {
  try {
    const raw = await fs.readFile(STORE_PATH, 'utf8');
    return JSON.parse(raw || '{}');
  } catch (e) {
    return {};
  }
}

async function writeStore(s) {
  await fs.writeFile(STORE_PATH, JSON.stringify(s, null, 2), 'utf8');
}

async function saveRecord(moduleName, id) {
  const s = await readStore();
  s[moduleName] = id;
  await writeStore(s);
}

async function getRecord(moduleName) {
  const s = await readStore();
  return s[moduleName] || null;
}

async function waitForRecord(moduleName, timeout = 2 * 60 * 1000) {
  const start = Date.now();
  while (Date.now() - start < timeout) {
    const r = await getRecord(moduleName);
    if (r) return r;
    await new Promise((res) => setTimeout(res, 500));
  }
  return null;
}

module.exports = { saveRecord, getRecord, waitForRecord };
