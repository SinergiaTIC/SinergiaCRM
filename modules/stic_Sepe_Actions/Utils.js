/* HEADER */
// Set module name
var module = "stic_Sepe_Actions";

/* INCLUDES */
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
    type: "agreement",
    agreement: "type",
    end_date: "type",
    end_date: "start_date",
    start_date: "end_date",
};

/* VALIDATION CALLBACKS */

// In SEPE Actions the date fields can be set on the same day
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
        typeStatus = {
            A: {
                enabled: ["agreement"],
                disabled: [],
            },
            default: {
                enabled: [],
                disabled: ["agreement"],
            },
        };
        setCustomStatus(typeStatus, $("#type", "form").val());

        $("form").on("change", "#type", function () {
            clear_all_errors();
            setCustomStatus(typeStatus, $("#type", "form").val());
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
