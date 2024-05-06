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
var module = "Opportunities";

/* INCLUDES */

/* VALIDATION DEPENDENCIES */

/* VALIDATION CALLBACKS */

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
    break;
  case "detail":
    var cv = sticCustomizeView.detailview();
    addSendNotificationNowButton();
    break;
  case "list":
    break;
  default:
    break;
}

/* AUX FUNCTIONS */

function addSendNotificationNowButton() {
  // Create Button only if notification fields are present
  if (
    cv.field("stic_publishable_c").isDefined() &&
    cv.field("stic_sendto_prospectlist_c").isDefined() &&
    cv.field("stic_email_template_c").isDefined()
  ) {
    createDetailViewButton({
      id: "send_opportunity_alert_now",
      title: SUGAR.language.get("Opportunities", "LBL_STIC_SEND_NOTIFICATION_NOW"),
      onclick: "sendNotificationNow()"
    });
  }
}

function sendNotificationNow() {
  // Check if needed data is set
  var prospectList = cv.field("stic_sendto_prospectlist_c").value().split("|")[0];
  var emailTemplate = cv.field("stic_email_template_c").value().split("|")[0];

  if (prospectList == "" || emailTemplate == "") {
    alert(SUGAR.language.get("Opportunities", "LBL_STIC_NEED_DATA_TO_SEND_NOTIFICATION"));
    return;
  } 

  // Send Notification
  console.log("Send notification now");
  var obj = {
    action: "sendNotificationNow",
    module: "Opportunities",
    return_module: "Opportunities",
    return_action: "DetailView",
    record: window.document.forms["DetailView"].record.value,
  };
  console.log(obj);

  var url = "?index.php&" + $.param(obj);
  location.href = url;
}

