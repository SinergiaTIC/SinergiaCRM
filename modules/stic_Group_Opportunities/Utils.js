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
var module = "stic_Group_Opportunities";

/* VIEWS CUSTOM CODE */
var customView = sticCustomizeView.For(viewType());
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    $(document).ready(function() {
      initializeEditFields(customView);
    });
    break;

  case "detail":
  case "list":
  default:
    break;
}

/* AUX FUNCTIONS */

function refreshViewName(customView) {
  if (customView == null) {
    return;
  }
  customView
    .field("name")
    .value(
      customView.field("stic_group_opportunities_accounts_name").content.text() +
        " - " +
        customView.field("stic_group_opportunities_opportunities_name").content.text()
    );
}

function initializeEditFields(customView) {
  setAutofill(["name"]);

  if (customView != null) {
    // Readonly name
    // customView.field("name").readonly();
    // customView.field("name").content.bold();

    // Set initial name
    refreshViewName(customView);

    // Update name when any change on dependant fields
    customView.field("stic_group_opportunities_accounts_name").onChange(function() {
      refreshViewName(customView);
    });
    customView.field("stic_group_opportunities_opportunities_name").onChange(function() {
      refreshViewName(customView);
    });

    if(customView.view=="quickcreate") {
      customView.field("stic_group_opportunities_opportunities_name").readonly();
      customView.field("stic_group_opportunities_opportunities_name").content.bold();
    }
  }
}
