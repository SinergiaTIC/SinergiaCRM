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
var module = "stic_Signers";

/* INCLUDES */
// Load moment.js to use in validations
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
var validationDependencies = {

};

/* VALIDATION CALLBACKS */

/* VIEWS CUSTOM CODE */

/* AUX. FUNCTIONS */

switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":

    break;
  case "detail":
    var buttons = {
      sendToSign: {
        id: "bt_send_to_sign",
        title: SUGAR.language.get("stic_Signers", "LBL_SIGNER_SEND_TO_SIGN_BY_EMAIL"),
        onclick: "window.location='index.php?module=stic_Signers&action=sendToSign&signerId=" + STIC.record.id + "'"
      }
    }
    createDetailViewButton(buttons.sendToSign);
    break;
  case "list":

    break;
  default:
    break;
}





function previewSignature() {
  // Get the form name
  var signerId = STIC.record.id;

  // get html preview
  $.ajax({
    url: location.href.slice(0, location.href.indexOf(location.search)),
    type: "POST",
    data: {
      module: "stic_Signatures",
      action: "getPreview",
      signerId: signerId,
    },
    success: function (response) {
      $("#preview-container").html(response);
    },
    error: function (xhr, status, error) {
      console.error("Request error:", status, error);
    }
  });


}