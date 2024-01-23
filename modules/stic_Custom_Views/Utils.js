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
var lastCustomizationInitialOrder = 0;
var lastCustomizationDynamicOrder = 0;

/* INCLUDES */

/* VALIDATION DEPENDENCIES */

/* VALIDATION CALLBACKS */

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    $(document).ready(function () { 
      initializeEditFields();
      initializeSelectize();
    });
    break;

  case "detail":
    $(document).ready(function() {
      initializeQuickCreateCustomization();
    });
    break;

  case "list":
    $(document).ready(function () {
      disableListMenuActions();
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

function setIsInitialInForm(node, is_initial) {
  var form = node.querySelector('form');
  if (form) {
    var input = document.createElement("input");
    input.setAttribute('type', 'hidden');
    input.setAttribute('name', 'is_initial');
    input.setAttribute('value', is_initial);
    form.appendChild(input);
    console.log(form);
  }
}

function initializeQuickCreateCustomization() {
  // Observer for new elements
  var observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
        mutation.addedNodes.forEach(function(newNode) {
          if (newNode.nodeType === 1) {
            if(newNode.id === "subpanel_stic_custom_view_customizations_initial_newDiv") {
              setIsInitialInForm(newNode, 1);
            }
            if(newNode.id === "subpanel_stic_custom_view_customizations_dynamic_newDiv") {
              setIsInitialInForm(newNode, 0);
            }
          }
        });
      }
    });
  });
  
  // Set observer for subpanel_list
  var subpanelList = document.getElementById('subpanel_list');
  var config = { childList: true, subtree: true };
  observer.observe(subpanelList, config);
}

function initializeEditFields() {
  // Hide module selector, show label with module name
  $("#view_module").hide().parent().append($('<strong id="view_module_label">'+$("#view_module option:selected").text()+'</strong>'));

  // Set initial name
  $("#name").val($('#view_module_label').text() + ' - ' + $("#view_name").val());

  // Hide name, show label with name
  $("#name").hide().parent().append($('<strong id="name_label">'+$("#name").val()+'</strong>'));

  // Update name when any change on view_name
  $("#view_name").on("change paste keyup", function() {
    $("#name").val($('#view_module_label').text() + ' - ' + $("#view_name").val());
    $("#name_label").text($("#name").val());
  });
}

function initializeSelectize() {
  var config = { placeholder: '' };
  $('select#security_groups').selectize(config);
  $('select#roles').selectize(config);
  $('select#user_type').selectize(config);
}

function disableListMenuActions() {
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
}