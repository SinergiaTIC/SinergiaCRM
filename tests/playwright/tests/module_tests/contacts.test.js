const { test, expect } = require('@playwright/test');
const { login, expandAll, setFieldGeneric, robustSave, BASE_URL } = require('../test-utils/helpers');
const { saveRecord } = require('../test-utils/sharedStore');

test('Contacts - minimal fields', async ({ page }) => {
  test.setTimeout(120000);
  await login(page);
  await page.goto(new URL('index.php?module=Contacts&action=EditView', BASE_URL).toString());
  await page.waitForLoadState('networkidle');
  await expandAll(page);
  const last = 'PWContact_' + Math.random().toString(36).slice(2,8);
  await setFieldGeneric(page, ['input[name="first_name"]'], 'PWFirst');
  await setFieldGeneric(page, ['input[name="last_name"]','input[name="name"]'], last);
  await robustSave(page);
  const url = page.url();
  const m = url.match(/[?&]record=([^&]+)/);
  let record = m ? m[1] : null;
  if (!record) {
    const rec = page.locator('input[name="record"]'); if (await rec.count()) record = await rec.first().inputValue();
  }
  expect(record).toBeTruthy();
  // also save the contact's display name so other tests can select it by name
  await saveRecord('Contacts', record);
    await saveRecord('Contacts_name', last);
    // verify detail view shows the contact name
    await page.goto(new URL(`index.php?module=Contacts&action=DetailView&record=${record}`, BASE_URL).toString());
    await page.waitForLoadState('networkidle'); await expandAll(page);
    const found = await page.locator(`text=${last}`).count();
    expect(found).toBeGreaterThan(0);
});
