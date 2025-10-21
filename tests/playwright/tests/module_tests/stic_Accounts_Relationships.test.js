const { test, expect } = require('@playwright/test');
const { login, expandAll, setFieldGeneric, robustSave, BASE_URL } = require('../test-utils/helpers');
const { waitForRecord, saveRecord } = require('../test-utils/sharedStore');

test('stic_Accounts_Relationships - minimal fields (requires Accounts)', async ({ page }) => {
  test.setTimeout(120000);
  await login(page);
  const accountId = await waitForRecord('Accounts', 60 * 1000);
  let accountName = await waitForRecord('Accounts_name', 60 * 1000);
  if (!accountId && !accountName) test.skip(true, 'Accounts record not available');

  await page.goto(new URL('index.php?module=stic_Accounts_Relationships&action=EditView', BASE_URL).toString());
  await page.waitForLoadState('networkidle');
  await expandAll(page);

  // populate relate fields (visible name and hidden id)
  if (!accountName && accountId) {
    try { await page.goto(new URL(`index.php?module=Accounts&action=DetailView&record=${accountId}`, BASE_URL).toString()); await page.waitForLoadState('networkidle');
      accountName = await page.evaluate(() => document.querySelector('h2')?.textContent?.trim() || document.title || '');
    } catch (e) {}
    await page.goto(new URL('index.php?module=stic_Accounts_Relationships&action=EditView', BASE_URL).toString()); await page.waitForLoadState('networkidle'); await expandAll(page);
  }
  // try to populate relate via set_return (preferred) or fallback to visible+hidden inputs
  const payload = {
    form_name: 'EditView',
    name_to_value_array: {}
  };
  payload.name_to_value_array['stic_accounts_relationships_accounts_name'] = accountName || '';
  payload.name_to_value_array['stic_accounts_relationships_accountsaccounts_ida'] = accountId || '';
  await page.evaluate((p) => {
    try {
      if (window.set_return) { window.set_return(p); return; }
      if (window.SUGAR && window.SUGAR.set_return) { window.SUGAR.set_return(p); return; }
    } catch (e) {}
    // fallback: set DOM inputs
    try {
      const vis = document.querySelector('input[name="stic_accounts_relationships_accounts_name"]'); if (vis) { vis.value = p.name_to_value_array['stic_accounts_relationships_accounts_name'] || ''; vis.dispatchEvent(new Event('input',{bubbles:true})); vis.dispatchEvent(new Event('change',{bubbles:true})); }
      const hid = document.querySelector('input[name="stic_accounts_relationships_accountsaccounts_ida"]'); if (hid) { hid.value = p.name_to_value_array['stic_accounts_relationships_accountsaccounts_ida'] || ''; hid.dispatchEvent(new Event('input',{bubbles:true})); hid.dispatchEvent(new Event('change',{bubbles:true})); }
    } catch (e) {}
  }, payload);

  // set relationship type if present
  await setFieldGeneric(page, ['select[name="relationship_type"]','select#relationship_type'], 'Client').catch(()=>{});

  // pick required fields if any (none strictly required in many setups)
  // debug snapshot before save
  console.debug('DEBUG stic_accounts: name=', accountName, 'id=', accountId);
  await robustSave(page);
  const url = page.url(); const m = url.match(/[?&]record=([^&]+)/); const record = m ? m[1] : null;
  expect(record).toBeTruthy();
  await saveRecord('stic_Accounts_Relationships', record);
  // verify detail view shows the account name
  await page.goto(new URL(`index.php?module=stic_Accounts_Relationships&action=DetailView&record=${record}`, BASE_URL).toString());
  await page.waitForLoadState('networkidle'); await expandAll(page);
  const found = await page.locator(`text=${accountName || accountId}`).count();
  expect(found).toBeGreaterThan(0);
});
