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

// Global variable to store the TinyMCE editor instance.
var tinyMCEEditorInstance = null; //

// Global variable to store the jstree instance.
var jstreeInstance = null; //

// Global variable to store the path of selected modules (breadcrumb).
var moduleBreadcrumbPath = []; //

/**
 * Dynamically loads the jstree CSS file if not already present.
 */
function loadJstreeCss() {
    // Check if jstree CSS is already loaded.
    if (!$('link[href="SticInclude/vendor/jstree/themes/default/style.min.css"]').length) { //
        // Append link tag to head to load CSS.
        $('<link/>', {
            rel: 'stylesheet',
            type: 'text/css',
            href: 'SticInclude/vendor/jstree/themes/default/style.min.css'
        }).appendTo('head'); //
    }
}

/**
 * Creates and positions the main panel divs (tree, fields, and breadcrumbs).
 */
function createModulePanelDivs() {
    // Check if the main container already exists.
    if ($('#stic_module_panel_container').length === 0) { //
        // Find the TinyMCE textarea to insert elements before it.
        var $bodyTextarea = $('[name="body"]'); //
        if ($bodyTextarea.length > 0) { //
            // Create the main flex container for tree and fields.
            var $mainContainer = $('<div id="stic_module_panel_container" style="display: flex; border: 1px solid #ccc; background-color: #f9f9f9; max-height: 350px; overflow: hidden;"></div>'); //
            
            // Create the tree container (60% width).
            var $treeContainer = $('<div id="stic_module_tree_container" style="flex: 3; padding: 5px; overflow-y: auto; border-right: 1px solid #eee;"></div>'); //
            var $treeDiv = $('<div id="stic_module_tree"></div>'); //
            $treeContainer.append($treeDiv); //

            // Create the fields list container (40% width).
            var $fieldsContainer = $('<div id="stic_fields_list_container" style="flex: 2; padding: 5px; overflow-y: auto;"></div>'); //
            
            // Append tree and fields containers to the main container.
            $mainContainer.append($treeContainer).append($fieldsContainer); //
            // Insert the main container before the TinyMCE textarea.
            $bodyTextarea.before($mainContainer); //

            // Create and position the main breadcrumb div.
            var $breadcrumbDiv = $('<div id="stic_module_breadcrumb" style="width: 100%; padding: 5px; background-color: #e0e0e0; border: 1px solid #ccc; border-bottom: none; font-size: 0.9em; color: #333; box-sizing: border-box; text-align: left;"></div>'); //
            // Create and position the technical breadcrumb div.
            var $technicalBreadcrumbDiv = $('<div id="stic_module_technical_breadcrumb" style="width: 100%; padding: 3px 5px; background-color: #f0f0f0; border: 1px solid #ccc; border-top: none; font-size: 0.8em; color: #666; box-sizing: border-box; text-align: left;"></div>'); //
            
            // Insert both breadcrumbs before the main panel container.
            $mainContainer.before($technicalBreadcrumbDiv); //
            $technicalBreadcrumbDiv.before($breadcrumbDiv); //
        } else {
            // Log warning if textarea is not found.
            // console.warn("No se encontró el textarea 'body' para insertar el contenedor del panel.");
        }
    }
}

// Check if TinyMCE is defined.
if (typeof tinymce !== 'undefined') { //
    // Load jstree CSS.
    loadJstreeCss(); //

    // Create the main panel divs (tree + fields).
    createModulePanelDivs(); //



    // Initialize editor functions based on TinyMCE's active state.
    if (tinymce.activeEditor) { //
        editorReadyFunctions(tinymce.activeEditor); //
    } else {
        window.tinyMceInitCallbacks = window.tinyMceInitCallbacks || []; //
        window.tinyMceInitCallbacks.push(editorReadyFunctions); //
    }
}

/**
 * Executes functions once the TinyMCE editor is ready.
 * @param {object} editor The TinyMCE editor instance.
 */
function editorReadyFunctions(editor) {
    // Store the editor instance globally.
    tinyMCEEditorInstance = editor; //

    // Get the main module value and text from the form.
    var mainModuleVal = $('#main_module').val(); //
    var mainModuleText = $('#main_module').find("option:selected").text(); //
    
    // Initialize jstree and render the fields list.
    initializeModuleTree(mainModuleVal); //
    renderFieldsList(mainModuleVal, mainModuleText); //

    // Initialize the breadcrumbs.
    updateBreadcrumbs(mainModuleVal, mainModuleText); //
}

// Event listener for changes in the main module dropdown.
$('#main_module').on('change', function () { //
    // Get new main module value and text.
    var newMainModuleVal = $(this).val(); //
    var newMainModuleText = $(this).find("option:selected").text(); //

    // Destroy existing jstree instance if it exists.
    if (jstreeInstance) { //
        jstreeInstance.destroy(); //
    }
    // Re-initialize jstree and render fields list for the new module.
    initializeModuleTree(newMainModuleVal); //
    renderFieldsList(newMainModuleVal, newMainModuleText); //

    // Update the breadcrumbs for the new main module.
    updateBreadcrumbs(newMainModuleVal, newMainModuleText); //
});

/**
 * Retrieves a list of modules or fields related to the specified module via AJAX.
 * @param {string} moduleName The name of the module to retrieve relationships for.
 * @param {string} [modulesOrFields='modules'] Specifies whether to retrieve 'modules' or 'fields'.
 * @returns {object} An object of module names or field options.
 */
function getModuleOrFieldList(moduleName, modulesOrFields = 'modules') {
    var list = {}; //
    if (!moduleName) { //
        // console.warn("getModuleOrFieldList: No moduleName provided.");
        return list; //
    }

    // Perform synchronous AJAX request.
    $.ajax({
        url: location.href.slice(0, location.href.indexOf(location.search)), //
        type: "POST", //
        async: false, //
        dataType: "json", //
        data: {
            module: "stic_Signatures", //
            action: "getRelationships", //
            getmodule: moduleName, //
            format: "json", //
        },
        success: function (response) { //
            // Process the JSON response.
            if (response) { //
                if (modulesOrFields === 'fields' && response.option) { //
                    list = response.option; //
                } else if (modulesOrFields === 'modules' && response.module) { //
                    list = response.module; //
                } else {
                    // console.error("Failed to retrieve " + modulesOrFields + " list. Response:", response);
                }
            } else {
                // console.error("Empty response when retrieving " + modulesOrFields + " list for module: " + moduleName);
            }
        },
        error: function (xhr, status, error) { //
            // console.error("Error retrieving " + modulesOrFields + " list for module: " + moduleName, status, error);
            // Check for specific var_dump error from PHP.
            if (xhr.responseText.includes("var_dump")) { //
                // console.error("ERROR! AJAX response contains 'var_dump'. Remove var_dump and die() from action_getRelationships in controller.php.");
            }
        }
    });

    return list; //
}

/**
 * Initializes the module tree using jstree.
 * @param {string} mainModule The main module for the root node of the tree.
 */
function initializeModuleTree(mainModule) {
    if (!mainModule) { //
        // console.warn("No main module provided to initialize the tree.");
        $('#stic_module_tree').empty(); //
        return; //
    }

    // Destroy existing jstree instance and clear the container.
    if (jstreeInstance) { //
        jstreeInstance.destroy(); //
        $('#stic_module_tree').empty(); //
    }

    // Prepare the root node. It will only contain the main module.
    // Its children (related modules) will be loaded on first expansion.
    var rootNode = {
        id: mainModule, //
        text: $('#main_module').find("option:selected").text(), //
        children: true, // Indicates that it CAN have children, which Jstree will lazy load.
        state: { opened: false }, // Ensures the root node is initially closed.
        data: { moduleName: mainModule } //
    };
    
    // Initialize jstree on the designated div.
    $('#stic_module_tree').jstree({
        'core': {
            'data': function (node, cb) { //
                // console.log("Jstree core.data called for node:", node.id); 

                // If node.id is '#', Jstree is asking for first-level nodes (tree root).
                if (node.id === '#') { //
                    cb.call(this, [rootNode]); //
                } else {
                    // An existing node has been expanded, and Jstree is asking for its children.
                    // console.log("Jstree requesting children for node:", node.id, "Associated module:", node.data.moduleName);
                    var relatedModules = getModuleOrFieldList(node.data.moduleName, 'modules'); //
                    // console.log("Response from getModuleOrFieldList for", node.data.moduleName, ":", relatedModules);
                    var childrenNodes = []; //
                    // Convert the object of related modules to an array of Jstree nodes.
                    for (var key in relatedModules) { //
                        if (relatedModules.hasOwnProperty(key)) { //
                            // Filter out the child node if its ID is identical to the parent's ID.
                            if (key === node.id) { //
                                // console.warn("Jstree: Duplicate child ID detected matching parent ID. Ignoring node:", key);
                                continue; //
                            }

                            var actualChildModuleName = key.split(':')[0]; //
                            childrenNodes.push({
                                id: key, //
                                text: relatedModules[key], //
                                children: true, // Indicates this child can also have its own sub-children.
                                data: { moduleName: actualChildModuleName } //
                            });
                        }
                    }
                    // console.log("Formatted child nodes for Jstree:", childrenNodes);
                    
                    cb.call(this, childrenNodes); //
                }
            },
            'check_callback': true, // Allows modifying the tree (e.g., adding/removing nodes).
            'themes': {
                'icons': false, // Disable node icons.
                'responsive': false, // Disable responsive themes.
            }
        },
        'plugins': ["wholerow"] // Makes the entire row clickable.
    })
    .on('select_node.jstree', function (e, data) { //
        var selectedNode = data.node; //
        var moduleName = selectedNode.data.moduleName; // Base module name.
        var nodeId = selectedNode.id; // Full node ID (e.g., 'Accounts:link_name:timestamp').
        var nodeText = selectedNode.text; // Visible node text (e.g., 'Accounts : Contact').

        // console.log("Jstree node selected:", nodeId, "Module:", moduleName);

        // Populate the fields list for the selected module.
        renderFieldsList(moduleName, nodeText); //

        // Update the breadcrumbs for the selected node.
        updateBreadcrumbs(nodeId, nodeText); //
        
        // If the node is not a leaf (can be expanded).
        if (!jstreeInstance.is_leaf(selectedNode)) { //
            // If it's closed, open it (which will trigger children loading).
            if (!jstreeInstance.is_open(selectedNode)) { //
                // console.log("Opening node:", selectedNode.id);
                jstreeInstance.open_node(selectedNode.id); //
            } else { 
                // If it's already open, close it.
                // console.log("Closing node:", selectedNode.id);
                jstreeInstance.close_node(selectedNode.id); //
            }
        } else {
            // console.log("Leaf node, no expansion/collapse needed.");
        }
    })
    .on('ready.jstree', function() { //
        // Store the jstree instance.
        jstreeInstance = $('#stic_module_tree').jstree(true); //
    })
    // Debugging events for load/open operations (commented out for production).
    .on('after_open.jstree', function(e, data) { //
        // console.log("Node OPENED:", data.node.id, "Children loaded:", data.node.children ? data.node.children.length : 0);
    })
    .on('load_node.jstree', function(e, data) { //
        // console.log("Node LOADED (data obtained):", data.node.id, "Children obtained:", data.node.children ? data.node.children.length : 0);
        if (data.node.children && data.node.children.length === 0 && data.node.id !== '#') { //
            // console.warn("Node loaded without children. Arrow might disappear.");
        }
    });
}

/**
 * Updates both the display and technical breadcrumbs for modules.
 * @param {string} selectedNodeId The full ID of the selected node (e.g., 'Module:link_name:timestamp' or 'ModuleName').
 * @param {string} selectedNodeText The visible text of the selected node (e.g., 'Module', 'Module : Relation').
 */
function updateBreadcrumbs(selectedNodeId, selectedNodeText) {
    var $displayBreadcrumbDiv = $('#stic_module_breadcrumb'); //
    var $technicalBreadcrumbDiv = $('#stic_module_technical_breadcrumb'); //

    // Ensure breadcrumb divs exist before attempting to update.
    if ($displayBreadcrumbDiv.length === 0 || $technicalBreadcrumbDiv.length === 0) { //
        // console.warn("One or both breadcrumb divs do not exist. Cannot update.");
        return; //
    }

    if (jstreeInstance && selectedNodeId) { //
        var pathIds = jstreeInstance.get_path(selectedNodeId, false, true); // Get array of node IDs including timestamps.
        var pathTexts = []; //
        var pathTechnicalIds = []; // New array for technical IDs (without timestamp).

        // Convert node IDs to visible texts for the display breadcrumb.
        // And extract technical IDs for the technical breadcrumb.
        for (var i = 0; i < pathIds.length; i++) { //
            var node = jstreeInstance.get_node(pathIds[i]); //
            if (node) { //
                pathTexts.push(node.text); // Add visible text.
                // Extract the technical ID by removing the last ':timestamp' part.
                var technicalId = node.id;
                // Use a regex to robustly check if it ends with ':digits'
                if (/:(\d+)$/.test(technicalId)) { 
                    technicalId = technicalId.substring(0, technicalId.lastIndexOf(':')); // Remove timestamp
                }
                pathTechnicalIds.push(technicalId); // Add technical ID.
            }
        }
        
        // Update display breadcrumb.
        $displayBreadcrumbDiv.text(pathTexts.join(' > ')); //
        // Update technical breadcrumb with processed node IDs.
        $technicalBreadcrumbDiv.text(pathTechnicalIds.join(' :: ')); //

        // Store the path IDs globally for potential future use (e.g., 'back' button logic).
        moduleBreadcrumbPath = pathIds; //
        
        // console.log("Breadcrumb updated:", pathTexts.join(' > '));
        // console.log("Technical Breadcrumb updated:", pathTechnicalIds.join(' > '));
    } else if (selectedNodeId && selectedNodeText) {
        // Fallback for initial load if jstreeInstance is not ready yet.
        $displayBreadcrumbDiv.text(selectedNodeText); //
        // For the initial selected node, its ID might also contain a timestamp.
        var initialTechnicalId = selectedNodeId;
        if (/:(\d+)$/.test(initialTechnicalId)) { // Check if it ends with a number (timestamp)
            initialTechnicalId = initialTechnicalId.substring(0, initialTechnicalId.lastIndexOf(':'));
        }
        $technicalBreadcrumbDiv.text(initialTechnicalId); //
        moduleBreadcrumbPath = [selectedNodeId]; //
        // console.log("Breadcrumb initialized:", selectedNodeText);
        // console.log("Technical Breadcrumb initialized:", initialTechnicalId);
    } else {
        // Clear breadcrumbs if no module is selected.
        $displayBreadcrumbDiv.empty(); //
        $technicalBreadcrumbDiv.empty(); //
        moduleBreadcrumbPath = []; //
        // console.log("Breadcrumbs cleared.");
    }
}

/**
 * Normalizes a string for case-insensitive and accent-insensitive comparison.
 * @param {string} str The string to normalize.
 * @returns {string} The normalized string.
 */
function normalizeString(str) {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase(); //
}

/**
 * Renders the list of fields for the specified module in the side panel.
 * Includes a module name header and a searchable, sortable list of fields.
 * @param {string} moduleName The name of the module to retrieve fields for.
 * @param {string} moduleDisplayText The text to display for the module name (e.g., "Sessions', 'Accounts : Contact').
 */
function renderFieldsList(moduleName, moduleDisplayText) {
    var $fieldsContainer = $('#stic_fields_list_container'); //
    $fieldsContainer.empty(); // Clear previous content.

    // Display a message if no module is selected.
    if (!moduleName) { //
        $fieldsContainer.append($('<p id="stic_no_module_selected_msg" style="padding: 10px;">Select a module to view its fields.</p>')); //
        return; //
    }

    // Add bold header with the selected module's display name.
    var $moduleNameHeader = $('<div id="stic_fields_module_header" style="padding: 5px; font-weight: bold; border-bottom: 1px solid #eee;"></div>'); //
    $moduleNameHeader.text(moduleDisplayText); //
    $fieldsContainer.append($moduleNameHeader); //

    // Get fields for the module.
    var fieldsObject = getModuleOrFieldList(moduleName, 'fields'); //
    var fieldsArray = []; //

    // Convert fields object to an array for sorting.
    for (var key in fieldsObject) { //
        if (fieldsObject.hasOwnProperty(key) && key !== "") { //
            fieldsArray.push({ id: key, text: fieldsObject[key] }); //
        }
    }

    // Sort fields alphabetically by their display text (case and accent insensitive).
    fieldsArray.sort(function(a, b) { //
        var textA = normalizeString(a.text); //
        var textB = normalizeString(b.text); //
        if (textA < textB) { //
            return -1; //
        }
        if (textA > textB) { //
            return 1; //
        }
        return 0; //
    });

    // Add search input.
    var $searchContainer = $('<div id="stic_fields_search_container" style="padding: 5px; border-bottom: 1px solid #eee;"></div>'); //
    var $searchInput = $('<input type="text" id="stic_field_search_input" placeholder="Search field..." style="width: calc(100% - 10px); padding: 5px; border: 1px solid #ccc; border-radius: 3px;">'); //
    $searchContainer.append($searchInput); //
    $fieldsContainer.append($searchContainer); //

    // Create the unordered list for fields, with scroll if content overflows.
    var $ul = $('<ul id="stic_fields_list_ul"></ul>').css({
        'list-style': 'none', //
        'padding': '0', //
        'margin': '0', //
        'max-height': 'calc(100% - 80px)', // Adjust height for header and search input.
        'overflow-y': 'auto' // Enable vertical scrolling.
    });
    $fieldsContainer.append($ul); //

    /**
     * Displays a filtered list of fields in the UL element.
     * @param {Array<object>} filteredFields The array of field objects to display.
     */
    function displayFields(filteredFields) {
        $ul.empty(); // Clear existing list items.
        if (filteredFields.length === 0) { //
            $ul.append($('<li id="stic_no_fields_found_msg" style="padding: 5px;">No fields found.</li>')); //
            return; //
        }
        // Append each field as a list item.
        filteredFields.forEach(function(field) { //
            var $li = $('<li class="stic-field-list-item"></li>').css({
                'padding': '5px 0', //
                'cursor': 'pointer', //
                'border-bottom': '1px dotted #eee' //
            });
            $li.text(field.text); //
            
            // Attach click event to insert field into TinyMCE editor.
            $li.data('fieldKey', field.id); // Store field key.
            $li.data('fieldDisplayText', field.text); // Store display text for span content
            $li.on('click', function() { //
                var fieldId = $(this).data('fieldKey'); // e.g., $stic_payment_commitments:banking_concept
                var fieldDisplayText = $(this).data('fieldDisplayText'); // e.g., "Concepto Bancario"

                // ** MODIFICACIÓN CLAVE AQUÍ: Construir el ID del span con la ruta técnica **
                var pathTechnicalParts = [];
                // Process each ID in moduleBreadcrumbPath for the 'link:' parts.
                // moduleBreadcrumbPath contains IDs with timestamps if applicable.
                for (var i = 0; i < moduleBreadcrumbPath.length; i++) {
                    var currentPathId = moduleBreadcrumbPath[i];
                    // Remove timestamp from the current ID
                    if (/:(\d+)$/.test(currentPathId)) { 
                        currentPathId = currentPathId.substring(0, currentPathId.lastIndexOf(':')); 
                    }
                    // Add 'link:' prefix
                    pathTechnicalParts.push('link:' + currentPathId);
                }

                // Add the field part with 'field:' prefix
                var cleanFieldId = fieldId.startsWith('$') ? fieldId.substring(1) : fieldId;
                pathTechnicalParts.push('field:' + cleanFieldId);

                // Join all parts with '::'
                var fullTechnicalPath = pathTechnicalParts.join('::');

                // Construct the HTML span element.
                // The 'id' attribute needs to be a valid HTML ID (no '$' or '::').
                // We'll create a sanitised ID for the HTML 'id' attribute.
                var sanitizedSpanId = fullTechnicalPath.replace(/[^a-zA-Z0-9_-]/g, '_');
                if (/^\d/.test(sanitizedSpanId)) {
                    sanitizedSpanId = 'id_' + sanitizedSpanId;
                }
                
                // The visible content is the field's display text wrapped in {}.
                // The 'title' attribute will show the full technical path.
                var contentToInsert = '<span style="border:.2px dotted #f49b0c;background-color:#fcd17b" class="field" id="' + sanitizedSpanId + '" title="' + fullTechnicalPath + '">{' + fieldDisplayText + '}</span>&nbsp;'; // Added &nbsp;
                
                if (tinyMCEEditorInstance && contentToInsert) { 
                    tinyMCEEditorInstance.insertContent(contentToInsert); 
                    // ** MODIFICACIÓN CLAVE: Posicionar el cursor después del span **
                    var editor = tinyMCEEditorInstance;
                    editor.once('SetContent', function() { // Ensure span is rendered before moving cursor
                        var content = editor.getContent();
                        var lastSpanIndex = content.lastIndexOf('<span class="field"');
                        if (lastSpanIndex !== -1) {
                            var tempDiv = document.createElement('div');
                            tempDiv.innerHTML = content.substring(0, lastSpanIndex);
                            var textNodeCount = tempDiv.textContent.length;
                            
                            // Re-insert content to ensure TinyMCE's internal DOM is consistent
                            // This also helps with cursor positioning.
                            editor.setContent(content); 

                            // Get the last inserted span using its ID
                            var insertedSpan = editor.dom.select('#' + sanitizedSpanId)[0];
                            if (insertedSpan) {
                                // Create a new text node after the span to place the cursor
                                var textNode = editor.dom.doc.createTextNode('\u00a0'); // Non-breaking space
                                editor.dom.insertAfter(textNode, insertedSpan);
                                
                                // Set the caret position
                                var range = editor.dom.createRng();
                                range.setStartAfter(textNode);
                                range.collapse(true);
                                editor.selection.setRng(range);
                            }
                        }
                    });
                }
            });
            
            $ul.append($li); 
        });
    }

    // Display all fields initially.
    displayFields(fieldsArray); 

    // Add keyup event listener to the search input for live filtering.
    $searchInput.on('keyup', function() { 
        var searchTerm = normalizeString($(this).val()); 
        var filtered = fieldsArray.filter(function(field) { 
            return normalizeString(field.text).includes(searchTerm); 
        });
        displayFields(filtered); 
    });
}

/**
 * Placeholder function for renaming TinyMCE's module button (now obsolete).
 * This function is kept for backward compatibility if other scripts still reference it.
 * @param {string} newName The name that would have been used.
 */
function renameModulesDropdownButton(newName) {
    return; // Do nothing as the button is obsolete.
}