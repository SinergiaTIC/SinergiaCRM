/* HEADER */
// Set module name
var module = "stic_Accounts_Relationships";

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

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    // Set autofill mark beside field label
    setAutofill(["name"]);
    break;

  case "detail":
    break;

  case "list":
    break;

  default:
    break;
}

/* AUX FUNCTIONS */
