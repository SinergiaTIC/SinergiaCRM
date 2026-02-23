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
    <h2 class="module-title-text" style="font-size: 18px; margin-bottom: 0px;">{$MOD['LBL_STEP_2']}</h2>
</div>
<br>

<div class="import_instruction">
    <br>
    {$MOD['LBL_INSTRUCTION_1_STEP_2']}
    <br>
</div>

<div class="listViewBody">
    <form name="executeFinalImport" id="executeFinalImport" method="POST"
        action="index.php?module=stic_Transactions&action=executeFinalImport">
        <input type="hidden" id="allow_file_duplicates" name="allow_file_duplicates" value="0"/>

        <div class="form-actions">
            <input type="button" class="button" value="{$APP['LBL_BACK']}"
                onclick="if(confirm('{$MOD['LBL_BACK_TO_STEP_1']}')) location.href='index.php?module=stic_Transactions&action=uploadNorma43';" />
            <input type="submit" class="button primary" value="{$MOD['LBL_COMPLETE_IMPORT_NORMA_43']}" />
            {if $DATA.total_skipped_duplicates > 0}
                <input type="button" class="button" value="{$MOD['LBL_IMPORT_INCLUDING_FILE_DUPLICATES']}" 
                    onclick="document.getElementById('allow_file_duplicates').value='1'; document.getElementById('executeFinalImport').submit();" />
            {/if}
            <input type="button" class="button" value="{$APP['LBL_CANCEL']}"
                onclick="location.href='index.php?module=stic_Transactions&action=index';" />
        </div>

        {* GLOBAL SUMMARY STATISTICS *}
        <div class="summary-section">
            <h3>{$MOD['LBL_SUMMARY_STEP_2']}</h3>
            <div class="summary-grid">
                {* SHOW TOTAL ACCOUNTS ONLY IF MULTIPLE ACCOUNTS *}
                {if $DATA.accounts|@count > 1}
                    <div class="stat-card">
                        <div class="stat-number">{$DATA.total_accounts|default:0}</div>
                        <div class="stat-label">{$MOD['LBL_TOTAL_PRODUCTS']}</div>
                    </div>
                {/if}
                <div class="stat-card">
                    <div class="stat-number">{$DATA.total_movements|default:0}</div>
                    <div class="stat-label">{$MOD['LBL_TOTAL_TRANSACTIONS']}</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-number">{$DATA.total_imported_movements|default:0}</div>
                    <div class="stat-label">{$MOD['LBL_TOTAL_NEW_TRANSACTIONS']}</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-number">{$DATA.total_skipped_duplicates|default:0}</div>
                    <div class="stat-label">{$MOD['LBL_TOTAL_DUPLICATES']}</div>
                </div>
            </div>
        </div>

        {* PER-ACCOUNT DETAILS *}
        {if $DATA.accounts && $DATA.accounts|@count > 0}
            {foreach from=$DATA.accounts item=account name=account_loop}
                {assign var="account_index" value=$smarty.foreach.account_loop.iteration}
                <div class="account-container">
                    <div class="account-header">
                        {* SHOW ACCOUNT TITLE WITH PRODUCT TYPE *}
                        <h3>
                            {if !$account.existing_product}
                                {$MOD['LBL_NEW_PRODUCT']}
                            {/if}
                            {$APP_LIST['moduleListSingular']['stic_Financial_Products']}
                            {if $account.existing_product}
                                {$MOD['LBL_EXISTING_PRODUCT']}
                            {/if}
                            {* SHOW ACCOUNT NUMBERING ONLY IF MULTIPLE ACCOUNTS *}
                            {if $DATA.accounts|@count > 1}
                                - {$account_index}/{$DATA.accounts|@count}
                            {/if}
                        </h3>
                        <div class="account-info">
                            <p>
                                <strong>{$PRODUCT_STRINGS['LBL_IBAN']}:</strong> {$account.iban|default:''}
                            </p>
                            <p>
                                <strong>{$PRODUCT_STRINGS['LBL_NAME']}:</strong> {$account.financial_product|default:''}
                            </p>
                            <p>
                                <strong>{$PRODUCT_STRINGS['LBL_ENTITY']}:</strong> {$account.entity|default:''}
                            </p>
                            <p>
                                <strong>{$PRODUCT_STRINGS['LBL_INITIAL_BALANCE']}:</strong> {$account.initial_balance|default:0} €
                            </p>
                            <p>
                                <strong>{$PRODUCT_STRINGS['LBL_CURRENT_BALANCE']}:</strong> {$account.final_balance|default:0} €
                            </p>
                        </div>
                        {* SHOW PER-ACCOUNT STATS ONLY IF MULTIPLE ACCOUNTS *}
                        {if $DATA.accounts|@count > 1}
                            <div class="account-stats">
                                <div class="stat-card" style="border-left-color: #007bff;">
                                    <div class="stat-number" style="color: #007bff;">{$account.total_movements|default:0}</div>
                                    <div class="stat-label">{$MOD['LBL_TOTAL_TRANSACTIONS']}</div>
                                </div>
                                <div class="stat-card success">
                                    <div class="stat-number">{$account.imported_movements|default:0}</div>
                                    <div class="stat-label">{$MOD['LBL_TOTAL_NEW_TRANSACTIONS']}</div>
                                </div>
                                <div class="stat-card warning">
                                    <div class="stat-number">{$account.skipped_duplicates|default:0}</div>
                                    <div class="stat-label">{$MOD['LBL_TOTAL_DUPLICATES']}</div>
                                </div>
                            </div>
                        {/if}
                    </div>

                    {* NEW MOVEMENTS FOR THIS ACCOUNT *}
                    {if $account.new_movements && $account.new_movements|@count > 0}
                        <div class="data-table-section">
                            <h3 class="section-header success table-section-title">{$MOD['LBL_TOTAL_NEW_TRANSACTIONS']}</h3>
                            <div class="list-view-rounded-corners">
                                <table class="list view table-responsive" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="15%">{$MOD['LBL_TRANSACTION_DATE']}</th>
                                            <th width="50%">{$MOD['LBL_NAME']}</th>
                                            <th width="15%">{$MOD['LBL_PAYMENT_METHOD']}</th>
                                            <th width="20%">{$MOD['LBL_AMOUNT']}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="new_movements_body_account_{$account_index}">
                                        {foreach from=$account.new_movements item=mov name="new_mov_loop"}
                                            {assign var="mov_row_num" value=$smarty.foreach.new_mov_loop.iteration}
                                            <tr class="new-movement-row" data-account="{$account_index}" data-row="{$mov_row_num}" style="display: {if $mov_row_num <= $RECORDS_PER_PAGE}table-row{else}none{/if};">
                                                <td>{$mov.transaction_date_formatted}</td>
                                                <td>{$mov.name|truncate:80}</td>
                                                <td>{$mov.payment_method}</td>
                                                <td class="amount-cell">
                                                    <span class="{if $mov.amount >= 0}amount-positive{else}amount-negative{/if}">
                                                        {$mov.amount_formatted}
                                                    </span>
                                                </td>
                                            </tr>
                                        {/foreach}
                                    </tbody>
                                    {* PAGINATION FOR NEW MOVEMENTS *}
                                    {if $account.new_movements|@count > $RECORDS_PER_PAGE}
                                        <tr id='pagination' class="pagination-unique pagination-bottom" role='presentation'>
                                            <td colspan='4'>
                                                <table border='0' cellpadding='0' cellspacing='0' width='100%' class='paginationTable'>
                                                    <tr>
                                                        <td nowrap="nowrap" class='paginationActionButtons'>
                                                            &nbsp;
                                                        </td>
                                                        <td nowrap='nowrap' align="right" class='paginationChangeButtons' width="1%">
                                                            <button type='button' id='new_first_account_{$account_index}' name='new_first_account' title='{$APP['LNK_LIST_START']}' class='list-view-pagination-button' onclick="paginate_{$account_index}_new('first')" style="display: none;">
                                                                <span class='suitepicon suitepicon-action-first'></span>
                                                            </button>
                                                            <button type='button' id='new_prev_account_{$account_index}' name='new_prev_account' title='{$APP['LNK_LIST_PREVIOUS']}' class='list-view-pagination-button' onclick="paginate_{$account_index}_new(-1)" style="display: none;">
                                                                <span class='suitepicon suitepicon-action-left'></span>
                                                            </button>
                                                        </td>
                                                        <td nowrap='nowrap' width="1%" class="paginationActionButtons">
                                                            <div class='pageNumbers' id="new_page_info_account_{$account_index}">1 - {$RECORDS_PER_PAGE} {$APP['LBL_LIST_OF']} {$account.new_movements|@count}</div>
                                                        </td>
                                                        <td nowrap='nowrap' align="right" class='paginationChangeButtons' width="1%">
                                                            <button type='button' id='new_next_account_{$account_index}' name='new_next_account' title='{$APP['LNK_LIST_NEXT']}' class='list-view-pagination-button' onclick="paginate_{$account_index}_new(1)">
                                                                <span class='suitepicon suitepicon-action-right'></span>
                                                            </button>
                                                            <button type='button' id='new_last_account_{$account_index}' name='new_last_account' title='{$APP['LNK_LIST_END']}' class='list-view-pagination-button' onclick="paginate_{$account_index}_new('last')">
                                                                <span class='suitepicon suitepicon-action-last'></span>
                                                            </button>
                                                        </td>
                                                        <td nowrap='nowrap' width="4px" class="paginationActionButtons"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    {/if}
                                </table>
                            </div>
                        </div>
                    {/if}

                    {* DUPLICATES FOR THIS ACCOUNT *}
                    {if $account.duplicates && $account.duplicates|@count > 0}
                        <div class="data-table-section">
                            <h3 class="section-header warning table-section-title">{$MOD['LBL_TOTAL_DUPLICATES']}</h3>
                            <p style="color: #6c757d;">{$MOD['LBL_DUPLICATE_NORMA_43']}</p>
                            <div class="list-view-rounded-corners">
                                <table class="list view duplicate table-responsive" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="13%">{$MOD['LBL_TRANSACTION_DATE']}</th>
                                            <th width="42%">{$MOD['LBL_NAME']}</th>
                                            <th width="13%">{$MOD['LBL_PAYMENT_METHOD']}</th>
                                            <th width="17%">{$MOD['LBL_AMOUNT']}</th>
                                            <th width="15%">{$MOD['LBL_DUPLICATE_LOCATION']}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="duplicates_body_account_{$account_index}">
                                        {foreach from=$account.duplicates item=dup name="dup_loop"}
                                            {assign var="dup_row_num" value=$smarty.foreach.dup_loop.iteration}
                                            <tr class="duplicate-row" data-account="{$account_index}" data-row="{$dup_row_num}" style="display: {if $dup_row_num <= $RECORDS_PER_PAGE}table-row{else}none{/if};">
                                                <td>{$dup.transaction_date_formatted}</td>
                                                <td>{$dup.name|truncate:80}</td>
                                                <td>{$dup.payment_method}</td>
                                                <td class="amount-cell">
                                                    <span class="{if $dup.amount >= 0}amount-positive{else}amount-negative{/if}">
                                                        {$dup.amount_formatted}
                                                    </span>
                                                </td>
                                                <td class="duplicate-location-cell">
                                                    {if $dup.duplicate_type == 'file'}
                                                        <span class="duplicate-badge duplicate-file">{$MOD['LBL_DUPLICATE_LOCATION_FILE']}</span>
                                                    {else}
                                                        <span class="duplicate-badge duplicate-database">{$MOD['LBL_DUPLICATE_LOCATION_DATABASE']}</span>
                                                    {/if}
                                                </td>
                                            </tr>
                                        {/foreach}
                                    </tbody>
                                    {* PAGINATION FOR DUPLICATES *}
                                    {if $account.duplicates|@count > $RECORDS_PER_PAGE}
                                        <tr id='pagination' class="pagination-unique pagination-bottom" role='presentation'>
                                            <td colspan='5'>
                                                <table border='0' cellpadding='0' cellspacing='0' width='100%' class='paginationTable'>
                                                    <tr>
                                                        <td nowrap="nowrap" class='paginationActionButtons'>
                                                            &nbsp;
                                                        </td>
                                                        <td nowrap='nowrap' align="right" class='paginationChangeButtons' width="1%">
                                                            <button type='button' id='dup_first_account_{$account_index}' name='dup_first_account' title='{$APP['LNK_LIST_START']}' class='list-view-pagination-button' onclick="paginate_{$account_index}_dup('first')" style="display: none;">
                                                                <span class='suitepicon suitepicon-action-first'></span>
                                                            </button>
                                                            <button type='button' id='dup_prev_account_{$account_index}' name='dup_prev_account' title='{$APP['LNK_LIST_PREVIOUS']}' class='list-view-pagination-button' onclick="paginate_{$account_index}_dup(-1)" style="display: none;">
                                                                <span class='suitepicon suitepicon-action-left'></span>
                                                            </button>
                                                        </td>
                                                        <td nowrap='nowrap' width="1%" class="paginationActionButtons">
                                                            <div class='pageNumbers' id="dup_page_info_account_{$account_index}">1 - {$RECORDS_PER_PAGE} {$APP['LBL_LIST_OF']} {$account.duplicates|@count}</div>
                                                        </td>
                                                        <td nowrap='nowrap' align="right" class='paginationChangeButtons' width="1%">
                                                            <button type='button' id='dup_next_account_{$account_index}' name='dup_next_account' title='{$APP['LNK_LIST_NEXT']}' class='list-view-pagination-button' onclick="paginate_{$account_index}_dup(1)">
                                                                <span class='suitepicon suitepicon-action-right'></span>
                                                            </button>
                                                            <button type='button' id='dup_last_account_{$account_index}' name='dup_last_account' title='{$APP['LNK_LIST_END']}' class='list-view-pagination-button' onclick="paginate_{$account_index}_dup('last')">
                                                                <span class='suitepicon suitepicon-action-last'></span>
                                                            </button>
                                                        </td>
                                                        <td nowrap='nowrap' width="4px" class="paginationActionButtons"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    {/if}
                                </table>
                            </div>
                        </div>
                    {/if}
            {/foreach}
        {/if}

        <div class="form-actions">
            <input type="button" class="button" value="{$APP['LBL_BACK']}"
                onclick="if(confirm('{$MOD['LBL_BACK_TO_STEP_1']}')) location.href='index.php?module=stic_Transactions&action=uploadNorma43';" />
            <input type="submit" class="button primary" value="{$MOD['LBL_COMPLETE_IMPORT_NORMA_43']}" />
            {if $DATA.total_skipped_duplicates > 0}
                <input type="button" class="button" value="{$MOD['LBL_IMPORT_INCLUDING_FILE_DUPLICATES']}" 
                    onclick="document.getElementById('allow_file_duplicates').value='1'; document.getElementById('executeFinalImport').submit();" />
            {/if}
            <input type="button" class="button" value="{$APP['LBL_CANCEL']}"
                onclick="location.href='index.php?module=stic_Transactions&action=index';" />
        </div>
    </form>
</div>

<style>
    {literal}
        /* Page Header */
        .page-header {
            font-size: 18px;
            margin-bottom: 0;
        }

        /* Form Actions */
        .form-actions {
            text-align: left;
            padding: 15px 0;
            border-top: 2px solid #ddd;
            margin-top: 30px;
        }

        /* Section Headers */
        .section-header {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .section-header.success {
            background: #e8ffee;
            border-left: 4px solid #28a745;
        }

        .section-header.warning {
            background: #fff6d7;
            border-left: 4px solid #ffc107;
        }

        /* Account Container */
        .account-container {
            margin-bottom: 30px;
            padding-top: 20px;
            border-top: 2px solid #dee2e6;
        }

        .account-header {
            background: #fff0f3;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            border-left: 4px solid #ff5278;
        }

        /* Account Info */
        .account-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .account-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        /* Summary Section */
        .summary-section {
            margin-bottom: 25px;
        }

        .summary-section h3 {
            margin-bottom: 15px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        /* Data Table Section */
        .data-table-section {
            margin-bottom: 20px;
        }

        .table-section-title {
            margin-bottom: 15px;
        }

        .amount-positive {
            color: #28a745;
        }

        .amount-negative {
            color: #dc3545;
        }

        .amount-cell {
            text-align: right;
            font-weight: bold;
        }

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

        .list-view-rounded-corners {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            overflow: hidden;
        }

        .list.view {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
        }

        .list.view th {
            background: #343a40;
            color: white;
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
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
        }

        .list.view tbody td {
            border: 1px solid #dee2e6;
            padding: 10px 12px;
        }

        .list.view tbody tr:hover {
            background: #e8f4fd;
            border: 1px solid #dee2e6;
        }

        .list.view.duplicate tbody tr:hover {
            background: #f8f9fa !important;
        }

        .pagination-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 4px 4px;
        }

        .page-info {
            font-size: 13px;
            color: #666;
        }

        .pagination-btn {
            padding: 5px 10px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 3px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
        }

        .pagination-btn:hover:not(:disabled) {
            background: #e8f4fd;
            border-color: #007bff;
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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

        /* Pagination styles*/
        tr#pagination {
            background: transparent;
            border: none;
        }

        tr#pagination td {
            border: none;
            padding: 0;
            background: transparent;
        }

        .paginationTable {
            width: 100%;
            border: none;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .paginationTable tr {
            background: #f8f9fa;
            border: none;
        }

        .paginationTable .paginationActionButtons {
            padding: 5px 10px;
            border: none;
            background: #f8f9fa;
            vertical-align: middle;
            font-size: 12px;
        }

        .paginationTable .paginationChangeButtons {
            padding: 5px 10px;
            text-align: right;
            white-space: nowrap;
        }

        .paginationTable .pageNumbers {
            font-size: 13px;
            color: #333;
            font-weight: bold;
            padding: 5px 0;
        }

        .list-view-pagination-button {
            padding: 0;
            border: 1px solid #ccc;
            background: white;
            cursor: pointer;
            border-radius: 3px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            margin-left: 2px;
            transition: all 0.2s ease;
            vertical-align: middle;
        }

        .list-view-pagination-button:hover:not(:disabled) {
            background: #e8f4fd;
            border-color: #007bff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .list-view-pagination-button:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            border-color: #ddd;
            background: #f8f9fa;
        }

        .list-view-pagination-button span {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #333;
        }

        .list-view-pagination-button:disabled span {
            color: #999;
        }

        /* Duplicate location badges */
        .duplicate-location-cell {
            text-align: center;
        }

        .duplicate-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .duplicate-file {
            background-color: #e3f2fd;
            color: #1976d2;
            border: 1px solid #90caf9;
        }

        .duplicate-database {
            background-color: #fff3e0;
            color: #f57c00;
            border: 1px solid #ffb74d;
        }

    {/literal}
</style>

<script>
    {literal}
    // Pagination data storage for each account
    var paginationData = {};
    var recordsPerPage = {/literal}{$RECORDS_PER_PAGE}{literal};
    var paginationTexts = {
        'of': '{/literal}{$APP['LBL_LIST_OF']}{literal}'
    };

    // Initialize pagination for all accounts
    function initializePagination(accountIndex, newMovementsCount, duplicatesCount) {
        if (!paginationData[accountIndex]) {
            paginationData[accountIndex] = {};
        }
        
        // New movements pagination
        if (newMovementsCount > recordsPerPage) {
            paginationData[accountIndex].newPage = 1;
            paginationData[accountIndex].newTotal = newMovementsCount;
            paginationData[accountIndex].newPages = Math.ceil(newMovementsCount / recordsPerPage);
            updateNewMovementsPagination(accountIndex);
        }
        
        // Duplicates pagination
        if (duplicatesCount > recordsPerPage) {
            paginationData[accountIndex].dupPage = 1;
            paginationData[accountIndex].dupTotal = duplicatesCount;
            paginationData[accountIndex].dupPages = Math.ceil(duplicatesCount / recordsPerPage);
            updateDuplicatesPagination(accountIndex);
        }
    }

    // Paginate new movements
    function paginate_new_movements(accountIndex, action) {
        var data = paginationData[accountIndex];
        if (!data || !data.newPages) return;

        if (action === 'first') {
            data.newPage = 1;
        } else if (action === 1) {
            data.newPage = Math.min(data.newPage + 1, data.newPages);
        } else if (action === -1) {
            data.newPage = Math.max(data.newPage - 1, 1);
        } else if (action === 'last') {
            data.newPage = data.newPages;
        }

        updateNewMovementsPagination(accountIndex);
    }

    // Paginate duplicates
    function paginate_dup(accountIndex, action) {
        var data = paginationData[accountIndex];
        if (!data || !data.dupPages) return;

        if (action === 'first') {
            data.dupPage = 1;
        } else if (action === 1) {
            data.dupPage = Math.min(data.dupPage + 1, data.dupPages);
        } else if (action === -1) {
            data.dupPage = Math.max(data.dupPage - 1, 1);
        } else if (action === 'last') {
            data.dupPage = data.dupPages;
        }

        updateDuplicatesPagination(accountIndex);
    }

    // Update new movements table display
    function updateNewMovementsPagination(accountIndex) {
        var data = paginationData[accountIndex];
        if (!data || !data.newPages) return;

        var rows = document.querySelectorAll('tr.new-movement-row[data-account="' + accountIndex + '"]');
        var startRow = (data.newPage - 1) * recordsPerPage;
        var endRow = startRow + recordsPerPage;

        rows.forEach(function(row, index) {
            row.style.display = (index >= startRow && index < endRow) ? 'table-row' : 'none';
        });

        // Update page info
        var pageInfoEl = document.getElementById('new_page_info_account_' + accountIndex);
        if (pageInfoEl) {
            var displayStart = startRow + 1;
            var displayEnd = Math.min(endRow, data.newTotal);
            pageInfoEl.textContent = '(' + displayStart + ' - ' + displayEnd + ' ' + paginationTexts.of + ' ' + data.newTotal + ')';
        }

        // Update button states
        updateNewMovementButtons(accountIndex);
    }

    // Update duplicates table display
    function updateDuplicatesPagination(accountIndex) {
        var data = paginationData[accountIndex];
        if (!data || !data.dupPages) return;

        var rows = document.querySelectorAll('tr.duplicate-row[data-account="' + accountIndex + '"]');
        var startRow = (data.dupPage - 1) * recordsPerPage;
        var endRow = startRow + recordsPerPage;

        rows.forEach(function(row, index) {
            row.style.display = (index >= startRow && index < endRow) ? 'table-row' : 'none';
        });

        // Update page info
        var pageInfoEl = document.getElementById('dup_page_info_account_' + accountIndex);
        if (pageInfoEl) {
            var displayStart = startRow + 1;
            var displayEnd = Math.min(endRow, data.dupTotal);
            pageInfoEl.textContent = '(' + displayStart + ' - ' + displayEnd + ' ' + paginationTexts.of + ' ' + data.dupTotal + ')';
        }

        // Update button states
        updateDuplicatesButtons(accountIndex);
    }

    // Update button states for new movements
    function updateNewMovementButtons(accountIndex) {
        var data = paginationData[accountIndex];
        var firstBtn = document.getElementById('new_first_account_' + accountIndex);
        var prevBtn = document.getElementById('new_prev_account_' + accountIndex);
        var nextBtn = document.getElementById('new_next_account_' + accountIndex);
        var lastBtn = document.getElementById('new_last_account_' + accountIndex);

        if (firstBtn) firstBtn.style.display = data.newPage > 1 ? 'inline-flex' : 'none';
        if (prevBtn) prevBtn.style.display = data.newPage > 1 ? 'inline-flex' : 'none';
        if (nextBtn) nextBtn.style.display = data.newPage < data.newPages ? 'inline-flex' : 'none';
        if (lastBtn) lastBtn.style.display = data.newPage < data.newPages ? 'inline-flex' : 'none';
    }

    // Update button states for duplicates
    function updateDuplicatesButtons(accountIndex) {
        var data = paginationData[accountIndex];
        var firstBtn = document.getElementById('dup_first_account_' + accountIndex);
        var prevBtn = document.getElementById('dup_prev_account_' + accountIndex);
        var nextBtn = document.getElementById('dup_next_account_' + accountIndex);
        var lastBtn = document.getElementById('dup_last_account_' + accountIndex);

        if (firstBtn) firstBtn.style.display = data.dupPage > 1 ? 'inline-flex' : 'none';
        if (prevBtn) prevBtn.style.display = data.dupPage > 1 ? 'inline-flex' : 'none';
        if (nextBtn) nextBtn.style.display = data.dupPage < data.dupPages ? 'inline-flex' : 'none';
        if (lastBtn) lastBtn.style.display = data.dupPage < data.dupPages ? 'inline-flex' : 'none';
    }

    // Create dynamic pagination functions for each account
    function createPaginationFunctions(accountIndex, newMovementsCount, duplicatesCount) {
        window['paginate_' + accountIndex + '_new'] = function(action) {
            paginate_new_movements(accountIndex, action);
        };

        window['paginate_' + accountIndex + '_dup'] = function(action) {
            paginate_dup(accountIndex, action);
        };

        initializePagination(accountIndex, newMovementsCount, duplicatesCount);
    }

    // Initialize on document ready
    document.addEventListener('DOMContentLoaded', function() {
        {/literal}
        {if $DATA.accounts && $DATA.accounts|@count > 0}
            {foreach from=$DATA.accounts item=account name=account_loop}
                {assign var="account_index" value=$smarty.foreach.account_loop.iteration}
                createPaginationFunctions({$account_index}, {$account.new_movements|@count}, {$account.duplicates|@count});
            {/foreach}
        {/if}
        {literal}
    });
    {/literal}
</script>
