const dotenv = require('dotenv');
// Get current directory of this file and load .env from there

dotenv.config({ path: 'tests/playwright/.env' });
const BASE_URL = process.env.BASE_URL || 'http://localhostttt/';
const ADMIN_USER = process.env.ADMIN_USER || 'admin';
const ADMIN_PASS = process.env.ADMIN_PASS || 'admin';

async function login(page) {
  await page.goto(BASE_URL);
  await page.waitForLoadState('networkidle');
  const userCandidates = ['input[name="user_name"]', 'input#user_name', 'input[name="username"]', 'input[type="text"]'];
  const passCandidates = ['input[name="user_password"]', 'input#user_password', 'input[name="username_password"]', 'input#username_password', 'input[name="password"]', 'input[type="password"]'];
  let userSelector = null;
  for (const s of userCandidates) if (await page.locator(s).count() > 0) { userSelector = s; break; }
  let passSelector = null;
  for (const s of passCandidates) if (await page.locator(s).count() > 0) { passSelector = s; break; }
  if (!userSelector || !passSelector) throw new Error('Login inputs not found');
  await page.fill(userSelector, ADMIN_USER);
  await page.fill(passSelector, ADMIN_PASS);
  const submitCandidates = ['button:has-text("Login")', 'button[type="submit"]', 'input[type="submit"]', 'input[name="Login"]'];
  for (const s of submitCandidates) {
    if (await page.locator(s).count()) { await Promise.all([page.waitForLoadState('networkidle'), page.locator(s).first().click()]); break; }
  }
  await page.waitForLoadState('networkidle');
}

async function expandAll(page) {
  const showMore = page.locator('a:has-text("Show More"), a:has-text("Show more"), button:has-text("Show More")');
  if (await showMore.count()) { for (let i = 0; i < await showMore.count(); i++) { await showMore.nth(i).click().catch(() => {}); } }
  const toggles = page.locator('.panel .panel-heading .fa-chevron-down, .panel .panel-heading .fa-chevron-right, .collapsed');
  if (await toggles.count()) { for (let i = 0; i < await toggles.count(); i++) { await toggles.nth(i).click().catch(() => {}); } }
  await page.waitForTimeout(200);
}

async function setFieldGeneric(page, selectorOrCandidates, value) {
  const candidates = Array.isArray(selectorOrCandidates) ? selectorOrCandidates : [selectorOrCandidates];
  for (const sel of candidates) {
    const count = await page.locator(sel).count();
    if (!count) continue;
    const el = page.locator(sel).first();
    const tag = await el.evaluate((n) => (n && n.tagName ? n.tagName.toLowerCase() : null));
    try {
      if (tag === 'select') { await el.selectOption({ label: value }).catch(() => el.selectOption(value).catch(() => {})); }
      else if (tag === 'input' || tag === 'textarea') { await el.fill(value); try { await el.press('Tab'); } catch (e) { await el.evaluate((n) => { try { n.focus(); n.blur(); } catch (e) {} }); } }
      else { await el.click().catch(() => {}); }
      return true;
    } catch (e) {
      await page.evaluate(({ selector, val }) => {
        const node = document.querySelector(selector);
        if (!node) return;
        try { node.value = val; node.dispatchEvent(new Event('input', { bubbles: true })); node.dispatchEvent(new Event('change', { bubbles: true })); } catch (e) {}
      }, { selector: sel, val: value });
      return true;
    }
  }
  return false;
}

async function robustSave(page) {
  const form = page.locator('form[name="EditView"], form#EditView');
  let saved = false;
  if (await form.count()) {
    const candidates = form.locator('button[type="submit"], input[type="submit"], button, input[type="button"]');
    const c = await candidates.count();
    for (let i = 0; i < c; i++) {
      const cand = candidates.nth(i);
      if (!(await cand.isVisible())) continue;
      try { await Promise.all([page.waitForLoadState('networkidle'), cand.click()]); saved = true; break; } catch (e) { continue; }
    }
  }
  if (!saved) { await page.evaluate(() => { const f = document.querySelector('form[name="EditView"], form#EditView'); if (f) f.submit(); }); await page.waitForLoadState('networkidle'); }
  await page.waitForTimeout(350);
}

async function attachFile(page, selectorCandidates, filePath) {
  const candidates = Array.isArray(selectorCandidates) ? selectorCandidates : [selectorCandidates];
  for (const sel of candidates) {
    const count = await page.locator(sel).count();
    if (!count) continue;
    const input = page.locator(sel).first();
    try { await input.setInputFiles(filePath); return true; } catch (e) { continue; }
  }
  return false;
}

async function verifyDownloadedFile(page, linkSelectors, expectedPath, baseUrl = BASE_URL) {
  const selectors = Array.isArray(linkSelectors) ? linkSelectors : [linkSelectors];
  const fs = require('fs');
  for (const sel of selectors) {
    const count = await page.locator(sel).count();
    if (!count) continue;
    const link = page.locator(sel).first();
    try {
      const [ download ] = await Promise.all([
        page.waitForEvent('download', { timeout: 20000 }),
        link.click()
      ]);
      const path = await download.path();
      const expected = fs.readFileSync(expectedPath, 'utf8');
      const actual = fs.readFileSync(path, 'utf8');
      return actual === expected;
    } catch (e) {
      // fallback to fetching href
      try {
        const href = await link.getAttribute('href');
        if (!href) continue;
        const target = new URL(href, baseUrl).toString();
        const resp = await page.request.get(target);
        const actual = await resp.text();
        const expected = fs.readFileSync(expectedPath, 'utf8');
        return actual === expected;
      } catch (e2) { continue; }
    }
  }
  return false;
}

module.exports = { login, expandAll, setFieldGeneric, robustSave, BASE_URL, attachFile, verifyDownloadedFile };
