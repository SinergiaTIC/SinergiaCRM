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

    break;
  case "detail":
    // CReate buttons for the detail view of the stic_Signers module.
    var buttons = {
      sendToSign: {
        id: "bt_send_to_sign",
        title: SUGAR.language.get("stic_Signers", "LBL_SIGNER_SEND_TO_SIGN_BY_EMAIL"),
        onclick: "window.location='index.php?module=stic_Signers&action=sendToSign&signerId=" + STIC.record.id + "'"
      },
      rediretToSingPortal: {
        id: "bt_redirect_to_portal",
        title: SUGAR.language.get("stic_Signers", "LBL_SIGNER_REDIRECT_TO_PORTAL"), // Localized button title with link emoji 
        onclick: "window.open('index.php?entryPoint=sticSign&signerId=" + STIC.record.id + "', '_blank')"
      },
      copyPortalUrl: {
        id: "bt_copy_portal_url",
        title: SUGAR.language.get("stic_Signers", "LBL_SIGNER_COPY_PORTAL_URL"), // Localized button title with clipboard emoji
        onclick: "navigator.clipboard.writeText('" + window.location.origin + window.location.pathname + "?entryPoint=sticSign&signerId=" + STIC.record.id + "'); alert(SUGAR.language.get('stic_Signers', 'LBL_SIGNER_PORTAL_URL_COPIED'));"
      },
      downloadDocument: {
        id: "bt_download_document_draft",
        title: SUGAR.language.get("stic_Signers", "LBL_SIGNER_DOWNLOAD_DOCUMENT"),
        onclick: "if(confirm('" + SUGAR.language.get("stic_Signers", "LBL_SIGNER_DOWNLOAD_DOCUMENT_INFO") + "')){window.location='index.php?module=stic_Signers&action=downloadDocument&signerId=" + STIC.record.id + "'}"
      }
    };
    // Add the defined button to the detail view.
    createDetailViewButton(buttons.sendToSign);
    createDetailViewButton(buttons.rediretToSingPortal);
    createDetailViewButton(buttons.copyPortalUrl);
    createDetailViewButton(buttons.downloadDocument);


    break;
  case "list":
    // selectRemittanceAlert = SUGAR.language.get("stic_Payments", "LBL_ADD_PAYMENTS_TO_REMITTANCE_INFO_ALERT");
    sendMailMassive = SUGAR.language.languages.app_strings.LBL_LISTVIEW_NO_SELECTED;
    buttons = {
      sendToSign: {
        id: "send-to-sign-massive",
        text: SUGAR.language.get("stic_Signers", "LBL_SIGNER_SEND_TO_SIGN_BY_EMAIL"),
        onclick: "sendToSign()"
      },
      downloadDocument: {
        id: "download-document-draft-massive",
        text: SUGAR.language.get("stic_Signers", "LBL_SIGNER_DOWNLOAD_DOCUMENT"),
        onclick: "downloadDocument()"
      }
    }
      ;

    createListViewButton(buttons.sendToSign);
    createListViewButton(buttons.downloadDocument);

    break;
  default:
    // Default case for any other view types not explicitly handled.
    break;
}







/* AUX. FUNCTIONS */

function sendToSign() {

  sugarListView.get_checks();
  if (sugarListView.get_checks_count() < 1) {
    alert(alertListView);
    return false;
  }

  if (sugarListView.get_checks_count() > 20) {
    alert(SUGAR.language.get("stic_Signers", "LBL_SIGNER_SEND_TO_SIGN_MASSIVE_LIMIT_ALERT"));
    return false;
  }
  document.MassUpdate.action.value = 'sendToSign';
  document.MassUpdate.module.value = 'stic_Signers';
  document.MassUpdate.submit();

}

function downloadDocument() {

  if(confirm(SUGAR.language.get("stic_Signers", "LBL_SIGNER_DOWNLOAD_DOCUMENT_INFO"))===false){
    return false;
  }

  sugarListView.get_checks();
  if (sugarListView.get_checks_count() < 1) {
    alert(alertListView);
    return false;
  }


  document.MassUpdate.action.value = 'downloadDocument';
  document.MassUpdate.module.value = 'stic_Signers';
  document.MassUpdate.submit();
}