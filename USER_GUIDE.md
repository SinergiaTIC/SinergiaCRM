# Portal Authentication — User Guide

## For Administrators

### Enabling portal access for a Contact/Account

1. Go to the Contact or Account record in the CRM
2. Set **Portal Username** (`stic_portal_username_c`) — defaults to primary email if left empty
3. Set a **Portal Password** in the password field
4. Check **Portal Enabled** (`stic_portal_enabled_c`)
5. Save the record

The system will bcrypt-hash the password before storing it.

### Configuring portal settings

1. Go to **Administration → Portal Configuration**
2. Configure:
   - **General**: portal title, home URL, logo upload
   - **Password Policies**: min length, uppercase/lowercase/number/special requirements, expiration, history
   - **Security**: max failed attempts, lockout duration, remember-me days, session timeout
   - **Magic Link**: enable/disable, expiration, email template
   - **Notifications**: email alerts for password change, new login, account lockout

### Creating an OAuth2 Client for external apps

1. Go to **Administration → OAuth2 Clients and Tokens**
2. Create a new client with:
   - **Name**: your external app name
   - **Allowed Grant Type**: `password` (this enables `portal_password` too)
   - **Secret**: auto-generated SHA-256 hash (or set manually)
   - **Redirect URI**: your app's callback URL (e.g. `https://yourapp.com/callback`)
3. Give the **Client ID** and **Secret** to the external app developer

### Managing sessions and lockouts

- **Clear All Lockouts**: resets failed attempts and unlocks all portal users
- **Clear All Sessions**: forces all portal users to re-authenticate
- **Login Audit Log**: view recent login attempts with success/failure, IP, and method

---

## For External App Developers

### OAuth 2.0 Authentication (password grant)

Use SuiteCRM's League OAuth2 Server:

```bash
POST http://your-crm.com/Api/access_token
Content-Type: application/x-www-form-urlencoded

grant_type=portal_password
&username=PORTAL_USERNAME
&password=PORTAL_PASSWORD
&client_id=OAUTH2_CLIENT_UUID
&client_secret=OAUTH2_CLIENT_SECRET
```

Response:
```json
{
  "token_type": "Bearer",
  "expires_in": 3600,
  "access_token": "eyJ0eXAiOiJKV1Q...",
  "refresh_token": "def50200..."
}
```

### Refreshing tokens

```bash
POST http://your-crm.com/Api/access_token
grant_type=refresh_token&refresh_token=REFRESH_TOKEN&client_id=CLIENT_ID&client_secret=CLIENT_SECRET
```

### Client demo

See `../clientOauth/` for a complete PHP example.
