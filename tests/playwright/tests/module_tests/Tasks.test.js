const { test, expect } = require('@playwright/test');
const { login, expandAll, setFieldGeneric, robustSave, BASE_URL } = require('../test-utils/helpers');
const { saveRecord } = require('../test-utils/sharedStore');

test('Tasks - minimal fields', async ({ page }) => {
  await login(page);
  await page.goto(new URL('index.php?module=Tasks&action=EditView', BASE_URL).toString());
  await page.waitForLoadState('networkidle');
  await expandAll(page);
  const name = 'PWTask_' + Math.random().toString(36).slice(2,8);
  await setFieldGeneric(page, ['input[name="name"]','input[name="task_name"]'], name);
  await robustSave(page);
  const url = page.url(); const m = url.match(/[?&]record=([^&]+)/); let record = m ? m[1] : null;
  if (!record) { const rec = page.locator('input[name="record"]'); if (await rec.count()) record = await rec.first().inputValue(); }
  expect(record).toBeTruthy();
  await saveRecord('Tasks', record);
  // verify detail view shows the task name
  await page.goto(new URL(`index.php?module=Tasks&action=DetailView&record=${record}`, BASE_URL).toString());
  await page.waitForLoadState('networkidle'); await expandAll(page);
  const found = await page.locator(`text=${name}`).count();
  expect(found).toBeGreaterThan(0);
});
