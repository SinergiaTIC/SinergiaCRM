const { test, expect } = require('@playwright/test');
const { login, expandAll, setFieldGeneric, robustSave, BASE_URL } = require('../test-utils/helpers');
const { saveRecord } = require('../test-utils/sharedStore');

test('Projects - minimal fields', async ({ page }) => {
  test.setTimeout(120000);
  await login(page);
  await page.goto(new URL('index.php?module=Project&action=EditView', BASE_URL).toString());
  await page.waitForLoadState('networkidle');
  await expandAll(page);
  const name = 'PWProject_' + Math.random().toString(36).slice(2,8);
  await setFieldGeneric(page, ['input[name="name"]','input[name="project_name"]'], name);
  // set required start date/time if present (try multiple candidate field names)
  await setFieldGeneric(page, ['input[name="date_start"]','input[name="date_s"]','input[name="project_date_start"]','input[name="date_start_date"]','input[name="project_start_date"]'], '21/10/2025');
  // some installs require a time as well
  await setFieldGeneric(page, ['input[name="time_start"]','input[name="date_start_time"]','input[name="project_time_start"]','input[name="time"]'], '10:00');
  // ensure the combined date_start field (some modules expect 'DD/MM/YYYY hh:mm') is set on the form
  await page.evaluate(() => {
    try {
      const f = document.forms['EditView'] || document.querySelector('form[name="EditView"]') || document.querySelector('form#EditView');
      if (f) {
        if (typeof f.date_start !== 'undefined') f.date_start.value = '21/10/2025 10:00';
        if (typeof f.time_start !== 'undefined') f.time_start.value = '10:00';
        // also try common named inputs
        const el = document.querySelector('input[name="date_start"]'); if (el) el.value = '21/10/2025 10:00';
        const el2 = document.querySelector('input[name="time_start"]'); if (el2) el2.value = '10:00';
      }
    } catch (e) {}
  });
  // dump date/start related inputs for debugging
  const dbg = await page.evaluate(() => {
    const res = [];
    const nodes = Array.from(document.querySelectorAll('input, select, textarea'));
    for (const n of nodes) {
      try {
        const name = n.name || n.id || n.getAttribute('data-name') || '';
        if (/date|start|time/i.test(name) || /date|start|time/i.test(n.className || '')) {
          res.push({ tag: n.tagName.toLowerCase(), name: name, id: n.id || null, value: n.value || null });
        }
      } catch (e) {}
    }
    return res;
  });
  console.debug('PROJECTS DEBUG inputs=', JSON.stringify(dbg));
  // Also set estimated start/end if present (some Project setups require these)
  await page.evaluate(() => {
    try {
      const est = document.querySelector('input[name="estimated_start_date"]');
      if (est) { est.value = document.querySelector('input[name="date_start"]')?.value || est.value || '21/10/2025 10:00'; est.dispatchEvent(new Event('input',{bubbles:true})); est.dispatchEvent(new Event('change',{bubbles:true})); }
      const est2 = document.querySelector('input[name="estimated_end_date"]');
      if (est2) { est2.value = '22/10/2025 10:00'; est2.dispatchEvent(new Event('input',{bubbles:true})); est2.dispatchEvent(new Event('change',{bubbles:true})); }
    } catch (e) {}
  });
  await robustSave(page);
  const url = page.url(); const m = url.match(/[?&]record=([^&]+)/); let record = m ? m[1] : null;
  if (!record) { const rec = page.locator('input[name="record"]'); if (await rec.count()) record = await rec.first().inputValue(); }
  if (!record) {
    // collect some debug info to understand why save didn't produce a record
    const pageTitle = await page.title();
    const pageUrl = page.url();
    const errors = [];
    const errEl = page.locator('.error, .alert, .help-block, .required, .validation-error');
    for (let i = 0; i < await errEl.count(); i++) errors.push(await errEl.nth(i).innerText());
    console.error('PROJECTS TEST SAVE FAILED; title=', pageTitle, 'url=', pageUrl, 'errors=', errors);
  }
  expect(record).toBeTruthy();
  await saveRecord('Project', record);
  // verify detail view shows the project name
  await page.goto(new URL(`index.php?module=Project&action=DetailView&record=${record}`, BASE_URL).toString());
  await page.waitForLoadState('networkidle'); await expandAll(page);
  const found = await page.locator(`text=${name}`).count();
  expect(found).toBeGreaterThan(0);
});
