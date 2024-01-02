/* HEADER */
// Set module name
var module = "stic_Assessments";

/* INCLUDES */
// Load moment.js to use in validations
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
  next_date: "assessment_date",
  assessment_date: "next_date",
};

/* VALIDATION CALLBACKS */
addToValidateCallback(getFormName(), "next_date", "date", false, SUGAR.language.get(module, "LBL_NEXT_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("assessment_date", "next_date");
});
addToValidateCallback(getFormName(), "assessment_date", "date", false, SUGAR.language.get(module, "LBL_ASSESSMENT_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("assessment_date", "next_date");
});

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    // Set autofill mark beside field label
    setAutofill(["name"]);
    contactInView = $("#stic_assessments_contactscontacts_ida").length;
    familyInView = $("#stic_families_stic_assessmentsstic_families_ida").length;
    addValidationsAccordingToContactOrFamily(contactInView, familyInView);
    break;

  case "detail":
    contactInView = $("#stic_assessments_contactscontacts_ida").length;
    familyInView = $("#stic_families_stic_assessmentsstic_families_ida").length;
    addValidationsAccordingToContactOrFamily(contactInView, familyInView);
    break;

  case "list":
    contactInView = $('[field=stic_assessments_contacts_name]').length;
    familyInView = $('[field=stic_families_stic_assessments_name]').length;
    addValidationsAccordingToContactOrFamily(contactInView, familyInView);
    break;

  default:
    break;
}

/* AUX FUNCTIONS */
/**
 * Add validations for contact or family field
 */
function addValidationsAccordingToContactOrFamily(contactInView, familyInView) {
  if (contactInView && familyInView) {
    validationDependencies['stic_assessments_contacts_name'] = "stic_families_stic_assessments_name";
    validationDependencies['stic_families_stic_assessments_name'] = "stic_assessments_contacts_name";

    addToValidateCallback(getFormName(), "stic_assessments_contacts_name", "related", false, SUGAR.language.get(module, "LBL_MUST_RELATE_TO_A_FAMILY_OR_A_CONTACT"), function () {
      return JSON.parse(checkContactOrFamily());
    });
    addToValidateCallback(getFormName(), "stic_families_stic_assessments_name", "related", false, SUGAR.language.get(module, "LBL_MUST_RELATE_TO_A_FAMILY_OR_A_CONTACT"), function () {
      return JSON.parse(checkContactOrFamily());
    });
  } else {
    if (contactInView) {
      addToValidate(getFormName(), 'stic_assessments_contacts_name', 'relate', true, SUGAR.language.get(module, 'LBL_STIC_ASSESSMENTS_CONTACTS_FROM_CONTACTS_TITLE'));
      addRequiredMark('stic_assessments_contacts_name', 'conditional-required')
    } else if (familyInView) {
      addToValidate(getFormName(), 'stic_families_stic_assessments_name', 'relate', true, SUGAR.language.get(module, 'LBL_STIC_FAMILIES_STIC_ASSESSMENTS_FROM_STIC_FAMILIES_TITLE'));
      addRequiredMark('stic_families_stic_assessments_name', 'conditional-required')
    }
  }
}

/**
 * Callback function to check if there is a base person or a family or both
 */
function checkContactOrFamily() {
  if (viewType() == "edit") {
    contact = getFieldValue("stic_assessments_contactscontacts_ida");
    family = getFieldValue("stic_families_stic_assessmentsstic_families_ida");
  } else {
    // For inline edit we can only check this fields
    contact = getFieldValue("stic_assessments_contacts_name");
    family = getFieldValue("stic_families_stic_assessments_name");
  }

  if (contact == "" && family == "") {
    return false;
  }
  return true;
}