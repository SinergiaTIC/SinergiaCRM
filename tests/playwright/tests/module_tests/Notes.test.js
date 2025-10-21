const { test, expect } = require('@playwright/test');
const { login, expandAll, setFieldGeneric, robustSave, BASE_URL } = require('../test-utils/helpers');
const { saveRecord } = require('../test-utils/sharedStore');

test('Notes - minimal fields', async ({ page }) => {
  await login(page);
  await page.goto(new URL('index.php?module=Notes&action=EditView', BASE_URL).toString());
  await page.waitForLoadState('networkidle');
  await expandAll(page);
  const name = 'PWNote_' + Math.random().toString(36).slice(2,8);
  await setFieldGeneric(page, ['input[name="name"]','input[name="note_name"]'], name);
  await setFieldGeneric(page, ['textarea[name="description"]','textarea[name="notes_description"]'], 'Auto note');
  await robustSave(page);
  const url = page.url(); const m = url.match(/[?&]record=([^&]+)/); let record = m ? m[1] : null;
  if (!record) { const rec = page.locator('input[name="record"]'); if (await rec.count()) record = await rec.first().inputValue(); }
  expect(record).toBeTruthy();
  await saveRecord('Notes', record);
  // verify detail view shows the note name/description
  await page.goto(new URL(`index.php?module=Notes&action=DetailView&record=${record}`, BASE_URL).toString());
  await page.waitForLoadState('networkidle'); await expandAll(page);
  const found = await page.locator(`text=${name}`).count();
  expect(found).toBeGreaterThan(0);
});
