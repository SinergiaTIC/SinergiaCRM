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
var module = "stic_Allocation_Proposals";

/* INCLUDES */


/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    setAutofill(["name"]);
    break;

  case "detail":
    $(document).ready(function() {
      debugger;
      // 1. Defineix l'ID del contenidor del subpanell (ajusta 'contacts' segons el teu cas)
      var subpanelID = 'whole_subpanel_stic_allocation_proposals_stic_allocations'; 
      var targetNode = document.getElementById(subpanelID);

      if (!targetNode) {
          console.warn("No s'ha trobat el subpanell: " + subpanelID);
          return;
      }

      // 2. Configuració de l'observador
      var config = { childList: true, subtree: true };

      // 3. Funció que s'executa quan hi ha canvis
      var callback = function(mutationsList, observer) {
          // Busquem les files de dades (oddListRowS1 o evenListRowS1)
          var rows = $(targetNode).find('tr.oddListRowS1, tr.evenListRowS1');
          
          if (rows.length > 0) {
            $("#delete_button").hide(); // hide delete button
          } 
          else {
            $("#delete_button").show(); // show delete button
          }
      };

      // 4. Crear i iniciar l'observador
      var observer = new MutationObserver(callback);
      observer.observe(targetNode, config);
    });
    break;

  case "list":
    break;

  default:
    break;
}

/* AUX FUNCTIONS */