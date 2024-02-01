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

// function GetElementInsideContainer(containerID, childID) {
//   var elm = document.getElementById(childID);
//   var parent = elm ? elm.parentNode : {};
//   return (parent.id && parent.id === containerID) ? elm : {};
// }
// function isDescendant(parent, child) {
//   var node = child.parentNode;
//   while (node != null) {
//       if (node == parent) {
//           return true;
//       }
//       node = node.parentNode;
//   }
//   return false;
// }

function setFieldsInForm(node, default, order) {
  var form = node.querySelector('form');
  if (form) {
    // Create default input
    var input = document.createElement("input");
    input.setAttribute('type', 'hidden');
    input.setAttribute('name', 'default');
    input.setAttribute('value', default);
    form.appendChild(input);

    // Set value in order input if is not set
    if (!$("#order").val()) {
      $("#order").val(order);
    }
  }
}

function initializeQuickCreateCustomization() {
  // When appears a new QuickCreate form in subpanel
  //  - set the correct value in default field
  //  - set order if not set (for new items)
  
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
              if ($("#order").val()=="0") {
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
  // Hide module selector, show label with module name
  sticCustomView.editview.field("view_module").readonly().bold();
  // $("#view_module").hide().parent().append($('<strong id="view_module_label">'+$("#view_module option:selected").text()+'</strong>'));

  // Hide view selector, show label with view name
  sticCustomView.editview.field("view_module_view").readonly().bold();
  //$("#view_module_view").hide().parent().append($('<strong id="view_module_view_label">'+$("#view_module_view option:selected").text()+'</strong>'));

  // Set initial name
  sticCustomView.editview.field("name").input().editor.val(
    sticCustomView.editview.field("view_module").input().text() + " - " +
    sticCustomView.editview.field("view_module_view").input().text() + " - " +
    sticCustomView.editview.field("view_name").input().text()
  );
  //$("#name").val($('#view_module_label').text() + ' - ' + $('#view_module_view_label').text() + ' - ' + $("#view_name").val());

  // Hide name, show label with name
  sticCustomView.editview.field("name").readonly().bold();
  //$("#name").hide().parent().append($('<strong id="name_label">'+$("#name").val()+'</strong>'));

  // Update name when any change on view_name
  sticCustomView.editview.field("view_name").input().editor.on("change paste keyup", function() {
    sticCustomView.editview.field("name").input().editor.val(
      sticCustomView.editview.field("view_module").input().text() + " - " +
      sticCustomView.editview.field("view_module_view").input().text() + " - " +
      sticCustomView.editview.field("view_name").input().text()
    );
    sticCustomView.editview.field("name").input().editor.change();
  });
  // $("#view_name").on("change paste keyup", function() {
  //   $("#name").val($('#view_module_label').text() + ' - ' + $('#view_module_view_label').text() + ' - ' + $("#view_name").val());
  //   $("#name_label").text($("#name").val());
  // });
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