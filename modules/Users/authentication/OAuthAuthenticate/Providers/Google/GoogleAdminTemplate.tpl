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
<table id='google_table' name="oauth_auth_provider" width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr>
                    <th align="left" scope="row" colspan='2'>
                        <h4>{$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_TITLE}</h4>
                    </th>
                </tr>
                <tr>
                    <td width="25%" scope="row" valign='middle'>{$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_ENABLE}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_ENABLE_HELP}
                    </td>
                    {if $OAUTH_CONFIG.enabled == 'true'}
                        {assign var='oauth_google_enabled_checked' value='CHECKED'}
                    {else}
                        {assign var='oauth_google_enabled_checked' value=''}
                    {/if}
                    <td>
                        <input name="authenticationOauthProviders_Google_enabled" type="hidden" value='false'>
                        <input name="authenticationOauthProviders_Google_enabled" id="Google_enabled_checkbox" class="checkbox"
                            type="checkbox" value='true' {$oauth_google_enabled_checked}>
                    </td>
                </tr>
                <tr name='google_params'>
                    <td class="conditional-required">{$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_CLIENT_ID}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_CLIENT_ID_HELP}
                    </td>
                    <td>
                        <input name="authenticationOauthProviders_Google_clientId" id="authenticationOauthProviders_Google_clientId" size='100' type="text"
                            value="{$OAUTH_CONFIG.clientId}">
                    </td>

                </tr>
                <tr name='google_params'>
                    <td class="conditional-required">{$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_CLIENT_SECRET}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_CLIENT_SECRET_HELP}
                    </td>
                    <td>
                        <input name="authenticationOauthProviders_Google_clientSecret" id="authenticationOauthProviders_Google_clientSecret" size='100' type="text"
                            value="{$OAUTH_CONFIG.clientSecret}">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
{literal}
<script>
document.addEventListener("DOMContentLoaded", function () {
    function toggleGoogleParams() {
        const checkbox = document.getElementById("Google_enabled_checkbox");
        const clientId = document.getElementById("authenticationOauthProviders_Google_clientId");
        const clientSecret = document.getElementById("authenticationOauthProviders_Google_clientSecret");
        const rows = document.querySelectorAll("tr[name='google_params']");

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
            clientSecret.setAttribute("required", "required");
        } else {
            clientId.removeAttribute("required");
            clientSecret.removeAttribute("required");
        }
    }

    toggleGoogleParams();

    document.getElementById("Google_enabled_checkbox")
        .addEventListener("change", toggleGoogleParams);
});
</script>
{/literal}
