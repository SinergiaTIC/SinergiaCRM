/* HEADER */
// Set module name
var module = "stic_Registrations";

/* INCLUDES */

/* VALIDATION DEPENDENCIES */
// var validationDependencies = {
//   stic_registrations_accounts_name: ["stic_registrations_contacts_name", "stic_registrations_leads_name"],
//   stic_registrations_contacts_name: ["stic_registrations_accounts_name", "stic_registrations_leads_name"],
//   stic_registrations_leads_name: ["stic_registrations_contacts_name", "stic_registrations_accounts_name"],
// };

/* VALIDATION CALLBACKS */

addToValidateCallback(getFormName(), "stic_registrations_contacts_name", "related", false, "", function() {
    return JSON.parse(checkContactOrAccountOrLead());
});

addToValidateCallback(getFormName(), "stic_registrations_accounts_name", "related", false, "", function() {
    return JSON.parse(checkContactOrAccountOrLead());
});

addToValidateCallback(getFormName(), "stic_registrations_leads_name", "related", false, "", function() {
    return JSON.parse(checkContactOrAccountOrLead());
});

addToValidateMoreThan(getFormName(), "attendees", "int", true, SUGAR.language.languages.app_strings.ERR_INVALID_VALUE, 1);

/* VIEWS CUSTOM CODE */

switch (viewType()) {
case "edit":
case "quickcreate":
case "popup":    
    setAutofill(["name"]);

    // Status behavior
    registrationStatus = {
        not_participate: {
            enabled: ["not_participating_reason"],
            disabled: ["rejection_reason"],
        },
        rejected: {
            enabled: ["rejection_reason"],
            disabled: ["not_participating_reason"],
        },
        default: {
            enabled: [],
            disabled: ["not_participating_reason", "rejection_reason"],
        },
    };

    setCustomStatus(registrationStatus, $("#status", "form").val());
    $("form").on("change", "#status", function() {
        clear_all_errors();
        setCustomStatus(registrationStatus, $("#status", "form").val());
    });

    // Special needings behaviour
    specialNeeds = {
        "1": {
            enabled: ["special_needs_description"],
            disabled: [],
            validated: [],
        },
        default: {
            enabled: [],
            disabled: ["special_needs_description"],
            validated: [],
        },
    };
    setCustomStatus(specialNeeds, $("#special_needs", "form").val());
    $("form").on("change", "#special_needs", function() {
        clear_all_errors();
        setCustomStatus(specialNeeds, $("#special_needs", "form").val());
    });
    break;

case "detail":
    break;

case "list":
    break;

default:
    break;
}

/* AUX. FUNCTIONS */
/**
 * Check if there is a person or a account or a lead selected
 */
function checkContactOrAccountOrLead() {
    var contact = 0
    var account = 0
    var lead = 0
    if (typeof getFieldValue("stic_registrations_contacts_name") != "undefined") {
        contact = getFieldValue("stic_registrations_contacts_name").length == 0 ? 0 : 1;
    }

    if (typeof getFieldValue("stic_registrations_accounts_name") != "undefined") {
        account = getFieldValue("stic_registrations_accounts_name").length == 0 ? 0 : 5;
    }

    if (typeof getFieldValue("stic_registrations_leads_name") != "undefined") {
        lead = getFieldValue("stic_registrations_leads_name").length == 0 ? 0 : 10;
    }

    var subjectCount = contact + account + lead;
    cl(subjectCount);
    // subjectCount evaluate:
    // 11 = lead & contact selected. Error
    // 15 = lead & account selected. Error
    // 16 = all selected. Error
    // 0 = none selected. Error
    // 1, 5, 10, 6 = Ok

    switch (subjectCount) {
    case 1:
    case 5:
    case 10:
    case 6:
        return true;
        break;

    case 15:
        clear_all_errors();
        $("input#stic_registrations_contacts_name").css("background-color", "transparent");
        add_error_style(getFormName(), "stic_registrations_accounts_name", SUGAR.language.get(module, "LBL_LEAD_ACCOUNT_CONFLICT"));
        add_error_style(getFormName(), "stic_registrations_leads_name", SUGAR.language.get(module, "LBL_LEAD_ACCOUNT_CONFLICT"));
        return false;
        break;
    case 11:
        clear_all_errors();
        $("input#stic_registrations_accounts_name").css("background-color", "transparent");
        add_error_style(getFormName(), "stic_registrations_contacts_name", SUGAR.language.get(module, "LBL_LEAD_CONTACT_CONFLICT"));
        add_error_style(getFormName(), "stic_registrations_leads_name", SUGAR.language.get(module, "LBL_LEAD_CONTACT_CONFLICT"));
        return false;
        break;
    case 16:
        clear_all_errors();
        add_error_style(getFormName(), "stic_registrations_contacts_name", SUGAR.language.get(module, "LBL_LEAD_ACCOUNT_OR_CONTACT_REQUIRED_NOT_ALL"));
        add_error_style(getFormName(), "stic_registrations_accounts_name", SUGAR.language.get(module, "LBL_LEAD_ACCOUNT_OR_CONTACT_REQUIRED_NOT_ALL"));
        add_error_style(getFormName(), "stic_registrations_leads_name", SUGAR.language.get(module, "LBL_LEAD_ACCOUNT_OR_CONTACT_REQUIRED_NOT_ALL"));
        return false;
        break;
    default:
        clear_all_errors();
        add_error_style(getFormName(), "stic_registrations_contacts_name", SUGAR.language.get(module, "LBL_LEAD_ACCOUNT_OR_CONTACT_REQUIRED"));
        add_error_style(getFormName(), "stic_registrations_accounts_name", SUGAR.language.get(module, "LBL_LEAD_ACCOUNT_OR_CONTACT_REQUIRED"));
        add_error_style(getFormName(), "stic_registrations_leads_name", SUGAR.language.get(module, "LBL_LEAD_ACCOUNT_OR_CONTACT_REQUIRED"));
        return false;

        break;
    }
}
