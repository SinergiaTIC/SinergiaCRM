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

/* VIEWS CUSTOM CODE */
switch (viewType()) {
    case "edit":
    case "quickcreate":
    case "popup":
  
      $(document).ready(function () {
        // Definition of the behavior of fields that are conditionally enabled or disabled
        showTabsEdit();
        setAutofill(["name"]);
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
// Function to show the tabs depending of the type
function showTabs(typeSelected) {

    var panelPlaces = $("div.panel-content");

    // Ocultar el panel de lenguaje por defecto
    panelPlace(panelPlaces, "hide");

    if (typeSelected === "places") {
        // Mostrar el panel de lenguaje si typeSelected es 'language'
        panelPlace(panelPlaces, "show");
    } else {
        // Ocultar el panel de lenguaje si typeSelected no es 'language'
        panelPlace(panelPlaces, "hide");
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
        });
      }
    } else {
      $("#type").on("change", function () {
        showTabsEdit();
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