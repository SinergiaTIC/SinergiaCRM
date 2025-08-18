<table id='google_table' width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr>
                    <th align="left" scope="row" colspan='2'>
                        <h4>{$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_TITLE}</h4>
                    </th>
                </tr>
                <tr>
                    <td>{$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_ENABLE}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_ENABLE_HELP}
                    </td>
                    {if $OAUTH_CONFIG.enabled == 'true'}
                        {assign var='oauth_google_enabled_checked' value='CHECKED'}
                    {else}
                        {assign var='oauth_google_enabled_checked' value=''}
                    {/if}
                    <td>
                        <input name="authenticationOauthProviders_Google_enabled" id="Google_enabled_checkbox" class="checkbox"
                            type="checkbox" value='true' {$oauth_google_enabled_checked}>
                    </td>
                </tr>
                <tr>
                    <td>{$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_CLIENT_ID}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_CLIENT_ID_HELP}
                    </td>
                    <td>
                        <input name="authenticationOauthProviders_Google_clientId" id="authenticationOauthProviders_Google_clientId" size='100' type="text"
                            value="{$OAUTH_CONFIG.clientId}">
                    </td>

                </tr>
                <tr>
                    <td>{$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_CLIENT_SECRET}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_CLIENT_SECRET_HELP}
                    </td>
                    <td>
                        <input name="authenticationOauthProviders_Google_clientSecret" id="authenticationOauthProviders_Google_clientSecret" size='100' type="text"
                            value="{$OAUTH_CONFIG.clientSecret}">
                    </td>
                </tr>
                <tr>
                    <td>{$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_SCOPES}
                        {sugar_help text=$OAUTH_LANG.LBL_OAUTH_AUTH_GOOGLE_SCOPES_HELP}
                    </td>
                    <td>
                        <input name="authenticationOauthProviders_Google_scopes" id="authenticationOauthProviders_Google_scopes" size='100' type="text"
                            value="{$OAUTH_CONFIG.scopes}">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>