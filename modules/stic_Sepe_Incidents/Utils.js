/* HEADER */
// Set module name
var module = "stic_Sepe_Incidents";

/* INCLUDES */

/* VALIDATION DEPENDENCIES */
var validationDependencies = {};

/* VALIDATION CALLBACKS */

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    setAutofill(["name"]);
    break;

  case "detail":
    break;

  case "list":
    break;

  default:
    break;
}
