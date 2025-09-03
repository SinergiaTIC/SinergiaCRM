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
        enabledStudioModules: STIC.enabledStudioModules,
        enabledModules: STIC.enabledModules,

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
    // let rootNode = {
    //     id: rootModule_name,
    //     text: rootModule_text,
    //     children: true, // Indicates that it CAN have children, which Jstree will lazy load.
    //     state: { opened: false }, // Ensures the root node is initially closed.
    //     data: { 
    //         moduleName: rootModule_name,
    //         path: [rootModule_name],
    //         pathText: [rootModule_text],
    //     }
    // };

    let rootNodes = [];
    for (const [moduleName, module] of Object.entries(window.alpineComponent.enabledStudioModules)) {
        rootNodes.push({
            id: moduleName,
            text: module.text,
            children: true, // Indicates that it CAN have children, which Jstree will lazy load.
            state: { opened: false }, // Ensures the root node is initially closed.
            data: { 
                moduleName: moduleName,
                path: [moduleName],
                pathText: [module.text],
            }
        });
    }
    rootNodes.sort(function(a,b) {
        if (a.text > b.text) return 1;
        if (a.text < b.text) return -1;
        return 0;
    });

    
    // Initialize jstree on the designated div.
    $tree.jstree({
        core: {
            data: function (node, cb) {
                // If node.id is '#', Jstree is asking for first-level nodes (tree root).
                if (node.id === '#') {
                    //cb.call(this, [rootNode]);
                    cb.call(this, rootNodes);
                } else {
                    // An existing node has been expanded, and Jstree is asking for its children.
                    /* 
                        Result: [ name, text, 
                                fields: [name, text, type, required, inViews],
                                relationships: [name, text, fieldName, relationship, moduleName, moduleText] ]
                    */
                    let moduleInfo = getModuleInformation(node.data?.moduleName);
                    if (!moduleInfo) {
                        return;
                    }
                    let childrenNodes = [];
                    // Convert the object of related modules to an array of Jstree nodes.
                    for (let key in moduleInfo.relationships) {
                        // Skip fields related to disabled modules
                        if (!window.alpineComponent.enabledModules.hasOwnProperty(moduleInfo.relationships[key].moduleName)) {
                            continue;
                        }
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
                    childrenNodes.sort(function(a,b) {
                        if (a.data.pathText > b.data.pathText) return 1;
                        if (a.data.pathText < b.data.pathText) return -1;
                        if (a.text > b.text) return 1;
                        if (a.text < b.text) return -1;
                        return 0;
                    });                
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
        getModuleInformation(data.node.data?.moduleName);
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
    // Do not get info of not enabled modules
    if (!moduleName || !window.alpineComponent.enabledModules.hasOwnProperty(moduleName)) {
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


