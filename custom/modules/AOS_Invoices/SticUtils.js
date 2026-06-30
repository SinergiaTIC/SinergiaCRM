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

// Validation: Organization or Person is required - add to validate and callback
addToValidate(getFormName(), "billing_account_id", "varchar", false, SUGAR.language.get(module, "LBL_VERIFACTU_REQUIRE_ORG_OR_PERSON"));
addToValidateCallback(getFormName(), "billing_account_id", "varchar", false, SUGAR.language.get(module, "LBL_VERIFACTU_REQUIRE_ORG_OR_PERSON"), function () {
  var accountId = $("#billing_account_id").val();
  var contactId = $("#billing_contact_id").val();
  return (accountId || contactId) ? true : false;
});

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
    // Ensure validation is attached when form is ready
    $(document).ready(function() {
      addToValidate(getFormName(), "billing_account_id", "varchar", false, SUGAR.language.get(module, "LBL_VERIFACTU_REQUIRE_ORG_OR_PERSON"));
      addToValidateCallback(getFormName(), "billing_account_id", "varchar", false, SUGAR.language.get(module, "LBL_VERIFACTU_REQUIRE_ORG_OR_PERSON"), function () {
        var accountId = $("#billing_account_id").val();
        var contactId = $("#billing_contact_id").val();
        return (accountId || contactId) ? true : false;
      });
      setAutofill(["name"]);

      initSeriesFilter();

      // Validation: Customer must have identification number (DNI/NIF/CIF)
      addToValidateCallback(getFormName(), "customer_id_number", "text", false,
          SUGAR.language.get(module, "LBL_CUSTOMER_IDENTIFICATION_NUMBER_MISSING"),
          function() {
              var accountId = $("#billing_account_id").val();
              var contactId = $("#billing_contact_id").val();
              if (!accountId && !contactId) return true;
              var idNum = $("#customer_id_number").val() || customerIdentificationNumber || '';
              return idNum.trim() !== "";
          }
      );

      // Override billing_contact popup button to also map Contact address fields.
      // The popup's send_back() looks up each field_to_name_array key in
      // associated_javascript_data[the_key.toUpperCase()], so we map the
      // Contact field names (primary_address_*) to form field names (billing_address_*)
      var contactBtn = document.getElementById('btn_billing_contact');
      if (contactBtn) {
          contactBtn.onclick = function() {
              var initialFilter = "&account_name=";
              if (document.EditView && document.EditView.billing_account) {
                  initialFilter += encodeURIComponent(document.EditView.billing_account.value);
              }
              open_popup("Contacts", 600, 400, initialFilter, true, false, {
                  "call_back_function": "set_return",
                  "form_name": "EditView",
                  "field_to_name_array": {
                      "id": "billing_contact_id",
                      "name": "billing_contact",
                      "primary_address_street": "billing_address_street",
                      "primary_address_city": "billing_address_city",
                      "primary_address_state": "billing_address_state",
                      "primary_address_postalcode": "billing_address_postalcode",
                      "primary_address_country": "billing_address_country",
                      "alt_address_street": "shipping_address_street",
                      "alt_address_city": "shipping_address_city",
                      "alt_address_state": "shipping_address_state",
                      "alt_address_postalcode": "shipping_address_postalcode",
                      "alt_address_country": "shipping_address_country",
                      "stic_identification_number_c": "customer_id_number"
                  }
              }, "single", true);
              return false;
          };
      }

      // NOTE: Autocomplete address population is handled by extending
      // sqs_objects['EditView_billing_contact'] in view.edit.php (inline script
      // after parent::display()). The quicksearch's field_list/populate_list are
      // extended to include address fields before enableQS creates the widget.

      // Mutual exclusion: when Account is selected, disable Contact (and vice versa).
      // Polling detects changes from popup, autocomplete, or X button clearing.
      (function() {
          var lastAccountId, lastContactId;
          function setDisabled(el, disabled) {
              $(el).prop('disabled', disabled).toggleClass('stic-disabled', disabled);
          }
          function syncDisableState() {
              var accountId = $('#billing_account_id').val();
              var contactId = $('#billing_contact_id').val();
              if (accountId === lastAccountId && contactId === lastContactId) return;
              lastAccountId = accountId;
              lastContactId = contactId;
              setDisabled('#billing_contact', !!accountId);
              setDisabled('#btn_billing_contact, #btn_clr_billing_contact', !!accountId);
              setDisabled('#billing_account', !!contactId);
              setDisabled('#btn_billing_account, #btn_clr_billing_account', !!contactId);
          }
          syncDisableState();
          setInterval(syncDisableState, 300);
      })();

      // Disable non-draft options in status dropdown when original status is draft
      // During creation, also disable 'emitted' (use "Enviar a AEAT" button instead)
      if ($('#status').val() === 'draft') {
        var isNewRecord = !$('[name="record"]').val();
        $('#status option').each(function() {
          var val = $(this).val();
          if (val !== 'draft' && (val !== 'emitted' || isNewRecord)) {
            $(this).prop('disabled', true);
          }
        });
      }

      // Hide stic_invoice_type_c (Tipo de factura) in EditView when Verifactu is NOT activated (legacy mode)
      if (typeof verifactuActivated !== 'undefined' && verifactuActivated === false) {
        $("#stic_invoice_type_c").closest(".edit-view-row-item").hide();
      }

      // Clear address fields when Account or Contact X button is clicked
      $('#btn_clr_billing_account, #btn_clr_billing_contact').on('click', function() {
          setTimeout(function() {
              if (!$('#billing_account_id').val() && !$('#billing_contact_id').val()) {
                  var addressFields = [
                      'billing_address_street', 'billing_address_city', 'billing_address_state',
                      'billing_address_postalcode', 'billing_address_country',
                      'shipping_address_street', 'shipping_address_city', 'shipping_address_state',
                      'shipping_address_postalcode', 'shipping_address_country'
                  ];
                  addressFields.forEach(function(f) { $('#' + f).val(''); });
              }
          }, 0);
      });
    });
    break;

  // 'customCode' => '{if !empty($fields.verifactu_submitted_at_c.value) && empty($fields.verifactu_is_rectified_c.value)}<input type="button" class="button" value="{$MOD.LBL_CREATE_RECTIFIED_INVOICE}" onclick="window.location.href=\'index.php?module=AOS_Invoices&action=CreateRectifiedInvoice&record={$fields.id.value}\';" />{/if}',

  case "detail":
    if (typeof verifactuActivated !== 'undefined' && verifactuActivated) {
      var buttons = {
        sendToAEAT: {
          id: "bt_send_to_aeat",
          title: SUGAR.language.get("AOS_Invoices", "LBL_SIGNER_SEND_TO_AEAT"),
          onclick: "sendToAEAT()",
        },
        createRectifiedInvoice: {
          id: "bt_create_rectified_invoice",
          title: SUGAR.language.get("AOS_Invoices", "LBL_CREATE_RECTIFIED_INVOICE"),
          onclick: "window.location='index.php?module=AOS_Invoices&action=CreateRectifiedInvoice&record=" + STIC.record.id + "'",
        },
        cancelInvoice: {
          id: "bt_cancel_invoice",
          title: SUGAR.language.get("AOS_Invoices", "LBL_CANCEL_INVOICE"),
          onclick: "if(confirm('" + SUGAR.language.get("AOS_Invoices", "LBL_CANCEL_INVOICE_CONFIRM") + "')) { window.location='index.php?module=AOS_Invoices&action=CancelInvoice&record=" + STIC.record.id + "'; }",
        },
      };

      // Rectified invoice button: enabled only if accepted by AEAT
      if (STIC.record.verifactu_aeat_status_c !== 'accepted') {
        buttons.createRectifiedInvoice.disabled = 'disabled';
        buttons.createRectifiedInvoice.style = "cursor: not-allowed; opacity: .5;";
      }

      // Send to AEAT button: disabled if already accepted or cancelled in AEAT
      if (STIC.record.verifactu_aeat_status_c === 'accepted' || STIC.record.verifactu_aeat_status_c === 'cancelled') {
        buttons.sendToAEAT.disabled = 'disabled';
        buttons.sendToAEAT.style = "cursor: not-allowed; opacity: .5;";
      }

      // Cancel invoice button: only enabled if invoice is accepted by AEAT (not rectified)
      if (STIC.record.verifactu_aeat_status_c !== 'accepted') {
        buttons.cancelInvoice.disabled = 'disabled';
        buttons.cancelInvoice.style = "cursor: not-allowed; opacity: .5;";
      }

      createDetailViewButton(buttons.sendToAEAT);
      createDetailViewButton(buttons.createRectifiedInvoice);
      createDetailViewButton(buttons.cancelInvoice);

      // Disable delete button for invoices already sent to AEAT
      if (STIC.record.verifactu_aeat_status_c === 'accepted' || STIC.record.verifactu_aeat_status_c === 'emitted') {
        disableDeleteButton();
      }
    }

    break;

  case "list":
    // Restrict inline edit for non-draft invoices in list view
    // Only status, assigned_user_name and description can be inline-edited
    // Detection: if verifactu_aeat_status_c cell has a value, it's non-draft (draft=empty)
    // In legacy mode, verifactu_aeat_status_c is always empty, so no restriction applies
    (function() {
      if (typeof verifactuActivated === "undefined" || verifactuActivated !== true) {
        return;
      }

      var allowedFields = ["status", "assigned_user_name", "description"];

      function restrictInlineEdit() {
        var rows = document.querySelectorAll(".listViewBody tr.oddListRowS1, .listViewBody tr.evenListRowS1");

        rows.forEach(function(row) {
          var statusCell = row.querySelector('td[field="status"]');
          if (!statusCell) return;
          var statusText = statusCell.textContent.trim();
          if (statusText === "Borrador" || statusText === "Draft") return;

          row.querySelectorAll("td[field]").forEach(function(cell) {
            var fieldName = cell.getAttribute("field");
            if (allowedFields.indexOf(fieldName) === -1) {
              cell.classList.remove("inlineEdit");
              $(cell).off("dblclick");
              cell.setAttribute("title", typeof verifactuInlineEditRestricted !== "undefined" ? verifactuInlineEditRestricted : "");
              var icon = cell.querySelector(".inlineEditIcon");
              if (icon) icon.style.display = "none";
            }
          });
        });
      }

      $(document).ready(function() {
        restrictInlineEdit();
        setInterval(restrictInlineEdit, 3000);
      });
    })();
    break;

  default:
    break;
}


// Only show rectified invoice panel if the invoice is rectified
if (STIC?.record?.verifactu_is_rectified_c == '0') {
  $("[data-label=LBL_VERIFACTU_RECTIFIED_PANEL]").closest('.panel').hide();
}



/* AUX FUNCTIONS */

// Confirmation and redirection for sending invoice to AEAT. 
// If invoice is in draft status, it will be marked as emitted before sending.
function sendToAEAT() {
  if (STIC.record.status === 'draft') {
    if (!confirm(SUGAR.language.get("AOS_Invoices", "LBL_SEND_TO_AEAT_CONFIRM_DRAFT"))) {
      return;
    }
  }
  window.location = 'index.php?module=AOS_Invoices&action=sendToAEAT&invoiceId=' + STIC.record.id + '&set=emitted';
}

// Disable delete button in detail view when invoice is already sent to AEAT
function disableDeleteButton() {
  // Find the delete button in the action menu
  var deleteButton = document.querySelector('[name="Delete"]');
  if (deleteButton) {
    deleteButton.disabled = true;
    deleteButton.style.cursor = 'not-allowed';
    deleteButton.style.opacity = '0.5';
    deleteButton.onclick = function() {
      alert(SUGAR.language.get("AOS_Invoices", "LBL_VERIFACTU_BLOCK_DELETE_MESSAGE"));
      return false;
    };
  }
  
  // Also disable any other delete links/buttons in the dropdown menu
  var menuItems = document.querySelectorAll('.dropdown-menu li a');
  menuItems.forEach(function(item) {
    var href = item.getAttribute('href') || '';
    if (href.indexOf('action=delete') !== -1 || href.indexOf('delete.php') !== -1) {
      item.style.cursor = 'not-allowed';
      item.style.opacity = '0.5';
      item.onclick = function(e) {
        e.preventDefault();
        alert(SUGAR.language.get("AOS_Invoices", "LBL_VERIFACTU_BLOCK_DELETE_MESSAGE"));
        return false;
      };
    }
  });
}

// === Step 2.3: Filter series dropdown based on isRectified flag ===
function filterSeriesDropdown() {
  var seriesSelect = $("#stic_invoice_type_c");

  if (!seriesSelect.length || typeof sticSeriesConfig === 'undefined') {
    return;
  }

  // Sync checkbox with currently selected series
  var currentValue = seriesSelect.val();
  var currentSeriesInfo = sticSeriesConfig.find(function(s) { return s.name === currentValue; });
  if (currentSeriesInfo) {
    $("#verifactu_is_rectified_c").prop("checked", currentSeriesInfo.isRectified);
  }

  var isRectified = $("#verifactu_is_rectified_c").is(":checked");
  var validOptions = [];
  var firstValidValue = null;

  seriesSelect.find("option").each(function() {
    var option = $(this);
    var optionValue = option.val();

    if (!optionValue) {
      return;
    }

    var seriesInfo = sticSeriesConfig.find(function(s) { return s.name === optionValue; });

    if (seriesInfo) {
      var isValid = seriesInfo.isRectified === isRectified;
      option.css('display', isValid ? '' : 'none');

      if (isValid) {
        validOptions.push(optionValue);
        if (firstValidValue === null) {
          firstValidValue = optionValue;
        }
      }
    }
  });

  if (validOptions.length > 0 && !validOptions.includes(currentValue)) {
    seriesSelect.val(firstValidValue);
  }
}

function initSeriesFilter() {
  filterSeriesDropdown();

  $("#verifactu_is_rectified_c").on("change", function() {
    filterSeriesDropdown();
  });

  $("#stic_invoice_type_c").on("change", function() {
    filterSeriesDropdown();
  });
}

// Hide AEAT status panel in legacy mode (VERIFACTU_ACTIVATED = 0)
$(document).ready(function() {
  var isVerifactuActivated = typeof verifactuActivated !== 'undefined' ? verifactuActivated : null;
  if (isVerifactuActivated === false) {
    $('[data-id="LBL_AEAT_STATUS_PANEL"]').closest(".panel").hide();
  }
});