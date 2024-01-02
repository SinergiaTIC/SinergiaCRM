/* HEADER */
// Set module name
var module = "stic_Advanced_Security_Groups";

/* INCLUDES */

/* VALIDATION DEPENDENCIES */

/* VALIDATION CALLBACKS */

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":


    $(document).ready(function () {
      // convert to selectize
      $('select[name=name], select#inherit_from_modules, select#non_inherit_from_security_groups').selectize();
    })

    break;
  case "detail":
    break;

  case "list":

    $(document).ready(function () {
      // disable some list menu actions
      var selectorsToKeep = ['#massupdate_listview_top', '#export_listview_top', '#delete_listview_top'];

      // remove duplicate massive link which has not a uniq id
      $('#actionLinkTop > li > ul > li:nth-child(2) > a#massupdate_listview_top').closest('li').remove();

      $('ul#actionLinkTop li.sugar_action_button ul li').each(function () {
        var containsSelector = false;
        for (var i = 0; i < selectorsToKeep.length; i++) {
          if ($(this).find(selectorsToKeep[i]).length > 0) {
            containsSelector = true;
            break;
          }
        }
        if (!containsSelector) {
          $(this).remove();
        }
      });
    });





    break;

  default:
    break;
}
