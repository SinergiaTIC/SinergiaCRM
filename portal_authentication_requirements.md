# Portal Authentication System — Requirements & Critical Gaps

## Purpose

Authentication system for Contacts and Accounts with a public login portal. Supports direct browser login (password + magic link), session-based access, and OAuth 2.0 for external application access. Credentials are stored on Contact/Account records with bcrypt hashing, lockout protection, remember-me, password policies, password history, reset flow, security notifications, and admin-configurable branding.

---

## Critical Gaps in Existing Code

The following issues were found in the current SinergiaCRM codebase. These MUST be addressed by the new system or represent risks that the new implementation must avoid repeating.

| # | Gap | Severity | Location | Detail |
|---|-----|----------|----------|--------|
| 1 | **Passwords stored in plain text** | CRITICAL | `SticInclude/SticPrivateAreaUtils.php:112` | `stic_pa_password_c` is stored as plaintext VARCHAR. Three `TODO: Encrypt password` markers exist. No hashing layer. |
| 2 | **SticPrivateAreaUtils never wired to logic hooks** | HIGH | Entire codebase | The 506-line class has `processBeforeSave()` and `processAfterSave()` fully implemented, but no logic hook calls them. Password generation, email sending, and username defaults are all **code that exists but never executes**. |
| 3 | **No contact/account-facing login page exists** | HIGH | `custom/Extension/application/Ext/EntryPointRegistry/` | Three credential fields (`stic_pa_*`) exist on Contacts/Accounts, but there is zero authentication endpoint, login page, or session mechanism. It's a credential store with no consumer. |
| 4 | **Username uniqueness enforcement is disabled** | MEDIUM | `SticPrivateAreaUtils.php:113` | `assertUniquePortalUsername()` is commented out: `// self::assertUniquePortalUsername($bean);`. Duplicate usernames allowed. |
| 5 | **OAuth only supports Users, not Contacts/Accounts** | HIGH | `modules/OAuthTokens/`, `modules/OAuth2Clients/`, `modules/OAuth2Tokens/` | Both OAuth 1.0a and OAuth 2.0 implementations authenticate exclusively against the `users` table. No Contact/Account entity exists in the token model. |
| 6 | **Email sending defaults to enabled when setting is empty** | MEDIUM | `SticPrivateAreaUtils.php:132-135` | `PRIVATEAREA_SEND_CREDENTIALS_ON_ENABLE` defaults to **enabled** (opt-out). Should be opt-in for security. |
| 7 | **No brute-force / rate limiting** | HIGH | N/A | The non-existent login page has no rate limiting. No lockout, no IP tracking, no CAPTCHA. |
| 8 | **No session management** | HIGH | N/A | No session timeout, no concurrent session enforcement, no `session_regenerate_id()` at login (session fixation risk). |
| 9 | **`write_only` property only hides display, doesn't encrypt** | MEDIUM | Vardefs | The `write_only => true` vardef flag only prevents the value from being sent to the browser. The value is still plain text in the database. |
| 10 | **No password change self-service** | MEDIUM | N/A | Contact/Account users have no way to change their own password once logged in. Only the admin can do it from the CRM. |

---

## 1. Credential Fields

All fields are stored in `contacts_cstm` / `accounts_cstm` (one credential set per record). **None of the existing `stic_pa_*` fields are reused. All credentials are stored in new `stic_portal_*` fields.**

### 1.1 New fields

| Field | Type | Description |
|-------|------|-------------|
| `stic_portal_hashed_c` | `varchar(255)` | bcrypt hash of the password (`password_hash(PASSWORD_DEFAULT)`) |
| `stic_portal_remember_token_c` | `varchar(255)` | Hashed remember-me token (sha256 of random token) |
| `stic_portal_locked_until_c` | `datetime` | Account locked until this time (NULL = not locked) |
| `stic_portal_failed_attempts_c` | `int(11)` | Consecutive failed login attempts (reset on success) |
| `stic_portal_last_login_c` | `datetime` | Timestamp of last successful login |
| `stic_portal_password_changed_c` | `datetime` | When password was last changed |
| `stic_portal_password_expires_c` | `datetime` | When password expires (NULL = never) |
| `stic_portal_reset_token_c` | `varchar(255)` | Hashed password-reset token |
| `stic_portal_reset_expires_c` | `datetime` | Reset token expiration |
| `stic_portal_session_id_c` | `varchar(255)` | Active session ID for single-session enforcement |
| `stic_portal_magic_token_c` | `varchar(255)` | Hashed magic-link login token |
| `stic_portal_magic_expires_c` | `datetime` | Magic-link token expiration |
| `stic_portal_enabled_c` | `bool` | Enable/disable portal access (default 0) |
| `stic_portal_force_pw_change_c` | `bool` | Force password change on next login (default 0, set to 1 when admin creates credentials) |
| `stic_portal_username_c` | `varchar(255)` | Portal login username (defaults to primary email if empty) |

> **Explicitly NOT used**: `stic_pa_username_c`, `stic_pa_password_c`, `stic_pa_enable_c`. All are replaced by new `stic_portal_*` fields.

### 1.2 Password history table

```
CREATE TABLE stic_portal_password_history (
    id CHAR(36) NOT NULL PRIMARY KEY,
    parent_id CHAR(36) NOT NULL,        -- Contact or Account ID
    parent_type VARCHAR(20) NOT NULL,    -- 'Contact' or 'Account'
    password_hash VARCHAR(255) NOT NULL,
    date_created DATETIME NOT NULL,
    INDEX idx_parent (parent_id, parent_type)
);
```

### 1.3 Login audit table

```
CREATE TABLE stic_portal_login_audit (
    id CHAR(36) NOT NULL PRIMARY KEY,
    parent_id CHAR(36) NOT NULL,
    parent_type VARCHAR(20) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(500) NULL,
    success BOOLEAN NOT NULL DEFAULT 0,
    failure_reason VARCHAR(100) NULL,
    date_created DATETIME NOT NULL,
    INDEX idx_parent_date (parent_id, date_created),
    INDEX idx_ip (ip_address)
);
```

### 1.4 IP lockout table

```
CREATE TABLE stic_portal_login_attempts (
    id CHAR(36) NOT NULL PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    failed_attempts INT(11) DEFAULT 1,
    locked_until DATETIME NULL,
    last_attempt DATETIME NOT NULL,
    INDEX idx_ip (ip_address)
);
```

---

## 2. Password Encryption

- PHP `password_hash($password, PASSWORD_DEFAULT)` — bcrypt/argon2, same as SuiteCRM Users table
- `password_verify($password, $hash)` for validation
- Passwords MUST NEVER be stored in plain text or reversible encryption
- `password_needs_rehash($hash, PASSWORD_DEFAULT)` checked on each login; re-hash automatically if algorithm changed
- Existing `stic_pa_password_c` field is completely ignored/abandoned by the new system

---

## 3. Password Policies

All policies are optional, configured by the administrator via the Portal Admin Configuration page (§17) and stored in SuiteCRM's `config` table (category `portal`):

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| `PORTAL_PASSWORD_MIN_LENGTH` | int | `8` | Minimum password length (1–64) |
| `PORTAL_PASSWORD_REQUIRE_UPPER` | bool | `0` | Require at least one uppercase letter |
| `PORTAL_PASSWORD_REQUIRE_LOWER` | bool | `0` | Require at least one lowercase letter |
| `PORTAL_PASSWORD_REQUIRE_NUMBER` | bool | `0` | Require at least one digit |
| `PORTAL_PASSWORD_REQUIRE_SPECIAL` | bool | `0` | Require at least one special character |
| `PORTAL_PASSWORD_EXPIRATION_DAYS` | int | `0` | Password expires after N days (0 = never) |
| `PORTAL_PASSWORD_HISTORY_COUNT` | int | `0` | Prevent reuse of last N passwords (0 = disabled) |

- Policies enforced on: password creation, password change, password reset
- All active policies must be met; violations returned as specific error messages
- When password expires, user is prompted to change it on next login
- Password strength meter on the UI (client-side) — weak/medium/strong with visual indicator

---

## 4. Password History (Reuse Prevention)

- When `PORTAL_PASSWORD_HISTORY_COUNT > 0`, store the previous password hash in `stic_portal_password_history` before overwriting
- On password change, check new password hash against the last N stored hashes; reject if match found
- Old entries beyond N are automatically purged

---

## 5. Password Reset Flow

### 5.1 Request reset
1. User visits `index.php?entryPoint=portalReset` (public page, auth=false)
2. Form asks for username
3. Server looks up username in Contacts + Accounts (`stic_portal_username_c`, `stic_portal_enabled_c = 1`, `deleted = 0`)
4. If found: generates 32-byte random token, stores `hash('sha256', $token)` in `stic_portal_reset_token_c`, sets `stic_portal_reset_expires_c` to `now + 1 hour`
5. Sends email with link: `index.php?entryPoint=portalResetConfirm&token=<RAW>&id=<RECORD_ID>`
6. Always show same "If account exists, email sent" message (prevents username enumeration)

### 5.2 Confirm reset
1. User clicks email link → `portalResetConfirm`
2. Server validates: token hash matches, not expired, account not locked, portal enabled
3. Shows new password form with policy requirements and strength meter
4. On submit: validates policy, hashes password, saves to `stic_portal_hashed_c`, archives old hash to history, clears `stic_portal_reset_token_c`, clears `stic_portal_reset_expires_c`, resets `stic_portal_failed_attempts_c`, clears `stic_portal_locked_until_c`, sets `stic_portal_password_changed_c` and `stic_portal_password_expires_c`, clears `stic_portal_force_pw_change_c`
5. Sends security notification: "Your password was changed" email
6. Shows success, link to login

---

## 6. Password Change (Authenticated Self-Service)

- Authenticated portal user can change their password from within the portal
- Requires current password confirmation
- New password must pass policy checks and history check
- On success: hash saved, old hash archived to history, timestamp updated
- Security notification email sent: "Your password was changed"

---

## 7. Force Password Change on First Login

- When admin creates/sets credentials for a user, `stic_portal_force_pw_change_c` is set to `1`
- On next login (password or magic link), user is redirected to password change page instead of `PORTAL_HOME_URL`
- User must set a new password (with policy enforcement) before portal access is granted
- Once changed, `stic_portal_force_pw_change_c` cleared to `0`

---

## 8. Magic Link Login (Passwordless)

### 8.1 Request magic link
1. User visits login page, clicks "Send Magic Link" tab/button
2. Enters username
3. Server looks up username (`stic_portal_username_c`, `stic_portal_enabled_c = 1`, `deleted = 0`)
4. Rate limiting: max 1 request per 60 seconds per username; max 5 per hour per IP
5. If found and within rate limits: generates 32-byte random token, stores `hash('sha256', $token)` in `stic_portal_magic_token_c`, sets `stic_portal_magic_expires_c` to `now + PORTAL_MAGIC_LINK_EXPIRATION_MINUTES` (default 15)
6. Sends email with link: `index.php?entryPoint=portalMagicLogin&token=<RAW>&id=<RECORD_ID>`
7. Always shows "If account exists, magic link sent" message

### 8.2 Consume magic link
1. User clicks email link → `portalMagicLogin`
2. Server validates: token hash matches, not expired, account not locked, portal enabled
3. Invalid/expired → redirect to login with "Invalid or expired link" error
4. Valid → consume token immediately (clear fields), reset failed attempts + lockout, set `stic_portal_last_login_c`
5. If `stic_portal_force_pw_change_c = 1` → redirect to password change page
6. Check password expiration → if expired, set session flag to prompt change (but allow login)
7. Create session, set session cookie, regenerate session ID
8. If "remember me" enabled: generate remember token
9. Security notification: "New login detected" email (with IP, time, user agent)
10. Redirect to `PORTAL_HOME_URL`

### 8.3 Magic link settings

| Setting | Type | Default |
|---------|------|---------|
| `PORTAL_MAGIC_LINK_ENABLED` | bool | `0` |
| `PORTAL_MAGIC_LINK_EXPIRATION_MINUTES` | int | `15` |
| `PORTAL_MAGIC_LINK_TEMPLATE` | varchar | `''` (EmailTemplate UUID) |

---

## 9. Account Lockout (per-Credential + IP)

### 9.1 Per-credential lockout
- After N consecutive failed attempts (`PORTAL_MAX_FAILED_ATTEMPTS`, default 5), lock for M minutes (`PORTAL_LOCKOUT_DURATION_MINUTES`, default 30)
- Stored in `stic_portal_locked_until_c`
- On successful login: reset `stic_portal_failed_attempts_c` to 0, clear `stic_portal_locked_until_c`
- Audit log entry recorded for each failure (reason: `invalid_credentials`, `locked_out`, `disabled`, `ip_blocked`)

### 9.2 IP-based lockout
- Track failed attempts per IP in `stic_portal_login_attempts`
- After same threshold from one IP, block that IP for the same duration
- Prevents distributed brute-force attacks

### 9.3 CAPTCHA escalation
- After 3 failed attempts from the same IP (within a session) → show CAPTCHA on login form
- Reuse existing reCAPTCHA integration (`stic_Web_Forms_saveRecaptcha`)

### 9.4 Manual block
- `stic_portal_enabled_c = 0` acts as admin-driven block
- When disabled, login is rejected regardless of password validity (generic error)

---

## 10. Remember Me

- On login with "remember me" checked, server generates random 32-byte token
- Store `hash('sha256', $token)` in `stic_portal_remember_token_c`
- Set cookie `portal_remember` with raw token, expires: `PORTAL_REMEMBER_ME_DAYS` (default 30), HttpOnly, Secure, SameSite=Lax
- On next visit without active session: validate cookie against DB, create new session, **rotate** the token (generate new one, invalidate old)
- On logout: clear token from DB and cookie
- If remember-me cookie validation fails (tampered, reused after rotation) → clear all remember tokens for that user, send security notification

---

## 11. Session Management

### 11.1 Session creation
- After successful login, `session_regenerate_id(true)` to prevent session fixation
- Store new session ID in `stic_portal_session_id_c`
- Store user type and ID in session: `$_SESSION['portal_user_type']`, `$_SESSION['portal_user_id']`
- Store login timestamp and IP in session for audit

### 11.2 Session timeout
- On each authenticated request, check `$_SESSION['portal_last_activity']` vs `PORTAL_SESSION_TIMEOUT_MINUTES` (default 60)
- Expired → destroy session, redirect to login with "Session expired" message
- Active → update `$_SESSION['portal_last_activity']`

### 11.3 Single session per record
- A new login overwrites `stic_portal_session_id_c`, invalidating the previous session
- Configurable via `PORTAL_ALLOW_CONCURRENT_SESSIONS` (default `0` = single session)

### 11.4 Session validation
- Session middleware validates `stic_portal_session_id_c` matches current PHP session ID
- If mismatch → session invalidated (another login occurred)

---

## 12. Security Notifications

Auto-send emails on these events (configurable per event type):

| Event | Setting | Default |
|-------|---------|---------|
| Password changed | `PORTAL_NOTIFY_PASSWORD_CHANGED` | `1` |
| New device/location login | `PORTAL_NOTIFY_NEW_LOGIN` | `1` |
| Account locked | `PORTAL_NOTIFY_ACCOUNT_LOCKED` | `1` |
| Password reset requested | `PORTAL_NOTIFY_RESET_REQUESTED` | `1` |

Email templates stored in `config` table (category `portal`).

---

## 13. Login Page (`entryPoint=portalLogin`)

### 13.1 General
- Public, no CRM auth (`auth => false`)
- `index.php?entryPoint=portalLogin`
- CSRF protection (token in hidden field)
- Honeypot hidden field (anti-bot)
- Security headers: HSTS, X-Frame-Options, X-Content-Type-Options, Referrer-Policy

### 13.2 Form — two modes (tabs)

**Password mode:**
- Username (`autocomplete="username"`)
- Password (`autocomplete="current-password"`)
- Remember me checkbox
- Submit button
- CAPTCHA (appears after 3 failed attempts from same IP)

**Magic Link mode** (if `PORTAL_MAGIC_LINK_ENABLED = 1`):
- Username
- "Send Magic Link" button

### 13.3 Links
- "Forgot password?" → `entryPoint=portalReset`
- Language selector dropdown

### 13.4 Branding
- Logo: `PORTAL_LOGO` setting → `custom/themes/default/images/portal_logo.png`
- Fallback: `SugarTheme::getImageURL('company_logo.png')` if portal logo empty/missing
- Portal title: `PORTAL_TITLE` setting (default "SinergiaCRM Portal")
- `PORTAL_LOGO_WIDTH` setting for logo max width (default 212)
- Admin uploads logo via the admin configuration page

### 13.5 Multi-language
- All UI strings in 5 languages (ca_ES, en_us, es_ES, eu_ES, gl_ES)
- Labels in `custom/Extension/application/Ext/Language/{lang}.portalAuth.php`
- Auto-detect from browser `Accept-Language`, fallback to SuiteCRM default language
- Manual language selector on login page

### 13.6 Login flow (POST, password mode)
1. Validate CSRF
2. Honeypot check
3. CAPTCHA check if threshold exceeded
4. Look up username in Contacts + Accounts (`stic_portal_username_c`, `stic_portal_enabled_c = 1`, `deleted = 0`)
5. Not found or disabled → generic "Invalid credentials" (no enumeration)
6. Check per-credential lockout → if locked, "Account locked. Try again in X min."
7. Check IP lockout → if locked, "Too many attempts. Try again later."
8. `password_verify($password, $record->stic_portal_hashed_c)`
9. Invalid → increment `stic_portal_failed_attempts_c`, increment IP counter, audit log, check lockout thresholds
10. Valid → reset failures & lockout, set `stic_portal_last_login_c`, check password expiration, check `stic_portal_force_pw_change_c`
11. If force change or expired → redirect to password change page
12. If `client_id` + `redirect_uri` present → OAuth flow (§17)
13. Otherwise → create session, regenerate ID, set session cookie, optionally set remember-me cookie, send security notification, redirect to `PORTAL_HOME_URL`

### 13.7 Error messages (always generic)
- "Invalid credentials" — wrong username, wrong password, or disabled account
- "Account locked. Please try again in X minutes."
- "Too many attempts. Please try again later."
- "Your password has expired. Please set a new one."

---

## 14. Logout

### 14.1 Browser logout
- Entry point `index.php?entryPoint=portalLogout` (auth not required)
- Destroys PHP session (`session_destroy()`)
- Clears `stic_portal_session_id_c` from the record
- Clears `portal_remember` cookie
- Clears `stic_portal_remember_token_c` from the record
- Redirects to `portalLogin`

### 14.2 OAuth token revocation
- Clear access token and refresh token from `oauth2tokens` table
- Reuse existing `OAuth2Tokens` revocation logic

---

## 15. Login Audit Trail

- Table `stic_portal_login_audit` records every login attempt (success + failure)
- Fields: parent_id, parent_type, IP, user agent, success/failure, failure reason, timestamp
- Used for: security notifications ("new device login"), admin audit view, user's own login history
- Admin can view audit log from the admin configuration page
- Purge old entries (configurable retention: `PORTAL_AUDIT_RETENTION_DAYS`, default 365)

---

## 16. OAuth 2.0 Integration

### 16.1 Overview
External applications authenticate on behalf of a Contact/Account using OAuth 2.0 Authorization Code grant, reusing SuiteCRM's existing infrastructure (OAuth2Clients, League OAuth2 Server, OAuth2Tokens).

### 16.2 Flow
1. External app registers as OAuth2Client in the CRM
2. External app redirects user to:
   ```
   index.php?entryPoint=portalLogin&client_id=X&redirect_uri=Y&response_type=code&state=S
   ```
3. User logs in on portal login page
4. After success, server generates authorization code
5. Server redirects to `redirect_uri?code=<CODE>&state=<STATE>`
6. External app exchanges code for tokens via existing `/access_token`
7. External app receives `access_token` (JWT, 1h TTL) + `refresh_token` (1 month TTL)
8. External app calls API with `Authorization: Bearer <access_token>`

### 16.3 Changes to existing OAuth2 infrastructure
- Extend League OAuth2 Server with a portal UserRepository that authenticates Contacts/Accounts
- Store `portal:contact:<id>` or `portal:account:<id>` in token `sub` claim
- Add `portal_type` column to `oauth2tokens` table (values: `contact`, `account`, `user`, NULL)
- ResourceServer: recognize `portal:contact:` / `portal:account:` identifiers, load Contact/Account bean, apply ACL

---

## 17. Admin Configuration Section

### 17.1 Architecture
A dedicated admin page within SuiteCRM's Administration panel. All settings are stored in the `config` DB table under the `portal` category, following the pattern established by the project's Google Drive credentials panel.

### 17.2 Components

#### Menu entry
File: `custom/Extension/modules/Administration/Ext/Administration/SticPortalAdmin.php`
```php
$admin_option_defs = array();
$admin_option_defs['Administration']['stic_portal_config'] = array(
    'Portal',
    'LBL_STIC_PORTAL_CONFIG_LINK_TITLE',
    'LBL_STIC_PORTAL_CONFIG_DESCRIPTION',
    './index.php?module=Administration&action=sticportalconfig',
    'stic-portal-config',
);
// Add to SinergiaCRM section
if (!isset($admin_group_header['LBL_SINERGIACRM_TAB_TITLE'])) {
    $admin_group_header['LBL_SINERGIACRM_TAB_TITLE'] = array(
        'LBL_SINERGIACRM_TAB_TITLE', '', false, $admin_option_defs, 'LBL_SINERGIACRM_TAB_DESCRIPTION',
    );
} else {
    $admin_group_header['LBL_SINERGIACRM_TAB_TITLE'][3] = array_replace_recursive(
        $admin_option_defs, $admin_group_header['LBL_SINERGIACRM_TAB_TITLE'][3]
    );
}
```

#### ActionViewMap
File: `custom/modules/Administration/action_view_map.php`
```php
$action_view_map['sticportalconfig'] = 'sticportalconfig';
```

#### View class
File: `custom/modules/Administration/views/view.sticportalconfig.php`
```php
require_once 'include/MVC/View/SugarView.php';

class AdministrationViewSticportalconfig extends SugarView
{
    public function preDisplay()
    {
        global $current_user;
        if (!is_admin($current_user)) {
            sugar_die("Unauthorized access to administration.");
        }
    }

    public function display()
    {
        global $mod_strings, $db;
        // Read all settings from config table (category='portal')
        // Assign to Smarty for SticPortalConfig.tpl
        // Show portal logo preview, etc.
        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('title', $this->getModuleTitle(false));
        echo $this->ss->fetch('custom/modules/Administration/templates/SticPortalConfig.tpl');
    }
}
```

#### Controller (POST handling)
File: `custom/modules/Administration/controller.php`
```php
require_once 'modules/Administration/controller.php';

class CustomAdministrationController extends AdministrationController
{
    public function action_sticportalconfig_save()
    {
        // Save all settings to config table
        SticPortalConfigUtils::saveSettings($_POST);
        SugarApplication::redirect('index.php?module=Administration&action=sticportalconfig');
    }

    public function action_sticportalconfig_upload_logo()
    {
        // Handle PORTAL_LOGO file upload
        SticPortalConfigUtils::handleLogoUpload($_FILES['portal_logo']);
        SugarApplication::redirect('index.php?module=Administration&action=sticportalconfig');
    }
}
```

#### SticPortalConfigUtils (settings read/write)
File: `SticInclude/SticPortalConfigUtils.php`

Reads/writes all portal settings to the `config` table:
```php
class SticPortalConfigUtils
{
    // Read a setting
    public static function get($name, $default = null) { ... }
    // SELECT value FROM config WHERE category='portal' AND name='...'

    // Save a setting (INSERT ON DUPLICATE KEY UPDATE)
    public static function set($name, $value) { ... }

    // Save all from POST
    public static function saveSettings($postData) { ... }

    // Handle logo upload
    public static function handleLogoUpload($fileField) { ... }
}
```

Settings stored in `config` table:
```
category = 'portal'
name    = <setting_key>   (e.g. 'PORTAL_TITLE', 'PORTAL_PASSWORD_MIN_LENGTH')
value   = <setting_value>
```

### 17.3 Admin page sections

**General:**
- `PORTAL_TITLE` (text)
- `PORTAL_HOME_URL` (text)
- Portal logo upload (file input + current preview)
- `PORTAL_LOGO_WIDTH` (number, default 212)

**Password Policies:**
- All `PORTAL_PASSWORD_*` settings (number inputs + checkboxes)

**Security:**
- `PORTAL_MAX_FAILED_ATTEMPTS`, `PORTAL_LOCKOUT_DURATION_MINUTES`, `PORTAL_REMEMBER_ME_DAYS`, `PORTAL_SESSION_TIMEOUT_MINUTES`, `PORTAL_ALLOW_CONCURRENT_SESSIONS`
- `PORTAL_PASSWORD_EXPIRATION_DAYS`, `PORTAL_PASSWORD_HISTORY_COUNT`

**Magic Link:**
- `PORTAL_MAGIC_LINK_ENABLED`, `PORTAL_MAGIC_LINK_EXPIRATION_MINUTES`, `PORTAL_MAGIC_LINK_TEMPLATE`

**Email Templates:**
- Credentials template (Contacts), credentials template (Accounts), reset template, magic link template, all notification templates
- All as dropdowns populated from EmailTemplates module

**Bulk Actions:**
- "Clear All Lockouts" button
- "Clear All Sessions" button
- "Test Email" button

**Login Audit:**
- Filterable table from `stic_portal_login_audit`
- Columns: date, username, type, IP, user agent, success/failure, reason
- Filter by: date range, username, success/failure

### 17.4 Language labels
File: `custom/Extension/modules/Administration/Ext/Language/{en_us,es_ES,ca_ES,eu_ES,gl_ES}.SticPortalAdmin.php`
```
LBL_STIC_PORTAL_CONFIG_LINK_TITLE   = 'Portal Configuration'
LBL_STIC_PORTAL_CONFIG_DESCRIPTION  = 'Configure portal authentication, branding, and security settings'
LBL_STIC_PORTAL_CONFIG_TITLE        = 'Portal Configuration'
LBL_STIC_PORTAL_GENERAL             = 'General Settings'
LBL_STIC_PORTAL_PASSWORD_POLICIES   = 'Password Policies'
LBL_STIC_PORTAL_SECURITY            = 'Security Settings'
LBL_STIC_PORTAL_MAGIC_LINK          = 'Magic Link Settings'
LBL_STIC_PORTAL_EMAIL_TEMPLATES     = 'Email Templates'
LBL_STIC_PORTAL_ACTIONS             = 'Actions'
LBL_STIC_PORTAL_AUDIT               = 'Login Audit Log'
LBL_STIC_PORTAL_SAVE                = 'Save Settings'
LBL_STIC_PORTAL_CLEAR_LOCKOUTS      = 'Clear All Lockouts'
LBL_STIC_PORTAL_CLEAR_SESSIONS      = 'Clear All Sessions'
LBL_STIC_PORTAL_TEST_EMAIL          = 'Test Email'
LBL_STIC_PORTAL_LOGO_UPLOAD         = 'Upload Portal Logo'
```

---

## 18. Libraries (SticInclude/)

### `SticPortalAuthUtils.php`

| Method | Purpose |
|--------|---------|
| `authenticate($username, $password, $remember, $ip)` | Full login flow, returns result array |
| `hashPassword($plain)` | `password_hash()` wrapper |
| `verifyPassword($plain, $hash)` | `password_verify()` wrapper |
| `checkLockout($bean)` | Returns `['locked' => bool, 'remaining' => int]` |
| `recordFailedAttempt($bean, $ip)` | Increments counters, may trigger lockout |
| `resetFailedAttempts($bean)` | Clears lockout on success |
| `generateRememberToken()` | Creates random 32-byte token |
| `validateRememberToken($cookieValue)` | Validates + rotates token, returns bean |
| `clearRememberToken($bean)` | Clears token from DB + cookie |
| `validatePasswordPolicy($password)` | Returns array of violation messages |
| `isPasswordExpired($bean)` | Checks `stic_portal_password_expires_c` |
| `generateResetToken($bean)` | Creates reset token, returns raw token |
| `validateResetToken($token, $recordId)` | Validates hash + expiry, returns bean |
| `generateMagicLinkToken($bean)` | Creates magic link token, sends email, returns raw token |
| `validateMagicLinkToken($token, $recordId)` | Validates + consumes magic token, returns bean |
| `sendMagicLinkEmail($bean, $rawToken)` | Dispatches magic link email |
| `processBeforeSave($bean)` | Logic hook: hash password before save |
| `getPortalUserByUsername($username)` | UNION lookup Contacts + Accounts |
| `archivePasswordHistory($bean)` | Saves old hash to history table |
| `isPasswordInHistory($bean, $newHash)` | Checks new hash against history |
| `createPortalSession($bean)` | Sets up session, regenerates ID |
| `destroyPortalSession($bean)` | Destroys session, clears DB fields |
| `validatePortalSession()` | Validates session timeout + ID match |
| `sendSecurityNotification($bean, $eventType)` | Sends notification email |
| `recordLoginAudit($bean, $ip, $userAgent, $success, $reason)` | Inserts audit log row |

### `SticPortalConfigUtils.php`

| Method | Purpose |
|--------|---------|
| `get($name, $default)` | Read a setting from `config` table (category `portal`) |
| `set($name, $value)` | Save a setting to `config` table |
| `getAll()` | Return all portal settings as associative array |
| `saveSettings($postData)` | Save all settings from POST |
| `handleLogoUpload($fileField)` | Validate + save portal logo file |
| `getLogoUrl()` | Returns logo URL (portal logo or fallback to company_logo) |

### `SticPortalOAuthUtils.php`

| Method | Purpose |
|--------|---------|
| `getPortalUserEntity($username, $password)` | Returns League UserEntity for Contact/Account |
| `generateAuthCode($clientId, $portalIdentifier)` | Creates authorization code |
| `exchangeAuthCode($code)` | Validates code, returns token info |

---

## 19. Database Changes Summary

### 19.1 New custom fields on Contacts and Accounts (`contacts_cstm` / `accounts_cstm`)
- `stic_portal_hashed_c` VARCHAR(255)
- `stic_portal_remember_token_c` VARCHAR(255)
- `stic_portal_locked_until_c` DATETIME NULL
- `stic_portal_failed_attempts_c` INT(11) DEFAULT 0
- `stic_portal_last_login_c` DATETIME NULL
- `stic_portal_password_changed_c` DATETIME NULL
- `stic_portal_password_expires_c` DATETIME NULL
- `stic_portal_reset_token_c` VARCHAR(255)
- `stic_portal_reset_expires_c` DATETIME NULL
- `stic_portal_session_id_c` VARCHAR(255)
- `stic_portal_magic_token_c` VARCHAR(255)
- `stic_portal_magic_expires_c` DATETIME NULL
- `stic_portal_enabled_c` TINYINT(1) DEFAULT 0
- `stic_portal_force_pw_change_c` TINYINT(1) DEFAULT 0
- `stic_portal_username_c` VARCHAR(255) NULL

### 19.2 New tables
- `stic_portal_password_history` — password reuse prevention
- `stic_portal_login_audit` — login audit trail
- `stic_portal_login_attempts` — IP-based lockout tracking

### 19.3 Modification to existing table
- `oauth2tokens.portal_type` VARCHAR(20) NULL

### 19.4 Settings stored in `config` table

All portal configuration is stored in SuiteCRM's `config` table (not `stic_Settings`):

```
config table:
  category = 'portal'
  name     = '<SETTING_KEY>'
  value    = '<SETTING_VALUE>'
```

| Setting Key | Default |
|-------------|---------|
| `PORTAL_TITLE` | `'SinergiaCRM Portal'` |
| `PORTAL_HOME_URL` | `''` |
| `PORTAL_LOGO` | `''` (empty = use company_logo) |
| `PORTAL_LOGO_WIDTH` | `'212'` |
| `PORTAL_PASSWORD_MIN_LENGTH` | `'8'` |
| `PORTAL_PASSWORD_REQUIRE_UPPER` | `'0'` |
| `PORTAL_PASSWORD_REQUIRE_LOWER` | `'0'` |
| `PORTAL_PASSWORD_REQUIRE_NUMBER` | `'0'` |
| `PORTAL_PASSWORD_REQUIRE_SPECIAL` | `'0'` |
| `PORTAL_PASSWORD_EXPIRATION_DAYS` | `'0'` |
| `PORTAL_PASSWORD_HISTORY_COUNT` | `'0'` |
| `PORTAL_MAX_FAILED_ATTEMPTS` | `'5'` |
| `PORTAL_LOCKOUT_DURATION_MINUTES` | `'30'` |
| `PORTAL_REMEMBER_ME_DAYS` | `'30'` |
| `PORTAL_SESSION_TIMEOUT_MINUTES` | `'60'` |
| `PORTAL_ALLOW_CONCURRENT_SESSIONS` | `'0'` |
| `PORTAL_MAGIC_LINK_ENABLED` | `'0'` |
| `PORTAL_MAGIC_LINK_EXPIRATION_MINUTES` | `'15'` |
| `PORTAL_MAGIC_LINK_TEMPLATE` | `''` |
| `PORTAL_RESET_TEMPLATE` | `''` |
| `PORTAL_CREDENTIALS_TEMPLATE_CONTACTS` | `''` |
| `PORTAL_CREDENTIALS_TEMPLATE_ACCOUNTS` | `''` |
| `PORTAL_NOTIFY_PASSWORD_CHANGED` | `'1'` |
| `PORTAL_NOTIFY_NEW_LOGIN` | `'1'` |
| `PORTAL_NOTIFY_ACCOUNT_LOCKED` | `'1'` |
| `PORTAL_NOTIFY_RESET_REQUESTED` | `'1'` |
| `PORTAL_AUDIT_RETENTION_DAYS` | `'365'` |

Default values are only INSERTed on first save if the key doesn't exist yet.

---

## 20. Entry Points (all `auth => false`)

| Entry Point | File | Method | Purpose |
|-------------|------|--------|---------|
| `portalLogin` | `SticInclude/portal_login.php` | GET, POST | Login page |
| `portalLogout` | `SticInclude/portal_logout.php` | GET | Logout |
| `portalReset` | `SticInclude/portal_reset.php` | GET, POST | Password reset request |
| `portalResetConfirm` | `SticInclude/portal_reset_confirm.php` | GET, POST | Reset confirmation |
| `portalMagicLogin` | `SticInclude/portal_magic_login.php` | GET | Magic link login |
| `portalChangePassword` | `SticInclude/portal_change_password.php` | GET, POST | Authenticated password change |

---

## 21. Files Summary

### 21.1 New files
```
SticInclude/
  SticPortalAuthUtils.php              # Core authentication logic
  SticPortalOAuthUtils.php             # OAuth 2.0 bridge
  SticPortalConfigUtils.php            # Admin settings read/write (config table)
  portal_login.php                     # Login entry point
  portal_logout.php                    # Logout entry point
  portal_reset.php                     # Reset request entry point
  portal_reset_confirm.php             # Reset confirmation entry point
  portal_magic_login.php               # Magic link entry point
  portal_change_password.php           # Password change entry point

custom/Extension/modules/Contacts/Ext/
  Vardefs/SticPortalVardefs.php        # Portal credential fields
  Language/{en_us,es_ES,ca_ES,eu_ES,gl_ES}.SticPortalLang.php

custom/Extension/modules/Accounts/Ext/
  Vardefs/SticPortalVardefs.php        # Same for Accounts
  Language/{en_us,es_ES,ca_ES,eu_ES,gl_ES}.SticPortalLang.php

custom/Extension/modules/Administration/Ext/
  Administration/SticPortalAdmin.php   # Admin panel link
  Language/{en_us,...}.SticPortalAdmin.php

custom/Extension/application/Ext/
  EntryPointRegistry/(append)          # Register entry points
  Language/{en_us,...}.portalAuth.php  # Portal UI labels

custom/modules/Administration/
  action_view_map.php                  # Map SticPortalConfig action
  views/view.sticportalconfig.php      # Admin view class
  templates/SticPortalConfig.tpl       # Admin page template
  controller.php                       # POST handler for admin actions

SticUpdates/Migrations/
  2025XXXX_portal_authentication.sql   # DB migration

themes/SuiteP/tpls/
  portal_login.tpl                     # Login page template
  portal_reset.tpl                     # Reset request template
  portal_reset_confirm.tpl             # Reset confirmation template
  portal_change_password.tpl           # Password change template
```

### 21.2 Modified files
```
custom/modules/Contacts/SticLogicHooksCode.php   # Add before_save hook
custom/modules/Accounts/SticLogicHooksCode.php    # Add before_save hook
custom/modules/Contacts/SticUtils.php              # Hide sensitive fields from DetailView
custom/modules/Accounts/SticUtils.php              # Same
custom/modules/Contacts/SticUtils.js              # Portal fields UI
custom/modules/Accounts/SticUtils.js               # Same
```

---

## 22. Non-Functional Requirements

- **Security**: CSRF on all forms, HttpOnly + Secure + SameSite=Lax cookies, generic error messages (no enumeration), rate limiting per IP, honeypot anti-bot, CAPTCHA escalation, session fixation prevention (`session_regenerate_id`), all passwords bcrypt-hashed (never plaintext), password history prevents reuse, remember-me tokens rotated, single-use reset/magic tokens
- **Performance**: Single DB query per login attempt (UNION across Contacts + Accounts), `password_verify` is fast, native PHP sessions
- **Audit**: Full login audit trail (`stic_portal_login_audit`), configurable retention
- **Notifications**: Email alerts for password change, new login, account lockout, password reset
- **Backwards compatibility**: Existing `stic_pa_*` fields are left untouched; no migration of existing plaintext passwords; no breaking changes to existing CRM login or API

---

## 23. End-to-End Testing Strategy

### 23.1 Overview
E2E tests validate the full portal authentication flow against a running SinergiaCRM instance using **Playwright** (TypeScript, headless Chrome). The tests live in a dedicated folder outside the CRM codebase so that test infrastructure is decoupled from the application and can be reused across projects.

### 23.2 Location
All test artifacts live in `../playwright/` (sibling to `application/`):

```
../playwright/
├── package.json                    # Node deps (playwright, dotenv, etc.)
├── playwright.config.ts            # Browsers, base URL, retries, timeouts
├── tsconfig.json                   # TypeScript config
├── .env.example                    # Test environment variables (DB, URLs, credentials)
├── .env                            # Local secrets (gitignored)
├── .gitignore
├── README.md                       # Setup & run instructions
│
├── fixtures/
│   ├── global-setup.ts             # Bootstraps test DB state via SQL/Docker
│   ├── global-teardown.ts          # Cleans up test data after run
│   ├── seed.ts                     # Inserts Contacts/Accounts with portal creds
│   └── cleanup.ts                  # Removes test records, resets lockouts
│
├── helpers/
│   ├── api.ts                      # Wrapper around Playwright APIRequestContext
│   ├── db.ts                       # MySQL connection to sw-mysql (via mysql2)
│   ├── mail.ts                     # Mailpit REST client (verify emails sent)
│   ├── crypto.ts                   # Hash/verify helpers (for seeding creds)
│   └── portal.ts                   # Page objects / reusable flows (login, reset, magic)
│
├── pages/
│   ├── portal-login.page.ts        # Locators & actions for login page
│   ├── portal-reset.page.ts        # Locators & actions for reset page
│   ├── portal-magic.page.ts        # Locators & actions for magic-link consumer
│   ├── portal-change-password.page.ts
│   ├── admin-portal.page.ts        # Admin config page
│   └── crm-contact.page.ts         # CRM EditView for Contact (admin sets creds)
│
├── tests/
│   ├── auth/
│   │   ├── login.password.spec.ts
│   │   ├── login.magic-link.spec.ts
│   │   ├── login.lockout.spec.ts
│   │   ├── login.ip-lockout.spec.ts
│   │   ├── login.remember-me.spec.ts
│   │   ├── login.csrf.spec.ts
│   │   ├── login.session-timeout.spec.ts
│   │   ├── login.session-fixation.spec.ts
│   │   ├── login.captcha.spec.ts
│   │   ├── login.concurrency.spec.ts
│   │   └── login.branding.spec.ts
│   ├── reset/
│   │   ├── reset.request.spec.ts
│   │   ├── reset.confirm.spec.ts
│   │   ├── reset.token-expiry.spec.ts
│   │   ├── reset.single-use.spec.ts
│   │   └── reset.history-enforced.spec.ts
│   ├── magic-link/
│   │   ├── magic.request.spec.ts
│   │   ├── magic.consume.spec.ts
│   │   ├── magic.rate-limit.spec.ts
│   │   └── magic.notification.spec.ts
│   ├── change-password/
│   │   ├── change.authenticated.spec.ts
│   │   ├── change.first-login.spec.ts
│   │   ├── change.policy.spec.ts
│   │   ├── change.history.spec.ts
│   │   └── change.notification.spec.ts
│   ├── admin/
│   │   ├── admin.access.spec.ts
│   │   ├── admin.settings.spec.ts
│   │   ├── admin.logo-upload.spec.ts
│   │   ├── admin.clear-lockouts.spec.ts
│   │   ├── admin.clear-sessions.spec.ts
│   │   └── admin.audit-log.spec.ts
│   ├── oauth/
│   │   ├── oauth.authorize.spec.ts
│   │   ├── oauth.token-exchange.spec.ts
│   │   ├── oauth.bearer-access.spec.ts
│   │   └── oauth.refresh.spec.ts
│   └── audit/
│       ├── audit.success-logged.spec.ts
│       ├── audit.failure-logged.spec.ts
│       └── audit.retention.spec.ts
│
└── artifacts/                      # Created at runtime (gitignored)
    ├── screenshots/                # Failed-test screenshots
    ├── videos/                     # Test recordings
    ├── traces/                     # Playwright traces
    └── reports/                    # HTML reports
```

### 23.3 Tooling choices

- **Playwright** (TypeScript) — primary test runner
- **@playwright/test** — assertion library, fixtures, parallelization
- **mysql2** — direct DB access for fixture setup/teardown (bypasses UI for speed)
- **dotenv** — environment variable loading
- **Mailpit REST API** (`http://localhost:8025/api/v1/...`) — verify emails sent by portal flows (reset link, magic link, security notifications)
- **@axe-core/playwright** — accessibility checks on login page
- **playwright-lighthouse** or `@unlighthouse/cli` — performance budget on login page (LCP < 2s)
- **Allure** or **Playwright HTML reporter** — reports

### 23.4 Configuration

`playwright.config.ts`:
```ts
import { defineConfig, devices } from '@playwright/test';
export default defineConfig({
  testDir: './tests',
  timeout: 30_000,
  expect: { timeout: 5_000 },
  fullyParallel: true,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 2 : 0,
  workers: process.env.CI ? 2 : undefined,
  reporter: [['html', { open: 'never' }], ['list']],
  use: {
    baseURL: process.env.BASE_URL || 'http://localhost:8000',
    actionTimeout: 10_000,
    trace: 'retain-on-failure',
    video: 'retain-on-failure',
    screenshot: 'only-on-failure',
  },
  projects: [
    { name: 'chromium', use: { ...devices['Desktop Chrome'] } },
    { name: 'firefox',  use: { ...devices['Desktop Firefox'] } },
    { name: 'webkit',   use: { ...devices['Desktop Safari'] } },
  ],
  globalSetup: './fixtures/global-setup.ts',
  globalTeardown: './fixtures/global-teardown.ts',
});
```

`.env.example`:
```
BASE_URL=http://localhost:8000
ADMIN_URL=http://localhost:8000
DB_HOST=localhost
DB_PORT=3316
DB_USER=sinergiacrm
DB_PASSWORD=sinergiacrmcontinue
DB_NAME=mysuitecrm
MAILPIT_URL=http://localhost:8025
CRM_ADMIN_USER=admin
CRM_ADMIN_PASSWORD=admin
```

### 23.5 Docker integration

The CRM runs via `../../docker-compose.yml` (services: `sw-mysql`, `sw-webserver`, `sw-php-fpm`, `sw-mailpit`). The Playwright suite runs from the host machine and:

- Uses **`sw-mysql`** on `localhost:3316` to seed/clean test fixtures
- Uses **`sw-webserver`** on `http://localhost:8000` as the test target
- Uses **`sw-mailpit`** on `http://localhost:8025` to verify emails sent by reset/magic/notification flows

Pre-test checklist (in `global-setup.ts`):
1. Verify `sw-mysql` is reachable (`mysql2.createConnection({...}).ping()`)
2. Verify `sw-webserver` returns 200 on `BASE_URL`
3. Verify `sw-mailpit` is reachable
4. Run database seed: insert test Contacts and Accounts with known bcrypt-hashed passwords
5. Clear `stic_portal_login_audit` and `stic_portal_login_attempts` to start clean

Post-test cleanup (in `global-teardown.ts`):
1. Remove all seeded test records
2. Reset any persisted state in `config` table under `category = 'portal'`
3. Wipe test-generated audit rows

### 23.6 Test data strategy

Tests do **not** use the production data. Each test seeds its own fixtures with a deterministic prefix (e.g., `[E2E-LOGIN] Jane Doe`) and cleans up afterwards.

Two seeding modes:

**Mode A: Pre-seeded shared fixtures** (used by tests that don't mutate auth state, e.g., branding/logo tests)
- 1 Contact + 1 Account with `stic_portal_enabled_c = 1` and a known bcrypt password
- Created in `global-setup.ts`, removed in `global-teardown.ts`

**Mode B: Per-test fixtures** (used by tests that mutate state, e.g., lockout, reset)
- Each test creates its own Contact with unique username and tears it down in `afterEach`
- Implemented as a Playwright fixture: `await test.use({ portalUser: await seedContact() })`

### 23.7 Test scenarios (mapped to requirements)

| Requirement | Test file(s) |
|-------------|--------------|
| §13 Login page (password mode) | `auth/login.password.spec.ts` |
| §13 Login page (magic link mode) | `auth/login.magic-link.spec.ts` |
| §9 Per-credential lockout | `auth/login.lockout.spec.ts` |
| §9 IP-based lockout | `auth/login.ip-lockout.spec.ts` |
| §10 Remember me | `auth/login.remember-me.spec.ts` |
| §13 CSRF on forms | `auth/login.csrf.spec.ts` |
| §11 Session timeout | `auth/login.session-timeout.spec.ts` |
| §11 Session fixation prevention | `auth/login.session-fixation.spec.ts` |
| §9 CAPTCHA escalation | `auth/login.captcha.spec.ts` |
| §11 Single-session enforcement | `auth/login.concurrency.spec.ts` |
| §13 Branding (logo + title) | `auth/login.branding.spec.ts` |
| §5 Password reset request | `reset/reset.request.spec.ts` |
| §5 Password reset confirm | `reset/reset.confirm.spec.ts` |
| §5 Reset token expiry | `reset/reset.token-expiry.spec.ts` |
| §5 Single-use reset token | `reset/reset.single-use.spec.ts` |
| §4 Password history | `reset/reset.history-enforced.spec.ts` |
| §8 Magic link request | `magic-link/magic.request.spec.ts` |
| §8 Magic link consume | `magic-link/magic.consume.spec.ts` |
| §8 Magic link rate limit | `magic-link/magic.rate-limit.spec.ts` |
| §12 Security notifications | `magic-link/magic.notification.spec.ts` |
| §6 Authenticated password change | `change-password/change.authenticated.spec.ts` |
| §7 Force password change first login | `change-password/change.first-login.spec.ts` |
| §3 Password policy enforcement | `change-password/change.policy.spec.ts` |
| §4 Password history | `change-password/change.history.spec.ts` |
| §12 Security notifications | `change-password/change.notification.spec.ts` |
| §17 Admin page access control | `admin/admin.access.spec.ts` |
| §17 Admin settings persistence | `admin/admin.settings.spec.ts` |
| §17 Logo upload | `admin/admin.logo-upload.spec.ts` |
| §17 Clear all lockouts | `admin/admin.clear-lockouts.spec.ts` |
| §17 Clear all sessions | `admin/admin.clear-sessions.spec.ts` |
| §15 Login audit log | `admin/admin.audit-log.spec.ts` |
| §16 OAuth authorize | `oauth/oauth.authorize.spec.ts` |
| §16 OAuth token exchange | `oauth/oauth.token-exchange.spec.ts` |
| §16 OAuth bearer access | `oauth/oauth.bearer-access.spec.ts` |
| §16 OAuth refresh | `oauth/oauth.refresh.spec.ts` |
| §15 Audit success/failure | `audit/audit.*.spec.ts` |

### 23.8 Test authoring pattern

Page object example — `pages/portal-login.page.ts`:
```ts
import { Page, Locator, expect } from '@playwright/test';

export class PortalLoginPage {
  constructor(private page: Page) {}

  async goto(oauthParams?: { client_id: string; redirect_uri: string; state: string }) {
    const url = new URL('/index.php', process.env.BASE_URL);
    url.searchParams.set('entryPoint', 'portalLogin');
    if (oauthParams) {
      url.searchParams.set('client_id', oauthParams.client_id);
      url.searchParams.set('redirect_uri', oauthParams.redirect_uri);
      url.searchParams.set('state', oauthParams.state);
      url.searchParams.set('response_type', 'code');
    }
    await this.page.goto(url.toString());
  }

  async loginWithPassword(username: string, password: string) {
    await this.page.getByLabel(/username/i).fill(username);
    await this.page.getByLabel(/password/i).fill(password);
    await this.page.getByRole('button', { name: /sign in/i }).click();
  }

  async requestMagicLink(username: string) {
    await this.page.getByRole('tab', { name: /magic link/i }).click();
    await this.page.getByLabel(/username/i).fill(username);
    await this.page.getByRole('button', { name: /send magic link/i }).click();
  }

  get errorMessage(): Locator {
    return this.page.getByRole('alert');
  }
}
```

### 23.9 Example test — `tests/auth/login.lockout.spec.ts`

```ts
import { test, expect } from '@playwright/test';
import { PortalLoginPage } from '../../pages/portal-login.page';
import { seedContact, removeContact } from '../../fixtures/seed';
import { SticPortalConfigUtils } from '../../helpers/portal-config';

test.describe('Per-credential lockout', () => {
  let username: string;
  let password = 'CorrectHorseBatteryStaple!1';

  test.beforeEach(async () => {
    username = `e2e-lockout-${Date.now()}@example.com`;
    await seedContact({ username, password, enabled: true });
    await SticPortalConfigUtils.set('PORTAL_MAX_FAILED_ATTEMPTS', '3');
    await SticPortalConfigUtils.set('PORTAL_LOCKOUT_DURATION_MINUTES', '1');
  });

  test.afterEach(async () => {
    await removeContact(username);
  });

  test('locks account after N failed attempts', async ({ page }) => {
    const login = new PortalLoginPage(page);
    await login.goto();

    for (let i = 0; i < 3; i++) {
      await login.loginWithPassword(username, 'wrong-password');
      await expect(login.errorMessage).toContainText(/invalid credentials/i);
    }

    // 4th attempt should report lockout
    await login.loginWithPassword(username, password);
    await expect(login.errorMessage).toContainText(/account locked/i);
  });

  test('successful login resets the counter', async ({ page }) => {
    const login = new PortalLoginPage(page);
    await login.goto();

    await login.loginWithPassword(username, 'wrong-password');
    await login.loginWithPassword(username, password);
    await expect(page).toHaveURL(/portal_home/);

    // Now 2 more wrong attempts should not lock
    await login.goto();
    await login.loginWithPassword(username, 'wrong-password');
    await expect(login.errorMessage).toContainText(/invalid credentials/i);
  });
});
```

### 23.10 Example — `tests/magic-link/magic.consume.spec.ts`

```ts
import { test, expect } from '@playwright/test';
import { seedContact, removeContact } from '../../fixtures/seed';
import { fetchMagicLinkEmail } from '../../helpers/mail';
import { PortalMagicPage } from '../../pages/portal-magic.page';

test('valid magic link logs the user in', async ({ page, request }) => {
  const username = `e2e-magic-${Date.now()}@example.com`;
  await seedContact({ username, enabled: true });

  // Trigger magic link send
  await request.post('/index.php?entryPoint=portalLogin', {
    form: { entryPoint: 'portalLogin', mode: 'magic_link', username },
  });

  // Wait for email via Mailpit
  const email = await fetchMagicLinkEmail({ to: username, timeoutMs: 10_000 });
  const link = email.match(/http.*portalMagicLogin[^"\s]+/)[0];
  const url = new URL(link);

  await page.goto(link);
  await expect(page).toHaveURL(/portal_home/);
  await expect(page.getByRole('heading', { name: /welcome/i })).toBeVisible();

  await removeContact(username);
});

test('magic link is single-use', async ({ page, request }) => {
  const username = `e2e-magic-once-${Date.now()}@example.com`;
  await seedContact({ username, enabled: true });

  await request.post('/index.php?entryPoint=portalLogin', {
    form: { entryPoint: 'portalLogin', mode: 'magic_link', username },
  });

  const email = await fetchMagicLinkEmail({ to: username, timeoutMs: 10_000 });
  const link = email.match(/http.*portalMagicLogin[^"\s]+/)[0];

  // First click: should succeed
  await page.goto(link);
  await expect(page).toHaveURL(/portal_home/);

  // Second click: should redirect to login with error
  const ctx2 = await page.context().browser()!.newContext();
  const page2 = await ctx2.newPage();
  await page2.goto(link);
  await expect(page2).toHaveURL(/portalLogin/);
  await expect(page2.getByRole('alert')).toContainText(/invalid or expired/i);

  await removeContact(username);
});
```

### 23.11 Email verification helper — `helpers/mail.ts`

```ts
import axios from 'axios';

const MAILPIT = process.env.MAILPIT_URL || 'http://localhost:8025';

export async function fetchLatestEmail(to: string, timeoutMs = 10_000) {
  const start = Date.now();
  while (Date.now() - start < timeoutMs) {
    const { data } = await axios.get(`${MAILPIT}/api/v1/messages`);
    const match = data.messages.find((m: any) =>
      m.To.some((t: any) => t.Address === to)
    );
    if (match) {
      const { data: msg } = await axios.get(`${MAILPIT}/api/v1/message/${match.ID}`);
      return msg;
    }
    await new Promise(r => setTimeout(r, 500));
  }
  throw new Error(`No email received for ${to} within ${timeoutMs}ms`);
}

export async function fetchMagicLinkEmail(opts: { to: string; timeoutMs?: number }) {
  const email = await fetchLatestEmail(opts.to, opts.timeoutMs);
  return email.Body || email.HTML || '';
}
```

### 23.12 DB fixture helper — `helpers/db.ts`

```ts
import mysql from 'mysql2/promise';

let pool: mysql.Pool | null = null;

export function getDb() {
  if (!pool) {
    pool = mysql.createPool({
      host: process.env.DB_HOST,
      port: Number(process.env.DB_PORT),
      user: process.env.DB_USER,
      password: process.env.DB_PASSWORD,
      database: process.env.DB_NAME,
      waitForConnections: true,
      connectionLimit: 5,
    });
  }
  return pool;
}

export async function closeDb() { if (pool) await pool.end(); }
```

### 23.13 Security notification helper

The Mailpit client from `helpers/mail.ts` is reused to verify the four notification events:

```ts
export async function expectSecurityEmail(opts: {
  to: string;
  subjectPattern: RegExp;
  bodyPattern?: RegExp;
}) {
  const email = await fetchLatestEmail(opts.to, 10_000);
  expect(email.Subject).toMatch(opts.subjectPattern);
  if (opts.bodyPattern) {
    expect(email.Body).toMatch(opts.bodyPattern);
  }
}
```

### 23.14 Run commands

```bash
# Install
cd ../playwright
npm install
npx playwright install --with-deps chromium firefox webkit
cp .env.example .env  # edit values

# Run all tests
npx playwright test

# Run a specific suite
npx playwright test tests/auth/

# Run a single test in headed mode
npx playwright test tests/auth/login.password.spec.ts --headed

# Run with trace viewer on failure
npx playwright test --trace on

# View last HTML report
npx playwright show-report
```

### 23.15 CI integration

Tests run on every PR via GitHub Actions (or GitLab CI). The pipeline spins up the docker-compose stack as a service, waits for `sw-webserver` to return 200, then runs the Playwright suite. The HTML report and trace files are uploaded as artifacts.

GitHub Actions example (`.github/workflows/portal-e2e.yml`):
```yaml
name: Portal E2E
on: [pull_request]
jobs:
  e2e:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with: { node-version: 20 }
      - run: docker compose -f ../../docker-compose.yml up -d
      - run: |
          for i in {1..30}; do
            curl -sf http://localhost:8000 >/dev/null && break
            sleep 2
          done
      - run: npm ci
      - run: npx playwright install --with-deps
      - run: npx playwright test
      - uses: actions/upload-artifact@v4
        if: always()
        with:
          name: playwright-report
          path: artifacts/reports/
```

### 23.16 What the tests do NOT cover

- **PHP unit tests** (for `SticPortalAuthUtils` itself): should be added as a separate PHPUnit suite inside the CRM (existing `tests/unit/` directory). Playwright covers the E2E surface only.
- **Performance / load testing**: out of scope for Playwright. Use k6 or Locust for that.
- **Penetration testing**: out of scope. Run Burp/ZAP periodically.

### 23.17 Acceptance criteria for "ready to ship"

The feature is considered complete when:
1. All E2E tests in §23.7 pass on `chromium`, `firefox`, and `webkit` projects
2. HTML report shows 0 failures and 0 flaky tests over 10 consecutive runs
3. Lighthouse audit on `portalLogin` page: Performance ≥ 90, Accessibility ≥ 95, Best Practices ≥ 95
4. axe-core a11y checks pass with 0 serious/critical violations
5. CI pipeline runs in < 5 minutes
- **Multi-language**: All strings in 5 languages (ca_ES, en_us, es_ES, eu_ES, gl_ES); language selector on login page
