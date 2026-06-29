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
npx playwright test --project=functional-ca_ES-desktop  # single project
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
| `TEST_LANG` | `ca_ES` | Locale (`en_us`, `ca_ES`, `es_ES`, `gl_ES`, `eu_ES`) |
| `INSTANCE_USER` | `sinergiacrm` | Login username |
| `INSTANCE_PASSWORD` | `sinergiacrm` | Login password |

Override via `.env` file (copy from `.env.example`) or environment variables:

```bash
TEST_LANG=en_us npx playwright test --project=functional-*
```

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

## Supported locales

`en_us`, `ca_ES`, `es_ES`, `gl_ES`, `eu_ES`

Set `TEST_LANG` to match the CRM's UI language. Labels are parsed from PHP language files on disk before the suite starts.

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
