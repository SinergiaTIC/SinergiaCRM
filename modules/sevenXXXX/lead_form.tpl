{extends file='modules/seven/base_form.tpl'}
{block name=action}lead{/block}
{block name=table}
    <tr>
        <td>{$MOD.LBL_SEVEN_ACTIVE}</td>

        <td>
            {if empty($config.seven_lead_active)}
                {assign var='seven_lead_active' value=$seven_config.seven_lead_active.default}
            {else}
                {assign var='seven_lead_active' value=$config.seven_lead_active}
            {/if}

            <label for='seven_lead_active_yes'>{$MOD.LBL_SEVEN_YES}</label>
            <input
                    id='seven_lead_active_yes'
                    name='seven_lead_active'
                    type='radio'
                    value='yes'
                    {if $seven_lead_active =='yes'}checked{/if}
            />

            <label for='seven_lead_active_no'>{$MOD.LBL_SEVEN_NO}</label>
            <input
                    id='seven_lead_active_no'
                    name='seven_lead_active'
                    type='radio'
                    value='no'
                    {if $seven_lead_active =='no'}checked{/if}
            />
        </td>
    </tr>
{/block}
{block name=text_var}
    {if empty($config.seven_lead_body)}
        {assign var='seven_lead_body' value=$seven_config.seven_lead_body.default}
    {else}
        {assign var='seven_lead_body' value=$config.seven_lead_body}
    {/if}

    {assign var='seven_text_name' value='seven_lead_body'}
{/block}



