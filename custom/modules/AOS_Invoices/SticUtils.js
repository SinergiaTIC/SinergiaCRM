/**
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
 */
/* HEADER */
// Set module name
var module = "AOS_Invoices";

/* INCLUDES */
// Load moment.js to use in validations
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
  invoice_date: "due_date",
  due_date: "invoice_date",
};

/* DIRECT VALIDATION CALLBACKS */
addToValidateCallback(getFormName(), "invoice_date", "date", false, SUGAR.language.get(module, "LBL_INVOICE_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("invoice_date", "due_date");
});

addToValidateCallback(getFormName(), "due_date", "date", false, SUGAR.language.get(module, "LBL_DUE_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("invoice_date", "due_date");
});

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
    break;

                        // 'customCode' => '{if !empty($fields.verifactu_submitted_at_c.value) && empty($fields.verifactu_is_rectified_c.value)}<input type="button" class="button" value="{$MOD.LBL_CREATE_RECTIFIED_INVOICE}" onclick="window.location.href=\'index.php?module=AOS_Invoices&action=CreateRectifiedInvoice&record={$fields.id.value}\';" />{/if}',

  case "detail":
    var buttons = {
      sendToAEAT: {
        id: "bt_send_to_aeat",
        title: SUGAR.language.get("AOS_Invoices", "LBL_SIGNER_SEND_TO_AEAT"),
        onclick: "window.location='index.php?module=AOS_Invoices&action=sendToAEAT&invoiceId=" + STIC.record.id + "'",        
      },
      createRectifiedInvoice: {
        id: "bt_create_rectified_invoice",
        title: SUGAR.language.get("AOS_Invoices", "LBL_CREATE_RECTIFIED_INVOICE"),
        onclick: "window.location='index.php?module=AOS_Invoices&action=CreateRectifiedInvoice&record=" + STIC.record.id + "'",
      },
    };

    if(STIC.record.status != 'emitted') {
      buttons.createRectifiedInvoice.disabled = 'disabled';
      buttons.createRectifiedInvoice.style = "cursor: not-allowed; opacity: .5;";
    }

    if(STIC.record.status === 'emitted' && STIC.record.verifactu_aeat_status_c === 'accepted') {
      buttons.sendToAEAT.disabled = 'disabled';
      buttons.sendToAEAT.style = "cursor: not-allowed; opacity: .5;";
    }

    createDetailViewButton(buttons.sendToAEAT);
    createDetailViewButton(buttons.createRectifiedInvoice);

    break;

  case "list":
    break;

  default:
    break;
}

    
    // Only show rectified invoice panel if the invoice is rectified
    if(STIC?.record?.verifactu_is_rectified_c == '0')
    {
      $("[data-label=LBL_VERIFACTU_RECTIFIED_PANEL]").closest('.panel').hide();
    }



/* AUX FUNCTIONS */
