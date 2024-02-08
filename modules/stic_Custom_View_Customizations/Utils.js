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
var module = "stic_Custom_View_Customizations";

/* INCLUDES */

/* VALIDATION DEPENDENCIES */

/* VALIDATION CALLBACKS */

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "quickcreate":
    $(document).ready(function () { 
      var customView = sticCustomizeView.quickcreate;
      // Hide Condition lines
      if($('input[name="init"]').val()=="1") {
        customView.panel("LBL_CONDITION_LINES").hide();
        customView.field("customization_order").readonly();
      }
      // Hide Labels
      customView.field("condition_lines").label.hide();
      customView.field("action_lines").label.hide();
    });
    break;

  case "edit":
  case "popup":
    break;
    
  case "detail":
    break;

  case "list":
    break;

  default:
    break;
}

$(document).ready(function () {
});

function translate(label, module) {
  return SUGAR.language.get(module, label);
}

function translateCustomization(label) {
  return translate(label,'stic_Custom_View_Customizations');
}