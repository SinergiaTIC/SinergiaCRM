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

$(document).ready(function() {
  removeMenu();
  createMenu();

  // Restore default menu
  $("#restore-menu").on("click", function() {
    if (confirm("Restore the default SinergiaCRM menu?") == false) {
      return;
    }

    // Define data to be sent in the request
    var dataToSend = {
      manageMode: "restore"
    };

    // Perform AJAX request
    $.ajax({
      url: location.href.slice(0, location.href.indexOf(location.search)),
      type: "POST",
      data: {
        module: "Studio",
        action: "SticAdvancedMenuController",
        ...dataToSend
      },
      success: function(response) {
        window.location.reload(true);
      },
      error: function(xhr, status, error) {
        console.error("Request error:", status, error);
      }
    });
  });

  // Restore default menu
  $("#legacy-menu").on("click", function() {
    if (confirm(SUGAR.language.get('Studio', "LBL_STIC_MENU_CHANGE_TO_LEGACY_CONFIRM")) == false) {
      return;
    }

    // Define data to be sent in the request
    var dataToSend = {
      manageMode: "legacy_mode",
      sticAdvancedMenuEnabled: "1"
    };

    // Perform AJAX request
    $.ajax({
      url: location.href.slice(0, location.href.indexOf(location.search)),
      type: "POST",
      data: {
        module: "Studio",
        action: "SticAdvancedMenuController",
        ...dataToSend
      },
      success: function(response) {
        window.location.reload(true);
      },
      error: function(xhr, status, error) {
        console.error("Request error:", status, error);
      }
    });
  });

  // Save menu button
  $("#save-menu").on("click", function() {
    var $cleanMenu = $("#stic-menu-manager").jstree(true).get_json().map(filterNodes);

    // Define data to be sent in the request
    var dataToSend = {
      menuJson: JSON.stringify($cleanMenu),
      sticAdvancedMenuIcons: document.getElementById("stic_advanced_menu_icons").checked ? "1" : "0",
      sticAdvancedMenuAll: document.getElementById("stic_advanced_menu_all").checked ? "1" : "0",
      manageMode: "save"
    };

    // Perform AJAX request
    $.ajax({
      url: location.href.slice(0, location.href.indexOf(location.search)),
      type: "POST",
      data: {
        module: "Studio",
        action: "SticAdvancedMenuController",
        ...dataToSend
      },
      success: function(response) {
        console.log("Response received:", response);
        location.reload(true);
      },
      error: function(xhr, status, error) {
        console.error("Request error:", status, error);
      }
    });
  });

  // Ensure that modules dragged from hidden-modules maintain the original id
  $("#stic-menu-manager").on("copy_node.jstree", function(e, data) {
    var original_node = data.original;
    var new_node = data.node;

    // Preserve the original ID and text
    var tree = $("#stic-menu-manager").jstree(true);
    tree.set_id(new_node, original_node.id);
  });

  // Change text and id when renaming a node
  $("#stic-menu-manager").on("rename_node.jstree", function(e, data) {
    var tree = $("#stic-menu-manager").jstree(true);
    var newId = data.text.replace(/\s+/g, "_");

    // Change the ID directly
    tree.set_id(data.node, newId);

    // Log the current node ID for verification
    console.log("New ID set:", newId);
    console.log("Current node ID:", data.node.id);
  });

  // Handle tree changes
  $("#stic-menu-manager").on("rename_node.jstree", handleTreeChanges);
  $("#stic-menu-manager").on("move_node.jstree", handleTreeChanges);
  $("#stic-menu-manager").on("delete_node.jstree", handleTreeChanges);
  $("#stic-menu-manager").on("create_node.jstree", handleTreeChanges);

  // Handle options changes
  $("#stic_advanced_menu_icons").on("change", handleTreeChanges);
  $("#stic_advanced_menu_all").on("change", handleTreeChanges);

  // Hide saved notice after 3 seconds
  setTimeout(() => {
    $("#saved-notice").fadeOut(3000);
  }, 3000);
});

/**
 * Filter node properties, keeping only 'id' and 'children'
 * @param {Object} node - The node to filter
 * @return {Object} The filtered node
 */
function filterNodes(node) {
  if (typeof node === "object" && node !== null) {
    const keys = Object.keys(node);
    for (const key of keys) {
      if (key !== "id" && key !== "children") {
        delete node[key];
      } else if (key === "children") {
        node[key] = node[key].map(filterNodes);
        if (node[key].length === 0) {
          delete node[key];
        }
      }
    }
  }
  return node;
}

/**
 * Handle tree changes and update save button appearance
 * @param {Event} e - The event object
 * @param {Object} data - The data associated with the event
 */
function handleTreeChanges(e, data) {
  console.log("Tree has been modified:", data);
  $("#save-menu .glyphicon")
    .addClass("glyphicon-save")
    .removeClass("glyphicon-ok")
    .addClass("text-danger")
    .removeClass("text-success");
}

/**
 * Create jsTree menu
 */
function createMenu() {
  $("#stic-menu-manager").jstree({
    core: {
      data: menu[0],
      check_callback: function(operation, node, parent, position, more) {
        return true; // Allow all operations
      },
      themes: {
        icons: false,
        dots: true
      }
    },
    plugins: ["dnd", "wholerow", "contextmenu"],
    contextmenu: {
      items: function($node) {
        var tree = $("#stic-menu-manager").jstree(true);
        return {
          Create: {
            separator_before: false,
            separator_after: true,
            label: "<i class='glyphicon glyphicon-plus'></i> Create",
            action: function(obj) {
              $node = tree.create_node($node, { text: "New Node", url: "" });
              tree.edit($node);
            }
          },
          Rename: {
            separator_before: false,
            separator_after: true,
            label: "<i class='glyphicon glyphicon-pencil'></i> Rename",
            action: function(obj) {
              tree.edit($node);
            }
          },
          EditURL: {
            separator_before: false,
            separator_after: true,
            label: "<i class='glyphicon glyphicon-link'></i> Edit URL",
            action: function(obj) {
              var editUrlPrompt = function() {
                var url = prompt("Enter URL:", $node.original.url || "");
                if (url !== null) {
                  if (url === "") {
                    // Allow empty URL to clear the value
                    tree.get_node($node).original.url = "";
                    updateNodeDisplay($node);
                  } else if (isValidUrl(url)) {
                    tree.get_node($node).original.url = url;
                    updateNodeDisplay($node);
                  } else {
                    alert("Please enter a valid URL.");
                    editUrlPrompt(); // Show the prompt again
                  }
                }
              };
              editUrlPrompt();
            }
          },

          Delete: {
            separator_before: false,
            separator_after: false,
            label: "<i class='glyphicon glyphicon-remove'></i> Delete",
            action: function(obj) {
              if (tree.is_selected($node)) {
                tree.delete_node(tree.get_selected());
              } else {
                tree.delete_node($node);
              }
            }
          }
        };
      }
    }
  });

  // Create jsTree for hidden modules
  $("#hidden-modules").jstree({
    core: {
      data: allModules[0],
      themes: {
        icons: false
      },
      check_callback: true
    },
    plugins: ["dnd", "wholerow", "search", "unique"]
  });
}

/**
 * Remove existing jsTree menu
 */
function removeMenu() {
  var tree = $("#stic-menu-manager").jstree(true);
  if (tree) {
    tree.destroy();
  }
}

function updateNodeDisplay(node) {
  var tree = $("#stic-menu-manager").jstree(true);
  var nodeObj = tree.get_node(node);
  var displayText = nodeObj.text.split("|")[0]; // Remove any existing URL display
  if (nodeObj.original.url) {
    displayText += "|" + nodeObj.original.url;
  }
  tree.rename_node(node, displayText);
}

// Function to validate URL
function isValidUrl(string) {
  try {
    new URL(string);
    return true;
  } catch (_) {
    return false;
  }
}
