function wizardForm(readOnly) {
    return {
        step: 1,
        totalSteps: 4,
        steps: [],
        stepTitle:"",
    
        isReadOnly: readOnly,
    
        bean: STIC.record || {},
        configDataBlocks: {},
    
        modStrings: SUGAR.language.languages.stic_Advanced_Web_Forms,
        appStrings: SUGAR.language.languages.app_strings,
        appListStrings: SUGAR.language.languages.app_list_strings,

        treeSelectedNode: {},

        async init() {
            // Set Context accessible
            window.alpineComponent = this;

            // Split configs
            let allConfig = JSON.parse(this.bean?.config_json || '{}');
            if (allConfig.hasOwnProperty("dataBlocks")) {
                this.configDataBlocks = allConfig.dataBlocks;
            }

            // Load current Step
            this.loadStep();
        },
    
        async loadStep() {
            if(this.step <= this.totalSteps && this.steps.length < this.step+1) {
                this.steps[this.step] = await (await fetch(`modules/stic_Advanced_Web_Forms/wizard/steps/step${this.step}.html`)).text();
            }
            this.stepTitle = this.modStrings["LBL_WIZARD_TITLE_STEP" + this.step];
            document.getElementById('wizard-step-container').innerHTML = this.steps[this.step];
            $("#wizard-section-title").text(this.stepTitle + " (" + this.step + "/" + this.totalSteps + ")");
        },
    
        enablePrevStep() {
            return this.step > 1;
        },
        enableNextStep() {
            return this.step < this.totalSteps;
        },
        nextStep() { 
            if (this.enableNextStep()) {
                this.step++;
                this.autoSave(); 
                this.loadStep();
            };
        },
        prevStep() { 
            if (this.enablePrevStep()) {
                this.step--; 
                this.loadStep();
            };
        },
    
        autoSave() {
            // Join Configs
            let config = {
                dataBlocks: this.configDataBlocks,
            }
            fetch('index.php?module=stic_Advanced_Web_Forms&action=saveDraft', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({bean: this.bean, config: config, step: this.step})
            });
        },
    
        finish() {
            if (!this.isReadOnly) {
                fetch('index.php?module=stic_Advanced_Web_Forms&action=finalizeConfig', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({beanData: this.beanData, config: this.config, step: this.step})
                }).then(() => location.reload());
            }
        }
    };
}

function set_wizard_assigned_user(popup_reply_data) {
    window.alpineComponent.bean.assigned_user_id = popup_reply_data.name_to_value_array.assigned_user_id;
    window.alpineComponent.bean.assigned_user_name = popup_reply_data.name_to_value_array.assigned_user_name;
}

// Global variable to store the jstree instance.
var jstreeInstance = null;

function initializeModuleTree($tree) {
    // Destroy existing jstree instance and clear the container.
    if (jstreeInstance) {
        jstreeInstance.destroy();
    }
    if ($tree == null || $tree.length == 0) {
        return;
    }
    $tree.empty();

    let rootModule_name = window.alpineComponent.bean.base_module;
    let rootModule_text = window.alpineComponent.appListStrings.moduleList[rootModule_name];

    // Prepare the root node. It will only contain the main module.
    // Its children (related modules) will be loaded on first expansion.
    let rootNode = {
        id: rootModule_name,
        text: rootModule_text,
        children: true, // Indicates that it CAN have children, which Jstree will lazy load.
        state: { opened: false }, // Ensures the root node is initially closed.
        data: { 
            moduleName: rootModule_name,
            path: [rootModule_name],
            pathText: [rootModule_text],
        }
    };
    
    // Initialize jstree on the designated div.
    $tree.jstree({
        core: {
            data: function (node, cb) {
                // If node.id is '#', Jstree is asking for first-level nodes (tree root).
                if (node.id === '#') {
                    cb.call(this, [rootNode]);
                } else {
                    // An existing node has been expanded, and Jstree is asking for its children.
                    /* 
                        Result: [ name, text, 
                                fields: [name, text, type, required, inViews],
                                relationships: [name, text, fieldName, relationship, moduleName, moduleText] ]
                    */
                    let moduleInfo = getModuleInformation(node.data.moduleName);
                    if (!moduleInfo) {
                        return;
                    }
                    let childrenNodes = [];
                    // Convert the object of related modules to an array of Jstree nodes.
                    for (let key in moduleInfo.relationships) {
                        let newPath = [...node.data.path]
                        newPath.push(moduleInfo.relationships[key].moduleName);
                        
                        let newPathText = [...node.data.pathText];
                        newPathText.push(moduleInfo.relationships[key].text);

                        let isLoop = node.data.path.includes(moduleInfo.relationships[key].moduleName);
                        childrenNodes.push({
                            id: node.id + "-" + key,
                            text: moduleInfo.relationships[key].text + " <sup>(" + moduleInfo.relationships[key].moduleText + ")</sup>",
                            children: !isLoop,
                            data: { 
                                moduleName: moduleInfo.relationships[key].moduleName,
                                path: newPath,
                                pathText: newPathText,
                            }
                        });
                    }
                    cb.call(this, childrenNodes);
                }
            },
            check_callback: true, // Allows modifying the tree (e.g., adding/removing nodes).
            themes: {
                icons: false, // Disable node icons.
                responsive: false, // Disable responsive themes.
                dots: true,
            }
        },
        plugins: ["wholerow"] // Makes the entire row clickable.
    })    
    .on('ready.jstree', function() {
        // Store the jstree instance.
        jstreeInstance = $('#tree-container').jstree(true);
    })
    .on('select_node.jstree', function (e, data) {
        window.alpineComponent.treeSelectedNode = data.node;
    });
    // // Debugging events for load/open operations (commented out for production).
    // .on('after_open.jstree', function(e, data) {
    //     // console.log("Node OPENED:", data.node.id, "Children loaded:", data.node.children ? data.node.children.length : 0);
    // })
    // .on('load_node.jstree', function(e, data) {
    //     // console.log("Node LOADED (data obtained):", data.node.id, "Children obtained:", data.node.children ? data.node.children.length : 0);
    //     if (data.node.children && data.node.children.length === 0 && data.node.id !== '#') {
    //         // console.warn("Node loaded without children. Arrow might disappear.");
    //     }
    // });
}

// Global variable to store cached modules information
var cachedModules = {};


function getModuleInformation(moduleName) {
    if (!moduleName) {
        return null;
    }
    
    if (!cachedModules.hasOwnProperty(moduleName)) {
        $.ajax({
            url: "index.php", //location.href.slice(0, location.href.indexOf(location.search)),
            type: "POST",
            async: false,
            dataType: "json",
            data: {
                module: "stic_Advanced_Web_Forms",
                action: "getModuleInformation",
                getmodule: moduleName,
            },
            success: function (response) { 
                cachedModules[moduleName] = response; 
            },
            error: function (xhr, status, error) {
                console.error("Error retrieving Information for module: '" + moduleName + "'", status, error);
            }
        });
    }

    if (cachedModules.hasOwnProperty(moduleName)) {
        return cachedModules[moduleName];
    }

    return null;
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
    var fieldsObject = getModuleInformation(moduleName, 'fields'); //
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
