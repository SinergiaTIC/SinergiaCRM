# AOS_Invoices Test Plan

## Application Overview

This module manages the complete lifecycle of invoices within SinergiaCRM (based on SuiteCRM's AOS_Invoices). It enables users to create, view, edit, duplicate, cancel/annul invoices, and print them as PDFs. It integrates with the VeriFactu Spanish tax validation system for electronic invoice submission to the AEAT (Spanish Tax Agency).

**Key pages:**

- **ListView** (`index.php?module=AOS_Invoices&action=index`): Displays all invoices in a table with columns: Title, LBL_VERIFACTU_INVOICE_TYPE, Num, Quote Number, Status, LBL_VERIFACTU_AEAT_STATUS, LBL_VERIFACTU_VALID_INVOICE, Grand Total, Invoice Date, Due Date, Contact, Account, User (13 columns on desktop; many hidden on mobile via hidden-xs class, only Title visible on mobile). Supports sorting by Title, bulk actions (ACTION on desktop / BULK ACTION on mobile), a filter/search dialog (Quick & Advanced tabs), column chooser, and pagination.
- **EditView** (`index.php?module=AOS_Invoices&action=EditView`): Create/edit form. Sections: Overview (Title, Assigned to, VeriFactu Invoice Type, Status, Number/Date fields, Description, VeriFactu rectification panel), Invoice To (Account/Contact, Billing/Shipping addresses), Line Items (products & services with pricing), and Totals (Subtotal, Discount, Shipping, Tax, Grand Total).
- **DetailView** (`index.php?module=AOS_Invoices&action=DetailView`): Read-only display of all invoice fields with action buttons (Edit, Duplicate, Delete, Find Duplicates, Print as PDF, Email PDF, Email Invoice, View Change Log) and a PDF Template selector.

**Status lifecycle:** Draft (default for newly created invoices) → Emitted (after VeriFactu submission to AEAT — validated and accepted). Once emitted, the Status can be manually edited to Paid or Unpaid.

**Device differences:**

- Desktop: Sidebar with shortcuts (Create, View Invoices, Recently Viewed, Import, Import Line Items, Administration) is visible.
- Mobile/Tablet: Sidebar is hidden. Navigation items available in top menu. Only `name` column visible by default on mobile list view.

**VeriFactu integration:** Invoices can be sent to the AEAT via "Send to AEAT" action (available from the actions dropdown on DetailView for draft invoices). This changes status from Draft to Emitted and assigns an Invoice Number. Once emitted, invoices can be:

- Sent to AEAT for validation (via `sendToAEAT()` action)
- Rectified via "Crear factura rectificativa" (via `CreateRectifiedInvoice` action)
- Revoked/cancelled via AEAT (via `CancelInvoice` action), which sets VeriFactu status to "Factura anulada en AEAT"

## Test Scenarios

## Prerequisites

### Digital Certificate for AEAT

All scenarios that send invoices to AEAT (Sections 7 and 8.4, 8.5, 8.7, and 9.2) require a valid digital certificate configured in Administration.

**Certificate file:** `.private/cert` (relative to project root)
**Certificate password:** `1234`
**Upload URL:** `index.php?module=Administration&action=SticManageCertificate`

The certificate must be uploaded **programmatically** as part of test setup (in `global-setup.ts` or a `beforeAll` hook), not manually. The upload flow is:

1. Navigate to the certificate management page
2. Upload the certificate file from `.private/cert`
3. Enter password `1234`
4. Submit the form
5. Verify the certificate is active

If the certificate upload fails or no certificate is present, the following scenarios must be skipped (`test.skip`): 7.2, 7.3, 7.4, 8.4, 8.5, 8.7, 9.2.

### 1. List View

**Seed:** `specs/seed.spec.ts`

#### 1.1. loads-list-view

**File:** `specs/modules/AOS_invoices/common/list-view.spec.ts`

**Steps:**

1. Navigate to AOS_Invoices module (index.php?module=AOS_Invoices&action=index)
   - expect: The page title should contain 'Invoices'
   - expect: The ListView table should be visible
   - expect: The heading should show 'INVOICES'

#### 1.2. list-view-columns-and-sorting

**File:** `specs/modules/AOS_invoices/common/list-view.spec.ts`

**Steps:**

1. Navigate to AOS_Invoices ListView
   - expect: The 'Title' column header should be visible and clickable for sorting

2. Click on the 'Title' column header
   - expect: The column should toggle sort direction (ASC/DESC indicator visible next to column name)

#### 1.3. list-view-pagination

**File:** `specs/modules/AOS_invoices/common/list-view-pagination.spec.ts`

**Steps:**

1. Create enough invoice records via UI to trigger pagination (e.g., 21+ records via a helper loop using `EditViewPage` + keyboard Enter). WARN: This is slow; consider `test.fixme()` if performance is a concern.
   - expect: The 'Next' pagination button should be enabled

2. Click the 'Next' pagination button
   - expect: The page should advance to the next set of records
   - expect: The URL should contain offset parameter

3. Click the 'Previous' pagination button
   - expect: The page should return to the first set of records

#### 1.4. list-view-search-filter

**File:** `specs/modules/AOS_invoices/common/list-view.spec.ts`

**Steps:**

1. Navigate to AOS_Invoices ListView
   - expect: The filter icon should be visible in the header

2. Click the filter icon
   - expect: A filter dialog should open with 'Quick Filter' and 'Advanced Filter' tabs

3. Verify the Quick Filter fields are present: Invoice Number, Title, Quote Number, Status, LBL_VERIFACTU_AEAT_STATUS, LBL_VERIFACTU_INVOICE_TYPE, LBL_VERIFACTU_VALID_INVOICE, Grand Total (with operator dropdown), Invoice Date (with operator dropdown), Due Date (with operator dropdown), Contact (with Select/Clear buttons), Account (with Select/Clear buttons), Assigned to. Also verify My Items and My Favorites checkboxes, plus Search and Clear buttons.
   - expect: All expected filter fields should be visible

4. Enter a search term in the 'Title' field and click 'Search'
   - expect: The list should filter to show only records matching the search term

5. Click 'Clear' to reset the filter
   - expect: All filter fields should be cleared
   - expect: The list should show all records again

#### 1.5. list-view-bulk-actions

**File:** `specs/modules/AOS_invoices/common/list-view.spec.ts`

**Steps:**

1. Navigate to AOS_Invoices ListView
   - expect: The ACTION/BULK ACTION button should be visible at the top of the list view

2. Click 'ACTION' (or 'BULK ACTION' on mobile viewport)
   - expect: A dropdown menu should appear with options: Mass Update, Duplicate & Mass Update, Export, Print as PDF, Add to signature process, LBL_MASS_SEND_AEAT (untranslated in English: "Enviar facturas a AEAT" in Spanish), Delete

3. Select one or more invoice checkboxes
   - expect: A bottom form should appear with 'Assign' and 'Remove' buttons and a 'Group' dropdown

4. Use the 'Group' dropdown and select a group, then click 'Assign'
   - expect: The selected records should be assigned to the chosen group

5. Select one or more invoice checkboxes and click 'Remove'
   - expect: The selected records should be removed (note: SuiteCRM mass update confirmation may vary)

#### 1.6. list-view-column-chooser

**File:** `specs/modules/AOS_invoices/common/list-view.spec.ts`

**Steps:**

1. Navigate to AOS_Invoices ListView
   - expect: The column chooser icon should be visible

2. Click the column chooser icon
   - expect: A column chooser dialog should open, allowing reordering or toggling of visible columns

### 2. Record Creation

**Seed:** `specs/seed.spec.ts`

#### 2.1. create-invoice-basic

**File:** `specs/modules/AOS_invoices/common/create-invoice.spec.ts`

**Steps:**

1. Create a minimal Account record via the UI (navigate to Accounts module → Create, fill in Account Name with a unique name, save, and note the record ID from the DetailView URL)
   - expect: The Account DetailView should load with the expected name

2. Navigate to AOS_Invoices module and click 'CREATE' or the Create Invoice button
   - expect: The EditView form should load with the heading 'CREATE'
   - expect: The form should have Overview tab, Save and Cancel buttons

3. Fill in the 'Title' field with a unique test invoice name (use unique timestamp name)
   - expect: The Title field should show the entered text

4. Set the 'Invoice Date' to today's date
   - expect: The Invoice Date field should show today's date

5. Set the 'Due Date' to a future date
   - expect: The Due Date field should show the entered date

6. In the 'Invoice To' section, click the Account select button. This opens a **new browser window/popup** (not an inline modal). In the popup, search for and select the test account created in step 1, then close/switch back to the main window.
   WARN: Account selection opens a separate popup window. Tests must handle this via `page.waitForEvent('popup')` and switch contexts.
   - expect: The Account field should show the selected account name
   - expect: Billing address fields should auto-populate from the Account (if address data was configured)

7. Click 'SAVE'
   WARN: The Save button may not respond to standard Playwright `click()` due to SuiteCRM's event binding. Use `page.evaluate(() => document.querySelector('input[value="Save"]').click())` or a JavaScript-based click as a fallback.
   - expect: The page should navigate to the DetailView of the newly created invoice
   - expect: The URL should contain 'action=DetailView' and a 'record' parameter
   - expect: The invoice title should match what was entered

8. Verify the DetailView shows the expected fields
   - expect: The Title should match the entered value
   - expect: The Account name should be visible in the invoice detail
   - expect: The Status should be 'Draft' (lowercase, visible as "draft" on DetailView)

#### 2.2. create-invoice-with-account

**File:** `specs/modules/AOS_invoices/common/create-invoice.spec.ts`

**Steps:**

1. Create a test Account record via the UI (navigate to Accounts module, fill in name, save, and note the record ID from the DetailView URL)
   - expect: The Account DetailView should load with the expected name

2. Navigate to AOS_Invoices EditView for creation
   - expect: The EditView form should load

3. Fill in 'Title' with a unique test name
4. In the 'Invoice To' section, click the Account select button and search for/select the test account
   - expect: The Account field should show the selected account name

5. Verify the billing address fields auto-fill from the Account
   - expect: Billing address fields (Street, City, etc.) should be populated from the Account if available

6. Click 'SAVE'
   - expect: The page should navigate to DetailView
   - expect: The Account name should be visible in the invoice detail

#### 2.3. create-invoice-with-contact

**File:** `specs/modules/AOS_invoices/common/create-invoice.spec.ts`

**Steps:**

1. Create a test Contact record via the UI (navigate to Contacts module, fill in first/last name, save, and note the record ID from the DetailView URL)
   - expect: The Contact DetailView should load with the expected name

2. Navigate to AOS_Invoices EditView for creation
   - expect: The EditView form should load

3. Fill in 'Title' with a unique test name
4. In the 'Invoice To' section, click the Contact select button and search for/select the test contact
   - expect: The Contact field should show the selected contact name

5. Fill in billing address manually
   - expect: The address fields should accept input

6. Click 'SAVE'
   - expect: The page should navigate to DetailView
   - expect: The Contact name should be visible in the invoice detail

#### 2.4. create-invoice-with-line-items

**File:** `specs/modules/AOS_invoices/common/create-invoice.spec.ts`

**Steps:**

1. Create a test Product record via the UI (navigate to AOS_Products EditView, fill in name, price, cost, save, and note the record ID from the DetailView URL)
   - expect: The Product DetailView should load with the expected values

2. Navigate to AOS_Invoices EditView for creation
   - expect: The EditView form should load

3. Fill in 'Title' with a unique test name
4. Click 'Add Product Line' button in the Line Items section
   - expect: A new product line row should appear in the Line Items table

5. Click the Product select icon and search for/select the test Product
   - expect: The Product name should appear in the line item
   - expect: The Part Number, List price, and other product fields should auto-populate

6. Enter a Quantity (e.g., 2)
   - expect: The Qty field shows the entered value

7. Verify the Total for the line item is calculated (Quantity × Sale Price)
   - expect: The Total column should display the calculated amount

8. Click 'SAVE'
   - expect: The page should navigate to DetailView
   - expect: The invoice should show the line item product and its pricing on the detail view

#### 2.5. create-invoice-validation-required-fields

**File:** `specs/modules/AOS_invoices/common/create-invoice.spec.ts`

**Steps:**

1. Navigate to AOS_Invoices EditView for creation
   - expect: The EditView form should load

2. Leave 'Title' field empty and click 'SAVE'
   - expect: The form should not submit
   - expect: A validation error should indicate that Title is a required field
   - expect: The page should remain on EditView and not navigate to DetailView

3. Fill in 'Title' and click 'SAVE'
   - expect: The invoice should be saved successfully and navigate to DetailView

#### 2.6. create-invoice-cancel-creation

**File:** `specs/modules/AOS_invoices/common/create-invoice.spec.ts`

**Steps:**

1. Navigate to AOS_Invoices EditView for creation
   - expect: The EditView form should load

2. Fill in the 'Title' field with a test name
3. Click 'CANCEL'
   - expect: The page should navigate back to the ListView
   - expect: No new invoice record should be created (verify by absence from ListView)

### 3. Record Detail View

**Seed:** `specs/seed.spec.ts`

#### 3.1. detail-view-field-display

**File:** `specs/modules/AOS_invoices/common/detail-view.spec.ts`

**Steps:**

1. Create an invoice via the UI with known field values (title, invoice date, due date, description). Note the record ID from the DetailView URL.
   - expect: The invoice DetailView should load showing the entered values

2. Navigate to the invoice's DetailView URL
   - expect: The DetailView should show the heading with the invoice title

3. Verify the displayed fields match the created values
   - expect: Title should match
   - expect: Status should be visible
   - expect: Invoice Date should match
   - expect: Due Date should match
   - expect: Description should match
   - expect: Assigned To should show the current user

#### 3.2. detail-view-action-buttons

**File:** `specs/modules/AOS_invoices/common/detail-view.spec.ts`

**Steps:**

1. Navigate to an existing invoice's DetailView
   - expect: The DetailView should load with the invoice data

2. Verify the presence of action buttons
   - expect: The actions dropdown (labeled "Acciones" in Spanish locale) should be visible
   - expect: Edit button should be visible
   - expect: Duplicate button should be visible
   - expect: Delete button should be visible
   - expect: Print as PDF button should be visible
   - expect: Email PDF button should be visible
   - expect: View Change Log button should be visible
   - expect: Find Duplicates button should be visible
   - expect: Email Invoice button should be visible
   - expect: Three VeriFactu action buttons (sendToAEAT, CancelInvoice, CreateRectifiedInvoice) should be visible (may be disabled depending on invoice state, rendered as `button[onclick*="sendToAEAT"]`, `button[onclick*="CancelInvoice"]`, `button[onclick*="CreateRectifiedInvoice"]`)

3. Click 'Edit'
   - expect: The page should navigate to EditView for this invoice record

#### 3.3. detail-view-print-as-pdf-after-aeat

**File:** `specs/modules/AOS_invoices/common/detail-view.spec.ts`

**Steps:**

1. Create a draft invoice via the UI with a unique title. Note the record ID from the DetailView URL.


   - expect: The invoice DetailView should load showing Draft status

2. Navigate to the invoice's DetailView and send it to AEAT via the actions dropdown (select "Send to AEAT" and accept the confirmation).
   WARN: See Prerequisites section for certificate requirements. Skip with test.skip if not available.


   - expect: The invoice status should now be 'Emitida' with an assigned invoice number

3. Open the "Acciones" (Actions) dropdown and click the "Print as PDF" option


   - expect: An inline form overlay (popup) should appear with the text "Please Select a Template:-" and a Cancel button

4. Click the 'Ejemplo - Verifactu - Factura Normal' template link inside the popup
   Note: Templates are rendered as clickable `<a>` links, not a `<select>` dropdown. Clicking a template link directly triggers PDF generation — there is no separate "Generar PDF" button.


   - expect: A PDF should be generated and downloaded (response should be a PDF file)
   - expect: WARN: This test may need to handle file download behavior depending on the Playwright config
   - expect: WARN: The PDF Template popup requires configured PDF templates to exist in the system. If no templates are available, this test should be skipped or the template must be pre-seeded.

#### 3.4. detail-view-duplicate-record

**File:** `specs/modules/AOS_invoices/common/detail-view.spec.ts`

**Steps:**

1. Navigate to an existing invoice's DetailView
   - expect: The DetailView should load

2. Click 'Duplicate'
   - expect: The page should navigate to EditView for a new record
   - expect: The Title field should contain the original title with a suffix (e.g., 'OriginalTitle (Copy)')

3. Modify the Title to make it unique and click 'SAVE'
   - expect: A new invoice record should be created
   - expect: The new invoice's DetailView should show the modified title

### 4. Record Update

**Seed:** `specs/seed.spec.ts`

#### 4.1. edit-invoice-fields

**File:** `specs/modules/AOS_invoices/common/edit-invoice.spec.ts`

**Steps:**

1. Create an invoice via the UI with a unique title and invoice date. Note the record ID from the DetailView URL.
   - expect: The invoice DetailView should load showing the created invoice

2. Navigate to the invoice's DetailView and click 'Edit'
   - expect: The EditView should load with existing values pre-filled

3. Modify the 'Title' field to a new value
   - expect: The field should show the new value

4. Verify the Status field is locked/disabled (draft invoices cannot change status without AEAT submission first)
   - expect: The Status field should be disabled (no selectable options visible) because the invoice is in Draft status

5. Click 'SAVE'
   - expect: The page should navigate back to DetailView
   - expect: The Title should show the updated value
   - expect: The Status should remain 'Draft'

#### 4.2. edit-invoice-line-items

**File:** `specs/modules/AOS_invoices/common/edit-invoice.spec.ts`

**Steps:**

1. Create an invoice via the UI (without line items) with a unique title. Note the record ID from the DetailView URL.
   - expect: The invoice DetailView should load showing the created invoice

2. Navigate to EditView for the invoice
   - expect: The form should load with the invoice data

3. Click 'Add Product Line', select a product, and enter quantity
   - expect: A line item row appears with product details and calculated total

4. Click 'SAVE'
   - expect: The invoice DetailView should now display the line item

#### 4.3. edit-invoice-cancel-editing

**File:** `specs/modules/AOS_invoices/common/edit-invoice.spec.ts`

**Steps:**

1. Navigate to an existing invoice's EditView, modify the Title
   - expect: The Title field should show the modified value

2. Click 'CANCEL'
   - expect: The page should navigate back to the DetailView
   - expect: The original Title should NOT reflect the change made in EditView

### 5. Record Deletion

**Seed:** `specs/seed.spec.ts`

#### 5.1. delete-invoice-from-listview

**File:** `specs/modules/AOS_invoices/common/delete-invoice.spec.ts`

**Steps:**

1. Create an invoice via the UI with a unique title. Note the record ID from the DetailView URL.
   - expect: The invoice DetailView should load showing the created invoice

2. Navigate to the ListView
   - expect: The ListView should load

3. Select the checkbox next to the created invoice
   - expect: The checkbox should be selected

4. Click the ACTION (or BULK ACTION) button, then select 'Delete' from the dropdown
   - expect: A confirmation dialog may appear (if enabled)

5. Confirm removal if dialog appears
   - expect: The invoice should no longer appear in the ListView

6. Verify via API that the invoice is deleted or marked as deleted
   - expect: API should confirm the record no longer exists or has deleted=1

### 6. Sidebar (Desktop)

**Seed:** `specs/seed.spec.ts`

#### 6.1. sidebar-desktop-visibility

**File:** `specs/modules/AOS_invoices/desktop/sidebar.spec.ts`

**Steps:**

1. Navigate to AOS_Invoices ListView on a desktop viewport size
   - expect: The sidebar should be visible on the left or right side

2. Verify sidebar content
   - expect: The sidebar should contain shortcuts such as 'Create Invoice', 'View Invoices', 'Import', 'Import Line Items', 'Recently Viewed' with recent invoice entries

#### 6.2. sidebar-create-shortcut

**File:** `specs/modules/AOS_invoices/desktop/sidebar.spec.ts`

**Steps:**

1. On desktop viewport, click 'Create Invoice' link in the sidebar
   - expect: The page should navigate to the EditView for creating a new invoice

### 7. Complex Workflows (VeriFactu / AEAT)

**Seed:** `specs/seed.spec.ts`

#### 7.1. send-invoice-to-aeat-and-cancel

**File:** `specs/modules/AOS_invoices/common/verifactu.spec.ts`

**Steps:**

1. Create a draft invoice via the UI (ensure Status is 'Draft'). Note the record ID from the DetailView URL.
   - expect: The invoice DetailView should load showing Draft status

2. Navigate to the invoice's DetailView
   - expect: The DetailView should show the invoice

3. From the actions dropdown, select 'Send to AEAT' (Enviar a AEAT)
   - expect: A confirmation dialog should appear with message: 'Esta factura está en estado "Borrador". ¿Confirma que desea marcarla como "Emitida" y enviarla a la AEAT?'

4. Click 'Cancel' on the confirmation dialog
   - expect: The invoice should remain in Draft status
   - expect: The invoice should not have an invoice number assigned

#### 7.2. send-invoice-to-aeat-and-accept

**File:** `specs/modules/AOS_invoices/common/verifactu.spec.ts`

**Steps:**

1. Create a draft invoice via the UI (ensure Status is 'Draft'). Note the record ID from the DetailView URL.
   WARN: See Prerequisites section for certificate requirements. Skip with test.skip if not available.
   - expect: The invoice DetailView should load showing Draft status

2. Navigate to the invoice's DetailView
   - expect: The DetailView should show the invoice

3. From the actions dropdown, select 'Send to AEAT' (Enviar a AEAT)
   - expect: A confirmation dialog should appear

4. Accept the confirmation dialog
   - expect: A success banner should appear: 'Comunicación correcta con la AEAT y aceptada'

5. Verify the invoice status changed
   - expect: Expand the AEAT status panel (LBL_AEAT_STATUS_PANEL)
   - expect: A success banner should appear: 'Comunicación correcta con la AEAT y aceptada'
   - expect: The invoice status should now be 'Emitida' (emitted)
   - expect: An invoice number should be assigned (format: YEAR-NUMBER, e.g., 2026-0001)
   - expect: The AEAT status field (LBL_VERIFACTU_AEAT_STATUS) should show 'Enviado y aceptado'
   - expect: A warning message should state: 'Esta factura ha sido enviada a la AEAT y no puede ser modificada ni eliminada'

#### 7.3. generate-rectificative-invoice-for-emitted-invoice

> **Note:** This scenario is the canonical test for rectificative invoice creation. Rectificative invoices **must** be generated from an existing emitted invoice's DetailView via the "Crear factura rectificativa" action — they cannot be created standalone. This scenario supersedes the previously planned (and removed) scenario 2.7.

**File:** `specs/modules/AOS_invoices/common/verifactu.spec.ts`

**Steps:**

1. Create an emitted invoice that was sent to AEAT. First create a draft invoice via the UI, then navigate to its DetailView and use 'Send to AEAT' to emit it.
   WARN: See Prerequisites section for certificate requirements. Skip with test.skip if not available.
   - expect: The invoice DetailView should show 'Emitida' status and an assigned invoice number

2. Navigate to the emitted invoice's DetailView
   - expect: The actions dropdown should have 'Crear factura rectificativa' enabled

3. Expand the AEAT status panel (labeled "LBL_AEAT_STATUS_PANEL" in English, "Estado de la factura en la AEAT" in Spanish) by clicking the panel header button
   - expect: The AEAT status panel should be visible with AEAT response details

4. Click 'Crear factura rectificativa'
   - expect: The EditView should open for a new invoice with 'Factura rectificativa' type pre-selected
   - expect: The VeriFactu rectification panel should be visible

5. Fill in the rectification details and click 'SAVE'
   - expect: The rectificative invoice should be created and linked to the original

#### 7.4. revoke-emitted-invoice

> **Note:** This scenario tests the cancellation/annulment of an emitted invoice through the AEAT VeriFactu system. The action is available only for invoices that have already been sent to and accepted by AEAT (Emitida status).

**File:** `specs/modules/AOS_invoices/common/verifactu.spec.ts`

**Steps:**

1. Create an emitted invoice that was sent to AEAT. First create a draft invoice via the UI, then navigate to its DetailView and use 'Send to AEAT' to emit it.
   WARN: See Prerequisites section for certificate requirements. Skip with test.skip if not available.
   - expect: The invoice DetailView should show 'Emitida' status and an assigned invoice number

2. Expand the AEAT status panel (labeled "LBL_AEAT_STATUS_PANEL" in English, "Estado de la factura en la AEAT" in Spanish) by clicking the panel header button
   - expect: The AEAT status panel should be visible with AEAT response details

3. From the actions dropdown, select the Cancel/Annul invoice action (rendered as an `input[type="button"]` with onclick calling `CancelInvoice`)
   - expect: A confirmation dialog should appear asking to confirm the annulment

4. Accept the confirmation dialog
   - expect: The page should navigate and show a success message or the invoice DetailView updated
   - expect: Expand the AEAT status panel (LBL_AEAT_STATUS_PANEL)
   - expect: The AEAT response field (LBL_VERIFACTU_AEAT_RESPONSE) should contain "Factura anulada en AEAT. CSV:"
   - expect: LBL_VERIFACTU_VALID_INVOICE should show "No"
   - expect: LBL_VERIFACTU_CSV should show an AEAT CSV code (format: A-XXXXXXXXXXXX)
   - expect: LBL_VERIFACTU_HASH should show a 64-character uppercase hex string
   - expect: LBL_VERIFACTU_CHECK_URL should match pattern: https://_.aeat.es/wlpl/TIKE-CONT/ValidarQR?nif=_&numserie=_&fecha=_&importe=\*

### 8. Invoice Numbering & Type Settings (AOS Admin)

> **⚠️ XSRF Navigation Warning:** Direct URL navigation to `index.php?module=Administration&action=AOSAdmin` may trigger an XSRF token validation error. Always navigate via the menu path: **Admin → Sales Module Settings**. If the direct URL is used, ensure the XSRF token is properly handled (e.g., by first loading the Admin page to establish a valid session context).

**Seed:** `specs/seed.spec.ts`

#### 8.1. view-invoice-settings-in-aos-admin

**File:** `specs/modules/AOS_invoices/admin/aos-invoices.spec.ts`

**Steps:**

1. Navigate to AOS Admin (use the menu path **Admin → Sales Module Settings** rather than the direct URL to avoid XSRF validation errors; if using the direct URL `index.php?module=Administration&action=AOSAdmin`, navigate via a preceding Admin page load first)
   - expect: The page title should contain 'AOS Admin'
   - expect: The 'Invoice Settings' section heading should be visible

2. Locate the Invoice Settings section and verify the three invoice type rows
   - expect: A row with name 'Factura normal' should exist with format 'YYYY-0000', next number '1', and example '2026-0001'
   - expect: A row with name 'Factura rectificativa' should exist with format 'RECT-YYYY-0000', next number '1', and example 'RECT-2026-0001'
   - expect: A row with name 'Factura alternativa' should exist with format 'ALT-YYYY-000', next number '5', and example 'ALT-2026-005'

3. Verify the default checkbox state
   - expect: 'Factura rectificativa' should have its default checkbox checked and disabled (readonly)
   - expect: 'Factura normal' and 'Factura alternativa' should have their default checkboxes unchecked

4. Verify the delete/clear buttons
   - expect: 'Factura normal' should have a clear/delete button visible
   - expect: 'Factura alternativa' should have a clear/delete button visible
   - expect: 'Factura rectificativa' should NOT have a clear/delete button

#### 8.2. edit-invoice-type-settings

**File:** `specs/modules/AOS_invoices/admin/aos-invoices.spec.ts`

**Steps:**

1. Navigate to AOS Admin and locate the Invoice Settings section
   - expect: The Invoice Settings section should be visible

2. Change the 'Factura normal' name to a temporary name (e.g., 'Factura normal TEST'), change its format to 'YYYY-0001', and set its next number to '10'
   - expect: The fields should show the entered values

3. Click the 'Save' button at the bottom of the Invoice Settings section
   - expect: A success message should appear indicating settings were saved

4. Re-navigate to AOS Admin and verify the changes persist
   - expect: 'Factura normal' name should be 'Factura normal TEST'
   - expect: 'Factura normal' format should be 'YYYY-0001'
   - expect: 'Factura normal' next number should be '10'

5. Restore the original settings ('Factura normal', 'YYYY-0000', next number '1') and save again to clean up
   - expect: The settings should be restored successfully

#### 8.3. verify-default-invoice-type-on-create

**File:** `specs/modules/AOS_invoices/admin/aos-invoices.spec.ts`

**Steps:**

1. Navigate to AOS Admin and note which type has the default checkbox checked
   - expect: The default type should be identified (e.g., 'Factura normal' by default behavior)

2. Navigate to AOS_Invoices EditView for creating a new invoice
   - expect: The EditView form should load

3. Locate the LBL_VERIFACTU_INVOICE_TYPE dropdown
   - expect: The pre-selected option should match the default type from AOS Admin (e.g., 'Factura normal')
   - expect: WARN: If no default is explicitly set, the system should default to the first configured type or a sensible default

#### 8.4. verify-invoice-number-format-after-aeat-submission

**File:** `specs/modules/AOS_invoices/admin/aos-invoices.spec.ts`

**Steps:**

1. Navigate to AOS Admin and note the 'Factura normal' next number and format
   - expect: The current next number and format should be recorded

2. Navigate to AOS_Invoices EditView and create a new draft invoice with type 'Factura normal'. Ensure it has a valid product line item (required for AEAT submission).
   - expect: The invoice should be created in Draft status
   - expect: The invoice number should still be '0' (draft invoices have no assigned number)

3. Navigate to the invoice's DetailView and send it to AEAT via the actions dropdown

   WARN: See Prerequisites section for certificate requirements. Skip with test.skip if not available.
   - expect: A success message should appear: 'Comunicación correcta con la AEAT y aceptada'

4. Verify the invoice number follows the expected format
   - expect: The invoice number should match the 'Factura normal' format pattern (e.g., 'YYYY-NNNN' based on the configured format)
   - expect: The invoice number should be the expected sequential number (e.g., if next was 1, the number should end with '-0001')

5. After the test, clean up by deleting the invoice via API. If the next number was consumed, note it in test output but do NOT automatically reset it (manual cleanup may be needed).
   - expect: The invoice record should be deleted

#### 8.5. verify-rectificative-invoice-number-format

**File:** `specs/modules/AOS_invoices/admin/aos-invoices.spec.ts`

**Steps:**

1. Navigate to AOS Admin and note the 'Factura rectificativa' format and next number
   - expect: The rectificative format and next number should be recorded

2. Create a normal invoice, send it to AEAT to emit it (creating an emitted invoice), then create a rectificative invoice from its DetailView via 'Crear factura rectificativa'

   WARN: Requires an emitted invoice. See Prerequisites section for certificate requirements. Skip with test.skip if not available.
   - expect: The rectificative invoice should be created

3. Send the rectificative invoice to AEAT
   - expect: A success message should appear

4. Verify the rectificative invoice number follows the rectificative format
   - expect: The invoice number should match the 'Factura rectificativa' format (e.g., 'RECT-YYYY-NNNN')

5. Clean up by deleting created records
   - expect: Both invoices should be deleted

#### 8.6. verify-invoice-type-dropdown-options-match-admin-settings

**File:** `specs/modules/AOS_invoices/admin/aos-invoices.spec.ts`

**Steps:**

1. Navigate to AOS Admin and list all configured invoice type names
   - expect: The list of type names should be recorded (e.g., ['Factura normal', 'Factura rectificativa', 'Factura alternativa'])

2. Navigate to AOS_Invoices EditView for creating a new invoice
   - expect: The EditView form should load

3. Open the LBL_VERIFACTU_INVOICE_TYPE dropdown
   - expect: The number of option elements in the DOM should match the number of configured types in AOS Admin
   - expect: 'Factura normal' and 'Factura alternativa' should be visible
   - expect: 'Factura rectificativa' should exist in the DOM but be hidden (`display: none`) — it is only selectable on emitted invoices via the DetailView action, not on new drafts
   - expect: No extra/unexpected options should exist in the dropdown

#### 8.7. verify-next-number-increments

**File:** `specs/modules/AOS_invoices/admin/aos-invoices.spec.ts`

**Steps:**

1. Navigate to AOS Admin and note the current next number for 'Factura normal'
   - expect: The starting next number should be recorded

2. Create a draft invoice with type 'Factura normal', add line items, then send it to AEAT

   WARN: This test depends on AEAT availability. See Prerequisites section for certificate requirements. Skip with test.skip if not available.
   - expect: The invoice should be successfully emitted

3. Navigate back to AOS Admin and check the next number for 'Factura normal'
   - expect: The next number should have incremented by 1 from the previously recorded value

4. Clean up by deleting the invoice via API
   - expect: The invoice record should be deleted

**WARN (admin test isolation):** Tests in this section modify global AOS Admin invoice settings that affect all invoices and users. To avoid conflicts with other tests:

- Run admin config tests **before** other invoice tests, save original settings, and restore them after (preferred).
- Alternatively, use a separate test user/instance for admin tests.
- If conflicts cannot be avoided, mark admin config tests (especially 8.2) as `test.fixme()` or skip them.

---

### 9. Query AEAT Invoices (VeriFactu Visualization)

> **Overview:** The "Query AEAT Invoices" feature (accessible from the sidebar shortcut or direct URL) displays a flat table view of invoices that have been sent to and processed by the AEAT (Spanish Tax Agency). It shows AEAT-submitted invoice records with columns such as Invoice Number, Date, Amount, and Assigned User. A month-based filter is available. The page heading reads "AEAT INVOICE QUERY (VERIFACTU)". This is a read-only view.

**Seed:** `specs/seed.spec.ts`

#### 9.1. navigate-to-query-aeat-invoices

**File:** `specs/modules/AOS_invoices/common/query-aeat-invoices.spec.ts`

**Steps:**

1. Navigate to the Query AEAT Invoices page (via the sidebar shortcut "Query AEAT Invoices" or directly to `index.php?module=AOS_Invoices&action=QueryAeatInvoices`)
   WARN: The browser may simplify the URL to just `index.php`. Use `page.url()` to assert query parameters contain `action=QueryAeatInvoices`.
   - expect: The page should load without JavaScript errors (PHP warnings for missing VeriFactu query labels are expected)
   - expect: The heading should contain "AEAT INVOICE QUERY (VERIFACTU)"
   - expect: A table/list of AEAT-submitted invoices should be visible with columns (Invoice Number, Date, Amount, Assigned User)
   - expect: A month filter (e.g., "Filters Mes: 07 X 1 records") should be visible

#### 9.2. verify-aeat-invoice-list-contents

**File:** `specs/modules/AOS_invoices/common/query-aeat-invoices.spec.ts`

**Steps:**

1. Create an emitted invoice via the UI (draft → send to AEAT). Note the record ID and invoice number.
   WARN: See Prerequisites section for certificate requirements. Skip with test.skip if not available.
   - expect: The invoice should be successfully emitted with an assigned invoice number

2. Navigate to the Query AEAT Invoices page
   - expect: The page should load the invoice list

3. Locate the emitted invoice in the list
   - expect: The invoice should appear in the table with the correct invoice number
   - expect: The invoice number should be a clickable link that navigates to the invoice's DetailView
   - expect: The date, amount, and assigned user columns should contain data
   - expect: If a rectificative invoice was created from this invoice, it should also appear in the list (plan-level, same table)
   - expect: If the invoice was cancelled/annulled, it should still appear in the list with updated status information

4. Click on an invoice number link in the table
   - expect: The page should navigate to the invoice's DetailView
   - expect: URL should contain `action=DetailView&record=`

#### 9.3. verify-aeat-invoice-filter

**File:** `specs/modules/AOS_invoices/common/query-aeat-invoices.spec.ts`

**Steps:**

1. Navigate to the Query AEAT Invoices page
   - expect: The page should load the invoice list with a month filter

2. Interact with the month filter
   - expect: The filter bar should show a selectable month (e.g., "Mes: 07") with current record count
   - expect: Changing the filter month should update the displayed records

3. Verify draft invoices are excluded
   - expect: Draft invoices that have NOT been sent to AEAT should NOT appear in this view (only AEAT-submitted invoices are shown)

**WARN (certificate dependency):** Scenario 9.2 requires an emitted invoice and a valid digital certificate — see Prerequisites section. Scenario 9.1 (navigation) and 9.3 (filter) do NOT require a certificate and can run independently.

---

## Label Resolution Strategy

| Type                      | Strategy                                                                                                | Example                                                                        |
| ------------------------- | ------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------ |
| App-level labels          | Use `t("LBL_KEY")` from `#helpers/i18n` (reads `.labels-cache.json`)                                    | `t("LBL_SAVE_BUTTON")`                                                         |
| Module-specific labels    | Use `page.evaluate(() => SUGAR.language.get("AOS_Invoices", "LBL_KEY"))` as fallback                    | `SUGAR.language.get("AOS_Invoices", "LBL_STATUS")`                             |
| Status dropdown values    | Use `app_list_strings.dom_status_list` or literal string matching (`'Paid'`, `'Unpaid'`, `'Cancelled'`) | `page.getByRole('combobox', { name: 'Status' }).selectOption('Paid')`          |
| VeriFactu dropdown values | Direct literal string matching (`'Factura normal'`, `'Factura rectificativa'`, `'Factura alternativa'`) | `page.getByLabel('LBL_VERIFACTU_INVOICE_TYPE').selectOption('Factura normal')` |
| VeriFactu field name      | The EditView `<select>` uses `name="verifactu_invoice_type_c"` (not `stic_invoice_type_c`)              | `page.locator('select[name="verifactu_invoice_type_c"]')`                      |
| Button text               | Use `page.getByRole('button', { name: 'SAVE' })` or `page.getByRole('button', { name: 'CANCEL' })`      | —                                                                              |
| Action drop-down items    | Use `page.getByRole('link', { name: 'Edit' })`                                                          | —                                                                              |

> **WARN**: Several VeriFactu-related labels (`LBL_VERIFACTU_INVOICE_TYPE`, `LBL_VERIFACTU_AEAT_STATUS`, `LBL_VERIFACTU_VALID_INVOICE`) are missing English translations and display as raw language keys in the English locale. Tests interacting with these labels should use `SUGAR.language.get("AOS_Invoices", "LBL_KEY")` or direct DOM selectors rather than translated text.

**Spanish translations for untranslated VeriFactu labels (English locale shows raw LBL\_\* keys):**

| Raw key (English)                    | Spanish translation                                                                                       | Notes                                                                            |
| ------------------------------------ | --------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- |
| LBL_VERIFACTU_INVOICE_TYPE           | Tipo de factura                                                                                           | Dropdown values: Factura normal, Factura rectificativa, Factura alternativa      |
| LBL_VERIFACTU_AEAT_STATUS            | Estado AEAT de la factura                                                                                 | Shows status of AEAT submission                                                  |
| LBL_VERIFACTU_VALID_INVOICE          | Factura vigente                                                                                           | "Sí" (valid) / "No" (invalid/revoked)                                            |
| LBL_VERIFACTU_AEAT_RESPONSE          | Respuesta AEAT de la factura                                                                              | Contains AEAT response text                                                      |
| LBL_VERIFACTU_CSV                    | Codigo seguro de verificación                                                                             | AEAT CSV code                                                                    |
| LBL_VERIFACTU_HASH                   | Hash de la factura                                                                                        | 64-char uppercase hex                                                            |
| LBL_VERIFACTU_PREVIOUS_HASH          | Hash de la factura anterior                                                                               | Previous invoice hash                                                            |
| LBL_VERIFACTU_CHECK_URL              | Url de verificación de la factura                                                                         | AEAT QR verification URL                                                         |
| LBL_VERIFACTU_SUBMITTED_AT           | Fecha de envío a Verifactu                                                                                | Timestamp of AEAT submission                                                     |
| LBL_AEAT_STATUS_PANEL                | Estado de la factura en la AEAT                                                                           | Collapsible panel in DetailView                                                  |
| LBL_MASS_SEND_AEAT                   | Enviar facturas a AEAT                                                                                    | Bulk action option                                                               |
| LBL_SEND_TO_AEAT_CONFIRM_DRAFT       | Esta factura está en estado "Borrador". ¿Confirma que desea marcarla como "Emitida" y enviarla a la AEAT? | Confirmation dialog for send                                                     |
| LBL_CANCEL_INVOICE                   | Anular factura                                                                                            | Action in DetailView dropdown                                                    |
| LBL_CANCEL_INVOICE_CONFIRM           | ¿Está seguro de que desea anular esta factura en AEAT? (full text contains line breaks)                   | Confirmation dialog for cancel                                                   |
| LBL_CREATE_RECTIFIED_INVOICE         | Crear factura rectificativa                                                                               | Action in DetailView dropdown                                                    |
| LBL_VERIFACTU_INLINE_EDIT_RESTRICTED | (displayed as raw key)                                                                                    | Displayed in ListView columns when inline editing is restricted for field values |
| LBL_INVOICE_CANCELLED_SUCCESS        | Factura anulada correctamente en AEAT                                                                     | Success message after cancel                                                     |
| LBL_AEAT_COMMUNICATION_SUCCESS       | Comunicación correcta con la AEAT                                                                         | Success message prefix                                                           |
| LBL_AEAT_COMMUNICATION_AND_ACCEPTED  | y aceptada                                                                                                | Appended to success message                                                      |

> **Strategies for these labels:** For field labels in the DetailView, locate by text content or role. For dropdown values and verification, use the Spanish text directly since that is what the CRM displays regardless of locale settings for these specific values.

## Known WARN / Gaps

- **WARN (locator):** The `fillRelateField` helper from `#helpers/generic/FieldHelpers` uses `querySelectorAll` with an `_ida$` naming convention for hidden relate ID inputs. If SuiteCRM changes the naming convention, the hidden input is silently not updated and test assertions pass with stale data.
- **WARN (accessibility):** The filter dialog uses an icon button (``) without visible text for the filter toggle. Locators should use `page.getByRole('link', { name: 'Filter' })` or `page.locator('[description="Filter"]')`.
- **WARN (accessibility):** The bulk action button has non-descriptive text "BULK ACTION". Use `page.getByRole('link', { name: /(ACTION|BULK ACTION)/ })`.
- **WARN (accessibility):** Edit action per row uses an icon (``) without visible text. Use `page.getByRole('link', { name: 'Edit' })`.
- **WARN (PHP warnings):** The module emits PHP warnings (`Undefined array key "LBL_VERIFACTU_QUERY_MENU"`) on every page. The Query AEAT Invoices page additionally emits 3 more: `LBL_VERIFACTU_QUERY_TYPE_F1`, `LBL_VERIFACTU_QUERY_STATUS_CANCELLED`, `LBL_VERIFACTU_QUERY_LINK_TOOLTIP`. Tests should wait for content to render rather than relying on `networkidle`.
- **WARN (pagination):** Pagination tests require >20 records to activate. Create records via a UI batch-creation helper loop (e.g., using `EditViewPage` + keyboard Enter). If the loop is too slow, mark as `test.fixme()`. API seeding is forbidden.
- **GAP (send to AEAT):** Scenario 7.2 (send-invoice-to-aeat-and-accept) requires a valid digital certificate — see Prerequisites section. The `CancelInvoice` action may require re-authentication depending on AEAT configuration.
- **GAP (certificate-dependent scenarios):** Scenarios 7.2, 7.3, 7.4, 8.4, 8.5, 8.7, and 9.2 all depend on a valid digital certificate for AEAT submission. See Prerequisites section.
- **GAP (Email PDF/Invoice):** Email actions may trigger an email compose dialog or redirect to Emails module. These are noted but not fully scoped for automated validation in this plan.
- **WARN (status editing):** Invoice status cannot be changed while the invoice is in 'Draft' status. The EditView Status dropdown is disabled for draft invoices. Status changes (to Paid or Unpaid) are only possible after the invoice has been sent to and accepted by AEAT (status becomes 'Emitted'). Alternatively, if an invoice was saved with a non-Draft status at creation (e.g., explicitly selecting 'Paid' before saving), the status can be edited but this bypasses VeriFactu workflow.
- **WARN (admin test isolation):** Scenario 8.2 (edit-invoice-type-settings) modifies global AOS Admin invoice settings that affect all invoices and users. Consider one of these approaches to avoid conflicts:
  - **Option A (preferred):** Run admin config tests **before** other invoice tests, save original settings at the start of the suite, and restore them after.
  - **Option B:** Use a separate test user/instance for admin tests.
  - **Option C:** Mark admin config tests (especially 8.2) as `test.fixme()` or skip them if conflicts cannot be avoided.

## Cleanup Patterns

Every test should clean up records created during the test to maintain isolation:

```typescript
import { SuiteCRMApi } from "#helpers/api";

test.afterEach(async () => {
  const api = new SuiteCRMApi();
  await api.login();
  // Clean up invoice
  if (invoiceId) {
    await api.deleteEntry("AOS_Invoices", invoiceId);
  }
  // Clean up related records (products, accounts, contacts)
  if (productId) {
    await api.deleteEntry("AOS_Products", productId);
  }
  if (accountId) {
    await api.deleteEntry("Accounts", accountId);
  }
  if (contactId) {
    await api.deleteEntry("Contacts", contactId);
  }
});
```

> **⚠️ API Usage Policy:** The API must **never** be used for creating seed data or test fixtures. See the "cleanup" pattern above for the only acceptable API usage (deleting records after tests). All seed records must be created through the UI using the browser — just like a real user would.
>
> For a reference pattern on UI-only record creation, see `specs/modules/accounts/helpers/createRecord.ts` (uses `EditViewPage` + keyboard Enter).

## Certificate Setup (global-setup)

The digital certificate must be uploaded once before any AEAT-dependent tests run. Add this step to `global-setup.ts`:

1. Navigate to `index.php?module=Administration&action=SticManageCertificate`
2. Locate the file upload field for the certificate
3. Upload `.private/cert` (path relative to project root)
4. Enter password `1234` in the password field
5. Click Save/Upload
6. Confirm the success message and that the certificate is now active

This runs once per test suite execution, not per test file. If the certificate is already present (e.g., from a previous run), the upload step may be skipped — check for the certificate's presence first.

## File Structure

```
specs/modules/AOS_invoices/
├── admin/                           # AOS Admin settings tests
│   └── aos-invoices.spec.ts    # 8.1, 8.2, 8.3, 8.4, 8.5, 8.6, 8.7
├── common/                          # Device-agnostic tests (desktop + tablet + mobile)
│   ├── list-view.spec.ts            # 1.1, 1.2, 1.4, 1.5, 1.6
│   ├── list-view-pagination.spec.ts # 1.3
│   ├── create-invoice.spec.ts       # 2.1, 2.2, 2.3, 2.4, 2.5, 2.6
│   ├── detail-view.spec.ts          # 3.1, 3.2, 3.3, 3.4
│   ├── edit-invoice.spec.ts         # 4.1, 4.2, 4.3
│   ├── delete-invoice.spec.ts       # 5.1
│   ├── verifactu.spec.ts            # 7.1, 7.2, 7.3, 7.4
│   └── query-aeat-invoices.spec.ts  # 9.1, 9.2, 9.3
├── desktop/                         # Desktop-only tests
│   └── sidebar.spec.ts              # 6.1, 6.2
├── visual/                          # Future visual regression tests (empty initially)
│   └── .gitkeep
├── helpers/                         # Module-specific shared workflows (future)
│   └── .gitkeep
└── plan.md                          # This file
```
