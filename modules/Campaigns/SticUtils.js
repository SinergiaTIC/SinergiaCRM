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
var module = "Campaigns";

/* INCLUDES */

/* VALIDATION DEPENDENCIES */

/* VALIDATION CALLBACKS */

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "quickcreate":
    $(document).ready(function() {
      initializeQuickCreate();
    });
    break;

  case "edit":
  case "popup":
    $(document).ready(function() {
      initilizeEditView();
    });
    break;

  case "detail":
    $(document).ready(function() {});
    break;

  case "list":
    break;

  default:
    break;
}

$(document).ready(function() {
  type_change();
});

function type_change() {
  var typeValue = $('[name="campaign_type"]').val();
  if (typeValue === undefined) {
    typeValue = $('[field="campaign_type"] input').val();
  }

  showNewsLetterFields(typeValue == "NewsLetter");
  showNotificationFields(typeValue == "Notification");
}

function ConvertItems(id) {
  var items = new Array();

  //get the items that are to be converted
  expected_revenue = document.getElementById("expected_revenue");
  budget = document.getElementById("budget");
  actual_cost = document.getElementById("actual_cost");
  expected_cost = document.getElementById("expected_cost");

  //unformat the values of the items to be converted
  expected_revenue.value = unformatNumber(expected_revenue.value, num_grp_sep, dec_sep);
  expected_cost.value = unformatNumber(expected_cost.value, num_grp_sep, dec_sep);
  budget.value = unformatNumber(budget.value, num_grp_sep, dec_sep);
  actual_cost.value = unformatNumber(actual_cost.value, num_grp_sep, dec_sep);

  //add the items to an array
  items[items.length] = expected_revenue;
  items[items.length] = budget;
  items[items.length] = expected_cost;
  items[items.length] = actual_cost;

  //call function that will convert currency
  ConvertRate(id, items);

  //Add formatting back to items
  expected_revenue.value = formatNumber(expected_revenue.value, num_grp_sep, dec_sep);
  expected_cost.value = formatNumber(expected_cost.value, num_grp_sep, dec_sep);
  budget.value = formatNumber(budget.value, num_grp_sep, dec_sep);
  actual_cost.value = formatNumber(actual_cost.value, num_grp_sep, dec_sep);
}

function showNewsLetterFields(show) {
  if (show) {
    $('[data-field="frequency"]').show();
  } else {
    $('[data-field="frequency"]').hide();
  }
}

function setRequired(require, field, type, label) {
  if (require) {
    addToValidate(getFormName(), field, type, true, SUGAR.language.get(module, label));
    addRequiredMark(field, "conditional-required");
  } else {
    removeFromValidate(getFormName(), field);
  }
}

function showNotificationFields(show) {
  setRequired(show, "parent_name", "relate", "LBL_FLEX_RELATE");
  setRequired(show, "notification_prospect_list_id", "enum", "LBL_NOTIFICATION_PROSPECT_LIST_ID");
  setRequired(show, "notification_template_id", "enum", "LBL_NOTIFICATION_TEMPLATE_ID");
  setRequired(show, "notification_from_name", "varchar", "LBL_NOTIFICATION_FROM_NAME");
  setRequired(show, "notification_from_addr", "varchar", "LBL_NOTIFICATION_FROM_ADDR");

  if (show) {
    $('[data-field="parent_name"]').show();
    $(".panel-body[data-id='LBL_NOTIFICATION_INFORMATION_PANEL']").parent().show();
  } else {
    $("#parent_type").val("");
    $("#parent_name").val("");
    $("#parent_id").val("");
    $('[data-field="parent_name"]').hide();

    $(".panel-body[data-id='LBL_NOTIFICATION_INFORMATION_PANEL']").parent().hide();
  }
}

function initializeQuickCreate() {
  if ($("#subpanel_stic_notifications_newDiv").length == 1) {
    // Is a New notification from Subpanel

    $("[data-field='campaign_type']").hide();
    $("#campaign_type").val("Notification");

    $("#status").val("Active");
  }
}

function initilizeEditView() {
  if ($("#parent_type").length == 1) {
    // Remove not allowed Modules for Notifications
    var allowedModules = ["Opportunities"];
    $("#parent_type").find("option").each(function() {
      var $option = $(this);
      if (!allowedModules.includes($option.val())) {
        $option.remove();
      }
    });
  }
}
