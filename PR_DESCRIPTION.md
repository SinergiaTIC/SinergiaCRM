# Portal Authentication — PR Description

## Summary

Implements a complete OAuth 2.0 authentication system for SinergiaCRM Contacts and Accounts, integrated with SuiteCRM's native League OAuth2 Server. Portal users can authenticate via a public login page (session-based) or via the OAuth2 `portal_password` grant (JWT-based) for external applications.

## Features

- **Public portal login page** (`entryPoint=sticPortalLogin`) with password auth, magic link, CSRF protection
- **Password reset flow** with email links and security policies
- **Password change** from within authenticated portal
- **Remember-me** with hashed token rotation
- **Per-credential + IP lockout** with configurable thresholds
- **Login audit trail** stored in `stic_portal_login_audit`
- **Security notifications** on password change, new login, account lockout
- **Admin configuration page** (`Administration → Portal Configuration`) with logo upload, policy settings, audit log viewer
- **OAuth 2.0 `portal_password` grant** via SuiteCRM's League OAuth2 Server at `POST /Api/access_token`
- **OAuth2Clients module** used for client registration and redirect URI validation
- **Tokens stored in `oauth2tokens`** (SuiteCRM table) with `portal_type` column
- **15 new custom fields** on Contacts and Accounts (`stic_portal_*`)

## Architecture

```
External App → POST /Api/access_token (grant_type=portal_password)
                  ↓
             League OAuth2 Server
                  ↓
        PortalPasswordGrant → PortalUserRepository → SticPortalAuthUtils
                  ↓
           PortalAccessTokenRepository → oauth2tokens (portal_type)
```

## Extension approach

- **Zero core files modified** — uses `custom/application/Ext/Api/V8/middlewares.php` extension
- **4 new class files** added to `Api/V8/OAuth2/` (autoloaded via Composer classmap)
- **1 extension config** at `custom/application/Ext/Api/V8/middlewares.php` overrides AuthorizationServer

## Database changes

- 15 custom fields on `contacts_cstm` / `accounts_cstm`
- New tables: `stic_portal_login_audit`, `stic_portal_login_attempts`, `stic_portal_magic_rate_limit`, `stic_portal_password_history`, `stic_portal_oauth_codes`
- Extended `oauth2tokens` with `portal_type` column
- 30 config settings in `config` table (category `portal`)

## Testing

- 7 Playwright E2E tests covering login page, invalid login, OAuth2 grants, reset page, admin validation, client demo
- Manual testing via Chrome DevTools
- Demo client at `../clientOauth/` for external app integration testing

## Demo credentials

- Portal test user: `portal-test@e2e.local` / `Test123456`
- OAuth2 Client ID: `6b6ad4f5-5e99-11f1-b59b-b216769ad5d8`
- OAuth2 Client Secret: `demo-secret-key-45f4e53d2545`
