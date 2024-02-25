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

function setFieldsInForm(node, init, order) {
  var form = node.querySelector('form');
  if (form) {
    // Create init input
    var input = document.createElement("input");
    input.setAttribute('type', 'hidden');
    input.setAttribute('name', 'init');
    input.setAttribute('value', init);
    form.appendChild(input);

    // Set value in customization_order input if is not set
    if (!$("#customization_order").val()) {
      $("#customization_order").val(order);
    }
  }
}

function initializeQuickCreateCustomization() {
  // When appears a new QuickCreate form in subpanel
  //  - set the correct value in init field
  //  - set customization_order if not set (for new items)
  
  // Observer for new elements
  var observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
        mutation.addedNodes.forEach(function(newNode) {
          if (newNode.nodeType === 1) {
            if (newNode.id === "subpanel_stic_custom_view_customizations_newDiv") {
              var nextOrder = 1;
              if ($("#list_subpanel_stic_custom_view_customizations .subpanel-table tbody tr.footable-empty").length == 0) {
                nextOrder = $("#list_subpanel_stic_custom_view_customizations .subpanel-table").find("tbody tr").length - 1;
              }
              if ($("#customization_order").val()=="0") {
                setFieldsInForm(newNode, 1, nextOrder);
              } else {
                setFieldsInForm(newNode, 0, nextOrder);
              }
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
  var customView = sticCustomizeView.editview();

  // Hide module selector, show label with module name
  customView.field("view_module").readonly();
  customView.field("view_module").content.bold();

  // Hide view selector, show label with view name
  customView.field("view_type").readonly();
  customView.field("view_type").content.bold();

  // Set initial name
  customView.field("name").value(
    customView.field("view_module").content.text() + " - " +
    customView.field("view_type").content.text() + " - " +
    customView.field("customization_name").content.text()
  );

  // Hide name, show label with name
  customView.field("name").readonly();
  customView.field("name").content.bold();

  // Update name when any change on customization_name
  customView.field("customization_name").onChange(function() {
    customView.field("name").value(
      customView.field("view_module").content.text() + " - " +
      customView.field("view_type").content.text() + " - " +
      customView.field("customization_name").content.text()
    );
  });
}

function initializeSelectize() {
  var config = { placeholder: '' };
  $('select#user_type').selectize(config);
  $('select#security_groups').selectize(config);
  $('select#roles').selectize(config);
  $('select#security_groups_exclude').selectize(config);
  $('select#roles_exclude').selectize(config);
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
