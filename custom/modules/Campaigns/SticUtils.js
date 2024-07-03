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
var validationDependencies = {
  start_date: "end_date",
  end_date: "start_date",
};

/* VALIDATION CALLBACKS */
addToValidateCallback(getFormName(), "end_date", "date", false, SUGAR.language.get(module, "LBL_END_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "end_date");
});

addToValidateCallback(getFormName(), "start_date", "date", false, SUGAR.language.get(module, "LBL_START_DATE_ERROR"), function () {
  return checkStartAndEndDatesCoherence("start_date", "end_date");
});

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "quickcreate":
  case "popup":
      $(document).ready(function() {
      initializeQuickCreate();
    });
    break;

  case "edit":
    $(document).ready(function() {
      initilizeEditView();
    });
    break;

  case "detail":
    $(document).ready(function() {
      initilizeDetailView();
    });
    break;

  case "list":
    break;

  default:
    break;
}

$(document).ready(function() {
  type_change();
});

function get_CampaingType() {
  var typeValue = $('[name="campaign_type"]').val();
  if (typeValue === undefined) {
    typeValue = $('[field="campaign_type"] input').val();
  }
  return typeValue;
}

function type_change() {
  var typeValue = get_CampaingType();

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
    addRequiredMark(field);
  } else {
    removeFromValidate(getFormName(), field);
    removeRequiredMark(field)
  }
}

function showNotificationFields(show) {
  setRequired(show, "start_date", "date", "LBL_CAMPAIGN_START_DATE");
  setRequired(show, "parent_name", "relate", "LBL_FLEX_RELATE");
  setRequired(show, "notification_outbound_email_id", "enum", "LBL_NOTIFICATION_OUTBOUND_EMAIL_ID");
  setRequired(show, "notification_prospect_list_id", "enum", "LBL_NOTIFICATION_PROSPECT_LIST_ID");
  setRequired(show, "notification_template_id", "enum", "LBL_NOTIFICATION_TEMPLATE_ID");
  setRequired(show, "notification_from_name", "varchar", "LBL_NOTIFICATION_FROM_NAME");
  setRequired(show, "notification_from_addr", "varchar", "LBL_NOTIFICATION_FROM_ADDR");

  if (show) {
    $("#status").val("Active")
    $('[data-field="status"]').hide();
    $('[data-field="end_date"]').hide();
    $('[data-field="parent_name"]').show();
    $(".panel-body[data-id='LBL_NOTIFICATION_INFORMATION_PANEL']").parent().show();
    $("[data-label='LBL_NAVIGATION_MENU_GEN2']").hide()
  } else {
    $("#parent_type").val("");
    $("#parent_name").val("");
    $("#parent_id").val("");
    $("#status").val("")
    $('[data-field="status"]').show();
    $('[data-field="end_date"]').show();
    $('[data-field="parent_name"]').hide();
    $(".panel-body[data-id='LBL_NOTIFICATION_INFORMATION_PANEL']").parent().hide();
    $("[data-label='LBL_NAVIGATION_MENU_GEN2']").show()
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

function initilizeDetailView() {
  var typeValue = get_CampaingType();

  if (typeValue == "Notification") {
    // Disable all editable actions

    // Action menu buttons
    $("#edit_button").hide();
    $("#delete_button").hide();
    $("#launch_wizard_button").hide();

    // All Subpanels buttons
    $(".clickMenu").hide(); 
  }
}


