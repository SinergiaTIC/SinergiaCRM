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
npm run test                              # all projects, 3 devices
npx playwright test --project=functional-*  # functional only (skip screenshots)
npx playwright test --project=visual-*      # visual only (screenshot regression)
npx playwright test --project=functional-desktop  # single project
npx playwright test --grep="login"          # filter tests by name
npm run test:ui                             # Playwright UI mode
npm run test:debug                          # PWDEBUG=1
```

## Projects

6 projects — 3 devices × 2 types:

| Type | Purpose |
|------|---------|
| `functional-*` | Behaviour validation (login, navigation, CRUD, module smoke) |
| `visual-*` | Screenshot regression (baselines compared per run) |

| Device | Viewport |
|--------|----------|
| desktop | 1920 × 1080 |
| tablet | 768 × 1024 |
| mobile | 375 × 812 |

Tests in `specs/common/` run on all devices. Tests in `specs/{device}/` run only on that device.

## Environments

| Variable | Default | Description |
|----------|---------|-------------|
| `BASE_URL` | `http://localhost:8000/sinergiacrm/` | CRM URL |
| `INSTANCE_USER` | `sinergiacrm` | Login username |
| `INSTANCE_PASSWORD` | `sinergiacrm` | Login password |

Override via `.env` file (copy from `.env.example`) or environment variables.

Language is auto-detected from the CRM's HTML `lang` attribute after login.

## Test structure

```
specs/
  common/               cross-device tests (login, dashboard, module smoke)
  desktop/              desktop-only (navigation)
  mobile/               mobile-only (hamburger menu)
  tablet/               (not yet used)
  visual/               screenshot tests by device
  modules/{module}/     per-module CRUD tests
```

## Visual tests

- Baselines stored per project name in snapshot directories
- Tolerance: 2% pixel diff, 0.2 threshold
- CI runs `--project=functional-*` only (visual baselines are updated on purpose, not on every CI run)

## Scripts

| Command | Action |
|---------|--------|
| `npm run test` | Full suite |
| `npm run test:ui` | Playwright UI mode |
| `npm run test:debug` | Debug with PWDEBUG |
| `npm run typecheck` | TypeScript check (`tsc --noEmit`) |

## Notes

- **1 worker** (`workers: 1`). Parallelism is not supported — workers would share the same PHP session cookie and conflict on server-side state.
- Tests authenticate once in `global-setup` and reuse the session via `storageState`. The logout test saves the refreshed session back to the file.
- PHP errors (warnings, notices, deprecations) are soft-asserted on every page navigation.
- `channel: "chrome"` — uses system Google Chrome, not Playwright's bundled Chromium (not installable on Ubuntu 26.04).

## OpenCode Agents

OpenCode agents configured in `.opencode/` assist with E2E test development:

| Agent | Type | Use |
|---|---|---|
| `playwright-qa-orchestrator` | primary | Coordinates plan → generate → heal cycle |
| `playwright-test-planner` | subagent | Writes test plan `.md` to `specs/modules/<module>/plan.md` |
| `playwright-test-generator` | subagent | Reads plan, drives app via `playwright-cli`, generates tests |
| `playwright-test-healer` | subagent | Debugs and fixes failing tests |

```bash
cd playwright && opencode
```

Tab to the orchestrator or `@playwright-test-<planner|generator|healer>` to invoke.
Generated code uses `// WARN:` comments for accessibility/flakiness gaps.
