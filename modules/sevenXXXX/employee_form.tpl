{extends file='modules/seven/base_form.tpl'}
{block name=action}employee{/block}
{block name=table}
    <tr>
        <td>{$MOD.LBL_SEVEN_ACTIVE}</td>

        <td>
            {if empty($config.seven_employee_active)}
                {assign var='seven_employee_active' value=$seven_config.seven_employee_active.default}
            {else}
                {assign var='seven_employee_active' value=$config.seven_employee_active}
            {/if}

            <label for='seven_employee_active_yes'>{$MOD.LBL_SEVEN_YES}</label>
            <input
                    id='seven_employee_active_yes'
                    name='seven_employee_active'
                    type='radio'
                    value='yes'
                    {if $seven_employee_active =='yes'}checked{/if}
            />

            <label for='seven_employee_active_no'>{$MOD.LBL_SEVEN_NO}</label>
            <input
                    id='seven_employee_active_no'
                    name='seven_employee_active'
                    type='radio'
                    value='no'
                    {if $seven_employee_active =='no'}checked{/if}
            />
        </td>
    </tr>
{/block}
{block name=text_var}
    {if empty($config.seven_employee_body)}
        {assign var='seven_text_content' value=$seven_config.seven_employee_body.default}
    {else}
        {assign var='seven_text_content' value=$config.seven_employee_body}
    {/if}

    {assign var='seven_text_name' value='seven_employee_body'}
{/block}



