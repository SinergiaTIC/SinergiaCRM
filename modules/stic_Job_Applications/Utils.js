/* HEADER */
// Set module name
var module = "stic_Job_Applications";

/* INCLUDES */
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
  end_date: "start_date",
  start_date: "end_date",
  contract_end_date: "contract_start_date",
  contract_start_date: "contract_end_date",
  start_date: "contract_start_date",
  contract_start_date: "start_date",
};

/* VALIDATION CALLBACKS */
addToValidateCallback(getFormName(), "end_date", "date", false, SUGAR.language.get(module, "LBL_END_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "end_date");
});
addToValidateCallback(getFormName(), "start_date", "date", false, SUGAR.language.get(module, "LBL_START_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "end_date");
});
addToValidateCallback(getFormName(), "contract_end_date", "date", false, SUGAR.language.get(module, "LBL_CONTRACT_END_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("contract_start_date", "contract_end_date");
});
addToValidateCallback(getFormName(), "contract_start_date", "date", false, SUGAR.language.get(module, "LBL_CONTRACT_START_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("contract_start_date", "contract_end_date");
});
addToValidateCallback(getFormName(), "start_date", "date", false, SUGAR.language.get(module, "LBL_START_DATE_CONTRACT_START_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "contract_start_date");
});
addToValidateCallback(getFormName(), "contract_start_date", "date", false, SUGAR.language.get(module, "LBL_CONTRACT_START_DATE_START_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "contract_start_date");
});

/* VIEWS CUSTOM CODE */

switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    // Definition of the behavior of fields that are conditionally enabled or disabled
    rejectedReasons = {
      rejected_closed: {
        enabled: ["rejection_reason"],
        disabled: [],
      },
      default: {
        enabled: [],
        disabled: ["rejection_reason"],
      },
    };

    setCustomStatus(rejectedReasons, $("#status", "form").val());

    $("form").on("change",'#status', function () {
      clear_all_errors();
      setCustomStatus(rejectedReasons, $("#status", "form").val());
    });
    
    setAutofill(["name"]);

    break;
  case "detail":
    break;
  case "list":
    break;

  default:
    break;
}