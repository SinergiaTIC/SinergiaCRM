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

/*
 * This script provides client-side functionality for the stic_Signers module,
 * including dynamic button creation for detail views and AJAX calls for signature previews.
 */

/* HEADER */
// Set module name for localization and context
var module = "stic_Signers";

/* INCLUDES */
// Load moment.js library for date and time manipulations, if needed for future validations.
loadScript("include/javascript/moment.min.js");

/* VALIDATION DEPENDENCIES */
// Currently, there are no specific validation dependencies defined in this script.
var validationDependencies = {};

/* VALIDATION CALLBACKS */
// No validation callbacks are defined in this section for the stic_Signers module in this script.

/* VIEWS CUSTOM CODE */
// Apply specific JavaScript logic based on the current view type (e.g., edit, detail, list).
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    // No specific custom logic for edit, quickcreate, or popup views in this section.
    break;
  case "detail":
    // CReate buttons for the detail view of the stic_Signers module.
    var buttons = {
      sendToSign: {
        id: "bt_send_to_sign", 
        title: "✉️ " + SUGAR.language.get("stic_Signers", "LBL_SIGNER_SEND_TO_SIGN_BY_EMAIL") + " " + STIC.record.email_address, // Localized button title with email emoji   
        onclick: "window.location='index.php?module=stic_Signers&action=sendToSign&signerId=" + STIC.record.id + "'"
      },
      rediretToSingPortal: {
        id: "bt_redirect_to_portal", 
        title:  "🔗 " + SUGAR.language.get("stic_Signers", "LBL_SIGNER_REDIRECT_TO_PORTAL") , // Localized button title with link emoji 
        onclick: "window.location='index.php?entryPoint=sticSign&signerId=" + STIC.record.id + "'"
      },
      copyPortalUrl: {
        id: "bt_copy_portal_url", 
        title: "📋 " + SUGAR.language.get("stic_Signers", "LBL_SIGNER_COPY_PORTAL_URL") , // Localized button title with clipboard emoji
        onclick: "navigator.clipboard.writeText('" + window.location.origin+window.location.pathname + "?entryPoint=sticSign&signerId=" + STIC.record.id + "'); alert(SUGAR.language.get('stic_Signers', 'LBL_SIGNER_PORTAL_URL_COPIED'));"
      }
    };
    // Add the defined button to the detail view.
    createDetailViewButton(buttons.sendToSign);
    createDetailViewButton(buttons.rediretToSingPortal);
    createDetailViewButton(buttons.copyPortalUrl);

    break;
  case "list":
    // No specific custom logic for list view in this section.
    break;
  default:
    // Default case for any other view types not explicitly handled.
    break;
}

/* AUX. FUNCTIONS */

/**
 * Initiates an AJAX request to fetch and display a preview of the signature document.
 * The preview is based on the current signer's ID and is rendered within a designated container.
 */
function previewSignature() {
  // Get the signer ID from the global STIC object, which holds current record details.
  var signerId = STIC.record.id;

  // Perform an AJAX call to the 'getPreview' action of the 'stic_Signatures' module.
  $.ajax({
    // Dynamically determines the base URL of the SugarCRM instance.
    url: location.href.slice(0, location.href.indexOf(location.search)),
    type: "POST", // Use POST method for sending data
    data: {
      module: "stic_Signatures", // Target module for the AJAX request
      action: "getPreview",      // Specific action to call within the controller
      signerId: signerId,        // Pass the current signer's ID
    },
    success: function (response) {
      // On successful response, inject the received HTML content into the '#preview-container' element.
      $("#preview-container").html(response);
    },
    error: function (xhr, status, error) {
      // Log any errors that occur during the AJAX request.
      console.error("Request error:", status, error);
    }
  });
}