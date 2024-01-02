/* HEADER */
// Set module name
var module = "stic_Sepe_Files";

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
    agreement: "type",
    type: "agreement",

};

/* VALIDATION CALLBACKS */

validateFunctions.agreement = function () {
    addToValidateCallback(
        getFormName(),
        "agreement",
        "text",
        false,
        SUGAR.language.get(module, "LBL_AGREEMENT_EMPTY_ERROR"),
        function () {
            type = getFieldValue("type", "stic_sepe_file_types_list");
            typeValidation = ["monthly_accd", "monthly_acci", "annual_accd"];
            if (
                (typeValidation.indexOf(type) >= 0) &&
                !getFieldValue("agreement", "stic_sepe_agreement_list")
            ) {
                return false;
            } 
        }
    );
};
validateFunctions.agreement1 = function () {
    addToValidateCallback(
        getFormName(),
        "agreement",
        "text",
        false,
        SUGAR.language.get(module, "LBL_AGREEMENT_ERROR"),
        function () {
            type = getFieldValue("type", "stic_sepe_file_types_list");
            typeValidation = ["monthly_accd", "monthly_acci", "annual_accd"];
            if (
                !(typeValidation.indexOf(type) >= 0) &&
                getFieldValue("agreement", "stic_sepe_agreement_list")
            ) {
                return false;
            } 
        }
    );
};
validateFunctions.typeACC = function () {
    addToValidateCallback(
        getFormName(),
        "type",
        "text",
        ["edit", "quickcreate"].indexOf(viewType()) >= 0,
        SUGAR.language.get(module, "LBL_AGREEMENT_EMPTY_ERROR"),
        function () {
            type = getFieldValue("type", "stic_sepe_file_types_list");
            typeValidation = ["monthly_accd", "monthly_acci", "annual_accd"];
            if (
                (typeValidation.indexOf(type) >= 0) &&
                !getFieldValue("agreement", "stic_sepe_agreement_list")
            ) {
                return false;
            } 
        }
    );
};

validateFunctions.typeAC = function () {
    addToValidateCallback(
        getFormName(),
        "type",
        "text",
        ["edit", "quickcreate"].indexOf(viewType()) >= 0,
        SUGAR.language.get(module, "LBL_AGREEMENT_ERROR"),
        function () {
            type = getFieldValue("type", "stic_sepe_file_types_list");
            typeValidation = ["monthly_accd", "monthly_acci", "annual_accd"];
            if (
                !(typeValidation.indexOf(type) >= 0) &&
                getFieldValue("agreement", "stic_sepe_agreement_list")
            ) {
                return false;
            } 
        }
    );
};

/* VIEWS CUSTOM CODE */
switch (viewType()) {
    case "edit":
    case "quickcreate":
    case "popup":        
        // Definition of the behavior of fields that are conditionally enabled or disabled
        typeStatus = {
            monthly_accd: {
                enabled: ["agreement"],
                disabled: [],
            },
            monthly_acci: {
                enabled: ["agreement"],
                disabled: [],
            },
            annual_accd: {
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
        $recordId = $("#formDetailView input[type=hidden][name=record]").val();
        // Define button content
        var buttons = {
            xmlGenerator: {
                id: "bt_generateSepeFile_detailview",
                title: SUGAR.language.get(
                    "stic_Sepe_Files",
                    "LBL_GENERATE_XML_FILE"
                ),
                onclick:
                    "window.location='index.php?module=stic_Sepe_Files&action=generateSepeFile&record=" +
                    $recordId +
                    "'",
            },
        };
        createDetailViewButton(buttons.xmlGenerator);
        break;

    case "list":
        break;

    default:
        break;
}
