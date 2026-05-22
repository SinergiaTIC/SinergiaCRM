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
{literal}
<style>
    .payment-selector-container {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        font-family: Arial, sans-serif;
    }
    .payment-selector-container h2 {
        color: #333;
        border-bottom: 2px solid #1c78c1;
        padding-bottom: 10px;
    }
    .payments-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    .payments-table th, .payments-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
    .payments-table th {
        background-color: #1c78c1;
        color: white;
    }
    .payments-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .payments-table tr:hover {
        background-color: #e8f4fd;
    }
    .payments-table input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    .form-group {
        margin: 15px 0;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .form-group input {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%;
        max-width: 300px;
    }
    .btn-container {
        margin-top: 20px;
        text-align: center;
    }
    .btn {
        padding: 10px 20px;
        margin: 0 5px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }
    .btn-primary {
        background-color: #1c78c1;
        color: white;
    }
    .btn-primary:hover {
        background-color: #155a96;
    }
    .btn-primary:disabled {
        background-color: #a0a0a0;
        cursor: not-allowed;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #545b62;
    }
    .info-text {
        background-color: #e8f4fd;
        padding: 10px;
        border-radius: 5px;
        margin: 10px 0;
        color: #333;
    }
</style>
{/literal}

<div class="payment-selector-container">
    <h2>{$MOD.LBL_SELECT_PAYMENTS_TO_INVOICE}</h2>

    <div class="info-text">
        {$MOD.LBL_SELECT_PAYMENTS_INFO}
    </div>

    <form method="POST" action="index.php?module=AOS_Invoices&action=selectPaymentsToInvoice" id="paymentSelectorForm">
        <input type="hidden" name="confirm_create" value="1">

        <h3>{$MOD.LBL_AVAILABLE_PAYMENTS}</h3>
        <table class="payments-table">
            <thead>
                <tr>
                    <th>{$MOD.LBL_SELECT}</th>
                    <th>{$MOD.LBL_NAME}</th>
                    <th>{$MOD.LBL_PAYER}</th>
                    <th>{$MOD.LBL_AMOUNT}</th>
                    <th>{$MOD.LBL_PAYMENT_DATE}</th>
                    <th>{$MOD.LBL_STATUS}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$PAYMENTS item=payment}
                <tr>
                    <td><input type="checkbox" name="payment_ids[]" value="{$payment.id}" class="payment-checkbox" data-payer="{$payment.payer_type}"></td>
                    <td>{$payment.name}</td>
                    <td>{$payment.payer_name}</td>
                    <td style="text-align: right;">{$payment.amount}</td>
                    <td>{$payment.payment_date}</td>
                    <td>{$payment.status}</td>
                </tr>
                {foreachelse}
                <tr>
                    <td colspan="6" style="text-align: center;">{$MOD.LBL_NO_PAYMENTS_AVAILABLE}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>

        <div class="form-group">
            <label for="invoice_name">{$MOD.LBL_INVOICE_NAME}:</label>
            <input type="text" id="invoice_name" name="invoice_name" value="{$MOD.LBL_INVOICE_DEFAULT_NAME}">
        </div>

        <div class="form-group">
            <label for="invoice_date">{$MOD.LBL_INVOICE_DATE}:</label>
            <input type="date" id="invoice_date" name="invoice_date" value="{$smarty.now|date_format:'%Y-%m-%d'}">
        </div>

        <div class="form-group">
            <label for="due_date">{$MOD.LBL_DUE_DATE}:</label>
            <input type="date" id="due_date" name="due_date">
        </div>

        <div class="btn-container">
            <button type="submit" class="btn btn-primary" id="createBtn" disabled>{$MOD.LBL_CREATE_INVOICE}</button>
            <a href="{$SITE_URL}/index.php?module=AOS_Invoices&action=ListView" class="btn btn-secondary">{$MOD.LBL_CANCEL}</a>
        </div>
    </form>
</div>

{literal}
<script>
document.addEventListener('DOMContentLoaded', function() {
    var checkboxes = document.querySelectorAll('.payment-checkbox');
    var createBtn = document.getElementById('createBtn');

    function checkSelection() {
        var checkedCount = 0;
        var lastPayer = null;
        var firstPayer = null;

        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                checkedCount++;
                var payer = checkbox.getAttribute('data-payer');
                if (firstPayer === null) {
                    firstPayer = payer;
                    lastPayer = payer;
                } else if (payer !== firstPayer) {
                    lastPayer = 'mixed';
                }
            }
        });

        createBtn.disabled = checkedCount === 0 || lastPayer === 'mixed';

        if (lastPayer === 'mixed') {
            alert('Todos los pagos deben ser del mismo pagador');
        }
    }

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', checkSelection);
    });
});
</script>
{/literal}