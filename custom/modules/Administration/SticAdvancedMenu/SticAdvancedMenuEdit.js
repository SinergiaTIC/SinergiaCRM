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

    handleTreeChanges();
  });

  // Change text and id when renaming a node
  $("#stic-menu-manager").on("rename_node.jstree", function(e, data) {
    var tree = $("#stic-menu-manager").jstree(true);
    var newId = data.text.replace(/\s+/g, "_") + "__" + Math.random().toString(36).substr(2, 4);

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

  /**
 * Handles the deletion of nodes from the stic-menu-manager tree.
 * When a node is deleted, it and its descendants are added to the hidden-modules tree.
 */
  $("#stic-menu-manager").on("delete_node.jstree", function(e, data) {
    /**
   * Recursively collects node data and its descendants into a flat array.
   * @param {Object} node - The node to process.
   * @returns {Array} An array of node data objects.
   */
    function getNodeDataArray(node) {
      var nodeDataArray = [];

      function addNodeToArray(n) {
        var nodeData = {
          id: n.id,
          text: n.text,
          parent: n.parent
        };

        // Add other relevant properties
        if (n.data) {
          nodeData.data = n.data;
        }
        if (n.original) {
          nodeData.original = n.original;
        }

        nodeDataArray.push(nodeData);

        // Process children iteratively
        if (n.children && n.children.length > 0) {
          n.children.forEach(function(childId) {
            var childNode = data.instance.get_node(childId);
            addNodeToArray(childNode);
          });
        }
      }

      addNodeToArray(node);
      return nodeDataArray;
    }

    // Get data of the deleted node and its descendants
    var deletedNodesArray = getNodeDataArray(data.node);

    // Add each deleted node to the hidden-modules tree
    deletedNodesArray.forEach(element => {
      addNewItemToHiddenModules({
        id: element.id,
        text: element.text
      });
    });

    // Log the deleted nodes data
    console.log("Nodes deleted from stic-menu-manager:", deletedNodesArray);

    // If you need to return this array for use elsewhere:
    // return deletedNodesArray;
  });
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
                var urlOld=tree.get_node($node).id?.split('|')[1]?.split('__')[0] || ''
                var url = prompt(
                  SUGAR.language.languages.Administration.LBL_STIC_MENU_COMMAND_EDITURL_PROMPT + ":",
                  urlOld || ""
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
              console.log("eliminando");
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
          // Prevent any movement inside the tree
          return false;
        }
        // Allow other operations (such as copying out)
        return true;
      }
    },
    plugins: ["dnd", "wholerow", "search", "unique"],
    dnd: {
      is_draggable: function(nodes) {
        // Allow to drag nodes out, but not inside the tree
        return true;
      },
      copy: false, // Allow to copy nodes instead of moving them
      always_copy: false // Always copy, never move
    }
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

/**
 * Adds a new item to the hidden modules tree and applies a highlight effect.
 * 
 * @param {Object} nodeData - The data for the new node.
 * @param {string} nodeData.id - The unique identifier for the node.
 * @param {string} nodeData.text - The display text for the node.
 * @param {string} [nodeData.url] - Optional URL associated with the node.
 * @returns {string|null} The ID of the newly created node, or null if creation failed.
 */
function addNewItemToHiddenModules(nodeData) {
  // Verify required properties
  if (!nodeData.id || !nodeData.text) {
    console.error("Properties 'id' and 'text' are required to create a new node");
    return null;
  }

  var hiddenModulesTree = $.jstree.reference("#hidden-modules");

  if (!hiddenModulesTree) {
    console.error("Could not find the hidden-modules tree");
    return null;
  }

  // Check if the node already exists
  if (hiddenModulesTree.get_node(nodeData.id)) {
    return null;
  }

  // Prepare the display text
  var displayText = nodeData.text + (nodeData.url ? "|" + nodeData.url : "");

  // Create the new node
  var newNode = hiddenModulesTree.create_node("#", {
    id: nodeData.id,
    text: displayText,
    data: nodeData,
    original: nodeData
  });

  if (newNode) {
    // Apply highlight effect
    setTimeout(function() {
      var $node = $("#" + newNode);
      $node.css({
        transition: "background-color 0.5s ease",
        "background-color": "#B5BC31" // Light yellow color
      });
      setTimeout(function() {
        $node.css({
          transition: "background-color 1s ease",
          "background-color": ""
        });
      }, 300);
    }, 100);

    sortHiddenModulesAlphabetically();
    return newNode;
  } else {
    console.error("Failed to create new node in hidden-modules");
    return null;
  }
}

/**
 * Sorts the nodes in the hidden modules tree alphabetically by their text.
 */
function sortHiddenModulesAlphabetically() {
  var hiddenModulesTree = $.jstree.reference("#hidden-modules");

  if (!hiddenModulesTree) {
    console.error("Could not find the hidden-modules tree");
    return;
  }

  // Get all top-level nodes
  var topLevelNodes = hiddenModulesTree.get_node("#").children;

  // Sort nodes alphabetically by text
  topLevelNodes.sort(function(a, b) {
    var nodeA = hiddenModulesTree.get_node(a);
    var nodeB = hiddenModulesTree.get_node(b);

    // Extract text without URL (if exists)
    var textA = nodeA.text.split("|")[0].trim().toLowerCase();
    var textB = nodeB.text.split("|")[0].trim().toLowerCase();

    return textA.localeCompare(textB);
  });

  // Reorder nodes in the tree
  for (var i = 0; i < topLevelNodes.length; i++) {
    hiddenModulesTree.move_node(topLevelNodes[i], "#", i);
  }

  // Refresh the tree to show the new order
  hiddenModulesTree.redraw(true);
}
