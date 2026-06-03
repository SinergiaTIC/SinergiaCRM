{literal}
<h2>Portal Configuration</h2>
<form method="post" action="index.php?module=Administration&action=sticportalconfig_save" enctype="multipart/form-data">

<h3>General Settings</h3>
<table class="edit view" width="100%"><tbody>
<tr><td width="15%" class="dataLabel">{/literal}Portal Title{literal}</td><td width="85%"><input type="text" name="PORTAL_TITLE" value="{/literal}{$SETTINGS.PORTAL_TITLE|escape}{literal}"></td></tr>
<tr><td class="dataLabel">{/literal}Home URL{literal}</td><td><input type="text" name="PORTAL_HOME_URL" value="{/literal}{$SETTINGS.PORTAL_HOME_URL|escape}{literal}" size="60"></td></tr>
<tr><td class="dataLabel">{/literal}Logo{literal}</td><td><input type="file" name="portal_logo_file" accept="image/*"><br><img src="{/literal}{$LOGO_URL|escape}{literal}" style="max-height:40px;margin-top:5px;" alt="Current logo"></td></tr>
<tr><td class="dataLabel">{/literal}Logo Max Width{literal}</td><td><input type="number" name="PORTAL_LOGO_WIDTH" value="{/literal}{$SETTINGS.PORTAL_LOGO_WIDTH|default:'212'}{literal}"></td></tr>
</tbody></table>

<h3>Password Policies</h3>
<table class="edit view" width="100%"><tbody>
<tr><td class="dataLabel">{/literal}Min Length{literal}</td><td><input type="number" name="PORTAL_PASSWORD_MIN_LENGTH" value="{/literal}{$SETTINGS.PORTAL_PASSWORD_MIN_LENGTH|default:'8'}{literal}" min="1" max="64"></td></tr>
<tr><td class="dataLabel">{/literal}Require Uppercase{literal}</td><td><input type="checkbox" name="PORTAL_PASSWORD_REQUIRE_UPPER" value="1" {/literal}{if $SETTINGS.PORTAL_PASSWORD_REQUIRE_UPPER eq '1'}checked{/if}{literal}></td></tr>
<tr><td class="dataLabel">{/literal}Require Lowercase{literal}</td><td><input type="checkbox" name="PORTAL_PASSWORD_REQUIRE_LOWER" value="1" {/literal}{if $SETTINGS.PORTAL_PASSWORD_REQUIRE_LOWER eq '1'}checked{/if}{literal}></td></tr>
<tr><td class="dataLabel">{/literal}Require Number{literal}</td><td><input type="checkbox" name="PORTAL_PASSWORD_REQUIRE_NUMBER" value="1" {/literal}{if $SETTINGS.PORTAL_PASSWORD_REQUIRE_NUMBER eq '1'}checked{/if}{literal}></td></tr>
<tr><td class="dataLabel">{/literal}Require Special{literal}</td><td><input type="checkbox" name="PORTAL_PASSWORD_REQUIRE_SPECIAL" value="1" {/literal}{if $SETTINGS.PORTAL_PASSWORD_REQUIRE_SPECIAL eq '1'}checked{/if}{literal}></td></tr>
<tr><td class="dataLabel">{/literal}Expiration Days{literal}</td><td><input type="number" name="PORTAL_PASSWORD_EXPIRATION_DAYS" value="{/literal}{$SETTINGS.PORTAL_PASSWORD_EXPIRATION_DAYS|default:'0'}{literal}" min="0" max="365"> (0=never)</td></tr>
<tr><td class="dataLabel">{/literal}History Count{literal}</td><td><input type="number" name="PORTAL_PASSWORD_HISTORY_COUNT" value="{/literal}{$SETTINGS.PORTAL_PASSWORD_HISTORY_COUNT|default:'0'}{literal}" min="0" max="50"> (0=disabled)</td></tr>
</tbody></table>

<h3>Security Settings</h3>
<table class="edit view" width="100%"><tbody>
<tr><td class="dataLabel">{/literal}Max Failed Attempts{literal}</td><td><input type="number" name="PORTAL_MAX_FAILED_ATTEMPTS" value="{/literal}{$SETTINGS.PORTAL_MAX_FAILED_ATTEMPTS|default:'5'}{literal}" min="1" max="100"></td></tr>
<tr><td class="dataLabel">{/literal}Lockout Duration (min){literal}</td><td><input type="number" name="PORTAL_LOCKOUT_DURATION_MINUTES" value="{/literal}{$SETTINGS.PORTAL_LOCKOUT_DURATION_MINUTES|default:'30'}{literal}" min="1" max="1440"></td></tr>
<tr><td class="dataLabel">{/literal}Remember Me Days{literal}</td><td><input type="number" name="PORTAL_REMEMBER_ME_DAYS" value="{/literal}{$SETTINGS.PORTAL_REMEMBER_ME_DAYS|default:'30'}{literal}" min="1" max="365"></td></tr>
<tr><td class="dataLabel">{/literal}Session Timeout (min){literal}</td><td><input type="number" name="PORTAL_SESSION_TIMEOUT_MINUTES" value="{/literal}{$SETTINGS.PORTAL_SESSION_TIMEOUT_MINUTES|default:'60'}{literal}" min="1" max="1440"></td></tr>
<tr><td class="dataLabel">{/literal}Concurrent Sessions{literal}</td><td><input type="checkbox" name="PORTAL_ALLOW_CONCURRENT_SESSIONS" value="1" {/literal}{if $SETTINGS.PORTAL_ALLOW_CONCURRENT_SESSIONS eq '1'}checked{/if}{literal}></td></tr>
<tr><td class="dataLabel">{/literal}CAPTCHA After Failures{literal}</td><td><input type="number" name="PORTAL_CAPTCHA_AFTER_FAILURES" value="{/literal}{$SETTINGS.PORTAL_CAPTCHA_AFTER_FAILURES|default:'3'}{literal}" min="0" max="100"> (0=disabled)</td></tr>
</tbody></table>

<h3>Magic Link Settings</h3>
<table class="edit view" width="100%"><tbody>
<tr><td class="dataLabel">{/literal}Enabled{literal}</td><td><input type="checkbox" name="PORTAL_MAGIC_LINK_ENABLED" value="1" {/literal}{if $SETTINGS.PORTAL_MAGIC_LINK_ENABLED eq '1'}checked{/if}{literal}></td></tr>
<tr><td class="dataLabel">{/literal}Expiration (min){literal}</td><td><input type="number" name="PORTAL_MAGIC_LINK_EXPIRATION_MINUTES" value="{/literal}{$SETTINGS.PORTAL_MAGIC_LINK_EXPIRATION_MINUTES|default:'15'}{literal}" min="1" max="1440"></td></tr>
<tr><td class="dataLabel">{/literal}Template ID{literal}</td><td><input type="text" name="PORTAL_MAGIC_LINK_TEMPLATE" value="{/literal}{$SETTINGS.PORTAL_MAGIC_LINK_TEMPLATE|escape}{literal}" size="40"></td></tr>
</tbody></table>

<h3>Notifications</h3>
<table class="edit view" width="100%"><tbody>
<tr><td class="dataLabel">{/literal}Password Changed{literal}</td><td><input type="checkbox" name="PORTAL_NOTIFY_PASSWORD_CHANGED" value="1" {/literal}{if $SETTINGS.PORTAL_NOTIFY_PASSWORD_CHANGED eq '1'}checked{/if}{literal}></td></tr>
<tr><td class="dataLabel">{/literal}New Login{literal}</td><td><input type="checkbox" name="PORTAL_NOTIFY_NEW_LOGIN" value="1" {/literal}{if $SETTINGS.PORTAL_NOTIFY_NEW_LOGIN eq '1'}checked{/if}{literal}></td></tr>
<tr><td class="dataLabel">{/literal}Account Locked{literal}</td><td><input type="checkbox" name="PORTAL_NOTIFY_ACCOUNT_LOCKED" value="1" {/literal}{if $SETTINGS.PORTAL_NOTIFY_ACCOUNT_LOCKED eq '1'}checked{/if}{literal}></td></tr>
<tr><td class="dataLabel">{/literal}Reset Requested{literal}</td><td><input type="checkbox" name="PORTAL_NOTIFY_RESET_REQUESTED" value="1" {/literal}{if $SETTINGS.PORTAL_NOTIFY_RESET_REQUESTED eq '1'}checked{/if}{literal}></td></tr>
</tbody></table>

<input type="submit" class="button primary" value="Save Settings">
</form>

<h3>Bulk Actions</h3>
<form method="post" action="index.php?module=Administration&action=sticportalconfig_clearlockouts" style="display:inline">
  <input type="submit" class="button" value="Clear All Lockouts">
</form>
<form method="post" action="index.php?module=Administration&action=sticportalconfig_clearsessions" style="display:inline">
  <input type="submit" class="button" value="Clear All Sessions">
</form>

<h3>Login Audit Log (Last 20)</h3>
<table class="list view" width="100%">
<thead><tr>
  <th>{/literal}Date{literal}</th><th>{/literal}Username{literal}</th><th>{/literal}Type{literal}</th><th>{/literal}IP{literal}</th><th>{/literal}Result{literal}</th><th>{/literal}Reason{literal}</th><th>{/literal}Method{literal}</th>
</tr></thead>
<tbody>
{/literal}
{foreach from=$AUDIT item=r}
<tr>
  <td>{$r.date_entered|escape}</td>
  <td>{$r.username|escape}</td>
  <td>{$r.parent_type|escape}</td>
  <td>{$r.ip_address|escape}</td>
  <td>{if $r.success}Success{else}Failure{/if}</td>
  <td>{$r.failure_reason|escape}</td>
  <td>{$r.auth_method|escape}</td>
</tr>
{/foreach}
</tbody></table>
