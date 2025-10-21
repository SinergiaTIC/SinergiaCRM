const { test, expect } = require('@playwright/test');
const { login, expandAll, setFieldGeneric, robustSave, BASE_URL } = require('../test-utils/helpers');
const { saveRecord } = require('../test-utils/sharedStore');

test('Calls - minimal fields', async ({ page }) => {
  test.setTimeout(120000);
  await login(page);
  await page.goto(new URL('index.php?module=Calls&action=EditView', BASE_URL).toString());
  await page.waitForLoadState('networkidle');
  await expandAll(page);
  const name = 'PWCall_' + Math.random().toString(36).slice(2,8);
  await setFieldGeneric(page, ['input[name="name"]','input[name="call_name"]'], name);
  await setFieldGeneric(page, ['input[name="date_start"]','input[name="date_s"]'], '21/10/2025');
  await robustSave(page);
  const url = page.url(); const m = url.match(/[?&]record=([^&]+)/); let record = m ? m[1] : null;
  if (!record) { const rec = page.locator('input[name="record"]'); if (await rec.count()) record = await rec.first().inputValue(); }
  expect(record).toBeTruthy();
  await saveRecord('Calls', record);
  // verify detail view shows the call name
  await page.goto(new URL(`index.php?module=Calls&action=DetailView&record=${record}`, BASE_URL).toString());
  await page.waitForLoadState('networkidle'); await expandAll(page);
  const found = await page.locator(`text=${name}`).count();
  expect(found).toBeGreaterThan(0);
});
