{extends file='modules/seven/base_form.tpl'}
{block name=action}user{/block}
{block name=table}
    <tr>
        <td>{$MOD.LBL_SEVEN_ACTIVE}</td>

        <td>
            {if empty($config.seven_user_active)}
                {assign var='seven_user_active' value=$seven_config.seven_user_active.default}
            {else}
                {assign var='seven_user_active' value=$config.seven_user_active}
            {/if}

            <label for='seven_user_active_yes'>{$MOD.LBL_SEVEN_YES}</label>
            <input
                    id='seven_user_active_yes'
                    name='seven_user_active'
                    type='radio'
                    value='yes'
                    {if $seven_user_active =='yes'}checked{/if}
            />

            <label for='seven_user_active_no'>{$MOD.LBL_SEVEN_NO}</label>
            <input
                    id='seven_user_active_no'
                    name='seven_user_active'
                    type='radio'
                    value='no'
                    {if $seven_user_active =='no'}checked{/if}
            />
        </td>
    </tr>
{/block}
{block name=text_var}
    {if empty($config.seven_user_body)}
        {assign var='seven_user_body' value=$seven_config.seven_user_body.default}
    {else}
        {assign var='seven_user_body' value=$config.seven_user_body}
    {/if}

    {assign var='seven_text_name' value='seven_user_body'}
{/block}



