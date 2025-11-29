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
var module = "stic_Payment_Commitments";

// Attach event to remove spaces and strange characters in the IBAN
$("body").on("blur paste change", "input#bank_account", function() {
    $(this).val($(this).val().replace(/[^0-9a-zA-Z]/g, "").toUpperCase());
});

/* INCLUDES */
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
    bank_account: "payment_method",
    payment_method: "bank_account",
    stic_payment_commitments_contacts_name: "stic_payment_commitments_accounts_name",
    stic_payment_commitments_accounts_name: "stic_payment_commitments_contacts_name",
    first_payment_date: "end_date",
    end_date: "first_payment_date"
};

/* VALIDATION CALLBACKS */

validateFunctions.bank_account = function() {
    var isRequired = ["edit", "quickcreate"].indexOf(viewType()) >= 0;
    addToValidateCallback(
        getFormName(),
        "bank_account",
        "text",
        isRequired,
        SUGAR.language.get(module, "LBL_NO_BANK_ACCOUNT_ERROR"),
        function() {
            return JSON.parse(checkBankAccount());
        }
    );
};

addToValidateCallback(
    getFormName(),
    "payment_method",
    "enum",
    true,
    SUGAR.language.get(module, "LBL_BANK_ACCOUNT_SHOULD_BE_EMPTY_ERROR"),
    function() {
        if (
            getFieldValue("payment_method", "stic_payments_methods_list") != "direct_debit" &&
            getFieldValue("payment_method", "stic_payments_methods_list") != "transfer_issued" &&
            getFieldValue("bank_account")
        ) {
            return false;
        }
        return true;
    }
);

addToValidateCallback(getFormName(), "payment_method", "enum", true, SUGAR.language.get(module, "LBL_NO_BANK_ACCOUNT_ERROR"), function() {
    return JSON.parse(checkBankAccount());
});

addToValidateCallback(
    getFormName(),
    "stic_payment_commitments_contacts_name",
    "related",
    false,
    SUGAR.language.get(module, "LBL_MUST_RELATE_TO_AN_ACCOUNT_OR_A_CONTACT"),
    function() {
        return JSON.parse(checkPCContactOrAccount());
    }
);

addToValidateCallback(
    getFormName(),
    "stic_payment_commitments_accounts_name",
    "related",
    false,
    SUGAR.language.get(module, "LBL_MUST_RELATE_TO_AN_ACCOUNT_OR_A_CONTACT"),
    function() {
        return JSON.parse(checkPCContactOrAccount());
    }
);

addToValidateCallback(getFormName(), "end_date", "date", false, SUGAR.language.get(module, "LBL_END_DATE_ERROR"), function() {
    return checkStartAndEndDatesCoherence("first_payment_date", "end_date");
});

addToValidateCallback(
    getFormName(),
    "first_payment_date",
    "date",
    false,
    SUGAR.language.get(module, "LBL_FIRST_PAYMENT_DATE_ERROR"),
    function() {
        return checkStartAndEndDatesCoherence("first_payment_date", "end_date");
    }
);

addToValidateCallback(
    getFormName(),
    "payment_type",
    "enum",
    true,
    SUGAR.language.get(module, "LBL_AGGREGATED_SHOULDNT_BE_PUNTUAL_ERROR"),
    function() {
        if (
            getFieldValue("payment_type", "stic_payments_types_list") == "aggregated_services" &&
            getFieldValue("periodicity", "stic_payments_periodicities_list") == "punctual"
        ) {
            return false;
        }
        return true;
    }
);

addToValidateCallback(
    getFormName(),
    "periodicity",
    "enum",
    true,
    SUGAR.language.get(module, "LBL_AGGREGATED_SHOULDNT_BE_PUNTUAL_ERROR"),
    function() {
        if (
            getFieldValue("payment_type", "stic_payments_types_list") == "aggregated_services" &&
            getFieldValue("periodicity", "stic_payments_periodicities_list") == "punctual"
        ) {
            return false;
        }
        return true;
    }
);

/* VIEWS CUSTOM CODE */
switch (viewType()) {
    case "edit":
    case "quickcreate":
    case "popup":        
        // Definition of the behavior of fields that are conditionally enabled or disabled
        paymentMethodStatus = {
            direct_debit: {
                enabled: ["bank_account", "mandate", "signature_date"],
                disabled: []
            },
            transfer_issued: {
                enabled: ["bank_account"],
                disabled: ["mandate", "signature_date"]
            },
            default: {
                enabled: [],
                disabled: ["bank_account", "mandate", "signature_date"]
            }
        };

        setCustomStatus(paymentMethodStatus, $("#payment_method", "form").val());
        $("form").on("change", "#payment_method", function() {
            clear_all_errors();
            setCustomStatus(paymentMethodStatus, $("#payment_method", "form").val());
        });

        setAutofill(["name", "mandate", "signature_date"]);

        // In the quickcreate form, Contact and Account fields are disabled to avoid errors while entering data.
        // It should be noticed that in Contacts/Accounts detail view there will be two subpanels related to the Payment Commitments module,
        // one for setting who pays, one for setting (if needed) the recipient. So in the subpanel will appear four relevant fields
        // (Contact/Account that pays, Contact/Account that is the recipient). If the subpanel is for the first relationship,
        // the paying Contact/Account buttons will be disabled & input wil be readonly. If the subpanel is for the second relationship, the recipient
        // Contact/Account buttons will be disabled & input will  be readionly. In both cases the other two fields will remain fully enabled.
        if (viewType() == "quickcreate") {
            // Each one of the four lines below address one of the four possible scenarios (Contacts or Accounts detail view vs one relationship or another).
            // There's no need to detect the current scenario because jQuery will assure that only the proper line is run.

            // Disabling the "Contact" field relationship
            $(
                "#subpanel_stic_payment_commitments_contacts_newDiv #stic_payment_commitments_contacts_name, #subpanel_stic_payment_commitments_contacts_newDiv #stic_payment_commitments_accounts_name"
            )
                .closest(".edit-view-row-item")
                .find("input[type=text]")
                .prop("readonly", "readonly")
                .css("background-color", "#bcbcbc")
                .closest(".edit-view-row-item")
                .find(".button")
                .prop("disabled", "disabled");
            // Disabling the "Account" field relationship
            $(
                "#subpanel_stic_payment_commitments_accounts_newDiv #stic_payment_commitments_contacts_name, #subpanel_stic_payment_commitments_accounts_newDiv #stic_payment_commitments_accounts_name"
            )
                .closest(".edit-view-row-item")
                .find("input[type=text]")
                .prop("readonly", "readonly")
                .css("background-color", "#bcbcbc")
                .closest(".edit-view-row-item")
                .find(".button")
                .prop("disabled", "disabled");
            // Disabling the "Recipient contact" field relationship
            $(
                "#subpanel_stic_payment_commitments_contacts_1_newDiv #stic_payment_commitments_contacts_1_name, #subpanel_stic_payment_commitments_contacts_1_newDiv #stic_payment_commitments_accounts_1_name"
            )
                .closest(".edit-view-row-item")
                .find("input[type=text]")
                .prop("readonly", "readonly")
                .css("background-color", "#bcbcbc")
                .closest(".edit-view-row-item")
                .find(".button")
                .prop("disabled", "disabled");
            // Disabling the "Recipient account" field relationship
            $(
                "#subpanel_stic_payment_commitments_accounts_1_newDiv #stic_payment_commitments_contacts_1_name, #subpanel_stic_payment_commitments_accounts_1_newDiv #stic_payment_commitments_accounts_1_name"
            )
                .closest(".edit-view-row-item")
                .find("input[type=text]")
                .prop("readonly", "readonly")
                .css("background-color", "#bcbcbc")
                .closest(".edit-view-row-item")
                .find(".button")
                .prop("disabled", "disabled");
        }

        break;
    case "detail":
        // var recordId = $("#formDetailView input[type=hidden][name=record]").val();
        var buttons = {
          copyProposals: {
                id: "add_prospect_list",
                title: SUGAR.language.get("stic_Payment_Commitments", "LBL_COPY_PROPOSALS_BUTTON_TITTLE"),
                onclick:
                "open_popup('stic_Payment_Commitments', 800, 600, '', true, true, { 'call_back_function': 'setReturnAndCopyProposals',     'form_name': 'DetailView',    'field_to_name_array': {        'id': 'originalPC'    },    'passthru_data': {           }}, 'Select', true)",
            }
        };
      createDetailViewButton(buttons.copyProposals);
        break;

    case "list":
        var buttons = {
          copyProposals: {
                id: "add_prospect_list",
                title: SUGAR.language.get("stic_Payment_Commitments", "LBL_COPY_PROPOSALS_BUTTON_TITTLE"),
                text: SUGAR.language.get("stic_Payment_Commitments", "LBL_COPY_PROPOSALS_BUTTON_TITTLE"),
                onclick:
                "open_popup('stic_Payment_Commitments', 800, 600, '', true, true, { 'call_back_function': 'setReturnAndMassCopyProposals',     'form_name': 'DetailView',    'field_to_name_array': {        'id': 'originalPC'    },    'passthru_data': {           }}, 'Select', true)",
            }
        };
      createListViewButton(buttons.copyProposals);
        break;

    default:
        break;
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

/**
 * Check if there is a person or a account or both
 */
function checkPCContactOrAccount() {
    if (viewType() == "edit") {
        contact = getFieldValue("stic_payment_commitments_contactscontacts_ida");
        account = getFieldValue("stic_payment_commitments_accountsaccounts_ida");
    } else {
        // For inline edit we can only check this fields
        contact = getFieldValue("stic_payment_commitments_contacts_name");
        account = getFieldValue("stic_payment_commitments_accounts_name");
    }

    if ((contact != "" && account != "") || (contact == "" && account == "")) {
        return false;
    }
    return true;
}

/**
 * Callback function after selecting a payment commitment to copy from
 */
function setReturnAndCopyProposals(popupReplyData) {
  console.log("callback");
  debugger;
  var obj = {
    action: "copyPCProposals",
    module: "stic_Payment_Commitments",
    return_module: "stic_Payment_Commitments",
    return_action: "DetailView",
    record: window.document.forms["DetailView"].record.value,
    originPC: popupReplyData.name_to_value_array.originalPC,
  };
  console.log(obj);

  var url = "?index.php&" + $.param(obj);
  location.href = url;
}

/**
 * Callback function after selecting a payment commitment to copy from, from the list view
 */
function setReturnAndMassCopyProposals(popupReplyData) {
    debugger;
    sugarListView.get_checks();
    if(sugarListView.get_checks_count() < 1) {
        alert(SUGAR.language.get('app_strings', 'LBL_LISTVIEW_NO_SELECTED'));
        return false;
    }
    document.MassUpdate.action.value='massCopyPCProposals';
    document.MassUpdate.module.value='stic_Payment_Commitments';
    // add element for originPC value
    var originPCInput = document.createElement("input");
    originPCInput.type = "hidden";
    originPCInput.name = "originPC";
    originPCInput.value = popupReplyData.name_to_value_array.originalPC;
    document.MassUpdate.appendChild(originPCInput);

    document.MassUpdate.submit();
}
