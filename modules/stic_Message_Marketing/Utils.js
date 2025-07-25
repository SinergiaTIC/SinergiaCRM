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


addToValidateCallback(
  getFormName(),
  "stic_payment_commitments_contacts_name",
  "related",
  false,
  SUGAR.language.get(module, "LBL_MUST_RELATE_TO_AN_ACCOUNT_OR_A_CONTACT"),
  function() {
      return JSON.parse(checkPCContactOrAccount());
  }
);


/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    debugger;
    setAutofill(["name"]);
    function toggle_message_for() {
      debugger;
        const isChecked = $("#select_all").is(":checked");
        // multiSelect = document.getElementById('prospect_lists');
        multiSelect = $("#prospect_lists");
        if (isChecked) {
          multiSelect.prop("disabled", "disabled");
          multiSelect.css("background-color", "#bcbcbc");
        } else {
          // multiSelect.disabled = false;
          multiSelect.prop("disabled", "");
          multiSelect.css("background-color", "");
        }
    }
    // $("select_all").click(function(){ myFunction(); });
    debugger;
    $(document).ready(function() {
      $("#select_all").on("click", toggle_message_for);
      toggle_message_for();
      var baseURL = 'index.php';

    // Create an object with all the parameters
      var paramsPost = {
        module: 'stic_Message_Marketing',
        action: 'getDefaultSender',
      };
      debugger;
        $.ajax({
          url: baseURL,
          type: 'POST',
          data: paramsPost,
          success: function(data) {
            debugger;
            let parsedData = JSON.parse(data);
            $("#sender").val(parsedData.data.defaultSender)
          },
          error: function(xhr, status, error) {
              // Your error handler here
          }
        });
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

/* AUX FUNCTIONS */
/**
 * Check if there is a person or a account or both
 */
function checkSelectAllOrProspectListSelected() {
  if (viewType() == "edit") {
      contact = getFieldValue("stic_payment_commitments_contactscontacts_ida");
      account = getFieldValue("stic_payment_commitments_accountsaccounts_ida");
  } else {
      // For inline edit we can only check this fields
      contact = getFieldValue("stic_payment_commitments_contacts_name");
      account = getFieldValue("stic_payment_commitments_accounts_name");
  }

  if ((contact != "" && account != "") || (contact == "" && account == "")) {
      return false;
  }
  return true;
}