/**
 * Transform a Javascript Date into a DB date format
 */
function dateToYMDHM(date) {
    var d = date.getDate();
    var m = date.getMonth() + 1;
    var y = date.getFullYear();
    var h = date.getHours();
    var min = date.getMinutes();
    return "" + y + "-" + (m <= 9 ? "0" + m : m) + "-" + (d <= 9 ? "0" + d : d) + " " + h + ":" + (min <= 9 ? "0" + min : min) + ":00";
}

/**
 * Returns the name of the active form (used only for editable views)
 */
function getFormName() {
    var formNames = ["form_DCQuickCreate_" + module, "form_SubpanelQuickCreate_" + module, "form_QuickCreate_" + module, "EditView"];
    var form = null;
    for (var i = 0; i < formNames.length && !form; i++) {
      form = document.forms[formNames[i]];
    }
    return form != null ? form.id : "EditView";
  }

  /**
  * Return key value for label in app_list_stringsName array
  *
  * @param String app_list_stringsName $app_list_string to search in
  * @param String label The label to be searched (in current language)
  */
 function getListValueFromLabel(app_list_stringsName, label) {
   var res;
   $.each(SUGAR.language.languages.app_list_strings[app_list_stringsName], function (l, k) {
     if (k == label) {
       res = l;
     }
   });
   return res;
 }

 /**
 * Get the view type
 */
function viewType() {
  if ($(".listViewBody").length == 1) {
    return "list";
  } else if ($(".sub-panel .quickcreate form").length == 1) {
    return "quickcreate";
  } else if ($(".detail-view").length == 1) {
    return "detail";
  } else if ($("form[name=EditView]").length == 1) {
    return "edit";
  } else if ($("#popup_query_form").length == 1) {
    return "popup";
  }
}

  /**
 * Get the value of a field in any of the edit|detail|list views (view-dependent function).
 * In case of enum type fields value will be get through the visible label.
 *
 * @param String fieldName Required The name of the field
 * @param String listName Required when type is enum
 * @return String found value or '' if a value has not been found for any reason.
 */
function getFieldValue(fieldName, listName) {
    // If fieldName is the active inline edit field, obtain the value directly
    $activeField = $(".inlineEditActive [name=" + fieldName + "]");
    if ($activeField.length == 1) {
      var res = $activeField.val();
      return res === undefined ? '' : res;
    }
  
    switch (viewType()) {
      case "edit":
        var res = $("form#EditView #" + fieldName).val();
        return res === undefined ? '' : res;
  
      case "quickcreate":
        var res = $("#" + fieldName, ".sub-panel .quickcreate form").val();
        return res === undefined ? '' : res;
  
      case "popup":
        var res = $("#" + fieldName, "form .edit-view-row").val();
        return res === undefined ? '' : res;
  
      case "detail":
        var $field = $(".detail-view-field[field=" + fieldName + "]");
        if ($field.length == 1) {
          var fieldType = $field.attr("type");
          if (fieldType == "enum") {
            if (!listName) {
              console.error("In enum type fields it is necessary to indicate the full name of the list");
              return '';
            }
            var res = getListValueFromLabel(listName, trim($field.text()));
            return res === undefined ? '' : res;
          } else {
            var res = trim($field.text());
            return res === undefined ? '' : res;
          }
        } else {
          console.error("It was not possible to obtain the value of the field [" + fieldName + "]");
          return '';
        }
  
      case "list":
        if ($(".inlineEditActive").length === 0) {
          console.error("The getFieldValue() function has been called in a list, but there is no active inline-edit field, so it cannot be evaluated");
          return '';
        }
  
        var $field = $("td[field=" + fieldName + "] ", $(".inlineEditActive").closest("tr"));
  
        if ($field.length == 1) {
          if ($field.closest("form[name=EditView]") > 0) {
            return $field.val() === undefined ? '' : $field.val();
          } else {
            var fieldType = $field.attr("type");
            if (fieldType == "enum") {
              var res = getListValueFromLabel(listName, trim($field.text()));
              if (!listName) {
                console.error("In enum type fields it is necessary to indicate the full name of the list");
              }
              return res === undefined ? '' : res;
            } else {
              var res = trim($field.text());
              return res === undefined ? '' : res;
            }
          }
        } else {
          console.error("It was not possible to obtain the value of the field [" + fieldName + "]");
          return '';
        }
  
      default:
        return '';
    }
  }
  