/* HEADER */
// Set module name
var module = "stic_Medication_Log";

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

/* AUX FUNCTIONS */