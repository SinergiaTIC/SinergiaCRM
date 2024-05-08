{extends file='modules/seven/base_form.tpl'}
{block name=action}account{/block}
{block name=table}
    <tr>
        <td>{$MOD.LBL_SEVEN_ACTIVE}</td>

        <td>
            {if empty($config.seven_account_active)}
                {assign var='seven_account_active' value=$seven_config.seven_account_active.default}
            {else}
                {assign var='seven_account_active' value=$config.seven_account_active}
            {/if}

            <label for='seven_account_active_yes'>{$MOD.LBL_SEVEN_YES}</label>
            <input
                    id='seven_account_active_yes'
                    name='seven_account_active'
                    type='radio'
                    value='yes'
                    {if $seven_account_active =='yes'}checked{/if}
            />

            <label for='seven_account_active_no'>{$MOD.LBL_SEVEN_NO}</label>
            <input
                    id='seven_account_active_no'
                    name='seven_account_active'
                    type='radio'
                    value='no'
                    {if $seven_account_active =='no'}checked{/if}
            />
        </td>
    </tr>
{/block}
{block name=text_var}
    {if empty($config.seven_account_body)}
        {assign var='seven_text_content' value=$seven_config.seven_account_body.default}
    {else}
        {assign var='seven_text_content' value=$config.seven_account_body}
    {/if}

    {assign var='seven_text_name' value='seven_text_content'}
{/block}


