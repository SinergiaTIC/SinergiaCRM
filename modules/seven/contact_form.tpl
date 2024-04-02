{extends file='modules/seven/base_form.tpl'}
{block name=action}contact{/block}
{block name=table}
    <tr>
        <td>{$MOD.LBL_SEVEN_ACTIVE}</td>
        <td>
            {if empty($config.seven_contact_active)}
                {assign var='seven_contact_active' value=$seven_config.seven_contact_active.default}
            {else}
                {assign var='seven_contact_active' value=$config.seven_contact_active}
            {/if}

            <label for='seven_contact_active_yes'>Yes</label>
            <input
                    id='seven_contact_active_yes'
                    type='radio'
                    value='yes'
                    name='seven_contact_active'
                    {if $seven_contact_active =='yes'}checked{/if}
            />

            <label for='seven_contact_active_no'>No</label>
            <input
                    id='seven_contact_active_no'
                    type='radio'
                    name='seven_contact_active'
                    value='no'
                    {if $seven_contact_active =='no'}checked{/if}
            />
        </td>
    </tr>
{/block}
{block name=text_var}
    {if empty($config.seven_contact_body)}
        {assign var='seven_text_content' value=$seven_config.seven_contact_body.default}
    {else}
        {assign var='seven_text_content' value=$config.seven_contact_body}
    {/if}

    {assign var='seven_text_name' value='seven_text_content'}
{/block}


