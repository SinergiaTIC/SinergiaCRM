const { test, expect } = require('@playwright/test');
const { login, expandAll, setFieldGeneric, robustSave, BASE_URL } = require('../test-utils/helpers');
const { waitForRecord, saveRecord } = require('../test-utils/sharedStore');

test('stic_Contacts_Relationships - minimal fields (requires Contacts)', async ({ page }) => {
  await login(page);
  // wait for Contacts id created by Contacts test (or its saved name)
  const contactId = await waitForRecord('Contacts', 60 * 1000);
  let contactName = await waitForRecord('Contacts_name', 60 * 1000);
  if (!contactId && !contactName) test.skip(true, 'Contacts record not available');

  await page.goto(new URL('index.php?module=stic_Contacts_Relationships&action=EditView', BASE_URL).toString());
  await page.waitForLoadState('networkidle');
  await expandAll(page);
  // required: contact relate field and relationship_type
  // The EditView uses the relate field name `stic_contacts_relationships_contacts_name` and its id field
  // `stic_contacts_relationships_contactscontacts_ida`. Fill the name (so SQS can resolve) and set the hidden id as fallback.
  const contactValue = contactName || contactId;
  if (contactValue) {
    // if name not available in the store but we have the id, try to read it from the contact DetailView
    if (!contactName && contactId) {
      try {
        await page.goto(new URL(`index.php?module=Contacts&action=DetailView&record=${contactId}`, BASE_URL).toString());
        await page.waitForLoadState('networkidle');
        // try several selectors to extract display name
        contactName = await page.evaluate(() => {
          const candidates = ['h2', 'h1', '.record-label', '#detail_header .record-label', 'span#fullname', 'span#name_text', '.moduleTitle', 'h2.page-title'];
          for (const s of candidates) {
            const el = document.querySelector(s);
            if (el && el.textContent && el.textContent.trim()) return el.textContent.trim();
          }
          // fallback: try meta title
          return document.title || '';
        });
      } catch (e) {
        // ignore and continue
      }
      // navigate back to the Relationship EditView
      await page.goto(new URL('index.php?module=stic_Contacts_Relationships&action=EditView', BASE_URL).toString());
      await page.waitForLoadState('networkidle');
      await expandAll(page);
    }
  // fill visible relate textbox with the resolved name (prefer contactName)
  await setFieldGeneric(page, ['input[name="stic_contacts_relationships_contacts_name"]'], contactName || contactValue);
    // if we have the raw id, set the hidden id input too and dispatch events (ensures server-side validation)
    if (contactId) {
      await page.evaluate((val) => {
        const hid = document.querySelector('input[name="stic_contacts_relationships_contactscontacts_ida"]');
        const vis = document.querySelector('input[name="stic_contacts_relationships_contacts_name"]');
        if (hid) {
          hid.value = val;
          hid.dispatchEvent(new Event('input', { bubbles: true }));
          hid.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (vis) {
          vis.dispatchEvent(new Event('input', { bubbles: true }));
          vis.dispatchEvent(new Event('change', { bubbles: true }));
        }
      }, contactId);
    }
  }
  // Populate relate fields using the app's popup return handler (set_return) which performs the correct wiring.
  await page.evaluate(({ nameVal, idVal }) => {
    const payload = {
      form_name: 'EditView',
      name_to_value_array: {
        stic_contacts_relationships_contacts_name: nameVal,
        stic_contacts_relationships_contactscontacts_ida: idVal,
      },
    };
    try {
      if (typeof set_return === 'function') {
        set_return(payload);
        return;
      }
    } catch (e) {}
    try {
      if (window && window.SUGAR && typeof window.SUGAR.set_return === 'function') {
        window.SUGAR.set_return(payload);
        return;
      }
    } catch (e) {}
    // fallback: set DOM values directly
    const vis = document.querySelector('input[name="stic_contacts_relationships_contacts_name"]');
    const hid = document.querySelector('input[name="stic_contacts_relationships_contactscontacts_ida"]');
    if (vis) {
      vis.value = nameVal || '';
      vis.setAttribute('value', nameVal || '');
      vis.dispatchEvent(new Event('input', { bubbles: true }));
      vis.dispatchEvent(new Event('change', { bubbles: true }));
    }
    if (hid) {
      hid.value = idVal || '';
      hid.setAttribute('value', idVal || '');
      hid.dispatchEvent(new Event('input', { bubbles: true }));
      hid.dispatchEvent(new Event('change', { bubbles: true }));
    }
  }, { nameVal: contactName || '', idVal: contactId || '' });
  // sanity assert that either visible name or hidden id is set
  const visVal = await page.evaluate(() => (document.querySelector('input[name="stic_contacts_relationships_contacts_name"]') || {}).value || '');
  const hidVal = await page.evaluate(() => (document.querySelector('input[name="stic_contacts_relationships_contactscontacts_ida"]') || {}).value || '');
  expect(visVal || hidVal).toBeTruthy();
  // relationship_type is required; pick a non-empty option (label example: 'Donant')
  await setFieldGeneric(page, ['select[name="relationship_type"]'], 'Donant');
  await robustSave(page);
  const url = page.url();
  const m = url.match(/[?&]record=([^&]+)/);
  const record = m ? m[1] : null;
  expect(record).toBeTruthy();
  await saveRecord('stic_Contacts_Relationships', record);
  // verify detail view shows the contact name or id
  await page.goto(new URL(`index.php?module=stic_Contacts_Relationships&action=DetailView&record=${record}`, BASE_URL).toString());
  await page.waitForLoadState('networkidle'); await expandAll(page);
  const found = await page.locator(`text=${contactName || contactId}`).count();
  expect(found).toBeGreaterThan(0);
});
