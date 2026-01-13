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
{* /*****************************************
.tpl template of 182 Model
****************************************/ *}
{literal}
    <style>
        #payment_type option:empty {
            display: none;
        }

    .letter13 {
        font-size: 13px;
    }

    .letter10 {
        font-size: 10px;
    }

    td,
    th {
        text-align: left;
        padding: 3px;
        padding-right: 50px;
    }
</style>
{/literal}

<table width="100%">
    <tr>
        {*TITLE*} <th style="text-align:left">
            <h2>{$MOD.LBL_M182_TITLE}</h2>
        </th>
    </tr>
</table>

{*ISSUING ORGANIZATION SELECTED*}
{if $ISSUING_ORGANIZATION_LABEL ne ''}
<br>
<p style="text-align:left;color:#000000;" class="wizard_info letter13">{$MOD.LBL_M182_ISSUING_ORGANIZATION_SELECTED}: <strong>{$ISSUING_ORGANIZATION_LABEL}</strong></p>
<br>
{/if}

{if $VAL.MISSING_SETTINGS|@count gt 0 || $VAL.MISSING_FIELDS|@count gt 0 }

    {if $VAL.MISSING_SETTINGS|@count gt 0}
    <p style="text-align:left;color:#d5061e;font-weight:bold;">
    {$MOD.LBL_M182_MISSING_SETTINGS}:
    <br>
    <ul>
        {foreach from=$VAL.MISSING_SETTINGS item=it}
        <li>{$it}</li>
        {/foreach}
    </ul>
    {/if}
    {if $VAL.MISSING_SETTINGS|@count gt 0 && $VAL.MISSING_FIELDS|@count gt 0 }
        <br>
    {/if}
    {if $VAL.MISSING_FIELDS|@count gt 0}
    <p style="text-align:left;color:#d5061e;font-weight:bold;">
    {$MOD.LBL_M182_MISSING_FIELDS}:
    <br>
    <ul>
        {foreach from=$VAL.MISSING_FIELDS item=it}
        <li>{$it}</li>
        {/foreach}
    </ul>
    {/if}
</p>
{else}

<br>
{*INSTRUCTIONS*}
{if $ERR.ERROR_TYPE== 0}
<p style="text-align:left;color:#000000;" class="wizard_info letter13">{$MOD.LBL_M182_INSTRUCT}</p> {/if}
{if $ERR.ERROR_TYPE== 1} <p style="text-align:left;color:#d5061e;" class="wizard_info letter13">{$MOD.LBL_M182_INSTRUCT}</p> {/if}

<br>
<form name="stic_Payments" method="POST">
    {*ISSUING ORGANIZATION SELECTED*}
    {if $ISSUING_ORGANIZATION_KEY ne ''}
    <input type="hidden" id="issuing_organization_key" name="issuing_organization_key" value="{$ISSUING_ORGANIZATION_KEY}">
    {/if}
    <input type="hidden" id="module" name="module" value="stic_Payments">
    <input type="hidden" id="action" name="action" value="createModel182">

    <table border="0" cellspacing="5">
        <br>
        {*TYPE OF PAYMENTS*}
        <select required id="payment_type" name="payment_type[]" multiple="multiple">
            {html_options values=$LAB.PAYMENT_TYPE_VALUES output=$INT.PAYMENT_TYPE_OUTPUT}
        </select>
    </table>

    <table width="100%">
        {*BUTTONS*}
        <tr>
            <br><br>
            <td align="left" style="padding-bottom: 2px;">
                <input title="{$MOD.LBL_M182_BACK}" class="button" type="button" id="reset_wizard" onclick="location.reload();" value="{$MOD.LBL_M182_BACK}">
                <input id="send_wizard" title="{$MOD.LBL_M182_NEXT}" class="button" type="submit" value="{$MOD.LBL_M182_NEXT}">
            </td>
        </tr>
    </table>

</form>

<script type="text/javascript">
                {literal}
    $('#send_wizard').on('click', function() {
        if ($('#payment_type option:selected').length == 0) {
            $('.wizard_info').css('color', 'red').fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
            return false;
        }
    });

    // Clear issuing organization selection and go back to selector before reloading
    $('#reset_wizard').on('click', function() {
        // If the issuing organization select exists on the page, clear it
        if ($('#issuing_organization_selected').length) {
            $('#issuing_organization_selected').val('');
            $('#issuing_organization_selected').prop('selectedIndex', -1);
            $('#issuing_organization_selected').trigger('change');
        }
        // Clear the hidden key on this form if present
        if ($('#issuing_organization_key').length) {
            $('#issuing_organization_key').val('');
        }
        // Redirect to the issuing organization selection action so server-side state is cleared
        window.location.href = 'index.php?module=stic_Payments&action=m182SelectIssuingOrganization';
    });

                {/literal}
</script>
{/if}