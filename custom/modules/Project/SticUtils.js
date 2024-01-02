/* HEADER */
// Set module name
var module = "Project";

/* INCLUDES */
// Load moment.js to use in validations
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
  estimated_start_date: "estimated_end_date",
  estimated_end_date: "estimated_start_date",
};

/* DIRECT VALIDATION CALLBACKS */
addToValidateCallback(getFormName(), "estimated_start_date", "date", false, SUGAR.language.get(module, "LBL_ESTIMATED_START_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("estimated_start_date", "estimated_end_date");
});

addToValidateCallback(getFormName(), "estimated_end_date", "date", false, SUGAR.language.get(module, "LBL_ESTIMATED_END_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("estimated_start_date", "estimated_end_date");
});

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
    break;

  case "detail":
    break;

  case "list":
    break;

  default:
    break;
}

/* AUX FUNCTIONS */
