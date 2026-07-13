# Dashboard Test Plan

## Application Overview

The SinergiaCRM Dashboard (Home page at index.php?module=Home&action=index) is the landing page after login. It displays:

1. **Dashlets** (main content area):
   - **My Calls** — Shows upcoming calls in a data table with pagination. Columns: Subject, Related to, Start Date, Accept?, Status.
   - **My Meetings** — Shows upcoming meetings in a data table with pagination. Columns: Subject, Related to, Start Date, Accept?, Status.
   - **My Open Tasks** — Shows open tasks in a data table with pagination. Columns: Subject, Related to, Priority, Status, Start Date, Due Date.
   - **SinergiaCRM News** — Displays an external iframe from https://www.sinergiatic.org/actualitat-sinergiacrm-news/ with news articles.

2. **Right sidebar** (desktop only — `.sidebar` element):
   - **Recently Viewed** (`<h2>Recently Viewed</h2>`) — Shows last visited records as links.
   - **Admin actions** (`<h2>Admin actions</h2>` and `#admin_link`) — Shows admin links: Administration, Studio, Custom views, Repair, System Settings.

Each dashlet has an `<h3>` title (e.g., "My Calls", "My Meetings", "My Open Tasks", "SinergiaCRM News - SinergiaTIC") and action buttons (Edit, Refresh, Delete).

On tablet/mobile devices the dashlets are still visible but the right sidebar is not rendered (responsive layout hides it).

## Test Scenarios

### 1. Dashboard

**Seed:** ``

#### 1.1. TC01 — Dashboard page loads and shows suiteCRM Dashboard heading

**File:** `specs/common/dashboard.spec.ts`

**Steps:**
  1. Navigate to index.php?module=Home&action=index
    - expect: Page URL contains module=Home&action=index
    - expect: Page title contains 'Home » SinergiaCRM'
  2. Check the SuiteCRM Dashboard heading link exists
    - expect: A link with text 'SUITECRM DASHBOARD' is visible

#### 1.2. TC02 — All four dashlets are visible on the dashboard

**File:** `specs/common/dashboard.spec.ts`

**Steps:**
  1. Navigate to index.php?module=Home&action=index
    - expect: Page URL contains module=Home&action=index
  2. Check for the My Calls dashlet heading
    - expect: An <h3> heading with text 'My Calls' is visible
  3. Check for the My Meetings dashlet heading
    - expect: An <h3> heading with text 'My Meetings' is visible
  4. Check for the My Open Tasks dashlet heading
    - expect: An <h3> heading with text 'My Open Tasks' is visible
  5. Check for the SinergiaCRM News dashlet heading
    - expect: An <h3> heading with text containing 'SinergiaCRM News' is visible

#### 1.3. TC03 — Each dashlet has action buttons (Edit, Refresh, Delete)

**File:** `specs/common/dashboard.spec.ts`

**Steps:**
  1. Navigate to index.php?module=Home&action=index
    - expect: Page URL contains module=Home&action=index
  2. Check My Calls dashlet action buttons
    - expect: My Calls dashlet has Edit, Refresh, and Delete action links
  3. Check My Meetings dashlet action buttons
    - expect: My Meetings dashlet has Edit, Refresh, and Delete action links
  4. Check My Open Tasks dashlet action buttons
    - expect: My Open Tasks dashlet has Edit, Refresh, and Delete action links
  5. Check SinergiaCRM News dashlet action buttons
    - expect: SinergiaCRM News dashlet has Edit, Refresh, and Delete action links

#### 1.4. TC04 — My Calls dashlet shows data table or empty state

**File:** `specs/common/dashboard.spec.ts`

**Steps:**
  1. Navigate to index.php?module=Home&action=index
    - expect: Page URL contains module=Home&action=index
  2. Locate the My Calls dashlet table
    - expect: The dashlet shows column headers: Subject, Related to, Start Date, Accept?, Status
    - expect: The dashlet contains either data rows or 'No Data' text

#### 1.5. TC05 — My Meetings dashlet shows data table or empty state

**File:** `specs/common/dashboard.spec.ts`

**Steps:**
  1. Navigate to index.php?module=Home&action=index
    - expect: Page URL contains module=Home&action=index
  2. Locate the My Meetings dashlet table
    - expect: The dashlet shows column headers: Subject, Related to, Start Date, Accept?, Status
    - expect: The dashlet contains either data rows or 'No Data' text

#### 1.6. TC06 — My Open Tasks dashlet shows data table or empty state

**File:** `specs/common/dashboard.spec.ts`

**Steps:**
  1. Navigate to index.php?module=Home&action=index
    - expect: Page URL contains module=Home&action=index
  2. Locate the My Open Tasks dashlet table
    - expect: The dashlet shows column headers: Subject, Related to, Priority, Status, Start Date, Due Date
    - expect: The dashlet contains either data rows or 'No Data' text

#### 1.7. TC07 — SinergiaCRM News dashlet loads an iframe

**File:** `specs/common/dashboard.spec.ts`

**Steps:**
  1. Navigate to index.php?module=Home&action=index
    - expect: Page URL contains module=Home&action=index
  2. Locate the SinergiaCRM News dashlet iframe
    - expect: An iframe is present in the SinergiaCRM News dashlet section
    - expect: The iframe has a title 'SinergiaCRM News - SinergiaTIC'

#### 1.8. TC08 — Right sidebar shows Recently Viewed section

**File:** `specs/desktop/dashboard-sidebar.spec.ts`

**Steps:**
  1. Navigate to index.php?module=Home&action=index
    - expect: Page URL contains module=Home&action=index
  2. Locate the sidebar
    - expect: A `.sidebar` element is visible
  3. Check Recently Viewed section
    - expect: An <h2> heading with text 'Recently Viewed' is visible in the sidebar
  4. Check that recently viewed records appear as links
    - expect: At least one record link is visible in the Recently Viewed section

#### 1.9. TC09 — Right sidebar shows Admin actions section

**File:** `specs/desktop/dashboard-sidebar.spec.ts`

**Steps:**
  1. Navigate to index.php?module=Home&action=index
    - expect: Page URL contains module=Home&action=index
  2. Check Admin actions heading
    - expect: An <h2> heading with text 'Admin actions' is visible in the sidebar
  3. Check admin links are present
    - expect: The #admin_link element is visible
    - expect: Links for Administration, Studio, Custom views, Repair, and System Settings are present
