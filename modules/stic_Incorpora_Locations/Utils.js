/* HEADER */
// Set module name
var module = "stic_Evstic_Incorpora_Locationsents";

/* INCLUDES */
// Load moment.js to use in validations

/* VALIDATION DEPENDENCIES */
var validationDependencies = {};

/* VALIDATION CALLBACKS */

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
    break;
  case "detail":
    $("#tab-actions").hide();
    break;
  case "list":
    break;

  default:
    break;
}

/* AUX FUNCTIONS */
