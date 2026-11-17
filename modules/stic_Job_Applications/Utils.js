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
var module = "stic_Job_Applications";

/* INCLUDES */
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {
  end_date: "start_date",
  start_date: "end_date",
  contract_end_date: "contract_start_date",
  contract_start_date: "contract_end_date",
  start_date: "contract_start_date",
  contract_start_date: "start_date",
};

/* VALIDATION CALLBACKS */
addToValidateCallback(getFormName(), "end_date", "date", false, SUGAR.language.get(module, "LBL_END_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "end_date");
});
addToValidateCallback(getFormName(), "start_date", "date", false, SUGAR.language.get(module, "LBL_START_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "end_date");
});
addToValidateCallback(getFormName(), "contract_end_date", "date", false, SUGAR.language.get(module, "LBL_CONTRACT_END_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("contract_start_date", "contract_end_date");
});
addToValidateCallback(getFormName(), "contract_start_date", "date", false, SUGAR.language.get(module, "LBL_CONTRACT_START_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("contract_start_date", "contract_end_date");
});
addToValidateCallback(getFormName(), "start_date", "date", false, SUGAR.language.get(module, "LBL_START_DATE_CONTRACT_START_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "contract_start_date");
});
addToValidateCallback(getFormName(), "contract_start_date", "date", false, SUGAR.language.get(module, "LBL_CONTRACT_START_DATE_START_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "contract_start_date");
});

/* VIEWS CUSTOM CODE */

switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    // Definition of the behavior of fields that are conditionally enabled or disabled
    rejectedReasons = {
      rejected_closed: {
        enabled: ["rejection_reason"],
        disabled: [],
      },
      default: {
        enabled: [],
        disabled: ["rejection_reason"],
      },
    };

    setCustomStatus(rejectedReasons, $("#status", "form").val());

    $("form").on("change",'#status', function () {
      clear_all_errors();
      setCustomStatus(rejectedReasons, $("#status", "form").val());
    });
    
    setAutofill(["name"]);

    // On new records, prefill assigned user from the selected offer so UI and saved value match
    if (typeof STIC !== "undefined" && STIC.record && !STIC.record.id) {
      const offerIdField = "stic_job_applications_stic_job_offersstic_job_offers_ida";
      const assignedUserIdField = "assigned_user_id";
      const assignedUserNameField = "assigned_user_name";
      let assignedUserManuallyChanged = false;

      function getOfferAssignedUser(offerId, callbackFunction) {
        $.ajax({
          url: "index.php?module=stic_Job_Applications&action=getOfferAssignedUser",
          type: "post",
          dataType: "json",
          data: { offerId: offerId },
          success: function (result) {
            if (result && result.code == "OK" && result.data) {
              callbackFunction(result.data);
            }
          },
        });
      }

      function prefillAssignedUserFromOffer() {
        const offerId = $("#" + offerIdField).val();
        if (!offerId) {
          return;
        }
        getOfferAssignedUser(offerId, function (data) {
          if (
            data &&
            data.assigned_user_id &&
            !assignedUserManuallyChanged &&
            $("#" + offerIdField).val() === offerId
          ) {
            $("#" + assignedUserIdField).val(data.assigned_user_id);
            $("#" + assignedUserNameField).val(data.assigned_user_name);
          }
        });
      }

      // Any change to the assigned user field is considered a manual change
      YAHOO.util.Event.addListener(assignedUserIdField, "change", function () {
        assignedUserManuallyChanged = true;
      });

      YAHOO.util.Event.addListener(offerIdField, "change", prefillAssignedUserFromOffer);

      // If the offer is already set (e.g. new record created from an offer subpanel), prefill on load
      prefillAssignedUserFromOffer();
    }

    break;
  case "detail":
    break;
  case "list":
    break;

  default:
    break;
}