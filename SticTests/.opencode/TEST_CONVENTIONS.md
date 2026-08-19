## API Usage Policy

**API is strictly forbidden for creating seed data or test fixtures.** The API may only be used for:

1. **Login/Authentication** — handled by `global-setup.ts` using the SuiteCRM REST API to establish a session and cache labels.
2. **Cleanup** — deleting records created during a test, via `SuiteCRMApi.deleteEntry()` in `afterEach` or `test.step("cleanup", ...)` blocks.

### ❌ Never do this
```typescript
// FORBIDDEN: Creating seed data via API
const api = new SuiteCRMApi();
await api.login();
const invoiceId = await api.createEntry("AOS_Invoices", { name: "Test", ... });
```

### ✅ Do this instead
```typescript
// Create seed data through the UI
const editView = new EditViewPage(page, "AOS_Invoices");
await editView.navigateToCreate();
await editView.fillField("name", uniqueName);
await page.keyboard.press("Enter");
await page.waitForURL(/action=DetailView/);
const recordId = new URL(page.url()).searchParams.get("record") ?? "";
```

### ✅ Acceptable: Use API for cleanup
```typescript
test.afterEach(async () => {
  const api = new SuiteCRMApi();
  await api.login();
  await api.deleteEntry("AOS_Invoices", recordId);
});
```

---

## Playwright Best Practices (from research_v2.md)

**Code Structure:**

- Use English for all test names, comments, and variable names — no localized identifiers in test code
- Use `test.step()` only when grouping distinct workflow phases, never for single steps.
  Follow the seed → verify → cleanup pattern for multi-phase tests:
  ```ts
  await test.step("create seed record via UI", async () => { ... });
  await test.step("verify list view elements", async () => { ... });
  await test.step("cleanup seed record", async () => { ... });
  ```
- Prefer Node.js subpath imports (`#pages/`, `#helpers/`, `#settings`) over relative paths for better module resolution
- Follow the exact POM locator priority: `getByRole` > `getByLabel` > `getByText` > stable CSS `#id`

**Test Maintenance:**

- Always use POM locators and `t()`, never hardcoded strings
- When `t()` doesn't have the required label (module-specific strings not in
  `.labels-cache.json`), resolve it from the browser runtime via `SUGAR.language.get()`:
  ```ts
  const label = await page.evaluate(() =>
    (window as any).SUGAR?.language?.get("<Module>", "<KEY>"),
  );
  ```
  Always scope assertions to the container where the text appears (e.g., `#sidebar_container`)
  to avoid hidden DOM duplicates from SuiteP's 3-bar responsive layout.
- Use `as any` for `storageState` casts — this is a known limitation that enables cleaner code
- Treat all visual baseline PNGs as disposable test placeholders — regenerate when UI changes

**Quality Requirements:**

- Never use index-based selectors (`.nth`, `:last-child`, etc.)
- Never use class names, deep descendant chains, or auto-generated IDs as locators
- Never use `page.evaluate()` for assertions
- Extract shared workflows (record creation, navigation patterns, etc.) into
  `specs/modules/<module>/helpers/<name>.ts`. Import via relative path, not subpath
  imports. Keep helpers focused on the module's domain — avoid generic utility files.
- For intentional accessibility gaps, always add `// WARN:` comments

---

## Detailed WARN/Annotation Requirements

**For Poor Semantics/Accessibility:**
When forced to use suboptimal selectors due to poor UI semantics:

```ts
// WARN: <brief explanation of the accessibility gap or poor semantics>.
// <what would be the ideal solution if the UI were fixed>.
// If the <selector> changes, this locator silently breaks.
```

Examples from research_v2.md:

- `// WARN: login inputs use placeholder instead of <label> — no accessible name.`
- `// WARN: hamburger toggle has no id, aria-label, or data-testid.`
- `// WARN: index-based checkbox selection is fragile — adding a row shifts indices.`

**For Flaky Tests:**
When a test has potential flakiness due to timing or race conditions:

```ts
// WARN: FLAKY — <explain the timing/race condition issue>.
// <suggest the fix to make this reliable>.
```

Examples (applied when observed):

- `// WARN: FLAKY — Test depends on networkidle which is unreliable.`
- `// WARN: FLAKY — Implicit wait timing may cause intermittent failures.`

\*\*Every locator, test, or page object generated or reviewed by you must have at least one `
// WARN:` comment explaining its circumstances. This enables developers to fix root causes rather than chasing broken tests.

---

## Primary Agent Characteristics

You handle standard development requests directly. You have full access to:

- All file systems tools for reading, writing, editing, and creating test files
- Browser tools for manual exploration and testing
- Task tool for delegating to any of the three Playwright subagents
- Code execution tools for test debugging and validation

### Delegation Rules

When delegating, always complete one phase before starting the next. Delegation follows the plan → generate → heal order:

- **Phase 1 (Plan):** Explore the app and write the plan file yourself, or use `@playwright-test-planner` to explore and write it
- **Phase 2 (Generate):** Delegate to `@playwright-test-generator` — pass it the plan file path. The generator must read the plan file and use the `playwright-cli` skill to build tests
- **Phase 3 (Heal):** Delegate to `@playwright-test-healer` with the failing test names or file paths

## CRM Login and Environment Setup

Before working with SinergiaCRM tests, you need to setup your environment and login:

**Reading Credentials:**

1. Read the `.env` file in the playwright directory to get
   - `BASE_URL`: CRM base URL (e.g., `http://localhost:8000/sinergiacrm/`)
   - `INSTANCE_USER`: login username
   - `INSTANCE_PASSWORD`: login password

**Login Flow:**

1. Call `chrome-devtools_navigate_page` with the login URL: `${BASE_URL}index.php?action=Login&module=Users`
2. Call `chrome-devtools_fill` to enter credentials:
   - `#user_name` with `INSTANCE_USER`
   - `#username_password` with `INSTANCE_PASSWORD`
3. Call `chrome-devtools_click` on `#bigbutton` to submit
4. Call `chrome-devtools_wait_for` to wait for page to reach `module=Home&action=index`

**Session Management:**

- After login, you can delegate to subagents using `@playwright-test-planner`, `@playwright-test-generator`, or `@playwright-test-healer`
- The login session will persist within a single conversation

---

## Your Adaptive Capabilities

**Context Integration:**

- Review your test-s artifacts and prioritize consistency with the existing architecture patterns in `research_v2.md`
- Maintain the established directory structure: `specs/ → common/` for shared tests, and `specs/ → desktop/tablet/mobile/` per-device tests
- Preserve the `visual/` vs `functional/` project split in Playwright configuration
- Always respect the 47-module automation via `ModuleRegistry.ts`
- Maintain the `t()` i18n system for user-facing text without modifying label generation rules

**Decision Framework:**

- When a decision requires bypassing a convention, explain _why_ and document _the desired UI fix_ — this keeps code maintainable while reflecting current reality
- Balance between pragmatic test delivery and long-term maintainability based on urgency
- Always add contextual `
// WARN:` comments for any deviation from established patterns

This agent orchestrates the entire E2E testing process while enforcing Playwright best practices documented in `research_v2.md`. You focus on strategic coordination and ensuring tests are maintainable and effective. The three subagents handle the technical details (planning, generation, and healing).
