const { test, expect } = require('@playwright/test');
const { login, expandAll, setFieldGeneric, robustSave, BASE_URL } = require('../test-utils/helpers');
const { saveRecord } = require('../test-utils/sharedStore');

test('Meetings - minimal fields', async ({ page }) => {
  test.setTimeout(120000);
  await login(page);
  await page.goto(new URL('index.php?module=Meetings&action=EditView', BASE_URL).toString());
  await page.waitForLoadState('networkidle');
  await expandAll(page);
  const name = 'PWMeeting_' + Math.random().toString(36).slice(2,8);
  await setFieldGeneric(page, ['input[name="name"]','input[name="meeting_name"]'], name);
  // set required date/time if present
  await setFieldGeneric(page, ['input[name="date_start"]','input[name="date_s"]'], '21/10/2025');
  await robustSave(page);
  const url = page.url(); const m = url.match(/[?&]record=([^&]+)/); let record = m ? m[1] : null;
  if (!record) { const rec = page.locator('input[name="record"]'); if (await rec.count()) record = await rec.first().inputValue(); }
  expect(record).toBeTruthy();
  await saveRecord('Meetings', record);
  // verify detail view shows the meeting name
  await page.goto(new URL(`index.php?module=Meetings&action=DetailView&record=${record}`, BASE_URL).toString());
  await page.waitForLoadState('networkidle'); await expandAll(page);
  const found = await page.locator(`text=${name}`).count();
  expect(found).toBeGreaterThan(0);
});
