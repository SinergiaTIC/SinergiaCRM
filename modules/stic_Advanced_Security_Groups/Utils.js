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
      // Initialize selectize for specific select elements
      $('select[name=name], select#non_inherit_from_security_groups').selectize();
      $('select#inherit_from_modules').selectize({
        onInitialize: function () {
          // Trigger a change event upon initialization
          this.trigger('change', this.getValue(), true);
        },
        onChange: function (value, isOnInitialize) {
          // Handle the change event for selectize
          if (value.length > 0) {
            // Uncheck the checkbox if any selectize item is selected
            $('#inherit_parent').prop('checked', false);
          }
        }
      })

      // Additional document ready function to handle checkbox state change
      $(document).ready(function () {
        // Event listener for checkbox state change
        $('#inherit_parent').change(function () {
          // Clear the selectize input if checkbox is checked
          if (this.checked) {
            $('#inherit_from_modules')[0].selectize.clear()
          }
        });


      });
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
