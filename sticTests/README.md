# SinergiaCRM Playwright Test Suite

E2E functional and visual regression tests for SinergiaCRM (SuiteCRM fork), written in TypeScript with Playwright.

## Prerequisites

- Node.js 22 (use `nvm use` — `.nvmrc` sets the version)
- Google Chrome installed (Playwright uses system Chrome via `channel: "chrome"`)
- SinergiaCRM running locally (default `http://localhost:8000/sinergiacrm/`)

## Setup

```bash
nvm use
npm install
```

## Running tests

```bash
npm run test                                # all projects, 3 devices
npm run test:smoke                          # smoke-tests only
npm run test:functional                     # functional only
npm run test:visual                         # visual only
npx playwright test --project=functional-desktop  # single project
npx playwright test --grep="login"          # filter tests by name
npm run test:ui                             # Playwright UI mode
npm run test:ui:functional                  # Playwright UI mode, functional tests only
npm run test:ui:visual                      # Playwright UI mode, visual tests only
npm run test:ui:smoke                       # UI mode, smoke-tests only
npm run test:debug                          # PWDEBUG=1
```

## Projects

7 projects — 3 devices × 2 types + smoke-tests:

| Project        | Purpose                                                      |
| -------------- | ------------------------------------------------------------ |
| `functional-*` | Behaviour validation (login, navigation, CRUD, module smoke) |
| `visual-*`     | Screenshot regression (baselines compared per run)           |
| `smoke-tests`  | Quick health checks — login and basic page load              |

| Device  | Viewport    |
| ------- | ----------- |
| desktop | 1920 × 1080 |
| tablet  | 768 × 1024  |
| mobile  | 375 × 812   |

Tests in `specs/common/` run on all devices. Tests in `specs/{device}/` run only on that device. Tests in `specs/smoke/` run in the `smoke-tests` project (desktop only, no retries).

## Environments

| Variable            | Default                              | Description    |
| ------------------- | ------------------------------------ | -------------- |
| `BASE_URL`          | `http://localhost:8000/sinergiacrm/` | CRM URL        |
| `INSTANCE_USER`     | `sinergiacrm`                        | Login username |
| `INSTANCE_PASSWORD` | `sinergiacrm`                        | Login password |

Override via `.env` file (copy from `.env.example`) or environment variables.

Language is auto-detected from the CRM's HTML `lang` attribute after login.

## Test structure

```
specs/
  common/               cross-device tests (login, dashboard, module smoke)
  desktop/              desktop-only (navigation)
  mobile/               mobile-only (hamburger menu)
  tablet/               (not yet used)
  smoke/                quick health checks (login, basic page load)
  visual/               screenshot tests by device
  modules/{module}/     per-module CRUD tests
```

## Visual tests

- Baselines stored per project name in snapshot directories
- Tolerance: 2% pixel diff, 0.2 threshold
- CI runs `--project=functional-*` only (visual baselines are updated on purpose, not on every CI run)

## Scripts

| Command                      | Action                                             |
| ---------------------------- | -------------------------------------------------- |
| `npm run test`               | Full suite (all projects)                          |
| `npm run test:smoke`         | Smoke tests only                                   |
| `npm run test:functional`    | Functional only (desktop + tablet + mobile)        |
| `npm run test:visual`        | Visual regression only (desktop + tablet + mobile) |
| `npm run test:ui`            | Playwright UI mode                                 |
| `npm run test:ui:smoke`      | UI mode, smoke-tests only                          |
| `npm run test:ui:functional` | UI mode, functional only                           |
| `npm run test:ui:visual`     | UI mode, visual only                               |
| `npm run test:debug`         | Debug with PWDEBUG                                 |
| `npm run typecheck`          | TypeScript check (`tsc --noEmit`)                  |

## Notes

- **4 workers** (`workers: 4`) with `fullyParallel: true`. Tests are fully parallelised within a project. Smoke tests run sequentially (retries: 0).
- Tests authenticate once in `global-setup` and reuse the session via `storageState`. The logout test saves the refreshed session back to the file.
- PHP errors (warnings, notices, deprecations) are soft-asserted on every page navigation.
- `channel: "chrome"` — uses system Google Chrome, not Playwright's bundled Chromium (not installable on Ubuntu 26.04).

## OpenCode Agents

OpenCode agents configured in `.opencode/` assist with E2E test development:

| Agent                        | Type     | Use                                                          |
| ---------------------------- | -------- | ------------------------------------------------------------ |
| `playwright-qa-orchestrator` | primary  | Coordinates plan → generate → heal cycle                     |
| `playwright-test-planner`    | subagent | Writes test plan `.md` to `specs/modules/<module>/plan.md`   |
| `playwright-test-generator`  | subagent | Reads plan, drives app via `playwright-cli`, generates tests |
| `playwright-test-healer`     | subagent | Debugs and fixes failing tests                               |

```bash
cd sticTests && opencode
```

Tab to the orchestrator or `@playwright-test-<planner|generator|healer>` to invoke.
Generated code uses `// WARN:` comments for accessibility/flakiness gaps.
