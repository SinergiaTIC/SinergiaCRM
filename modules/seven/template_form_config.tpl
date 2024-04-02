<form method='POST' action='index.php' enctype='multipart/form-data'>
    <input type='hidden' name='module' value='seven'/>
    <input type='hidden' name='action' value='index'/>

    <span class='error'>{$error.main}</span>

    <table>
        <tr>
            <td><label for='seven_api_key'>{$MOD.LBL_SEVEN_API_KEY}</label></td>
            <td>
                {if empty($config.seven_api_key)}
                    {assign var='seven_api_key' value=$seven_config.seven_api_key.default}
                {else}
                    {assign var='seven_api_key' value=$config.seven_api_key}
                {/if}

                <input
                        id='seven_api_key'
                        name='seven_api_key'
                        placeholder='{$seven_config.seven_api_key.placeholder}'
                        required
                        size='64'
                        type='password'
                        value='{$seven_api_key}'
                />
            </td>
        </tr>

        <tr>
            <td><label for='seven_sender'>{$MOD.LBL_SEVEN_SENDER}</label></td>

            <td>
                {if empty($config.seven_sender)}
                    {assign var='seven_sender' value=$seven_config.seven_sender.default}
                {else}
                    {assign var='seven_sender' value=$config.seven_sender}
                {/if}

                <input
                        id='seven_sender'
                        maxlength='16'
                        name='seven_sender'
                        size='16'
                        type='text'
                        value='{$seven_sender}'
                        placeholder='{$seven_config.seven_sender.placeholder}'
                />
            </td>
        </tr>

        <tr>
            <td>{$MOD.LBL_SEVEN_ACTIVE}</td>

            <td>
                {if empty($config.seven_active)}
                    {assign var='seven_active' value=$seven_config.seven_active.default}
                {else}
                    {assign var='seven_active' value=$config.seven_active}
                {/if}

                <label for='seven_active_yes'>{$MOD.LBL_SEVEN_YES}</label>
                <input
                        id='seven_active_yes'
                        name='seven_active'
                        type='radio'
                        value='yes'
                        {if $seven_active =='yes'}checked{/if}
                />

                <label for='seven_active_no'>{$MOD.LBL_SEVEN_NO}</label>
                <input
                        id='seven_active_no'
                        name='seven_active'
                        type='radio'
                        value='no'
                        {if $seven_active =='no'}checked{/if}
                />
            </td>
        </tr>

        <tr>
            <td>{$MOD.LBL_SEVEN_USER_FRIENDLY_RESPONSES}</td>

            <td>
                {if empty($config.seven_user_friendly_responses)}
                    {assign var='seven_user_friendly_responses' value=$seven_config.seven_user_friendly_responses.default}
                {else}
                    {assign var='seven_user_friendly_responses' value=$config.seven_user_friendly_responses}
                {/if}

                <label for='seven_user_friendly_responses_yes'>{$MOD.LBL_SEVEN_YES}</label>
                <input
                        id='seven_user_friendly_responses_yes'
                        name='seven_user_friendly_responses'
                        type='radio'
                        value='yes'
                        {if $seven_active =='yes'}checked{/if}
                />

                <label for='seven_user_friendly_responses_no'>{$MOD.LBL_SEVEN_NO}</label>
                <input
                        id='seven_user_friendly_responses_no'
                        name='seven_user_friendly_responses'
                        type='radio'
                        value='no'
                        {if $seven_active =='no'}checked{/if}
                />
            </td>
        </tr>
    </table>
    <br/>
    <div>
        <input
                class='button'
                name='save'
                title='{$APP.LBL_SAVE_BUTTON_TITLE}'
                type='submit'
                value='{$APP.LBL_SAVE_BUTTON_LABEL}'
        />

        <input
                class='button' type='button' name='cancel'
                onclick='document.location.href="index.php?module=Administration&action=index"'
                title='{$MOD.LBL_CANCEL_BUTTON_TITLE}'
                value='{$APP.LBL_CANCEL_BUTTON_LABEL}'
        />
    </div>
</form>



