const { test, expect } = require('@playwright/test');
const { login, expandAll, setFieldGeneric, robustSave, BASE_URL } = require('../test-utils/helpers');
const { saveRecord } = require('../test-utils/sharedStore');

test('Leads - minimal fields', async ({ page }) => {
  test.setTimeout(120000);
  await login(page);
  await page.goto(new URL('index.php?module=Leads&action=EditView', BASE_URL).toString());
  await page.waitForLoadState('networkidle');
  await expandAll(page);
  const last = 'PWLead_' + Math.random().toString(36).slice(2,8);
  await setFieldGeneric(page, ['input[name="first_name"]'], 'PWFirst');
  await setFieldGeneric(page, ['input[name="last_name"]','input[name="name"]'], last);
  await robustSave(page);
  const url = page.url(); const m = url.match(/[?&]record=([^&]+)/); let record = m ? m[1] : null;
  if (!record) { const rec = page.locator('input[name="record"]'); if (await rec.count()) record = await rec.first().inputValue(); }
  expect(record).toBeTruthy();
  await saveRecord('Leads', record);
  // verify detail view shows the lead name
  await page.goto(new URL(`index.php?module=Leads&action=DetailView&record=${record}`, BASE_URL).toString());
  await page.waitForLoadState('networkidle'); await expandAll(page);
  const found = await page.locator(`text=PWFirst ${last}`).count();
  expect(found).toBeGreaterThan(0);
});
