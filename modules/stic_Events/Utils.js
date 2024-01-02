/* HEADER */
// Set module name
var module = "stic_Events";

/* INCLUDES */
// Load moment.js to use in validations
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
  end_date: "start_date",
  start_date: "end_date",
};

/* VALIDATION CALLBACKS */
addToValidateCallback(getFormName(), "end_date", "date", false, SUGAR.language.get(module, "LBL_END_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "end_date");
});
addToValidateCallback(getFormName(), "start_date", "date", false, SUGAR.language.get(module, "LBL_START_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "end_date");
});

addToValidateMoreThan(getFormName(), "total_hours", "decimal", false, SUGAR.language.languages.app_strings.ERR_INVALID_VALUE, 0);

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
    // Adding color dots to "color" enum field
    buildEditableColorFieldSelectize('session_color');
    break;
  case "detail":
    // Define button content
    var buttons = {
      addTargetListToEventRegistrations: {
        id: "add_prospect_list",
        title: SUGAR.language.get("stic_Events", "LBL_BTN_ADD_TARGET_LIST"),
        onclick:
          "open_popup('ProspectLists', 800, 600, '', true, true, { 'call_back_function': 'setReturnAndSaveLPOToEvent',     'form_name': 'DetailView',    'field_to_name_array': {        'id': 'prospectListId'    },    'passthru_data': {           }}, 'Select', true)",
      },
      showSessionAssistant: {
        id: "show_session_assistant",
        title: SUGAR.language.get("stic_Events", "LBL_PERIODIC_SESSIONS"),
        onclick: "location.href='" + STIC.siteUrl + "/index.php?module=stic_Events&action=showSessionAssistant&event_id=" + STIC.record.id + "'",
      },
    };

    createDetailViewButton(buttons.addTargetListToEventRegistrations);
    createDetailViewButton(buttons.showSessionAssistant);

    // Adding color dots to "color" enum field
    buildDetailedColorFieldSelectize('session_color');
    break;
  case "list":
    // Adding color dots to "color" enum field
    // Check both massupdate and both filters, basic and advanced.
    buildEditableColorFieldSelectize('mass_session_color');
    buildEditableColorFieldSelectize('session_color_basic');
    buildEditableColorFieldSelectize('session_color_advanced');
    break;
  default:
    break;
}

/* AUX FUNCTIONS */
/**
 * Callback function after selecting a prospect list
 */
function setReturnAndSaveLPOToEvent(popupReplyData) {
  console.log("callback");
  var obj = {
    action: "addTargetListToEventRegistrations",
    module: "stic_Events",
    return_module: "stic_Events",
    return_action: "DetailView",
    record: window.document.forms["DetailView"].record.value,
    prospectListId: popupReplyData.name_to_value_array.prospectListId,
  };
  console.log(obj);

  var url = "?index.php&" + $.param(obj);
  location.href = url;
}
