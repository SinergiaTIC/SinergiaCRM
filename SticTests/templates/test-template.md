# {ModuleName} Test Plan

> **AI reference:** Can be consumed by the `playwright-qa-orchestrator` to generate Playwright tests.

## Application Overview

_Describe what this module does, the key pages it exposes, and its main components._

- **URL:** `index.php?module={ModuleName}&action=index`
- **What it does:** {one paragraph describing the module's purpose}
- **Main components:** {list the main UI sections, tables, forms, subpanels}
- **Device differences:** {how the layout changes on tablet/mobile, if any}

**Example:**

```markdown
# {ModuleName} Test Plan

The Accounts module (`index.php?module=Accounts&action=index`) manages customer accounts. It displays:

1. **List view** — Data table with columns: Name, Phone, Email, Billing City.
2. **Detail view** — Full record breakdown with subpanels for Contacts, Opportunities, and Cases.
3. **Edit view** — Form with required fields: Name, Phone, Email.

On mobile the sidebar is hidden and the action bar collapses into a hamburger menu.
```

---

## Test Environment Prerequisites _(Optional)_

_Fill this section if the module depends on records in other modules. Otherwise delete it._

```markdown
## Test Environment Prerequisites

{N}. {Dependency Module}

- **Required:** {quantity and type of records needed}
- **Why:** {why this dependency exists for {ModuleName}}
```

**Example:**

```markdown
## Test Environment Prerequisites

1. Product Records (for Invoice module)

- **Required:** At least 2 products with pricing, stock, and tax configured
- **Why:** Invoice line items must reference valid product records
```

---

## Smoke Tests

_Verify the module loads without errors._

> [!TIP]
> Smoke tests are automatized. Ensure that the module you are testing is already registered at `sticTests/helpers/generic/ModuleRegistry.ts` with the correct module type.

---

## Functional Tests

_Test CRUD operations, validation, search, and module-specific workflows._

Template:

```markdown
## Test Scenarios

### {id}. {kebab-case-description}

**File:** `specs/modules/{module}/{device-scope}/{id}.spec.ts`

**Steps:**

1. {user action}
   - expect: {expected outcome}
     ...
     {N}. Cleanup seed record via API
```

Examples:

```markdown
## Test Scenarios

### 1.1 create-record-with-required-fields

**File:** `specs/modules/{module}/common/create-record-with-required-fields.spec.ts`

**Steps:**

1. Navigate to EditView
2. Fill required fields
3. Save via keyboard Enter
   - expect: redirect to DetailView
   - expect: record name visible in heading
4. Cleanup via API
```

```markdown
### 2.1 search-by-name-filters-results

**File:** `specs/modules/{module}/common/search-by-name-filters-results.spec.ts`

**Steps:**

1. Navigate to list view
2. Open search dialog, type one record's name, submit
   - expect: only the matching row is shown
3. Cleanup via API
```

---

## Visual Regression Tests

_Catch unintended UI changes via screenshots._

Template:

```markdown
## Visual Test Scenarios

#### {id}. {kebab-case-description}

**File:** `specs/modules/{module}/visual/{id}.spec.ts`

**Steps:**

1. Navigate to {page}
2. Wait for content to fully load
3. Take viewport screenshot
   - expect: `toHaveScreenshot()` matches baseline
```

Example:

```markdown
## Visual Test Scenarios

## 1.1. list-view-screenshot

**File:** `specs/modules/{module}/visual/list-view-screenshot.spec.ts`

**Steps:**

1. Navigate to list view
2. Wait for content to fully load (no spinners, no PHP errors)
3. Take viewport screenshot
   - expect: `toHaveScreenshot()` matches baseline
```

---

## File Structure

```
specs/
├── smoke/                     # Smoke tests
├── modules/{module}/
│   ├── common/                # Desktop, Tablet, Mobile
│   ├── desktop/               # Desktop only
│   ├── mobile/                # Mobile only (optional)
│   ├── visual/                # Visual regression (optional)
│   ├── helpers/               # Shared workflows
│   └── plan.md                # This file
```
