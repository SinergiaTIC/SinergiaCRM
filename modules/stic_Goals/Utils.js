/* HEADER */
// Set module name
var module = "stic_Goals";

/* INCLUDES */
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
  start_date: "actual_end_date",
  start_date: "expected_end_date",
  expected_end_date: "start_date",
  actual_end_date: "start_date",
};

/* VALIDATION CALLBACKS */
addToValidateCallback(getFormName(), "expected_end_date", "date", false, SUGAR.language.get(module, "LBL_EXPECTED_END_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "expected_end_date");
});

addToValidateCallback(getFormName(), "actual_end_date", "date", false, SUGAR.language.get(module, "LBL_ACTUAL_END_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "actual_end_date");
});

addToValidateCallback(getFormName(), "start_date", "date", false, SUGAR.language.get(module, "LBL_START_DATE_ERROR_2"), function () {
  return checkStartAndEndDatesCoherence("start_date", "actual_end_date");
});

addToValidateCallback(getFormName(), "start_date", "date", false, SUGAR.language.get(module, "LBL_START_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "expected_end_date");
});

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    contactInView = $("#stic_goals_contactscontacts_ida").length;
    familyInView = $("#stic_families_stic_goalsstic_families_ida").length;
    addValidationsAccordingToContactOrFamily(contactInView, familyInView);
    break;
  case "detail":
    contactInView = $("#stic_goals_contactscontacts_ida").length;
    familyInView = $("#stic_families_stic_goalsstic_families_ida").length;
    addValidationsAccordingToContactOrFamily(contactInView, familyInView);
    function sub_p_rem(subpanelName, getSubpanelData, goalId) {
      console.log(subpanelName, getSubpanelData, goalId);
    }
    break;
  case "list":
    contactInView = $('[field=stic_goals_contacts_name]').length;
    familyInView = $('[field=stic_families_stic_goals_name]').length;
    addValidationsAccordingToContactOrFamily(contactInView, familyInView);
  default:
    break;
}

/* AUX FUNCTIONS */
/**
 * Add validations for contact or family field
 */
function addValidationsAccordingToContactOrFamily(contactInView, familyInView) {
  if (contactInView && familyInView) {
    validationDependencies['stic_goals_contacts_name'] = "stic_families_stic_goals_name";
    validationDependencies['stic_families_stic_goals_name'] = "stic_goals_contacts_name";

    addToValidateCallback(getFormName(), "stic_goals_contacts_name", "related", false, SUGAR.language.get(module, "LBL_MUST_RELATE_TO_A_FAMILY_OR_A_CONTACT"), function () {
      return JSON.parse(checkContactOrFamily());
    });
    addToValidateCallback(getFormName(), "stic_families_stic_goals_name", "related", false, SUGAR.language.get(module, "LBL_MUST_RELATE_TO_A_FAMILY_OR_A_CONTACT"), function () {
      return JSON.parse(checkContactOrFamily());
    });
  } else {
    if (contactInView) {
      addToValidate(getFormName(), 'stic_goals_contacts_name', 'relate', true, SUGAR.language.get(module, 'LBL_STIC_GOALS_CONTACTS_FROM_CONTACTS_TITLE'));
      addRequiredMark('stic_goals_contacts_name', 'conditional-required')
    } else if (familyInView) {
      addToValidate(getFormName(), 'stic_families_stic_goals_name', 'relate', true, SUGAR.language.get(module, 'LBL_STIC_FAMILIES_STIC_GOALS_FROM_STIC_FAMILIES_TITLE'));
      addRequiredMark('stic_families_stic_goals_name', 'conditional-required')
    }
  }
}

/**
 * Callback function to check if there is a base person or a family or both
 */
function checkContactOrFamily() {
  if (viewType() == "edit") {
    contact = getFieldValue("stic_goals_contactscontacts_ida");
    family = getFieldValue("stic_families_stic_goalsstic_families_ida");
  } else {
    // For inline edit we can only check this fields
    contact = getFieldValue("stic_goals_contacts_name");
    family = getFieldValue("stic_families_stic_goals_name");
  }

  if (contact == "" && family == "") {
    return false;
  }
  return true;
}

/**
 * FUNCTIONS TO MANAGE CUSTOM MANY2MANY RELATIONSHIPS BETWEEN GOALS
 */

//  Unset default open popup event for destination goals subpanel
$("a#getSticGoalsSticGoalsDestinationSide_select_button").off();

// Set custom event to open custom popup window
$("BODY").on("click", "a#getSticGoalsSticGoalsDestinationSide_select_button", function () {
  open_popup(
    "stic_Goals",
    800,
    600,
    "",
    true,
    true,
    {
      call_back_function: "relateDestinationGoal",
      form_name: "DetailView",
      field_to_name_array: {
        id: "sticGoalId",
      },
      passthru_data: {},
    },
    "Select",
    true
  );
});

/**
 * Custom callback function to process selected goals in popupview
 */
function relateDestinationGoal(popupReplyData) {
  // console.log(popupReplyData);

  idList = "";

  // Get value in case of single select (link click)
  if (popupReplyData.name_to_value_array && popupReplyData.name_to_value_array.sticGoalId) {
    idList = popupReplyData.name_to_value_array.sticGoalId;
  }

  // Get values in case of multiple select (and convert in single string separated by space " ")
  $.each(popupReplyData.selection_list || {}, function (index, value) {
    idList = idList + " " + value;
  });

  var obj = {
    action: "relateDestinationGoalFromPopUp",
    module: "stic_Goals",
    return_module: "stic_Goals",
    return_action: "DetailView",
    record: window.document.forms["DetailView"].record.value,
    goalIds: idList.trim(),
    select_entire_list: popupReplyData.select_entire_list,
    current_query_by_page: popupReplyData.current_query_by_page,
  };

  var url = "?index.php&" + $.param(obj);
  location.href = url;
}

//  Unset default open popup event for origin goals subpanel
$("a#getSticGoalsSticGoalsOriginSide_select_button").off();

// Set custom event to open custom popup window
$("BODY").on("click", "a#getSticGoalsSticGoalsOriginSide_select_button", function () {
  open_popup(
    "stic_Goals",
    800,
    600,
    "",
    true,
    true,
    {
      call_back_function: "relateOriginGoal",
      form_name: "DetailView",
      field_to_name_array: {
        id: "sticGoalId",
      },
      passthru_data: {},
    },
    "Select",
    true
  );
});

/**
 * Custom callback function to process selected goals in popupview
 */
function relateOriginGoal(popupReplyData) {
  idList = "";

  // Get value in case of single select (link click)
  if (popupReplyData.name_to_value_array && popupReplyData.name_to_value_array.sticGoalId) {
    idList = popupReplyData.name_to_value_array.sticGoalId;
  }

  // Get values in case of multiple select (and convert in single string separated by space " ")
  $.each(popupReplyData.selection_list || {}, function (index, value) {
    idList = idList + " " + value;
  });
  var obj = {
    action: "relateOriginGoalFromPopUp",
    module: "stic_Goals",
    return_module: "stic_Goals",
    return_action: "DetailView",
    record: window.document.forms["DetailView"].record.value,
    goalIds: idList.trim(),
    select_entire_list: popupReplyData.select_entire_list,
    current_query_by_page: popupReplyData.current_query_by_page,
  };
  var url = "?index.php&" + $.param(obj);
  location.href = url;
}

/**
 * Redirect to custom action "removeAutoRelationGoalFromSubpanel" in controller.php to remove custom goal relationship 
 * This function is called directly by custom widget in custom/include/generic/SugarWidgets/SugarWidgetSubPanelRemoveButtonstic_Goals.php
 * 
 * @param {String} subpanelName The subpanel name allows know if the goal to remove relation is Origin or Destination
 * @param {String} goalId The goal id to unlink 
 */
function removeCustomRelationManyToMany(subpanelName, goalId) {
  var obj = {
    action: "removeAutoRelationGoalFromSubpanel",
    module: "stic_Goals",
    return_module: "stic_Goals",
    return_action: "DetailView",
    main_record: window.document.forms["DetailView"].record.value,
    subpanel_record: goalId,
    subpanel_name: subpanelName,
  };
  var url = "?index.php&" + $.param(obj);
  location.href = url;
}

/**
 * END FUNCTIONS TO MANAGE CUSTOM MANY2MANY RELATIONSHIPS BETTWEN GOALS
 */
