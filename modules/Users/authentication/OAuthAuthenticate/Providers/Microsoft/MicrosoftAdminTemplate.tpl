<table id='microsoft_table' width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr>
                    <th align="left" scope="row" colspan='2'>
                        <h4>{$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_TITLE}</h4>
                    </th>
                </tr>
                <tr>
                    <td>{$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_ENABLE}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_ENABLE_HELP}
                    </td>
                    {if $OAUTH_CONFIG.enabled == 'true'}
                        {assign var='oauth_microsoft_enabled_checked' value='CHECKED'}
                    {else}
                        {assign var='oauth_microsoft_enabled_checked' value=''}
                    {/if}
                    <td>
                        <input name="authenticationOauthProviders_Microsoft_enabled" id="Microsoft_enabled_checkbox" class="checkbox"
                            type="checkbox" value='true' {$oauth_microsoft_enabled_checked}>
                    </td>
                </tr>
                <tr>
                    <td>{$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_CLIENT_ID}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_CLIENT_ID_HELP}
                    </td>
                    <td><input name="authenticationOauthProviders_Microsoft_clientId" id="authenticationOauthProviders_Microsoft_clientId" size='100' type="text"
                            value="{$OAUTH_CONFIG.clientId}">
                    </td>
                </tr>
                <tr>
                    <td>{$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_TENANT_ID}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_TENANT_ID_HELP}
                    </td>
                    <td>
                        <input name="authenticationOauthProviders_Microsoft_tenantId" id="authenticationOauthProviders_Microsoft_tenantId" size='100' type="text"
                            value="{$OAUTH_CONFIG.tenantId}">
                    </td>
                </tr>
                <tr>
                    <td>{$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_SCOPES}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_MICROSOFT_SCOPES_HELP}
                    </td>
                    <td>
                        <input name="authenticationOauthProviders_Microsoft_scopes" id="authenticationOauthProviders_Microsoft_scopes" size='100' type="text"
                            value="{$OAUTH_CONFIG.scopes}">
                    </td>
                </tr>
                <tr>
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