/* HEADER */
// Set module name
var module = "stic_Sessions";

/* INCLUDES */
// Load moment.js to use in validations
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
  start_date: "end_date",
  end_date: "start_date",
};

/* VALIDATION CALLBACKS */
addToValidateCallback(getFormName(), "start_date", "date", false, SUGAR.language.get(module, "LBL_START_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "end_date", true);
});

addToValidateCallback(getFormName(), "end_date", "date", false, SUGAR.language.get(module, "LBL_END_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "end_date", true);
});

/* VIEWS CUSTOM CODE */

/* AUX. FUNCTIONS */

switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    setAutofill(["name"]);

    // Adding color dots to "color" enum field
    buildEditableColorFieldSelectize('color');
    break;
  case "detail":
    // Adding color dots to "color" enum field
    buildDetailedColorFieldSelectize('color');
    break;
  case "list":
    // Adding color dots to "color" enum field
    // Check both massupdate and both filters, basic and advanced.
    buildEditableColorFieldSelectize('mass_color');
    buildEditableColorFieldSelectize('color_basic');
    buildEditableColorFieldSelectize('color_advanced');
    break;
  default:
    break;
}
