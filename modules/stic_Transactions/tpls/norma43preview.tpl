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
    <h2 class="module-title-text" style="font-size: 18px; margin-bottom: 0px;">{$MOD['LBL_STEP_3']}</h2>
</div>
<br>

<div  class="import_instruction">
    <br>
    {$MOD['LBL_INSTRUCTION_1_STEP_3']}
    <br>
</div>

<div class="listViewBody">
    <form name="executeFinalImport" id="executeFinalImport" method="POST"
        action="index.php?module=stic_Transactions&action=executeFinalImport">

        <div style="margin-bottom: 25px;">
            <h3>{$MOD['LBL_SUMMARY_STEP_3']}</h3>
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 20px;">
                <div class="stat-card">
                    <div class="stat-number">{$DATA.total_movements|default:0}</div>
                    <div class="stat-label">{$MOD['LBL_TOTAL_TRANSACTIONS']}</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-number">{$DATA.new_movements|@count|default:0}</div>
                    <div class="stat-label">{$MOD['LBL_TOTAL_NEW_TRANSACTIONS']}</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-number">{$DATA.skipped_duplicates|default:0}</div>
                    <div class="stat-label">{$MOD['LBL_TOTAL_DUPLICATES']}</div>
                </div>
            </div>
        </div>

        <div
            style="background: #fff0f3; padding: 20px; margin-bottom: 20px; border-radius: 5px; border-left: 4px solid #ff5278;">
            <h3>{$APP_LIST['moduleListSingular']['stic_Financial_Products']}</h3>
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 20px;">
                <p style="margin-bottom: 0; font-size: 1.1em;">
                    {$PRODUCT_STRINGS['LBL_IBAN']}: {$DATA.iban}
                </p>
                <p style="margin-bottom: 0; font-size: 1.1em;">
                    {$PRODUCT_STRINGS['LBL_NAME']}: {$DATA.financial_product}
                </p>
                <p style="margin-bottom: 0; font-size: 1.1em;">
                    {$PRODUCT_STRINGS['LBL_ENTITY']}: {$DATA.entity}
                </p>
                <p style="margin-bottom: 0; font-size: 1.1em;">
                    {$PRODUCT_STRINGS['LBL_INITIAL_BALANCE']}: {$DATA.initial_balance}
                </p>
            </div>
        </div>

        {if $DATA.new_movements && $DATA.new_movements|@count > 0}
            <div style="margin-bottom: 20px;">
                <h3 style="background: #e8ffee; padding: 12px; border-left: 4px solid #28a745; margin-bottom: 15px;">{$MOD['LBL_TOTAL_NEW_TRANSACTIONS']}</h3>
                <table class="list view" width="100%">
                    <thead>
                        <tr>
                            <th width="15%">{$MOD['LBL_TRANSACTION_DATE']}</th>
                            <th width="50%">{$MOD['LBL_NAME']}</th>
                            <th width="15%">{$MOD['LBL_PAYMENT_METHOD']}</th>
                            <th width="20%">{$MOD['LBL_AMOUNT']}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$DATA.new_movements item=mov}
                            <tr>
                                <td>{$mov.transaction_date_formatted}</td>
                                <td>{$mov.name|truncate:80}</td>
                                <td>{$mov.payment_method}</td>
                                <td style="text-align: right;">
                                    <span style="color: {if $mov.amount >= 0}#28a745{else}#dc3545{/if}; font-weight: bold;">
                                        {$mov.amount_formatted}
                                    </span>
                                </td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        {/if}

        {if $DATA.duplicates && $DATA.duplicates|@count > 0}
            <div style="margin-bottom: 20px;">
                <h3 style="background: #fff6d7; padding: 12px; border-left: 4px solid #ffc107; margin-bottom: 15px;">{$MOD['LBL_TOTAL_DUPLICATES']}</h3>
                <p style="color: #6c757d;">{$MOD['LBL_DUPLICATE_NORMA_43']}</p>
                <table class="list view duplicate" width="100%">
                    <thead>
                        <tr>
                            <th width="15%">{$MOD['LBL_TRANSACTION_DATE']}</th>
                            <th width="50%">{$MOD['LBL_NAME']}</th>
                            <th width="15%">{$MOD['LBL_PAYMENT_METHOD']}</th>
                            <th width="20%">{$MOD['LBL_AMOUNT']}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$DATA.duplicates item=dup}
                            <tr>
                                <td>{$dup.transaction_date_formatted}</td>
                                <td>{$dup.name|truncate:80}</td>
                                <td>{$dup.payment_method}</td>
                                <td style="text-align: right;">
                                    <span style="color: {if $dup.amount >= 0}#28a745{else}#dc3545{/if};">
                                        {$dup.amount_formatted}
                                    </span>
                                </td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        {/if}

        <div style="text-align: left; padding: 15px 0; border-top: 2px solid #ddd; margin-top: 30px;">
            <input type="button" class="button" value="{$APP['LBL_BACK']}" 
                onclick="history.back();" />
            <input type="submit" class="button primary" value="{$MOD['LBL_COMPLETE_IMPORT_NORMA_43']}"/>
            <input type="button" class="button" value="{$APP['LBL_CANCEL']}"
                onclick="location.href='index.php?module=stic_Transactions&action=index';" />
        </div>
    </form>
</div>

<style>
    {literal}
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-left: 4px solid #007bff;
        }

        .stat-card.success {
            border-left-color: #28a745;
        }

        .stat-card.warning {
            border-left-color: #ffc107;
        }

        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }

        .success .stat-number {
            color: #28a745;
        }

        .warning .stat-number {
            color: #ffc107;
        }

        .stat-label {
            font-size: 0.9em;
            color: #666;
        }

        .list.view th {
            background: #343a40;
            color: white;
            border: 1px solid #dee2e6;
        }

        .list.view tbody tr {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .list.view tbody tr:nth-child(even) {
            background: #ffffff;
        }

        .list.view.duplicate tbody tr {
            background: #fff6d7 !important;
            /* border: 1px solid #dee2e6; */
        }

        .list.view tbody td {
            border: 1px solid #dee2e6;
        }

        .list.view tbody tr:hover {
            background: #e8f4fd;
            border: 1px solid #dee2e6;
        }

        .list.view.duplicate tbody tr:hover {
            background: #f8f9fa !important;
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

    {/literal}
</style>
