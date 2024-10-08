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
  removeDisabledObjects(menu);
  createMenu();

  // Restore default menu
  $("#restore-menu").on("click", function() {
    if (confirm(SUGAR.language.languages.Administration.LBL_STIC_MENU_RESTORE_CONFIRM) == false) {
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
        module: "Administration",
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
        module: "Administration",
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
    dnd: {
      copy: false
    },
    contextmenu: {
      items: function($node) {
        var tree = $("#stic-menu-manager").jstree(true);
        return {
          Create: {
            separator_before: false,
            separator_after: true,
            label:
              "<i class='glyphicon glyphicon-plus'></i>" +
              SUGAR.language.languages.Administration.LBL_STIC_MENU_COMMAND_CREATE,
            action: function(obj) {
              $node = tree.create_node($node, {
                text: SUGAR.language.languages.Administration.LBL_STIC_MENU_COMMAND_CREATE_DEFAULT,
                url: ""
              });
              tree.edit($node);
            }
          },

          Rename: {
            separator_before: false,
            separator_after: true,
            label:
              "<i class='glyphicon glyphicon-pencil'></i>" +
              SUGAR.language.languages.Administration.LBL_STIC_MENU_COMMAND_RENAME,
            action: function(obj) {
              tree.edit($node);
            }
          },
          Duplicate: {
            separator_before: false,
            separator_after: false,
            label:
              "<i class='glyphicon glyphicon-duplicate'></i>" +
              SUGAR.language.languages.Administration.LBL_STIC_MENU_COMMAND_DUPLICATE,
            action: function(obj) {
              var nodeData = tree.get_json($node, { no_state: true, no_id: false, no_children: false, no_data: false });

              function generateCustomId(baseId) {
                var randomSuffix = Math.random().toString(36).substr(2, 4);
                return baseId + "__" + randomSuffix;
              }

              function duplicateNodeRecursively(node) {
                var nodeCopy = $.extend(true, {}, node);

                // Generate a new custom ID
                nodeCopy.id = generateCustomId(node.id.split("__")[0]);

                if (typeof nodeCopy.text === "string") {
                  nodeCopy.text = nodeCopy.text.split("|")[0];
                } else if (nodeCopy.original && typeof nodeCopy.original.text === "string") {
                  nodeCopy.text = nodeCopy.original.text.split("|")[0];
                } else {
                  nodeCopy.text = "_";
                }

                if (nodeCopy.original && nodeCopy.original.url) {
                  nodeCopy.data = { url: nodeCopy.original.url };
                }

                if (nodeCopy.children && nodeCopy.children.length > 0) {
                  nodeCopy.children = nodeCopy.children.map(duplicateNodeRecursively);
                }

                return nodeCopy;
              }

              var duplicatedNode = duplicateNodeRecursively(nodeData);

              // Get the current node index
              var siblings = tree.get_children_dom(tree.get_parent($node));
              var currentIndex = siblings.index(tree.get_node($node, true));

              // Create the new node after the current node
              var newNodeId = tree.create_node(tree.get_parent($node), duplicatedNode, currentIndex + 1);

              if (newNodeId) {
                tree.deselect_all();
                tree.select_node(newNodeId);
                console.log("Nuevo nodo creado:", newNodeId);

                function updateNodeDisplayRecursively(nodeId) {
                  var node = tree.get_node(nodeId);
                  if (node.data && node.data.url) {
                    var displayText = node.text + "|" + node.data.url;
                    tree.rename_node(node, displayText);
                  }
                  var children = tree.get_children_dom(nodeId);
                  children.each(function() {
                    updateNodeDisplayRecursively(this.id);
                  });
                }

                updateNodeDisplayRecursively(newNodeId);
                handleTreeChanges();
              } else {
                console.error("No se pudo crear el nuevo nodo");
              }
            },
            _disabled: function(data) {
              return false; // Allow duplication for all nodes
            }
          },
          EditURL: {
            separator_before: false,
            separator_after: true,
            label:
              "<i class='glyphicon glyphicon-link'></i>" +
              SUGAR.language.languages.Administration.LBL_STIC_MENU_COMMAND_EDITURL,
            action: function(obj) {
              var editUrlPrompt = function() {
                var url = prompt(
                  SUGAR.language.languages.Administration.LBL_STIC_MENU_COMMAND_EDITURL_PROMPT + ":",
                  $node.original.url || ""
                );
                if (url !== null) {
                  if (url === "") {
                    // Allow empty URL to clear the value
                    tree.get_node($node).original.url = "";
                    updateNodeDisplay($node);
                  } else if (isValidUrl(url)) {
                    tree.get_node($node).original.url = url;
                    updateNodeDisplay($node);
                  } else {
                    alert(SUGAR.language.languages.Administration.LBL_STIC_MENU_COMMAND_EDITURL_PROMPT_VALIDATE);
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
            label:
              "<i class='glyphicon glyphicon-remove'></i>" +
              SUGAR.language.languages.Administration.LBL_STIC_MENU_COMMAND_REMOVE,
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
      check_callback: function(operation, node, node_parent, node_position, more) {
        if (operation === "move_node") {
          //Allow movement only if the father node is the root node
          return node_parent.id === "#";
        }
      }
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

// Actualizar la función updateNodeDisplay para manejar IDs
function updateNodeDisplay(nodeId) {
  var tree = $("#stic-menu-manager").jstree(true);
  var nodeObj = tree.get_node(nodeId);
  if (nodeObj) {
    var displayText = nodeObj.text ? nodeObj.text.split("|")[0] : "Nodo sin título";
    if (nodeObj.original && nodeObj.original.url) {
      displayText += "|" + nodeObj.original.url;
    }
    tree.rename_node(nodeId, displayText);
  }
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

/**
 * Recursively removes objects with the property 'disabled' set to true from an array.
 * This function modifies the original array and its nested structures.
 * 
 * @param {Array} arr - The array to process.
 * @returns {Array} The modified array with disabled objects removed.
 * 
 */
function removeDisabledObjects(arr) {
  for (let i = arr.length - 1; i >= 0; i--) {
    let element = arr[i];

    if (Array.isArray(element)) {
      // If the element is an array, recursively call the function
      removeDisabledObjects(element);
      // If the array became empty after recursion, remove it
      if (element.length === 0) {
        arr.splice(i, 1);
      }
    } else if (typeof element === "object" && element !== null) {
      // If the element is an object, check if it has the disabled property set to true
      if (element.hasOwnProperty("disabled") && element.disabled === true) {
        arr.splice(i, 1);
        continue;
      }
      // If the object has properties that are arrays or objects, process them recursively
      for (let prop in element) {
        if (Array.isArray(element[prop])) {
          removeDisabledObjects(element[prop]);
          if (element[prop].length === 0) {
            delete element[prop];
          }
        } else if (typeof element[prop] === "object" && element[prop] !== null) {
          if (removeDisabledObjects([element[prop]]).length === 0) {
            delete element[prop];
          }
        }
      }
    }
  }
  return arr;
}

/**
 * Creates a new main node in the jsTree.
 * 
 * This function generates a new node with a unique ID and adds it
 * to the root level of the tree as the last item.
 */
function newMainNode() {
  var tree = $("#stic-menu-manager").jstree(true);
  var text = SUGAR.language.languages.Administration.LBL_STIC_MENU_COMMAND_CREATE_DEFAULT;
  var newNode = {
    id: text + "__" + Math.random().toString(36).substr(2, 4),
    text: text
  };

  tree.create_node("#", newNode, "last");
}

/**
 * Expands all nodes in the jsTree.
 * 
 * This function attempts to open all nodes in the tree. If successful,
 * it logs a success message. If an error occurs, it logs the error.
 * If the tree is not found, it logs a warning.
 */
function expandAll() {
  var $tree = $("#stic-menu-manager");
  if ($tree.length) {
    try {
      $tree.jstree("open_all");
      console.log("Tree fully expanded");
    } catch (error) {
      console.error("Error expanding the tree:", error);
    }
  } else {
    console.warn("jsTree not found");
  }
}

/**
 * Collapses all nodes in the jsTree.
 * 
 * This function attempts to close all nodes in the tree. If successful,
 * it logs a success message. If an error occurs, it logs the error.
 * If the tree is not found, it logs a warning.
 */
function collapseAll() {
  var $tree = $("#stic-menu-manager");
  if ($tree.length) {
    try {
      $tree.jstree("close_all");
      console.log("Tree fully collapsed");
    } catch (error) {
      console.error("Error collapsing the tree:", error);
    }
  } else {
    console.warn("jsTree not found");
  }
}
