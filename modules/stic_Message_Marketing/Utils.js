/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */
/* HEADER */
// Set module name
var module = "stic_Message_Marketing";
var inputsWithErrors2 = new Array();

addToValidateCallback(
    getFormName(),
    "prospect_lists",
    "enum",
    false,
    SUGAR.language.get(module, "LBL_MUST_SELECT_ALL_OR_CHOSEN_LISTS"),
    function() {
      console.log('popoopopopo');
        return JSON.parse(checkAllOrSelectedLists());
    }
);


/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    function toggle_message_for() {
        const isChecked = $("#select_all").is(":checked");
        multiSelect = $("#prospect_lists");
        if (isChecked) {
          multiSelect.prop("disabled", "disabled");
          multiSelect.css("background-color", "#bcbcbc");
        } else {
          multiSelect.prop("disabled", "");
          multiSelect.css("background-color", "");
        }
    }
    $(document).ready(function() {
      addEditCreateTemplateLinks();
      $("#select_all").on("click", toggle_message_for);
      toggle_message_for();
      var baseURL = 'index.php';

    // Create an object with all the parameters
      var paramsPost = {
        module: 'stic_Message_Marketing',
        action: 'getDefaultSender',
      };
    });
    if (viewType() == "quickcreate") {
      // Disabling the "Campaign" field relationship
      $("#campaigns_stic_message_marketing_name").prop("disabled", "disabled").css("background-color", "#bcbcbc").prop("readonly", "readonly");
      $("#btn_campaigns_stic_message_marketing_name").prop("disabled", "disabled");
      $("#btn_clr_campaigns_stic_message_marketing_name").prop("disabled", "disabled");
    }


    break;

  case "detail":
    break;

  case "list":
    break;

  default:
    break;
}

/**
 * Check if there is a person or a account or both
 */
function checkAllOrSelectedLists() {
    checkFlag = $("#select_all").is(':checked');
    numListsSelected = $("#prospect_lists").find(':selected').length;

    if (checkFlag || numListsSelected > 0) {
        return true;
    }
    return false;
}

function addEditCreateTemplateLinks() {
  if ($("#template_id_edit_link").length == 0) {
    var $select = $("#template_id");
    var $div = $select.parent();

    $select.css("width","50%");

    var editText = SUGAR.language.translate("app_strings", "LNK_EDIT");
    var $editLink = $('<a href="#" id="template_id_edit_link" style="margin-left:10px;">'+editText+'</a>').on("click", function(e) {
      e.preventDefault();
      edit_email_template_form();
    });
    $div.append($editLink);

    var createText = SUGAR.language.translate("app_strings", "LNK_CREATE");
    var $createLink = $('<a href="#" id="template_id_create_link" style="margin-left:10px;">'+createText+'</a>').on("click", function(e) {
      e.preventDefault();
      open_email_template_form();
    });
    $div.append($createLink);
  }
}
