/* HEADER */
// Set module name
var module = "Accounts";

/* INCLUDES */

/* VALIDATION DEPENDENCIES */

/* DIRECT VALIDATION CALLBACKS */
addToValidateCallback(getFormName(), "stic_identification_number_c", "text", false, SUGAR.language.get(module, "LBL_ACCOUNT_IDENTIFICATION_NUMBER_ERROR"), function() {
  return JSON.parse(checkAccountIdentificationNumber());
});

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":

    // Labour Insertion functionality
    if ($("#inc_incorpora_record_c", "form").is(":checked")) {
      for (var incField in STIC.incorporaRequiredFieldsArray) {
            // Exception for email field in EditView
            if (incField == 'email1') {
                incField = 'Accounts0emailAddress0';
            }
            addRequiredMark(incField, 'conditional-required-alternative');
      }
      if ($("#inc_agreement_avail_c", "form").val() === 'SI') {
        for (var incAgreementField in STIC.incorporaAgreementRequiredFieldsArray) {
          addRequiredMark(incAgreementField, 'conditional-required-alternative');
        }
      }
    }

    $("#inc_incorpora_record_c", "form").on("change", function() {
      if ($("#inc_incorpora_record_c", "form").is(":checked")) {
        for (var incField in STIC.incorporaRequiredFieldsArray) {
            // Exception for email field in EditView
            if (incField == 'email1') {
                incField = 'Accounts0emailAddress0';
            }
            addRequiredMark(incField, 'conditional-required-alternative');
        }
        if ($("#inc_agreement_avail_c", "form").val() === 'SI') {
          for (var incAgreementField in STIC.incorporaAgreementRequiredFieldsArray) {
            addRequiredMark(incAgreementField, 'conditional-required-alternative');
          }
        }
      } 
      else {
        for (var incField in STIC.incorporaRequiredFieldsArray) {
            // Exception for email field in EditView
            if (incField == 'email1') {
                incField = 'Accounts0emailAddress0';
            }
            removeRequiredMark(incField, 'conditional-required-alternative');
        }
        for (var incAgreementField in STIC.incorporaAgreementRequiredFieldsArray) {
          removeRequiredMark(incAgreementField, 'conditional-required-alternative');
        }
      }
    });
    
    $("#inc_agreement_avail_c", "form").on("change", function() {
      if ($("#inc_agreement_avail_c", "form").val() === 'SI' && $("#inc_incorpora_record_c", "form").is(":checked")) {
        for (var incField in STIC.incorporaAgreementRequiredFieldsArray) {
            addRequiredMark(incField, 'conditional-required-alternative');
        }
      } 
      else {
        for (var incField in STIC.incorporaAgreementRequiredFieldsArray) {
            removeRequiredMark(incField, 'conditional-required-alternative');
        }
      }
    });

    break;

  case "detail":
    // Labour Insertion functionality
    recordId = $("#formDetailView input[type=hidden][name=record]").val();
    // Define button content
    var buttons = {
      syncIncorpora: {
        id: "bt_sync_incorpora_detailview",
        title: SUGAR.language.get("app_strings", "LBL_INCORPORA_BUTTON_TITTLE"),
        onclick: "window.location='index.php?module=stic_Incorpora&action=fromDetailView&record=" + recordId + "&return_module="+ module +"'"
      },
      pdfEmail: {
        id: "bt_pdf_email_detailview",
        title: SUGAR.language.get("app_strings", "LBL_EMAIL_PDF_ACTION_BUTTON"),
        onclick: "showPopupPdfEmail()"
      }
    };
    createDetailViewButton(buttons.syncIncorpora);
    createDetailViewButton(buttons.pdfEmail);

    break;

  case "list":
    button = {
      id: "bt_sync_incorpora_listview",
      title: SUGAR.language.get("app_strings", "LBL_INCORPORA_BUTTON_TITTLE"),
      text: SUGAR.language.get("app_strings", "LBL_INCORPORA_BUTTON_TITTLE"),
      onclick: "onClickIncorporaSyncButton()",
    };

    createListViewButton(button);
    break;

  default:
    break;
}


/**
 * Check the stic_identification_number_c field
 */
function checkAccountIdentificationNumber() {
  var identificationNumberValue = getFieldValue("stic_identification_number_c");
  return trim(identificationNumberValue) == "" ? true : isValidCif(identificationNumberValue);
}

/**
 * Used as a callback for the Incorpora Synchronization button
 */
function onClickIncorporaSyncButton() {
  sugarListView.get_checks();
  if(sugarListView.get_checks_count() < 1) {
      alert(SUGAR.language.get('app_strings', 'LBL_LISTVIEW_NO_SELECTED'));
      return false;
  }
  document.MassUpdate.action.value='fromMassUpdate';
  document.MassUpdate.module.value='stic_Incorpora';
  document.MassUpdate.submit();
}