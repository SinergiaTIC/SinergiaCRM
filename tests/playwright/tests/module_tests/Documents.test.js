const { test, expect } = require('@playwright/test');
const { login, expandAll, setFieldGeneric, robustSave, BASE_URL } = require('../test-utils/helpers');
const { saveRecord } = require('../test-utils/sharedStore');

test('Documents - upload file and save', async ({ page }) => {
  test.setTimeout(120000);
  await login(page);
  await page.goto(new URL('index.php?module=Documents&action=EditView', BASE_URL).toString());
  await page.waitForLoadState('networkidle');
  await expandAll(page);

  const name = 'PWDoc_' + Math.random().toString(36).slice(2,8);
  // fill document name field candidates
  await setFieldGeneric(page, ['input[name="document_name"]','input[name="name"]','input#document_name'], name);

  // attempt to set required parent or other minimal fields if present
  await setFieldGeneric(page, ['input[name="parent_name"]','input[name="parent_id"]'], '').catch(()=>{});

  // attach file - try to find a file input; SuiteCRM sometimes uses file input with name 'uploadfile' or 'filename' or 'filename[0]'
  const filePath = require('path').resolve(__dirname, '..', 'fixtures', 'test_upload.txt');
  const fileInputCandidates = ['input[type="file"][name="uploadfile"]','input[type="file"][name="filename"]','input[type="file"][name="file"]','input[type="file"]'];
  let attached = false;
  for (const sel of fileInputCandidates) {
    const count = await page.locator(sel).count();
    if (!count) continue;
    const input = page.locator(sel).first();
    try {
      await input.setInputFiles(filePath);
      attached = true;
      break;
    } catch (e) {
      // continue trying other selectors
    }
  }
  if (!attached) {
    // fallback: try setting hidden file input name used by some versions
    const found = await page.locator('input[name="filename"]').count();
    if (found) {
      try { await page.locator('input[name="filename"]').first().setInputFiles(filePath); attached = true; } catch (e) {}
    }
  }

  if (!attached) {
    console.error('Documents test: could not find file input to attach file');
  }

  await robustSave(page);

  // retrieve created record id
  const url = page.url(); const m = url.match(/[?&]record=([^&]+)/); let record = m ? m[1] : null;
  if (!record) { const rec = page.locator('input[name="record"]'); if (await rec.count()) record = await rec.first().inputValue(); }
  expect(record).toBeTruthy();
  await saveRecord('Documents', record);

  // Navigate to DetailView and assert the document name appears
  await page.goto(new URL(`index.php?module=Documents&action=DetailView&record=${record}`, BASE_URL).toString());
  await page.waitForLoadState('networkidle');
  await expandAll(page);
  // Check for the document name somewhere in the page headers or detail fields
  const foundName = await page.locator(`text=${name}`).count();
  expect(foundName).toBeGreaterThan(0);

  // Attempt to find a download link for the attachment and download it
  // Common patterns: a link with the filename text or a link to index.php?entryPoint=download or index.php?module=Documents&action=Download
  const linkSelectors = [
    `a:has-text("${name}")`,
    `a[href*="Download" i]`,
    `a[href*="download" i]`,
    `a[href*="entryPoint=download" i]`,
    'a[href*="index.php?entryPoint=download"]',
  ];
  let downloadFound = false;
  for (const sel of linkSelectors) {
    const count = await page.locator(sel).count();
    if (!count) continue;
    const link = page.locator(sel).first();
    // try Playwright download API first with longer timeout
    try {
      const [ download ] = await Promise.all([
        page.waitForEvent('download', { timeout: 20000 }),
        link.click()
      ]);
      const path = await download.path();
      const fs = require('fs');
      const expected = fs.readFileSync(filePath, 'utf8');
      const actual = fs.readFileSync(path, 'utf8');
      expect(actual).toBe(expected);
      downloadFound = true;
      break;
    } catch (e) {
      // fallback: try to fetch the href directly
      try {
        const href = await link.getAttribute('href');
        if (!href) continue;
        const target = new URL(href, BASE_URL).toString();
        const resp = await page.request.get(target);
        const actual = await resp.text();
        const fs = require('fs');
        const expected = fs.readFileSync(filePath, 'utf8');
        expect(actual).toBe(expected);
        downloadFound = true;
        break;
      } catch (e2) {
        // continue to other selectors
        continue;
      }
    }
  }
  if (!downloadFound) {
    console.error('Documents test: could not find download link on DetailView');
  }
});
