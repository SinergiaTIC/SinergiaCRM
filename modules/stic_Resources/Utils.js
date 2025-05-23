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
var module = "stic_Resources";

/* VALIDATION CALLBACKS */

addToValidateCallback(getFormName(), "stic_resources_stic_centers_name", "relate", false, SUGAR.language.get(module, "LBL_CENTER_REQUIRED_FOR_PLACES"), function() {
  return checkCenterForPlaces();
});

/* VIEWS CUSTOM CODE */
switch (viewType()) {
    case "edit":
    case "quickcreate":
    case "popup":
  
      $(document).ready(function () {
        showTabsEdit();
        updateCenterRequirement();
      });
      break;
  
    case "detail":
      $(document).ready(function () {
        var typeSelected = $("#type").val();
        showTabs(typeSelected);
      });
      break;
  
    case "list":
      break;
  
    default:
      break;
  }

/* AUX FUNCTIONS */

// Function to check if center is required based on type
function checkCenterForPlaces() {
  var typeValue = getFieldValue("type");
  var centerValue = getFieldValue("stic_resources_stic_centers_name");

  if (typeValue === "places" && centerValue === "") {
    return false;
  }
  
  return true;
}

// Function to update UI indicating center field is required when type is places
function updateCenterRequirement() {
  var typeValue = $("#type").val();
  var centerField = $("#stic_resources_stic_centers_name");
  var centerLabelDiv = $('[data-label="LBL_STIC_RESOURCES_STIC_CENTERS_FROM_STIC_CENTERS_TITLE"]');

  if (typeValue === "places") {
    if (centerLabelDiv.length && !centerLabelDiv.find("span.required").length) {
      centerLabelDiv.append('<span class="required">*</span>');
    }

    centerField.addClass("conditional-required");
  } else {
    centerLabelDiv.find("span.required").remove();
    centerField.removeClass("conditional-required");
  }
}

// Function to show the tabs depending of the type
function showTabs(typeSelected) {

    var panelPlaces = $("div.panel-content");

    panelPlace(panelPlaces, "hide");

    if (typeSelected === "places") {
        panelPlace(panelPlaces, "show");          
        updateCenterRequirement();
    } else {
        panelPlace(panelPlaces, "hide");
        updateCenterRequirement();
    }
}

// Function to show the tabs when the type is changing
function showTabsEdit() {
    var typeSelected = $("#type").val();
  
    showTabs(typeSelected);
  
    // Get the subpanels of the quickcreate
    if (viewType() == "quickcreate") {
      typeContact = document.querySelector(
        "#whole_subpanel_stic_resources_stic_bookings"
      );
      if (typeContact != null) {
        typeSelected = $("#whole_subpanel_stic_resources_stic_bookings #type");
        typeSelected.on("change", function () {
          showTabs(typeSelected.val());
          updateCenterRequirement();
        });
      }
    } else {
      $("#type").on("change", function () {
        var newType = $(this).val();
        showTabs(newType);
        updateCenterRequirement();
      });
    }

}
  
function panelPlace(panelPlaces, view) {
    // Showing the tab Task and put the fields required if is in the EditView
    if (view === "show") {
        panelPlaces.show();
     
      // Hiding the tab Task and put the fields unrequired if is in the EditView
    } else if (view === "hide") {
        panelPlaces.hide();
    }
}