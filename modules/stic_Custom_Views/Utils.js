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
var module = "stic_Custom_Views";

/* INCLUDES */

/* VALIDATION DEPENDENCIES */

/* VALIDATION CALLBACKS */

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    $(document).ready(function () {
      // Initialize selectize for specific select elements
      $('select#security_groups').selectize({
        placeholder: ''
      });
      $('select#roles').selectize({
        placeholder: ''
      });
      $('select#user_type').selectize({
        placeholder: ''
      });
      // Additional document ready function to handle checkbox state change
    })


    break;
  case "detail":
    break;

  case "list":

    $(document).ready(function () {
      // disable some list menu actions
      var selectorsToKeep = ['#massupdate_listview_top', '#export_listview_top', '#delete_listview_top'];

      // remove duplicate massive link which has not a uniq id
      $('#actionLinkTop > li > ul > li:nth-child(2) > a#massupdate_listview_top').closest('li').remove();

      $('ul#actionLinkTop li.sugar_action_button ul li').each(function () {
        var containsSelector = false;
        for (var i = 0; i < selectorsToKeep.length; i++) {
          if ($(this).find(selectorsToKeep[i]).length > 0) {
            containsSelector = true;
            break;
          }
        }
        if (!containsSelector) {
          $(this).remove();
        }
      });



    });





    break;

  default:
    break;
}

function GetElementInsideContainer(containerID, childID) {
  var elm = document.getElementById(childID);
  var parent = elm ? elm.parentNode : {};
  return (parent.id && parent.id === containerID) ? elm : {};
}
function isDescendant(parent, child) {
  var node = child.parentNode;
  while (node != null) {
      if (node == parent) {
          return true;
      }
      node = node.parentNode;
  }
  return false;
}

$(document).ready(function () {
  var panelInintial = document.getElementById("list_subpanel_stic_custom_view_customizations_initial"); 
  var panelDynamic = document.getElementById("list_subpanel_stic_custom_view_customizations_dynamic"); 

  //IEPA!!
  // Caldria, escoltar l'event de creació, i afegir els inputs abans de fer el commit

  var forms = document.querySelectorAll("[id='formformstic_custom_views_stic_custom_view_customizations']");
  for(var i = 0; i < forms.length; i++) {
    if(isDescendant(panelInintial, forms[i])) {
      var input = document.createElement("input");
      input.setAttribute('type', 'hidden');
      input.setAttribute('name', 'is_initial');
      input.setAttribute('value', '1');
      forms[i].appendChild(input);
      //alert("inserted Initial");
    } 
    if(isDescendant(panelDynamic,forms[i])) {
      var input = document.createElement("input");
      input.setAttribute('type', 'hidden');
      input.setAttribute('name', 'is_initial');
      input.setAttribute('value', '0');
      forms[i].appendChild(input);
      //alert("inserted Dynamic");
    }
  }

  // var e = GetElementInsideContainer("list_subpanel_stic_custom_view_customizations_initial", "formformstic_custom_views_stic_custom_view_customizations");
  // alert (e);
  // // Show message if the functionality is deactivated
  // if (SUGAR.config.stic_security_groups_rules_enabled != 1) {
  //   $('<div class=msg-fatal-lock>' + SUGAR.language.languages.stic_Security_Groups_Rules.LBL_DISABLED_MODULE_RULES_INFO + '</div>').prependTo('#pagecontent')
  // }
});

