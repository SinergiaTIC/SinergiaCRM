/* HEADER */
// Set module name
var module = "stic_Settings";

/* INCLUDES */


/* VALIDATION DEPENDENCIES */

/* VALIDATION CALLBACKS */

/* VIEWS CUSTOM CODE */

/* AUX. FUNCTIONS */

switch (viewType()) {
  case "edit":
  case "quickcreate":
    if (getFieldValue('name') == 'GENERAL_CUSTOM_THEME_COLOR') {
      $('input#value').attr('type', 'color')
    }


    break;
  case "detail":

    $('body').on('focus', '.inlineEditActive input#value', function () {
      if (getFieldValue('name') == 'GENERAL_CUSTOM_THEME_COLOR') {
        $(this).attr('type', 'color')
      }
    })
    break;

  case "list":
    $('body').on('focus', '.inlineEditActive input#value', function () {
      if (getFieldValue('name') == 'GENERAL_CUSTOM_THEME_COLOR') {
        $(this).attr('type', 'color')
      }
    })

    break;


  default:
    break;
}



