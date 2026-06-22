{*
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 *}
<style>
.portal-msg{margin:10px 0;padding:10px;border-radius:4px;font-size:13px}
.portal-msg-success{background:#e8f5e9;color:#2e7d32;border:1px solid #c8e6c9}
.portal-msg-warning{background:#fff3e0;color:#e65100;border:1px solid #ffe0b2}
.portal-logo-preview{max-height:40px;margin-top:5px}
.portal-input-narrow{width:80px}
.inline-help-content{display:none}
</style>

{if isset($smarty.get.portal_msg)}
<div class="portal-msg {if $smarty.get.portal_msg|strpos:"ok" !== false}portal-msg-success{else}portal-msg-warning{/if}">
    {if $smarty.get.portal_msg|strpos:"apply" !== false}Portal tab: {if $smarty.get.portal_msg|strpos:"ok" !== false}Added successfully. Check a Contact detail view.{else}Already exists (no changes needed).{/if}
    {else}Portal tab: {if $smarty.get.portal_msg|strpos:"ok" !== false}Removed successfully.{else}Not found (already removed).{/if}
    {/if}
</div>
{/if}

<form method="post" action="index.php?module=Administration&action=sticportalconfig_save" enctype="multipart/form-data">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="actionsContainer">
<tr><td>
    <input type="submit" class="button primary" value="{$APP.LBL_SAVE_BUTTON_LABEL}" title="{$APP.LBL_SAVE_BUTTON_TITLE}" accesskey="a">&nbsp;
    <input type="button" class="button" onclick="document.location.href='index.php?module=Administration&action=index'" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" title="{$APP.LBL_CANCEL_BUTTON_TITLE}">
</td></tr>
</table>

<!-- GENERAL -->
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
<tr><th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_STIC_PORTAL_GENERAL}</h4></th></tr>
<tr>
    <td width="25%" scope="row" valign="middle">
        {$MOD.LBL_STIC_PORTAL_TITLE}
        <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="1" aria-describedby="qtip-1"></i>
<script>$(this).prev("i.inline-help").removeAttr("data-hasqtip");setInlineHelpQtip();</script>
        <div class="inline-help-content">{$MOD.LBL_STIC_PORTAL_TITLE_HELP}</div>
    </td>
    <td width="25%" valign="middle"><input type="text" name="PORTAL_TITLE" value="{$SETTINGS.PORTAL_TITLE|escape}" size="40"></td>
    <td width="25%" scope="row" valign="middle">
        {$MOD.LBL_STIC_PORTAL_HOME_URL}
        <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="2" aria-describedby="qtip-2"></i>
<script>$(this).prev("i.inline-help").removeAttr("data-hasqtip");setInlineHelpQtip();</script>
        <div class="inline-help-content">{$MOD.LBL_STIC_PORTAL_HOME_URL_HELP}</div>
    </td>
    <td width="25%" valign="middle"><input type="text" name="PORTAL_HOME_URL" value="{$SETTINGS.PORTAL_HOME_URL|escape}" size="40"></td>
</tr>
<tr>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_LOGO}</td>
    <td width="25%" valign="middle">
        <input type="file" name="portal_logo_file" accept="image/*">
        {if $LOGO_URL}<br><img src="{$LOGO_URL|escape}" class="portal-logo-preview">{/if}
    </td>
    <td width="25%" scope="row" valign="middle">
        {$MOD.LBL_STIC_PORTAL_LOGO_WIDTH}
        <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="3" aria-describedby="qtip-3"></i>
<script>$(this).prev("i.inline-help").removeAttr("data-hasqtip");setInlineHelpQtip();</script>
        <div class="inline-help-content">{$MOD.LBL_STIC_PORTAL_LOGO_WIDTH_HELP}</div>
    </td>
    <td width="25%" valign="middle"><input type="number" name="PORTAL_LOGO_WIDTH" value="{$SETTINGS.PORTAL_LOGO_WIDTH|default:'212'}" min="0" class="portal-input-narrow"></td>
</tr>
</table>

<!-- PASSWORD POLICIES -->
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
<tr><th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_STIC_PORTAL_PASSWORD_POLICIES}</h4></th></tr>
<tr>
    <td width="25%" scope="row" valign="middle">
        {$MOD.LBL_STIC_PORTAL_PASSWORD_MIN_LENGTH}
        <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="4" aria-describedby="qtip-4"></i>
<script>$(this).prev("i.inline-help").removeAttr("data-hasqtip");setInlineHelpQtip();</script>
        <div class="inline-help-content">{$MOD.LBL_STIC_PORTAL_PASSWORD_MIN_LENGTH_HELP}</div>
    </td>
    <td width="25%" valign="middle"><input type="number" name="PORTAL_PASSWORD_MIN_LENGTH" value="{$SETTINGS.PORTAL_PASSWORD_MIN_LENGTH|default:'8'}" min="4" max="64" class="portal-input-narrow"> {$MOD.LBL_STIC_PORTAL_CHARACTERS}</td>
    <td width="25%" scope="row" valign="middle">
        {$MOD.LBL_STIC_PORTAL_PASSWORD_HISTORY}
        <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="5" aria-describedby="qtip-5"></i>
<script>$(this).prev("i.inline-help").removeAttr("data-hasqtip");setInlineHelpQtip();</script>
        <div class="inline-help-content">{$MOD.LBL_STIC_PORTAL_PASSWORD_HISTORY_HELP}</div>
    </td>
    <td width="25%" valign="middle"><input type="number" name="PORTAL_PASSWORD_HISTORY_COUNT" value="{$SETTINGS.PORTAL_PASSWORD_HISTORY_COUNT|default:'0'}" min="0" max="50" class="portal-input-narrow"> {$MOD.LBL_STIC_PORTAL_DISABLED}</td>
</tr>
<tr>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_PASSWORD_UPPER}</td>
    <td width="25%" valign="middle"><input type="checkbox" name="PORTAL_PASSWORD_REQUIRE_UPPER" value="1" {if $SETTINGS.PORTAL_PASSWORD_REQUIRE_UPPER eq '1'}checked{/if}></td>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_PASSWORD_LOWER}</td>
    <td width="25%" valign="middle"><input type="checkbox" name="PORTAL_PASSWORD_REQUIRE_LOWER" value="1" {if $SETTINGS.PORTAL_PASSWORD_REQUIRE_LOWER eq '1'}checked{/if}></td>
</tr>
<tr>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_PASSWORD_NUMBER}</td>
    <td width="25%" valign="middle"><input type="checkbox" name="PORTAL_PASSWORD_REQUIRE_NUMBER" value="1" {if $SETTINGS.PORTAL_PASSWORD_REQUIRE_NUMBER eq '1'}checked{/if}></td>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_PASSWORD_SPECIAL}</td>
    <td width="25%" valign="middle"><input type="checkbox" name="PORTAL_PASSWORD_REQUIRE_SPECIAL" value="1" {if $SETTINGS.PORTAL_PASSWORD_REQUIRE_SPECIAL eq '1'}checked{/if}></td>
</tr>
<tr>
    <td width="25%" scope="row" valign="middle">
        {$MOD.LBL_STIC_PORTAL_PASSWORD_EXPIRATION}
        <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="6" aria-describedby="qtip-6"></i>
<script>$(this).prev("i.inline-help").removeAttr("data-hasqtip");setInlineHelpQtip();</script>
        <div class="inline-help-content">{$MOD.LBL_STIC_PORTAL_PASSWORD_EXPIRATION_HELP}</div>
    </td>
    <td width="25%" valign="middle"><input type="number" name="PORTAL_PASSWORD_EXPIRATION_DAYS" value="{$SETTINGS.PORTAL_PASSWORD_EXPIRATION_DAYS|default:'0'}" min="0" max="365" class="portal-input-narrow"> {$MOD.LBL_STIC_PORTAL_NEVER}</td>
    <td width="25%" scope="row" valign="middle"></td>
    <td width="25%" valign="middle"></td>
</tr>
</table>

<!-- SECURITY -->
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
<tr><th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_STIC_PORTAL_SECURITY}</h4></th></tr>
<tr>
    <td width="25%" scope="row" valign="middle">
        {$MOD.LBL_STIC_PORTAL_MAX_ATTEMPTS}
        <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="7" aria-describedby="qtip-7"></i>
<script>$(this).prev("i.inline-help").removeAttr("data-hasqtip");setInlineHelpQtip();</script>
        <div class="inline-help-content">{$MOD.LBL_STIC_PORTAL_MAX_ATTEMPTS_HELP}</div>
    </td>
    <td width="25%" valign="middle"><input type="number" name="PORTAL_MAX_FAILED_ATTEMPTS" value="{$SETTINGS.PORTAL_MAX_FAILED_ATTEMPTS|default:'5'}" min="1" max="100" class="portal-input-narrow"> {$MOD.LBL_STIC_PORTAL_ATTEMPTS}</td>
    <td width="25%" scope="row" valign="middle">
        {$MOD.LBL_STIC_PORTAL_LOCKOUT_DURATION}
        <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="8" aria-describedby="qtip-8"></i>
<script>$(this).prev("i.inline-help").removeAttr("data-hasqtip");setInlineHelpQtip();</script>
        <div class="inline-help-content">{$MOD.LBL_STIC_PORTAL_LOCKOUT_DURATION_HELP}</div>
    </td>
    <td width="25%" valign="middle"><input type="number" name="PORTAL_LOCKOUT_DURATION_MINUTES" value="{$SETTINGS.PORTAL_LOCKOUT_DURATION_MINUTES|default:'30'}" min="1" max="1440" class="portal-input-narrow"> {$MOD.LBL_STIC_PORTAL_MINUTES}</td>
</tr>
<tr>
    <td width="25%" scope="row" valign="middle">
        {$MOD.LBL_STIC_PORTAL_REMEMBER_ME}
        <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="9" aria-describedby="qtip-9"></i>
<script>$(this).prev("i.inline-help").removeAttr("data-hasqtip");setInlineHelpQtip();</script>
        <div class="inline-help-content">{$MOD.LBL_STIC_PORTAL_REMEMBER_ME_HELP}</div>
    </td>
    <td width="25%" valign="middle"><input type="number" name="PORTAL_REMEMBER_ME_DAYS" value="{$SETTINGS.PORTAL_REMEMBER_ME_DAYS|default:'30'}" min="1" max="365" class="portal-input-narrow"> {$MOD.LBL_STIC_PORTAL_DAYS}</td>
    <td width="25%" scope="row" valign="middle">
        {$MOD.LBL_STIC_PORTAL_SESSION_TIMEOUT}
        <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="10" aria-describedby="qtip-10"></i>
<script>$(this).prev("i.inline-help").removeAttr("data-hasqtip");setInlineHelpQtip();</script>
        <div class="inline-help-content">{$MOD.LBL_STIC_PORTAL_SESSION_TIMEOUT_HELP}</div>
    </td>
    <td width="25%" valign="middle"><input type="number" name="PORTAL_SESSION_TIMEOUT_MINUTES" value="{$SETTINGS.PORTAL_SESSION_TIMEOUT_MINUTES|default:'60'}" min="1" max="1440" class="portal-input-narrow"> {$MOD.LBL_STIC_PORTAL_MINUTES}</td>
</tr>
<tr>
    <td width="25%" scope="row" valign="middle">
        {$MOD.LBL_STIC_PORTAL_AUDIT_RETENTION}
        <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="11" aria-describedby="qtip-11"></i>
<script>$(this).prev("i.inline-help").removeAttr("data-hasqtip");setInlineHelpQtip();</script>
        <div class="inline-help-content">{$MOD.LBL_STIC_PORTAL_AUDIT_RETENTION_HELP}</div>
    </td>
    <td width="25%" valign="middle"><input type="number" name="PORTAL_AUDIT_RETENTION_DAYS" value="{$SETTINGS.PORTAL_AUDIT_RETENTION_DAYS|default:'365'}" min="0" max="3650" class="portal-input-narrow"> {$MOD.LBL_STIC_PORTAL_DAYS}</td>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_CONCURRENT_SESSIONS}</td>
    <td width="25%" valign="middle"><input type="checkbox" name="PORTAL_ALLOW_CONCURRENT_SESSIONS" value="1" {if $SETTINGS.PORTAL_ALLOW_CONCURRENT_SESSIONS eq '1'}checked{/if}></td>
</tr>
</table>

<!-- MAGIC LINK -->
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
<tr><th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_STIC_PORTAL_MAGIC_LINK}</h4></th></tr>
<tr>
    <td width="25%" scope="row" valign="middle">
        {$MOD.LBL_STIC_PORTAL_MAGIC_ENABLED}
        <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="12" aria-describedby="qtip-12"></i>
<script>$(this).prev("i.inline-help").removeAttr("data-hasqtip");setInlineHelpQtip();</script>
        <div class="inline-help-content">{$MOD.LBL_STIC_PORTAL_MAGIC_ENABLED_HELP}</div>
    </td>
    <td width="25%" valign="middle"><input type="checkbox" name="PORTAL_MAGIC_LINK_ENABLED" value="1" {if $SETTINGS.PORTAL_MAGIC_LINK_ENABLED eq '1'}checked{/if}></td>
    <td width="25%" scope="row" valign="middle">
        {$MOD.LBL_STIC_PORTAL_MAGIC_EXPIRATION}
        <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="13" aria-describedby="qtip-13"></i>
<script>$(this).prev("i.inline-help").removeAttr("data-hasqtip");setInlineHelpQtip();</script>
        <div class="inline-help-content">{$MOD.LBL_STIC_PORTAL_MAGIC_EXPIRATION_HELP}</div>
    </td>
    <td width="25%" valign="middle"><input type="number" name="PORTAL_MAGIC_LINK_EXPIRATION_MINUTES" value="{$SETTINGS.PORTAL_MAGIC_LINK_EXPIRATION_MINUTES|default:'15'}" min="1" max="1440" class="portal-input-narrow"> {$MOD.LBL_STIC_PORTAL_MINUTES}</td>
</tr>
</table>

<!-- EMAIL TEMPLATES -->
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
<tr><th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_STIC_PORTAL_EMAIL_TEMPLATES}</h4></th></tr>
<tr>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_TEMPLATE_CONTACTS}</td>
    <td width="25%" valign="middle">
        <select name="PORTAL_TMPL_CRED_CONTACTS">{html_options options=$EMAIL_TEMPLATES selected=$SETTINGS.PORTAL_TMPL_CRED_CONTACTS}</select>
        <input type="button" class="button" value="{$MOD.LBL_STIC_PORTAL_CREATE|escape}" onclick="document.location.href='index.php?module=EmailTemplates&action=EditView'">
        <input type="button" class="button" value="{$MOD.LBL_STIC_PORTAL_EDIT|escape}" onclick="var s=document.querySelector('[name=PORTAL_TMPL_CRED_CONTACTS]'); if(s.value) document.location.href='index.php?module=EmailTemplates&action=EditView&record='+s.value;">
    </td>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_TEMPLATE_ACCOUNTS}</td>
    <td width="25%" valign="middle">
        <select name="PORTAL_TMPL_CRED_ACCOUNTS">{html_options options=$EMAIL_TEMPLATES selected=$SETTINGS.PORTAL_TMPL_CRED_ACCOUNTS}</select>
        <input type="button" class="button" value="{$MOD.LBL_STIC_PORTAL_CREATE|escape}" onclick="document.location.href='index.php?module=EmailTemplates&action=EditView'">
        <input type="button" class="button" value="{$MOD.LBL_STIC_PORTAL_EDIT|escape}" onclick="var s=document.querySelector('[name=PORTAL_TMPL_CRED_ACCOUNTS]'); if(s.value) document.location.href='index.php?module=EmailTemplates&action=EditView&record='+s.value;">
    </td>
</tr>
<tr>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_TEMPLATE_RESET}</td>
    <td width="25%" valign="middle">
        <select name="PORTAL_TMPL_RESET">{html_options options=$EMAIL_TEMPLATES selected=$SETTINGS.PORTAL_TMPL_RESET}</select>
        <input type="button" class="button" value="{$MOD.LBL_STIC_PORTAL_CREATE|escape}" onclick="document.location.href='index.php?module=EmailTemplates&action=EditView'">
        <input type="button" class="button" value="{$MOD.LBL_STIC_PORTAL_EDIT|escape}" onclick="var s=document.querySelector('[name=PORTAL_TMPL_RESET]'); if(s.value) document.location.href='index.php?module=EmailTemplates&action=EditView&record='+s.value;">
    </td>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_TEMPLATE_MAGIC}</td>
    <td width="25%" valign="middle">
        <select name="PORTAL_TMPL_MAGIC">{html_options options=$EMAIL_TEMPLATES selected=$SETTINGS.PORTAL_TMPL_MAGIC}</select>
        <input type="button" class="button" value="{$MOD.LBL_STIC_PORTAL_CREATE|escape}" onclick="document.location.href='index.php?module=EmailTemplates&action=EditView'">
        <input type="button" class="button" value="{$MOD.LBL_STIC_PORTAL_EDIT|escape}" onclick="var s=document.querySelector('[name=PORTAL_TMPL_MAGIC]'); if(s.value) document.location.href='index.php?module=EmailTemplates&action=EditView&record='+s.value;">
    </td>
</tr>
</table>

<!-- SECURITY NOTIFICATIONS -->
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
<tr><th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_STIC_PORTAL_NOTIFICATIONS}</h4></th></tr>
<tr>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_NOTIFY_PASSWORD_CHANGED}</td>
    <td width="25%" valign="middle">
        <input type="checkbox" name="PORTAL_NOTIFY_PASSWORD_CHANGED" value="1" {if $SETTINGS.PORTAL_NOTIFY_PASSWORD_CHANGED eq '1'}checked{/if}>
        <select name="PORTAL_TMPL_NOTIFY_PWCHG">{html_options options=$EMAIL_TEMPLATES selected=$SETTINGS.PORTAL_TMPL_NOTIFY_PWCHG}</select>
    </td>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_NOTIFY_NEW_LOGIN}</td>
    <td width="25%" valign="middle">
        <input type="checkbox" name="PORTAL_NOTIFY_NEW_LOGIN" value="1" {if $SETTINGS.PORTAL_NOTIFY_NEW_LOGIN eq '1'}checked{/if}>
        <select name="PORTAL_TMPL_NOTIFY_LOGIN">{html_options options=$EMAIL_TEMPLATES selected=$SETTINGS.PORTAL_TMPL_NOTIFY_LOGIN}</select>
    </td>
</tr>
<tr>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_NOTIFY_ACCOUNT_LOCKED}</td>
    <td width="25%" valign="middle">
        <input type="checkbox" name="PORTAL_NOTIFY_ACCOUNT_LOCKED" value="1" {if $SETTINGS.PORTAL_NOTIFY_ACCOUNT_LOCKED eq '1'}checked{/if}>
        <select name="PORTAL_TMPL_NOTIFY_LOCK">{html_options options=$EMAIL_TEMPLATES selected=$SETTINGS.PORTAL_TMPL_NOTIFY_LOCK}</select>
    </td>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_NOTIFY_RESET_REQUESTED}</td>
    <td width="25%" valign="middle">
        <input type="checkbox" name="PORTAL_NOTIFY_RESET_REQUESTED" value="1" {if $SETTINGS.PORTAL_NOTIFY_RESET_REQUESTED eq '1'}checked{/if}>
        <select name="PORTAL_TMPL_NOTIFY_RESET">{html_options options=$EMAIL_TEMPLATES selected=$SETTINGS.PORTAL_TMPL_NOTIFY_RESET}</select>
    </td>
</tr>
</table>

<!-- BULK ACTIONS -->
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
<tr><th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_STIC_PORTAL_BULK_ACTIONS}</h4></th></tr>
<tr>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_CLEAR_LOCKOUTS}</td>
    <td width="25%" valign="middle">
        <input type="button" class="button" value="{$MOD.LBL_STIC_PORTAL_CLEAR_LOCKOUTS|escape}" onclick="if(confirm('{$MOD.LBL_STIC_PORTAL_CONFIRM_CLEAR_LOCKOUTS|escape}')) document.location.href='index.php?module=Administration&action=sticportalconfig_clearlockouts';">
    </td>
    <td width="25%" scope="row" valign="middle">{$MOD.LBL_STIC_PORTAL_CLEAR_SESSIONS}</td>
    <td width="25%" valign="middle">
        <input type="button" class="button" value="{$MOD.LBL_STIC_PORTAL_CLEAR_SESSIONS|escape}" onclick="if(confirm('{$MOD.LBL_STIC_PORTAL_CONFIRM_CLEAR_SESSIONS|escape}')) document.location.href='index.php?module=Administration&action=sticportalconfig_clearsessions';">
    </td>
</tr>
</table>

<!-- LOGIN AUDIT LOG -->
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
<tr><th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_STIC_PORTAL_LOGIN_AUDIT}</h4></th></tr>
<tr>
    <td width="25%" scope="row" valign="middle">
        <i class="inline-help glyphicon glyphicon-info-sign" data-hasqtip="14" aria-describedby="qtip-14"></i>
<script>$(this).prev("i.inline-help").removeAttr("data-hasqtip");setInlineHelpQtip();</script>
        <div class="inline-help-content">{$MOD.LBL_STIC_PORTAL_LOGIN_AUDIT_HELP}</div>
    </td>
    <td width="75%" valign="middle">
        <input type="button" class="button" value="{$MOD.LBL_STIC_PORTAL_VIEW_LOG|escape}" onclick="document.location.href='index.php?module=Administration&action=sticportalconfig_audit'">
    </td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="actionsContainer">
<tr><td>
    <input type="submit" class="button primary" value="{$APP.LBL_SAVE_BUTTON_LABEL}" title="{$APP.LBL_SAVE_BUTTON_TITLE}" accesskey="a">&nbsp;
    <input type="button" class="button" onclick="document.location.href='index.php?module=Administration&action=index'" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" title="{$APP.LBL_CANCEL_BUTTON_TITLE}">
</td></tr>
</table>

</form>

{literal}
<script>
$(function() {
    $("i.inline-help").removeAttr("data-hasqtip");
    setInlineHelpQtip();
});
</script>
{/literal}
