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
var module = "stic_Payments";

// Attach event to remove spaces and strange characters in the IBAN
$("body").on("blur paste change", "input#bank_account", function () {
  $(this).val(
    $(this)
      .val()
      .replace(/[^0-9a-zA-Z]/g, "")
      .toUpperCase()
  );
});

/* INCLUDES */

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
  bank_account: "payment_method",
  payment_method: "bank_account",
};

/* VALIDATION CALLBACKS */

validateFunctions.bank_account = function () {
  var isRequired = ["edit", "quickcreate"].indexOf(viewType()) >= 0;
  addToValidateCallback(getFormName(), "bank_account", "text", isRequired, SUGAR.language.get(module, "LBL_NO_BANK_ACCOUNT_ERROR"), function () {
    return JSON.parse(checkBankAccount());
  });
};

addToValidateCallback(getFormName(), "payment_method", "enum", true, SUGAR.language.get(module, "LBL_BANK_ACCOUNT_SHOULD_BE_EMPTY_ERROR"), function () {
  if (
    getFieldValue("payment_method", "stic_payments_methods_list") != "direct_debit" &&
    getFieldValue("payment_method", "stic_payments_methods_list") != "transfer_issued" &&
    getFieldValue("bank_account")
  ) {
    return false;
  }
  return true;
});

addToValidateCallback(getFormName(), "payment_method", "enum", true, SUGAR.language.get(module, "LBL_NO_BANK_ACCOUNT_ERROR"), function () {
  return JSON.parse(checkBankAccount());
});

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":    
    // Definition of the behavior of fields that are conditionally enabled or disabled
    paymentMethodStatus = {
      direct_debit: {
        enabled: ["bank_account", "mandate"],
        disabled: [],
      },
      transfer_issued: {
        enabled: ["bank_account"],
        disabled: ["mandate"],
      },
      default: {
        enabled: [],
        disabled: ["bank_account", "mandate"],
      },
    };

    setCustomStatus(paymentMethodStatus, $("#payment_method", "form").val());
    $("form").on("change", "#payment_method", function () {
      clear_all_errors();
      setCustomStatus(paymentMethodStatus, $("#payment_method", "form").val());
    });

    setAutofill(["name", "mandate"]);

    checkBlockedPayment();
    // add observer for blocked changes
    $("form").on("change", "#blocked", function () {
      checkBlockedPayment();
    });

    checkAmountInEditView();

    break;
  case "detail":
    checkPercentagesInDetailView();    
  
    checkBlockedPaymentInDetailView();
    // add observer for [field='blocked'] changes
    const targetNode = document.querySelector('[field="blocked"]');
    var observer = new MutationObserver(function(mutations) {
      checkBlockedPaymentInDetailView();
    });
    observer.observe(targetNode, { childList: true, subtree: true});

    checkAmountInDetailView();
      $("#allocated").on("change", function() {
        checkAmountInDetailView();
    });    

    break;

  case "list":
    selectRemittanceAlert = SUGAR.language.get("stic_Payments", "LBL_ADD_PAYMENTS_TO_REMITTANCE_INFO_ALERT");
    alertListView = SUGAR.language.languages.app_strings.LBL_LISTVIEW_NO_SELECTED;
    button = {
      id: "addPaymentsToRemittance",
      text: SUGAR.language.get("stic_Payments", "LBL_ADD_PAYMENTS_TO_REMITTANCE_BUTTON_LABEL"),
      onclick: "onClickAddPaymentsToRemittanceButton()",
    };

    createListViewButton(button);

    break;

  default:
    break;
}

function checkAmountInEditView() {
  $("#amount").on('change', function() {
    var allocated = $("#allocated").is(":checked");
    if(allocated) {
      let alertMessage = SUGAR.language.get("stic_Payments", "LBL_ALLOCATED_AMOUNT_ALERT");
      alert(alertMessage);
    }
  });
  
}

function checkAmountInDetailView() {

  var allocated = $("[field='allocated'] input").is(":checked");
  if (allocated) {
    addAmountInDetailViewAlert();
  }
}

function addAmountInDetailViewAlert() {
  let alertMessage = SUGAR.language.get("stic_Payments", "LBL_ALLOCATED_AMOUNT_ALERT");
  const targetNode = document.querySelector('[field="amount"]');
  var observer = new MutationObserver(function(mutations) {
    // check if any added node is a form
    mutations.forEach(function(mutation) {
      mutation.addedNodes.forEach(function(addedNode) {
        if (addedNode.nodeName === 'FORM') { // Check if it's an element node
          alert(alertMessage);
        }
      });
    });
  });
  observer.observe(targetNode, { childList: true, subtree: true});
}

function checkPercentagesInDetailView() {
  let allocatedPercentage =  $("#allocated_percentage").text().replace(/\n/g, "");
  let justifiedPercentage =  $("#justified_percentage").text().replace(/\n/g, "");

  if (allocatedPercentage > 100) {
    $("#allocated_percentage").css("color", "red");
    var iconUrl = "https://cdn-icons-png.flaticon.com/512/3253/3253156.png";

    // Create the image HTML with inline styles for size and spacing
    var iconHtml = '<img src="' + iconUrl + '" style="width: 16px; height: 16px; margin-left: 8px; vertical-align: middle;" />';

    // Append it to the span
    $('#allocated_percentage').prepend(iconHtml);
  }
  if (justifiedPercentage > 100) {
    $("#justified_percentage").css("color", "red");
    var iconUrl = "https://cdn-icons-png.flaticon.com/512/3253/3253156.png";

    // Create the image HTML with inline styles for size and spacing
    var iconHtml = '<img src="' + iconUrl + '" style="width: 16px; height: 16px; margin-left: 8px; vertical-align: middle;" />';

    // Append it to the span
    $('#justified_percentage').prepend(iconHtml);
  }
}


function checkBlockedPaymentInDetailView() {
  var blocked = $("[field='blocked'] input").is(":checked")
  if (blocked) {
    $(".inlineEdit").css("pointer-events", "none");
    $("[field='blocked']").css("pointer-events", "auto");
  }
  else {
    $(".inlineEdit").css("pointer-events", "auto");

  }
}
function checkBlockedPayment() {
  var blocked = $("#blocked").is(":checked");
  if (blocked) {
    $(".edit-view-row-item input").prop('disabled', true); // text, decimals, checks, etc.
    $(".edit-view-row-item select").prop('disabled', true); // desplegables
    $("button[type='button']:not(.saveAndContinue)").prop('disabled', true); // buttons except "Save and Continue Edit"
    $("#blocked").prop('disabled', false); // keep blocked enabled
  }
  else {
    $(".edit-view-row-item input").prop('disabled', false);
    $(".edit-view-row-item select").prop('disabled', false);
    $("button[type='button']").prop('disabled', false);
  }
}


function setReturnAddPaymentsToRemittance(popupReplyData) {
  SUGAR.ajaxUI.loadingPanel.hide();
  ajaxStatus.hideStatus();
  sugarListView.get_checks();
  var remittanceId = popupReplyData.name_to_value_array.remittanceId;
  var input = $("<input>").attr("type", "hidden").attr("name", "remittanceId").attr("id", "remittanceId").val(remittanceId);
  $("#MassUpdate").append(input);
  document.MassUpdate.action.value = "addPaymentsToRemittance";
  document.MassUpdate.module.value = "stic_Remittances";
  document.MassUpdate.submit();
}

function onClickAddPaymentsToRemittanceButton() {
  sugarListView.get_checks();
  if (sugarListView.get_checks_count() < 1) {
    alert(alertListView);
    return false;
  }
  var win = open_popup(
    "stic_Remittances",
    800,
    600,
    "",
    true,
    true,
    { call_back_function: "setReturnAddPaymentsToRemittance", form_name: "ListView", field_to_name_array: { id: "remittanceId" }, passthru_data: {} },
    "single",
    true,
    "lvso=DES&stic_Remittances2_STIC_REMITTANCES_ORDER_BY=charge_date"
  );
  win.onload = function(){ 
    win.onbeforeunload = function() {
      SUGAR.ajaxUI.loadingPanel.hide();
      ajaxStatus.hideStatus();
    };
  }
  // In order to initialize the function loadingPanel.show(), it needs to be called the showLoadingPanel() first.
  SUGAR.ajaxUI.showLoadingPanel();
  SUGAR.ajaxUI.hideLoadingPanel();
  SUGAR.ajaxUI.loadingPanel.show();
  ajaxStatus.showStatus(selectRemittanceAlert);
  document.getElementById("ajaxStatusDiv").style.zIndex = 1040; // No need this line when this PR is merged: https://github.com/salesagility/SuiteCRM/issues/8266
}

/* AUX FUNCTIONS */
/**
 * Check bank_account IBAN
 */
function checkBankAccount() {
  var paymentMethod = getFieldValue("payment_method", "stic_payments_methods_list");
  var bankAccount = getFieldValue("bank_account");

  switch (paymentMethod) {
    case "direct_debit":
    case "transfer_issued":
      var res = checkIBAN(bankAccount);
      break;
    default:
      return bankAccount == "";
      break;
  }
  return JSON.parse(res);
}
