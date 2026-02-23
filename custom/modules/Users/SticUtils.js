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

// Set module name
var module = "Users";

// Ensure editACL is defined (default to false when missing)
if (typeof editACL === 'undefined') {
    editACL = false;
}
/* VIEWS CUSTOM CODE */
switch (viewTypeUsers()) {
    case "edit":
    case "quickcreate":
    case "popup":
        // Disable fields if the user is not admin
        if (isAdminCurrentUser == '0') {
            adminOnlyFields = {
                "": {
                    enabled: [],
                    disabled: ["stic_work_calendar_c", "stic_clock_c", "sda_allowed_c", "stic_m182_issuing_organization_c"]
                },
                default: {
                    enabled: ["stic_work_calendar_c", "stic_clock_c", "sda_allowed_c", "stic_m182_issuing_organization_c"],
                    disabled: []
                }
            };
            setCustomStatus(adminOnlyFields, "");
        }
        break;
    
    case "detail":
        if (editACL){    
            // Define button content
            var buttons = {
                showWorkCalendarAssistant: {
                    id: "show_work_calendar_assistant",
                    title: SUGAR.language.get(module, "LBL_PERIODIC_WORK_CALENDAR_BUTTON"),
                    onclick: "location.href='" + STIC.siteUrl + "/index.php?module=stic_Work_Calendar&action=showWorkCalendarAssistant&employeeId=" + STIC.record.id + "'",
                },
            };
            createDetailViewButton(buttons.showWorkCalendarAssistant);
        }
        // Define button content
        var buttons = {
            showImpersonateUser: {
                id: "show_impersonate_user",
                title: SUGAR.language.get(module, "LBL_IMPERSONATE_USER_BUTTON"),
                onclick: "location.href='" + STIC.siteUrl + "/index.php?module=Users&action=startImpersonation&userId=" + STIC.record.id + "'",
            },
        };
        createDetailViewButton(buttons.showImpersonateUser);

        break;

    case "list":
        button = {
            id: "bt_work_calendar_periodic_creation_listview",
            title: SUGAR.language.get(module, "LBL_PERIODIC_WORK_CALENDAR_BUTTON"),
            text: SUGAR.language.get(module, "LBL_PERIODIC_WORK_CALENDAR_BUTTON"),
            onclick: "onClickWorkCalendarPeriodicCreationButton()",
        };
        createListViewButton(button);
        break;

    default:
        break;
}    


/**
 * Used as a callback for the periodic creation of Work Calendar Records
 */
function onClickWorkCalendarPeriodicCreationButton() {
    sugarListView.get_checks();
    if(sugarListView.get_checks_count() < 1) {
        alert(SUGAR.language.get('app_strings', 'LBL_LISTVIEW_NO_SELECTED'));
        return false;
    }
    document.MassUpdate.action.value='showWorkCalendarAssistant';
    document.MassUpdate.module.value='stic_Work_Calendar';
    document.MassUpdate.submit();
  }

/**
 * This function is a helper to determine the current view type
 * It is a clone of viewType() in SticInclude/Utils.js but adapted to work in Users modules
 * where the DetailView form exists alongside the EditView form.
 * @returns 
 */
function viewTypeUsers() {
  if ($(".listViewBody").length == 1) {
    return "list";
  } else if ($(".sub-panel .quickcreate form").length == 1) {
    return "quickcreate";
  } else if ($(".detail-view").length == 1 || ($("form[name=DetailView]").length == 1 && $("form[name=EditView]").length != 1)) {
    return "detail";
  } else if ($("form[name=EditView]").length == 1) {
    return "edit";
  } else if ($("#popup_query_form").length == 1) {
    return "popup";
  }
}