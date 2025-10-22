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
 <div class="moduleTitle">
 <h2 class="module-title-text" style="font-size: 18px;">{$MOD['LBL_STEP_2']}</h2>
</div>
<br>


<div class="listViewBody">
    <form method="POST" action="index.php?module=stic_Transactions&action=previewNorma43">

        <div style="margin-bottom: 30px;">
            <h3 style="background: #f8f9fa; padding: 12px; border-left: 4px solid #007bff; margin-bottom: 15px;">
                {$APP_LIST['moduleListSingular']['stic_Financial_Products']}
            </h3>
            
            <table class="detail view mapping-table" width="100%">
                <thead>
                    <tr>
                        <th width="25%">{$MOD['LBL_FIELD_1_STEP_2']}</th>
                        <th width="35%">{$MOD['LBL_FIELD_2_STEP_2']}</th>
                        <th width="40%">{$MOD['LBL_FIELD_3_STEP_2']}: {$PRODUCT_STRINGS['LBL_MODULE_NAME']}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ACCOUNT_DATA key=normaField item=data}
                    <tr>
                        <td><strong>{$data.label}</strong></td>
                        <td><span class="example-value">{$data.value}</span></td>
                        <td>
                            <select name="product_mapping[{$normaField}]" class="mapping-select">
                                <option value="">{$MOD['LBL_NOT_IMPORT_FIELD']}</option>
                                {foreach from=$PRODUCT_FIELDS key=fieldName item=fieldLabel}
                                    <option value="{$fieldName}" 
                                        {if $SUGGESTED_PRODUCT_MAPPING[$normaField] == $fieldName}selected{/if}>
                                        {$fieldLabel}
                                    </option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>

        <div style="margin-bottom: 30px;">
            <h3 style="background: #f8f9fa; padding: 12px; border-left: 4px solid #28a745; margin-bottom: 15px;">
            {$MOD['LBL_MODULE_NAME']}
            </h3>
            
            <table class="detail view mapping-table" width="100%">
                <thead>
                    <tr>
                        <th width="25%">{$MOD['LBL_FIELD_1_STEP_2']}</th>
                        <th width="35%">{$MOD['LBL_FIELD_2_STEP_2']}</th>
                        <th width="40%">{$MOD['LBL_FIELD_3_STEP_2']}: {$MOD['LBL_MODULE_NAME']}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$MOVEMENT_DATA key=normaField item=data}
                    <tr>
                        <td><strong>{$data.label}</strong></td>
                        <td><span class="example-value">{$data.value}</span></td>
                        <td>
                            <select name="transaction_mapping[{$normaField}]" class="mapping-select">
                                <option value="">{$MOD['LBL_NOT_IMPORT_FIELD']}</option>
                                {foreach from=$TRANSACTION_FIELDS key=fieldName item=fieldLabel}
                                    <option value="{$fieldName}" 
                                        {if $SUGGESTED_TRANSACTION_MAPPING[$normaField] == $fieldName}selected{/if}>
                                        {$fieldLabel}
                                    </option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>

        <div style="text-align: left; padding: 15px 0; border-top: 2px solid #ddd; margin-top: 20px;">
            <input type="button" class="button" value="{$APP['LBL_BACK']}" 
                onclick="if(confirm('{$MOD['LBL_BACK_TO_STEP_1']}')) location.href='index.php?module=stic_Transactions&action=uploadNorma43';" />
            <input type="submit" class="button primary" value="{$APP['LNK_RESUME']}" />
            <input type="button" class="button" value="{$APP['LBL_CANCEL']}" 
                onclick="if(confirm('{$MOD['LBL_CANCEL_IMPORT_NORMA_43']}')) location.href='index.php?module=stic_Transactions&action=index';" />
        </div>
    </form>
</div>

<style>
{literal}
    .mapping-table {
        border-collapse: collapse;
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .mapping-table thead th {
        background: #343a40;
        color: white;
        padding: 12px;
        text-align: left;
        font-weight: 600;
        border: 1px solid #dee2e6;
    }

    .mapping-table tbody td {
        padding: 12px;
        border: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .mapping-table tbody tr:nth-child(even) {
        background: #f8f9fa;
    }

    .mapping-table tbody tr:hover {
        background: #e8f4fd;
    }

    .mapping-select {
        width: 100%;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
        background-color: white;
    }

    .mapping-select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    .example-value {
        font-family: 'Courier New', monospace;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 3px;
        display: inline-block;
        color: #495057;
    }

    .button {
        padding: 10px 20px;
        margin-right: 10px;
        border: 1px solid #ccc;
        background: #f8f9fa;
        cursor: pointer;
        border-radius: 4px;
        font-size: 14px;
    }

    .button.primary {
        margin: 0 4px 4px 0;
    }

    h3 {
        font-size: 14px;
        font-weight: bold;
    }
{/literal}
</style>