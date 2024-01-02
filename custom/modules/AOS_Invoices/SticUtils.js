/* HEADER */
// Set module name
var module = "AOS_Invoices";

/* INCLUDES */
// Load moment.js to use in validations
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
  invoice_date: "due_date",
  due_date: "invoice_date",
};

/* DIRECT VALIDATION CALLBACKS */
addToValidateCallback(getFormName(), "invoice_date", "date", false, SUGAR.language.get(module, "LBL_INVOICE_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("invoice_date", "due_date");
});

addToValidateCallback(getFormName(), "due_date", "date", false, SUGAR.language.get(module, "LBL_DUE_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("invoice_date", "due_date");
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
