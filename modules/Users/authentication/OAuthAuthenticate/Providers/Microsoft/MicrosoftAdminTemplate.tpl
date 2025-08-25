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
<table id='microsoft_table' name="oauth_auth_provider" width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr>
                    <th align="left" scope="row" colspan='2'>
                        <h4>{$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_TITLE}</h4>
                    </th>
                </tr>
                <tr>
                    <td width="25%" scope="row" valign='middle'>{$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_ENABLE}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_ENABLE_HELP}
                    </td>
                    {if $OAUTH_CONFIG.enabled == 'true'}
                        {assign var='oauth_microsoft_enabled_checked' value='CHECKED'}
                    {else}
                        {assign var='oauth_microsoft_enabled_checked' value=''}
                    {/if}
                    <td>
                        <input name="authenticationOauthProviders_Microsoft_enabled" type="hidden" value='false'>
                        <input name="authenticationOauthProviders_Microsoft_enabled" id="Microsoft_enabled_checkbox" class="checkbox"
                            type="checkbox" value='true' {$oauth_microsoft_enabled_checked}>
                    </td>
                </tr>
                <tr name='microsoft_params'>
                    <td class="conditional-required">{$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_CLIENT_ID}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_CLIENT_ID_HELP}
                    </td>
                    <td><input name="authenticationOauthProviders_Microsoft_clientId" id="authenticationOauthProviders_Microsoft_clientId" size='100' type="text"
                            value="{$OAUTH_CONFIG.clientId}">
                    </td>
                </tr>
                <tr name='microsoft_params'>
                    <td class="conditional-required">{$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_TENANT_ID}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_TENANT_ID_HELP}
                    </td>
                    <td>
                        <input name="authenticationOauthProviders_Microsoft_tenantId" id="authenticationOauthProviders_Microsoft_tenantId" size='100' type="text"
                            value="{$OAUTH_CONFIG.tenantId}">
                    </td>
                </tr>
                <tr name='microsoft_params'>
                    <td>{$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_SCOPES}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_SCOPES_HELP}
                    </td>
                    <td>
                        <input name="authenticationOauthProviders_Microsoft_scopes" id="authenticationOauthProviders_Microsoft_scopes" size='100' type="text"
                            value="{$OAUTH_CONFIG.scopes}">
                    </td>
                </tr>
                <tr name='microsoft_params'>
                    <td>{$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_REDIRECT_URI}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_REDIRECT_URI_HELP}
                    </td>
                    <td>
                        <input name="authenticationOauthProviders_Microsoft_redirectUri" id="authenticationOauthProviders_Microsoft_redirectUri" size='100' type="text"
                            value="{$OAUTH_CONFIG.redirectUri}">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
{literal}
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        function toggleMicrosoftParams() {
            const checkbox = document.getElementById("Microsoft_enabled_checkbox");
            const clientId = document.getElementById("authenticationOauthProviders_Microsoft_clientId");
            const tenantId = document.getElementById("authenticationOauthProviders_Microsoft_tenantId");
            const rows = document.querySelectorAll("tr[name='microsoft_params']");
    
            rows.forEach(row => {
                if (row.contains(checkbox)) {
                    row.style.display = "";
                    return;
                }
    
                if (checkbox.checked) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
    
            // addToValidate SuiteCRM aren't working in this context. So we use require HTML5 attribute
            if (checkbox.checked) {
                clientId.setAttribute("required", "required");
                tenantId.setAttribute("required", "required");
            } else {
                clientId.removeAttribute("required");
                tenantId.removeAttribute("required");
             }
        }
    
        toggleMicrosoftParams();
    
        document.getElementById("Microsoft_enabled_checkbox")
            .addEventListener("change", toggleMicrosoftParams);
    });
    </script>
    {/literal}